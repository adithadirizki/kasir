<?php

namespace App\Controllers;

use App\Models\ProdukModel;
use App\Models\KategoriModel;

class Produk extends BaseController
{
    protected $session;
    protected $m_Produk;
    protected $m_Kategori;

    public function __construct()
    {
        $this->session = session();
        $this->m_Produk = new ProdukModel();
        $this->m_Kategori = new KategoriModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Produk',
            'dataSession' => $this->session->get()
        ];
        return view('produk', $data);
    }

    public function detailProduct($id)
    {
        $data = [
            "title" => "Detail Produk",
            "data" => $this->m_Produk->getDetailProduct($id),
            "kategori" => $this->m_Kategori->getDataCategories(),
            "dataSession" => $this->session->get()
        ];
        return view('detailproduk', $data);
    }

    public function allProducts()
    {
        helper('number');
        $search = $_POST['search']['value'];
        $limit = $_POST['length'];
        $start = $_POST['start'];
        $index = $_POST['order'][0]['column'];
        $field = $_POST['columns'][$index]['data'];
        $orderby = $_POST['order'][0]['dir'];
        $totalProducts = $this->m_Produk->getCountAllProducts();
        $filterProducts = $this->m_Produk->getFilterProducts($search, $limit, $start, $field, $orderby);
        $totalFilterProducts = $this->m_Produk->getCountFilterProducts($search);
        $rows = array();
        foreach ($filterProducts as $row) {
            $harga = number_to_currency($row['harga'], 'IDR', 'id_ID');
            unset($row['harga']);
            $rows[] = $row + array('harga' => $harga);
        }
        $data = [
            'draw' => $_POST['draw'],
            'recordsTotal' => $totalProducts,
            'recordsFiltered' => $totalFilterProducts,
            'data' => $rows
        ];
        return json_encode($data);
    }

    public function preCreateProducts()
    {
        $data = [
            "title" => "Tambah Produk",
            "kategori" => $this->m_Kategori->getDataCategories(),
            "dataSession" => $this->session->get()
        ];
        return view('tambahproduk', $data);
    }

    public function addProducts()
    {
        $file = $this->request->getFile('foto');
        if ($file->getName() != '') {
            $namaFile = $file->getRandomName();
            $file->move('assets/img', $namaFile);
        } else {
            $namaFile = 'default.jpg';
        }
        $nama_produk = $this->request->getPost('nama_produk');
        $id_kategori = $this->request->getPost('kategori');
        $harga = preg_replace('/\D/', '', $this->request->getPost('harga'));
        $stok = $this->request->getPost('stok');
        $data = [
            "nama_produk" => $nama_produk,
            "foto" => $namaFile,
            "cat_id" => $id_kategori,
            "stok" => $stok,
            "harga" => $harga
        ];
        if ($this->m_Produk->addProducts($data)) {
            return json_encode([
                "error" => false,
                "msg" => "Produk berhasil ditambahkan."
            ]);
        } else {
            return json_encode([
                "error" => true,
                "msg" => "Produk gagal ditambahkan."
            ]);
        }
    }

    public function updateProduct($id)
    {
        $file = $this->request->getFile('foto');
        if ($file->getName() != '') {
            $namaFile = $file->getRandomName();
            $file->move('assets/img', $namaFile);
        } else {
            $namaFile = 'default.jpg';
        }
        $nama_produk = $this->request->getPost('nama_produk');
        $id_kategori = $this->request->getPost('kategori');
        $harga = preg_replace('/\D/', '', $this->request->getPost('harga'));
        $stok = $this->request->getPost('stok');
        $data = [
            "id" => [$id],
            "data" => [
                "nama_produk" => $nama_produk,
                "foto" => $namaFile,
                "cat_id" => $id_kategori,
                "stok" => $stok,
                "harga" => $harga
            ]
        ];
        if ($this->m_Produk->updateProducts($data)) {
            return json_encode([
                "error" => false,
                "msg" => "Produk berhasil ditambahkan."
            ]);
        } else {
            return json_encode([
                "error" => true,
                "msg" => "Produk gagal ditambahkan."
            ]);
        }
    }

    public function deleteProduct($id)
    {
        if ($this->m_Produk->deleteProducts([$id])) {
            return json_encode([
                "error" => false,
                "msg" => "Produk berhasil dihapus."
            ]);
        } else {
            return json_encode([
                "error" => true,
                "msg" => "Produk gagal dihapus."
            ]);
        }
    }

    public function deleteProducts()
    {
        if ($this->m_Produk->deleteProducts($this->request->getPost('produk'))) {
            return json_encode([
                "error" => false,
                "msg" => "Produk berhasil dihapus."
            ]);
        } else {
            return json_encode([
                "error" => true,
                "msg" => "Produk gagal dihapus."
            ]);
        }
    }

    public function preCreateOrders()
    {
        // dd($this->request->getPost());
        $data = [
            'title' => 'Buat Pesanan',
            'data' => $this->m_Produk->getDataProducts($this->request->getPost('produk')),
            'dataSession' => $this->session->get()
        ];
        return view('buatpesanan', $data);
    }
}
