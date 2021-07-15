<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\ProdukModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Barang extends BaseController
{
   protected $session;
   protected $m_Barang;
   protected $m_Produk;

   public function __construct()
   {
      $this->m_Barang = new BarangModel();
      $this->m_Produk = new ProdukModel();
   }

   public function index()
   {
      $data = [
         "title" => "Barang",
         "produk" => $this->m_Produk->get()->getResultArray(),
         "dataSession" => session()->get()
      ];
      return view('barang', $data);
   }

   public function getGoods($tipe = null)
   {
      if (!in_array($tipe, ["masuk", "keluar"])) {
         throw PageNotFoundException::forPageNotFound();
      }
      $search = $_POST['search']['value'];
      $limit = $_POST['length'];
      $start = $_POST['start'];
      $index = $_POST['order'][0]['column'];
      $field = $_POST['columns'][$index]['data'];
      $orderby = $_POST['order'][0]['dir'];
      $range = isset($_POST['searchByDate']) ? $_POST['searchByDate'] : '';
      $totalGoods = $this->m_Barang->getCountAllGoods($tipe, $range);
      $filterGoods = $this->m_Barang->filterGoods($search, $limit, $start, $field, $orderby, $tipe, $range);
      $totalFilterGoods = $this->m_Barang->getCountFilterGoods($search, $tipe, $range);
      $totalPengeluaran = $this->m_Barang->getTotal($filterGoods);
      $data = [
         "draw" => $_POST['draw'],
         "recordsTotal" => $totalGoods,
         "recordsFiltered" => $totalFilterGoods,
         "data" => $filterGoods,
         "total_pengeluaran" => $totalPengeluaran
      ];
      return json_encode($data);
   }

   public function addInGoods()
   {
      $faktur = $this->m_Barang->getIdFaktur();
      $data1 = [
         "id" => $this->request->getPost('id'),
         "stok" => $this->request->getPost('stok')
      ];
      $data1['harga'] = preg_replace('/\D/', '', $this->request->getPost('harga'));
      $data2 = [
         "faktur" => $faktur,
         "id_produk" => $data1['id'],
         "nama_produk" => $this->m_Produk->getDetailProduct($data1['id'])->nama_produk,
         "stok" => $data1['stok'],
         "harga" => $data1['harga'],
         "tipe" => "masuk"
      ];
      if ($this->m_Produk->addStokProduct($data1) && $this->m_Barang->addInGoods($data2)) {
         return json_encode([
            "error" => false,
            "msg" => "Barang berhasil ditambahkan."
         ]);
      } else {
         return json_encode([
            "error" => true,
            "msg" => "Barang gagal ditambahkan."
         ]);
      }
   }

   public function deleteGoods($tipe, $faktur)
   {
      $data = [
         "faktur" => $faktur,
         "tipe" => $tipe
      ];
      if($this->m_Barang->deleteGoods($data)) {
         return json_encode([
            "error" => false,
            "msg" => "Barang berhasil dihapus."
         ]);
      } else {
         return json_encode([
            "error" => true,
            "msg" => "Barang gagal dihapus."
         ]);
      }
   }
}
