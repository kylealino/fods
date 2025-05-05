<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class MyProject extends BaseController
{
    public function __construct()
	{
		$this->request = \Config\Services::request();
        $this->myproject = model('App\Models\MyProjectModel');
        $this->db = \Config\Database::connect();
	}

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');
    
        switch ($meaction) {
            case 'MAIN': 
                return $this->loadMainView();
                break;
    
            case 'MAIN-SAVE': 
                $this->myproject->project_save();
                return redirect()->to('myproject?meaction=MAIN');
                break;

            // case 'MAIN-DELETE': 
            //     $this->myproject->projects_delete();
            //     return redirect()->to('myproject?meaction=MAIN');
            //     break;
        }
    }
    

    private function loadMainView() {

        //reference/project title lookup
        $projectquery = $this->db->query("
        SELECT
            a.`recid`,
            a.`fundcluster_id`,
            b.`fund_cluster_code`,
            a.`division_id`,
            c.`division_name`,
            a.`responsibility_code`,
            a.`project_title`
        FROM
            `tbl_reference_project` a
        JOIN
            `tbl_fundcluster`b
        ON 
            a.fundcluster_id = b.`recid`
        JOIN
            `tbl_division` c
        ON
            a.`division_id` = c.recid
        ");
        $projectdata = $projectquery->getResultArray();

        //division data lookup
        $divisionquery = $this->db->query("
        SELECT
            `recid` AS `division_id`,
            `division_name`
        FROM
            `tbl_division` 
        ");
        $divisiondata = $divisionquery->getResultArray();

        //fundcluster data lookup
        $fundclusterquery = $this->db->query("
        SELECT
            `recid` AS `fundcluster_id`,
            `fund_cluster_code`
        FROM
            `tbl_fundcluster` 
        ");
        $fundclusterdata = $fundclusterquery->getResultArray();
    
        return view('project/project-main', [
            'projectdata' => $projectdata,
            'divisiondata' => $divisiondata,
            'fundclusterdata' => $fundclusterdata,
        ]);
    }
    
    
}
