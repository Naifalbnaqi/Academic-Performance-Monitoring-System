<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'auditor') {
    header("Location: index.php");
}
?>

<!-- Google Charts Library -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<?php
// Statistics
$total_kpis = mysqli_fetch_array(mysqli_query($link, "SELECT COUNT(*) as count FROM kpis"))['count'];
$achieved = mysqli_fetch_array(mysqli_query($link, "SELECT COUNT(*) as count FROM kpi_results WHERE status='Achieved' AND academic_year='2024-2025'"))['count'];
$warning = mysqli_fetch_array(mysqli_query($link, "SELECT COUNT(*) as count FROM kpi_results WHERE status='Warning' AND academic_year='2024-2025'"))['count'];
$critical = mysqli_fetch_array(mysqli_query($link, "SELECT COUNT(*) as count FROM kpi_results WHERE status='Critical' AND academic_year='2024-2025'"))['count'];
$action_plans = mysqli_fetch_array(mysqli_query($link, "SELECT COUNT(*) as count FROM action_plans"))['count'];

// Get data for pie chart
$stats_query = "SELECT status, COUNT(*) as count FROM kpi_results WHERE academic_year='2024-2025' GROUP BY status";
$stats_result = mysqli_query($link, $stats_query);
$achieved = 0; $warning = 0; $critical = 0;
while($stat = mysqli_fetch_array($stats_result)) {
    if($stat['status'] == 'Achieved') $achieved = $stat['count'];
    if($stat['status'] == 'Warning') $warning = $stat['count'];
    if($stat['status'] == 'Critical') $critical = $stat['count'];
}
?>

<script type="text/javascript">
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Status', 'Count'],
        ['Achieved',     <?php echo $achieved; ?>],
        ['Warning',      <?php echo $warning; ?>],
        ['Critical',     <?php echo $critical; ?>]
    ]);

    var options = {
        title: 'KPI Status Distribution',
        pieHole: 0.4,
        colors: ['#4CAF50', '#FF9800', '#F44336']
    };

    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    chart.draw(data, options);
}
</script>

<div class="hed-h2">
    <h2>Auditor Dashboard</h2>
    <div align="right" style="margin-bottom:10px;">
        <button onclick="window.print();" class="btn btn-primary">
            <i class="fa-solid fa-print"></i> Print / Save as PDF
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="panel panel-primary">
            <div class="panel-heading">Total KPIs</div>
            <div class="panel-body" style="font-size:30px; text-align:center;"><?php echo $total_kpis;?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-success">
            <div class="panel-heading">Achieved</div>
            <div class="panel-body" style="font-size:30px; text-align:center; color:green;"><?php echo $achieved;?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-warning">
            <div class="panel-heading">Warning</div>
            <div class="panel-body" style="font-size:30px; text-align:center; color:orange;"><?php echo $warning;?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-danger">
            <div class="panel-heading">Critical</div>
            <div class="panel-body" style="font-size:30px; text-align:center; color:red;"><?php echo $critical;?></div>
        </div>
    </div>
</div>

<!-- Pie Chart -->
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">KPI Status Distribution Chart</div>
            <div class="panel-body">
                <div id="chart_div" style="width: 100%; height: 400px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">Action Plans in Progress</div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>KPI</th>
                        <th>Issue</th>
                        <th>Responsible</th>
                        <th>Deadline</th>
                        <th>Status</th>
                    </tr>
                    <?php 
                    $plans_query = "SELECT action_plans.*, kpis.code 
                                    FROM action_plans 
                                    LEFT JOIN kpi_results ON action_plans.kpi_result_id = kpi_results.id
                                    LEFT JOIN kpis ON kpi_results.kpi_id = kpis.id
                                    LIMIT 10";
                    $plans_result = mysqli_query($link, $plans_query);
                    while($plan = mysqli_fetch_array($plans_result)) { ?>
                    <tr>
                        <td><?php echo $plan['code'];?></td>
                        <td><?php echo substr($plan['issue_description'], 0, 30); echo (strlen($plan['issue_description'])>30)?'...':'';?></td>
                        <td><?php echo $plan['responsible_person'];?></td>
                        <td><?php echo $plan['deadline'];?></td>
                        <td><?php echo $plan['status'];?></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">Recent Evidence Uploads</div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>KPI</th>
                        <th>Description</th>
                        <th>Upload Date</th>
                    </tr>
                    <?php 
                    $evidence_query = "SELECT evidence.*, kpis.code 
                                       FROM evidence 
                                       LEFT JOIN kpis ON evidence.kpi_id = kpis.id 
                                       ORDER BY evidence.upload_date DESC LIMIT 10";
                    $evidence_result = mysqli_query($link, $evidence_query);
                    while($ev = mysqli_fetch_array($evidence_result)) { ?>
                    <tr>
                        <td><?php echo $ev['code'];?></td>
                        <td><?php echo substr($ev['description'], 0, 30); echo (strlen($ev['description'])>30)?'...':'';?></td>
                        <td><?php echo $ev['upload_date'];?></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php';?>