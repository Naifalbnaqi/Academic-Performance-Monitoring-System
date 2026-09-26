<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'super_admin') {
    header("Location: index.php");
}
?>

<?php
$result = mysqli_query($link, "SELECT programs.*, departments.name AS department_name, colleges.name AS college_name 
                               FROM programs 
                               LEFT JOIN departments ON programs.department_id = departments.id
                               LEFT JOIN colleges ON departments.college_id = colleges.id");
?>

<div class="hed-h2">
    <h2>Academic Programs</h2>
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>College</th>
            <th>Department</th>
            <th>Program Name</th>
            <th>Degree Type</th>
            <th>#</th>
        </tr>
        <?php while($row = mysqli_fetch_array($result)) { ?>
        <tr>
            <td><?php echo $row['college_name'];?></td>
            <td><?php echo $row['department_name'];?></td>
            <td><?php echo $row['name'];?></td>
            <td><?php echo $row['degree_type'];?></td>
            <td>
                <a href="admin_edit_program.php?id=<?php echo $row['id'];?>">Edit</a> |
                <a href="admin_delete_program.php?id=<?php echo $row['id'];?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php }?>
        <tr>
            <td align="center" colspan="5"><a href="admin_add_program.php">Add new program</a></td>
        </tr>
    </table>
</div>

<?php include 'footer.php';?>