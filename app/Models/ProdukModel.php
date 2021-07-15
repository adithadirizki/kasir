<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table         = 'produk';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'nama_produk', 'foto', 'cat_id', 'stok', 'harga'
    ];

    public function joinTableCategory()
    {
        $this->select('produk.*, kategori.nama_kategori, kategori.id as cat_id');
        $this->join('kategori', 'kategori.id = produk.cat_id', 'inner');
    }

    public function getDetailProduct($id)
    {
        $this->where('id', $id);
        return $this->get()->getFirstRow();
    }

    public function getCountAllProducts()
    {
        return $this->countAllResults();
    }

    public function getFilterProducts($search, $limit, $start, $field, $orderby)
    {
        $this->joinTableCategory();
        $this->like('produk.nama_produk', $search);
        $this->orLike('produk.id', $search);
        $this->orLike('produk.harga', $search);
        $this->orLike('produk.created_at', $search);
        $this->orLike('produk.updated_at', $search);
        $this->orLike('kategori.nama_kategori', $search);
        $this->orderBy($field, $orderby);
        if ($limit >= 0) {
            $this->limit($limit, $start);
        }
        return $this->get()->getResultArray();
    }

    public function getCountFilterProducts($search)
    {
        $this->joinTableCategory();
        $this->like('produk.nama_produk', $search);
        $this->orLike('produk.id', $search);
        $this->orLike('produk.harga', $search);
        $this->orLike('produk.created_at', $search);
        $this->orLike('produk.updated_at', $search);
        $this->orLike('kategori.nama_kategori', $search);
        return $this->countAllResults();
    }

    public function addProducts($data)
    {
        return $this->save($data);
    }

    public function updateProduct($data)
    {
        $this->set($data['data']);
        $this->where('id', $data['id']);
        return $this->update();
    }

    public function updateProducts($data)
    {
        $newData = [];
        for ($i = 0; $i < count($data['id']); $i++) {
            if($this->getDetailProduct($data['id'][$i])->stok < $data['qty'][$i]) {
                return false;
            }
            $newStok = $this->getDetailProduct($data['id'][$i])->stok - $data['qty'][$i];
            $newData[] = [
                "id" => $data['id'][$i],
                "stok" => $newStok
            ];
        }
        return $this->updateBatch($newData, 'id');
    }

    public function deleteProducts($data)
    {
        foreach ($data as $id) {
            if (!$this->delete($id)) {
                return false;
            }
        }
        return true;
    }

    public function addStokProduct($data)
    {
        $stok = $this->getDetailProduct($data['id'])->stok;
        $this->set('stok', $stok + $data['stok']);
        $this->where('id', $data['id']);
        return $this->update();
    }

    public function getDataProducts($data)
    {
        $this->whereIn('id', $data);
        return $this->get()->getResultArray();
    }
}
