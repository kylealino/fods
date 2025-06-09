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
                return $this->loadMainView();
                break;
    
			case 'SAOB-PDF':
				return view('report/saob-pdf');
				break;

            case 'MAIN-SAVE': 
                $this->mysaob->saob_save();
                return redirect()->to('mysaobrpt?meaction=MAIN');
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

        //budget table dt fetching
        $budgetdtquery = $this->db->query("
        SELECT 
            a.`recid`,
            a.`trxno`,
            a.`project_title`,
            a.`responsibility_code`,
            a.`fund_cluster_code`,
            a.`division_name`,
            a.`is_pending`,
            a.`is_approved`,
            a.`is_disapproved`,
            a.`added_at`,
            (
                IFNULL((SELECT SUM(`approved_budget`) FROM `tbl_budget_direct_ps_dt` WHERE project_id = a.recid), 0) +
                IFNULL((SELECT SUM(`approved_budget`) FROM `tbl_budget_indirect_ps_dt` WHERE project_id = a.recid), 0) +
                IFNULL((SELECT SUM(`approved_budget`) FROM `tbl_budget_direct_mooe_dt` WHERE project_id = a.recid), 0) +
                IFNULL((SELECT SUM(`approved_budget`) FROM `tbl_budget_indirect_mooe_dt` WHERE project_id = a.recid), 0) +
                IFNULL((SELECT SUM(`approved_budget`) FROM `tbl_budget_indirect_co_dt` WHERE project_id = a.recid), 0) +
                IFNULL((SELECT SUM(`approved_budget`) FROM `tbl_budget_direct_co_dt` WHERE project_id = a.recid), 0)
            ) AS approved_budget
        FROM
            tbl_budget_hd a
        ");
        $budgetdtdata = $budgetdtquery->getResultArray();

        //hd lookup data
        $fundclusterquery = $this->db->query("SELECT `fund_cluster_code` FROM tbl_fundcluster");
        $fundclusterdata = $fundclusterquery->getResultArray();

        $divisionquery = $this->db->query("SELECT `division_name` FROM tbl_division");
        $divisiondata = $divisionquery->getResultArray();

        $psuacsquery = $this->db->query("SELECT * FROM mst_uacs WHERE allotment_class = 'Personnel Services' ORDER BY TRIM(sub_object_code) ASC");
        $psuacsdata = $psuacsquery->getResultArray();

        $mooeuacsquery = $this->db->query("SELECT * FROM mst_uacs WHERE allotment_class = 'Maintenance and Other Operating Expenses' ORDER BY TRIM(sub_object_code) ASC");
        $mooeuacsdata = $mooeuacsquery->getResultArray();

        $programtitlequery = $this->db->query("SELECT * FROM tbl_budget_hd WHERE fund_cluster_code = '01' ORDER BY recid ASC");
        $programtitledata = $programtitlequery->getResultArray();

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

        return view('report/saob-main', [
            'fundclusterdata' => $fundclusterdata,
            'divisiondata' => $divisiondata,
            'psuacsdata' => $psuacsdata,
            'mooeuacsdata' => $mooeuacsdata,
            'budgetdtdata' => $budgetdtdata,
            'projectdata' => $projectdata,
            'programtitledata' => $programtitledata,
        ]);
    }
    
    
}
