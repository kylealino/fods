<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$recid = $this->request->getPostGet('recid');

$uacs_category_id = "";
$expenditure_category = "";
$object_of_expenditures = "";
$parent_category = "";
$code = "";
$counter = 1;

if(!empty($recid) || !is_null($recid)) { 

    $query = $this->db->query("
    SELECT
        a.`recid`,
        a.`uacs_category_id` ,
        (select `expenditure_category` FROM `tbl_uacs_category` WHERE `recid` = a.`uacs_category_id`) AS expenditure_category,
        a.`object_of_expenditures`,
        a.`parent_category`,
        a.`code`
    FROM
        `tbl_uacs` a
    WHERE 
        a.`recid` = '$recid'"
    );

    $data = $query->getRowArray();
    $uacs_category_id = $data['uacs_category_id'];
    $expenditure_category = $data['expenditure_category'];
    $object_of_expenditures = $data['object_of_expenditures'];
    $parent_category = $data['parent_category'];
    $code = $data['code'];

}
echo view('templates/myheader.php');
?>

<div class="container-fluid">
    <div class="row me-mypayee-outp-msg mx-0">
    </div>
    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />
    <div class="row mb-2 mt-0">
        <h4 class="fw-semibold mb-8">Unified Accounts Code Structure</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a class="text-muted text-decoration-none" href="<?=site_url();?>"><i class="ti ti-home fs-5"></i></a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Maintenance</li>
            <li class="breadcrumb-item" aria-current="page"><span class="form-label fw-bold">UACS</span></li>
            </ol>
        </nav>
    </div>
    <div class="card">
        <div class="card-header bg-info p-1">
            <div class="row">
                <div class="col-sm-6 d-flex align-items-center text-start">
                    <h6 class="mb-0 lh-base px-3 text-white fw-semibold d-flex align-items-center">
                        <i class="ti ti-pencil fs-5 me-1"></i>
                        <span class="pt-1">Entry</span>
                    </h6>
                </div>
                <div class="col-sm-6 text-end ">
                    <!-- <button type="button" id="btn_delete" name="btn_delete" class="btn_delete btn btn-sm btn-danger mx-3">
                        Remove Payee
                    </button> -->
                </div>
            </div>
		</div>						
        <div class="card-body p-0 px-4 py-2 my-2">
            <form action="<?=site_url();?>myuacs?meaction=MAIN-SAVE" method="post" class="myuacs-validation">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span>Expenditure Category:</span>
                            </div>
                            <div class="col-sm-8">
                                <?php if(!empty($recid)):?>
                                    <select name="selUacsCat" id="selUacsCat" class="form-control select2 form-select-sm show-tick">
                                        <option selected value="<?=$expenditure_category;?>"><?=$expenditure_category;?></option>
                                        <?php foreach($uacscategorydata as $data): ?>
                                            <option 
                                                value="<?= $data['expenditure_category'] ?>"
                                                data-uacsid="<?= $data['uacs_category_id'] ?>"
                                            >
                                                <?= $data['expenditure_category'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" id="uacs_category_id" name="uacs_category_id" value="<?=$uacs_category_id;?>" class="form-control form-control-sm" readonly />
                                <?php else:?>
                                    <select name="selUacsCat" id="selUacsCat" class="form-control select2 form-select-sm show-tick">
                                        <option selected value="">Choose...</option>
                                        <?php foreach($uacscategorydata as $data): ?>
                                            <option 
                                                value="<?= $data['expenditure_category'] ?>"
                                                data-uacsid="<?= $data['uacs_category_id'] ?>"
                                            >
                                                <?= $data['expenditure_category'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" id="uacs_category_id" name="uacs_category_id" value="" class="form-control form-control-sm" readonly />
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span>Code:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="hidden" class="form-control form-control-sm" id="recid" name="recid" value="<?=$recid;?>"/>
                                <input type="text" id="code" name="code" value="<?=$code;?>" class="form-control form-control-sm" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span>Parent Expenditure:</span>
                            </div>
                            <div class="col-sm-8">
                                <select id="parent_category" name="parent_category" class="form-select form-select-sm">
                                    <?php if(!empty($recid)):?>
                                    <option value="<?=$parent_category;?>"><?=$parent_category;?></option>
                                    <?php endif;?>
                                    <option value="Personal Services">Personal Services</option>
                                    <option value="Maintenance and Other Operating Expenses">Maintenance and Other Operating Expenses</option>
                                    <option value="Capital Outlay">Capital Outlay</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-2">
                        <div class="row">
                            <div class="col-sm-4">
                                <span>Object of Expenditure:</span>
                            </div>
                            <div class="col-sm-8">
                                <textarea name="object_of_expenditures" id="object_of_expenditures" placeholder="" rows="4" class="form-control form-control-sm"><?=$object_of_expenditures;?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">  
                    <div class="col-sm-12 text-end">
                        <button type="submit" class="btn bg-<?= empty($recid) ? 'success' : 'info' ?>-subtle text-<?= empty($recid) ? 'success' : 'info' ?> btn-sm rounded-pill "><i class="ti ti-brand-doctrine mt-1 fs-4 me-1"></i>
                            <?= empty($recid) ? 'Save' : 'Update' ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header bg-info p-1">
            <div class="row">
                <div class="col-sm-6 d-flex align-items-center text-start">
                    <h6 class="mb-0 lh-base px-3 text-white fw-semibold d-flex align-items-center">
                        <i class="ti ti-list fs-5 me-1"></i>
                        <span class="pt-1">List</span>
                    </h6>
                </div>
            </div>
		</div>						
        <div class="card-body p-0 px-4 py-2 my-2">
            <table id="datatablesSimple" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Expenditure Category</th>
                        <th>Object of Expenditures</th>
                        <th>Code</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($uacsdata)):
                        
                        foreach ($uacsdata as $data):
                            $recid = $data['recid'];
                            $expenditure_category = $data['expenditure_category'];
                            $object_of_expenditures = $data['object_of_expenditures'];
                            $code = $data['code'];
                    ?>
                    <tr>
                        <td class="text-center align-middle">
                            <a class="text-info nav-icon-hover" href="myuacs?meaction=MAIN&recid=<?=$recid;?>">
                                Review
                            </a>
                        </td>
                        <td class="text-center"><?=$expenditure_category;?></td>
                        <td class="text-center"><?=$object_of_expenditures;?></td>
                        <td class="text-center"><?=$code;?></td>
                    </tr>
                    <?php endforeach; endif;?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row uacs-outp-msg">
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
<script src="<?=base_url('assets/js/maintenance/myuacs.js');?>"></script>
<script src="<?=base_url('assets/js/mysysapps.js');?>"></script>
<script>
    __mysys_uacs_ent.__uacs_saving();
    $(document).ready(function () {
        $('#datatablesSimple').DataTable({
            pageLength: 5,
            lengthChange: false,
            language: {
            search: "Search:"
            }
        });
    });

    $(document).on('change', '#selUacsCat', function() {
        var selected = $(this).find('option:selected');

        // Extract data from selected option
        var uacsid = selected.data('uacsid') || '';
        // Set the values into inputs
        $('#uacs_category_id').val(uacsid);
    });

</script>

<?php
    echo view('templates/myfooter.php');
?>


