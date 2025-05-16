<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$recid = $this->request->getPostGet('recid');
$realign_id = $this->request->getPostGet('realign_id');
$action = $this->request->getPostGet('action');
$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');

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


echo view('templates/myheader.php');
?>

<div class="container-fluid">
    <div class="row me-mybudgetallotment-appr-outp-msg mx-0">
    </div>

    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />
    <div class="row mb-2 mt-0">
        <h4 class="fw-semibold mb-8">Obligation Request and Status / Budget Utilization Request and Status</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a class="text-muted text-decoration-none" href="<?=site_url();?>"><i class="ti ti-home fs-5"></i></a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Budget</li>
            <li class="breadcrumb-item" aria-current="page"><span class="form-label fw-bold">ORS/BURS</span></li>
            </ol>
        </nav>
    </div>
    <div class="card rounded">
        <div class="row mybudgetallotment-outp-msg mx-0">

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
            <form action="<?=site_url();?>mybudgetallotment?meaction=MAIN-SAVE" method="post" class="mybudgetallotment-validation">
                <div class="row">
                    <div class="col-sm-6 mb-2">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">Payee:</span>
                            </div>
                            <div class="col-sm-8">
                                <select name="selPayee" id="selPayee" class="form-control select2 form-select-sm show-tick">
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
                    <div class="col-sm-6 mb-2">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">Program Title:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="program_title" name="program_title" value="" class="form-control form-control-sm"/>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">Fund Cluster:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="fund_cluster" name="fund_cluster" value="" class="form-control form-control-sm"/>
                            </div>
                        </div>
                                          <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">Funding Source:</span>
                            </div>
                            <div class="col-sm-8">

                                <input type="text" id="funding_source" name="funding_source" value="" class="form-control form-control-sm"/>
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
                                                        <a class="text-info px-2 fs-7 bg-hover-primary nav-icon-hover position-relative z-index-5" id="btn_trxjournalitem_add" href="javascript:__mysys_budget_allotment_ent.my_add_budget_line();"><i class="ti ti-new-section"></i></a>
                                                    </th>
                                                    <th class="text-center align-middle">UACS.</th>
                                                    <th class="text-center align-middle">Approved Budget</th>
                                                </thead>
                                                <tbody>
                                                    <tr style="display:none;">
                                                        <td class="text-center align-middle">
                                                            <a class="text-info px-2 fs-5 bg-hover-danger nav-icon-hover position-relative z-index-5" 
                                                            href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                                            <i class="ti ti-trash"></i>
                                                            </a>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="" size="25"  name="uacs" class="uacs text-center" disabled>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="approved_budget"  value="" size="25" name="approved_budget" data-dtid="" class="approved_budget text-center"/>
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
                                                        <a class="text-info px-2 fs-7 bg-hover-primary nav-icon-hover position-relative z-index-5" id="btn_trxjournalitem_add" href="javascript:__mysys_budget_allotment_ent.my_add_budget_indirect_line();"><i class="ti ti-new-section"></i></a>
                                                    </th>
                                                    <th class="text-center align-middle">PS - Particulars</th>
                                                    <th class="text-center align-middle">UACS.</th>
                                                    <th class="text-center align-middle">Approved Budget</th>
                                                </thead>
                                                <tbody>
                                                    <tr style="display:none;">
                                                        <td class="text-center align-middle">
                                                            <a class="text-info px-2 fs-5 bg-hover-danger nav-icon-hover position-relative z-index-5" 
                                                            href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                                            <i class="ti ti-trash"></i>
                                                            </a>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <select name="selUacs" class="selUacs form" style="width:500px; height:30px;">
                                                                <option selected value ="">Choose...</option>
                                                                <?php foreach($psuacsdata as $data){
                                                                    $object_of_expenditures = $data['object_of_expenditures'];
                                                                    $code = $data['code'];
                                                                ?>
                                                                    <option value="<?=$object_of_expenditures?>" data-uacs="<?=$code;?>"><?=$object_of_expenditures?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="" size="25"  name="uacs" class="uacs text-center" disabled>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="approved_budget"  value="" size="25" name="approved_budget" data-dtid="" class="approved_budget text-center"/>
                                                        </td>
                                                    </tr>
                                                    <?php if(!empty($recid)):
                                                        $query = $this->db->query("
                                                        SELECT
                                                            `recid`,
                                                            `particulars`,
                                                            `code`,
                                                            `approved_budget`
                                                        FROM
                                                            `tbl_budget_indirect_ps_dt`
                                                        WHERE 
                                                            `project_id` = '$recid'"
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
                                                            href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                                            <i class="ti ti-trash"></i>
                                                            </a>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <select name="selUacs" class="selUacs form"  style="width:500px; height:30px;">
                                                                <option selected value ="<?=$particulars;?>"><?=$particulars;?></option>
                                                                <?php foreach($psuacsdata as $data){
                                                                    $object_of_expenditures = $data['object_of_expenditures'];
                                                                    $_code = $data['code'];
                                                                ?>
                                                                    <option value="<?=$object_of_expenditures?>"  data-uacs="<?=$_code;?>"><?=$object_of_expenditures?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="<?=$code;?>" size="25"  name="uacs" class="uacs text-center" disabled>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="approved_budget"  value="<?=$approved_budget;?>" size="25" data-dtid="<?=$dt_id;?>" name="approved_budget" class="approved_budget text-center"/>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; endif;?>
                                                    <?php if(!empty($realign_id)):
                                                        $query = $this->db->query("
                                                        SELECT
                                                            `recid`,
                                                            `particulars`,
                                                            `code`,
                                                            `approved_budget`
                                                        FROM
                                                            `tbl_budget_indirect_ps_dt`
                                                        WHERE 
                                                            `project_id` = '$realign_id'"
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
                                                            href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                                            <i class="ti ti-trash"></i>
                                                            </a>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <select name="selUacs" class="selUacs form"  style="width:500px; height:30px;">
                                                                <option selected value ="<?=$particulars;?>"><?=$particulars;?></option>
                                                                <?php foreach($psuacsdata as $data){
                                                                    $object_of_expenditures = $data['object_of_expenditures'];
                                                                    $_code = $data['code'];
                                                                ?>
                                                                    <option value="<?=$object_of_expenditures?>"  data-uacs="<?=$_code;?>"><?=$object_of_expenditures?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="<?=$code;?>" size="25"  name="uacs" class="uacs text-center" disabled>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="approved_budget"  value="<?=$approved_budget;?>" size="25" data-dtid="" name="approved_budget" class="approved_budget text-center"/>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; endif;?>
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
                                                        <a class="text-info px-2 fs-7 bg-hover-primary nav-icon-hover position-relative z-index-5" id="btn_budgetmooeitem_add" href="javascript:__mysys_budget_allotment_ent.my_add_budget_mooe_line();"><i class="ti ti-new-section"></i></a>
                                                    </th>
                                                    <th class="text-center align-middle">MOOE - Particulars</th>
                                                    <th class="text-center align-middle">UACS.</th>
                                                    <th class="text-center align-middle">Approved Budget</th>
                                                </thead>
                                                <tbody>
                                                    <tr style="display:none;">
                                                        <td class="text-center align-middle">
                                                            <a class="text-info px-2 fs-5 bg-hover-danger nav-icon-hover position-relative z-index-5" 
                                                            href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                                            <i class="ti ti-trash"></i>
                                                            </a>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <select name="selUacs" class="selUacs form" style="width:500px; height:30px;">
                                                                <option selected value ="">Choose...</option>
                                                                <?php foreach($mooeuacsdata as $data){
                                                                    $object_of_expenditures = $data['object_of_expenditures'];
                                                                    $code = $data['code'];
                                                                ?>
                                                                    <option value="<?=$object_of_expenditures?>" data-uacs="<?=$code;?>"><?=$object_of_expenditures?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="" size="25"  name="uacs" class="uacs text-center" disabled>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="approved_budget"  value="" size="25" name="approved_budget" data-dtid="" class="approved_budget text-center"/>
                                                        </td>
                                                    </tr>
                                                    <?php if(!empty($recid)):
                                                        $query = $this->db->query("
                                                        SELECT
                                                            `recid`,
                                                            `particulars`,
                                                            `code`,
                                                            `approved_budget`
                                                        FROM
                                                            `tbl_budget_direct_mooe_dt`
                                                        WHERE 
                                                            `project_id` = '$recid'"
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
                                                            href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                                            <i class="ti ti-trash"></i>
                                                            </a>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <select name="selUacs" class="selUacs form"  style="width:500px; height:30px;">
                                                                <option selected value ="<?=$particulars;?>"><?=$particulars;?></option>
                                                                <?php foreach($mooeuacsdata as $data){
                                                                    $object_of_expenditures = $data['object_of_expenditures'];
                                                                    $_code = $data['code'];
                                                                ?>
                                                                    <option value="<?=$object_of_expenditures?>"  data-uacs="<?=$_code;?>"><?=$object_of_expenditures?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="<?=$code;?>" size="25"  name="uacs" class="uacs text-center" disabled>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="approved_budget"  value="<?=$approved_budget;?>" size="25" data-dtid="<?=$dt_id;?>" name="approved_budget" class="approved_budget text-center"/>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; endif;?>
                                                    <?php if(!empty($realign_id)):
                                                        $query = $this->db->query("
                                                        SELECT
                                                            `recid`,
                                                            `particulars`,
                                                            `code`,
                                                            `approved_budget`
                                                        FROM
                                                            `tbl_budget_direct_mooe_dt`
                                                        WHERE 
                                                            `project_id` = '$realign_id'"
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
                                                            href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                                            <i class="ti ti-trash"></i>
                                                            </a>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <select name="selUacs" class="selUacs form"  style="width:500px; height:30px;">
                                                                <option selected value ="<?=$particulars;?>"><?=$particulars;?></option>
                                                                <?php foreach($mooeuacsdata as $data){
                                                                    $object_of_expenditures = $data['object_of_expenditures'];
                                                                    $_code = $data['code'];
                                                                ?>
                                                                    <option value="<?=$object_of_expenditures?>"  data-uacs="<?=$_code;?>"><?=$object_of_expenditures?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="<?=$code;?>" size="25"  name="uacs" class="uacs text-center" disabled>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="approved_budget"  value="<?=$approved_budget;?>" size="25" data-dtid="" name="approved_budget" class="approved_budget text-center"/>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; endif;?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-sm-12">
                                            <span class="ms-3 fw-bold">Indirect Cost:</span>
                                            <table id="budget_mooe_indirect_line_items" class="table-sm table-striped budgetmooedata-indirect-list">
                                                <thead>
                                                    <th class="text-center">
                                                        <a class="text-info px-2 fs-7 bg-hover-primary nav-icon-hover position-relative z-index-5" id="btn_budgetmooeitem_add" href="javascript:__mysys_budget_allotment_ent.my_add_budget_indirect_mooe_line();"><i class="ti ti-new-section"></i></a>
                                                    </th>
                                                    <th class="text-center align-middle">MOOE - Particulars</th>
                                                    <th class="text-center align-middle">UACS.</th>
                                                    <th class="text-center align-middle">Approved Budget</th>
                                                </thead>
                                                <tbody>
                                                    <tr style="display:none;">
                                                        <td class="text-center align-middle">
                                                            <a class="text-info px-2 fs-5 bg-hover-danger nav-icon-hover position-relative z-index-5" 
                                                            href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                                            <i class="ti ti-trash"></i>
                                                            </a>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <select name="selUacs" class="selUacs form" style="width:500px; height:30px;">
                                                                <option selected value ="">Choose...</option>
                                                                <?php foreach($mooeuacsdata as $data){
                                                                    $object_of_expenditures = $data['object_of_expenditures'];
                                                                    $code = $data['code'];
                                                                ?>
                                                                    <option value="<?=$object_of_expenditures?>" data-uacs="<?=$code;?>"><?=$object_of_expenditures?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="" size="25"  name="uacs" class="uacs text-center" disabled>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="approved_budget"  value="" size="25" name="approved_budget" data-dtid="" class="approved_budget text-center"/>
                                                        </td>
                                                    </tr>
                                                    <?php if(!empty($recid)):
                                                        $query = $this->db->query("
                                                        SELECT
                                                            `recid`,
                                                            `particulars`,
                                                            `code`,
                                                            `approved_budget`
                                                        FROM
                                                            `tbl_budget_indirect_mooe_dt`
                                                        WHERE 
                                                            `project_id` = '$recid'"
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
                                                            href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                                            <i class="ti ti-trash"></i>
                                                            </a>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <select name="selUacs" class="selUacs form"  style="width:500px; height:30px;">
                                                                <option selected value ="<?=$particulars;?>"><?=$particulars;?></option>
                                                                <?php foreach($mooeuacsdata as $data){
                                                                    $object_of_expenditures = $data['object_of_expenditures'];
                                                                    $_code = $data['code'];
                                                                ?>
                                                                    <option value="<?=$object_of_expenditures?>"  data-uacs="<?=$_code;?>"><?=$object_of_expenditures?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="<?=$code;?>" size="25"  name="uacs" class="uacs text-center" disabled>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="approved_budget"  value="<?=$approved_budget;?>" size="25" data-dtid="<?=$dt_id;?>" name="approved_budget" class="approved_budget text-center"/>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; endif;?>
                                                    <?php if(!empty($realign_id)):
                                                        $query = $this->db->query("
                                                        SELECT
                                                            `recid`,
                                                            `particulars`,
                                                            `code`,
                                                            `approved_budget`
                                                        FROM
                                                            `tbl_budget_indirect_mooe_dt`
                                                        WHERE 
                                                            `project_id` = '$realign_id'"
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
                                                            href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                                            <i class="ti ti-trash"></i>
                                                            </a>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <select name="selUacs" class="selUacs form"  style="width:500px; height:30px;">
                                                                <option selected value ="<?=$particulars;?>"><?=$particulars;?></option>
                                                                <?php foreach($mooeuacsdata as $data){
                                                                    $object_of_expenditures = $data['object_of_expenditures'];
                                                                    $_code = $data['code'];
                                                                ?>
                                                                    <option value="<?=$object_of_expenditures?>"  data-uacs="<?=$_code;?>"><?=$object_of_expenditures?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="<?=$code;?>" size="25"  name="uacs" class="uacs text-center" disabled>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="approved_budget"  value="<?=$approved_budget;?>" size="25" data-dtid="" name="approved_budget" class="approved_budget text-center"/>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; endif;?>
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
                                                        <a class="text-info px-2 fs-7 bg-hover-primary nav-icon-hover position-relative z-index-5" id="btn_budgetcoitem_add" href="javascript:__mysys_budget_allotment_ent.my_add_budget_co_line();"><i class="ti ti-new-section"></i></a>
                                                    </th>
                                                    <th class="text-center align-middle">CO - Particulars</th>
                                                    <th class="text-center align-middle">UACS.</th>
                                                    <th class="text-center align-middle">Approved Budget</th>
                                                </thead>
                                                <tbody>
                                                    <tr style="display:none;">
                                                        <td class="text-center align-middle">
                                                            <a class="text-info px-2 fs-5 bg-hover-danger nav-icon-hover position-relative z-index-5" 
                                                            href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                                            <i class="ti ti-trash"></i>
                                                            </a>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="particulars"  value="" style="width:500px; height:30px;"  name="particulars" class="particulars text-center">
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="" size="25"  name="uacs" class="uacs text-center">
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="approved_budget"  value="" size="25" name="approved_budget" data-dtid="" class="approved_budget text-center"/>
                                                        </td>
                                                    </tr>
                                                    <?php if(!empty($recid)):
                                                        $query = $this->db->query("
                                                        SELECT
                                                            `recid`,
                                                            `particulars`,
                                                            `code`,
                                                            `approved_budget`
                                                        FROM
                                                            `tbl_budget_direct_co_dt`
                                                        WHERE 
                                                            `project_id` = '$recid'"
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
                                                            href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                                            <i class="ti ti-trash"></i>
                                                            </a>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="particulars"  value="<?=$particulars;?>" style="width:500px; height:30px;"  name="particulars" class="particulars text-center">
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="<?=$code;?>" size="25"  name="uacs" class="uacs text-center">
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="approved_budget"  value="<?=$approved_budget;?>" size="25" data-dtid="<?=$dt_id;?>" name="approved_budget" class="approved_budget text-center"/>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; endif;?>
                                                    <?php if(!empty($realign_id)):
                                                        $query = $this->db->query("
                                                        SELECT
                                                            `recid`,
                                                            `particulars`,
                                                            `code`,
                                                            `approved_budget`
                                                        FROM
                                                            `tbl_budget_direct_co_dt`
                                                        WHERE 
                                                            `project_id` = '$realign_id'"
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
                                                            href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                                            <i class="ti ti-trash"></i>
                                                            </a>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="particulars"  value="<?=$particulars;?>" style="width:500px; height:30px;"  name="particulars" class="particulars text-center">
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="<?=$code;?>" size="25"  name="uacs" class="uacs text-center">
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="approved_budget"  value="<?=$approved_budget;?>" size="25" data-dtid="" name="approved_budget" class="approved_budget text-center"/>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; endif;?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-sm-12">
                                            <span class="ms-3 fw-bold">Indirect Cost:</span>
                                            <table id="budget_indirect_co_line_items" class="table-sm table-striped budgetcodata-indirect-list">
                                                <thead>
                                                    <th class="text-center">
                                                        <a class="text-info px-2 fs-7 bg-hover-primary nav-icon-hover position-relative z-index-5" id="btn_budgetcoitem_add" href="javascript:__mysys_budget_allotment_ent.my_add_budget_indirect_co_line();"><i class="ti ti-new-section"></i></a>
                                                    </th>
                                                    <th class="text-center align-middle">CO - Particulars</th>
                                                    <th class="text-center align-middle">UACS.</th>
                                                    <th class="text-center align-middle">Approved Budget</th>
                                                </thead>
                                                <tbody>
                                                    <tr style="display:none;">
                                                        <td class="text-center align-middle">
                                                            <a class="text-info px-2 fs-5 bg-hover-danger nav-icon-hover position-relative z-index-5" 
                                                            href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                                            <i class="ti ti-trash"></i>
                                                            </a>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="particulars"  value="" style="width:500px; height:30px;"   name="particulars" class="particulars text-center">
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="" size="25"  name="uacs" class="uacs text-center">
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="approved_budget"  value="" size="25" name="approved_budget" data-dtid="" class="approved_budget text-center"/>
                                                        </td>
                                                    </tr>
                                                    <?php if(!empty($recid)):
                                                        $query = $this->db->query("
                                                        SELECT
                                                            `recid`,
                                                            `particulars`,
                                                            `code`,
                                                            `approved_budget`
                                                        FROM
                                                            `tbl_budget_indirect_co_dt`
                                                        WHERE 
                                                            `project_id` = '$recid'"
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
                                                            href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                                            <i class="ti ti-trash"></i>
                                                            </a>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="particulars"  value="<?=$particulars;?>" style="width:500px; height:30px;"  name="particulars" class="particulars text-center">
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="<?=$code;?>" size="25"  name="uacs" class="uacs text-center">
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="approved_budget"  value="<?=$approved_budget;?>" size="25" data-dtid="<?=$dt_id;?>" name="approved_budget" class="approved_budget text-center"/>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; endif;?>
                                                    <?php if(!empty($realign_id)):
                                                        $query = $this->db->query("
                                                        SELECT
                                                            `recid`,
                                                            `particulars`,
                                                            `code`,
                                                            `approved_budget`
                                                        FROM
                                                            `tbl_budget_indirect_co_dt`
                                                        WHERE 
                                                            `project_id` = '$realign_id'"
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
                                                            href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                                            <i class="ti ti-trash"></i>
                                                            </a>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="particulars"  value="<?=$particulars;?>" style="width:500px; height:30px;"  name="particulars" class="particulars text-center">
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="<?=$code;?>" size="25"  name="uacs" class="uacs text-center">
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="approved_budget"  value="<?=$approved_budget;?>" size="25" data-dtid="" name="approved_budget" class="approved_budget text-center"/>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; endif;?>
                                                </tbody>
                                            </table>
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
                        <th>Transaction No.</th>
                        <th>Project Title</th>
                        <th>Responsibility Code</th>
                        <th>Encode Date</th>
                        <th>Budget</th>
                        <th>Status</th>
                        <th>Print LIB</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($budgetdtdata)):
                        
                        foreach ($budgetdtdata as $data):
                            $dt_recid = $data['recid'];
                            $hdtrxno = $data['trxno'];
                            $project_title = $data['project_title'];
                            $responsibility_code = $data['responsibility_code'];
                            $fund_cluster_code = $data['fund_cluster_code'];
                            $division_name = $data['division_name'];
                            $approved_budget = $data['approved_budget'];
                            $is_pending = $data['is_pending'];
                            $is_approved = $data['is_approved'];
                            $is_disapproved = $data['is_disapproved'];
                            $added_at = $data['added_at'];

                            if ($is_approved == '1' && $is_disapproved == '0' && $is_pending == '0') {
                                $status = "APPROVED";
                                $color = "success";
                            }elseif ($is_approved == '0' && $is_disapproved == '1' && $is_pending == '0') {
                                $status = "DISAPPROVED";
                                $color = "danger";
                            }else{
                                $status = "PENDING";
                                $color = "info";
                            }

                    ?>
                    <tr>
                        <td class="text-center align-middle">
                            <a class="text-info nav-icon-hover" href="mybudgetallotment?meaction=MAIN&recid=<?= $dt_recid ?>">
                                Review
                            </a>
                        </td>
                        <td class="text-center"><?=$hdtrxno;?></td>
                        <td class="text-center"><?=$project_title;?></td>
                        <td class="text-center"><?=$responsibility_code;?></td>
                        <td class="text-center"><?=$added_at;?></td>
                        <td class="text-center"><?=$approved_budget;?></td>
                        <td class="text-center text-<?=$color;?>"><?=$status;?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-secondary" onclick="window.open('<?= base_url('mybudgetallotment?meaction=PRINT-LIB&recid='.$dt_recid) ?>', '_blank')">
                                Print
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; endif;?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-info p-1">
            <div class="row">
                <div class="col-sm-6 d-flex align-items-center text-start">
                    <h6 class="mb-0 lh-base px-3 text-white fw-semibold d-flex align-items-center">
                        <i class="ti ti-files fs-5 me-1"></i>
                        <span class="pt-1">Project Attachments</span>
                    </h6>
                </div>
            </div>
		</div>						
        <div class="card-body p-0 px-4 py-2 my-2">
            <form id="uploadForm" action="<?=site_url();?>mybudgetallotment" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-6 mb-2">
                    <label for="formFileSm" class="form-label">Uploading:</label>
                    <div class="d-flex gap-2">
                        <input class="form-control form-control-sm" name="userfile" type="file" />
                        <input type="hidden" name="hd_trxno" value="<?=$trxno;?>">
                        <input type="hidden" name="meaction"  value="MAIN-UPLOAD">
                        
                        <?php if(!empty($recid)):?>
                            <button type="submit" class="btn btn-sm btn-primary">Upload</button>
                        <?php else:?>
                            <button type="submit" class="btn btn-sm btn-primary" disabled>Upload</button>
                        <?php endif;?>
                    </div>
                </div>
                
                <div class="col-sm-12">
                    <label for="formFileSm" class="form-label">List of uploaded files:</label>
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">File Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($trxno)):
                                $query = $this->db->query("
                                SELECT
                                    `recid`,
                                    `file_name`
                                FROM
                                    `tbl_budget_attachments`
                                WHERE 
                                    `trxno` = '$trxno'"
                                );
                                $result = $query->getResultArray();
                                foreach ($result as $data):
                                    $recid = $data['recid'];
                                    $file_name = $data['file_name'];
                            ?>
                            <tr>
                                <td class="text-center"><?=$counter++;?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('uploads/' . urlencode($file_name)) ?>" target="_blank">
                                        <?=$file_name;?>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; endif;?>
                        </tbody>
                    </table>
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
<script src="<?=base_url('assets/js/orsburs/orsburs.js');?>"></script>
<script src="<?=base_url('assets/js/mysysapps.js');?>"></script>

<!-- Bootstrap JS (and Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<?php
	echo $MDL_jsscript;
?>
<script>
 
    $(document).ready(function () {
        $('#datatablesSimple').DataTable({
            pageLength: 5,
            lengthChange: false,
            order: [[4, 'desc']],
            language: {
            search: "Search:"
            }
        });
    
    });

    $(document).on('change', '.selUacs', function() {
        var selectedCode = $(this).find('option:selected').data('uacs');
        $(this).closest('tr').find('.uacs').val(selectedCode);
    });

    $(document).on('change', '#selPayee', function() {
        var selected = $(this).find('option:selected');

        // Extract data from selected option
        var office = selected.data('office') || '';
        var address = selected.data('address') || '';
        
        // Set the values into inputs
        $('#payee_office').val(office);
        $('#payee_address').val(address);

    });

</script>
<?php
    echo view('templates/myfooter.php');
?>


