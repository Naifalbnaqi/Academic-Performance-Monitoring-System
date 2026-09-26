<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'qa_director') {
    header("Location: index.php");
}
?>

<?php
// Get all results with program info
$results_query = "SELECT kpi_results.*, kpis.code, kpis.title, programs.name AS program_name, 
                         colleges.name AS college_name,
                         (SELECT target_value FROM kpi_targets WHERE kpi_id = kpis.id AND academic_year = kpi_results.academic_year) as target_value
                  FROM kpi_results 
                  LEFT JOIN kpis ON kpi_results.kpi_id = kpis.id
                  LEFT JOIN programs ON kpis.program_id = programs.id
                  LEFT JOIN departments ON programs.department_id = departments.id
                  LEFT JOIN colleges ON departments.college_id = colleges.id
                  WHERE kpi_results.academic_year = '2024-2025'
                  ORDER BY colleges.name, programs.name";
$results_result = mysqli_query($link, $results_query);
?>

<div class="hed-h2">
    <h2>All KPI Results - University Wide</h2>
    <div align="right" style="margin-bottom:10px;">
        <button onclick="window.print();" class="btn btn-primary">
            <i class="fa-solid fa-print"></i> Print / Save as PDF
        </button>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>College</th>
            <th>Program</th>
            <th>KPI Code</th>
            <th>Title</th>
            <th>Target</th>
            <th>Actual</th>
            <th>Achievement</th>
            <th>Status</th>
            <th>QA Status</th>
            <th>Actions</th>
        </tr>
        <?php while($row = mysqli_fetch_array($results_result)) { 
            $status_class = '';
            if($row['status'] == 'Achieved') $status_class = 'style="color:green;font-weight:bold;"';
            else if($row['status'] == 'Warning') $status_class = 'style="color:orange;font-weight:bold;"';
            else if($row['status'] == 'Critical') $status_class = 'style="color:red;font-weight:bold;"';
            
            $qa_status_class = '';
            $qa_status_text = '';
            if($row['qa_approved'] == 'Approved') {
                $qa_status_class = 'style="color:green;font-weight:bold;"';
                $qa_status_text = 'Approved';
            } else if($row['qa_approved'] == 'Returned') {
                $qa_status_class = 'style="color:red;font-weight:bold;"';
                $qa_status_text = 'Returned';
            } else {
                $qa_status_class = 'style="color:orange;font-weight:bold;"';
                $qa_status_text = 'Pending';
            }
        ?>
        <tr>
            <td><?php echo $row['college_name'];?></td>
            <td><?php echo $row['program_name'];?></td>
            <td><?php echo $row['code'];?></td>
            <td><?php echo substr($row['title'], 0, 40); echo (strlen($row['title'])>40)?'...':'';?></td>
            <td><?php echo $row['target_value'];?></td>
            <td><?php echo $row['actual_value'];?></td>
            <td><?php echo number_format($row['achievement_percentage'],2);?>%</td>
            <td <?php echo $status_class;?>><?php echo $row['status'];?></td>
            <td <?php echo $qa_status_class;?>><?php echo $qa_status_text;?></td>
            <td><a href="qa_director_approve.php?id=<?php echo $row['id'];?>">Review</a></td>
        </tr>
        <?php } ?>
    </table>
</div>

<?php include 'footer.php';?>