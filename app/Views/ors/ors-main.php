<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$this->myors = model('App\Models\MyOrsModel');
$action = $this->request->getPostGet('action');
$recid = $this->request->getPostGet('recid');

$program_title = '';
$particulars = '';
$funding_source = '';
$payee_name = '';
$payee_office = '';
$payee_address = '';
$certified_a = '';
$certified_b = '';
$position_a = '';
$position_b = '';
$serialno = '';
$is_pending = '';
$is_approved_certa = '';
$is_disapproved_certa = '';
$is_approved_certb = '';
$is_disapproved_certb = '';
$certa_remarks = '';
$certb_remarks = '';
$MDL_jsscript = "";
$status_color= "";
$status_msg = "";
$status_color_b= "";
$status_msg_b = "";

if(!empty($recid) || !is_null($recid)) { 
    $query = $this->db->query("
    SELECT
        `program_title`,
        `particulars`,
        `funding_source`,
        `payee_name`,
        `payee_office`,
        `payee_address`,
        `certified_a`,
        `certified_b`,
        `position_a`,
        `position_b`,
        `serialno`,
        `is_pending`,
        `is_approved_certa`,
        `is_disapproved_certa`,
        `is_approved_certb`,
        `is_disapproved_certb`,
        `certa_remarks`,
        `certb_remarks`,
        `certa_approver`,
        `certb_approver`
    FROM
        `tbl_ors_hd`
    WHERE 
        `recid` = '$recid'"
    );

    $data = $query->getRowArray();
    $program_title = $data['program_title'];
    $particulars = $data['particulars'];
    $funding_source = $data['funding_source'];
    $payee_name = $data['payee_name'];
    $payee_office = $data['payee_office'];
    $payee_address = $data['payee_address'];
    $certified_a = $data['certified_a'];
    $position_a = $data['position_a'];
    $certified_b = $data['certified_b'];
    $position_b = $data['position_b'];
    $serialno = $data['serialno'];
    $is_pending = $data['is_pending'];
    $is_approved_certa = $data['is_approved_certa'];
    $is_disapproved_certa = $data['is_disapproved_certa'];
    $is_approved_certb = $data['is_approved_certb'];
    $is_disapproved_certb = $data['is_disapproved_certb'];
    $certa_remarks = $data['certa_remarks'];
    $certb_remarks = $data['certb_remarks'];
    $certa_approver = $data['certa_approver'];
    $certb_approver = $data['certb_approver'];

    if ($action == 'certifya_appr_pending') {
        $MDL_jsscript = "
        <script>
            __mysys_ors_ent.__approve_certa();
            __mysys_ors_ent.__disapprove_certa();
        </script>
       ";	
    }elseif ($action == 'is_disapproved_certa') {
        $MDL_jsscript = "
        <script>
           __mysys_ors_ent.__disapprove_certa();
        </script>
       ";	
    }elseif ($action == 'is_approved_certa') {
        $MDL_jsscript = "
        <script>
           __mysys_ors_ent.__approve_certa();
        </script>
       ";	
    }elseif ($action == 'certifyb_appr_pending') {
        $MDL_jsscript = "
        <script>
           __mysys_ors_ent.__approve_certb();
           __mysys_ors_ent.__disapprove_certb();
        </script>
       ";
    }elseif ($action == 'is_disapproved_certb') {
        $MDL_jsscript = "
        <script>
           __mysys_ors_ent.__disapprove_certb();
        </script>
       ";	
    }elseif ($action == 'is_approved_certb') {
        $MDL_jsscript = "
        <script>
           __mysys_ors_ent.__approve_certb();
        </script>
       ";	
    }else{
        $MDL_jsscript = "

       ";	
    }

}

if ($action == 'certifya_appr_pending') {
    $status_color = 'text-warning';
    $status_msg = 'For Approval';
    $status_color_b = 'text-warning';
    $status_msg_b = 'For Approval';
}elseif ($action == 'certifyb_appr_pending'){
    if ($is_approved_certa == '1' && $is_disapproved_certa == '0'&& $is_approved_certb == '0' && $is_disapproved_certb == '0') {
        $status_color = 'text-success';
        $status_msg = 'Approved by ' . $certa_approver;
        $status_color_b = 'text-warning';
        $status_msg_b = 'For Approval';
    }elseif($is_approved_certa == '1' && $is_disapproved_certa == '0'&& $is_approved_certb == '0' && $is_disapproved_certb == '1'){
        $status_color = 'text-success';
        $status_msg = 'Approved by ' . $certa_approver;
        $status_color_b = 'text-danger';
        $status_msg_b = 'Disapproved';
    }else{
        $status_color = 'text-warning';
        $status_msg = 'For Approval';
        $status_color_b = 'text-warning';
        $status_msg_b = 'For Approval';
    }

}elseif ($action == 'transaction_viewing') {
    if ($is_approved_certa == '1' && $is_approved_certb == '0' && $is_disapproved_certb == '0') {
        $status_color = 'text-success';
        $status_msg = 'Approved by ' . $certa_approver;
        $status_color_b = 'text-warning';
        $status_msg_b = 'For Approval';
    }elseif ($is_approved_certa == '1' && $is_approved_certb == '1') {
        $status_color_b = 'text-success';
        $status_msg_b = 'Approved by ' . $certb_approver;
        $status_color = 'text-success';
        $status_msg = 'Approved by ' . $certa_approver;
    }elseif($is_approved_certa == '1' && $is_disapproved_certb == '1'){
        $status_color = 'text-success';
        $status_msg = 'Approved by ' . $certa_approver;
        $status_color_b = 'text-danger';
        $status_msg_b = 'Disapproved';
    }else{
        $status_color = 'text-warning';
        $status_msg = 'For Approval';
        $status_color_b = 'text-warning';
        $status_msg_b = 'For Approval';
    }
}else{
    $status_color_b = 'text-info';
    $status_msg_b = '';
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
        <div class="row myors-outp-msg mx-0">

        </div>
        <div class="card-header   bg-info p-1">
            <div class="row d-flex align-items-center">
                <div class="col-sm-6 d-flex align-items-center text-start">
                    <h6 class="mb-0 lh-base px-3 text-white fw-semibold d-flex align-items-center">
                        <i class="ti ti-pencil fs-5 me-1"></i>
                        <span class="pt-1">Entry</span>
                    </h6>
                </div>
                <div class="col-sm-6 text-end">
                    <?php if($action == 'certifya_appr_pending' || $action == 'certifyb_appr_pending'):?>
                        <button type="button" id="btn_approve" name="btn_approve" class="btn_approve btn btn-sm btn-success">
                            Approve
                        </button>
                        <button type="button" id="btn_disapprove" name="btn_disapprove" class="btn_disapprove btn btn-sm btn-danger">
                            Dispprove
                        </button>
                    <?php endif;?>
                </div>
            </div>
        </div>						
        <div class="card-body p-0 px-4 py-2 my-2">
            <form action="<?=site_url();?>myors?meaction=MAIN-SAVE" method="post" class="myors-validation">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <span>Program Title</span>
                            </div>
                            <div class="col-sm-10">
                                <input type="hidden" class="form-control form-control-sm" id="recid" name="recid" value="<?=$recid;?>"/>
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
                                        <textarea name="particulars" id="particulars" placeholder="" rows="4" class="form-control form-control-sm"><?=$particulars;?></textarea>
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
                                        <?php if(!empty($recid)):?>
                                            <option value="<?=$funding_source;?>"><?=$funding_source;?></option>
                                        <?php else:?>
                                            <option value="">Choose...</option>
                                        <?php endif;?>
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
                                <?php if(!empty($recid)):?>
                                    <select name="payee_name" id="payee_name" class="form-control select2 form-select-sm show-tick">
                                        <option selected value="<?=$payee_name;?>"><?=$payee_name;?></option>
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
                                <?php else:?>
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
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">Office:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="payee_office" name="payee_office" value="<?=$payee_office;?>" class="form-control form-control-sm bg-light"  />
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">Address:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="payee_address" name="payee_address" value="<?=$payee_address;?>" class="form-control form-control-sm"  />
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">Serial No.:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="serialno" name="serialno" value="<?=$serialno;?>" class="form-control form-control-sm" disabled/>
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
                                                                    $rc_code = $data['responsibility_code'];
                                                                    $mfopaps_code = $data['mfopaps_code'];
                                                                ?>
                                                                    <option value="<?=$project_title?>" data-rc="<?=$rc_code;?>" data-mfo="<?=$mfopaps_code;?>"><?=$project_title?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="responsibility_code"  value="" size="35"  name="responsibility_code" class="responsibility_code text-center" disabled>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="mfopaps_code"  value="" size="25" name="mfopaps_code" data-dtid="" class="mfopaps_code text-center" disabled/>
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
                                                    <?php if(!empty($recid)):
                                                        $query = $this->db->query("
                                                        SELECT
                                                            `recid`,
                                                            `project_title`,
                                                            `responsibility_code`,
                                                            `mfopaps_code`,
                                                            `sub_object_code`,
                                                            `uacs_code`,
                                                            `amount`
                                                        FROM
                                                            `tbl_ors_direct_ps_dt`
                                                        WHERE 
                                                            `project_id` = '$recid'"
                                                        );
                                                        $result = $query->getResultArray();
                                                        foreach ($result as $data):
                                                            $dt_id = $data['recid'];
                                                            $project_title = $data['project_title'];
                                                            $responsibility_code = $data['responsibility_code'];
                                                            $mfopaps_code = $data['mfopaps_code'];
                                                            $sub_object_code = $data['sub_object_code'];
                                                            $uacs_code = $data['uacs_code'];
                                                            $amount = $data['amount'];
                                                    ?>
                                                    <tr>
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
                                                                <option selected value ="<?=$project_title?>"><?=$project_title?></option>
                                                                <?php foreach($projectdata as $data){
                                                                    $project_title = $data['project_title'];
                                                                    $rc_code = $data['responsibility_code'];
                                                                    $mfopaps_code = $data['mfopaps_code'];
                                                                ?>
                                                                    <option value="<?=$project_title?>" data-rc="<?=$rc_code;?>" data-mfo="<?=$mfopaps_code;?>"><?=$project_title?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="responsibility_code"  value="<?=$responsibility_code;?>" size="35"  name="responsibility_code" class="responsibility_code text-center" disabled>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="mfopaps_code"  value="<?=$mfopaps_code;?>" size="25" name="mfopaps_code" data-dtid="" class="mfopaps_code text-center" disabled/>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <select name="selUacs" class="selUacs form" style="width:300px; height:30px;">
                                                                <option selected value ="<?=$sub_object_code;?>"><?=$sub_object_code;?></option>
                                                                <?php foreach($psuacsdata as $data){
                                                                    $sub_object_code = $data['sub_object_code'];
                                                                    $lu_uacs_code = $data['uacs_code'];
                                                                ?>
                                                                    <option value="<?=$sub_object_code?>" data-uacs="<?=$lu_uacs_code;?>"><?=$sub_object_code?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="<?=$uacs_code;?>" size="25" name="uacs" data-dtid="" class="uacs text-center" disabled/>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="amount"  value="<?=$amount;?>" size="25" step="any" name="amount" data-dtid="" class="amount text-center"/>
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
                                                        <a class="text-info px-2 fs-7 bg-hover-primary nav-icon-hover position-relative z-index-5" id="btn_trxjournalitem_add" href="javascript:__mysys_ors_ent.my_add_budget_indirect_line();"><i class="ti ti-new-section"></i></a>
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
                                                                onclick="__mysys_ors_ent.my_add_budget_indirect_line_above(this);">
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
                                                                    $mfopaps_code = $data['mfopaps_code'];
                                                                ?>
                                                                    <option value="<?=$project_title?>" data-rc="<?=$responsibility_code;?>" data-mfo="<?=$mfopaps_code;?>"><?=$project_title?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="responsibility_code"  value="" size="35"  name="responsibility_code" class="responsibility_code text-center" disabled>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="mfopaps_code"  value="" size="25" name="mfopaps_code" data-dtid="" class="mfopaps_code text-center" disabled/>
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
                                                    <?php if(!empty($recid)):
                                                        $query = $this->db->query("
                                                        SELECT
                                                            `recid`,
                                                            `project_title`,
                                                            `responsibility_code`,
                                                            `mfopaps_code`,
                                                            `sub_object_code`,
                                                            `uacs_code`,
                                                            `amount`
                                                        FROM
                                                            `tbl_ors_indirect_ps_dt`
                                                        WHERE 
                                                            `project_id` = '$recid'"
                                                        );
                                                        $result = $query->getResultArray();
                                                        foreach ($result as $data):
                                                            $dt_id = $data['recid'];
                                                            $project_title = $data['project_title'];
                                                            $responsibility_code = $data['responsibility_code'];
                                                            $mfopaps_code = $data['mfopaps_code'];
                                                            $sub_object_code = $data['sub_object_code'];
                                                            $uacs_code = $data['uacs_code'];
                                                            $amount = $data['amount'];
                                                    ?>
                                                    <tr>
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
                                                                <option selected value ="<?=$project_title?>"><?=$project_title?></option>
                                                                <?php foreach($projectdata as $data){
                                                                    $project_title = $data['project_title'];
                                                                    $rc_code = $data['responsibility_code'];
                                                                    $mfopaps_code = $data['mfopaps_code'];
                                                                ?>
                                                                    <option value="<?=$project_title?>" data-rc="<?=$rc_code;?>" data-mfo="<?=$mfopaps_code;?>"><?=$project_title?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="responsibility_code"  value="<?=$responsibility_code;?>" size="35"  name="responsibility_code" class="responsibility_code text-center" disabled>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="mfopaps_code"  value="<?=$mfopaps_code;?>" size="25" name="mfopaps_code" data-dtid="" class="mfopaps_code text-center" disabled/>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <select name="selUacs" class="selUacs form" style="width:300px; height:30px;">
                                                                <option selected value ="<?=$sub_object_code;?>"><?=$sub_object_code;?></option>
                                                                <?php foreach($psuacsdata as $data){
                                                                    $sub_object_code = $data['sub_object_code'];
                                                                    $lu_uacs_code = $data['uacs_code'];
                                                                ?>
                                                                    <option value="<?=$sub_object_code?>" data-uacs="<?=$lu_uacs_code;?>"><?=$sub_object_code?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="<?=$uacs_code;?>" size="25" name="uacs" data-dtid="" class="uacs text-center" disabled/>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="amount"  value="<?=$amount;?>" size="25" step="any" name="amount" data-dtid="" class="amount text-center"/>
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
                                                        <a class="text-info px-2 fs-7 bg-hover-primary nav-icon-hover position-relative z-index-5" id="btn_trxjournalitem_add" href="javascript:__mysys_ors_ent.my_add_budget_mooe_line();"><i class="ti ti-new-section"></i></a>
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
                                                                onclick="__mysys_ors_ent.my_add_budget_mooe_line_above(this);">
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
                                                                    $mfopaps_code = $data['mfopaps_code'];
                                                                ?>
                                                                    <option value="<?=$project_title?>" data-rc="<?=$responsibility_code;?>" data-mfo="<?=$mfopaps_code;?>"><?=$project_title?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="responsibility_code"  value="" size="35"  name="responsibility_code" class="responsibility_code text-center" disabled>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="mfopaps_code"  value="" size="25" name="mfopaps_code" data-dtid="" class="mfopaps_code text-center" disabled/>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <select name="selUacs" class="selUacs form" style="width:300px; height:30px;">
                                                                <option selected value ="">Choose...</option>
                                                                <?php foreach($mooeuacsdata as $data){
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
                                                    <?php if(!empty($recid)):
                                                        $query = $this->db->query("
                                                        SELECT
                                                            `recid`,
                                                            `project_title`,
                                                            `responsibility_code`,
                                                            `mfopaps_code`,
                                                            `sub_object_code`,
                                                            `uacs_code`,
                                                            `amount`
                                                        FROM
                                                            `tbl_ors_direct_mooe_dt`
                                                        WHERE 
                                                            `project_id` = '$recid'"
                                                        );
                                                        $result = $query->getResultArray();
                                                        foreach ($result as $data):
                                                            $dt_id = $data['recid'];
                                                            $project_title = $data['project_title'];
                                                            $responsibility_code = $data['responsibility_code'];
                                                            $mfopaps_code = $data['mfopaps_code'];
                                                            $sub_object_code = $data['sub_object_code'];
                                                            $uacs_code = $data['uacs_code'];
                                                            $amount = $data['amount'];
                                                    ?>
                                                    <tr>
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
                                                                onclick="__mysys_ors_ent.my_add_budget_mooe_line_above(this);">
                                                                    <i class="ti ti-plus"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <select name="selProject" class="selProject form" style="width:600px; height:30px;">
                                                                <option selected value ="<?=$project_title?>"><?=$project_title?></option>
                                                                <?php foreach($projectdata as $data){
                                                                    $project_title = $data['project_title'];
                                                                    $rc_code = $data['responsibility_code'];
                                                                    $mfopaps_code = $data['mfopaps_code'];
                                                                ?>
                                                                    <option value="<?=$project_title?>" data-rc="<?=$rc_code;?>" data-mfo="<?=$mfopaps_code;?>"><?=$project_title?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="responsibility_code"  value="<?=$responsibility_code;?>" size="35"  name="responsibility_code" class="responsibility_code text-center" disabled>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="mfopaps_code"  value="<?=$mfopaps_code;?>" size="25" name="mfopaps_code" data-dtid="" class="mfopaps_code text-center" disabled/>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <select name="selUacs" class="selUacs form" style="width:300px; height:30px;">
                                                                <option selected value ="<?=$sub_object_code;?>"><?=$sub_object_code;?></option>
                                                                <?php foreach($mooeuacsdata as $data){
                                                                    $sub_object_code = $data['sub_object_code'];
                                                                    $lu_uacs_code = $data['uacs_code'];
                                                                ?>
                                                                    <option value="<?=$sub_object_code?>" data-uacs="<?=$lu_uacs_code;?>"><?=$sub_object_code?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="<?=$uacs_code;?>" size="25" name="uacs" data-dtid="" class="uacs text-center" disabled/>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="amount"  value="<?=$amount;?>" size="25" step="any" name="amount" data-dtid="" class="amount text-center"/>
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
                                                        <a class="text-info px-2 fs-7 bg-hover-primary nav-icon-hover position-relative z-index-5" id="btn_trxjournalitem_add" href="javascript:__mysys_ors_ent.my_add_budget_indirect_mooe_line();"><i class="ti ti-new-section"></i></a>
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
                                                                onclick="__mysys_ors_ent.my_add_budget_indirect_mooe_line_above(this);">
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
                                                                    $mfopaps_code = $data['mfopaps_code'];
                                                                ?>
                                                                    <option value="<?=$project_title?>" data-rc="<?=$responsibility_code;?>" data-mfo="<?=$mfopaps_code;?>"><?=$project_title?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="responsibility_code"  value="" size="35"  name="responsibility_code" class="responsibility_code text-center" disabled>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="mfopaps_code"  value="" size="25" name="mfopaps_code" data-dtid="" class="mfopaps_code text-center" disabled/>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <select name="selUacs" class="selUacs form" style="width:300px; height:30px;">
                                                                <option selected value ="">Choose...</option>
                                                                <?php foreach($mooeuacsdata as $data){
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
                                                    <?php if(!empty($recid)):
                                                        $query = $this->db->query("
                                                        SELECT
                                                            `recid`,
                                                            `project_title`,
                                                            `responsibility_code`,
                                                            `mfopaps_code`,
                                                            `sub_object_code`,
                                                            `uacs_code`,
                                                            `amount`
                                                        FROM
                                                            `tbl_ors_indirect_mooe_dt`
                                                        WHERE 
                                                            `project_id` = '$recid'"
                                                        );
                                                        $result = $query->getResultArray();
                                                        foreach ($result as $data):
                                                            $dt_id = $data['recid'];
                                                            $project_title = $data['project_title'];
                                                            $responsibility_code = $data['responsibility_code'];
                                                            $mfopaps_code = $data['mfopaps_code'];
                                                            $sub_object_code = $data['sub_object_code'];
                                                            $uacs_code = $data['uacs_code'];
                                                            $amount = $data['amount'];
                                                    ?>
                                                    <tr>
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
                                                                onclick="__mysys_ors_ent.my_add_budget_indirect_mooe_line_above(this);">
                                                                    <i class="ti ti-plus"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <select name="selProject" class="selProject form" style="width:600px; height:30px;">
                                                                <option selected value ="<?=$project_title?>"><?=$project_title?></option>
                                                                <?php foreach($projectdata as $data){
                                                                    $project_title = $data['project_title'];
                                                                    $rc_code = $data['responsibility_code'];
                                                                    $mfopaps_code = $data['mfopaps_code'];
                                                                ?>
                                                                    <option value="<?=$project_title?>" data-rc="<?=$rc_code;?>" data-mfo="<?=$mfopaps_code;?>"><?=$project_title?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="responsibility_code"  value="<?=$responsibility_code;?>" size="35"  name="responsibility_code" class="responsibility_code text-center" disabled>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="mfopaps_code"  value="<?=$mfopaps_code;?>" size="25" name="mfopaps_code" data-dtid="" class="mfopaps_code text-center" disabled/>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <select name="selUacs" class="selUacs form" style="width:300px; height:30px;">
                                                                <option selected value ="<?=$sub_object_code;?>"><?=$sub_object_code;?></option>
                                                                <?php foreach($mooeuacsdata as $data){
                                                                    $sub_object_code = $data['sub_object_code'];
                                                                    $lu_uacs_code = $data['uacs_code'];
                                                                ?>
                                                                    <option value="<?=$sub_object_code?>" data-uacs="<?=$lu_uacs_code;?>"><?=$sub_object_code?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="<?=$uacs_code;?>" size="25" name="uacs" data-dtid="" class="uacs text-center" disabled/>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="amount"  value="<?=$amount;?>" size="25" step="any" name="amount" data-dtid="" class="amount text-center"/>
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
                                                        <a class="text-info px-2 fs-7 bg-hover-primary nav-icon-hover position-relative z-index-5" id="btn_budgetcoitem_add" disabled href="javascript:__mysys_ors_ent.my_add_budget_co_line();"><i class="ti ti-new-section"></i></a>
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
                                                                onclick="__mysys_ors_ent.my_add_budget_co_line_above(this);">
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
                                                                    $mfopaps_code = $data['mfopaps_code'];
                                                                ?>
                                                                    <option value="<?=$project_title?>" data-rc="<?=$responsibility_code;?>" data-mfo="<?=$mfopaps_code;?>"><?=$project_title?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="responsibility_code"  value="" size="35"  name="responsibility_code" class="responsibility_code text-center" disabled>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="mfopaps_code"  value="" size="25" name="mfopaps_code" data-dtid="" class="mfopaps_code text-center" disabled/>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <select name="selUacs" class="selUacs form" style="width:300px; height:30px;">
                                                                <option selected value ="">Choose...</option>
                                                                <?php foreach($couacsdata as $data){
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
                                                    <?php if(!empty($recid)):
                                                        $query = $this->db->query("
                                                        SELECT
                                                            `recid`,
                                                            `project_title`,
                                                            `responsibility_code`,
                                                            `mfopaps_code`,
                                                            `sub_object_code`,
                                                            `uacs_code`,
                                                            `amount`
                                                        FROM
                                                            `tbl_ors_direct_co_dt`
                                                        WHERE 
                                                            `project_id` = '$recid'"
                                                        );
                                                        $result = $query->getResultArray();
                                                        foreach ($result as $data):
                                                            $dt_id = $data['recid'];
                                                            $project_title = $data['project_title'];
                                                            $responsibility_code = $data['responsibility_code'];
                                                            $mfopaps_code = $data['mfopaps_code'];
                                                            $sub_object_code = $data['sub_object_code'];
                                                            $uacs_code = $data['uacs_code'];
                                                            $amount = $data['amount'];
                                                    ?>
                                                    <tr>
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
                                                                onclick="__mysys_ors_ent.my_add_budget_co_line_above(this);">
                                                                    <i class="ti ti-plus"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <select name="selProject" class="selProject form" style="width:600px; height:30px;">
                                                                <option selected value ="<?=$project_title?>"><?=$project_title?></option>
                                                                <?php foreach($projectdata as $data){
                                                                    $project_title = $data['project_title'];
                                                                    $rc_code = $data['responsibility_code'];
                                                                    $mfopaps_code = $data['mfopaps_code'];
                                                                ?>
                                                                    <option value="<?=$project_title?>" data-rc="<?=$rc_code;?>" data-mfo="<?=$mfopaps_code;?>"><?=$project_title?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="responsibility_code"  value="<?=$responsibility_code;?>" size="35"  name="responsibility_code" class="responsibility_code text-center" disabled>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="mfopaps_code"  value="<?=$mfopaps_code;?>" size="25" name="mfopaps_code" data-dtid="" class="mfopaps_code text-center" disabled/>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <select name="selUacs" class="selUacs form" style="width:300px; height:30px;">
                                                                <option selected value ="<?=$sub_object_code;?>"><?=$sub_object_code;?></option>
                                                                <?php foreach($couacsdata as $data){
                                                                    $sub_object_code = $data['sub_object_code'];
                                                                    $lu_uacs_code = $data['uacs_code'];
                                                                ?>
                                                                    <option value="<?=$sub_object_code?>" data-uacs="<?=$lu_uacs_code;?>"><?=$sub_object_code?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="<?=$uacs_code;?>" size="25" name="uacs" data-dtid="" class="uacs text-center" disabled/>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="amount"  value="<?=$amount;?>" size="25" step="any" name="amount" data-dtid="" class="amount text-center"/>
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
                                                        <a class="text-info px-2 fs-7 bg-hover-primary nav-icon-hover position-relative z-index-5" id="btn_budgetcoitem_add" disabled href="javascript:__mysys_ors_ent.my_add_budget_indirect_co_line();"><i class="ti ti-new-section"></i></a>
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
                                                                onclick="__mysys_ors_ent.my_add_budget_indirect_co_line_above(this);">
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
                                                                    $mfopaps_code = $data['mfopaps_code'];
                                                                ?>
                                                                    <option value="<?=$project_title?>" data-rc="<?=$responsibility_code;?>" data-mfo="<?=$mfopaps_code;?>"><?=$project_title?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="responsibility_code"  value="" size="35"  name="responsibility_code" class="responsibility_code text-center" disabled>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="mfopaps_code"  value="" size="25" name="mfopaps_code" data-dtid="" class="mfopaps_code text-center" disabled/>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <select name="selUacs" class="selUacs form" style="width:300px; height:30px;">
                                                                <option selected value ="">Choose...</option>
                                                                <?php foreach($couacsdata as $data){
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
                                                    <?php if(!empty($recid)):
                                                        $query = $this->db->query("
                                                        SELECT
                                                            `recid`,
                                                            `project_title`,
                                                            `responsibility_code`,
                                                            `mfopaps_code`,
                                                            `sub_object_code`,
                                                            `uacs_code`,
                                                            `amount`
                                                        FROM
                                                            `tbl_ors_indirect_co_dt`
                                                        WHERE 
                                                            `project_id` = '$recid'"
                                                        );
                                                        $result = $query->getResultArray();
                                                        foreach ($result as $data):
                                                            $dt_id = $data['recid'];
                                                            $project_title = $data['project_title'];
                                                            $responsibility_code = $data['responsibility_code'];
                                                            $mfopaps_code = $data['mfopaps_code'];
                                                            $sub_object_code = $data['sub_object_code'];
                                                            $uacs_code = $data['uacs_code'];
                                                            $amount = $data['amount'];
                                                    ?>
                                                    <tr>
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
                                                                onclick="__mysys_ors_ent.my_add_budget_indirect_co_line_above(this);">
                                                                    <i class="ti ti-plus"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <select name="selProject" class="selProject form" style="width:600px; height:30px;">
                                                                <option selected value ="<?=$project_title?>"><?=$project_title?></option>
                                                                <?php foreach($projectdata as $data){
                                                                    $project_title = $data['project_title'];
                                                                    $rc_code = $data['responsibility_code'];
                                                                    $mfopaps_code = $data['mfopaps_code'];
                                                                ?>
                                                                    <option value="<?=$project_title?>" data-rc="<?=$rc_code;?>" data-mfo="<?=$mfopaps_code;?>"><?=$project_title?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="responsibility_code"  value="<?=$responsibility_code;?>" size="35"  name="responsibility_code" class="responsibility_code text-center" disabled>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="mfopaps_code"  value="<?=$mfopaps_code;?>" size="25" name="mfopaps_code" data-dtid="" class="mfopaps_code text-center" disabled/>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <select name="selUacs" class="selUacs form" style="width:300px; height:30px;">
                                                                <option selected value ="<?=$sub_object_code;?>"><?=$sub_object_code;?></option>
                                                                <?php foreach($couacsdata as $data){
                                                                    $sub_object_code = $data['sub_object_code'];
                                                                    $lu_uacs_code = $data['uacs_code'];
                                                                ?>
                                                                    <option value="<?=$sub_object_code?>" data-uacs="<?=$lu_uacs_code;?>"><?=$sub_object_code?></option>
                                                                <?php }?>
                                                            </select>
                                                        </td>
                                                        <td class="align-middle" nowrap>
                                                            <input type="text" id="uacs"  value="<?=$uacs_code;?>" size="25" name="uacs" data-dtid="" class="uacs text-center" disabled/>
                                                        </td>
                                                        <td class="text-center align-middle" nowrap>
                                                            <input type="number" id="amount"  value="<?=$amount;?>" size="25" step="any" name="amount" data-dtid="" class="amount text-center"/>
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

                <hr class="fw-bolder">

                <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row mb-2">
                                    <div class="col-sm-4">
                                        <span class="fw-bold">Certified A:</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <?php if(!empty($recid)):?>
                                            <select name="certified_a" id="certified_a" class="form-control select2 form-select-sm show-tick">
                                                <option selected value="<?=$certified_a;?>"><?=$certified_a;?></option>
                                                <?php foreach($certifydata as $data): ?>
                                                    <option 
                                                        value="<?= $data['full_name'] ?>"
                                                        data-position="<?= $data['position'] ?>"
                                                    >
                                                        <?= $data['full_name'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php else:?>
                                            <select name="certified_a" id="certified_a" class="form-control select2 form-select-sm show-tick">
                                                <option selected value="">Choose...</option>
                                                <?php foreach($certifydata as $data): ?>
                                                    <option 
                                                        value="<?= $data['full_name'] ?>"
                                                        data-position="<?= $data['position'] ?>"
                                                    >
                                                        <?= $data['full_name'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php endif;?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="row mb-2">
                                    <div class="col-sm-4">
                                        <span class="fw-bold">Position:</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" id="position_a" name="position_a" value="<?=$position_a;?>" class="form-control form-control-sm" disabled/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="row mb-2">
                                    <?php if(!empty($action)):?>
                                        <div class="col-sm-4">
                                            <span class="fw-bold">Status:</span>
                                        </div>
                                        <div class="col-sm-8 <?=$status_color;?> fs-3 fw-bold">
                                            <span><i class="ti ti-flag"></i> <?=$status_msg;?></span>
                                        </div>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span class="fw-bold">Certified B:</span>
                            </div>
                            <div class="col-sm-8">
                                <?php if(!empty($recid)):?>
                                    <select name="certified_b" id="certified_b" class="form-control select2 form-select-sm show-tick">
                                        <option selected value="<?=$certified_b;?>"><?=$certified_b;?></option>
                                        <?php foreach($certifydata as $data): ?>
                                            <option 
                                                value="<?= $data['full_name'] ?>"
                                                data-position="<?= $data['position'] ?>"
                                            >
                                                <?= $data['full_name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else:?>
                                    <select name="certified_b" id="certified_b" class="form-control select2 form-select-sm show-tick">
                                        <option selected value="">Choose...</option>
                                        <?php foreach($certifydata as $data): ?>
                                            <option 
                                                value="<?= $data['full_name'] ?>"
                                                data-position="<?= $data['position'] ?>"
                                            >
                                                <?= $data['full_name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <div class="row mb-2">
                                    <div class="col-sm-4">
                                        <span class="fw-bold">Position:</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" id="position_b" name="position_b" value="<?=$position_b;?>" class="form-control form-control-sm" disabled/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="row mb-2">
                                <?php if(!empty($action)):?>
                                    <div class="col-sm-4">
                                        <span class="fw-bold">Status:</span>
                                    </div>
                                    <div class="col-sm-8 <?=$status_color_b;?> fs-3 fw-bold">
                                        <span><i class="ti ti-flag"></i> <?=$status_msg_b;?></span>
                                    </div>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row mb-2">  
                    <div class="col-sm-12 text-end">
                        <button type="submit" id="submitBtn" class="btn bg-<?= empty($recid) ? 'success' : 'info' ?>-subtle text-<?= empty($recid) ? 'success' : 'info' ?> btn-sm"><i class="ti ti-brand-doctrine mt-1 fs-4 me-1"></i>
                            <?= empty($recid) ? 'Save' : 'Update' ?>
                        </button>
                    </div>
                </div>
            </form>


        </div>
    </div>

    <div class="row mb-2">
        <div class="col-sm-12">
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
                                <th>Program Title</th>
                                <th>Particulars</th>
                                <th>Payee</th>
                                <th>Total Amount</th>
                                <th>Print</th>
                            </tr>
                        </thead>
                        <tbody class="align-middle">
                            <?php if(!empty($orshddata)):
                                foreach ($orshddata as $data):
                                    $dt_recid = $data['recid'];
                                    $program_title = $data['program_title'];
                                    $particulars = $data['particulars'];
                                    $payee_name = $data['payee_name'];
                                    $total_amount = $data['amount'];
                            ?>
                            <tr>
                                <td class="text-center align-middle">
                                    <a class="text-info nav-icon-hover fs-7" href="myors?meaction=MAIN&recid=<?= $dt_recid ?>" title="Edit Transaction">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                </td>
                                <td class="text-center"><?=$program_title;?></td>
                                <td class="text-center"><?=$particulars;?></td>
                                <td class="text-center"><?=$payee_name;?></td>
                                <td class="text-center"><?=$total_amount;?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm fs-7 text-info" onclick="__mysys_ors_ent.__showPdfInModal('<?= base_url('myors?meaction=PRINT-ORS&recid='.$dt_recid) ?>')" title="Print ORS">
                                        <i class="ti ti-printer"></i>
                                    </button>
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

<!-- APPROVAL -->
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
                            <?php if($action == 'certifya_appr_approved' && !empty($recid)):?>
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
                        <?php if($action == 'certifya_appr_approved' && !empty($recid)):?>
                            <textarea name="approved_remarks" id="approved_remarks" placeholder="" rows="3" class="form-control form-control-sm" disabled><?=$certa_remarks;?></textarea>
                        <?php else:?>
                            <textarea name="approved_remarks" id="approved_remarks" placeholder="" rows="3" class="form-control form-control-sm"></textarea>
                        <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="modal-footer">
            <?php if($action == 'certifya_appr_approved' && !empty($recid)):?>
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
                            <?php if($action == 'certifya_appr_disapproved' && !empty($recid)):?>
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
                            <?php if($action == 'certifya_appr_disapproved' && !empty($recid)):?>
                                <textarea name="disapproved_remarks" id="disapproved_remarks" placeholder="" rows="3" class="form-control form-control-sm" disabled><?=$certb_remarks;?></textarea>
                            <?php else:?>
                                <textarea name="disapproved_remarks" id="disapproved_remarks" placeholder="" rows="3" class="form-control form-control-sm"></textarea>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="modal-footer">
            <?php if($action == 'certifya_appr_disapproved' && !empty($recid)):?>
                <button type="button" class="btn bg-secondary-subtle btn-sm" data-bs-dismiss="modal">Back</button>
            <?php else:?>
                <button type="button" class="btn btn-danger btn-sm px-3" id="confirmDisapproveBtn">Disapprove</button>
                <button type="button" class="btn bg-secondary-subtle btn-sm" data-bs-dismiss="modal">Cancel</button>
            <?php endif;?>
        </div>
        </div>
    </div>
</div>

<!-- PDF Modal -->
<div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Printing Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="pdfFrame" src="" style="width: 100%; height: 80vh;" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script src="<?=base_url('assets/js/ors/ors.js?v=2');?>"></script>
<script src="<?=base_url('assets/js/mysysapps.js');?>"></script>

<?php
	echo $MDL_jsscript;
?>
<script>
    __mysys_ors_ent.__ors_saving();
</script>

<?php
    echo view('templates/myfooter.php');
?>


