<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class MyOrs extends BaseController
{
    public function __construct()
	{
		$this->request = \Config\Services::request();
        $this->myors = model('App\Models\MyOrsModel');
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
	}

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');
    
        switch ($meaction) {
            case 'MAIN': 
                $accessQuery = $this->db->query("
                    SELECT `recid`FROM tbl_user_access WHERE `username` = '{$this->cuser}' AND `access_code` = '2001' AND `is_active` = '1'
                ");
                if ($accessQuery->getNumRows() > 0) {
                    return $this->loadMainView();
                }else {
                    return view('errors/html/access-restricted');
                }
                
                break;
    
            case 'MAIN-SAVE': 
                $this->myors->orsburs_save();
                // return redirect()->to('myors?meaction=MAIN');
                break;

             case 'MAIN-APPROVE': 
                $this->myors->budget_approve();
                return redirect()->to('myors?meaction=MAIN');
                break;

            case 'MAIN-DISAPPROVE': 
                $this->myors->budget_disapprove();
                return redirect()->to('myors?meaction=MAIN');
                break;
            
            case 'MAIN-UPLOAD': 
                $this->myors->budget_attachment_upload();
                return redirect()->to('myors?meaction=MAIN');
                break;
            
            case 'PRINT-LIB': 
                return view('budget/budget-lib-print');
                break;
            
        }
    }
    

    private function loadMainView() {
        //payee lookup
        $payeedata = $this->db->query("
        SELECT
            `payee_name`,
            `payee_office`,
            `payee_address`
        FROM
            `tbl_payee`
        ORDER BY `payee_name` ASC
        ");
        $payeedata = $payeedata->getResultArray();


        $psuacsquery = $this->db->query("SELECT * FROM mst_uacs WHERE allotment_class = 'Personnel Services' ORDER BY TRIM(sub_object_code) ASC");
        $psuacsdata = $psuacsquery->getResultArray();

        $mooeuacsquery = $this->db->query("SELECT * FROM mst_uacs WHERE allotment_class = 'Maintenance and Other Operating Expenses' ORDER BY TRIM(sub_object_code) ASC");
        $mooeuacsdata = $mooeuacsquery->getResultArray();

        $projecttitlequery = $this->db->query("SELECT * FROM tbl_budget_hd WHERE fund_cluster_code = '01'  GROUP BY program_title ORDER BY recid DESC");
        $projecttitledata = $projecttitlequery->getResultArray();

        $saobhdquery = $this->db->query("SELECT * FROM tbl_saob_hd ORDER BY recid DESC");
        $saobhddata = $saobhdquery->getResultArray();

        //reference/project title lookup
        $projectquery = $this->db->query("
        SELECT
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
        ORDER BY a.`project_title` ASC
        ");
        $projectdata = $projectquery->getResultArray();

        return view('ors/ors-main', [
            'psuacsdata' => $psuacsdata,
            'mooeuacsdata' => $mooeuacsdata,
            'payeedata' => $payeedata,
            'projectdata' => $projectdata,
        ]);
    }
    
    
}
