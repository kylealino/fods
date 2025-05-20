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
$tagging = "";
$MDL_jsscript = "";
$counter = 1;

if(!empty($recid) || !is_null($recid)) { 

    $query = $this->db->query("
    SELECT
        `trxno`,
        `project_title`,
        `responsibility_code`,
        `fund_cluster_code`,
        `division_name`,
        `added_at`,
        `added_by`,
        `is_approved`,
        `is_disapproved`,
        `is_pending`,
        `approver`,
        `remarks`,
        `program_title`,
        `total_duration`,
        `duration_from`,
        `duration_to`,
        `program_leader`,
        `monitoring_agency`,
        `collaborating_agencies`,
        `implementing_agency`,
        `tagging`
    FROM
        `tbl_budget_hd`
    WHERE 
        `recid` = '$recid'"
    );

    $data = $query->getRowArray();
    $trxno = $data['trxno'];
    $project_title = $data['project_title'];
    $responsibility_code = $data['responsibility_code'];
    $fund_cluster_code = $data['fund_cluster_code'];
    $division_name = $data['division_name'];
    $is_pending = $data['is_pending'];
    $is_approved = $data['is_approved'];
    $is_disapproved = $data['is_disapproved'];
    $approver = $data['approver'];
    $remarks = $data['remarks'];
    $program_title = $data['program_title'];
    $total_duration = $data['total_duration'];
    $duration_from = $data['duration_from'];
    $duration_to = $data['duration_to'];
    $program_leader = $data['program_leader'];
    $monitoring_agency = $data['monitoring_agency'];
    $collaborating_agencies = $data['collaborating_agencies'];
    $implementing_agency = $data['implementing_agency'];
    $tagging = $data['tagging'];


    if ($action == 'appr_pending') {
        $MDL_jsscript = "
        <script>
           __mysys_budget_allotment_ent.__approve_budget();
           __mysys_budget_allotment_ent.__disapprove_budget();
        </script>
       ";	
    }elseif ($action == 'appr_disapproved') {
        $MDL_jsscript = "
        <script>
           __mysys_budget_allotment_ent.__disapprove_budget();
        </script>
       ";	
    }elseif ($action == 'appr_approved') {
        $MDL_jsscript = "
        <script>
           __mysys_budget_allotment_ent.__approve_budget();
        </script>
       ";	
    }else{
        $MDL_jsscript = "

       ";	
    }
}

if(!empty($realign_id) || !is_null($realign_id)) { 

    $query = $this->db->query("
    SELECT
        `trxno`,
        `project_title`,
        `responsibility_code`,
        `fund_cluster_code`,
        `division_name`,
        `added_at`,
        `added_by`,
        `is_approved`,
        `is_disapproved`,
        `is_pending`,
        `approver`,
        `remarks`,
        `program_title`,
        `total_duration`,
        `duration_from`,
        `duration_to`,
        `program_leader`,
        `monitoring_agency`,
        `collaborating_agencies`,
        `implementing_agency`
    FROM
        `tbl_budget_hd`
    WHERE 
        `recid` = '$realign_id'"
    );

    $data = $query->getRowArray();
    $project_title = $data['project_title'];
    $responsibility_code = $data['responsibility_code'];
    $fund_cluster_code = $data['fund_cluster_code'];
    $division_name = $data['division_name'];
    $is_pending = $data['is_pending'];
    $is_approved = $data['is_approved'];
    $is_disapproved = $data['is_disapproved'];
    $approver = $data['approver'];
    $remarks = $data['remarks'];
    $program_title = $data['program_title'];
    $total_duration = $data['total_duration'];
    $duration_from = $data['duration_from'];
    $duration_to = $data['duration_to'];
    $program_leader = $data['program_leader'];
    $monitoring_agency = $data['monitoring_agency'];
    $collaborating_agencies = $data['collaborating_agencies'];
    $implementing_agency = $data['implementing_agency'];

    if ($action == 'appr_pending') {
        $MDL_jsscript = "
        <script>
           __mysys_budget_allotment_ent.__approve_budget();
           __mysys_budget_allotment_ent.__disapprove_budget();
        </script>
       ";	
    }elseif ($action == 'appr_disapproved') {
        $MDL_jsscript = "
        <script>
           __mysys_budget_allotment_ent.__disapprove_budget();
        </script>
       ";	
    }elseif ($action == 'appr_approved') {
        $MDL_jsscript = "
        <script>
           __mysys_budget_allotment_ent.__approve_budget();
        </script>
       ";	
    }else{
        $MDL_jsscript = "

       ";	
    }
}
echo view('templates/myheader.php');
?>

<div class="container-fluid">
    <div class="row me-mybudgetallotment-appr-outp-msg mx-0">
    </div>

    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />
    <div class="row mb-2 mt-0">
        <h4 class="fw-semibold mb-8">Budget Allotment</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a class="text-muted text-decoration-none" href="<?=site_url();?>"><i class="ti ti-home fs-5"></i></a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Budget</li>
            <li class="breadcrumb-item" aria-current="page"><span class="form-label fw-bold">Budget Allotment Module</span></li>
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
                    <?php if ($action == 'appr_pending'):?>
                        <button type="button" id="btn_approve" name="btn_approve" class="btn_approve btn btn-sm btn-success">
                            Approve
                        </button>
                        <button type="button" id="btn_disapprove" name="btn_disapprove" class="btn_disapprove btn btn-sm btn-danger">
                            Dispprove
                        </button>
                    <?php elseif($action == 'appr_approved'):?>
                        <button type="button" id="btn_approve" name="btn_approve" class="btn_approve btn btn-sm btn-success mx-3">
                            Approved
                        </button>
                    <?php elseif($action == 'appr_disapproved'):?>
                        <button type="button" id="btn_disapprove" name="btn_disapprove" class="btn_disapprove btn btn-sm btn-danger mx-3">
                            Dispproved
                        </button>
                    <?php endif;?>
                    <?php if(!empty($recid)):?>
                        <a class="text-white me-4 h6" href="<?= site_url('mybudgetallotment?meaction=MAIN&realign_id=' .$recid) ?>"> <i class="ti ti-brand-doctrine mt-1 fs-6 me-1"></i> Realign</a>
                    <?php endif;?>
                </div>
            </div>
		</div>						
        <div class="card-body p-0 px-4 py-2 my-2">
            <form action="<?=site_url();?>mybudgetallotment?meaction=MAIN-SAVE" method="post" class="mybudgetallotment-validation">
                <div class="row">
                    <div class="col-sm-12 mb-2">
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <span class="fw-bold">Program Title:</span>
                            </div>
                            <div class="col-sm-10">
                                <textarea name="program_title" id="program_title" placeholder="" rows="4" class="form-control form-control-sm"><?=$program_title;?></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <span class="fw-bold">Project Title:</span>
                            </div>
                            <div class="col-sm-10">
                                <?php if(!empty($recid)):?>
                                    <select name="selProjectTitle" id="selProjectTitle" class="form-select select2 form-select-sm show-tick">
                                        <option selected value="<?=$project_title;?>"><?=$project_title;?></option>
                                        <?php foreach($projectdata as $data): ?>
                                            <option 
                                                value="<?= $data['project_title'] ?>"
                                                data-fund="<?= $data['fund_cluster_code'] ?>"
                                                data-division="<?= $data['division_name'] ?>"
                                                data-responsibility="<?= $data['responsibility_code'] ?>"
                                            >
                                                <?= $data['project_title'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php elseif(!empty($realign_id)):?>
                                    <select name="selProjectTitle" id="selProjectTitle" class="form-select select2 form-select-sm show-tick">
                                        <option selected value="<?=$project_title;?>"><?=$project_title;?></option>
                                        <?php foreach($projectdata as $data): ?>
                                            <option 
                                                value="<?= $data['project_title'] ?>"
                                                data-fund="<?= $data['fund_cluster_code'] ?>"
                                                data-division="<?= $data['division_name'] ?>"
                                                data-responsibility="<?= $data['responsibility_code'] ?>"
                                            >
                                                <?= $data['project_title'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else:?>
                                    <select name="selProjectTitle" id="selProjectTitle" class="form-select select2 form-select-sm show-tick">
                                        <option selected value="">Choose...</option>
                                        <?php foreach($projectdata as $data): ?>
                                            <option 
                                                value="<?= $data['project_title'] ?>"
                                                data-fund="<?= $data['fund_cluster_code'] ?>"
                                                data-division="<?= $data['division_name'] ?>"
                                                data-responsibility="<?= $data['responsibility_code'] ?>"
                                            >
                                                <?= $data['project_title'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">Fund Cluster:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="fund_cluster_code" name="fund_cluster_code" value="<?=$fund_cluster_code;?>" class="form-control form-control-sm" readonly />
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">Division:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="division_name" name="division_name" value="<?=$division_name;?>" class="form-control form-control-sm" readonly />
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">Total Duration:</span>
                            </div>
                            <div class="col-sm-8">
                                <?php if(!empty($recid)):?>
                                    <select id="total_duration" name="total_duration" class="form-select form-select-sm">
                                        <option selected value="<?=$total_duration;?>"><?=$total_duration;?></option>
                                        <option value="One (1) Year">One (1) Year</option>
                                        <option value="Two (2) Years">Two (2) Years</option>
                                        <option value="Three (3) Years">Three (3) Years</option>
                                    </select>
                                <?php elseif(!empty($realign_id)):?>
                                    <select id="total_duration" name="total_duration" class="form-select form-select-sm">
                                        <option selected value="<?=$total_duration;?>"><?=$total_duration;?></option>
                                        <option value="One (1) Year">One (1) Year</option>
                                        <option value="Two (2) Years">Two (2) Years</option>
                                        <option value="Three (3) Years">Three (3) Years</option>
                                    </select>
                                <?php else:?>
                                    <select id="total_duration" name="total_duration" class="form-select form-select-sm">
                                        <option selected value="">Choose...</option>
                                        <option value="One (1) Year">One (1) Year</option>
                                        <option value="Two (2) Years">Two (2) Years</option>
                                        <option value="Three (3) Years">Three (3) Years</option>
                                    </select>
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">From:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="date" id="duration_from" name="duration_from" value="<?=$duration_from;?>" class="form-control form-control-sm" />
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">To:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="date" id="duration_to" name="duration_to" value="<?=$duration_to;?>" class="form-control form-control-sm" />
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">Project Leader:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="project_leader" name="project_leader" value="<?=$this->cuser;?>" class="form-control form-control-sm" disabled />
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">Transaction No.:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="trxno" name="trxno" placeholder="-system-generated-" value="<?=$trxno;?>" class="form-control form-control-sm" disabled/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">Responsibility Code:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="hidden" class="form-control form-control-sm" id="recid" name="recid" value="<?=$recid;?>"/>
                                <input type="hidden" class="form-control form-control-sm" id="realign_id" name="realign_id" value="<?=$realign_id;?>"/>
                                <input type="text" id="responsibility_code" name="responsibility_code" value="<?=$responsibility_code;?>" class="form-control form-control-sm" readonly />
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">Program Leader:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="program_leader" name="program_leader" value="<?=$program_leader;?>" class="form-control form-control-sm"/>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">Implementing Agency:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="implementing_agency" name="implementing_agency" value="<?=$implementing_agency;?>" class="form-control form-control-sm"/>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">Monitoring Agency:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="monitoring_agency" name="monitoring_agency" value="<?=$monitoring_agency;?>" class="form-control form-control-sm"/>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">Collaborating Agencies:</span>
                            </div>
                            <div class="col-sm-8">
                                <textarea name="collaborating_agencies" id="collaborating_agencies" placeholder="" rows="3" class="form-control form-control-sm"><?=$collaborating_agencies;?></textarea>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">Tagging:</span>
                            </div>
                            <div class="col-sm-8">
                                <select name="" id="tagging" class="form-select form-select-sm">
                                    <?php if(!empty($recid)):?>
                                        <option value="<?=$tagging;?>"><?=$tagging;?></option>
                                    <?php endif;?>
                                    <option value="Save to Draft" class="text-success">Save to Draft</option>
                                    <option value="For Approval" class="text-info">For Approval</option>
                                    <option value="Posted" class="text-primary">Posted</option>
                                </select>
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
                                II. Maintenance and Other Operating Expenses
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
                                                            `tbl_budget_direct_ps_dt`
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
                                                            `tbl_budget_direct_ps_dt`
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

                            <!-- CO TAB CONTENT -->
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
                        <td class="text-center"><?= '₱'. number_format($approved_budget,2);?></td>
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
<script src="<?=base_url('assets/js/budget/mybudgetallotment.js');?>"></script>
<script src="<?=base_url('assets/js/mysysapps.js');?>"></script>

<!-- Bootstrap JS (and Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<?php
	echo $MDL_jsscript;
?>
<script>
    __mysys_budget_allotment_ent.__budget_saving();
 
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

    $(document).on('change', '#selProjectTitle', function() {
        var selected = $(this).find('option:selected');

        // Extract data from selected option
        var fund = selected.data('fund') || '';
        var division = selected.data('division') || '';
        var responsibility = selected.data('responsibility') || '';

        // Set the values into inputs
        $('#fund_cluster_code').val(fund);
        $('#division_name').val(division);
        $('#responsibility_code').val(responsibility);
    });

</script>
<?php
    echo view('templates/myfooter.php');
?>


