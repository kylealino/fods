<?php

namespace App\Controllers;

class MyBudgetApproval extends BaseController
{
    public function __construct()
	{
		$this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
	}

    public function index(){
        $accessQuery = $this->db->query("
            SELECT `recid`FROM tbl_user_access WHERE `username` = '{$this->cuser}' AND `access_code` = '1004' AND `is_active` = '1'
        ");
        if ($accessQuery->getNumRows() > 0) {
            return $this->loadMainView();
        }else {
            return view('errors/html/access-restricted');
        }
    }

    private function loadMainView() {

        //pending budget table fetching
        $pendingbudgetquery = $this->db->query("
        SELECT 
            a.`recid`,
            a.`project_title`,
            a.`responsibility_code`,
            a.`fund_cluster_code`,
            a.`division_name`,
            SUM(b.`approved_budget`) approved_budget
        FROM
            tbl_budget_hd a
        JOIN
            tbl_budget_dt b
        on
            a.`recid` = b.`project_id`
        WHERE
            `is_pending` = '1' AND `is_approved` = '0' AND `is_disapproved` = '0'
        GROUP BY a.`trxno`
        ORDER BY a.`recid` DESC
        ");
        $pendingbudgetdata = $pendingbudgetquery->getResultArray();

        //approved budget table fetching
        $approvedbudgetquery = $this->db->query("
        SELECT 
            a.`recid`,
            a.`project_title`,
            a.`responsibility_code`,
            a.`fund_cluster_code`,
            a.`division_name`,
            SUM(b.`approved_budget`) approved_budget
        FROM
            tbl_budget_hd a
        JOIN
            tbl_budget_dt b
        on
            a.`recid` = b.`project_id`
        WHERE
            a.`is_approved` = '1' AND `is_disapproved` = '0' AND `is_pending` = '0'
        GROUP BY a.`trxno`
        ");
        $approvedbudgetdata = $approvedbudgetquery->getResultArray();

        //diaapproved budget table fetching
        $disapprovedbudgetquery = $this->db->query("
        SELECT 
            a.`recid`,
            a.`project_title`,
            a.`responsibility_code`,
            a.`fund_cluster_code`,
            a.`division_name`,
            SUM(b.`approved_budget`) approved_budget
        FROM
            tbl_budget_hd a
        JOIN
            tbl_budget_dt b
        on
            a.`recid` = b.`project_id`
        WHERE
            a.`is_disapproved` = '1' AND `is_approved` = '0' AND `is_pending` = '0'
        GROUP BY a.`trxno`
        ");
        $disapprovedbudgetdata = $disapprovedbudgetquery->getResultArray();

        return view('budget-approval/budget-approval-main', [
            'pendingbudgetdata' => $pendingbudgetdata,
            'approvedbudgetdata' => $approvedbudgetdata,
            'disapprovedbudgetdata' => $disapprovedbudgetdata,
        ]);
    }
    
}
