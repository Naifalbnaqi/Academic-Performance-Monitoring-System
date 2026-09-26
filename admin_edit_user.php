<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'super_admin') {
    header("Location: index.php");
}
?>

<?php
$colleges_result = mysqli_query($link, "SELECT * FROM colleges ORDER BY name");
$departments_result = mysqli_query($link, "SELECT * FROM departments ORDER BY name");
$programs_result = mysqli_query($link, "SELECT * FROM programs ORDER BY name");
?>

<?php
if (isset($_POST['btn-submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $college_id = $_POST['college_id'] ? $_POST['college_id'] : 'NULL';
    $department_id = $_POST['department_id'] ? $_POST['department_id'] : 'NULL';
    $program_id = $_POST['program_id'] ? $_POST['program_id'] : 'NULL';

    $query = "UPDATE users SET name='$name', email='$email', password='$password', role='$role', 
              college_id=$college_id, department_id=$department_id, program_id=$program_id 
              WHERE id=$_GET[id]";
    
    if (mysqli_query($link, $query)) {
        echo "<script>alert('User updated successfully');</script>";
        echo "<meta http-equiv='Refresh' content='0; url=admin_show_users.php'>";
    } else {
        echo "<script>alert('Error in update');</script>";
    }
}
?>

<?php
$user_result = mysqli_query($link, "SELECT * FROM users WHERE id='$_GET[id]'") or die("error: " . mysqli_error($link));
$user_row = mysqli_fetch_array($user_result);
?>

<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Edit User</div>
            <div class="panel-body">
                <form name="my_form" role="form" method="post">
                    <fieldset>
                        <div class="form-group">
                            Name <input type="text" class="form-control" name="name" required value="<?php echo $user_row['name'];?>" />
                        </div>
                        <div class="form-group">
                            Email <input type="email" class="form-control" name="email" required value="<?php echo $user_row['email'];?>" />
                        </div>
                        <div class="form-group">
                            Password <input type="password" class="form-control" name="password" required value="<?php echo $user_row['password'];?>" />
                        </div>
                        <div class="form-group">
                            Role
                            <select class="form-control" name="role" required id="role">
                                <option value="super_admin" <?php if($user_row['role']=='super_admin') echo 'selected';?>>Super Admin</option>
                                <option value="qa_director" <?php if($user_row['role']=='qa_director') echo 'selected';?>>QA Director</option>
                                <option value="college_dean" <?php if($user_row['role']=='college_dean') echo 'selected';?>>College Dean</option>
                                <option value="program_head" <?php if($user_row['role']=='program_head') echo 'selected';?>>Program Head</option>
                                <option value="faculty" <?php if($user_row['role']=='faculty') echo 'selected';?>>Faculty</option>
                                <option value="auditor" <?php if($user_row['role']=='auditor') echo 'selected';?>>Auditor</option>
                            </select>
                        </div>
                        <div class="form-group" id="college_div">
                            College
                            <select class="form-control" name="college_id">
                                <option value="">-- Select College --</option>
                                <?php while($row = mysqli_fetch_array($colleges_result)) { ?>
                                    <option value="<?php echo $row['id'];?>" <?php if($user_row['college_id']==$row['id']) echo 'selected';?>><?php echo $row['name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group" id="department_div">
                            Department
                            <select class="form-control" name="department_id">
                                <option value="">-- Select Department --</option>
                                <?php mysqli_data_seek($departments_result, 0);
                                while($row = mysqli_fetch_array($departments_result)) { ?>
                                    <option value="<?php echo $row['id'];?>" <?php if($user_row['department_id']==$row['id']) echo 'selected';?>><?php echo $row['name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group" id="program_div">
                            Program
                            <select class="form-control" name="program_id">
                                <option value="">-- Select Program --</option>
                                <?php mysqli_data_seek($programs_result, 0);
                                while($row = mysqli_fetch_array($programs_result)) { ?>
                                    <option value="<?php echo $row['id'];?>" <?php if($user_row['program_id']==$row['id']) echo 'selected';?>><?php echo $row['name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div align="center">
                            <input type="submit" name="btn-submit" class="btn btn-primary" value="Update User" />
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showDivs() {
    var role = document.getElementById('role').value;
    document.getElementById('college_div').style.display = 'none';
    document.getElementById('department_div').style.display = 'none';
    document.getElementById('program_div').style.display = 'none';
    
    if (role == 'college_dean') {
        document.getElementById('college_div').style.display = 'block';
    } else if (role == 'program_head') {
        document.getElementById('college_div').style.display = 'block';
        document.getElementById('department_div').style.display = 'block';
    } else if (role == 'faculty') {
        document.getElementById('college_div').style.display = 'block';
        document.getElementById('department_div').style.display = 'block';
        document.getElementById('program_div').style.display = 'block';
    }
}
document.getElementById('role').addEventListener('change', showDivs);
showDivs();
</script>

<?php include 'footer.php';?>