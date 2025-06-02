<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class MySaobReport extends BaseController
{
    public function __construct()
	{
		$this->request = \Config\Services::request();
        $this->mysaob = model('App\Models\MySaobReportModel');
        $this->db = \Config\Database::connect();
	}

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');
    
        switch ($meaction) {
            case 'MAIN': 
                return view('report/saob-main');
                break;
    
			case 'SAOB-PDF':
				return view('report/saob-pdf');
				break;


            //  case 'MAIN-APPROVE': 
            //     $this->mysaob->budget_approve();
            //     return redirect()->to('myua?meaction=MAIN');
            //     break;

            //   case 'MAIN-DISAPPROVE': 
            //     $this->mysaob->budget_disapprove();
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
        
        $validateaccessquery = $this->db->query("SELECT * FROM tbl_user_access WHERE `username` = '$username'");
        if ($validateaccessquery->getNumRows()>0) {

            $accessquery = $this->db->query("
            SELECT 
                a.`recid`,
                a.`access_code`,
                b.`access_name`,
                a.`is_active`,
                1 AS `with_ua`
            FROM 
                tbl_user_access a
            LEFT JOIN
                tbl_access_modules b
            ON
                a.`access_code` = b.`access_code`
            WHERE
                a.`username` = '$username'
            ");
            $accessdata = $accessquery->getResultArray();
        }else{
            $accessquery = $this->db->query("SELECT *, 0 AS `is_active`,'0' AS `with_ua` FROM tbl_access_modules");
            $accessdata = $accessquery->getResultArray();
        }

        return view('user-management/ua-main', [
            'myuadata' => $myuadata,
            'accessdata' => $accessdata,
        ]);
    }
    
    
}
