<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use App\Models\DetailPembelian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanPembelianController extends Controller
{
    /**
     * Display the purchase report page with filters and statistics
     */
    public function index(Request $request)
    {
        // Base query with relationships
        $query = Pembelian::with(['supplier', 'user', 'detailPembelian.barang']);

        // Apply filters
        $this->applyFilters($query, $request);

        // Get filtered data
        $pembelian = $query->orderBy('tanggal', 'desc')->get();

        // Calculate statistics
        $statistics = $this->calculateStatistics($pembelian, $request);

        // Get data for filter dropdowns
        $suppliers = Supplier::orderBy('nama_supplier')->get();
        $barangs = Barang::orderBy('nama_barang')->get();

        return view('laporan.pembelian.index', [
    'pembelian' => $pembelian,
    'suppliers' => $suppliers,
    'barangs' => $barangs,
    'filters' => $request->all(),
    'statistics' => $statistics
]);

    }

    /**
     * Export purchase report to PDF
     */
    public function exportPdf(Request $request)
    {
        // Base query with relationships
        $query = Pembelian::with(['supplier', 'user', 'detailPembelian.barang']);

        // Apply same filters as index method
        $this->applyFilters($query, $request);

        // Get filtered data
        $pembelian = $query->orderBy('tanggal', 'desc')->get();

        // Calculate statistics for PDF
        $statistics = $this->calculateStatistics($pembelian, $request);

        // Prepare data for PDF
        $data = [
            'pembelian' => $pembelian,
            'statistics' => $statistics,
            'filters' => $request->all(),
            'printed_at' => now()->format('d/m/Y H:i:s'),
            'period' => $this->getPeriodText($request)
        ];

        // Generate PDF
        $pdf = PDF::loadView('laporan.pembelian.pdf', $data);
        $pdf->setPaper('A4', 'landscape');

        // Generate filename
        $filename = 'laporan-pembelian-' . date('Y-m-d-H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Get purchase report data for AJAX requests
     */
    public function getData(Request $request)
    {
        try {
            $query = Pembelian::with(['supplier', 'user', 'detailPembelian.barang']);

            // Apply filters
            $this->applyFilters($query, $request);

            // Get data with pagination if requested
            if ($request->has('per_page')) {
                $pembelian = $query->orderBy('tanggal', 'desc')
                                 ->paginate($request->get('per_page', 15));
            } else {
                $pembelian = $query->orderBy('tanggal', 'desc')->get();
            }

            // Calculate statistics
            $statistics = $this->calculateStatistics($query->get(), $request);

            return response()->json([
                'success' => true,
                'data' => $pembelian,
                'statistics' => $statistics
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get purchase summary by period
     */
    public function getSummaryByPeriod(Request $request)
    {
        try {
            $period = $request->get('period', 'month'); // day, week, month, year

            $query = Pembelian::with(['detailPembelian']);

            // Apply date range if provided
            if ($request->filled('tanggal_dari')) {
                $query->whereDate('tanggal', '>=', $request->tanggal_dari);
            }

            if ($request->filled('tanggal_sampai')) {
                $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
            }

            // Group by period
            switch ($period) {
                case 'day':
                    $groupBy = DB::raw('DATE(tanggal)');
                    $selectDate = DB::raw('DATE(tanggal) as periode');
                    break;
                case 'week':
                    $groupBy = DB::raw('YEARWEEK(tanggal)');
                    $selectDate = DB::raw('YEARWEEK(tanggal) as periode');
                    break;
                case 'year':
                    $groupBy = DB::raw('YEAR(tanggal)');
                    $selectDate = DB::raw('YEAR(tanggal) as periode');
                    break;
                default: // month
                    $groupBy = DB::raw('YEAR(tanggal), MONTH(tanggal)');
                    $selectDate = DB::raw('DATE_FORMAT(tanggal, "%Y-%m") as periode');
                    break;
            }

            $summary = $query->select(
                $selectDate,
                DB::raw('COUNT(*) as total_transaksi'),
                DB::raw('SUM((SELECT SUM(jumlah * harga) FROM detail_pembelian WHERE detail_pembelian.id_pembelian = pembelian.id_pembelian)) as total_nilai')
            )
            ->groupBy($groupBy)
            ->orderBy('periode', 'desc')
            ->get();

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get top suppliers by purchase value
     */
    public function getTopSuppliers(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);

            $query = Supplier::with(['pembelian.detailPembelian'])
                ->whereHas('pembelian');

            // Apply date filter if provided
            if ($request->filled('tanggal_dari') || $request->filled('tanggal_sampai')) {
                $query->whereHas('pembelian', function($q) use ($request) {
                    if ($request->filled('tanggal_dari')) {
                        $q->whereDate('tanggal', '>=', $request->tanggal_dari);
                    }
                    if ($request->filled('tanggal_sampai')) {
                        $q->whereDate('tanggal', '<=', $request->tanggal_sampai);
                    }
                });
            }

            $suppliers = $query->get()->map(function($supplier) {
                $totalNilai = $supplier->pembelian->sum(function($pembelian) {
                    return $pembelian->detailPembelian->sum(function($detail) {
                        return $detail->jumlah * $detail->harga;
                    });
                });

                return [
                    'id' => $supplier->id_supplier,
                    'nama' => $supplier->nama_supplier,
                    'total_transaksi' => $supplier->pembelian->count(),
                    'total_nilai' => $totalNilai
                ];
            })->sortByDesc('total_nilai')->take($limit)->values();

            return response()->json([
                'success' => true,
                'data' => $suppliers
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get top purchased items
     */
    public function getTopItems(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);

            $query = DetailPembelian::with(['barang', 'pembelian'])
                ->select('kode_barang')
                ->selectRaw('SUM(jumlah) as total_jumlah')
                ->selectRaw('SUM(jumlah * harga) as total_nilai')
                ->selectRaw('COUNT(DISTINCT id_pembelian) as total_transaksi');

            // Apply date filter through pembelian relationship
            if ($request->filled('tanggal_dari') || $request->filled('tanggal_sampai')) {
                $query->whereHas('pembelian', function($q) use ($request) {
                    if ($request->filled('tanggal_dari')) {
                        $q->whereDate('tanggal', '>=', $request->tanggal_dari);
                    }
                    if ($request->filled('tanggal_sampai')) {
                        $q->whereDate('tanggal', '<=', $request->tanggal_sampai);
                    }
                });
            }

            $items = $query->groupBy('kode_barang')
                ->orderByDesc('total_jumlah')
                ->limit($limit)
                ->get()
                ->map(function($item) {
                    return [
                        'kode_barang' => $item->kode_barang,
                        'nama_barang' => $item->barang->nama_barang ?? '-',
                        'total_jumlah' => $item->total_jumlah,
                        'total_nilai' => $item->total_nilai,
                        'total_transaksi' => $item->total_transaksi
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $items
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Apply filters to query
     */
    private function applyFilters($query, Request $request)
    {
        // Filter by supplier
        if ($request->filled('supplier')) {
            $query->where('id_supplier', $request->supplier);
        }

        // Filter by date range
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        // Filter by item/barang
        if ($request->filled('barang')) {
            $query->whereHas('detailPembelian', function($q) use ($request) {
                $q->where('kode_barang', $request->barang);
            });
        }

        // Filter by user
        if ($request->filled('user')) {
            $query->where('id_user', $request->user);
        }

        // Filter by purchase ID
        if ($request->filled('id_pembelian')) {
            $query->where('id_pembelian', 'like', '%' . $request->id_pembelian . '%');
        }

        // Filter by minimum total value
        if ($request->filled('min_total')) {
            $query->whereHas('detailPembelian', function($q) use ($request) {
                $q->havingRaw('SUM(jumlah * harga) >= ?', [$request->min_total]);
            });
        }

        // Filter by maximum total value
        if ($request->filled('max_total')) {
            $query->whereHas('detailPembelian', function($q) use ($request) {
                $q->havingRaw('SUM(jumlah * harga) <= ?', [$request->max_total]);
            });
        }
    }

    /**
     * Calculate statistics from filtered data
     */
    private function calculateStatistics($pembelian, Request $request = null)
    {
        $total_pembelian = $pembelian->count();

        $total_item = $pembelian->sum(function($item) {
            return $item->detailPembelian->sum('jumlah');
        });

        $total_nilai = $pembelian->sum(function($item) {
            return $item->detailPembelian->sum(function($detail) {
                return $detail->jumlah * $detail->harga;
            });
        });

        $supplier_aktif = $pembelian->unique('id_supplier')->count();

        // Calculate average per transaction
        $rata_rata_per_transaksi = $total_pembelian > 0 ? $total_nilai / $total_pembelian : 0;

        // Calculate percentage change if date range is provided
        $persentase_perubahan = 0;
        if ($request && $request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $persentase_perubahan = $this->calculatePercentageChange($request);
        }

        // Get most active supplier
        $supplier_teraktif = $pembelian->groupBy('id_supplier')
            ->map(function($group) {
                return [
                    'supplier' => $group->first()->supplier,
                    'total_transaksi' => $group->count(),
                    'total_nilai' => $group->sum(function($item) {
                        return $item->detailPembelian->sum(function($detail) {
                            return $detail->jumlah * $detail->harga;
                        });
                    })
                ];
            })
            ->sortByDesc('total_transaksi')
            ->first();

        return [
            'total_pembelian' => $total_pembelian,
            'total_item' => $total_item,
            'total_nilai' => $total_nilai,
            'supplier_aktif' => $supplier_aktif,
            'rata_rata_per_transaksi' => $rata_rata_per_transaksi,
            'persentase_perubahan' => $persentase_perubahan,
            'supplier_teraktif' => $supplier_teraktif
        ];
    }

    /**
     * Calculate percentage change compared to previous period
     */
    private function calculatePercentageChange(Request $request)
    {
        try {
            $tanggalDari = Carbon::parse($request->tanggal_dari);
            $tanggalSampai = Carbon::parse($request->tanggal_sampai);
            $selisihHari = $tanggalDari->diffInDays($tanggalSampai) + 1;

            // Calculate previous period
            $tanggalDariSebelum = $tanggalDari->copy()->subDays($selisihHari);
            $tanggalSampaiSebelum = $tanggalDari->copy()->subDay();

            // Get current period data
            $currentQuery = Pembelian::with('detailPembelian')
                ->whereDate('tanggal', '>=', $request->tanggal_dari)
                ->whereDate('tanggal', '<=', $request->tanggal_sampai);

            $currentTotal = $currentQuery->get()->sum(function($item) {
                return $item->detailPembelian->sum(function($detail) {
                    return $detail->jumlah * $detail->harga;
                });
            });

            // Get previous period data
            $previousQuery = Pembelian::with('detailPembelian')
                ->whereDate('tanggal', '>=', $tanggalDariSebelum->format('Y-m-d'))
                ->whereDate('tanggal', '<=', $tanggalSampaiSebelum->format('Y-m-d'));

            $previousTotal = $previousQuery->get()->sum(function($item) {
                return $item->detailPembelian->sum(function($detail) {
                    return $detail->jumlah * $detail->harga;
                });
            });

            // Calculate percentage change
            if ($previousTotal > 0) {
                return (($currentTotal - $previousTotal) / $previousTotal) * 100;
            }

            return $currentTotal > 0 ? 100 : 0;

        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get period text for display
     */
    private function getPeriodText(Request $request)
    {
        $dari = $request->filled('tanggal_dari') ?
                Carbon::parse($request->tanggal_dari)->format('d/m/Y') :
                'Awal';

        $sampai = $request->filled('tanggal_sampai') ?
                  Carbon::parse($request->tanggal_sampai)->format('d/m/Y') :
                  'Sekarang';

        return $dari . ' s/d ' . $sampai;
    }

    /**
     * Clear filters and redirect to clean report page
     */
    public function clearFilters()
    {
        return redirect()->route('laporan.pembelian.index')
                        ->with('success', 'Filter berhasil direset');
    }

    /**
     * Get purchase report statistics for dashboard widget
     */
    public function getWidgetStats()
    {
        try {
            // Get current month data
            $currentMonth = Pembelian::with('detailPembelian')
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->get();

            $totalBulanIni = $currentMonth->sum(function($item) {
                return $item->detailPembelian->sum(function($detail) {
                    return $detail->jumlah * $detail->harga;
                });
            });

            // Get previous month data for comparison
            $previousMonth = Pembelian::with('detailPembelian')
                ->whereMonth('tanggal', now()->subMonth()->month)
                ->whereYear('tanggal', now()->subMonth()->year)
                ->get();

            $totalBulanLalu = $previousMonth->sum(function($item) {
                return $item->detailPembelian->sum(function($detail) {
                    return $detail->jumlah * $detail->harga;
                });
            });

            // Calculate percentage change
            $persentasePerubahan = 0;
            if ($totalBulanLalu > 0) {
                $persentasePerubahan = (($totalBulanIni - $totalBulanLalu) / $totalBulanLalu) * 100;
            } elseif ($totalBulanIni > 0) {
                $persentasePerubahan = 100;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'total_bulan_ini' => $totalBulanIni,
                    'total_bulan_lalu' => $totalBulanLalu,
                    'persentase_perubahan' => round($persentasePerubahan, 2),
                    'jumlah_transaksi' => $currentMonth->count()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

}
