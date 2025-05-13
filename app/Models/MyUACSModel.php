<?php
namespace App\Models;
use CodeIgniter\Model;

class MyUACSModel extends Model
{

    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
		$this->db = \Config\Database::connect();
		$this->cuser = $this->session->get('__xsys_myuserzicas__');
        
    }

	public function uacs_save() { 
		$recid = $this->request->getPostGet('recid');
		$object_of_expenditures = $this->request->getPostGet('object_of_expenditures');
		$parent_category = $this->request->getPostGet('parent_category');
		$expenditure_category = $this->request->getPostGet('expenditure_category');
		$uacs_category_id = $this->request->getPostGet('uacs_category_id');
		$code = $this->request->getPostGet('code');

		$is_direct_cost = $this->request->getPostGet('is_direct_cost');
		$is_indirect_cost = $this->request->getPostGet('is_indirect_cost');
		

		if (empty($object_of_expenditures)) {
			echo "
			<script>
			toastr.error('Object of expenditure is required!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}
		if (empty($parent_category)) {
			echo "
			<script>
			toastr.error('Parent expenditure is required!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}
		if (empty($expenditure_category)) {
			echo "
			<script>
			toastr.error('Expenditure category is required!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}
		if (empty($code)) {
			echo "
			<script>
			toastr.error('Code is required!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}

		if (empty($recid)) {
			$query = $this->db->query("
				INSERT INTO `tbl_uacs`(
					`uacs_category_id`,
					`object_of_expenditures`,
					`code`,
					`added_on`,
					`added_by`,
					`active_status`,
					`parent_category`,
					`is_direct_cost`,
					`is_indirect_cost`,
				)
				VALUES(
					'$uacs_category_id',
					'$object_of_expenditures',
					'$code',
					NOW(),
					'{$this->cuser}',
					'1',
					'$parent_category',
					'$is_direct_cost',
					'$is_indirect_cost'
				)
			");
			$status = "UACS Saved successfully";
			$color = "success";
		}else{
			$query = $this->db->query("
				UPDATE
					`tbl_uacs`
				SET
					`uacs_category_id` = '$uacs_category_id',
					`object_of_expenditures` = '$object_of_expenditures',
					`code` = '$code',
					`parent_category` = '$parent_category',
					`is_direct_cost` = '$is_direct_cost',
					`is_indirect_cost` = '$is_indirect_cost'
				WHERE `recid` = '$recid'
			");
			$status = "UACS Updated successfully";
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
						window.location.href = 'myuacs?meaction=MAIN'; // Redirect to MAIN view
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
	
	public function payee_delete() { 
		$recid = $this->request->getPostGet('recid');

		$query = $this->db->query("
			DELETE FROM `tbl_payee` WHERE `recid` = '$recid'
		");
		$status = "Payee deleted successfully";
		

		if ($query) {
			// Echo JavaScript to show the toast and then redirect
			echo "
			<div class=\"alert alert-danger\" role=\"alert\"><strong>Info: </strong> $status </div>
				<script type='text/javascript'>

					// Redirect after a short delay (e.g., 2 seconds)
					setTimeout(function() {
						window.location.href = 'mypayee?meaction=MAIN'; // Redirect to MAIN view
					}, 2000); // 2-second delay for user to see the toast
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
	
} //end main class
?>