<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $userlevel =session('id_user');
        if($userlevel=='') {
            return redirect()->to(base_url());
        }
     //  if(session()->get('isLoggedIn') != true){
     //      return redirect()->to(base_url());
     //   }
        
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}