<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class MyUserManagement extends BaseController
{
    public function __construct()
	{
		$this->request = \Config\Services::request();
        $this->myua = model('App\Models\MyUserManagementModel');
        $this->db = \Config\Database::connect();
	}

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');
    
        switch ($meaction) {
            case 'MAIN': 
                return $this->loadMainView();
                break;
    
            case 'MAIN-SAVE': 
                $this->myua->user_save();
                return redirect()->to('myua?meaction=MAIN');
                break;
            
            case 'MAIN-ACCESS-SAVE': 
                $this->myua->user_access_save();
                return redirect()->to('myua?meaction=MAIN');
                break;


            //  case 'MAIN-APPROVE': 
            //     $this->myua->budget_approve();
            //     return redirect()->to('myua?meaction=MAIN');
            //     break;

            //   case 'MAIN-DISAPPROVE': 
            //     $this->myua->budget_disapprove();
            //     return redirect()->to('myua?meaction=MAIN');
            //     break;
        }
    }
    

    private function loadMainView() {
        $recid = $this->request->getPostGet('recid');
        $myuaquery = $this->db->query("SELECT * FROM myua_user");
        $myuadata = $myuaquery->getResultArray();

        $myuserquery = $this->db->query("SELECT `username` FROM myua_user WHERE recid ='$recid' ");
        if ($myuserquery->getNumRows()>0) {
            $rw = $myuserquery->getRowArray();
            $username = $rw['username'];
        }else{
            $username ="";
        }
        
        $accessquery = $this->db->query("
        SELECT 
        a.`recid`,
        a.`access_name`,
        a.access_code,
        COALESCE(ua.is_active, 0) AS is_active
        FROM tbl_access_modules a
        LEFT JOIN tbl_user_access ua 
        ON ua.access_code = a.access_code AND ua.username = '$username'
        ");
        $accessdata = $accessquery->getResultArray();

        return view('user-management/ua-main', [
            'myuadata' => $myuadata,
            'accessdata' => $accessdata,
        ]);
    }
    
    
}
