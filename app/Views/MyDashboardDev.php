<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');

echo view('templates/myheader.php');
?>

<style>
html, body {
    margin: 0; padding: 0; height: 100%;
    font-family: "Segoe UI", Arial, sans-serif;
    background-color: #f1f5f9;
}

/* Wrapper */
.dashboard-wrapper {
    min-height: calc(100vh - 60px);
    padding: 20px 25px 70px 25px; 
    display: flex;
    justify-content: center;
    align-items: flex-start;
    overflow-y: auto;
}

.dashboard-container {
    width: 100%;
    max-width: 1100px; 
    background: #fff;
    border-radius: 12px;
    padding: 20px 25px 25px 25px; 
    box-shadow: 0 6px 25px rgba(37, 99, 235, 0.08);
    border: 1px solid #e6effd;
    display: flex;
    flex-direction: column;
    box-sizing: border-box;
}

/* Header */
.dev-header {
    text-align: center;
    margin-bottom: 30px;
}

.dev-header h2 {
    font-size: 24px; /* medium size */
    font-weight: 700;
    color: #1e3a8a;
    letter-spacing: 0.02em;
    margin-bottom: 10px;
    font-family: 'Segoe UI Semibold', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: inline-block;
    padding-bottom: 6px;
}

.dev-header h2::after {
    content: '';
    display: block;
    width: 45%;
    height: 2px;
    background-color: #2563eb;
    margin: 6px auto 0 auto;
    border-radius: 2px;
}

.dev-header .header-icon {
    font-size: 30px; /* medium size */
    display: block;
    margin: 0 auto 8px auto;
    animation: rotate 2s linear infinite;
}

@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.dev-header p {
    font-size: 15px; /* medium size */
    font-weight: 500;
    color: #475569;
    margin: 0 auto;
    max-width: 480px;
    line-height: 1.5;
    letter-spacing: 0.01em;
}

/* Skeleton shimmer */
.skeleton {
    background: linear-gradient(90deg, #e5ecf6 25%, #f1f5fb 37%, #e5ecf6 63%);
    background-size: 400% 100%;
    animation: shimmer 1.5s infinite;
    border-radius: 10px;
    position: relative;
    overflow: hidden;
}

@keyframes shimmer {
    0% { background-position: -400px 0; }
    100% { background-position: 400px 0; }
}

/* Banner placeholders */
.banner-row {
    display: flex;
    gap: 18px; 
    margin-bottom: 25px;
}
.banner-welcome {
    flex: 2;
    height: 95px; /* medium height */
    border-radius: 12px;
}
.banner-approved {
    flex: 1;
    height: 95px; /* medium height */
    border-radius: 12px;
}

/* KPI row */
.kpi-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px; 
    margin-bottom: 25px;
}
.kpi-card {
    height: 55px; /* medium height */
    border-radius: 12px;
}

/* Lower section */
.lower-row {
    display: grid;
    grid-template-columns: 1fr 1.3fr 1fr 1fr;
    gap: 18px;
}
.lower-box {
    border-radius: 12px;
    height: 180px; /* medium height */
}

/* Skeleton shapes */
.skeleton-text {
    width: 65%;
    height: 14px;
    margin: 11px auto 0 auto;
    border-radius: 8px;
}
.skeleton-circle {
    width: 85px; 
    height: 85px; 
    border-radius: 50%;
    margin: 0 auto;
    margin-top: 11px;
}
.skeleton-graph {
    width: 100%;
    height: 140px; 
    margin-top: 14px;
    border-radius: 12px;
}
</style>

<div class="dashboard-wrapper">
    <div class="dashboard-container">

        <div class="dev-header">
            <span class="header-icon">⚙️</span> 
            <h2>Division's Dashboard Under Development</h2>
            <p>This dashboard is currently being developed to meet the specific needs of your division and will be available in a future release. Thank you for your patience.</p>
        </div>

        <!-- Banner placeholders -->
        <div class="banner-row">
            <div class="skeleton banner-welcome"></div>
            <div class="skeleton banner-approved"></div>
        </div>

        <!-- KPI placeholders -->
        <div class="kpi-row">
            <div class="skeleton kpi-card"></div>
            <div class="skeleton kpi-card"></div>
            <div class="skeleton kpi-card"></div>
            <div class="skeleton kpi-card"></div>
        </div>

        <!-- Lower section placeholders -->
        <div class="lower-row">
            <div>
                <div class="skeleton skeleton-circle"></div>
                <div class="skeleton skeleton-text"></div>
            </div>

            <div>
                <div class="skeleton skeleton-graph"></div>
                <div class="skeleton skeleton-text"></div>
            </div>

            <div class="skeleton lower-box"></div>
            <div class="skeleton lower-box"></div>
        </div>

    </div>
</div>

<?php echo view('templates/myfooter.php'); ?>
