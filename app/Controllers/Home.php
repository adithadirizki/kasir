<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use App\Models\ProdukModel;
use App\Models\PesananModel;

class Home extends BaseController
{
	protected $session;
	protected $m_Kategori;
	protected $m_Produk;

	public function __construct()
	{
		// if (!session()->has('hasLogin')) {
		// 	return redirect()->to(base_url('login'));
		// }
		$this->m_Kategori = new KategoriModel();
		$this->m_Produk = new ProdukModel();
		$this->m_Pesanan = new PesananModel();
	}

	public function index()
	{
		if (!session()->has('hasLogin')) {
			return redirect()->to(base_url('login'));
		}
		$dataSession = session()->get();
		$data = [
			'title' => 'Dashboard',
			'jum_kategori' => $this->m_Kategori->getCountAllCategories(),
			'jum_produk' => $this->m_Produk->getCountAllProducts(),
			'jum_pesanan' => $this->m_Pesanan->getCountAllOrders(),
			'jum_produk_terjual' => $this->m_Pesanan->getCountAllProductSold(),
			'jum_pesanan_baru' => $this->m_Pesanan->getCountAllNewOrders(),
			'dataSession' => $dataSession,
		];
		// dd($data);
		return view('index', $data);
	}

	public function logout()
	{
		session()->destroy();
		return redirect()->to(base_url('login'));
	}

	public function newOrders()
	{
		helper('number');
		$search = $_POST['search']['value'];
		$limit = $_POST['length'];
		$start = $_POST['start'];
		$index = $_POST['order'][0]['column'];
		$field = $_POST['columns'][$index]['data'];
		$orderby = $_POST['order'][0]['dir'];
		$totalOrders = $this->m_Pesanan->getCountAllOrders();
		$filterOrders = $this->m_Pesanan->filterOrders($search, $limit, $start, $field, $orderby);
		$totalFilterOrders = $this->m_Pesanan->getCountFilterOrders($search);
		$rows = array();
		foreach ($filterOrders as $row) {
			$harga = number_to_currency($row['harga'], 'IDR', 'id_ID');
			$total = number_to_currency($row['kuantitas'] * $row['harga'], 'IDR', 'id_ID');
			unset($row['harga']);
			$rows[] = $row + array('harga' => $harga, 'total' => $total);
		}
		$data = [
			'draw' => $_POST['draw'],
			'recordsTotal' => $totalOrders,
			'recordsFiltered' => $totalFilterOrders,
			'data' => $rows
		];
		return json_encode($data);
	}
}
