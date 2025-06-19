<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$prjctdata = $this->request->getPostGet('prjctdata');
$action = $this->request->getPostGet('action');
$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');

$recid = "";
$budget_recid = "";
$serial_no ="";
$trxno = "";
$project_title = "";
$responsibility_code = "";
$fund_cluster_code = "";
$division_name = "";
$is_pending = "";
$is_approved = "";
$is_disapproved = "";
$approver = "";
$remarks = "";
$program_title = "";
$total_duration = "";
$duration_from = "";
$duration_to = "";
$program_leader = "";
$monitoring_agency = "";
$collaborating_agencies = "";
$implementing_agency = "";
$MDL_jsscript = "";
$counter = 1;

if (!empty($prjctdata) || !is_null($prjctdata)) {
    $query = $this->db->query("
    SELECT
        `recid`,
        `recid` budget_recid,
        `project_title`,
        `program_title`,
        `responsibility_code`,
        `fund_cluster_code`
    FROM
        `tbl_budget_hd`
    WHERE 
        `project_title` = '$prjctdata'"
    );

    $data = $query->getRowArray();
    $project_title = $data['project_title'];
    $program_title = $data['program_title'];
    $responsibility_code = $data['responsibility_code'];
    $fund_cluster_code = $data['fund_cluster_code'];
    $budget_recid = $data['budget_recid'];
}



echo view('templates/myheader.php');
?>

<div class="container-fluid">
    <div class="row me-myorsburs-appr-outp-msg mx-0">
    </div>
    
    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />
    <div class="row mb-2 mt-0">
        <h4 class="fw-semibold mb-8">Obligation Request and Status</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a class="text-muted text-decoration-none" href="<?=site_url();?>"><i class="ti ti-home fs-5"></i></a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Budget</li>
            <li class="breadcrumb-item" aria-current="page"><span class="form-label fw-bold">ORS</span></li>
            </ol>
        </nav>
    </div>
    <div class="card rounded">
        <div class="row myorsburs-outp-msg mx-0">

        </div>
        <div class="card-header   bg-info p-1">
            <div class="row d-flex align-items-center">
                <div class="col-sm-6 d-flex align-items-center text-start">
                    <h6 class="mb-0 lh-base px-3 text-white fw-semibold d-flex align-items-center">
                        <i class="ti ti-pencil fs-5 me-1"></i>
                        <span class="pt-1">Entry</span>
                    </h6>
                </div>
                <div class="col-sm-6 text-end ">
                </div>
            </div>
		</div>						
        <div class="card-body p-0 px-4 py-2 my-2">
            <form action="<?=site_url();?>myorsburs?meaction=MAIN-SAVE" method="post" class="myorsburs-validation">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <span>Program Title</span>
                            </div>
                            <div class="col-sm-10">
                                <input type="hidden" class="form-control form-control-sm" id="recid" name="recid" value="<?=$recid;?>"/>
                                <input type="hidden" class="form-control form-control-sm" id="serial_no" name="serial_no" value="<?=$serial_no;?>"/>
                                <input type="text" class="form-control form-control-sm" id="program_title" name="program_title" value="<?=$program_title;?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row mb-2">
                                    <div class="col-sm-4">
                                        <span>Particulars</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <textarea name="approved_remarks" id="approved_remarks" placeholder="" rows="3" class="form-control form-control-sm"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="row mb-2">
                                    <div class="col-sm-4">
                                        <span>Funding Source:</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <select name="" id="funding_source" class="form-select form-select-sm">
                                            <option value="">Choose...</option>
                                            <option value="101101">101101</option>
                                            <option value="102101">102101</option>
                                            <option value="102406">102406</option>
                                            <option value="102407">102407</option>
                                            <option value="104102">104102</option>
                                            <option value="105462">105462</option>
                                            <option value="308601">308601</option>
                                            <option value="308602">308602</option>
                                            <option value="308603">308603</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">Payee:</span>
                            </div>
                            <div class="col-sm-8">
                                <select name="payee_name" id="payee_name" class="form-control select2 form-select-sm show-tick">
                                    <option selected value="">Choose...</option>
                                    <?php foreach($payeedata as $data): ?>
                                        <option 
                                            value="<?= $data['payee_name'] ?>"
                                            data-office="<?= $data['payee_office'] ?>"
                                            data-address="<?= $data['payee_address'] ?>"
                                        >
                                            <?= $data['payee_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">Office:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="payee_office" name="payee_office" value="" class="form-control form-control-sm bg-light"  />
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">Address:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="payee_address" name="payee_address" value="" class="form-control form-control-sm bg-light"  />
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="row mb-2">
                    <div class="col-sm-12">
                        <!-- Nav tabs -->
                        <ul class="nav nav-pills mb-3 gap-2" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active rounded-pill px-3 fs-3 fw-semibold" data-bs-toggle="tab" href="#ps-pill" role="tab">
                                I. Personal Services
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link rounded-pill px-3 fs-3 fw-semibold" data-bs-toggle="tab" href="#mooe-pill" role="tab">
                                II. Maintenance
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link rounded-pill px-3 fs-3 fw-semibold" data-bs-toggle="tab" href="#co-pill" role="tab">
                                III. Capital Outlay
                                </a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content border">
                            <!-- PS TAB CONTENT -->
                            <div class="tab-pane active p-3" id="ps-pill" role="tabpanel">
                                <div class="row">
                                    <div class="table-responsive pe-2 ps-0">
                                        <div class="col-md-12 mb-2">
                                            <span class="ms-3 fw-bold">Direct Cost:</span>
                                            <table id="budget_line_items" class="table-sm table-striped budgetdata-list">
                                                <thead>
                                                    <th class="text-center">
                                                        <a class="text-info px-2 fs-7 bg-hover-primary nav-icon-hover position-relative z-index-5" id="btn_trxjournalitem_add" href="javascript:__mysys_ors_ent.my_add_budget_line();"><i class="ti ti-new-section"></i></a>
                                                    </th>
                                                    <th class="text-center align-middle">Project Title</th>
                                                    <th class="text-center align-middle">Responsibility Code</th>
                                                    <th class="text-center align-middle">MFO/PAP</th>
                                                    <th class="text-center align-middle">Sub-object Code</th>
                                                    <th class="text-center align-middle">UACS</th>
                                                    <th class="text-center align-middle">Amount</th>
                                                </thead>
                                                <tbody>
                                                    <tr style="display:none;">
                                                        <td class="text-center align-middle">
                                                            <div class="d-inline-flex gap-1 justify-content-center">
                                                                <a class="text-danger fs-5 bg-hover-danger nav-icon-hover"
                                                                href="javascript:void(0)"
                                                                onclick="$(this).closest('tr').remove();">
                                                                    <i class="ti ti-trash"></i>
                                                                </a>
                                                                <a class="text-success fs-5 bg-hover-primary nav-icon-hover"
                                                                href="javascript:void(0)"
                                                                title="Add rows above"
                                                                onclick="__mysys_ors_ent.my_add_budget_line_above(this);">
                                                                    <i class="ti ti-plus"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <select name="selProject" class="selProject form" style="width:600px; height:30px;">
                                                                <option selected value ="">Choose...</option>
                                                                <?php foreach($projectdata as $data){
                                                                    $project_title = $data['project_title'];
                                                                    $responsibility_code = $data['responsibility_code'];
                                                                ?>
                                                                    <option value="<?=$project_title?>" data-rc="<?=$responsibility_code;?>"><?=$project_title?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="responsibility_code"  value="" size="35"  name="responsibility_code" class="responsibility_code text-center" disabled>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="mfopap"  value="" size="25" name="mfopap" data-dtid="" class="mfopap"/>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <select name="selUacs" class="selUacs form" style="width:300px; height:30px;">
                                                                <option selected value ="">Choose...</option>
                                                                <?php foreach($psuacsdata as $data){
                                                                    $sub_object_code = $data['sub_object_code'];
                                                                    $uacs_code = $data['uacs_code'];
                                                                ?>
                                                                    <option value="<?=$sub_object_code?>" data-uacs="<?=$uacs_code;?>"><?=$sub_object_code?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="" size="25" name="uacs" data-dtid="" class="uacs text-center" disabled/>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="amount"  value="" size="25" step="any" name="amount" data-dtid="" class="amount text-center"/>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <hr>
                                        <div class="col-sm-12">
                                            <span class="ms-3 fw-bold">Indirect Cost:</span>
                                            <table id="budget_indirect_line_items" class="table-sm table-striped budgetdata-indirect-list">
                                                <thead>
                                                    <th class="text-center">
                                                        <a class="text-info px-2 fs-7 bg-hover-primary nav-icon-hover position-relative z-index-5" disabled id="btn_trxjournalitem_add"><i class="ti ti-new-section"></i></a>
                                                    </th>
                                                    <th class="text-center align-middle">PS - Particulars</th>
                                                    <th class="text-center align-middle">UACS.</th>
                                                    <th class="text-center align-middle">Approved Budget</th>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>                  
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- MOOE TAB CONTENT -->
                            <div class="tab-pane p-3" id="mooe-pill" role="tabpanel">
                                <div class="row">
                                    <div class="table-responsive pe-2 ps-0">
                                        <div class="col-md-12 mb-2">
                                            <span class="ms-3 fw-bold">Direct Cost:</span>
                                            <table id="budget_mooe_line_items" class="table-sm table-striped budgetmooedata-list">
                                                <thead>
                                                    <th class="text-center">
                                                        <a class="text-info px-2 fs-7 bg-hover-primary nav-icon-hover position-relative z-index-5" disabled id="btn_budgetmooeitem_add" href="javascript:__mysys_budget_allotment_ent.my_add_budget_mooe_line();"><i class="ti ti-new-section"></i></a>
                                                    </th>
                                                    <th class="text-center align-middle">MOOE - Particulars</th>
                                                    <th class="text-center align-middle">UACS.</th>
                                                    <th class="text-center align-middle">Approved Budget</th>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-sm-12">
                                            <span class="ms-3 fw-bold">Indirect Cost:</span>
                                            <table id="budget_mooe_indirect_line_items" class="table-sm table-striped budgetmooedata-indirect-list">
                                                <thead>
                                                    <th class="text-center">
                                                        <a class="text-info px-2 fs-7 bg-hover-primary nav-icon-hover position-relative z-index-5" id="btn_budgetmooeitem_add" disabled href="javascript:__mysys_budget_allotment_ent.my_add_budget_indirect_mooe_line();"><i class="ti ti-new-section"></i></a>
                                                    </th>
                                                    <th class="text-center align-middle">MOOE - Particulars</th>
                                                    <th class="text-center align-middle">UACS.</th>
                                                    <th class="text-center align-middle">Approved Budget</th>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane p-3" id="co-pill" role="tabpanel">
                                <div class="row">
                                    <div class="table-responsive pe-2 ps-0">
                                        <div class="col-md-12 mb-2">
                                            <span class="ms-3 fw-bold">Direct Cost:</span>
                                            <table id="budget_co_line_items" class="table-sm table-striped budgetcodata-list">
                                                <thead>
                                                    <th class="text-center">
                                                        <a class="text-info px-2 fs-7 bg-hover-primary nav-icon-hover position-relative z-index-5" id="btn_budgetcoitem_add" disabled href="javascript:__mysys_budget_allotment_ent.my_add_budget_co_line();"><i class="ti ti-new-section"></i></a>
                                                    </th>
                                                    <th class="text-center align-middle">CO - Particulars</th>
                                                    <th class="text-center align-middle">UACS.</th>
                                                    <th class="text-center align-middle">Approved Budget</th>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-sm-12">
                                            <span class="ms-3 fw-bold">Indirect Cost:</span>
                                            <table id="budget_indirect_co_line_items" class="table-sm table-striped budgetcodata-indirect-list">
                                                <thead>
                                                    <th class="text-center">
                                                        <a class="text-info px-2 fs-7 bg-hover-primary nav-icon-hover position-relative z-index-5" id="btn_budgetcoitem_add" disabled href="javascript:__mysys_budget_allotment_ent.my_add_budget_indirect_co_line();"><i class="ti ti-new-section"></i></a>
                                                    </th>
                                                    <th class="text-center align-middle">CO - Particulars</th>
                                                    <th class="text-center align-middle">UACS.</th>
                                                    <th class="text-center align-middle">Approved Budget</th>
                                                </thead>
                                                <tbody>
                                                    <?php if(!empty($budget_recid)):
                                                        $query = $this->db->query("
                                                        SELECT
                                                            `recid`,
                                                            `particulars`,
                                                            `code`,
                                                            `approved_budget`
                                                        FROM
                                                            `tbl_budget_indirect_co_dt`
                                                        WHERE 
                                                            `project_id` = '$budget_recid'"
                                                        );
                                                        $result = $query->getResultArray();
                                                        foreach ($result as $data):
                                                            $dt_id = $data['recid'];
                                                            $particulars = $data['particulars'];
                                                            $code = $data['code'];
                                                            $approved_budget = $data['approved_budget'];
                                                        ?>
                                                    <tr>
                                                        <td class="text-center align-middle">
                                                            <a class="text-info px-2 fs-5 bg-hover-danger nav-icon-hover position-relative z-index-5" 
                                                            href="javascript:void(0)">
                                                            <i class="ti ti-trash"></i>
                                                            </a>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="particulars"  value="<?=$particulars;?>" style="width:500px; height:30px;"  name="particulars" class="particulars text-center" disabled>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="<?=$code;?>" size="25"  name="uacs" class="uacs text-center" disabled>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="approved_budget"  value="<?=$approved_budget;?>" size="25" data-dtid="<?=$dt_id;?>" name="approved_budget" class="approved_budget text-center" disabled/>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; endif;?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-6">

                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-6">

                                            </div>
                                            <div class="col-sm-6">
                                                <span class="fw-bolder">Total Approved Budget:</span>
                                                <input type="number" id="total_amount_combined" name="total_amount_combined" value="" class="form-control form-control-sm text-center fw-bold" style="border-bottom: 2px solid #000;"  readonly/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title text-white" id="confirmDeleteModalLabel">Confirm Delete</h5>
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

<div class="modal fade" id="confirmApproveModal" tabindex="-1" aria-labelledby="confirmApproveModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title text-white" id="confirmApproveModalLabel">Confirm Approval</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <span>Approver:</span>
                    </div>
                    <div class="col-sm-8">
                        <?php if($action == 'appr_approved' && !empty($recid)):?>
                            <input type="text" id="approved_by" name="approved_by" value="<?=$approver;?>" class="form-control form-control-sm" readonly/>
                        <?php else:?>
                            <input type="text" id="approved_by" name="approved_by" value="<?=$this->cuser;?>" class="form-control form-control-sm" readonly/>
                        <?php endif;?>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <span>Remarks:</span>       
                    </div>
                    <div class="col-sm-8">
                    <?php if($action == 'appr_approved' && !empty($recid)):?>
                        <textarea name="approved_remarks" id="approved_remarks" placeholder="" rows="3" class="form-control form-control-sm" disabled><?=$remarks;?></textarea>
                    <?php else:?>
                        <textarea name="approved_remarks" id="approved_remarks" placeholder="" rows="3" class="form-control form-control-sm"></textarea>
                    <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <?php if($action == 'appr_approved' && !empty($recid)):?>
            <button type="button" class="btn bg-secondary-subtle btn-sm" data-bs-dismiss="modal">Back</button>
        <?php else:?>
            <button type="button" class="btn btn-success btn-sm px-3" id="confirmApproveBtn">Approve</button>
            <button type="button" class="btn bg-secondary-subtle btn-sm" data-bs-dismiss="modal">Cancel</button>
        <?php endif;?>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="confirmDisapproveModal" tabindex="-1" aria-labelledby="confirmDisapproveModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title text-white" id="confirmDisapproveModalLabel">Confirm Disapproval</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <span>Disapprover:</span>
                    </div>
                    <div class="col-sm-8">
                        <?php if($action == 'appr_disapproved' && !empty($recid)):?>
                            <input type="text" id="disapproved_by" name="disapproved_by" value="<?=$approver;?>" class="form-control form-control-sm" readonly/>
                        <?php else:?>
                            <input type="text" id="disapproved_by" name="disapproved_by" value="<?=$this->cuser;?>" class="form-control form-control-sm" readonly/>
                        <?php endif;?>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <span>Remarks:</span>       
                    </div>
                    <div class="col-sm-8">
                        <?php if($action == 'appr_disapproved' && !empty($recid)):?>
                            <textarea name="disapproved_remarks" id="disapproved_remarks" placeholder="" rows="3" class="form-control form-control-sm" disabled><?=$remarks;?></textarea>
                        <?php else:?>
                            <textarea name="disapproved_remarks" id="disapproved_remarks" placeholder="" rows="3" class="form-control form-control-sm"></textarea>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <?php if($action == 'appr_disapproved' && !empty($recid)):?>
            <button type="button" class="btn bg-secondary-subtle btn-sm" data-bs-dismiss="modal">Back</button>
        <?php else:?>
            <button type="button" class="btn btn-danger btn-sm px-3" id="confirmDisapproveBtn">Disapprove</button>
            <button type="button" class="btn bg-secondary-subtle btn-sm" data-bs-dismiss="modal">Cancel</button>
        <?php endif;?>
      </div>
    </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/ors/ors.js');?>"></script>
<script src="<?=base_url('assets/js/mysysapps.js');?>"></script>

<!-- Bootstrap JS (and Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<?php
	echo $MDL_jsscript;
?>
<script>
    
    // $(document).ready(function () {
    //     $('#datatablesSimple').DataTable({
    //         pageLength: 5,
    //         lengthChange: false,
    //         order: [[4, 'desc']],
    //         language: {
    //         search: "Search:"
    //         }
    //     });
    
    // });

    $(document).on('change', '.selUacs', function() {
        var selectedCode = $(this).find('option:selected').data('uacs');
        $(this).closest('tr').find('.uacs').val(selectedCode);
    });

    $(document).on('change', '.selProject', function() {
        var selectedRC = $(this).find('option:selected').data('rc');
        $(this).closest('tr').find('.responsibility_code').val(selectedRC);
    });

    $(document).on('change', '#payee_name', function() {
        var selected = $(this).find('option:selected');

        // Extract data from selected option
        var office = selected.data('office') || '';
        var address = selected.data('address') || '';
        
        // Set the values into inputs
        $('#payee_office').val(office);
        $('#payee_address').val(address);

    });

    $(document).on('change', '#program_title', function() {
        var selected = $(this).find('option:selected');

        // Extract data from selected option
        var projectitle = selected.data('projectitle') || '';
        var rescode = selected.data('rescode') || '';
        var fundcode = selected.data('fundcode') || '';
        
        // Set the values into inputs
        $('#project_title').val(projectitle);
        $('#fund_cluster_code').val(rescode);
        $('#responsibility_code').val(fundcode);

    });

    document.getElementById('project_title').addEventListener('change', function () {
        const token = this.value;
        if (token) {
            window.location.href = `myors?meaction=MAIN&prjctdata=${token}`;
        }
    });
</script>
<?php
    echo view('templates/myfooter.php');
?>


