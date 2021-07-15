<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\StrukModel;
use App\Models\DetailStrukModel;
use App\Models\ProdukModel;

class Struk extends BaseController
{
   protected $session;
   protected $m_Struk;
   protected $m_DetailStruk;
   protected $m_Produk;
   protected $m_Barang;

   public function __construct()
   {
      date_default_timezone_set('Asia/Jakarta');
      $this->session = session();
      $this->m_Struk = new StrukModel();
      $this->m_DetailStruk = new DetailStrukModel();
      $this->m_Produk = new ProdukModel();
      $this->m_Barang = new BarangModel();
   }

   public function index()
   {
      $data = [
         'title' => 'Riwayat Pesanan',
         'dataSession' => $this->session->get()
      ];
      return view('riwayatpesanan', $data);
   }

   public function newOrders()
   {
      helper('number');
      $search = $_POST['search']['value'];
      $limit = $_POST['length'];
      $start = $_POST['start'];
      $index = $_POST['order'][0]['column'];
      $field = $_POST['columns'][$index]['data'];
      $orderby = $_POST['order'][0]['dir'];
      $where = "DATE(struk.created_at) = CURDATE()";
      $totalOrders = $this->m_Struk->getCountAllOrders($where);
      $filterOrders = $this->m_Struk->filterOrders($search, $limit, $start, $field, $orderby, $where);
      $totalFilterOrders = $this->m_Struk->getCountFilterOrders($search, $where);
      $rows = array();
      foreach ($filterOrders as $row) {
         $total_bayar = number_to_currency($row['total_bayar'], 'IDR', 'id_ID');
         unset($row['total_bayar']);
         $rows[] = $row + array('total_bayar' => $total_bayar);
      }
      $data = [
         "draw" => $_POST['draw'],
         "recordsTotal" => $totalOrders,
         "recordsFiltered" => $totalFilterOrders,
         "data" => $rows
      ];
      return json_encode($data);
   }

   public function historyOrders()
   {
      helper('number');
      $search = $_POST['search']['value'];
      $limit = $_POST['length'];
      $start = $_POST['start'];
      $index = $_POST['order'][0]['column'];
      $field = $_POST['columns'][$index]['data'];
      $orderby = $_POST['order'][0]['dir'];
      $totalOrders = $this->m_Struk->getCountAllOrders();
      $findOrders = $this->m_Struk->filterOrders($search, $limit, $start, $field, $orderby);
      $totalFilterOrders = $this->m_Struk->getCountFilterOrders($search);
      $total_pendapatan = $this->m_Struk->getTotal($findOrders);
      $rows = array();
      foreach ($findOrders as $row) {
         $total_bayar = number_to_currency($row['total_bayar'], 'IDR', 'id_ID');
         unset($row['total_bayar']);
         $rows[] = $row + array('total_bayar' => $total_bayar);
      }
      $data = [
         'draw' => $_POST['draw'],
         'recordsTotal' => $totalOrders,
         'recordsFiltered' => $totalFilterOrders,
         'data' => $rows,
         'total_pendapatan' => $total_pendapatan
      ];
      return json_encode($data);
   }

   public function strukDetail($faktur)
   {
      $data = [
         "title" => "Struk Detail",
         "data" => $this->m_Struk->getStrukDetail($faktur),
         "dataSession" => $this->session->get()
      ];
      return view('strukdetail', $data);
   }

   public function saveStruk()
   {
      $faktur = $this->m_Struk->getIdFaktur();
      $data1 = [
         "data" => $this->request->getPost(['nama_produk', 'harga', 'qty', 'subtotal']),
         "faktur" => $faktur
      ];
      $data2 = [
         "data" => $this->request->getPost(['fee', 'pay']),
         "faktur" => $faktur
      ];
      $data3 = $this->request->getPost(['id', 'qty']);
      $data4 = [];
      for ($i = 0; $i < count($this->request->getPost('id')); $i++) {
         $data4[] = [
            "faktur" => $faktur,
            "id_produk" => $this->request->getPost('id')[$i],
            "nama_produk" => $this->request->getPost('nama_produk')[$i],
            "stok" => $this->request->getPost('qty')[$i],
            "harga" => preg_replace('/\D/', '', $this->request->getPost('subtotal')[$i]),
            "tipe" => "keluar"
         ];
      }
      if(!$this->m_Produk->updateProducts($data3)) {
         return json_encode([
            "error" => true,
            "msg" => "Stok kurang untuk melakukan transaksi."
         ]);
      }
      if ($this->m_DetailStruk->saveDetailStruk($data1) && $this->m_Struk->saveStruk($data2) && $this->m_Barang->addOutGoods($data4)) {
         return json_encode([
            "error" => false,
            "msg" => "Struk berhasil ditambahkan."
         ]);
      } else {
         return json_encode([
            "error" => true,
            "msg" => "Struk gagal ditambahkan."
         ]);
      }
   }

   public function deleteOrder($faktur)
   {
      if ($this->m_Struk->deleteOrder($faktur) && $this->m_DetailStruk->deleteOrder($faktur) && $this->m_Barang->deleteGoods(['faktur' => $faktur, 'tipe' => 'keluar'])) {
         return json_encode([
            "error" => false,
            "msg" => "Pesanan berhasil dihapus."
         ]);
      } else {
         return json_encode([
            "error" => true,
            "msg" => "Pesanan gagal dihapus."
         ]);
      }
   }
}
