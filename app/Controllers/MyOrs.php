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
                return $this->loadMainView();
                break;
    
            case 'MAIN-SAVE': 
                $this->myors->ors_save();
                return redirect()->to('myors?meaction=MAIN');
                break;

             case 'MAIN-APPROVE-A': 
                $this->myors->ors_certifya_approve();
                return redirect()->to('myors?meaction=MAIN');
                break;

            case 'MAIN-DISAPPROVE-A': 
                $this->myors->ors_certifya_disapprove();
                return redirect()->to('myors?meaction=MAIN');
                break;

            case 'MAIN-APPROVE-B': 
                $this->myors->ors_certifyb_approve();
                return redirect()->to('myors?meaction=MAIN');
                break;

            case 'MAIN-DISAPPROVE-B': 
                $this->myors->ors_certifyb_disapprove();
                return redirect()->to('myors?meaction=MAIN');
                break;
            
            case 'MAIN-UPLOAD': 
                $this->myors->budget_attachment_upload();
                return redirect()->to('myors?meaction=MAIN');
                break;
            
            case 'PRINT-ORS': 
                return view('ors/ors-pdf');
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

        $couacsquery = $this->db->query("SELECT * FROM mst_uacs WHERE allotment_class = 'Capital Outlay' ORDER BY TRIM(sub_object_code) ASC");
        $couacsdata = $couacsquery->getResultArray();

        $projecttitlequery = $this->db->query("SELECT * FROM tbl_budget_hd WHERE fund_cluster_code = '01'  GROUP BY program_title ORDER BY recid DESC");
        $projecttitledata = $projecttitlequery->getResultArray();

        $orshdquery = $this->db->query("
        SELECT 
        a.`recid`,
        a.`program_title`,
        a.`particulars`,
        a.`funding_source`,
        a.`payee_name`,
        a.`payee_office`,
        a.`payee_address`,
        (
            IFNULL((SELECT SUM(`amount`) FROM `tbl_ors_direct_ps_dt` WHERE project_id = a.recid), 0) +
            IFNULL((SELECT SUM(`amount`) FROM `tbl_ors_indirect_ps_dt` WHERE project_id = a.recid), 0) +
            IFNULL((SELECT SUM(`amount`) FROM `tbl_ors_direct_mooe_dt` WHERE project_id = a.recid), 0) +
            IFNULL((SELECT SUM(`amount`) FROM `tbl_ors_indirect_mooe_dt` WHERE project_id = a.recid), 0) +
            IFNULL((SELECT SUM(`amount`) FROM `tbl_ors_indirect_co_dt` WHERE project_id = a.recid), 0) +
            IFNULL((SELECT SUM(`amount`) FROM `tbl_ors_direct_co_dt` WHERE project_id = a.recid), 0)
        ) AS amount        
         FROM tbl_ors_hd a ORDER BY a.`recid` DESC");
        $orshddata = $orshdquery->getResultArray();

        //reference/project title lookup
        $projectquery = $this->db->query("
        SELECT
            a.`fundcluster_id`,
            b.`fund_cluster_code`,
            a.`division_id`,
            c.`division_name`,
            a.`responsibility_code`,
            a.`project_title`,
            a.`mfopaps_code`
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
        WHERE a.`fundcluster_id` = '1'
        ORDER BY a.`project_title` ASC
        ");
        $projectdata = $projectquery->getResultArray();

        $certifyquery = $this->db->query("SELECT * FROM myua_user ORDER BY recid DESC");
        $certifydata = $certifyquery->getResultArray();


        return view('ors/ors-main', [
            'psuacsdata' => $psuacsdata,
            'mooeuacsdata' => $mooeuacsdata,
            'couacsdata' => $couacsdata,
            'payeedata' => $payeedata,
            'projectdata' => $projectdata,
            'orshddata' => $orshddata,
            'certifydata' => $certifydata,
        ]);
    }
    
    
}
