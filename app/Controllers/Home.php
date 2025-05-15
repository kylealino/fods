<?php

namespace App\Controllers;
use CodeIgniter\HTTP\Response;
class Home extends BaseController
{
    public function index(): string
    {
        if (!session('__xsys_myuserzicas_is_logged__')) {
            return view('MyLogin');
        }
    
        return view('MyDashboard');
    }
    
}
