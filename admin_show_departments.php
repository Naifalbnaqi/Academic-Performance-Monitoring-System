<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'super_admin') {
    header("Location: index.php");
}
?>

<?php
$result = mysqli_query($link, "SELECT departments.*, colleges.name AS college_name 
                               FROM departments 
                               LEFT JOIN colleges ON departments.college_id = colleges.id");
?>

<div class="hed-h2">
    <h2>Departments</h2>
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>College</th>
            <th>Department Name</th>
            <th>#</th>
        </tr>
        <?php while($row = mysqli_fetch_array($result)) { ?>
        <tr>
            <td><?php echo $row['college_name'];?></td>
            <td><?php echo $row['name'];?></td>
            <td>
                <a href="admin_edit_department.php?id=<?php echo $row['id'];?>">Edit</a> |
                <a href="admin_delete_department.php?id=<?php echo $row['id'];?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php }?>
        <tr>
            <td align="center" colspan="3"><a href="admin_add_department.php">Add new department</a></td>
        </tr>
    </table>
</div>

<?php include 'footer.php';?>