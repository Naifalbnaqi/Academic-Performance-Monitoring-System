<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'super_admin') {
    header("Location: index.php");
}
?>

<?php
$users_query = "SELECT users.*, colleges.name AS college_name, departments.name AS department_name, programs.name AS program_name 
                FROM users 
                LEFT JOIN colleges ON users.college_id = colleges.id
                LEFT JOIN departments ON users.department_id = departments.id
                LEFT JOIN programs ON users.program_id = programs.id";
$users_result = mysqli_query($link, $users_query) or die('error : ' . mysqli_error($link));
?>

<div class="hed-h2">
    <h2>Users in System</h2>
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>College</th>
            <th>Department</th>
            <th>Program</th>
            <th>#</th>
        </tr>
        <?php while ($user_row = mysqli_fetch_array($users_result)) { ?>
        <tr>
            <td><?php echo $user_row['name'];?></td>
            <td><?php echo $user_row['email'];?></td>
            <td><?php echo $user_row['role'];?></td>
            <td><?php echo $user_row['college_name'];?></td>
            <td><?php echo $user_row['department_name'];?></td>
            <td><?php echo $user_row['program_name'];?></td>
            <td>
                <a href="admin_edit_user.php?id=<?php echo $user_row['id'];?>">Edit</a> |
                <a href="admin_delete_user.php?id=<?php echo $user_row['id'];?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php }?>
        <tr>
            <td align="center" colspan="7"><a href="admin_add_user.php">Add new user</a></td>
        </tr>
    </table>
</div>

<?php include 'footer.php';?>