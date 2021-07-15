<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\StrukModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Laporan extends BaseController
{
   protected $session;
   protected $m_Struk;
   protected $m_Barang;

   public function __construct()
   {
      $this->session = session();
      $this->m_Struk = new StrukModel();
      $this->m_Barang = new BarangModel();
   }

   public function reports($tipe)
   {
      switch ($tipe) {
         case 'pesanan':
            return $this->reportsOrders();
            break;
         case 'barangmasuk':
            return $this->reportsInGoods();
            break;
         case 'barangkeluar':
            return $this->reportsOutGoods();
            break;
         default:
            throw PageNotFoundException::forPageNotFound();
            break;
      }
      $data = [
         "title" => "Laporan",
         "dataSession" => $this->session->get()
      ];
      return view('laporan', $data);
   }

   public function reportsOrders()
   {
      $rincian = $this->m_Struk->getSimplyReports();
      $data = [
         "title" => "Laporan Pesanan",
         "simply" => $rincian,
         "dataSession" => $this->session->get()
      ];
      return view('l_pesanan', $data);
   }

   public function reportsInGoods()
   {
      $rincian = $this->m_Barang->getSimplyReports('masuk');
      $data = [
         "title" => "Laporan Barang Masuk",
         "simply" => $rincian,
         "dataSession" => $this->session->get()
      ];
      return view('l_barangmasuk', $data);
   }

   public function reportsOutGoods()
   {
      $rincian = $this->m_Barang->getSimplyReports('keluar');
      $data = [
         "title" => "Laporan Barang Keluar",
         "simply" => $rincian,
         "dataSession" => $this->session->get()
      ];
      return view('l_barangkeluar', $data);
   }
}
