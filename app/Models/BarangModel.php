<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
   protected $table         = 'barang';
   protected $useTimestamps = true;
   protected $allowedFields = [
      'faktur', 'id_produk', 'nama_produk', 'stok', 'harga', 'tipe'
   ];

   public function getIdFaktur()
   {
      date_default_timezone_set('Asia/Jakarta');
      $this->select("MAX(RIGHT(faktur,4)) AS last_faktur");
      $this->where('tipe', 'masuk');
      $this->where("DATE(created_at) = CURDATE()");
      if ($this->countAllResults(false) > 0) {
         foreach ($this->get()->getResult() as $row) {
            $tmp = ((int)$row->last_faktur + 1);
            $new_faktur = sprintf('%04s', $tmp);
         }
      } else {
         $new_faktur = '0001';
      }
      return date('dmy') . $new_faktur;
   }

   public function range($range)
   {
      switch ($range) {
         case 'hari_ini':
            $this->where('DATE(created_at)', 'CURDATE()', false);
            break;
         case 'kemarin':
            $this->where('YEAR(created_at)', 'YEAR(CURDATE())', false);
            $this->where('MONTH(created_at)', 'MONTH(CURDATE())', false);
            $this->where('DAY(created_at)', 'DAY(CURDATE() - INTERVAL 1 DAY)', false);
            // $this->where('DATE(created_at)', 'SUBDATE(CURDATE(), 1)', false);
            break;
         case 'bulan_ini':
            $this->where('YEAR(created_at)', 'YEAR(CURDATE())', false);
            $this->where('MONTH(created_at)', 'MONTH(CURDATE())', false);
            // $this->where('SUBDATE(CURDATE(), DAY(created_at))', 'SUBDATE(CURDATE(), DAY(CURDATE()) - 1)', false);
            break;
         case 'bulan_kemarin':
            $this->where('YEAR(created_at)', 'YEAR(CURDATE())', false);
            $this->where('MONTH(created_at)', 'MONTH(CURDATE() - INTERVAL 1 MONTH)', false);
            break;
         case 'tahun_ini':
            $this->where('YEAR(created_at)', 'YEAR(CURDATE())', false);
            break;
         case 'tahun_kemarin':
            $this->where('YEAR(created_at)', 'YEAR(CURDATE() - INTERVAL 1 YEAR)', false);
            break;
         default:
            # code...
            break;
      }
   }

   public function getTotal($data)
   {
      helper('number');
      $total = 0;
      foreach ($data as $row) {
         $total = $total + $row['harga'];
      }
      return number_to_currency($total, 'IDR', 'id_ID');
   }

   public function getCountAllGoods($tipe)
   {
      $this->where('tipe', $tipe);
      return $this->countAllResults();
   }

   public function getCountFilterGoods($search, $tipe, $range = '')
   {
      $this->range($range);
      $this->where('tipe', $tipe);
      $this->havinglike('nama_produk', $search);
      $this->orHavingLike('stok', $search);
      $this->orHavingLike('harga', $search);
      $this->orHavingLike('created_at', $search);
      return $this->countAll();
   }

   public function filterGoods($search, $limit, $start, $field, $orderby, $tipe, $range = '')
   {
      $this->range($range);
      $this->where('tipe', $tipe);
      $this->havinglike('nama_produk', $search);
      $this->orHavingLike('stok', $search);
      $this->orHavingLike('harga', $search);
      $this->orHavingLike('created_at', $search);
      $this->orderBy($field, $orderby);
      $this->limit($limit, $start);
      // echo $this->getCompiledSelect();
      return $this->get()->getResultArray();
   }

   public function addInGoods($data)
   {
      return $this->insert($data);
   }

   public function addOutGoods($data)
   {
      foreach ($data as $row) {
         if (!$this->insert($row)) {
            return false;
         }
      }
      return true;
   }

   public function deleteGoods($data)
   {
      $this->where($data);
      if(!$this->delete()){
         return false;
      }
      return true;
   }

   public function getSimplyReports($tipe)
   {
      helper('number');

      $this->where('tipe', $tipe);
      $this->range('hari_ini');
      $hari_ini = $this->countAllResults();

      $this->where('tipe', $tipe);
      $this->range('hari_ini');
      $transaksi_hari_ini = 0;
      foreach ($this->get()->getResult() as $row) {
         $transaksi_hari_ini = $transaksi_hari_ini + $row->harga;
      }

      $this->where('tipe', $tipe);
      $this->range('tahun_ini');
      $transaksi_tahun_ini = 0;
      foreach ($this->get()->getResult() as $row) {
         $transaksi_tahun_ini = $transaksi_tahun_ini + $row->harga;
      }

      $this->where('tipe', $tipe);
      $total_transaksi = 0;
      foreach ($this->get()->getResult() as $row) {
         $total_transaksi = $total_transaksi + $row->harga;
      }

      return [
         "hari_ini" => $hari_ini,
         "transaksi_hari_ini" => number_to_currency($transaksi_hari_ini, 'IDR', 'id_ID'),
         "transaksi_tahun_ini" => number_to_currency($transaksi_tahun_ini, 'IDR', 'id_ID'),
         "total_transaksi" => number_to_currency($total_transaksi, 'IDR', 'id_ID')
      ];
   }
}
