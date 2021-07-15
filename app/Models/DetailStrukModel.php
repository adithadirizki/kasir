<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailStrukModel extends Model
{
   protected $table         = 'struk_detail';
   protected $useTimestamps = true;
   protected $allowedFields = [
      'faktur', 'nama_produk', 'harga', 'kuantitas', 'subtotal'
   ];

   public function saveDetailStruk($data)
   {
      for ($i = 0; $i < count($data['data']['nama_produk']); $i++) {
         $insert = [
            'faktur' => $data['faktur'],
            'nama_produk' => $data['data']['nama_produk'][$i],
            'harga' => preg_replace('/\D/', '', $data['data']['harga'][$i]),
            'kuantitas' => $data['data']['qty'][$i],
            'subtotal' => preg_replace('/\D/', '', $data['data']['subtotal'][$i])
         ];
         if (!$this->insert($insert)) {
            return false;
         }
      }
      return true;
   }

   public function deleteOrder($faktur)
   {
      $this->where('faktur', $faktur);
      return $this->delete();
   }
}
