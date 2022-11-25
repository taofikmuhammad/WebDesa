<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Auth extends BaseController
{
    public function __construct()
    {
        $this->db = db_connect();
    }
    public function index()
    {
        $data = [
            'validation' => \Config\Services::validation()
        ];
        return view("login", $data);
    }
    public function prosesLogin()
    {
        $nik = $this->request->getVar('nik');
        $password = $this->request->getVar('password');

        $pengguna = $this->db->table('warga')->where('nik', $nik)->get()->getRow();

        $rules = [
            'nik' => [
                'rules' => 'required',
                'errors' => ['required' => 'NIK harus diisi']
            ],
            'password' => [
                'rules' => 'required',
                'errors' => ['required' => 'Password harus diisi']
            ]
        ];

        if (!$rules) {
            return redirect()->to(base_url('login'));
        }

        if (!$pengguna) {
            session()->setFlashdata('error', 'NIK tidak terdaftar');
            return redirect()->to(base_url('login'));
        }

        if ($pengguna->password != $password) {
            session()->setFlashdata('error', 'Password salah');
            return redirect()->to(base_url('login'));
        }

        return redirect()->to(base_url('beranda'));
    }
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}
