<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Get user (adjust based on your auth system)
        // $user = session()->get('user');

        // if ($user === null) {
        //     return redirect()->to('/login');
        // }

        // // Get roles (adjust this if stored differently)
        // $userRoles = explode(',', session('userlevel') ?? '');

        // // Check allowed roles from route
        // if (!empty($arguments) && !array_intersect($arguments, $userRoles)) {
        //     return redirect()->to('/my_training')
        //         ->with('error', 'You do not have permission.');
        // }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // not needed
    }
}