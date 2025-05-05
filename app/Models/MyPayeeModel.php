<?php
namespace App\Models;
use CodeIgniter\Model;

class MyPayeeModel extends Model
{

    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
		$this->db = \Config\Database::connect();
		$this->cuser = $this->session->get('__xsys_myuserzicas__');
        
    }

	public function payee_save() { 
		$recid = $this->request->getPostGet('recid');
		$payee_name = $this->request->getPostGet('payee_name');
		$payee_account_num = $this->request->getPostGet('payee_account_num');
		$payee_office = $this->request->getPostGet('payee_office');
		$payee_tin = $this->request->getPostGet('payee_tin');
		$payee_address = $this->request->getPostGet('payee_address');
		$disb_method = $this->request->getPostGet('disb_method');
		$currency = $this->request->getPostGet('currency');

		if (empty($payee_name)) {
			echo "
			<script>
			toastr.error('Payee name is required!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}
		if (empty($payee_account_num)) {
			echo "
			<script>
			toastr.error('Account number is required!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}
		if (empty($payee_office)) {
			echo "
			<script>
			toastr.error('Office is required!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}
		if (empty($payee_tin)) {
			echo "
			<script>
			toastr.error('TIN is required!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}
		if (empty($payee_address)) {
			echo "
			<script>
			toastr.error('Address is required!', 'Oops!', {
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
				INSERT INTO `tbl_payee` (
					`payee_name`, 
					`payee_account_num`, 
					`payee_office`, 
					`payee_tin`, 
					`payee_address`, 
					`disb_method`, 
					`currency`, 
					`added_on`, 
					`added_by`
				) VALUES (
					'$payee_name',
					'$payee_account_num',
					'$payee_office',
					'$payee_tin',
					'$payee_address',
					'$disb_method',
					'$currency',
					NOW(),
					'{$this->cuser}'
				)
			");
			$status = "Payee Saved successfully";
			$color = "success";
		}else{
			$query = $this->db->query("
				UPDATE tbl_payee SET 
				`payee_name` = '$payee_name', 
				`payee_account_num` = '$payee_account_num',
				`payee_office` = '$payee_office',
				`payee_tin` = '$payee_tin',
				`payee_address` = '$payee_address',
				`disb_method` = '$disb_method',
				`currency` = '$currency'
				WHERE `recid` = '$recid'
			");
			$status = "Payee Updated successfully";
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
						window.location.href = 'mypayee?meaction=MAIN'; // Redirect to MAIN view
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
			<script>
				toastr.warning('{$status}!', 'Well Done!', {
						progressBar: true,
						closeButton: true,
						timeOut:2500,
					});
				setTimeout(function() {
						window.location.href = 'mypayee?meaction=MAIN'; // Redirect to MAIN view
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
	
} //end main class
?>