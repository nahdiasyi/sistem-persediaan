<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\DetailPembelian;
use App\Models\DetailPenjualan;
use App\Models\DetailRetur;
use App\Models\Kategori;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->get('month', date('Y-m'));
        $startDate = Carbon::parse($selectedMonth . '-01')->startOfMonth();
        $endDate = Carbon::parse($selectedMonth . '-01')->endOfMonth();
        
        // Data Cards
        $data = [
            'total_barang' => Barang::count(),
            'barang_masuk' => $this->getBarangMasuk($startDate, $endDate),
            'barang_keluar' => $this->getBarangKeluar($startDate, $endDate),
            'total_persediaan' => Barang::sum('stok'),
            'barang_masuk_prev' => $this->getBarangMasuk($startDate->copy()->subMonth(), $endDate->copy()->subMonth()),
            'barang_keluar_prev' => $this->getBarangKeluar($startDate->copy()->subMonth(), $endDate->copy()->subMonth()),
            'selected_month' => $selectedMonth
        ];
        
        // Hitung persentase perubahan
        $data['persen_barang_masuk'] = $this->hitungPersentase($data['barang_masuk'], $data['barang_masuk_prev']);
        $data['persen_barang_keluar'] = $this->hitungPersentase($data['barang_keluar'], $data['barang_keluar_prev']);
        
        // Data untuk chart
        $data['chart_data'] = $this->getChartData($startDate, $endDate);
        $data['kategori_data'] = $this->getKategoriData();
        $data['top_products'] = $this->getTopProducts($startDate, $endDate);
        $data['low_stock'] = $this->getLowStockItems();
        $data['revenue_data'] = $this->getRevenueData($startDate, $endDate);
        
        return view('dashboard', $data);
    }
    
    private function getBarangMasuk($startDate, $endDate)
    {
        return DetailPembelian::whereHas('pembelian', function($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        })->sum('jumlah');
    }
    
    private function getBarangKeluar($startDate, $endDate)
    {
        return DetailPenjualan::whereHas('penjualan', function($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        })->sum('jumlah');
    }
    
    private function hitungPersentase($current, $previous)
    {
        if ($previous == 0) return $current > 0 ? 100 : 0;
        return round((($current - $previous) / $previous) * 100, 1);
    }
    
    private function getChartData($startDate, $endDate)
    {
        $days = [];
        $masuk = [];
        $keluar = [];
        
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $days[] = $date->format('d');
            
            $masukHarian = DetailPembelian::whereHas('pembelian', function($query) use ($date) {
                $query->whereDate('tanggal', $date->format('Y-m-d'));
            })->sum('jumlah');
            
            $keluarHarian = DetailPenjualan::whereHas('penjualan', function($query) use ($date) {
                $query->whereDate('tanggal', $date->format('Y-m-d'));
            })->sum('jumlah');
            
            $masuk[] = $masukHarian;
            $keluar[] = $keluarHarian;
        }
        
        return [
            'labels' => $days,
            'masuk' => $masuk,
            'keluar' => $keluar
        ];
    }
    
    private function getKategoriData()
    {
        return Kategori::withCount('barang')
            ->with(['barang' => function($query) {
                $query->select('id_kategori', DB::raw('SUM(stok) as total_stok'));
                $query->groupBy('id_kategori');
            }])
            ->get()
            ->map(function($kategori) {
                return [
                    'nama' => $kategori->nama_kategori,
                    'jumlah_item' => $kategori->barang_count,
                    'total_stok' => $kategori->barang->sum('total_stok') ?? 0
                ];
            });
    }
    
    private function getTopProducts($startDate, $endDate)
    {
        return DetailPenjualan::select('kode_barang', DB::raw('SUM(jumlah) as total_terjual'))
            ->whereHas('penjualan', function($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            })
            ->with('barang:kode_barang,nama_barang')
            ->groupBy('kode_barang')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get();
    }
    
    private function getLowStockItems()
    {
        return Barang::where('stok', '<', 10)
            ->orWhere('keterangan', 'stok hampir habis')
            ->select('kode_barang', 'nama_barang', 'stok', 'keterangan')
            ->orderBy('stok', 'asc')
            ->limit(10)
            ->get();
    }
    
    private function getRevenueData($startDate, $endDate)
    {
        $totalPenjualan = DetailPenjualan::whereHas('penjualan', function($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        })->sum(DB::raw('jumlah * harga'));
        
        $totalPembelian = DetailPembelian::whereHas('pembelian', function($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        })->sum(DB::raw('jumlah * harga'));
        
        return [
            'total_penjualan' => $totalPenjualan,
            'total_pembelian' => $totalPembelian,
            'profit' => $totalPenjualan - $totalPembelian
        ];
    }
}