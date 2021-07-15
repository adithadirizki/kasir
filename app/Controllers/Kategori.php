<?php

namespace App\Controllers;

use App\Models\KategoriModel;

class Kategori extends BaseController
{
   protected $session;
   protected $m_kategori;

   public function __construct()
   {
      $this->session = session();
      $this->m_Kategori = new KategoriModel();
   }

   public function index()
   {
      $dataSession = $this->session->get();
      $data = [
         "title" => "Kategori",
         "dataKategori" => $this->m_Kategori->getDataCategories(),
         "dataSession" => $dataSession
      ];
      return view('kategori', $data);
   }

   public function detailCategory($slug)
   {
      $data = [
         "title" => "Detail Kategori",
         "detailKategori" => $this->m_Kategori->getDetailCategory($slug),
         "dataSession" => $this->session->get()
      ];
      return view('detailkategori', $data);
   }

   public function addCategory()
   {
      $file = $this->request->getFile('foto');
      if ($file->getName() != '') {
         $namaFile = $file->getRandomName();
         $file->move('assets/img', $namaFile);
      } else {
         $namaFile = 'default.jpg';
      }
      $data = [
         "slug" => url_title($this->request->getPost('nama_kategori'), '-', true)
      ];
      if ($this->m_Kategori->hasCategory($data) > 0) {
         return json_encode([
            "error" => true,
            "msg" => "Nama kategori sudah ada."
         ]);
      }
      $data = [
         "nama_kategori" => $this->request->getPost('nama_kategori'),
         "slug" => url_title($this->request->getPost('nama_kategori'), '-', true),
         "foto" => $namaFile
      ];
      if ($this->m_Kategori->addCategory($data)) {
         return json_encode([
            "error" => false,
            "msg" => "Kategori berhasil diubah."
         ]);
      } else {
         return json_encode([
            "error" => true,
            "msg" => "Kategori gagal diubah."
         ]);
      }
   }

   public function updateCategory($slug)
   {
      $file = $this->request->getFile('foto');
      if ($file->getName() != '') {
         $namaFile = $file->getRandomName();
         $file->move('assets/img', $namaFile);
      } else {
         $namaFile = $this->request->getPost('old_foto');
      }
      $data = [
         "slug =" => url_title($this->request->getPost('nama_kategori'), '-', true),
         "slug !=" => $slug
      ];
      if ($this->m_Kategori->hasCategory($data) > 0) {
         return json_encode([
            "error" => true,
            "msg" => "Nama kategori sudah ada."
         ]);
      }
      $data = [
         "slug" => $slug,
         "data" => [
            "nama_kategori" => $this->request->getPost('nama_kategori'),
            "slug" => url_title($this->request->getPost('nama_kategori'), '-', true),
            "foto" => $namaFile
         ]
      ];
      if ($this->m_Kategori->updateCategory($data)) {
         return json_encode([
            "error" => false,
            "msg" => "Kategori berhasil diubah."
         ]);
      } else {
         return json_encode([
            "error" => true,
            "msg" => "Kategori gagal diubah."
         ]);
      }
   }

   public function deleteCategory($slug)
   {
      $data = [
         "slug" => $slug
      ];
      if($this->m_Kategori->deleteCategory($data)){
         return json_encode([
            "error" => false,
            "msg" => "Kategori berhasil dihapus."
         ]);
      } else {
         return json_encode([
            "error" => true,
            "msg" => "Kategori gagal dihapus."
         ]);
      }
   }
}
