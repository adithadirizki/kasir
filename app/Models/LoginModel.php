<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    protected $table         = 'users';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'username', 'nama', 'password'
    ];

    public function check_users($data)
    {
        return $this->where([
            'username' => $data['username'],
            'password' => $data['password']
        ])->findAll();
    }
}
