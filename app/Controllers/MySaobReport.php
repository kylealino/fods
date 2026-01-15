<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class MySaobReport extends BaseController
{
    public function __construct()
	{
		$this->request = \Config\Services::request();
        $this->mysaob = model('App\Models\MySaobReportModel');
        $this->session = session();
        $this->db = \Config\Database::connect();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
	}

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');
    
        switch ($meaction) {
            case 'MAIN': 
                $accessQuery = $this->db->query("
                    SELECT `recid`FROM tbl_user_access WHERE `username` = '{$this->cuser}' AND `access_code` = '3001' AND `is_active` = '1'
                ");
                if ($accessQuery->getNumRows() > 0) {
                    return $this->loadMainView();
                    break;
                }else {
                    return view('errors/html/access-restricted');
                }
    
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
    
    public function exportCsv(){
        $from = $this->request->getGet('date_from');
        $to   = $this->request->getGet('date_to');

        if (!$from || !$to) {
            return;
        }

        $sql = "
            SELECT
                hd.recid            AS ors_recid,
                hd.serialno,
                hd.ors_date,
                d.program_title,
                d.project_title,
                d.responsibility_code,
                d.mfopaps_code,
                d.sub_object_code,
                d.uacs_code,
                d.amount,
                d.added_at,
                d.added_by
            FROM tbl_ors_hd hd
            JOIN (
                SELECT project_id, program_title, project_title, responsibility_code,
                    mfopaps_code, sub_object_code, uacs_code, amount, added_at, added_by
                FROM tbl_ors_direct_ps_dt

                UNION ALL
                SELECT project_id, program_title, project_title, responsibility_code,
                    mfopaps_code, sub_object_code, uacs_code, amount, added_at, added_by
                FROM tbl_ors_indirect_ps_dt

                UNION ALL
                SELECT project_id, program_title, project_title, responsibility_code,
                    mfopaps_code, sub_object_code, uacs_code, amount, added_at, added_by
                FROM tbl_ors_direct_mooe_dt

                UNION ALL
                SELECT project_id, program_title, project_title, responsibility_code,
                    mfopaps_code, sub_object_code, uacs_code, amount, added_at, added_by
                FROM tbl_ors_indirect_mooe_dt

                UNION ALL
                SELECT project_id, program_title, project_title, responsibility_code,
                    mfopaps_code, sub_object_code, uacs_code, amount, added_at, added_by
                FROM tbl_ors_direct_co_dt

                UNION ALL
                SELECT project_id, program_title, project_title, responsibility_code,
                    mfopaps_code, sub_object_code, uacs_code, amount, added_at, added_by
                FROM tbl_ors_indirect_co_dt
            ) d ON d.project_id = hd.recid
            WHERE hd.ors_date BETWEEN ? AND ?
            ORDER BY hd.recid DESC, d.added_at ASC
        ";

        $query = $this->db->query($sql, [$from, $to]);
        $data  = $query->getResultArray();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="SAOB_'.$from.'_to_'.$to.'.csv"');

        $out = fopen('php://output', 'w');

        if (!empty($data)) {
            fputcsv($out, array_keys($data[0]));
            foreach ($data as $row) {
                fputcsv($out, $row);
            }
        }

        fclose($out);
        exit;
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

        $psobjectquery = $this->db->query("SELECT DISTINCT object_code FROM mst_uacs WHERE allotment_class = 'Personnel Services'  ORDER BY TRIM(object_code) ASC");
        $psobjectdata = $psobjectquery->getResultArray();

        $mooeuacsquery = $this->db->query("SELECT * FROM mst_uacs WHERE allotment_class = 'Maintenance and Other Operating Expenses' ORDER BY TRIM(sub_object_code) ASC");
        $mooeuacsdata = $mooeuacsquery->getResultArray();

        $mooeobjectquery = $this->db->query("SELECT DISTINCT object_code FROM mst_uacs WHERE allotment_class = 'Maintenance and Other Operating Expenses' ORDER BY TRIM(object_code) ASC");
        $mooeobjectdata = $mooeobjectquery->getResultArray();
        $mooeobjectdata[] = ['object_code' => 'General Services'];

        $couacsquery = $this->db->query("SELECT * FROM mst_uacs WHERE allotment_class = 'Capital Outlay' ORDER BY TRIM(sub_object_code) ASC");
        $couacsdata = $couacsquery->getResultArray();

        $coobjectquery = $this->db->query("SELECT DISTINCT object_code FROM mst_uacs WHERE allotment_class = 'Capital Outlay' ORDER BY TRIM(object_code) ASC");
        $coobjectdata = $coobjectquery->getResultArray();

        // $programtitlequery = $this->db->query("SELECT program_title FROM tbl_budget_hd WHERE fund_cluster_code = '01'  GROUP BY program_title ORDER BY recid DESC");
        // $programtitledata = $programtitlequery->getResultArray();

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

        return view('report/saob-main', [
            'fundclusterdata' => $fundclusterdata,
            'divisiondata' => $divisiondata,
            'psobjectdata' => $psobjectdata,
            'mooeobjectdata' => $mooeobjectdata,
            'coobjectdata' => $coobjectdata,
            'psuacsdata' => $psuacsdata,
            'mooeuacsdata' => $mooeuacsdata,
            'couacsdata' => $couacsdata,
            'budgetdtdata' => $budgetdtdata,
            'projectdata' => $projectdata,
            // 'programtitledata' => $programtitledata,
            'saobhddata' => $saobhddata,
        ]);
    }
    
    
}
