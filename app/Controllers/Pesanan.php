<?php

namespace App\Controllers;

use App\Models\ProdukModel;

class Pesanan extends BaseController
{
   protected $session;
   protected $m_Produk;

   public function __construct()
   {
      $this->session = session();
      $this->m_Produk = new ProdukModel();
   }

   public function index()
   {
      $data = [
         'title' => 'Riwayat Pesanan',
         'dataSession' => $this->session->get()
      ];
      return view('riwayatpesanan', $data);
   }

   public function historyOrders()
   {
		$search = $_POST['search']['value'];
		$limit = $_POST['length'];
		$start = $_POST['start'];
		$index = $_POST['order'][0]['column'];
		$field = $_POST['columns'][$index]['data'];
		$orderby = $_POST['order'][0]['dir'];
      
   }

   public function getDataProduk()
   {
      $keyword = $this->request->getPost('keyword') ? $this->request->getPost('keyword') : '';
      echo json_encode($this->m_Produk->getListProduk($keyword));
   }
}
