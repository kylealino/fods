<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$recid = $this->request->getPostGet('recid');

$full_name = "";
$division = "";
$username = "";
$hash_password = "";
$hash_value = "";


echo view('templates/myheader.php');
?>
<head>


</head>
<div class="container-fluid">
    <div class="row me-myua-outp-msg mx-0">
    </div>
    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />
    <div class="row mb-2 mt-0">
        <h4 class="fw-semibold mb-8">Account Settting</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a class="text-muted text-decoration-none" href="<?=site_url();?>"><i class="ti ti-home fs-5"></i></a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Maintenance</li>
            <li class="breadcrumb-item" aria-current="page"><span class="form-label fw-bold">Account Setting</span></li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-account" role="tabpanel" aria-labelledby="pills-account-tab" tabindex="0">
                    <div class="row">
                        <div class="col-lg-6 d-flex align-items-stretch">
                            <div class="card w-100 border position-relative overflow-hidden">
                                <div class="card-body p-4">
                                    <h4 class="card-title">Change Profile</h4>
                                    <p class="card-subtitle mb-4">Change your profile picture from here</p>
                                    <div class="text-center">
                                        <img src="<?=base_url('assets/images/logos/fnrilogo.png')?>" alt="flexy-img" class="img-fluid rounded-circle" width="120" height="120">
                                        <div class="d-flex align-items-center justify-content-center my-4 gap-6">
                                            <button class="btn btn-primary">Upload</button>
                                            <button class="btn bg-danger-subtle text-danger">Reset</button>
                                        </div>
                                        <p class="mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex align-items-stretch">
                            <div class="card w-100 border position-relative overflow-hidden">
                                <div class="card-body p-4">
                                    <h4 class="card-title">Change Password</h4>
                                    <p class="card-subtitle mb-4">To change your password please confirm here</p>
                                    <form>
                                        <div class="mb-3">
                                            <label for="exampleInputPassword1" class="form-label">Current Password</label>
                                            <input type="password" class="form-control" id="exampleInputPassword1" value="12345678910">
                                        </div>
                                        <div class="mb-3">
                                            <label for="exampleInputPassword2" class="form-label">New Password</label>
                                            <input type="password" class="form-control" id="exampleInputPassword2" value="12345678910">
                                        </div>
                                        <div>
                                            <label for="exampleInputPassword3" class="form-label">Confirm Password</label>
                                            <input type="password" class="form-control" id="exampleInputPassword3" value="12345678910">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                        <div class="card w-100 border position-relative overflow-hidden mb-0">
                            <div class="card-body p-4">
                                <h4 class="card-title">Personal Details</h4>
                                <p class="card-subtitle mb-4">To change your personal detail , edit and save from here</p>
                                <form>
                                    <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="exampleInputtext" class="form-label">Your Name</label>
                                            <input type="text" class="form-control" id="exampleInputtext" placeholder="Johnathan Doe">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Location</label>
                                            <select class="form-select" aria-label="Default select example">
                                                <option selected>United Kingdom</option>
                                                <option value="1">United States</option>
                                                <option value="2">United Kingdom</option>
                                                <option value="3">India</option>
                                                <option value="3">Russia</option>
                                            </select>
                                            </div>
                                            <div class="mb-3">
                                            <label for="exampleInputtext1" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="exampleInputtext1" placeholder="info@flexy.com">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="exampleInputtext2" class="form-label">Store Name</label>
                                            <input type="text" class="form-control" id="exampleInputtext2" placeholder="Maxima Studio">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Currency</label>
                                            <select class="form-select" aria-label="Default select example">
                                                <option selected>India (INR)</option>
                                                <option value="1">US Dollar ($)</option>
                                                <option value="2">United Kingdom (Pound)</option>
                                                <option value="3">India (INR)</option>
                                                <option value="3">Russia (Ruble)</option>
                                            </select>
                                            </div>
                                            <div class="mb-3">
                                            <label for="exampleInputtext3" class="form-label">Phone</label>
                                            <input type="text" class="form-control" id="exampleInputtext3" placeholder="+91 12345 65478">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label for="exampleInputtext4" class="form-label">Address</label>
                                            <input type="text" class="form-control" id="exampleInputtext4" placeholder="814 Howard Street, 120065, India">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex align-items-center justify-content-end mt-4 gap-6">
                                            <button class="btn btn-primary">Save</button>
                                            <button class="btn bg-danger-subtle text-danger">Cancel</button>
                                        </div>
                                    </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-notifications" role="tabpanel" aria-labelledby="pills-notifications-tab" tabindex="0">
                  <div class="row justify-content-center">
                    <div class="col-lg-9">
                      <div class="card border shadow-none">
                        <div class="card-body p-4">
                          <h4 class="card-title">Notification Preferences</h4>
                          <p class="card-subtitle mb-4">
                            Select the notificaitons ou would like to receive via email. Please note that you cannot opt
                            out of receving service
                            messages, such as payment, security or legal notifications.
                          </p>
                          <form class="mb-7">
                            <label for="exampleInputtext5" class="form-label">Email Address*</label>
                            <input type="text" class="form-control" id="exampleInputtext5" placeholder="" required>
                            <p class="mb-0">Required for notificaitons.</p>
                          </form>
                          <div>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                              <div class="d-flex align-items-center gap-3">
                                <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                  <i class="ti ti-article text-dark d-block fs-7" width="22" height="22"></i>
                                </div>
                                <div>
                                  <h5 class="fs-4 fw-semibold">Our newsletter</h5>
                                  <p class="mb-0">We'll always let you know about important changes</p>
                                </div>
                              </div>
                              <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked">
                              </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                              <div class="d-flex align-items-center gap-3">
                                <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                  <i class="ti ti-checkbox text-dark d-block fs-7" width="22" height="22"></i>
                                </div>
                                <div>
                                  <h5 class="fs-4 fw-semibold">Order Confirmation</h5>
                                  <p class="mb-0">You will be notified when customer order any product</p>
                                </div>
                              </div>
                              <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked1" checked>
                              </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                              <div class="d-flex align-items-center gap-3">
                                <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                  <i class="ti ti-clock-hour-4 text-dark d-block fs-7" width="22" height="22"></i>
                                </div>
                                <div>
                                  <h5 class="fs-4 fw-semibold">Order Status Changed</h5>
                                  <p class="mb-0">You will be notified when customer make changes to the order</p>
                                </div>
                              </div>
                              <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked2" checked>
                              </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                              <div class="d-flex align-items-center gap-3">
                                <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                  <i class="ti ti-truck-delivery text-dark d-block fs-7" width="22" height="22"></i>
                                </div>
                                <div>
                                  <h5 class="fs-4 fw-semibold">Order Delivered</h5>
                                  <p class="mb-0">You will be notified once the order is delivered</p>
                                </div>
                              </div>
                              <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked3">
                              </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                              <div class="d-flex align-items-center gap-3">
                                <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                  <i class="ti ti-mail text-dark d-block fs-7" width="22" height="22"></i>
                                </div>
                                <div>
                                  <h5 class="fs-4 fw-semibold">Email Notification</h5>
                                  <p class="mb-0">Turn on email notificaiton to get updates through email</p>
                                </div>
                              </div>
                              <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked4" checked>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-9">
                      <div class="card border shadow-none">
                        <div class="card-body p-4">
                          <h4 class="card-title">Date & Time</h4>
                          <p class="card-subtitle">Time zones and calendar display settings.</p>
                          <div class="d-flex align-items-center justify-content-between mt-7">
                            <div class="d-flex align-items-center gap-3">
                              <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                <i class="ti ti-clock-hour-4 text-dark d-block fs-7" width="22" height="22"></i>
                              </div>
                              <div>
                                <p class="mb-0">Time zone</p>
                                <h5 class="fs-4 fw-semibold">(UTC + 02:00) Athens, Bucharet</h5>
                              </div>
                            </div>
                            <a class="text-dark fs-6 d-flex align-items-center justify-content-center bg-transparent p-2 fs-4 rounded-circle" href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Download">
                              <i class="ti ti-download"></i>
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-9">
                      <div class="card border shadow-none">
                        <div class="card-body p-4">
                          <h4 class="card-title">Ignore Tracking</h4>
                          <div class="d-flex align-items-center justify-content-between mt-7">
                            <div class="d-flex align-items-center gap-3">
                              <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                <i class="ti ti-player-pause text-dark d-block fs-7" width="22" height="22"></i>
                              </div>
                              <div>
                                <h5 class="fs-4 fw-semibold">Ignore Browser Tracking</h5>
                                <p class="mb-0">Browser Cookie</p>
                              </div>
                            </div>
                            <div class="form-check form-switch mb-0">
                              <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked5">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="d-flex align-items-center justify-content-end gap-6">
                        <button class="btn btn-primary">Save</button>
                        <button class="btn bg-danger-subtle text-danger">Cancel</button>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    
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


