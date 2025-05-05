<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$recid = $this->request->getPostGet('recid');

$full_name = "";
$division = "";
$username = "";
$hash_password = "";
$hash_value = "";


if(!empty($recid) || !is_null($recid)) { 

    $query = $this->db->query("
    SELECT 
        `full_name`, 
        `division`, 
        `username`, 
        `hash_password`,
        `hash_value`
    FROM 
        `myua_user` 
    WHERE 
        `recid` = '$recid'"
    );

    $data = $query->getRowArray();
    $full_name = $data['full_name'];
    $division = $data['division'];
    $username = $data['username'];
    $hash_password = $data['hash_password'];
    $hash_value = $data['hash_value'];


}
echo view('templates/myheader.php');
?>
<head>
<style>
#datatablesSimple td {
    white-space: normal !important;
    word-wrap: break-word;
    word-break: break-word;
}
#datatablesSimple thead th {
        text-align: center;
    }

</style>

</head>
<div class="container-fluid">
    <div class="row me-myua-outp-msg mx-0">
    </div>
    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />
    <div class="card bg-secondary-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">User Management</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a class="text-muted text-decoration-none" href="<?=site_url();?>">Home</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Maintenance</li>
                        <li class="breadcrumb-item" aria-current="page"><span class="form-label fw-bold">User Management</span></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>		
    </div> 
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-secondary-subtle p-2">
                    <div class="row">
                        <div class="col-sm-6 text-start">
                            <h6 class="mb-0 lh-base  px-3">Register New User</h6>
                        </div>
                        <div class="col-sm-6 text-end ">

                        </div>
                    </div>
                </div>						
                <div class="card-body p-0 px-4 py-2 my-2">
                    <form action="<?=site_url();?>myua?meaction=MAIN-SAVE" method="post" class="myua-validation">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row mb-2 mt-2">
                                    <div class="col-sm-4">
                                        <span>Full Name:</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="hidden" class="form-control form-control-sm" id="recid" name="recid" value="<?=$recid;?>"/>
                                        <input type="text" id="full_name" name="full_name" value="<?=$full_name;?>" class="form-control form-control-sm"/>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4">
                                        <span>Division:</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" id="division" name="division" value="<?=$division;?>" class="form-control form-control-sm"/>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4">
                                        <span>Username:</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" id="username" name="username" value="<?=$username;?>" class="form-control form-control-sm"/>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4">
                                        <span>Password:</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" id="hash_value" name="hash_value" value="<?=$hash_value;?>" class="form-control form-control-sm"/>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4">
                                        <span>Encrypted Password:</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <textarea name="hash_password" id="hash_password" placeholder="-system-generated-" rows="4" class="form-control form-control-sm bg-light" readonly><?=$hash_password;?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">  
                            <div class="col-sm-12 text-end">
                                <button type="submit" class="btn bg-<?= empty($recid) ? 'success' : 'info' ?>-subtle text-<?= empty($recid) ? 'success' : 'info' ?> btn-sm"><i class="ti ti-brand-doctrine mt-1 fs-4 me-1"></i>
                                    <?= empty($recid) ? 'Save' : 'Update' ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-secondary-subtle p-2">
                    <h6 class="mb-0 lh-base  px-3">User List</h6>
                    <input type="hidden" id="atc_xx" value="" class="form-control form-control-sm" required />
                </div>						
                <div class="card-body p-0 px-4 py-2 my-2">
                    <table id="datatablesSimple" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Full Name</th>
                                <th>Division</th>
                                <th>Username</th>
                                <th>Password</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($myuadata)):
                                
                                foreach ($myuadata as $data):
                                    $list_recid = $data['recid'];
                                    $full_name = $data['full_name'];
                                    $division = $data['division'];
                                    $username = $data['username'];
                                    $hash_password = $data['hash_password'];
                            ?>
                            <tr>
                                <td class="text-center align-middle">
                                    <a class="text-info nav-icon-hover" href="myua?meaction=MAIN&recid=<?= $list_recid ?>">
                                         Review
                                    </a>
                                </td>
                                <td class="text-center"><?=$full_name;?></td>
                                <td class="text-center"><?=$division;?></td>
                                <td class="text-center"><?=$username;?></td>
                                <td class="text-center"><?=$hash_password;?></td>
                            </tr>
                            <?php endforeach; endif;?>
                        </tbody>
                    </table> 
                </div>
            </div>
        </div>
    </div>
    
    <?php if(!empty($recid)):?>
        <div class="card">
            <div class="card-header bg-secondary-subtle p-2">
                <div class="row">
                    <div class="col-sm-6 text-start">
                        <h6 class="mb-0 lh-base  px-3">Access Codes/Modules</h6>
                    </div>
                    <div class="col-sm-6 text-end">
                        <button type="button" id="btn_add_useraccess" name="btn_add_useraccess" class="btn_add_useraccess btn btn-sm btn-info mx-3">
                            Update User Access
                        </button>
                    </div>
                </div>
            </div>						
            <div class="card-body p-0 px-4 py-2 my-2">
                <div class="row">
                    <div class="col-sm-6">
                        <h6 class="mb-0 lh-base mb-2">Budget Allotment Access</h6>
                        <table id="datatablesSimple" class="table table-bordered table-hover accessdata-list">
                            <thead>
                                <tr>
                                    <th>Access Name</th>
                                    <th>Access Code</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($accessdata)):
                                    
                                    foreach ($accessdata as $data):
                                        $accessid = $data['recid'];
                                        $access_name = $data['access_name'];
                                        $access_code = $data['access_code'];
                                        $is_active = $data['is_active'];
                                        $with_ua = $data['with_ua'];
                                ?>
                                <tr>
                                    <td class="text-center">
                                        <input type="text" class="form-control form-control-sm border-0 text-center" name="access_name" data-dtid="<?= $with_ua === '1' ? $accessid : ''?>" id="" value="<?=$access_name;?>">
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control form-control-sm border-0 text-center" name="access_code" id="" value="<?=$access_code;?>">
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input form-switch" type="checkbox" <?= $is_active === '1' ? 'checked' : '' ?> role="switch" id="is_checked">
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; endif;?>
                            </tbody>
                        </table> 
                    </div>
                </div>

            </div>
        </div>
    <?php endif;?>
    <div class="row me-myua-access-outp-msg mx-0">
    </div>
</div>
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header bg-secondary-subtle text-white">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this payee?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success btn-sm px-3" id="confirmDeleteBtn">Yes</button>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/maintenance/myua.js');?>"></script>
<script src="<?=base_url('assets/js/mysysapps.js');?>"></script>
<script>
    __mysys_myua_ent.__user_saving();
    $(document).ready(function () {
    $('#datatablesSimple').DataTable({
        pageLength: 5,
        lengthChange: false,
        language: {
            search: "Search:"
        },
        columnDefs: [
            { targets: '_all', className: 'text-center' } // Center all columns
        ]
    });

    });
</script>
<?php
    echo view('templates/myfooter.php');
?>


