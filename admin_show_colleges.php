<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'super_admin') {
    header("Location: index.php");
}
?>

<?php
$result = mysqli_query($link, "SELECT colleges.*, universities.name AS university_name 
                               FROM colleges 
                               LEFT JOIN universities ON colleges.university_id = universities.id");
?>

<div class="hed-h2">
    <h2>Colleges</h2>
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>University</th>
            <th>College Name</th>
            <th>Dean</th>
            <th>#</th>
        </tr>
        <?php while($row = mysqli_fetch_array($result)) { ?>
        <tr>
            <td><?php echo $row['university_name'];?></td>
            <td><?php echo $row['name'];?></td>
            <td><?php echo $row['dean'];?></td>
            <td>
                <a href="admin_edit_college.php?id=<?php echo $row['id'];?>">Edit</a> |
                <a href="admin_delete_college.php?id=<?php echo $row['id'];?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php }?>
        <tr>
            <td align="center" colspan="4"><a href="admin_add_college.php">Add new college</a></td>
        </tr>
    </table>
</div>

<?php include 'footer.php';?>