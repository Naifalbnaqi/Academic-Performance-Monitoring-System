<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'super_admin') {
    header("Location: index.php");
}
?>

<?php
$result = mysqli_query($link, "SELECT kpis.*, programs.name AS program_name, departments.name AS department_name, colleges.name AS college_name 
                               FROM kpis 
                               LEFT JOIN programs ON kpis.program_id = programs.id
                               LEFT JOIN departments ON programs.department_id = departments.id
                               LEFT JOIN colleges ON departments.college_id = colleges.id");
?>

<div class="hed-h2">
    <h2>Key Performance Indicators (KPIs)</h2>
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
            <th>#</th>
        </tr>
        <?php while($row = mysqli_fetch_array($result)) { ?>
        <tr>
            <td><?php echo $row['code'];?></td>
            <td><?php echo substr($row['title'], 0, 50); echo (strlen($row['title'])>50)?'...':'';?></td>
            <td><?php echo $row['category'];?></td>
            <td><?php echo $row['formula_type'];?></td>
            <td><?php echo $row['college_name'] . ' - ' . $row['program_name'];?></td>
            <td>
                <a href="admin_edit_kpi.php?id=<?php echo $row['id'];?>">Edit</a> |
                <a href="admin_delete_kpi.php?id=<?php echo $row['id'];?>" onclick="return confirm('Are you sure?')">Delete</a> |
                <a href="admin_set_targets.php?kpi_id=<?php echo $row['id'];?>">Set Targets</a>
            </td>
        </tr>
        <?php }?>
        <tr>
            <td align="center" colspan="6"><a href="admin_add_kpi.php">Add new KPI</a></td>
        </tr>
    </table>
</div>

<?php include 'footer.php';?>