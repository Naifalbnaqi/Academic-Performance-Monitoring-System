<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'program_head') {
    header("Location: index.php");
}
?>

<?php
$program_id = $_SESSION['program_id'];

// Get KPIs for this program with results
$kpis_query = "SELECT kpis.*, 
               (SELECT actual_value FROM kpi_results WHERE kpi_id = kpis.id AND academic_year = '2024-2025') as actual_value,
               (SELECT target_value FROM kpi_targets WHERE kpi_id = kpis.id AND academic_year = '2024-2025') as target_value,
               (SELECT status FROM kpi_results WHERE kpi_id = kpis.id AND academic_year = '2024-2025') as status
               FROM kpis 
               WHERE kpis.program_id = '$program_id'";
$kpis_result = mysqli_query($link, $kpis_query) or die('error: ' . mysqli_error($link));
?>

<div class="hed-h2">
    <h2>Program KPIs - Results <?php echo date('Y') . '-' . (date('Y')+1);?></h2>
    <div align="right" style="margin-bottom:10px;">
        <button onclick="window.print();" class="btn btn-primary">
            <i class="fa-solid fa-print"></i> Print / Save as PDF
        </button>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>Code</th>
            <th>Title</th>
            <th>Target</th>
            <th>Actual</th>
            <th>Achievement</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while($row = mysqli_fetch_array($kpis_result)) { 
            $achievement = ($row['target_value'] > 0) ? ($row['actual_value'] / $row['target_value'] * 100) : 0;
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
            <td><?php echo number_format($achievement, 2);?>%</td>
            <td <?php echo $status_class;?>><?php echo $row['status'];?></td>
            <td>
                <a href="program_head_review_data.php?kpi_id=<?php echo $row['id'];?>">Review Data</a>
                <?php if($row['status'] == 'Warning' || $row['status'] == 'Critical') { ?>
                    | <a href="program_head_add_action_plan.php?kpi_id=<?php echo $row['id'];?>">Add Action Plan</a>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

<?php include 'footer.php';?>