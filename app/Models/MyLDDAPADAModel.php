<?php
namespace App\Models;
use CodeIgniter\Model;

class MyLDDAPADAModel extends Model
{

    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
		$this->db = \Config\Database::connect();
		$this->cuser = $this->session->get('__xsys_myuserzicas__');
        
    }

	public function lddapada_save() { 
		$recid = $this->request->getPostGet('recid');
		$serialno = $this->request->getPostGet('serialno');
		$dvno = $this->request->getPostGet('dvno');
		$lddapadano = $this->request->getPostGet('lddapadano');
		$mds_branch = $this->request->getPostGet('mds_branch');
		$mds_accountno = $this->request->getPostGet('mds_accountno');
		$lddapada_date = $this->request->getPostGet('lddapada_date');
		$fund_cluster_code = $this->request->getPostGet('fund_cluster_code');
		$funding_source = $this->request->getPostGet('funding_source');
		$dvdtdata = $this->request->getPostGet('dvdtdata');
		

		if (empty($recid)) {
			$cseqn =  $this->get_ctr_lddapada($fund_cluster_code,$funding_source,'CTRL_NO01');//TRANSACTION NO
			//INSERTING HD DATA
			//disbursement SERIALNO
			$query = $this->db->query("
				INSERT INTO `tbl_lddapada_hd`(
					`lddapadano`,
					`mds_branch`,
					`mds_accountno`,
					`lddapada_date`,
					`fund_cluster_code`,
					`funding_source`,
					`added_by`
				)
				VALUES (?, ?, ?, ?, ?, ?, ?)", 
				[
					$cseqn,
					$mds_branch,
					$mds_accountno,
					$lddapada_date,
					$fund_cluster_code,
					$funding_source,
					$this->cuser
				]
			);

			$project_id = $this->db->insertID();

			if (!empty($dvdtdata)) {
				for($aa = 0; $aa < count($dvdtdata); $aa++){
					$medata = explode("x|x",$dvdtdata[$aa]);
					$dvno = $medata[0]; 
					$payee_name = $medata[1]; 
					$payee_account_num = $medata[2];
					$serialno = $medata[3]; 
					$uacs_code = $medata[4]; 
					$gross_amount = $medata[5]; 
					$total_deduction = $medata[6]; 
					$net_amount = $medata[7]; 

					$query = $this->db->query("
						INSERT INTO `tbl_lddapada_dt`(
							`lddapada_id`,
							`dvno`,
							`payee_name`,
							`payee_account_num`,
							`serialno`,
							`uacs_code`,
							`gross_amount`,
							`total_deduction`,
							`net_amount`,
							`added_by`
						)
						VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", 
						[
							$project_id,
							$dvno,
							$payee_name,
							$payee_account_num,
							$serialno,
							$uacs_code,
							$gross_amount,
							$total_deduction,
							$net_amount,
							$this->cuser
						]
					);
					
				}
			}

			$status = "LDDAP-ADA Saved Successfully!";
			$color = "success";
		}else{
			$query = $this->db->query("
				UPDATE tbl_lddapada_hd
				SET
					`serialno` = ?,
					`dvno` = ?,
					`lddapadano` = ?,
					`mds_branch` = ?,
					`mds_accountno` = ?,
					`lddapada_date` = ?,
					`fund_cluster_code` = ?,
					`funding_source` = ?
				WHERE recid = ?
			", [
				$serialno,
				$dvno,
				$lddapadano,
				$mds_branch,
				$mds_accountno,
				$lddapada_date,
				$fund_cluster_code,
				$funding_source,
				$recid
			]);

			$project_id = $recid;


			$status = "LDDAP-ADA Updated Successfully!";
			$color = "info";
		}

		$lddapno_display = empty($recid) ? $cseqn : $lddapadano;

		if ($query) {
			// Echo JavaScript to show the toast and then redirect
			echo "
			<script>
				document.getElementById('submitBtn').disabled = true;
				document.getElementById('lddapadano').value = '" . $lddapno_display . "';
				toastr.$color('{$status}!', 'Well Done!', {
						progressBar: true,
						closeButton: true,
						timeOut:2500,
					});
				setTimeout(function() {
						window.location.href = 'mylddapada?meaction=MAIN'; // Redirect to MAIN view
					}, 2500); // 2-second delay for user to see the toast
			</script>
			";
			exit; // Stop further PHP execution after the toast
		} else {
			// If there's an error, show an alert message
			echo "<script type='text/javascript'>
					alert('An error occurred while executing the query.');
				  </script>";
			exit;
		}
		
	}
	
	//CERTIFIED A APPROVAL/DISAPPROVAL
	public function disbursement_certifya_approve() { 
		$recid = $this->request->getPostGet('recid');
		$approver = $this->request->getPostGet('approver');
		$remarks = $this->request->getPostGet('remarks');
		$serialno = $this->request->getPostGet('serialno');
		$funding_source = $this->request->getPostGet('funding_source');

		// $cseqn =  $this->get_ctr_lddapada('01',$funding_source,'CTRL_NO01');//TRANSACTION NO
		// $trx = empty($serialno) ? $cseqn : $serialno;

		$accessquery = $this->db->query("
			SELECT `recid` FROM tbl_user_access WHERE `username` = '{$this->cuser}' AND `access_code` = '2005' AND `is_active` = '1'
		");
		if ($accessquery->getNumRows() == 0) {
			echo "
			<script>
			toastr.error('Approve Access Denied! Please Contact the Administrator.', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}

		$query = $this->db->query("
			UPDATE tbl_disbursement_hd 
			SET 
				`is_pending` = '0', 
				`is_approved_certa` = '1',
				`is_disapproved_certa` = '0',
				`certa_approver` = '$approver', 
				`certa_remarks` = '$remarks'
			WHERE `recid` = '$recid'
		");
		$status = "disbursement approved!";
		
		if ($query) {
			// Echo JavaScript to show the toast and then redirect
			echo "
			<script>
				toastr.success('{$status}!', 'Well Done!', {
						progressBar: true,
						closeButton: true,
						timeOut:2500,
					});
				setTimeout(function() {
						window.location.href = 'mydisbursementapproval?meaction=MAIN'; // Redirect to MAIN view
					}, 2500); // 2-second delay for user to see the toast
			</script>
			";
			exit; // Stop further PHP execution after the toast
		} else {
			// If there's an error, show an alert message
			echo "<script type='text/javascript'>
					alert('An error occurred while executing the query.');
				  </script>";
			exit;
		}
	}

	public function disbursement_certifya_disapprove() { 
		$recid = $this->request->getPostGet('recid');
		$approver = $this->request->getPostGet('approver');
		$remarks = $this->request->getPostGet('remarks');

		$accessquery = $this->db->query("
			SELECT `recid`FROM tbl_user_access WHERE `username` = '{$this->cuser}' AND `access_code` = '2005' AND `is_active` = '1'
		");
		if ($accessquery->getNumRows() == 0) {
			echo "
			<script>
			toastr.error('Disapprove Access Denied! Please Contact the Administrator.', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}

		$query = $this->db->query("
			UPDATE tbl_disbursement_hd SET `is_pending` = '0', `is_approved_certa` = '0',`is_disapproved_certa` = '1',`certa_approver` = '$approver', `certa_remarks` = '$remarks' WHERE `recid` = '$recid'
		");
		$status = "disbursement disapproved!";
		
		if ($query) {
			// Echo JavaScript to show the toast and then redirect
			echo "
			<script>
				toastr.success('{$status}!', 'Well Done!', {
						progressBar: true,
						closeButton: true,
						timeOut:2500,
					});
				setTimeout(function() {
						window.location.href = 'mydisbursementapproval?meaction=MAIN'; // Redirect to MAIN view
					}, 2500); // 2-second delay for user to see the toast
			</script>
			";
			exit; // Stop further PHP execution after the toast
		} else {
			// If there's an error, show an alert message
			echo "<script type='text/javascript'>
					alert('An error occurred while executing the query.');
				  </script>";
			exit;
		}
	}

	// CERTIFIED B APPROVAL/DISAPPROVAL
	public function disbursement_certifyb_approve() { 
		$recid = $this->request->getPostGet('recid');
		$approver = $this->request->getPostGet('approver');
		$remarks = $this->request->getPostGet('remarks');

		$accessquery = $this->db->query("
			SELECT `recid` FROM tbl_user_access WHERE `username` = '{$this->cuser}' AND `access_code` = '2006' AND `is_active` = '1'
		");
		if ($accessquery->getNumRows() == 0) {
			echo "
			<script>
			toastr.error('Approve Access Denied! Please Contact the Administrator.', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}

		$query = $this->db->query("
			UPDATE tbl_disbursement_hd SET `is_pending` = '0', `is_approved_certb` = '1', `is_disapproved_certb` = '0',`certb_approver` = '$approver', `certb_remarks` = '$remarks' WHERE `recid` = '$recid'
		");
		$status = "disbursement approved!";
		
		if ($query) {
			// Echo JavaScript to show the toast and then redirect
			echo "
			<script>
				toastr.success('{$status}!', 'Well Done!', {
						progressBar: true,
						closeButton: true,
						timeOut:2500,
					});
				setTimeout(function() {
						window.location.href = 'mydisbursementapproval?meaction=MAIN'; // Redirect to MAIN view
					}, 2500); // 2-second delay for user to see the toast
			</script>
			";
			exit; // Stop further PHP execution after the toast
		} else {
			// If there's an error, show an alert message
			echo "<script type='text/javascript'>
					alert('An error occurred while executing the query.');
				  </script>";
			exit;
		}
	}

	public function disbursement_certifyb_disapprove() { 
		$recid = $this->request->getPostGet('recid');
		$approver = $this->request->getPostGet('approver');
		$remarks = $this->request->getPostGet('remarks');

		$accessquery = $this->db->query("
			SELECT `recid`FROM tbl_user_access WHERE `username` = '{$this->cuser}' AND `access_code` = '2006' AND `is_active` = '1'
		");
		if ($accessquery->getNumRows() == 0) {
			echo "
			<script>
			toastr.error('Disapprove Access Denied! Please Contact the Administrator.', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}

		$query = $this->db->query("
			UPDATE tbl_disbursement_hd SET `is_pending` = '0', `is_approved_certb` = '0',`is_disapproved_certb` = '1',`certb_approver` = '$approver', `certb_remarks` = '$remarks' WHERE `recid` = '$recid'
		");
		$status = "disbursement disapproved!";
		
		if ($query) {
			// Echo JavaScript to show the toast and then redirect
			echo "
			<script>
				toastr.success('{$status}!', 'Well Done!', {
						progressBar: true,
						closeButton: true,
						timeOut:2500,
					});
				setTimeout(function() {
						window.location.href = 'mydisbursementapproval?meaction=MAIN'; // Redirect to MAIN view
					}, 2500); // 2-second delay for user to see the toast
			</script>
			";
			exit; // Stop further PHP execution after the toast
		} else {
			// If there's an error, show an alert message
			echo "<script type='text/javascript'>
					alert('An error occurred while executing the query.');
				  </script>";
			exit;
		}
	}

	public function get_ctr_lddapada($fund_cluster,$funding_source,$mfld='') { 
		$accessquery = $this->db->query("
		CREATE TABLE if not exists `myctr_lddapada` (
		  `CTR_YEAR` varchar(4) DEFAULT '0000',
		  `CTR_MONTH` varchar(2) DEFAULT '00',
		  `CTR_DAY` varchar(2) DEFAULT '00',
		  `CTRL_NO01` varchar(15) DEFAULT '000',
		  `CTRL_NO02` varchar(15) DEFAULT '00000000',
		  `CTRL_NO03` varchar(15) DEFAULT '00000000',
		  `CTRL_NO04` varchar(15) DEFAULT '00000000',
		  `CTRL_NO05` varchar(15) DEFAULT '00000000',
		  `CTRL_NO06` varchar(15) DEFAULT '00000000',
		  `CTRL_NO07` varchar(15) DEFAULT '00000000',
		  `CTRL_NO08` varchar(15) DEFAULT '00000000',
		  `CTRL_NO09` varchar(15) DEFAULT '00000000',
		  `CTRL_NO10` varchar(15) DEFAULT '00000000',
		  `CTRL_NO11` varchar(15) DEFAULT '00000000',
		  `SS_CTR` varchar(15) DEFAULT '000000',
		  UNIQUE KEY `ctr01` (`CTR_YEAR`,`CTR_MONTH`,`CTR_DAY`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		");

		$xfield = (empty($mfld) ? 'CTRL_NO01' : $mfld);
		
		$q = $this->db->query("select date(now()) XSYSDATE");
		$rdate = $q->getRowArray();
		$xsysdate = $rdate['XSYSDATE'];
		$xsysdate_exp = explode('-', $xsysdate);
		$xsysyear =  $xsysdate_exp[0];
		$xsysmonth = $xsysdate_exp[1];
		$xsysday = $xsysdate_exp[2];
		
		$qctr = $this->db->query("select {$xfield} from myctr_lddapada WHERE CTR_YEAR = '$xsysyear' AND CTR_MONTH = '$xsysmonth' AND CTR_DAY = '$xsysday'  limit 1");
		if($qctr->getNumRows() == 0) {
			$xnumb = '001';
			$query = $this->db->query( "insert into myctr_lddapada (CTR_YEAR,CTR_MONTH,CTR_DAY,{$xfield}) values('$xsysyear','$xsysmonth','$xsysday','$xnumb')");
			$qctr->freeResult();
		} else {
			$qctr->freeResult();
			$qctr = $this->db->query( "select {$xfield} MYFIELD from myctr_lddapada WHERE CTR_YEAR = '$xsysyear' AND CTR_MONTH = '$xsysmonth' AND CTR_DAY = '$xsysday' limit 1");
			$rctr = $qctr->getRowArray();
			if(trim($rctr['MYFIELD'],' ') == '') { 
				$xnumb = '001';
			} else {
				$xnumb = $rctr['MYFIELD'];
				$qctr = $this->db->query("select ('{$xnumb}' + 1) XNUMB");
				$rctr = $qctr->getRowArray();
				$xnumb = trim($rctr['XNUMB'],' ');
				$xnumb = str_pad($xnumb + 0,3,"0",STR_PAD_LEFT);
				$query = $this->db->query("update myctr_lddapada set {$xfield} = '{$xnumb}'");
			}
		}
		return  $fund_cluster . '-' . $funding_source .  '-' . $xsysmonth . '-' . $xnumb . '-' . $xsysyear;//.$supp
	} 

	
} //end main class
?>