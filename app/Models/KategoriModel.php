<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table         = 'kategori';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'nama_kategori', 'slug', 'foto'
    ];

    public function getCountAllCategories()
    {
        return $this->countAllResults();
    }

    public function getDataCategories()
    {
        return $this->get()->getResult();
    }

    public function getDetailCategory($slug)
    {
        $this->where('slug', $slug);
        return $this->get()->getFirstRow();
    }

    public function hasCategory($data)
    {
        $this->where($data);
        return $this->countAllResults();
    }

    public function addCategory($data)
    {
        return $this->insert($data);
    }

    public function updateCategory($data)
    {
        $this->set($data['data']);
        $this->where('slug', $data['slug']);
        return $this->update();
    }

    public function deleteCategory($data)
    {
        $this->where($data);
        return $this->delete();
    }
}
