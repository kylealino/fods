<?php
namespace App\Models;
use CodeIgniter\Model;

class MySaobReportModel extends Model
{

    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
		$this->mylibzsys = model('App\Models\MyLibzSysModel');
		$this->db = \Config\Database::connect();
		$this->cuser = $this->session->get('__xsys_myuserzicas__');
        
    }

	public function saob_save() { 
		$recid = $this->request->getPostGet('recid');
		$trxno = $this->request->getPostGet('trxno');
		$program_title = $this->request->getPostGet('program_title');
		$project_title = $this->request->getPostGet('project_title');
		$department = $this->request->getPostGet('department');
		$agency = $this->request->getPostGet('agency');
		$current_year = $this->request->getPostGet('current_year');

		//newly added fields
		$is_jan = $this->request->getPostGet('is_jan');
		$is_feb = $this->request->getPostGet('is_feb');
		$is_mar = $this->request->getPostGet('is_mar');
		$is_apr = $this->request->getPostGet('is_apr');
		$is_may = $this->request->getPostGet('is_may');
		$is_jun = $this->request->getPostGet('is_jun');
		$is_jul = $this->request->getPostGet('is_jul');
		$is_aug = $this->request->getPostGet('is_aug');
		$is_sep = $this->request->getPostGet('is_sep');
		$is_oct = $this->request->getPostGet('is_oct');
		$is_nov = $this->request->getPostGet('is_nov');
		$is_dec = $this->request->getPostGet('is_dec');

		$total_proposed_combined = $this->request->getPostGet('total_proposed_combined');
		$total_approved_combined = $this->request->getPostGet('total_approved_combined');
		
		$budgetdtdata = $this->request->getPostGet('budgetdtdata');
		$budgetmooedtdata = $this->request->getPostGet('budgetmooedtdata');
		$budgetcodtdata = $this->request->getPostGet('budgetcodtdata');

		$cseqn =  $this->get_ctr_saob('LIB','fods','CTRL_NO01');//TRANSACTION NO
		$trx = empty($trxno) ? $cseqn : $trxno;

		// var_dump($budgetcodtdata);
		// die();

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
		if (empty($department)) {
			echo "
			<script>
			toastr.error('Department is required!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}
		if (empty($agency)) {
			echo "
			<script>
			toastr.error('Agency is required!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}
		if (empty($current_year)) {
			echo "
			<script>
			toastr.error('Year is required!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,0
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
		if ($total_approved_combined !== $total_proposed_combined) {
			echo "
			<script>
			toastr.error('Approved budget must be equal to Proposed Realignment!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}

		if (empty($recid)) {
			// $accessquery = $this->db->query("
			// 	SELECT `recid`FROM tbl_user_access WHERE `username` = '{$this->cuser}' AND `access_code` = '1002' AND `is_active` = '1'
			// ");
			// if ($accessquery->getNumRows() == 0) {
			// 	echo "
			// 	<script>
			// 	toastr.error('Saving Access Denied! Please Contact the Administrator.', 'Oops!', {
			// 			progressBar: true,
			// 			closeButton: true,
			// 			timeOut:2000,
			// 		});
			// 	</script>
			// 	";
			// 	die();
			// }

			//INSERTING HD DATA
			$query = $this->db->query("
				INSERT INTO tbl_saob_hd (
					trxno, program_title, project_title, department, agency, current_year,
					is_jan, is_feb, is_mar, is_apr, is_may, is_jun, is_jul,
					is_aug, is_sep, is_oct, is_nov, is_dec, added_at, added_by
				)
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)", 
				[
					$trx,
					$program_title,
					$project_title,
					$department,
					$agency,
					$current_year,
					$is_jan,
					$is_feb,
					$is_mar,
					$is_apr,
					$is_may,
					$is_jun,
					$is_jul,
					$is_aug,
					$is_sep,
					$is_oct,
					$is_nov,
					$is_dec,
					$this->cuser
				]
			);


			//PROJECT ID FETCHING
			$query = $this->db->query("
			SELECT `recid` FROM tbl_saob_hd WHERE `trxno` = '$trx'
			");
			$rw = $query->getRowArray();
			$project_id = $rw['recid'];

			//INSERTING PS DT DATA
			if (!empty($budgetdtdata)) {
				for($aa = 0; $aa < count($budgetdtdata); $aa++){
					$medata = explode("x|x",$budgetdtdata[$aa]);
					$object_code = $medata[0]; 
					$particulars = $medata[1]; 
					$code = $medata[2]; 
					$approved_budget = $medata[3]; 
					$revised_allotment = $medata[4]; 
					$revision = $medata[5];  
					$proposed_revision = $medata[6]; 

					$query = $this->db->query("
					INSERT INTO `tbl_saob_ps_dt`(
							`project_id`,
							`project_title`,
							`object_code`,
							`particulars`,
							`code`,
							`approved_budget`,
							`revised_allotment`,
							`revision`,
							`proposed_revision`,
							`added_at`,
							`added_by`
						)
						VALUES(
							'$project_id',
							'$project_title',
							'$object_code',
							'$particulars',
							'$code',
							'$approved_budget',
							'$revised_allotment',
							'$revision',
							'$proposed_revision',
							NOW(),
							'{$this->cuser}'
						)
					");
					
				}
			}

			//INSERTING MOOE DT DATA
			if (!empty($budgetmooedtdata)) {
				//this is for normal saving and updating
				for($aa = 0; $aa < count($budgetmooedtdata); $aa++){
					$medata = explode("x|x",$budgetmooedtdata[$aa]);
					$object_code = $medata[0]; 
					$particulars = $medata[1]; 
					$code = $medata[2]; 
					$approved_budget = $medata[3]; 
					$revised_allotment = $medata[4]; 
					$revision = $medata[5];  
					$proposed_revision = $medata[6]; 

					$query = $this->db->query("
					INSERT INTO `tbl_saob_mooe_dt`(
							`project_id`,
							`project_title`,
							`object_code`,
							`particulars`,
							`code`,
							`approved_budget`,
							`revised_allotment`,
							`revision`,
							`proposed_revision`,
							`added_at`,
							`added_by`
						)
						VALUES(
							'$project_id',
							'$project_title',
							'$object_code',
							'$particulars',
							'$code',
							'$approved_budget',
							'$revised_allotment',
							'$revision',
							'$proposed_revision',
							NOW(),
							'{$this->cuser}'
						)
					");
				}
			}

			//INSERTING CO DT DATA
			if (!empty($budgetcodtdata)) {
				//this is for normal saving and updating
				for($aa = 0; $aa < count($budgetcodtdata); $aa++){
					$medata = explode("x|x",$budgetcodtdata[$aa]);
					$object_code = $medata[0]; 
					$particulars = $medata[1]; 
					$code = $medata[2]; 
					$approved_budget = $medata[3]; 
					$revised_allotment = $medata[4]; 
					$revision = $medata[5];  
					$proposed_revision = $medata[6]; 

					$query = $this->db->query("
					INSERT INTO `tbl_saob_co_dt`(
							`project_id`,
							`project_title`,
							`object_code`,
							`particulars`,
							`code`,
							`approved_budget`,
							`revised_allotment`,
							`revision`,
							`proposed_revision`,
							`added_at`,
							`added_by`
						)
						VALUES(
							'$project_id',
							'$project_title',
							'$object_code',
							'$particulars',
							'$code',
							'$approved_budget',
							'$revised_allotment',
							'$revision',
							'$proposed_revision',
							NOW(),
							'{$this->cuser}'
						)
					");
				}
			}

			$status = "SAOB Saved Successfully!";
			$color = "success";
		}else{
			$query = $this->db->query("
				UPDATE tbl_saob_hd
				SET
					program_title = ?,
					project_title = ?,
					department = ?,
					agency = ?,
					current_year = ?,
					is_jan = ?,
					is_feb = ?,
					is_mar = ?,
					is_apr = ?,
					is_may = ?,
					is_jun = ?,
					is_jul = ?,
					is_aug = ?,
					is_sep = ?,
					is_oct = ?,
					is_nov = ?,
					is_dec = ?
				WHERE recid = ?
			", [
				$program_title,
				$project_title,
				$department,
				$agency,
				$current_year,
				$is_jan,
				$is_feb,
				$is_mar,
				$is_apr,
				$is_may,
				$is_jun,
				$is_jul,
				$is_aug,
				$is_sep,
				$is_oct,
				$is_nov,
				$is_dec,
				$recid
			]);


			//PROJECT ID FETCHING
			$query = $this->db->query("
			SELECT `recid` FROM tbl_saob_hd WHERE `trxno` = '$trx'
			");
			$rw = $query->getRowArray();
			$project_id = $rw['recid'];

			//UPDATE OR INSERT OF NEW ROW DATA
			if (!empty($budgetdtdata)) {
				for($aa = 0; $aa < count($budgetdtdata); $aa++){
					$medata = explode("x|x",$budgetdtdata[$aa]);
					$object_code = $medata[0]; 
					$particulars = $medata[1]; 
					$code = $medata[2]; 
					$approved_budget = $medata[3]; 
					$revised_allotment = $medata[4]; 
					$revision = $medata[5];  
					$proposed_revision = $medata[6]; 
					$dtid = $medata[7]; 

					if (!empty($dtid)) {
						if ($is_jan == '1' && $is_feb == '0' && $is_mar == '0' && $is_apr == '0' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_ps_dt`
							SET
								`project_id` = '$project_id',
								`project_title` = '$project_title',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`january_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '0' && $is_apr == '0' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_ps_dt`
							SET
								`project_id` = '$project_id',
								`project_title` = '$project_title',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`february_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '0' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_ps_dt`
							SET
								`project_id` = '$project_id',
								`project_title` = '$project_title',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`march_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_ps_dt`
							SET
								`project_id` = '$project_id',
								`project_title` = '$project_title',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`april_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_ps_dt`
							SET
								`project_id` = '$project_id',
								`project_title` = '$project_title',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`may_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_ps_dt`
							SET
								`project_id` = '$project_id',
								`project_title` = '$project_title',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`june_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_ps_dt`
							SET
								`project_id` = '$project_id',
								`project_title` = '$project_title',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`july_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_ps_dt`
							SET
								`project_id` = '$project_id',
								`project_title` = '$project_title',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`august_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_ps_dt`
							SET
								`project_id` = '$project_id',
								`project_title` = '$project_title',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`september_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '1' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_ps_dt`
							SET
								`project_id` = '$project_id',
								`project_title` = '$project_title',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`october_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '1' && $is_nov == '1' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_ps_dt`
							SET
								`project_id` = '$project_id',
								`project_title` = '$project_title',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`november_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '1' && $is_nov == '1' && $is_dec == '1') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_ps_dt`
							SET
								`project_id` = '$project_id',
								`project_title` = '$project_title',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`december_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}else{
							$query = $this->db->query("
							UPDATE
								`tbl_saob_ps_dt`
							SET
								`project_id` = '$project_id',
								`project_title` = '$project_title',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`proposed_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}

					}else{
						if ($is_jan == '1' && $is_feb == '0' && $is_mar == '0' && $is_apr == '0' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_ps_dt` (
									`project_id`,
									`project_title`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`january_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$project_title',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '0' && $is_apr == '0' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_ps_dt` (
									`project_id`,
									`project_title`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`february_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$project_title',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '0' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_ps_dt` (
									`project_id`,
									`project_title`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`march_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$project_title',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_ps_dt` (
									`project_id`,
									`project_title`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`april_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$project_title',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_ps_dt` (
									`project_id`,
									`project_title`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`may_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$project_title',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_ps_dt` (
									`project_id`,
									`project_title`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`june_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$project_title',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_ps_dt` (
									`project_id`,
									`project_title`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`july_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$project_title',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_ps_dt` (
									`project_id`,
									`project_title`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`august_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$project_title',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_ps_dt` (
									`project_id`,
									`project_title`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`september_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$project_title',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '1' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_ps_dt` (
									`project_id`,
									`project_title`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`october_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$project_title',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '1' && $is_nov == '1' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_ps_dt` (
									`project_id`,
									`project_title`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`november_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$project_title',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '1' && $is_nov == '1' && $is_dec == '1') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_ps_dt` (
									`project_id`,
									`project_title`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`december_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$project_title',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}else{
							$query = $this->db->query("
								INSERT INTO `tbl_saob_ps_dt`(
									`project_id`,
									`project_title`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`proposed_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$project_title',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$revised_allotment',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}

					}

				}
			}

			//INSERTING MOOE DT DATA
			if (!empty($budgetmooedtdata)) {
				for($aa = 0; $aa < count($budgetmooedtdata); $aa++){
					$medata = explode("x|x",$budgetmooedtdata[$aa]);
					$object_code = $medata[0]; 
					$particulars = $medata[1]; 
					$code = $medata[2]; 
					$approved_budget = $medata[3]; 
					$revised_allotment = $medata[4]; 
					$revision = $medata[5];  
					$proposed_revision = $medata[6]; 
					$dtid = $medata[7]; 

					if (!empty($dtid)) {
						if ($is_jan == '1' && $is_feb == '0' && $is_mar == '0' && $is_apr == '0' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_mooe_dt`
							SET
								`project_id` = '$project_id',
								`project_title` = '$project_title',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`january_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '0' && $is_apr == '0' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_mooe_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`february_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '0' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_mooe_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`march_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_mooe_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`april_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_mooe_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`may_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_mooe_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`june_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_mooe_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`july_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_mooe_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`august_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_mooe_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`september_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '1' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_mooe_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`october_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '1' && $is_nov == '1' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_mooe_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`november_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '1' && $is_nov == '1' && $is_dec == '1') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_mooe_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`december_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}else{
							$query = $this->db->query("
							UPDATE
								`tbl_saob_mooe_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`proposed_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}

					}else{
						if ($is_jan == '1' && $is_feb == '0' && $is_mar == '0' && $is_apr == '0' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_mooe_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`january_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '0' && $is_apr == '0' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_mooe_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`february_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '0' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_mooe_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`march_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_mooe_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`april_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_mooe_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`may_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_mooe_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`june_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_mooe_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`july_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_mooe_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`august_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_mooe_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`september_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '1' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_mooe_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`october_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '1' && $is_nov == '1' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_mooe_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`november_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '1' && $is_nov == '1' && $is_dec == '1') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_mooe_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`december_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}else{
							$query = $this->db->query("
								INSERT INTO `tbl_saob_mooe_dt`(
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`proposed_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$revised_allotment',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}

					}

				}
			}

			//INSERTING CO DT DATA
			if (!empty($budgetcodtdata)) {
				for($aa = 0; $aa < count($budgetcodtdata); $aa++){
					$medata = explode("x|x",$budgetcodtdata[$aa]);
					$object_code = $medata[0]; 
					$particulars = $medata[1]; 
					$code = $medata[2]; 
					$approved_budget = $medata[3]; 
					$revised_allotment = $medata[4]; 
					$revision = $medata[5];  
					$proposed_revision = $medata[6]; 
					$dtid = $medata[7]; 

					if (!empty($dtid)) {
						if ($is_jan == '1' && $is_feb == '0' && $is_mar == '0' && $is_apr == '0' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_co_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`january_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '0' && $is_apr == '0' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_co_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`february_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '0' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_co_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`march_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_co_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`april_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_co_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`may_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_co_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`june_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_co_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`july_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_co_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`august_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_co_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`september_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '1' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_co_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`october_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '1' && $is_nov == '1' && $is_dec == '0') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_co_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`november_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '1' && $is_nov == '1' && $is_dec == '1') {
							$query = $this->db->query("
							UPDATE
								`tbl_saob_co_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`december_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}else{
							$query = $this->db->query("
							UPDATE
								`tbl_saob_co_dt`
							SET
								`project_id` = '$project_id',
								`object_code` = '$object_code',
								`particulars` = '$particulars',
								`code` = '$code',
								`approved_budget` = '$approved_budget',
								`revised_allotment` = '$revised_allotment',
								`proposed_revision` = '$proposed_revision'
							WHERE
								`recid` = '$dtid'
							");
						}

					}else{
						if ($is_jan == '1' && $is_feb == '0' && $is_mar == '0' && $is_apr == '0' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_co_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`january_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '0' && $is_apr == '0' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_co_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`february_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '0' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_co_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`march_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '0' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_co_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`april_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '0' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_co_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`may_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '0'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_co_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`june_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '0' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_co_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`july_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '0' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_co_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`august_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '0' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_co_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`september_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '1' && $is_nov == '0' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_co_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`october_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '1' && $is_nov == '1' && $is_dec == '0') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_co_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`november_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}elseif ($is_jan == '1' && $is_feb == '1' && $is_mar == '1' && $is_apr == '1' && $is_may == '1' && $is_jun == '1' && $is_jul == '1'
							&& $is_aug == '1' && $is_sep == '1' && $is_oct == '1' && $is_nov == '1' && $is_dec == '1') {
							$query = $this->db->query("
								INSERT INTO `tbl_saob_co_dt` (
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`revision`,
									`december_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$proposed_revision',
									'0.0000',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}else{
							$query = $this->db->query("
								INSERT INTO `tbl_saob_co_dt`(
									`project_id`,
									`object_code`,
									`particulars`,
									`code`,
									`approved_budget`,
									`revised_allotment`,
									`proposed_revision`,
									`added_at`,
									`added_by`
								)
								VALUES(
									'$project_id',
									'$object_code',
									'$particulars',
									'$code',
									'$approved_budget',
									'$revised_allotment',
									'$proposed_revision',
									NOW(),
									'{$this->cuser}'
								)
							");
						}

					}
				}
			}

			$status = "SAOB Updated Successfully!";
			$color = "info";
		}

		if ($query) {
			// Echo JavaScript to show the toast and then redirect
			echo "
			<script>
				document.getElementById('submitBtn').disabled = true;
				toastr.$color('{$status}!', 'Well Done!', {
						progressBar: true,
						closeButton: true,
						timeOut:2500,
					});
				setTimeout(function() {
						window.location.href = 'mysaobrpt?meaction=MAIN&recid=$project_id'; // Redirect to MAIN view
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

	public function get_ctr_saob($class,$supp,$dbname,$mfld='') { 
		$accessquery = $this->db->query("
		CREATE TABLE if not exists `myctr_saob` (
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
		
		$qctr = $this->db->query("select {$xfield} from myctr_saob WHERE CTR_YEAR = '$xsysyear' AND CTR_MONTH = '$xsysmonth' AND CTR_DAY = '$xsysday'  limit 1");
		if($qctr->getNumRows() == 0) {
			$xnumb = '00001';
			$query = $this->db->query( "insert into myctr_saob (CTR_YEAR,CTR_MONTH,CTR_DAY,{$xfield}) values('$xsysyear','$xsysmonth','$xsysday','$xnumb')");
			$qctr->freeResult();
		} else {
			$qctr->freeResult();
			$qctr = $this->db->query( "select {$xfield} MYFIELD from myctr_saob WHERE CTR_YEAR = '$xsysyear' AND CTR_MONTH = '$xsysmonth' AND CTR_DAY = '$xsysday' limit 1");
			$rctr = $qctr->getRowArray();
			if(trim($rctr['MYFIELD'],' ') == '') { 
				$xnumb = '00001';
			} else {
				$xnumb = $rctr['MYFIELD'];
				$qctr = $this->db->query("select ('{$xnumb}' + 1) XNUMB");
				$rctr = $qctr->getRowArray();
				$xnumb = trim($rctr['XNUMB'],' ');
				$xnumb = str_pad($xnumb + 0,5,"0",STR_PAD_LEFT);
				$query = $this->db->query("update myctr_saob set {$xfield} = '{$xnumb}'");
			}
		}
		return  $class . '-' . $xnumb . '-' . $xsysmonth . $xsysday;//.$supp
	} 

	
} //end main class
?>