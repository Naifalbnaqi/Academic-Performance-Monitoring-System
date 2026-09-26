<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'faculty') {
    header("Location: index.php");
}
?>

<?php
$program_id = $_SESSION['program_id'];
$faculty_id = $_SESSION['user_id'];

// Get KPIs for faculty's program
$kpis_query = "SELECT kpis.*, programs.name AS program_name 
               FROM kpis 
               LEFT JOIN programs ON kpis.program_id = programs.id 
               WHERE kpis.program_id = '$program_id'";
$kpis_result = mysqli_query($link, $kpis_query) or die('error: ' . mysqli_error($link));
?>

<div class="hed-h2">
    <h2>My Assigned KPIs</h2>
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
            <th>Category</th>
            <th>Formula Type</th>
            <th>Program</th>
            <th>Submitted Data</th>
            <th>Actions</th>
        </tr>
        <?php while($row = mysqli_fetch_array($kpis_result)) { 
            // Get submitted data for this KPI
            $submitted_query = "SELECT * FROM kpi_raw_data WHERE kpi_id='{$row['id']}' AND entered_by='$faculty_id'";
            $submitted_result = mysqli_query($link, $submitted_query);
            $submitted_values = array();
            $submitted_ids = array();
            while($sub = mysqli_fetch_array($submitted_result)) {
                $submitted_values[] = $sub['value'];
                $submitted_ids[] = $sub['id'];
            }
        ?>
        <tr>
            <td><?php echo $row['code'];?></td>
            <td><?php echo $row['title'];?></td>
            <td><?php echo $row['category'];?></td>
            <td><?php echo $row['formula_type'];?></td>
            <td><?php echo $row['program_name'];?></td>
            <td>
                <?php 
                if(count($submitted_values) > 0) {
                    echo implode(', ', $submitted_values);
                } else {
                    echo '<span style="color:gray;">No data</span>';
                }
                ?>
            </td>
            <td>
                <a href="faculty_add_raw_data.php?kpi_id=<?php echo $row['id'];?>">Add Data</a>
                <?php 
                // Display Edit buttons for each submitted data
                foreach($submitted_ids as $index => $sid) { ?>
                    | <a href="faculty_edit_raw_data.php?id=<?php echo $sid;?>">Edit</a>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

<?php include 'footer.php';?>