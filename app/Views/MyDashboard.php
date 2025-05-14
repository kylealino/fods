<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');
$query = $this->db->query("
    SELECT 
        `full_name`
      
    FROM 
        `myua_user` 
    WHERE 
        `username` = '$this->cuser'"
    );

    $data = $query->getRowArray();
    $full_name = $data['full_name'];
echo view('templates/myheader.php');
?>
<div class="container-fluid">
    <div class="page-titles mb-2">
        <div class="row mb-2 mt-0">
            <h4 class="fw-semibold mb-8">Dashboard</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a class="text-muted text-decoration-none" href="<?=site_url();?>"><i class="ti ti-home fs-5"></i></a>
                </li>
                <li class="breadcrumb-item" aria-current="page">Home</li>
                <li class="breadcrumb-item" aria-current="page"><span class="form-label fw-bold">Dashboard</span></li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
                <div class="card bg-primary-gt text-white overflow-hidden shadow-none">
                        <div class="card-body">
                            <div class="row justify-content-between align-items-center">
                            <div class="col-sm-6">
                                <h5 class="fw-semibold mb-9 fs-5 text-white">Welcome back <?=$full_name;?>!</h5>
                                <p class="mb-9 opacity-75">
                                    Great to see you again — let’s make this month even more productive!
                                </p>
                                <button type="button" class="btn btn-danger">View Profile</button>
                            </div>
                            <div class="col-sm-5">
                                <div class="position-relative mb-n7 text-end">
                                <img src="<?=base_url('assets/images/backgrounds/welcome-bg2.png')?>" alt="flexy-img" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card bg-primary">
                <div class="card-body mb-2">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title text-white">Total Expense</h4>
                        <div class="ms-auto">
                            <span class="btn round-48 fs-7 rounded-circle btn-info d-flex align-items-center justify-content-center">
                            <i class="ti ti-currency-dollar"></i>
                            </span>
                        </div>
                        </div>
                    <div class="mt-5">
                        <h2 class="fs-8 text-white mb-0">$8,271,812.32</h2>
                        <span class="text-white text-opacity-50">Monthly revenue</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mb-3">
            <div class="card-group">
                <div class="card">
                    <div class="card-body">
                        <span class="btn round-50 fs-6 text-info rounded-circle bg-info-subtle d-flex align-items-center justify-content-center">
                            <i class="ti ti-users"></i>
                        </span>
                        <h3 class="mt-3 pt-1 mb-0 fs-6">
                            39,354
                            <span class="fs-2 ms-1 text-danger fw-medium">-9%</span>
                        </h3>
                        <h6 class="text-muted mb-0 fw-normal">Total Transactions</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                    <span class="btn round-50 fs-6 text-warning rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center">
                        <i class="ti ti-package"></i>
                    </span>
                    <h3 class="mt-3 pt-1 mb-0 fs-6">
                        4396
                        <span class="fs-2 ms-1 text-success fw-medium">+23%</span>
                    </h3>
                    <h6 class="text-muted mb-0 fw-normal">Pending</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                    <span class="btn round-50 fs-6 text-danger rounded-circle bg-danger-subtle d-flex align-items-center justify-content-center">
                        <i class="ti ti-chart-bar"></i>
                    </span>
                    <h3 class="mt-3 pt-1 mb-0 fs-6 d-flex align-items-center">
                        423,39
                        <span class="fs-2 ms-1 text-success fw-medium">+38%</span>
                    </h3>
                    <h6 class="text-muted mb-0 fw-normal">Approved</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                    <span class="btn round-50 fs-6 text-success rounded-circle bg-success-subtle d-flex align-items-center justify-content-center">
                        <i class="ti ti-refresh"></i>
                    </span>
                    <h3 class="mt-3 pt-1 mb-0 fs-6">
                        835
                        <span class="fs-2 ms-1 text-danger fw-medium">-12%</span>
                    </h3>
                    <h6 class="text-muted mb-0 fw-normal">Disapproved</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-md-flex align-items-center">
                        <div>
                            <h4 class="card-title">Budget Monitoring</h4>
                            <p class="card-subtitle">As of Today</p>
                        </div>
                        <div class="ms-auto">
                            <ul class="list-style-none">
                            <li class="list-inline-item">
                                <span class="round-8 text-bg-primary rounded-circle me-1 d-inline-block"></span>
                                Expence
                            </li>
                            <li class="list-inline-item">
                                <span class="round-8 text-bg-secondary rounded-circle me-1 d-inline-block"></span>
                                Budget
                            </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-5 border-end">
                            <div class="pe-4">
                                <h3 class="fs-8 d-flex align-items-center mb-0">
                                    $10,271,438.78
                                    <span class="btn btn-circle btn-sm btn-success fs-1 ms-2 d-flex align-items-center justify-content-center">23%</span>
                                </h3>
                                <h6 class="fw-normal text-muted mb-0">Budget</h6>
                                <h3 class="fs-8 d-flex align-items-center mb-0 mt-4">
                                    $8,271,812.32
                                </h3>
                                <h6 class="fw-normal text-muted mb-0">Expence</h6>
                                <div class="mt-3 mb-4">
                                    <div id="budget-expence-side-chart"></div>
                                </div>
                                <a href="javascript:void(0)" class="btn btn-info">Download Report</a>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div id="product-performance" class="ps-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card bg-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title text-white">Available Budget</h4>
                        <div class="ms-auto">
                            <span class="btn round-48 fs-7 rounded-circle btn-info d-flex align-items-center justify-content-center">
                            <i class="ti ti-currency-dollar"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h2 class="fs-8 text-white mb-0">$10,271,438.78</h2>
                        <span class="text-white text-opacity-50">Year 2025</span>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 col-xl-7">
                            <h3 class="fs-7 mb-0">$19,357,332.98</h3>
                            <h6 class="text-muted fw-normal">Yearly Budget</h6>
                            <div class="row mt-4 pt-2 gx-0">
                                <div class="col-6">
                                    <span class="round-8 text-bg-info rounded-circle me-1 d-inline-block"></span>
                                    2025
                                </div>
                                <div class="col-6">
                                    <span class="round-8 text-bg-primary rounded-circle me-1 d-inline-block"></span>
                                    2024
                                </div>
                                <div class="col-6 mt-2">
                                    <span class="round-8 text-bg-warning rounded-circle me-1 d-inline-block"></span>
                                    2023
                                </div>
                                <div class="col-6 mt-2">
                                    <span class="round-8 text-bg-muted rounded-circle me-1 d-inline-block"></span>
                                    2022
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-xl-5 d-flex align-items-center">
                            <div id="yearly-sales"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
  <script src="<?=base_url('assets/libs/apexcharts/dist/apexcharts.min.js')?>"></script>
  <script src="<?=base_url('assets/js/dashboards/dashboard2.js')?>"></script>
<?php
    echo view('templates/myfooter.php');
?>
