
<?php 
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');

  $query = $this->db->query("
  SELECT 
      `full_name`, 
      `division`,
      `section`, 
      `position`,
      `username`, 
      `hash_password`,
      `hash_value`
  FROM 
      `myua_user` 
  WHERE 
      `username` = '$this->cuser'"
  );

  $data = $query->getRowArray();
  $full_name = $data['full_name'];
  $position = $data['position'];
  $section = $data['section'];
  $division = $data['division'];

?>
<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <!-- Required meta tags -->
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Favicon icon-->
  <link rel="shortcut icon" type="image/png" href="<?=base_url('assets/images/logos/fnrilogo.png')?>" />

  <!-- Core Css -->
  <link rel="stylesheet" href="<?=base_url('assets/css/styles.css')?>" />

  <title>FODS</title>
  <style>
    .left-sidebar.with-vertical {
      height: 100vh;
      overflow-y: auto;
    }

    .left-sidebar.with-vertical::-webkit-scrollbar {
      width: 1px;  /* hides scrollbar */
      background: lightblue;
    }


  </style>
</head>
<body>
  <!-- Preloader -->
  <div class="preloader">
    <img src="<?=base_url('assets/images/logos/preloader.svg')?>" alt="loader" class="lds-ripple img-fluid" />
  </div>
  <div id="main-wrapper">
    <!-- Sidebar Start -->
    <aside class="left-sidebar with-vertical">
      <div><!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="<?=site_url();?>mydashboard" class="text-nowrap logo-img">
            <img src="<?=base_url('assets/images/logos/fnrilogo.png')?>" style="width: 35px; height: auto;"/>
            FODS
          </a> 
          <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
            <i class="ti ti-x"></i>
          </a>
        </div>


        <nav class="sidebar-nav scroll-sidebar" data-simplebar style="height: 100vh !important;">
          <ul id="sidebarnav">
            <!-- ---------------------------------- -->
            <!-- Home -->
            <!-- ---------------------------------- -->
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Home</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>mydashboard" aria-expanded="false">
                <span>
                  <i class="ti ti-aperture"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Maintenance</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>mypayee?meaction=MAIN" aria-expanded="false">
                <span>
                  <i class="ti ti-user-check"></i>
                </span>
                <span class="hide-menu">Payee</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>myproducts?meaction=MAIN" aria-expanded="false">
                <span>
                  <i class="ti ti-list-details"></i>
                </span>
                <span class="hide-menu">Supplies</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>myproject?meaction=MAIN" aria-expanded="false">
                <span>
                  <i class="ti ti-align-box-left-top"></i>
                </span>
                <span class="hide-menu">Projects</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>myuacs?meaction=MAIN" aria-expanded="false">
                <span>
                  <i class="ti ti-align-left"></i>
                </span>
                <span class="hide-menu">UACS</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>myua?meaction=MAIN" aria-expanded="false">
                <span>
                  <i class="ti ti-user"></i>
                </span>
                <span class="hide-menu">User Management</span>
              </a>
            </li>
            <!-- <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>myaccount?meaction=MAIN" aria-expanded="false">
                <span>
                  <i class="ti ti-user"></i>
                </span>
                <span class="hide-menu">Account Settings</span>
              </a>
            </li> -->
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">BUDGET</span>
            </li>

            </li>      
            <li class="sidebar-item">
              <a class="sidebar-link sidebar-link" href="<?=site_url();?>mybudgetallotment?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-cash"></i>
                </span>
                <span class="hide-menu">Budget Allotment</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link sidebar-link" href="<?=site_url();?>mybudgetapproval?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-checklist"></i>
                </span>
                <span class="hide-menu">Budget Approval</span>
              </a>
            </li>
            <!-- ---------------------------------- -->
            <!-- OTHER -->
            <!-- ---------------------------------- -->
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">ORS</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link sidebar-link" href="<?=site_url();?>myors?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-clipboard-text"></i>
                </span>
                <span class="hide-menu">ORS Entry</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link sidebar-link" href="<?=site_url();?>myorsapproval?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-clipboard-check"></i>
                </span>
                <span class="hide-menu">ORS Approval</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">BURS</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link sidebar-link" href="<?=site_url();?>myburs?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-clipboard-text"></i>
                </span>
                <span class="hide-menu">BURS Entry</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link sidebar-link" onclick="event.preventDefault();"  aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-clipboard-check"></i>
                </span>
                <span class="hide-menu">BURS Approval</span>
              </a>
            </li>
            <!-- <li class="sidebar-item">
              <a class="sidebar-link sidebar-link" href="<?=site_url();?>myburs?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-pencil"></i>
                </span>
                <span class="hide-menu">BURS</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link sidebar-link" href="<?=site_url();?>mybursapproval?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-pencil"></i>
                </span>
                <span class="hide-menu">BURS Approval</span>
              </a>
            </li> -->
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Reports</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link sidebar-link" href="<?=site_url();?>mysaobrpt?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-file"></i>
                </span>
                <span class="hide-menu">SAOB</span>
              </a>
            </li> 
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Procurement</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link sidebar-link" href="<?=site_url();?>myppmp?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-list-numbers"></i>
                </span>
                <span class="hide-menu">PPMP</span>
              </a>
            </li> 
            <li class="sidebar-item">
              <a class="sidebar-link sidebar-link" href="<?=site_url();?>myprocurement?meaction=PR-MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-playlist-add"></i>
                </span>
                <span class="hide-menu">PR</span>
              </a>
            </li> 
            <li class="sidebar-item">
              <a class="sidebar-link sidebar-link" href="<?=site_url();?>myabstract?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-mist"></i>
                </span>
                <span class="hide-menu">ABSTRACT</span>
              </a>
            </li> 
          </ul>
        </nav>

        <!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
      </div>
    </aside>
    <!--  Sidebar End -->
    <div class="page-wrapper">
      <!--  Header Start -->
      <header class="topbar">
        <div class="with-vertical"><!-- ---------------------------------- -->
          <!-- Start Vertical Layout Header -->
          <!-- ---------------------------------- -->
          <nav class="navbar navbar-expand-lg p-0">
            <ul class="navbar-nav">
              <li class="nav-item nav-icon-hover-bg rounded-circle ms-n2">
                <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                  <i class="ti ti-menu-2"></i>
                </a>
              </li>
            </ul>

            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">
              <!-- ------------------------------- -->
              <!-- start language Dropdown -->
              <!-- ------------------------------- -->
              <li class="nav-item nav-icon-hover-bg rounded-circle">
                <a class="nav-link moon dark-layout" href="javascript:void(0)">
                  <i class="ti ti-moon moon"></i>
                </a>
                <a class="nav-link sun light-layout" href="javascript:void(0)">
                  <i class="ti ti-sun sun"></i>
                </a>
              </li>

              <!-- ------------------------------- -->
              <!-- start profile Dropdown -->
              <!-- ------------------------------- -->
              <li class="nav-item dropdown">
                <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" aria-expanded="false">
                  <div class="d-flex align-items-center">
                    <div class="user-profile-img">
                      <img src="<?=base_url('assets/images/profile/user-1.jpg')?>" class="rounded-circle" width="35" height="35" alt="flexy-img" />
                    </div>
                  </div>
                </a>
                <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop1">
                  <div class="profile-dropdown position-relative" data-simplebar>
                    <div class="py-3 px-7 pb-0">
                      <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                    </div>
                    <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                      <img src="<?=base_url('assets/images/profile/user-1.jpg')?>" class="rounded-circle" width="80" height="80" alt="flexy-img" />
                      <div class="ms-3">
                        <h5 class="mb-1 fs-4"><?=$this->cuser;?></h5>
                        <span class="mb-1 d-block"><?=$full_name;?></span>
                        <span class="mb-1 d-block fs-2"><?=$position;?></span>
                        <span class="mb-1 d-block fs-2"><?=$division . ' - ' . $section;?></span>
                      </div>
                    </div>
                    <div class="message-body">
                      <a href="" class="py-8 px-7 mt-8 d-flex align-items-center">
                        <span class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                          <img src="<?=base_url('assets/images/svgs/icon-account.svg')?>" alt="flexy-img" width="24" height="24" />
                        </span>
                        <div class="w-100 ps-3">
                          <h6 class="mb-0 fs-4 lh-base">My Profile</h6>
                          <span class="fs-3 d-block text-body-secondary">Account Settings</span>
                        </div>
                      </a>
                      <a href="" class="py-8 px-7 d-flex align-items-center">
                        <span class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                          <img src="<?=base_url('assets/images/svgs/icon-inbox.svg')?>" alt="flexy-img" width="24" height="24" />
                        </span>
                        <div class="w-100 ps-3">
                          <h6 class="mb-0 fs-4 lh-base">My Inbox</h6>
                          <span class="fs-3 d-block text-body-secondary">Messages & Emails</span>
                        </div>
                      </a>
                      <a href="" class="py-8 px-7 d-flex align-items-center">
                        <span class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                          <img src="<?=base_url('assets/images/svgs/icon-tasks.svg')?>" alt="flexy-img" width="24" height="24" />
                        </span>
                        <div class="w-100 ps-3">
                          <h6 class="mb-0 fs-4 lh-base">My Task</h6>
                          <span class="fs-3 d-block text-body-secondary">To-do and Daily Tasks</span>
                        </div>
                      </a>
                    </div>
                    <div class="d-grid py-4 px-7 pt-8">
                      <form action="<?= site_url('mylogout'); ?>" method="post" novalidate>
                          <!-- Add a CSRF token for security -->
                          <?= csrf_field(); ?>
                          
                          <button type="submit" class="btn btn-outline-primary">Log Out</button>
                      </form>
                    </div>
                  </div>
                </div>
              </li>
              <!-- ------------------------------- -->
              <!-- end profile Dropdown -->
              <!-- ------------------------------- -->
            </ul>
          </nav>

        </div>
        <div class="app-header with-horizontal">
          <nav class="navbar navbar-expand-xl container-fluid p-0">
            <ul class="navbar-nav align-items-center">
              <li class="nav-item nav-icon-hover-bg rounded-circle d-flex d-xl-none ms-n2">
                <a class="nav-link sidebartoggler" id="sidebarCollapse" href="javascript:void(0)">
                  <i class="ti ti-menu-2"></i>
                </a>
              </li>
              <li class="nav-item d-none d-xl-block">
                <a href="../main/index.html" class="text-nowrap nav-link">
                  <img src="../assets/images/logos/dark-logo.svg" class="dark-logo" alt="flexy-img" />
                  <img src="../assets/images/logos/light-logo.svg" class="light-logo" alt="flexy-img" />
                </a>
              </li>
              <li class="nav-item nav-icon-hover-bg rounded-circle d-none d-xl-flex">
                <a class="nav-link" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">
                  <i class="ti ti-search"></i>
                </a>
              </li>
            </ul>
            <ul class="navbar-nav quick-links d-none d-xl-flex align-items-center">
              <!-- ------------------------------- -->
              <!-- start apps Dropdown -->
              <!-- ------------------------------- -->
              <li class="nav-item nav-icon-hover-bg rounded w-auto dropdown d-none d-lg-flex">
                <div class="hover-dd">
                  <a class="nav-link" href="javascript:void(0)">
                    Apps<span class="mt-1">
                      <i class="ti ti-chevron-down fs-3"></i>
                    </span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-nav dropdown-menu-animate-up py-0">
                    <div class="row">
                      <div class="col-8">
                        <div class="ps-7 pt-7">
                          <div class="border-bottom">
                            <div class="row">
                              <div class="col-6">
                                <div class="position-relative">
                                  <a href="../main/app-chat.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="../assets/images/svgs/icon-dd-chat.svg" alt="flexy-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div>
                                      <h6 class="mb-1 fw-semibold fs-3">
                                        Chat Application
                                      </h6>
                                      <span class="fs-2 d-block text-body-secondary">New messages arrived</span>
                                    </div>
                                  </a>
                                  <a href="../main/app-invoice.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="../assets/images/svgs/icon-dd-invoice.svg" alt="flexy-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div>
                                      <h6 class="mb-1 fw-semibold fs-3">Invoice App</h6>
                                      <span class="fs-2 d-block text-body-secondary">Get latest invoice</span>
                                    </div>
                                  </a>
                                  <a href="../main/app-contact2.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="../assets/images/svgs/icon-dd-mobile.svg" alt="flexy-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div>
                                      <h6 class="mb-1 fw-semibold fs-3">
                                        Contact Application
                                      </h6>
                                      <span class="fs-2 d-block text-body-secondary">2 Unsaved Contacts</span>
                                    </div>
                                  </a>
                                  <a href="../main/app-email.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="../assets/images/svgs/icon-dd-message-box.svg" alt="flexy-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div>
                                      <h6 class="mb-1 fw-semibold fs-3">Email App</h6>
                                      <span class="fs-2 d-block text-body-secondary">Get new emails</span>
                                    </div>
                                  </a>
                                </div>
                              </div>
                              <div class="col-6">
                                <div class="position-relative">
                                  <a href="../main/page-user-profile.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="../assets/images/svgs/icon-dd-cart.svg" alt="flexy-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div>
                                      <h6 class="mb-1 fw-semibold fs-3">
                                        User Profile
                                      </h6>
                                      <span class="fs-2 d-block text-body-secondary">learn more information</span>
                                    </div>
                                  </a>
                                  <a href="../main/app-calendar.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="../assets/images/svgs/icon-dd-date.svg" alt="flexy-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div>
                                      <h6 class="mb-1 fw-semibold fs-3">
                                        Calendar App
                                      </h6>
                                      <span class="fs-2 d-block text-body-secondary">Get dates</span>
                                    </div>
                                  </a>
                                  <a href="../main/app-contact.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="../assets/images/svgs/icon-dd-lifebuoy.svg" alt="flexy-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div>
                                      <h6 class="mb-1 fw-semibold fs-3">
                                        Contact List Table
                                      </h6>
                                      <span class="fs-2 d-block text-body-secondary">Add new contact</span>
                                    </div>
                                  </a>
                                  <a href="../main/app-notes.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="../assets/images/svgs/icon-dd-application.svg" alt="flexy-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div>
                                      <h6 class="mb-1 fw-semibold fs-3">
                                        Notes Application
                                      </h6>
                                      <span class="fs-2 d-block text-body-secondary">To-do and Daily tasks</span>
                                    </div>
                                  </a>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row align-items-center py-3">
                            <div class="col-8">
                              <a class="fw-semibold d-flex align-items-center lh-1" href="javascript:void(0)">
                                <i class="ti ti-help fs-6 me-2"></i>Frequently Asked Questions
                              </a>
                            </div>
                            <div class="col-4">
                              <div class="d-flex justify-content-end pe-4">
                                <button class="btn btn-primary">Check</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-4 ms-n4">
                        <div class="position-relative p-7 border-start h-100">
                          <h5 class="fs-5 mb-9 fw-semibold">Quick Links</h5>
                          <ul class="">
                            <li class="mb-3">
                              <a class="fw-semibold bg-hover-primary" href="../main/page-pricing.html">Pricing Page</a>
                            </li>
                            <li class="mb-3">
                              <a class="fw-semibold bg-hover-primary" href="../main/authentication-login.html">Authentication
                                Design</a>
                            </li>
                            <li class="mb-3">
                              <a class="fw-semibold bg-hover-primary" href="../main/authentication-register.html">Register Now</a>
                            </li>
                            <li class="mb-3">
                              <a class="fw-semibold bg-hover-primary" href="../main/authentication-error.html">404 Error Page</a>
                            </li>
                            <li class="mb-3">
                              <a class="fw-semibold bg-hover-primary" href="../main/app-notes.html">Notes App</a>
                            </li>
                            <li class="mb-3">
                              <a class="fw-semibold bg-hover-primary" href="../main/page-user-profile.html">User Application</a>
                            </li>
                            <li class="mb-3">
                              <a class="fw-semibold bg-hover-primary" href="../main/page-account-settings.html">Account Settings</a>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <!-- ------------------------------- -->
              <!-- end apps Dropdown -->
              <!-- ------------------------------- -->
              <li class="nav-item dropdown-hover d-none d-lg-block">
                <a class="nav-link" href="../main/app-chat.html">Chat</a>
              </li>
              <li class="nav-item dropdown-hover d-none d-lg-block">
                <a class="nav-link" href="../main/app-calendar.html">Calendar</a>
              </li>
              <li class="nav-item dropdown-hover d-none d-lg-block">
                <a class="nav-link" href="../main/app-email.html">Email</a>
              </li>
            </ul>
            <div class="d-block d-xl-none">
              <a href="../main/index.html" class="text-nowrap nav-link">
                <img src="../assets/images/logos/dark-logo.svg" width="180" alt="flexy-img" />
              </a>
            </div>
            <a class="navbar-toggler nav-icon-hover-bg rounded-circle p-0 mx-0 border-0" href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="p-2">
                <i class="ti ti-dots fs-7"></i>
              </span>
            </a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
              <div class="d-flex align-items-center justify-content-between px-0 px-xl-8">
                <a href="javascript:void(0)" class="nav-link round-40 p-1 ps-0 d-flex d-xl-none align-items-center justify-content-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar" aria-controls="offcanvasWithBothOptions">
                  <i class="ti ti-align-justified fs-7"></i>
                </a>
                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">
                  <!-- ------------------------------- -->
                  <!-- start language Dropdown -->
                  <!-- ------------------------------- -->
                  <li class="nav-item nav-icon-hover-bg rounded-circle">
                    <a class="nav-link moon dark-layout" href="javascript:void(0)">
                      <i class="ti ti-moon moon"></i>
                    </a>
                    <a class="nav-link sun light-layout" href="javascript:void(0)">
                      <i class="ti ti-sun sun"></i>
                    </a>
                  </li>
                  <li class="nav-item nav-icon-hover-bg rounded-circle dropdown">
                    <a class="nav-link" href="javascript:void(0)" id="drop2" aria-expanded="false">
                      <img src="../assets/images/svgs/icon-flag-en.svg" alt="flexy-img" width="20px" height="20px" class="rounded-circle object-fit-cover round-20" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                      <div class="message-body">
                        <a href="javascript:void(0)" class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item">
                          <div class="position-relative">
                            <img src="../assets/images/svgs/icon-flag-en.svg" alt="flexy-img" width="20px" height="20px" class="rounded-circle object-fit-cover round-20" />
                          </div>
                          <p class="mb-0 fs-3">English (UK)</p>
                        </a>
                        <a href="javascript:void(0)" class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item">
                          <div class="position-relative">
                            <img src="../assets/images/svgs/icon-flag-cn.svg" alt="flexy-img" width="20px" height="20px" class="rounded-circle object-fit-cover round-20" />
                          </div>
                          <p class="mb-0 fs-3">中国人 (Chinese)</p>
                        </a>
                        <a href="javascript:void(0)" class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item">
                          <div class="position-relative">
                            <img src="../assets/images/svgs/icon-flag-fr.svg" alt="flexy-img" width="20px" height="20px" class="rounded-circle object-fit-cover round-20" />
                          </div>
                          <p class="mb-0 fs-3">français (French)</p>
                        </a>
                        <a href="javascript:void(0)" class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item">
                          <div class="position-relative">
                            <img src="../assets/images/svgs/icon-flag-sa.svg" alt="flexy-img" width="20px" height="20px" class="rounded-circle object-fit-cover round-20" />
                          </div>
                          <p class="mb-0 fs-3">عربي (Arabic)</p>
                        </a>
                      </div>
                    </div>
                  </li>
                  <!-- ------------------------------- -->
                  <!-- end language Dropdown -->
                  <!-- ------------------------------- -->

                  <!-- ------------------------------- -->
                  <!-- start shopping cart Dropdown -->
                  <!-- ------------------------------- -->
                  <li class="nav-item nav-icon-hover-bg rounded-circle">
                    <a class="nav-link position-relative" href="javascript:void(0)" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                      <i class="ti ti-basket"></i>
                      <span class="popup-badge rounded-pill bg-danger text-white fs-2">2</span>
                    </a>
                  </li>
                  <!-- ------------------------------- -->
                  <!-- end shopping cart Dropdown -->
                  <!-- ------------------------------- -->

                  <!-- ------------------------------- -->
                  <!-- start notification Dropdown -->
                  <!-- ------------------------------- -->
                  <li class="nav-item nav-icon-hover-bg rounded-circle dropdown">
                    <a class="nav-link position-relative" href="javascript:void(0)" id="drop2" aria-expanded="false">
                      <i class="ti ti-bell-ringing"></i>
                      <div class="notification bg-primary rounded-circle"></div>
                    </a>
                    <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                      <div class="d-flex align-items-center justify-content-between py-3 px-7">
                        <h5 class="mb-0 fs-5 fw-semibold">Notifications</h5>
                        <span class="badge text-bg-primary rounded-2 px-3 py-1 lh-sm">5 new</span>
                      </div>
                      <div class="message-body" data-simplebar>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                          <span class="me-3">
                            <img src="../assets/images/profile/user-2.jpg" alt="user" class="rounded-circle" width="48" height="48" />
                          </span>
                          <div class="w-100">
                            <h6 class="mb-0 fs-4 lh-base">Roman Joined the Team!</h6>
                            <span class="fs-3 d-block text-body-secondary">Congratulate him</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                          <span class="me-3">
                            <img src="../assets/images/profile/user-3.jpg" alt="user" class="rounded-circle" width="48" height="48" />
                          </span>
                          <div class="w-100">
                            <h6 class="mb-0 fs-4 lh-base">New message</h6>
                            <span class="fs-3 d-block text-body-secondary">Salma sent you new message</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                          <span class="me-3">
                            <img src="../assets/images/profile/user-4.jpg" alt="user" class="rounded-circle" width="48" height="48" />
                          </span>
                          <div class="w-100">
                            <h6 class="mb-0 fs-4 lh-base">Bianca sent payment</h6>
                            <span class="fs-3 d-block text-body-secondary">Check your earnings</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                          <span class="me-3">
                            <img src="../assets/images/profile/user-5.jpg" alt="user" class="rounded-circle" width="48" height="48" />
                          </span>
                          <div class="w-100">
                            <h6 class="mb-0 fs-4 lh-base">Jolly completed tasks</h6>
                            <span class="fs-3 d-block text-body-secondary">Assign her new tasks</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                          <span class="me-3">
                            <img src="../assets/images/profile/user-6.jpg" alt="user" class="rounded-circle" width="48" height="48" />
                          </span>
                          <div class="w-100">
                            <h6 class="mb-0 fs-4 lh-base">John received payment</h6>
                            <span class="fs-3 d-block text-body-secondary">$230 deducted from account</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                          <span class="me-3">
                            <img src="../assets/images/profile/user-7.jpg" alt="user" class="rounded-circle" width="48" height="48" />
                          </span>
                          <div class="w-100">
                            <h6 class="mb-0 fs-4 lh-base">Roman Joined the Team!</h6>
                            <span class="fs-3 d-block text-body-secondary">Congratulate him</span>
                          </div>
                        </a>
                      </div>
                      <div class="py-6 px-7 mb-1">
                        <button class="btn btn-outline-primary w-100">See All Notifications</button>
                      </div>
                    </div>
                  </li>
                  <!-- ------------------------------- -->
                  <!-- end notification Dropdown -->
                  <!-- ------------------------------- -->

                  <!-- ------------------------------- -->
                  <!-- start profile Dropdown -->
                  <!-- ------------------------------- -->
                  <li class="nav-item dropdown">
                    <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" aria-expanded="false">
                      <div class="d-flex align-items-center">
                        <div class="user-profile-img">
                          <img src="../assets/images/profile/user-1.jpg" class="rounded-circle" width="35" height="35" alt="flexy-img" />
                        </div>
                      </div>
                    </a>
                    <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop1">
                      <div class="profile-dropdown position-relative" data-simplebar>
                        <div class="py-3 px-7 pb-0">
                          <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                        </div>
                        <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                          <img src="../assets/images/profile/user-1.jpg" class="rounded-circle" width="80" height="80" alt="flexy-img" />
                          <div class="ms-3">
                            <h5 class="mb-1 fs-4">Johnathan Doe</h5>
                            <span class="mb-1 d-block">Administrator</span>
                            <p class="mb-0 d-flex align-items-center gap-2">
                              <i class="ti ti-mail fs-4"></i> info@flexy.com
                            </p>
                          </div>
                        </div>
                        <div class="message-body">
                          <a href="../main/page-user-profile.html" class="py-8 px-7 mt-8 d-flex align-items-center">
                            <span class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                              <img src="../assets/images/svgs/icon-account.svg" alt="flexy-img" width="24" height="24" />
                            </span>
                            <div class="w-100 ps-3">
                              <h6 class="mb-0 fs-4 lh-base">My Profile</h6>
                              <span class="fs-3 d-block text-body-secondary">Account Settings</span>
                            </div>
                          </a>
                          <a href="../main/app-email.html" class="py-8 px-7 d-flex align-items-center">
                            <span class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                              <img src="../assets/images/svgs/icon-inbox.svg" alt="flexy-img" width="24" height="24" />
                            </span>
                            <div class="w-100 ps-3">
                              <h6 class="mb-0 fs-4 lh-base">My Inbox</h6>
                              <span class="fs-3 d-block text-body-secondary">Messages & Emails</span>
                            </div>
                          </a>
                          <a href="../main/app-notes.html" class="py-8 px-7 d-flex align-items-center">
                            <span class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                              <img src="../assets/images/svgs/icon-tasks.svg" alt="flexy-img" width="24" height="24" />
                            </span>
                            <div class="w-100 ps-3">
                              <h6 class="mb-0 fs-4 lh-base">My Task</h6>
                              <span class="fs-3 d-block text-body-secondary">To-do and Daily Tasks</span>
                            </div>
                          </a>
                        </div>
                        <div class="d-grid py-4 px-7 pt-8">
                          <div class="upgrade-plan bg-primary-subtle position-relative overflow-hidden rounded-2 p-4 mb-9">
                            <div class="row">
                              <div class="col-6">
                                <h5 class="fs-4 mb-3 fw-semibold">Unlimited Access</h5>
                                <button class="btn btn-primary">Upgrade</button>
                              </div>
                              <div class="col-6">
                                <div class="m-n4 unlimited-img">
                                  <img src="../assets/images/backgrounds/unlimited-bg.png" alt="flexy-img" class="w-100" />
                                </div>
                              </div>
                            </div>
                          </div>
                          <a href="<?= base_url();?>" class="btn btn-outline-primary">Log Out</a>
                        </div>
                      </div>
                    </div>
                  </li>
                  <!-- ------------------------------- -->
                  <!-- end profile Dropdown -->
                  <!-- ------------------------------- -->
                </ul>
              </div>
            </div>
          </nav>
        </div>
      </header>
      <!--  Header End -->

      <div class="body-wrapper">