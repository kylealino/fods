<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class MyUACS extends BaseController
{
    public function __construct()
	{
		$this->request = \Config\Services::request();
        $this->myuacs = model('App\Models\MyUACSModel');
        $this->db = \Config\Database::connect();
	}

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');
    
        switch ($meaction) {
            case 'MAIN': 
                return $this->loadMainView();
                break;
    
            case 'MAIN-SAVE': 
                $this->myuacs->uacs_save();
                return redirect()->to('myuacs?meaction=MAIN');
                break;

            // case 'MAIN-DELETE': 
            //     $this->myuacs->projects_delete();
            //     return redirect()->to('myuacs?meaction=MAIN');
            //     break;
        }
    }
    

    private function loadMainView() {

        $uacsquery = $this->db->query("
            SELECT
                a.`recid`,
                a.`uacs_category_id` ,
                (select `expenditure_category` FROM `tbl_uacs_category` WHERE `recid` = a.`uacs_category_id`) AS expenditure_category,
                a.`object_of_expenditures`,
                a.`code`,
                a.`added_on`,
                a.`added_by`,
                a.`active_status`
            FROM
                `tbl_uacs` a
        ");
        $uacsdata = $uacsquery->getResultArray();

        //fundcluster data lookup
        $uacscategoryquery = $this->db->query("
        SELECT
            `recid` AS `uacs_category_id`,
            `expenditure_category`
        FROM
            `tbl_uacs_category` 
        ");
        $uacscategorydata = $uacscategoryquery->getResultArray();
    
        return view('uacs/uacs-main', [
            'uacsdata' => $uacsdata,
            'uacscategorydata' => $uacscategorydata,
        ]);
    }
    
    
}
