<?php
namespace App\Models;
use CodeIgniter\Model;

class MyBudgetAllotmentModel extends Model
{

    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
		$this->db = \Config\Database::connect();
		$this->cuser = $this->session->get('__xsys_myuserzicas__');
        
    }

	public function budget_save() { 
		$trxno = $this->request->getPostGet('trxno');
		$recid = $this->request->getPostGet('recid');
		$realign_id = $this->request->getPostGet('realign_id');
		$project_title = $this->request->getPostGet('project_title');
		$responsibility_code = $this->request->getPostGet('responsibility_code');
		$fund_cluster_code = $this->request->getPostGet('fund_cluster_code');
		$division_name = $this->request->getPostGet('division_name');
		$project_leader = $this->request->getPostGet('project_leader');
		$budgetdtdata = $this->request->getPostGet('budgetdtdata');
		//newly added fields
		$program_title = $this->request->getPostGet('program_title');
		$total_duration = $this->request->getPostGet('total_duration');
		$duration_from = $this->request->getPostGet('duration_from');
		$duration_to = $this->request->getPostGet('duration_to');
		$program_leader = $this->request->getPostGet('program_leader');
		$monitoring_agency = $this->request->getPostGet('monitoring_agency');
		$collaborating_agencies = $this->request->getPostGet('collaborating_agencies');
		$implementing_agency = $this->request->getPostGet('implementing_agency');

		//MOOE DATA
		$budgetmooedtdata = $this->request->getPostGet('budgetmooedtdata');
		$budgetcodtdata = $this->request->getPostGet('budgetcodtdata');

		//PS INDIRECT DATA
		$budgetdtindirectdata = $this->request->getPostGet('budgetdtindirectdata');
		$budgetmooeindirectdtdata = $this->request->getPostGet('budgetmooeindirectdtdata');
		$budgetindirectcodtdata = $this->request->getPostGet('budgetindirectcodtdata');

		$cseqn =  $this->get_ctr_budget('LIB','fods','CTRL_NO01');//TRANSACTION NO
		$trx = empty($trxno) ? $cseqn : $trxno;

		if (empty($project_title)) {
			echo "
			<script>
			toastr.error('Project title is required!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}
		if (empty($program_title)) {
			echo "
			<script>
			toastr.error('Program title is required!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}
		if (empty($total_duration)) {
			echo "
			<script>
			toastr.error('Total duration is required!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}
		if (empty($duration_from)) {
			echo "
			<script>
			toastr.error('Duration from is required!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}
		if (empty($duration_to)) {
			echo "
			<script>
			toastr.error('Duration to is required!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}
		if (empty($program_leader)) {
			echo "
			<script>
			toastr.error('Program leader is required!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}
		if (empty($monitoring_agency)) {
			echo "
			<script>
			toastr.error('Monitoring agency is required!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}
		if (empty($collaborating_agencies)) {
			echo "
			<script>
			toastr.error('Collaborating agencies is required!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}
		if (empty($implementing_agency)) {
			echo "
			<script>
			toastr.error('Implementing agency is required!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}
		if (empty($budgetdtdata) && empty($budgetmooedtdata) && empty($budgetcodtdata)) {
			echo "
			<script>
			toastr.error('No particulars found!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}
		if (empty($recid)) {
			$accessquery = $this->db->query("
				SELECT `recid`FROM tbl_user_access WHERE `username` = '{$this->cuser}' AND `access_code` = '1002' AND `is_active` = '1'
			");
			if ($accessquery->getNumRows() == 0) {
				echo "
				<script>
				toastr.error('Saving Access Denied! Please Contact the Administrator.', 'Oops!', {
						progressBar: true,
						closeButton: true,
						timeOut:2000,
					});
				</script>
				";
				die();
			}

			//INSERTING HD DATA
			$query = $this->db->query("
				INSERT INTO `tbl_budget_hd`(
				`trxno`,
				`project_title`, 
				`responsibility_code`,
				`fund_cluster_code`, 
				`division_name`,
				`project_leader`, 
				`added_at`, 
				`added_by`,
				`program_title`,
				`total_duration`,
				`duration_from`,
				`duration_to`,
				`program_leader`,
				`monitoring_agency`,
				`collaborating_agencies`,
				`implementing_agency`) 
				VALUES (
				'$trx',
				'$project_title',
				'$responsibility_code',
				'$fund_cluster_code',
				'$division_name',
				'$project_leader',
				NOW(),
				'{$this->cuser}',
				'$program_title',
				'$total_duration',
				'$duration_from',
				'$duration_to',
				'$program_leader',
				'$monitoring_agency',
				'$collaborating_agencies',
				'$implementing_agency'
				)
			");

			//PROJECT ID FETCHING
			$query = $this->db->query("
			SELECT `recid` FROM tbl_budget_hd WHERE `trxno` = '$trx'
			");
			$rw = $query->getRowArray();
			$project_id = $rw['recid'];

			//INSERTING PS DT DATA
			if (!empty($budgetdtdata)) {
				//this is for normal saving and updating
				for($aa = 0; $aa < count($budgetdtdata); $aa++){
					$medata = explode("x|x",$budgetdtdata[$aa]);
					$particulars = $medata[0]; 
					$code = $medata[1]; 
					$approved_budget = $medata[2]; 
					$dtid = $medata[3]; 

					if (!empty($dtid)) {
						$query = $this->db->query("
						UPDATE
							`tbl_budget_direct_ps_dt`
						SET
							`particulars` = '$particulars',
							`code` = '$code',
							`approved_budget` = '$approved_budget'
						WHERE
							`recid` = '$dtid'
						");
					}else{
						$query = $this->db->query("
						INSERT INTO `tbl_budget_direct_ps_dt`(
								`project_id`,
								`particulars`,
								`code`,
								`approved_budget`,
								`added_at`,
								`added_by`
							)
							VALUES(
								'$project_id',
								'$particulars',
								'$code',
								'$approved_budget',
								NOW(),
								'{$this->cuser}'
							)
						");
					}

					
				}
			}

			//INSERTING PS DT INDIRECT DATA
			if (!empty($budgetdtindirectdata)) {
				//this is for normal saving and updating
				for($aa = 0; $aa < count($budgetdtindirectdata); $aa++){
					$medata = explode("x|x",$budgetdtindirectdata[$aa]);
					$particulars = $medata[0]; 
					$code = $medata[1]; 
					$approved_budget = $medata[2]; 
					$dtid = $medata[3]; 

					if (!empty($dtid)) {
						$query = $this->db->query("
						UPDATE
							`tbl_budget_indirect_ps_dt`
						SET
							`particulars` = '$particulars',
							`code` = '$code',
							`approved_budget` = '$approved_budget'
						WHERE
							`recid` = '$dtid'
						");
					}else{
						$query = $this->db->query("
						INSERT INTO `tbl_budget_indirect_ps_dt`(
								`project_id`,
								`particulars`,
								`code`,
								`approved_budget`,
								`added_at`,
								`added_by`
							)
							VALUES(
								'$project_id',
								'$particulars',
								'$code',
								'$approved_budget',
								NOW(),
								'{$this->cuser}'
							)
						");
					}

					
				}
			}
	
			//INSERTING MOOE DT DATA
			if (!empty($budgetmooedtdata)) {

				//this is for normal saving and updating
				for($aa = 0; $aa < count($budgetmooedtdata); $aa++){
					$medata = explode("x|x",$budgetmooedtdata[$aa]);
					$particulars = $medata[0]; 
					$code = $medata[1]; 
					$approved_budget = $medata[2]; 
					$dtid = $medata[3]; 

					if (!empty($dtid)) {
						$query = $this->db->query("
						UPDATE
							`tbl_budget_direct_mooe_dt`
						SET
							`particulars` = '$particulars',
							`code` = '$code',
							`approved_budget` = '$approved_budget'
						WHERE
							`recid` = '$dtid'
						");
					}else{
						$query = $this->db->query("
						INSERT INTO `tbl_budget_direct_mooe_dt`(
								`project_id`,
								`particulars`,
								`code`,
								`approved_budget`,
								`added_at`,
								`added_by`
							)
							VALUES(
								'$project_id',
								'$particulars',
								'$code',
								'$approved_budget',
								NOW(),
								'{$this->cuser}'
							)
						");
					}

					
				}
			}

			//INSERTING MOOE DT DATA INDIRECT ---
			if (!empty($budgetmooeindirectdtdata)) {

				//this is for normal saving and updating
				for($aa = 0; $aa < count($budgetmooeindirectdtdata); $aa++){
					$medata = explode("x|x",$budgetmooeindirectdtdata[$aa]);
					$particulars = $medata[0]; 
					$code = $medata[1]; 
					$approved_budget = $medata[2]; 
					$dtid = $medata[3]; 

					if (!empty($dtid)) {
						$query = $this->db->query("
						UPDATE
							`tbl_budget_indirect_mooe_dt`
						SET
							`particulars` = '$particulars',
							`code` = '$code',
							`approved_budget` = '$approved_budget'
						WHERE
							`recid` = '$dtid'
						");
					}else{
						$query = $this->db->query("
						INSERT INTO `tbl_budget_indirect_mooe_dt`(
								`project_id`,
								`particulars`,
								`code`,
								`approved_budget`,
								`added_at`,
								`added_by`
							)
							VALUES(
								'$project_id',
								'$particulars',
								'$code',
								'$approved_budget',
								NOW(),
								'{$this->cuser}'
							)
						");
					}

					
				}
			}

			//INSERTING CO DT DATA
			if (!empty($budgetcodtdata)) {

				//this is for normal saving and updating
				for($aa = 0; $aa < count($budgetcodtdata); $aa++){
					$medata = explode("x|x",$budgetcodtdata[$aa]);
					$particulars = $medata[0]; 
					$code = $medata[1]; 
					$approved_budget = $medata[2]; 
					$dtid = $medata[3]; 

					if (!empty($dtid)) {
						$query = $this->db->query("
						UPDATE
							`tbl_budget_direct_co_dt`
						SET
							`particulars` = '$particulars',
							`code` = '$code',
							`approved_budget` = '$approved_budget'
						WHERE
							`recid` = '$dtid'
						");
					}else{
						$query = $this->db->query("
						INSERT INTO `tbl_budget_direct_co_dt`(
								`project_id`,
								`particulars`,
								`code`,
								`approved_budget`,
								`added_at`,
								`added_by`
							)
							VALUES(
								'$project_id',
								'$particulars',
								'$code',
								'$approved_budget',
								NOW(),
								'{$this->cuser}'
							)
						");
					}

					
				}
			}

			//INSERTING CO DT DATA INDIRECT --
			if (!empty($budgetindirectcodtdata)) {

				//this is for normal saving and updating
				for($aa = 0; $aa < count($budgetindirectcodtdata); $aa++){
					$medata = explode("x|x",$budgetindirectcodtdata[$aa]);
					$particulars = $medata[0]; 
					$code = $medata[1]; 
					$approved_budget = $medata[2]; 
					$dtid = $medata[3]; 

					if (!empty($dtid)) {
						$query = $this->db->query("
						UPDATE
							`tbl_budget_indirect_co_dt`
						SET
							`particulars` = '$particulars',
							`code` = '$code',
							`approved_budget` = '$approved_budget'
						WHERE
							`recid` = '$dtid'
						");
					}else{
						$query = $this->db->query("
						INSERT INTO `tbl_budget_indirect_co_dt`(
								`project_id`,
								`particulars`,
								`code`,
								`approved_budget`,
								`added_at`,
								`added_by`
							)
							VALUES(
								'$project_id',
								'$particulars',
								'$code',
								'$approved_budget',
								NOW(),
								'{$this->cuser}'
							)
						");
					}

					
				}
			}

			$status = "Project Budget Allotment Saved Successfully!";
			$color = "success";
		}else{
			$accessquery = $this->db->query("
				SELECT `recid`FROM tbl_user_access WHERE `username` = '{$this->cuser}' AND `access_code` = '1003' AND `is_active` = '1'
			");
			if ($accessquery->getNumRows() == 0) {
				echo "
				<script>
				toastr.error('Updating Access Denied! Please Contact the Administrator.', 'Oops!', {
						progressBar: true,
						closeButton: true,
						timeOut:2000,
					});
				</script>
				";
				die();
			}
			$query = $this->db->query("
				UPDATE
					`tbl_budget_hd`
				SET
					`project_title` = '$project_title',
					`responsibility_code` = '$responsibility_code',
					`fund_cluster_code` = '$fund_cluster_code',
					`division_name` = '$division_name',
					`project_leader` = '$project_leader',
					`program_title` = '$program_title',
					`total_duration` = '$total_duration',
					`duration_from` = '$duration_from',
					`duration_to` = '$duration_to',
					`program_leader` = '$program_leader',
					`monitoring_agency` = '$monitoring_agency',
					`collaborating_agencies` = '$collaborating_agencies',
					`implementing_agency` = '$implementing_agency'
				WHERE `recid` = '$recid'
			");

			//PROJECT ID FETCHING
			$query = $this->db->query("
			SELECT `recid` FROM tbl_budget_hd WHERE `trxno` = '$trx'
			");
			$rw = $query->getRowArray();
			$project_id = $rw['recid'];

			//UPDATE OR INSERT OF NEW ROW DATA
			if (!empty($budgetdtdata)) {
				for($aa = 0; $aa < count($budgetdtdata); $aa++){
					$medata = explode("x|x",$budgetdtdata[$aa]);
					$particulars = $medata[0]; 
					$code = $medata[1]; 
					$approved_budget = $medata[2]; 
					$dtid = $medata[3];

					if (!empty($dtid)) {
						$query = $this->db->query("
						UPDATE
							`tbl_budget_direct_ps_dt`
						SET
							`particulars` = '$particulars',
							`code` = '$code',
							`approved_budget` = '$approved_budget'
						WHERE
							`recid` = '$dtid'
						");
					}else{
						$query = $this->db->query("
						INSERT INTO `tbl_budget_direct_ps_dt`(
								`project_id`,
								`particulars`,
								`code`,
								`approved_budget`,
								`added_at`,
								`added_by`
							)
							VALUES(
								'$project_id',
								'$particulars',
								'$code',
								'$approved_budget',
								NOW(),
								'{$this->cuser}'
							)
						");
					}

					
				}
			}

			//INSERTING PS DT INDIRECT DATA
			if (!empty($budgetdtindirectdata)) {
				//this is for normal saving and updating
				for($aa = 0; $aa < count($budgetdtindirectdata); $aa++){
					$medata = explode("x|x",$budgetdtindirectdata[$aa]);
					$particulars = $medata[0]; 
					$code = $medata[1]; 
					$approved_budget = $medata[2]; 
					$dtid = $medata[3];

					if (!empty($dtid)) {
						$query = $this->db->query("
						UPDATE
							`tbl_budget_indirect_ps_dt`
						SET
							`particulars` = '$particulars',
							`code` = '$code',
							`approved_budget` = '$approved_budget'
						WHERE
							`recid` = '$dtid'
						");
					}else{
						$query = $this->db->query("
						INSERT INTO `tbl_budget_indirect_ps_dt`(
								`project_id`,
								`particulars`,
								`code`,
								`approved_budget`,
								`added_at`,
								`added_by`
							)
							VALUES(
								'$project_id',
								'$particulars',
								'$code',
								'$approved_budget',
								NOW(),
								'{$this->cuser}'
							)
						");
					}

					
				}
			}
			//INSERTING MOOE DT DATA
			if (!empty($budgetmooedtdata)) {

				//this is for normal saving and updating
				for($aa = 0; $aa < count($budgetmooedtdata); $aa++){
					$medata = explode("x|x",$budgetmooedtdata[$aa]);
					$particulars = $medata[0]; 
					$code = $medata[1]; 
					$approved_budget = $medata[2]; 
					$dtid = $medata[3]; 

					if (!empty($dtid)) {
						$query = $this->db->query("
						UPDATE
							`tbl_budget_direct_mooe_dt`
						SET
							`particulars` = '$particulars',
							`code` = '$code',
							`approved_budget` = '$approved_budget'
						WHERE
							`recid` = '$dtid'
						");
					}else{
						$query = $this->db->query("
						INSERT INTO `tbl_budget_direct_mooe_dt`(
								`project_id`,
								`particulars`,
								`code`,
								`approved_budget`,
								`added_at`,
								`added_by`
							)
							VALUES(
								'$project_id',
								'$particulars',
								'$code',
								'$approved_budget',
								NOW(),
								'{$this->cuser}'
							)
						");
					}

				}
			}

			//INSERTING MOOE DT DATA INDIRECT ---
			if (!empty($budgetmooeindirectdtdata)) {

				//this is for normal saving and updating
				for($aa = 0; $aa < count($budgetmooeindirectdtdata); $aa++){
					$medata = explode("x|x",$budgetmooeindirectdtdata[$aa]);
					$particulars = $medata[0]; 
					$code = $medata[1]; 
					$approved_budget = $medata[2]; 
					$dtid = $medata[3]; 

					if (!empty($dtid)) {
						$query = $this->db->query("
						UPDATE
							`tbl_budget_indirect_mooe_dt`
						SET
							`particulars` = '$particulars',
							`code` = '$code',
							`approved_budget` = '$approved_budget'
						WHERE
							`recid` = '$dtid'
						");
					}else{
						$query = $this->db->query("
						INSERT INTO `tbl_budget_indirect_mooe_dt`(
								`project_id`,
								`particulars`,
								`code`,
								`approved_budget`,
								`added_at`,
								`added_by`
							)
							VALUES(
								'$project_id',
								'$particulars',
								'$code',
								'$approved_budget',
								NOW(),
								'{$this->cuser}'
							)
						");
					}

					
				}
			}

			//INSERTING CO DT DATA
			if (!empty($budgetcodtdata)) {

				//this is for normal saving and updating
				for($aa = 0; $aa < count($budgetcodtdata); $aa++){
					$medata = explode("x|x",$budgetcodtdata[$aa]);
					$particulars = $medata[0]; 
					$code = $medata[1]; 
					$approved_budget = $medata[2]; 
					$dtid = $medata[3]; 

					if (!empty($dtid)) {
						$query = $this->db->query("
						UPDATE
							`tbl_budget_direct_co_dt`
						SET
							`particulars` = '$particulars',
							`code` = '$code',
							`approved_budget` = '$approved_budget'
						WHERE
							`recid` = '$dtid'
						");
					}else{
						$query = $this->db->query("
						INSERT INTO `tbl_budget_direct_co_dt`(
								`project_id`,
								`particulars`,
								`code`,
								`approved_budget`,
								`added_at`,
								`added_by`
							)
							VALUES(
								'$project_id',
								'$particulars',
								'$code',
								'$approved_budget',
								NOW(),
								'{$this->cuser}'
							)
						");
					}

				}
			}

			//INSERTING CO DT DATA INDIRECT --
			if (!empty($budgetindirectcodtdata)) {

				//this is for normal saving and updating
				for($aa = 0; $aa < count($budgetindirectcodtdata); $aa++){
					$medata = explode("x|x",$budgetindirectcodtdata[$aa]);
					$particulars = $medata[0]; 
					$code = $medata[1]; 
					$approved_budget = $medata[2]; 
					$dtid = $medata[3]; 

					if (!empty($dtid)) {
						$query = $this->db->query("
						UPDATE
							`tbl_budget_indirect_co_dt`
						SET
							`particulars` = '$particulars',
							`code` = '$code',
							`approved_budget` = '$approved_budget'
						WHERE
							`recid` = '$dtid'
						");
					}else{
						$query = $this->db->query("
						INSERT INTO `tbl_budget_indirect_co_dt`(
								`project_id`,
								`particulars`,
								`code`,
								`approved_budget`,
								`added_at`,
								`added_by`
							)
							VALUES(
								'$project_id',
								'$particulars',
								'$code',
								'$approved_budget',
								NOW(),
								'{$this->cuser}'
							)
						");
					}

					
				}
			}

			$status = "Project Budget Allotment Updated Successfully!";
			$color = "info";
		}

		if ($query) {
			// Echo JavaScript to show the toast and then redirect
			echo "
			<script>
				toastr.$color('{$status}!', 'Well Done!', {
						progressBar: true,
						closeButton: true,
						timeOut:2500,
					});
				setTimeout(function() {
						window.location.href = 'mybudgetallotment?meaction=MAIN&recid=$project_id'; // Redirect to MAIN view
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
	
	public function budget_approve() { 
		$recid = $this->request->getPostGet('recid');
		$approver = $this->request->getPostGet('approver');
		$remarks = $this->request->getPostGet('remarks');

		$accessquery = $this->db->query("
			SELECT `recid`FROM tbl_user_access WHERE `username` = '{$this->cuser}' AND `access_code` = '1005' AND `is_active` = '1'
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
			UPDATE tbl_budget_hd SET `is_pending` = '0', `is_approved` = '1',`approver` = '$approver', `remarks` = '$remarks' WHERE `recid` = '$recid'
		");
		$status = "Project budget approved!";
		
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
						window.location.href = 'mybudgetallotment?meaction=MAIN'; // Redirect to MAIN view
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

	public function budget_disapprove() { 
		$recid = $this->request->getPostGet('recid');
		$approver = $this->request->getPostGet('approver');
		$remarks = $this->request->getPostGet('remarks');

		$accessquery = $this->db->query("
			SELECT `recid`FROM tbl_user_access WHERE `username` = '{$this->cuser}' AND `access_code` = '1005' AND `is_active` = '1'
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
			UPDATE tbl_budget_hd SET `is_pending` = '0', `is_approved` = '0',`is_disapproved` = '1',`approver` = '$approver', `remarks` = '$remarks' WHERE `recid` = '$recid'
		");
		$status = "Project budget disapproved!";
		
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
						window.location.href = 'mybudgetallotment?meaction=MAIN'; // Redirect to MAIN view
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

	public function budget_attachment_upload() { 
		$file = $this->request->getFile('userfile');
        $trxno = $this->request->getPostGet('hd_trxno');

        $query = $this->db->query("SELECT `recid` FROM tbl_budget_hd WHERE `trxno` = '$trxno' ");
        if ($query->getNumRows() > 0) {
            $rw = $query->getRowArray();
            $recid = $rw['recid'];
        } 

        if (!$file || !$file->isValid()) {
            return redirect()->to('mybudgetallotment?meaction=MAIN')
                            ->with('error', 'No file selected or invalid file.');
        }

        $uploadPath = FCPATH . 'uploads/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $originalName = pathinfo($file->getName(), PATHINFO_FILENAME);

        $newFileName = $originalName . '_' . $file->getRandomName();
        $file->move($uploadPath, $newFileName);

        $query = $this->db->query("
        INSERT INTO `tbl_budget_attachments`(
            `trxno`,
            `file_name`,
            `added_by`
        )
        VALUES(
            '$trxno',
            '$newFileName',
            '{$this->cuser}'
        )
        ");

        $status = 'File uploaded successfully';
        $redirectUrl = 'mybudgetallotment?meaction=MAIN&recid=' . $recid;
        
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
						window.location.href = 'mybudgetallotment?meaction=MAIN&recid=$recid'; 
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

	public function get_ctr_budget($class,$supp,$dbname,$mfld='') { 
		$accessquery = $this->db->query("
		CREATE TABLE if not exists `myctr_budget` (
		  `CTR_YEAR` varchar(4) DEFAULT '0000',
		  `CTR_MONTH` varchar(2) DEFAULT '00',
		  `CTR_DAY` varchar(2) DEFAULT '00',
		  `CTRL_NO01` varchar(15) DEFAULT '00000000',
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
		
		$qctr = $this->db->query("select {$xfield} from myctr_budget WHERE CTR_YEAR = '$xsysyear' AND CTR_MONTH = '$xsysmonth' AND CTR_DAY = '$xsysday'  limit 1");
		if($qctr->getNumRows() == 0) {
			$xnumb = '00001';
			$query = $this->db->query( "insert into myctr_budget (CTR_YEAR,CTR_MONTH,CTR_DAY,{$xfield}) values('$xsysyear','$xsysmonth','$xsysday','$xnumb')");
			$qctr->freeResult();
		} else {
			$qctr->freeResult();
			$qctr = $this->db->query( "select {$xfield} MYFIELD from myctr_budget WHERE CTR_YEAR = '$xsysyear' AND CTR_MONTH = '$xsysmonth' AND CTR_DAY = '$xsysday' limit 1");
			$rctr = $qctr->getRowArray();
			if(trim($rctr['MYFIELD'],' ') == '') { 
				$xnumb = '00001';
			} else {
				$xnumb = $rctr['MYFIELD'];
				$qctr = $this->db->query("select ('{$xnumb}' + 1) XNUMB");
				$rctr = $qctr->getRowArray();
				$xnumb = trim($rctr['XNUMB'],' ');
				$xnumb = str_pad($xnumb + 0,5,"0",STR_PAD_LEFT);
				$query = $this->db->query("update myctr_budget set {$xfield} = '{$xnumb}'");
			}
		}
		return  $class . '-' . $xnumb . '-' . $xsysmonth . $xsysday;//.$supp
	} 

	
} //end main class
?>