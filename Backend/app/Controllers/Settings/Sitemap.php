<?php

namespace App\Controllers;

use App\Models\Settings\Settings_model;
use App\Models\Settings\Dropdown_model;
use App\Models\User_login\Users_model;
#[\AllowDynamicProperties]
class Sitemap extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->settings_model = new Settings_model();
        $this->dropdown_model = new Dropdown_model();
        $this->users_model = new Users_model();
    }
    public function index()
    {
        $data = [];
        echo view('templates/header_view', $data);
        echo view('users/site_map_view');
        echo view('templates/footer_view');
    }
}
