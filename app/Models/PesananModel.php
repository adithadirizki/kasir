<?php

namespace App\Models;

use CodeIgniter\Model;

class PesananModel extends Model
{
   protected $table         = 'pesanan';
   protected $useTimestamps = true;
   protected $allowedFields = [
      'faktur', 'id_produk', 'nama_pambeli', 'kuantitas', 'harga'
   ];

   public function joinTableProduct()
   {
      $this->select('produk.nama_produk, produk.foto, produk.harga, pesanan.faktur, pesanan.nama_pembeli, pesanan.kuantitas');
      $this->join('produk', 'produk.id = pesanan.id_produk', 'inner');
   }
   
   public function getCountAllOrders()
   {
      return $this->countAllResults();
   }

   public function getCountAllNewOrders()
   {
      $this->where('DATE(created_at)', 'CURDATE()');
      return $this->countAllResults();
   }

   public function getCountAllProductSold()
   {
      $this->selectSum('kuantitas');
      return $this->get()->getResult()[0]->kuantitas;
   }

   public function filterOrders($search, $limit, $start, $field, $orderby)
   {
      $this->joinTableProduct();
      $this->like('produk.nama_produk', $search);
      $this->orLike('pesanan.nama_pembeli', $search);
      $this->orLike('pesanan.faktur', $search);
      $this->orderBy($field, $orderby);
      $this->limit($limit, $start);
      return $this->get()->getResultArray();
   }

   public function getCountFilterOrders($search)
   {
      $this->joinTableProduct();
      $this->like('produk.nama_produk', $search);
      $this->orLike('pesanan.nama_pembeli', $search);
      $this->orLike('pesanan.faktur', $search);
      return $this->countAllResults();
   }
}
