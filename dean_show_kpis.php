<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'college_dean') {
    header("Location: index.php");
}
?>

<?php
$college_id = $_SESSION['college_id'];

// Get all programs in this college
$programs_query = "SELECT programs.*, departments.name AS dept_name 
                   FROM programs 
                   LEFT JOIN departments ON programs.department_id = departments.id 
                   WHERE departments.college_id = '$college_id'";
$programs_result = mysqli_query($link, $programs_query);
?>

<div class="hed-h2">
    <h2>College KPIs - All Programs</h2>
    <div align="right" style="margin-bottom:10px;">
        <button onclick="window.print();" class="btn btn-primary">
            <i class="fa-solid fa-print"></i> Print / Save as PDF
        </button>
    </div>
</div>

<?php while($program = mysqli_fetch_array($programs_result)) { 
    $kpis_query = "SELECT kpis.*, 
                   (SELECT actual_value FROM kpi_results WHERE kpi_id = kpis.id AND academic_year = '2024-2025') as actual_value,
                   (SELECT target_value FROM kpi_targets WHERE kpi_id = kpis.id AND academic_year = '2024-2025') as target_value,
                   (SELECT status FROM kpi_results WHERE kpi_id = kpis.id AND academic_year = '2024-2025') as status
                   FROM kpis WHERE kpis.program_id = '{$program['id']}'";
    $kpis_result = mysqli_query($link, $kpis_query);
    if(mysqli_num_rows($kpis_result) > 0) {
?>
    <div class="panel panel-default">
        <div class="panel-heading" style="background-color:#0099CC; color:white;">
            <strong><?php echo $program['dept_name'] . ' - ' . $program['name'];?></strong>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th>KPI Code</th>
                    <th>Title</th>
                    <th>Target</th>
                    <th>Actual</th>
                    <th>Status</th>
                </tr>
                <?php while($row = mysqli_fetch_array($kpis_result)) { 
                    $status_class = '';
                    if($row['status'] == 'Achieved') $status_class = 'style="color:green;font-weight:bold;"';
                    else if($row['status'] == 'Warning') $status_class = 'style="color:orange;font-weight:bold;"';
                    else if($row['status'] == 'Critical') $status_class = 'style="color:red;font-weight:bold;"';
                ?>
                <tr>
                    <td><?php echo $row['code'];?></td>
                    <td><?php echo $row['title'];?></td>
                    <td><?php echo $row['target_value'];?></td>
                    <td><?php echo $row['actual_value'];?></td>
                    <td <?php echo $status_class;?>><?php echo $row['status'];?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
<?php } } ?>

<?php include 'footer.php';?>