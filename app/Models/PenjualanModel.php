<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class PenjualanModel extends Model
{
    protected $table         = 'penjualan';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'faktur', 'nama_pembeli', 'kuantitas'
    ];

    public function getJumlahPenjualan()
    {
        return $this->countAllResults();
    }

    public function getDataNewOrders()
    {
        $this->select('penjualan.*, produk.nama_produk, produk.foto, produk.harga');
        $this->where('DATE(penjualan.created_at) =', 'CURDATE()');
        $this->join('produk', 'produk.id = penjualan.id_produk', 'inner');
        $query = $this->get();
        return $query->getResult();
    }

    public function getJumlahProdukTerjual()
    {
        $this->selectSum('kuantitas');
        $query = $this->get();
        return $query->getResult();
    }
}
