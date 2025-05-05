<?php
    echo view('templates/myheader.php');
?>
<div class="container-fluid">
    <div class="page-titles mb-5">
    <div class="row">
        <div class="col-lg-8 col-md-6 col-12 align-self-center">
        <h4 class="text-muted mb-0 fw-normal fs-5">
            Welcome Johnathan
        </h4>
        <h2 class="mb-0 fw-bolderer fs-8">eCommerce Dashboard</h2>
        </div>
        <div class="col-lg-4 col-md-6 d-none d-md-flex align-items-center justify-content-end">
        <select class="form-select w-auto bg-primary-subtle border-0" aria-label="Default select example">
            <option selected>Today 23 March</option>
            <option value="1">Today 23 March</option>
            <option value="2">Today 24 March</option>
            <option value="3">Today 25 March</option>
        </select>
        <a href="javascript:void(0)" class="btn btn-primary d-flex align-items-center ms-2">
            <i class="ti ti-plus me-1"></i>
            Add New
        </a>
        </div>
    </div>
    </div>
    <!-- row -->
    <div class="row">
    <div class="col-lg-4">
        <div class="card welcome-card2 overflow-hidden bg-primary-subtle border-0">
        <div class="card-body">
            <div class="d-flex align-items-start position-relative">
            <div>
                <h4 class="fw-bolder fs-5">Earnings</h4>
                <h2 class="text-primary fs-7">$63,438.78</h2>
            </div>
            <div class="ms-auto">
                <span class="btn round-48 fs-7 rounded-circle btn-primary d-flex align-items-center justify-content-center">
                <i class="ti ti-currency-dollar"></i>
                </span>
            </div>
            </div>
            <a href="javascript:void(0)" class="btn btn-primary position-relative mt-2">Download</a>
        </div>
        </div>
    </div>
    <div class="col-lg-8">
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
            <h6 class="text-muted mb-0 fw-normal">Customers</h6>
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
            <h6 class="text-muted mb-0 fw-normal">Products</h6>
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
            <h6 class="text-muted mb-0 fw-normal">Sales</h6>
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
            <h6 class="text-muted mb-0 fw-normal">Refunds</h6>
            </div>
        </div>
        </div>
    </div>
    <!-- column -->
    <div class="col-lg-8">
        <div class="card w-100">
        <div class="card-body">
            <div class="d-md-flex align-items-center">
            <div>
                <h4 class="card-title">Products Performance</h4>
                <p class="card-subtitle">Latest new products</p>
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
                    $93,438.78
                    <span class="btn btn-circle btn-sm btn-success fs-1 ms-2 d-flex align-items-center justify-content-center">23%</span>
                </h3>
                <h6 class="fw-normal text-muted mb-0">Budget</h6>
                <h3 class="fs-8 d-flex align-items-center mb-0 mt-4">
                    $32,839.00
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
    <!-- column -->
    <div class="col-lg-4">
        <!-- earnings card -->
        <div class="card bg-primary">
        <div class="card-body">
            <div class="d-flex align-items-center">
            <h4 class="card-title text-white">Earnings</h4>
            <div class="ms-auto">
                <span class="btn round-48 fs-7 rounded-circle btn-info d-flex align-items-center justify-content-center">
                <i class="ti ti-currency-dollar"></i>
                </span>
            </div>
            </div>
            <div class="mt-3">
            <h2 class="fs-8 text-white mb-0">$93,438.78</h2>
            <span class="text-white text-opacity-50">Monthly revenue</span>
            </div>
        </div>
        </div>
        <!-- yearly sales -->
        <div class="card">
        <div class="card-body">
            <div class="row">
            <div class="col-6 col-xl-7">
                <h3 class="fs-8 mb-0">43,246</h3>
                <h6 class="text-muted fw-normal">Yearly sales</h6>
                <div class="row mt-4 pt-2 gx-0">
                <div class="col-6">
                    <span class="round-8 text-bg-info rounded-circle me-1 d-inline-block"></span>
                    2024
                </div>
                <div class="col-6">
                    <span class="round-8 text-bg-primary rounded-circle me-1 d-inline-block"></span>
                    2024
                </div>
                <div class="col-6 mt-2">
                    <span class="round-8 text-bg-warning rounded-circle me-1 d-inline-block"></span>
                    2019
                </div>
                <div class="col-6 mt-2">
                    <span class="round-8 text-bg-muted rounded-circle me-1 d-inline-block"></span>
                    2018
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
    <!-- column -->
    <div class="col-lg-4">
        <div class="card">
        <div class="card-body">
            <h4 class="card-title">Recent Transactions</h4>
            <p class="card-subtitle">List of payments</p>
            <div class="d-flex align-items-center mt-4 mb-3 pb-1">
            <a href="javascript:void(0)" class="btn fs-6 bg-info-subtle text-info round d-flex align-items-center justify-content-center">
                <i class="ti ti-currency-dollar flex-shrink-0"></i>
            </a>
            <div class="ms-3 ps-1">
                <h5 class="mb-1 fs-4">PayPal Transfer</h5>
                <h6 class="text-muted fw-normal mb-0 fs-3">Money Added</h6>
            </div>
            <h6 class="ms-auto text-success">+$350</h6>
            </div>
            <div class="d-flex align-items-center my-3 py-1">
            <a href="javascript:void(0)" class="btn fs-6 bg-success-subtle text-success round d-flex align-items-center justify-content-center">
                <i class="ti ti-shield flex-shrink-0"></i>
            </a>
            <div class="ms-3 ps-1">
                <h5 class="mb-1 fs-4">Wallet</h5>
                <h6 class="text-muted fw-normal mb-0 fs-3">Bill payment</h6>
            </div>
            <h6 class="ms-auto text-danger">-$560</h6>
            </div>
            <div class="d-flex align-items-center my-3 py-1">
            <a href="javascript:void(0)" class="btn fs-6 bg-danger-subtle text-danger round d-flex align-items-center justify-content-center">
                <i class="ti ti-credit-card flex-shrink-0"></i>
            </a>
            <div class="ms-3 ps-1">
                <h5 class="mb-1 fs-4">Credit Card</h5>
                <h6 class="text-muted fw-normal mb-0 fs-3">
                Money reversed
                </h6>
            </div>
            <h6 class="ms-auto text-success">+$350</h6>
            </div>
            <div class="d-flex align-items-center my-3 py-1">
            <a href="javascript:void(0)" class="btn fs-6 bg-warning-subtle text-warning round d-flex align-items-center justify-content-center">
                <i class="ti ti-check flex-shrink-0"></i>
            </a>
            <div class="ms-3 ps-1">
                <h5 class="mb-1 fs-4">Bank Transfer</h5>
                <h6 class="text-muted fw-normal mb-0 fs-3">Money Added</h6>
            </div>
            <h6 class="ms-auto text-success">+$350</h6>
            </div>
            <div class="d-flex align-items-center my-3 pb-4 border-bottom">
            <a href="javascript:void(0)" class="btn fs-6 bg-primary-subtle text-primary round d-flex align-items-center justify-content-center">
                <i class="ti ti-refresh flex-shrink-0"></i>
            </a>
            <div class="ms-3 ps-1">
                <h5 class="mb-1 fs-4">Refund</h5>
                <h6 class="text-muted fw-normal mb-0 fs-3">Payment Sent</h6>
            </div>
            <h6 class="ms-auto text-danger">-$50</h6>
            </div>
            <div class="d-flex align-items-center">
            <a href="javascript:void(0)" class="btn btn-info">Add</a>
            <div class="ms-auto">
                <span class="fs-3 text-muted">36 Recent Transactions
                </span>
            </div>
            </div>
        </div>
        </div>
    </div>
    <!-- column -->
    <div class="col-lg-8">
        <div class="card">
        <div class="card-body">
            <h4 class="card-title">Products Performance</h4>
            <p class="card-subtitle">Latest new products</p>
            <!-- Nav tabs -->
            <ul class="nav nav-pills justify-content-end mt-md-n5" role="tablist">
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#navpill-11" role="tab">
                <span>Pending</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#navpill-22" role="tab">
                <span>Active</span>
                </a>
            </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
            <div class="tab-pane" id="navpill-11" role="tabpanel">
                <div class="table-responsive mt-9">
                <table class="table mb-0 align-middle text-nowrap fs-3">
                    <tbody>
                    <tr>
                        <td class="px-0">
                        <div class="d-flex align-items-center">
                            <img src="../assets/images/products/product-2.jpg" class="rounded" alt="product" />
                            <div class="ms-4">
                            <h5 class="mb-0 fs-4">
                                Supreme toys presents best gift
                            </h5>
                            <span class="text-muted fs-3 mt-2">Angular, React</span>
                            </div>
                        </div>
                        </td>
                        <td>
                        <span class="text-dark fw-medium fs-3">Excellent</span>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 98%" aria-valuenow="98" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h6 class="text-muted fw-normal mt-2">
                            98% Sold
                        </h6>
                        </td>
                        <td>
                        <span class="fs-3 text-muted fw-normal">Earnings</span>
                        <h5 class="mb-0 fs-4">$546,000</h5>
                        </td>
                        <td class="pe-0 text-end">
                        <div class="d-flex align-items-center">
                            <a href="javascript:void(0)" class="me-3">
                            <i class="ti ti-edit fs-6"></i>
                            </a>
                            <a href="javascript:void(0)">
                            <i class="ti ti-trash fs-6"></i>
                            </a>
                        </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-0">
                        <div class="d-flex align-items-center">
                            <img src="../assets/images/products/product-3.jpg" class="rounded" alt="product" />
                            <div class="ms-4">
                            <h5 class="mb-0 fs-4">
                                Red color shoes from Gucci
                            </h5>
                            <span class="text-muted fs-3 mt-2">Bootstrap, React</span>
                            </div>
                        </div>
                        </td>
                        <td>
                        <span class="text-dark fw-medium fs-3">Average</span>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 46%" aria-valuenow="46" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h6 class="text-muted fw-normal mt-2">
                            46% Sold
                        </h6>
                        </td>
                        <td>
                        <span class="fs-3 text-muted fw-normal">Earnings</span>
                        <h5 class="mb-0 fs-4">$546,000</h5>
                        </td>
                        <td class="pe-0 text-end">
                        <div class="d-flex align-items-center">
                            <a href="javascript:void(0)" class="me-3">
                            <i class="ti ti-edit fs-6"></i>
                            </a>
                            <a href="javascript:void(0)">
                            <i class="ti ti-trash fs-6"></i>
                            </a>
                        </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-0">
                        <div class="d-flex align-items-center">
                            <img src="../assets/images/products/product-1.jpg" class="rounded" alt="product" />
                            <div class="ms-4">
                            <h5 class="mb-0 fs-4">
                                Nike branding shoes for Men and Women
                            </h5>
                            <span class="text-muted fs-3 mt-2">Bootstrap, Angular, React</span>
                            </div>
                        </div>
                        </td>
                        <td>
                        <span class="text-dark fw-medium fs-3">Good</span>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h6 class="text-muted fw-normal mt-2">
                            65% Sold
                        </h6>
                        </td>
                        <td>
                        <span class="fs-3 text-muted fw-normal">Earnings</span>
                        <h5 class="mb-0 fs-4">$546,000</h5>
                        </td>
                        <td class="pe-0 text-end">
                        <div class="d-flex align-items-center">
                            <a href="javascript:void(0)" class="me-3">
                            <i class="ti ti-edit fs-6"></i>
                            </a>
                            <a href="javascript:void(0)">
                            <i class="ti ti-trash fs-6"></i>
                            </a>
                        </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-0 pb-0">
                        <div class="d-flex align-items-center">
                            <img src="../assets/images/products/product-4.jpg" class="rounded" alt="product" />
                            <div class="ms-4">
                            <h5 class="mb-0 fs-4">
                                Stylish sneakers for men and women
                            </h5>
                            <span class="text-muted fs-3 mt-2">Bootstrap, Angular, React</span>
                            </div>
                        </div>
                        </td>
                        <td class="pb-0">
                        <span class="text-dark fw-medium fs-3">Bad</span>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 23%" aria-valuenow="23" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h6 class="text-muted fw-normal mt-2">
                            23% Sold
                        </h6>
                        </td>
                        <td class="pb-0">
                        <span class="fs-3 text-muted fw-normal">Earnings</span>
                        <h5 class="mb-0 fs-4">$546,000</h5>
                        </td>
                        <td class="pe-0 pb-0 text-end">
                        <div class="d-flex align-items-center">
                            <a href="javascript:void(0)" class="me-3">
                            <i class="ti ti-edit fs-6"></i>
                            </a>
                            <a href="javascript:void(0)">
                            <i class="ti ti-trash fs-6"></i>
                            </a>
                        </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                </div>
            </div>
            <div class="tab-pane active" id="navpill-22" role="tabpanel">
                <div class="table-responsive mt-9">
                <table class="table mb-0 align-middle text-nowrap fs-3">
                    <tbody>
                    <tr>
                        <td class="px-0">
                        <div class="d-flex align-items-center">
                            <img src="../assets/images/products/product-1.jpg" class="rounded" alt="product" />
                            <div class="ms-4">
                            <h5 class="mb-0 fs-4">
                                Nike branding shoes for Men and Women
                            </h5>
                            <span class="text-muted fs-3 mt-2">Bootstrap, Angular, React</span>
                            </div>
                        </div>
                        </td>
                        <td>
                        <span class="text-dark fw-medium fs-3">Good</span>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h6 class="text-muted fw-normal mt-2">
                            65% Sold
                        </h6>
                        </td>
                        <td>
                        <span class="fs-3 text-muted fw-normal">Earnings</span>
                        <h5 class="mb-0 fs-4">$546,000</h5>
                        </td>
                        <td class="pe-0 text-end">
                        <div class="d-flex align-items-center">
                            <a href="javascript:void(0)" class="me-3">
                            <i class="ti ti-edit fs-6"></i>
                            </a>
                            <a href="javascript:void(0)">
                            <i class="ti ti-trash fs-6"></i>
                            </a>
                        </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-0">
                        <div class="d-flex align-items-center">
                            <img src="../assets/images/products/product-2.jpg" class="rounded" alt="product" />
                            <div class="ms-4">
                            <h5 class="mb-0 fs-4">
                                Supreme toys presents best gift
                            </h5>
                            <span class="text-muted fs-3 mt-2">Angular, React</span>
                            </div>
                        </div>
                        </td>
                        <td>
                        <span class="text-dark fw-medium fs-3">Excellent</span>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 98%" aria-valuenow="98" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h6 class="text-muted fw-normal mt-2">
                            98% Sold
                        </h6>
                        </td>
                        <td>
                        <span class="fs-3 text-muted fw-normal">Earnings</span>
                        <h5 class="mb-0 fs-4">$546,000</h5>
                        </td>
                        <td class="pe-0 text-end">
                        <div class="d-flex align-items-center">
                            <a href="javascript:void(0)" class="me-3">
                            <i class="ti ti-edit fs-6"></i>
                            </a>
                            <a href="javascript:void(0)">
                            <i class="ti ti-trash fs-6"></i>
                            </a>
                        </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-0">
                        <div class="d-flex align-items-center">
                            <img src="../assets/images/products/product-3.jpg" class="rounded" alt="product" />
                            <div class="ms-4">
                            <h5 class="mb-0 fs-4">
                                Red color shoes from Gucci
                            </h5>
                            <span class="text-muted fs-3 mt-2">Bootstrap, React</span>
                            </div>
                        </div>
                        </td>
                        <td>
                        <span class="text-dark fw-medium fs-3">Average</span>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 46%" aria-valuenow="46" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h6 class="text-muted fw-normal mt-2">
                            46% Sold
                        </h6>
                        </td>
                        <td>
                        <span class="fs-3 text-muted fw-normal">Earnings</span>
                        <h5 class="mb-0 fs-4">$546,000</h5>
                        </td>
                        <td class="pe-0 text-end">
                        <div class="d-flex align-items-center">
                            <a href="javascript:void(0)" class="me-3">
                            <i class="ti ti-edit fs-6"></i>
                            </a>
                            <a href="javascript:void(0)">
                            <i class="ti ti-trash fs-6"></i>
                            </a>
                        </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-0 pb-0">
                        <div class="d-flex align-items-center">
                            <img src="../assets/images/products/product-4.jpg" class="rounded" alt="product" />
                            <div class="ms-4">
                            <h5 class="mb-0 fs-4">
                                Stylish sneakers for men and women
                            </h5>
                            <span class="text-muted fs-3 mt-2">Bootstrap, Angular, React</span>
                            </div>
                        </div>
                        </td>
                        <td class="pb-0">
                        <span class="text-dark fw-medium fs-3">Bad</span>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 23%" aria-valuenow="23" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h6 class="text-muted fw-normal mt-2">
                            23% Sold
                        </h6>
                        </td>
                        <td class="pb-0">
                        <span class="fs-3 text-muted fw-normal">Earnings</span>
                        <h5 class="mb-0 fs-4">$546,000</h5>
                        </td>
                        <td class="pe-0 pb-0 text-end">
                        <div class="d-flex align-items-center">
                            <a href="javascript:void(0)" class="me-3">
                            <i class="ti ti-edit fs-6"></i>
                            </a>
                            <a href="javascript:void(0)">
                            <i class="ti ti-trash fs-6"></i>
                            </a>
                        </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
    <!-- column -->
    <div class="col-lg-4">
        <div class="card overflow-hidden">
        <div class="card-body pb-0">
            <div class="d-flex align-items-start">
            <div>
                <h4 class="card-title">Weekly Stats</h4>
                <p class="card-subtitle">Average sales</p>
            </div>
            <div class="ms-auto">
                <div class="dropdown">
                <a href="javascript:void(0)" class="text-muted" id="year1-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-dots fs-7"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="year1-dropdown">
                    <li>
                    <a class="dropdown-item" href="javascript:void(0)">Action</a>
                    </li>
                    <li>
                    <a class="dropdown-item" href="javascript:void(0)">Another action</a>
                    </li>
                    <li>
                    <a class="dropdown-item" href="javascript:void(0)">Something else here</a>
                    </li>
                </ul>
                </div>
            </div>
            </div>
            <div class="mt-5 pb-3 d-flex align-items-center">
            <span class="btn btn-primary rounded-circle round-48 hstack justify-content-center">
                <i class="ti ti-shopping-cart fs-6"></i>
            </span>
            <div class="ms-3">
                <h5 class="mb-0 fw-bolder fs-4">Top Sales</h5>
                <span class="text-muted fs-3">Johnathan Doe</span>
            </div>
            <div class="ms-auto">
                <span class="badge bg-secondary-subtle text-muted">+68%</span>
            </div>
            </div>
            <div class="py-3 d-flex align-items-center">
            <span class="btn btn-warning rounded-circle round-48 hstack justify-content-center">
                <i class="ti ti-star fs-6"></i>
            </span>
            <div class="ms-3">
                <h5 class="mb-0 fw-bolder fs-4">Best Seller</h5>
                <span class="text-muted fs-3">MaterialPro Admin</span>
            </div>
            <div class="ms-auto">
                <span class="badge bg-secondary-subtle text-muted">+68%</span>
            </div>
            </div>
            <div class="pt-3 mb-7 d-flex align-items-center">
            <span class="btn btn-success rounded-circle round-48 hstack justify-content-center">
                <i class="ti ti-message-dots fs-6"></i>
            </span>
            <div class="ms-3">
                <h5 class="mb-0 fw-bolder fs-4">Most Commented</h5>
                <span class="text-muted fs-3">Ample Admin</span>
            </div>
            <div class="ms-auto">
                <span class="badge bg-secondary-subtle text-muted">+68%</span>
            </div>
            </div>
        </div>
        <div id="weekly-stats"></div>
        </div>
    </div>
    <!-- column -->
    <div class="col-lg-4">
        <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-start">
            <div>
                <h4 class="card-title">MedicalPro Branding</h4>
                <p class="card-subtitle">Branding & Website</p>
            </div>
            <div class="ms-auto">
                <div class="dropdown">
                <a href="javascript:void(0)" class="text-muted" id="medical-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-dots fs-7"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="medical-dropdown">
                    <li>
                    <a class="dropdown-item" href="javascript:void(0)">Action</a>
                    </li>
                    <li>
                    <a class="dropdown-item" href="javascript:void(0)">Another action</a>
                    </li>
                    <li>
                    <a class="dropdown-item" href="javascript:void(0)">Something else here</a>
                    </li>
                </ul>
                </div>
            </div>
            </div>
            <div class="mt-4">
            <span class="badge bg-primary-subtle text-primary">16 APR, 2024</span>
            <div class="row border-bottom mt-4 gx-0">
                <div class="col-4 pb-3 border-end">
                <h6 class="fw-normal text-muted mb-0 fs-3">Due Date</h6>
                <span class="fs-3 fw-medium text-dark">Oct 23, 2024</span>
                </div>
                <div class="col-4 pb-3 border-end ps-3">
                <h6 class="fw-normal text-muted mb-0 fs-3">Budget</h6>
                <span class="fs-3 fw-medium text-dark">$98,500</span>
                </div>
                <div class="col-4 pb-3 ps-3">
                <h6 class="fw-normal text-muted mb-0 fs-3">Expense</h6>
                <span class="fs-3 fw-medium text-dark">$63,000</span>
                </div>
            </div>
            <div class="mt-4 pb-4 border-bottom">
                <h4 class="fs-5">Teams</h4>
                <div class="mt-2 pt-1 mb-2">
                <span class="badge text-bg-warning">Bootstrap</span>
                <span class="badge text-bg-danger">Angular</span>
                </div>
            </div>
            <div class="mt-4 pb-4 border-bottom">
                <h4 class="fs-5">Leaders</h4>
                <div class="mt-2 pt-1 mb-2">
                <a href="javascript:void(0)" class="me-1">
                    <img src="../assets/images/profile/user-3.jpg" data-bs-toggle="tooltip" data-bs-placement="top" title="John" class="rounded-circle" width="35" />
                </a>
                <a href="javascript:void(0)" class="me-1">
                    <img src="../assets/images/profile/user-5.jpg" data-bs-toggle="tooltip" data-bs-placement="top" title="Nirav" class="rounded-circle" width="35" />
                </a>
                <a href="javascript:void(0)" class="me-1">
                    <img src="../assets/images/profile/user-6.jpg" data-bs-toggle="tooltip" data-bs-placement="top" title="Sunil" class="rounded-circle" width="35" />
                </a>
                <a href="javascript:void(0)">
                    <img src="../assets/images/profile/user-7.jpg" data-bs-toggle="tooltip" data-bs-placement="top" title="Maruti" class="rounded-circle" width="35" />
                </a>
                </div>
            </div>
            <div class="d-flex align-items-center mt-3">
                <a href="javascript:void(0)" class="btn btn-info">Add</a>
                <div class="ms-auto">
                <span class="fs-3 text-muted">28 Team Members, 268 Tasks
                </span>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
    <!-- column -->
    <div class="col-lg-4">
        <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-start">
            <div>
                <h4 class="card-title">Daily Activities</h4>
                <p class="card-subtitle">Overview of Years</p>
            </div>
            <div class="ms-auto">
                <div class="dropdown">
                <a href="javascript:void(0)" class="text-muted" id="daily-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-dots fs-7"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="daily-dropdown">
                    <li>
                    <a class="dropdown-item" href="javascript:void(0)">Action</a>
                    </li>
                    <li>
                    <a class="dropdown-item" href="javascript:void(0)">Another action</a>
                    </li>
                    <li>
                    <a class="dropdown-item" href="javascript:void(0)">Something else here</a>
                    </li>
                </ul>
                </div>
            </div>
            </div>
            <div class="mt-4 pt-2">
            <img src="../assets/images/blog/blog-img2.jpg" class="blog-img rounded w-100" height="180" alt="flexy" />
            <h4 class="card-title mt-4 mb-1">
                Angular 12 coming soon!
            </h4>
            <span class="text-muted">
                By
                <a href="javascript:void(0)" class="text-primary">Johnathan Doe</a>
            </span>
            <p class="fs-3 mt-4 text-muted">
                This will be the small description for the news you have
                shown here. There could be some great info.
            </p>
            <a href="javascript:void(0)" class="btn btn-info mt-3">Read more</a>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>
<?php
    echo view('templates/myfooter.php');
?>