<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class MyRCI extends BaseController
{
    public function __construct()
	{
		$this->request = \Config\Services::request();
        $this->mylddapada = model('App\Models\MyLDDAPADAModel');
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
    
        }
    }
    

    private function loadMainView() {

        $lddapadaquery = $this->db->query("
            SELECT
                a.lddapadano,
                a.lddapada_date,
                a.ckno,
                b.dvno,
                b.serialno,
                
                x.responsibility_code,
                b.payee_name,
                c.particulars,
                b.gross_amount,
                '0' AS philheath_amount,
                b.total_deduction AS tax_amount,
                b.net_amount
            FROM tbl_lddapada_hd a

            JOIN tbl_lddapada_dt b
                ON a.recid = b.lddapada_id

            JOIN tbl_ors_hd c
                ON b.serialno = c.serialno

            JOIN (

                SELECT
                    project_id,
                    responsibility_code,
                    amount
                FROM tbl_ors_direct_ps_dt

                UNION ALL

                SELECT
                    project_id,
                    responsibility_code,
                    amount
                FROM tbl_ors_direct_co_dt

                UNION ALL

                SELECT
                    project_id,
                    responsibility_code,
                    amount
                FROM tbl_ors_direct_mooe_dt

                UNION ALL

                SELECT
                    project_id,
                    responsibility_code,
                    amount
                FROM tbl_ors_indirect_ps_dt

                UNION ALL

                SELECT
                    project_id,
                    responsibility_code,
                    amount
                FROM tbl_ors_indirect_co_dt

                UNION ALL

                SELECT
                    project_id,
                    responsibility_code,
                    amount
                FROM tbl_ors_indirect_mooe_dt

            ) x

            ON c.recid = x.project_id
        
        ");
        $lddapadadata = $lddapadaquery->getResultArray();

        return view('cashier/rci-main', [
            'lddapadadata' => $lddapadadata,
        ]);
    }
    
    
}
