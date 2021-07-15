<?php

namespace App\Models;

use CodeIgniter\Model;

class StrukModel extends Model
{
   protected $table         = 'struk';
   protected $useTimestamps = true;
   protected $allowedFields = [
      'faktur', 'fee', 'pay'
   ];

   public function getIdFaktur()
   {
      date_default_timezone_set('Asia/Jakarta');
      $this->select("MAX(RIGHT(faktur,4)) AS last_faktur");
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

   public function saveStruk($data)
   {
      return $this->save([
         "faktur" => $data['faktur'],
         "fee" => preg_replace('/\D/', '', $data['data']['fee']),
         "pay" => preg_replace('/\D/', '', $data['data']['pay'])
      ]);
   }

   public function deleteOrder($faktur)
   {
      $this->where('faktur', $faktur);
      return $this->delete();
   }

   public function joinTable()
   {
      $this->join('struk_detail', 'struk_detail.faktur = struk.faktur', 'inner');
      $this->select("struk.faktur, struk.created_at, SUM(subtotal+fee) as total_bayar, SUM(kuantitas) as item, GROUP_CONCAT(struk_detail.nama_produk SEPARATOR ', ') as produk");
   }

   public function getStrukDetail($faktur)
   {
      $this->join('struk_detail', 'struk_detail.faktur = struk.faktur', 'inner');
      $this->select('*');
      $this->where('struk_detail.faktur', $faktur);
      if ($this->countAllResults(false) < 1) {
         throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
      }
      return $this->get()->getResultArray();
   }

   public function getTotal($data)
   {
      helper('number');
      $total = 0;
      foreach ($data as $row) {
         $total = $total + $row['total_bayar'];
      }
      return number_to_currency($total, 'IDR', 'id_ID');
   }

   public function range($range)
   {
      switch ($range) {
         case 'hari_ini':
            $this->where('DATE(struk.created_at)', 'CURDATE()', false);
            break;
         case 'kemarin':
            $this->where('YEAR(struk.created_at)', 'YEAR(CURDATE())', false);
            $this->where('MONTH(struk.created_at)', 'MONTH(CURDATE())', false);
            $this->where('DAY(struk.created_at)', 'DAY(CURDATE() - INTERVAL 1 DAY)', false);
            // $this->where('DATE(struk.created_at)', 'SUBDATE(CURDATE(), 1)', false);
            break;
         case 'bulan_ini':
            $this->where('YEAR(struk.created_at)', 'YEAR(CURDATE())', false);
            $this->where('MONTH(struk.created_at)', 'MONTH(CURDATE())', false);
            // $this->where('SUBDATE(CURDATE(), DAY(struk.created_at))', 'SUBDATE(CURDATE(), DAY(CURDATE()) - 1)', false);
            break;
         case 'bulan_kemarin':
            $this->where('YEAR(struk.created_at)', 'YEAR(CURDATE())', false);
            $this->where('MONTH(struk.created_at)', 'MONTH(CURDATE() - INTERVAL 1 MONTH)', false);
            break;
         case 'tahun_ini':
            $this->where('YEAR(struk.created_at)', 'YEAR(CURDATE())', false);
            break;
         case 'tahun_kemarin':
            $this->where('YEAR(struk.created_at)', 'YEAR(CURDATE() - INTERVAL 1 YEAR)', false);
            break;
         default:
            # code...
            break;
      }
   }

   public function getCountAllOrders($range = '')
   {
      $this->range($range);
      return $this->countAllResults();
   }

   public function filterOrders($search, $limit, $start, $field, $orderby, $range = '')
   {
      $this->joinTable();
      $this->range($range);
      $this->havingLike('struk.faktur', $search);
      $this->orHavingLike('produk', $search);
      $this->orHavingLike('item', $search);
      $this->orHavingLike('struk.created_at', $search);
      $this->groupBy('struk_detail.faktur');
      $this->orderBy($field, $orderby);
      $this->limit($limit, $start);
      return $this->get()->getResultArray();
   }

   public function getCountFilterOrders($search, $range = '')
   {
      $this->joinTable();
      $this->range($range);
      $this->havingLike('struk.faktur', $search);
      $this->orHavingLike('produk', $search);
      $this->orHavingLike('item', $search);
      $this->orHavingLike('struk.created_at', $search);
      $this->groupBy('struk_detail.faktur');
      return $this->countAllResults();
   }

   public function getSimplyReports()
   {
      helper('number');
      $this->range('hari_ini');
      $pesanan_hari_ini = $this->countAllResults();

      $this->range('bulan_ini');
      $pesanan_bulan_ini = $this->countAllResults();

      $this->joinTable();
      $this->range('hari_ini');
      $pendapatan_hari_ini = 0;
      foreach ($this->get()->getResult() as $row) {
         $pendapatan_hari_ini = $pendapatan_hari_ini + $row->total_bayar;
      }

      $this->joinTable();
      $total_pendapatan = 0;
      foreach ($this->get()->getResult() as $row) {
         $total_pendapatan = $total_pendapatan + $row->total_bayar;
      }
      return [
         "pesanan_hari_ini" => $pesanan_hari_ini,
         "pesanan_bulan_ini" => $pesanan_bulan_ini,
         "pendapatan_hari_ini" => number_to_currency($pendapatan_hari_ini, 'IDR', 'id_ID'),
         "total_pendapatan" => number_to_currency($total_pendapatan, 'IDR', 'id_ID')
      ];
   }
}
