<?php

use App\Controllers\BaseController;

class AuthLogin extends BaseController
{
   public function auth()
   {
      if(!session()->has('hasLogin')) {
         redirect()->to('/login');
      }
   }
}


?>