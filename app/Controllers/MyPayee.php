<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class MyPayee extends BaseController
{
    public function __construct()
	{
		$this->request = \Config\Services::request();
        $this->mypayee = model('App\Models\MyPayeeModel');
        $this->db = \Config\Database::connect();
	}

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');
    
        switch ($meaction) {
            case 'MAIN': 
                return $this->loadMainView();
                break;
    
            case 'MAIN-SAVE': 
                $this->mypayee->payee_save();
                return redirect()->to('mypayee?meaction=MAIN');
                break;

            case 'MAIN-DELETE': 
                $this->mypayee->payee_delete();
                return redirect()->to('mypayee?meaction=MAIN');
                break;
        }
    }
    

    private function loadMainView() {

        $query = $this->db->query("SELECT * FROM tbl_payee");
        $results = $query->getResultArray();
    
        return view('payee/payee-main', [
            'results' => $results
        ]);
    }
    
    
}
