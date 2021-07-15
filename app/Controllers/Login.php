<?php

namespace App\Controllers;

use App\Models\LoginModel;

class Login extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = session();
    }

    public function index()
    {
        // jika mempunyai session has Login maka langsung ke index
        if ($this->session->has('hasLogin')) {
            return redirect()->to('/');
        }
        return view('login');
    }

    public function check_login()
    {
        $model = new LoginModel();
        $dataPost = $this->request->getPost(['username', 'password']);
        $hasil_check = $model->check_users($dataPost);
        if ($hasil_check == false) {
            return json_encode([
                'error' => 1,
                'msg' => 'Username atau password Anda salah.'
            ]);
        }
        $data = [
            'username' => $hasil_check[0]['username'],
            'nama' => $hasil_check[0]['nama'],
            'password' => $hasil_check[0]['password'],
            'hasLogin' => true
        ];
        $this->session->set($data);
        return json_encode([
            'error' => 0,
            'msg' => 'Anda berhasil login.'
        ]);
    }

    //--------------------------------------------------------------------

}
