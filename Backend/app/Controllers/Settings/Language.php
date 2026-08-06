<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Language extends BaseController
{
    public function index()
    {
        $data['locale'] = $this->request->getVar('lang');
        $session = session();
        $session->remove('lang');
        if($session->set('lang', $data['locale']));
        $url = base_url();
        return redirect()->to($url.'User_login/profile');
        
    }
}