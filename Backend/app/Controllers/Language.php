<?php

namespace App\Controllers;

use App\Models\Dashboard\Dashboard_model;
use CodeIgniter\HTTP\IncomingRequest;

#[\AllowDynamicProperties]
class Language extends BaseController
{
    public function __construct()
    {
        $this->dashboard_model = new Dashboard_model();
    }
    public function setlanguage($lang)
    {
        $session = session();
        $allowed = ['en', 'es', 'kan', 'hi'];
        if (!in_array($lang, $allowed)) {
            $lang = 'en';
        }
        $session->remove('locale');
        $session->set('locale', $lang);
        $user = session()->get('id_user');
        $this->dashboard_model->updateLanguageUser($user, $lang);
        $ref = $this->request->getServer('HTTP_REFERER');
        if (!empty($ref)) {
            return redirect()->to($ref);
        }
        return redirect()->to(base_url('my_training'));
    }

    public function setLocale()
    {

        $lang = $this->request->getGet('lang');

        $allowed = ['en', 'es'];
        if (!in_array($lang, $allowed)) {
            $lang = 'en';
        }
        session()->set('locale', $lang);

        // Redirect back if possible, else to base URL
        $ref = $this->request->getServer('HTTP_REFERER');
        if (!empty($ref)) {
            return redirect()->to($ref);
        }

        return redirect()->to(base_url());
    }
}
