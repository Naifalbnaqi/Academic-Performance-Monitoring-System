<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'super_admin') {
    header("Location: index.php");
}
?>

<?php
// get data for dropdowns
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

    $query = "INSERT INTO users (name, email, password, role, college_id, department_id, program_id) 
              VALUES ('$name', '$email', '$password', '$role', $college_id, $department_id, $program_id)";
    
    if (mysqli_query($link, $query)) {
        echo "<script>alert('User added successfully');</script>";
        echo "<meta http-equiv='Refresh' content='0; url=admin_show_users.php'>";
    } else {
        echo "<script>alert('Error in add user');</script>";
    }
}
?>

<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Add New User</div>
            <div class="panel-body">
                <form name="my_form" role="form" method="post">
                    <fieldset>
                        <div class="form-group">
                            Name <input type="text" class="form-control" name="name" required />
                        </div>
                        <div class="form-group">
                            Email <input type="email" class="form-control" name="email" required />
                        </div>
                        <div class="form-group">
                            Password <input type="password" class="form-control" name="password" required />
                        </div>
                        <div class="form-group">
                            Role
                            <select class="form-control" name="role" required id="role">
                                <option value="">-- Select Role --</option>
                                <option value="super_admin">Super Admin</option>
                                <option value="qa_director">QA Director</option>
                                <option value="college_dean">College Dean</option>
                                <option value="program_head">Program Head</option>
                                <option value="faculty">Faculty</option>
                                <option value="auditor">Auditor</option>
                            </select>
                        </div>
                        <div class="form-group" id="college_div" style="display:none;">
                            College
                            <select class="form-control" name="college_id">
                                <option value="">-- Select College --</option>
                                <?php while($row = mysqli_fetch_array($colleges_result)) { ?>
                                    <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group" id="department_div" style="display:none;">
                            Department
                            <select class="form-control" name="department_id">
                                <option value="">-- Select Department --</option>
                                <?php mysqli_data_seek($departments_result, 0);
                                while($row = mysqli_fetch_array($departments_result)) { ?>
                                    <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group" id="program_div" style="display:none;">
                            Program
                            <select class="form-control" name="program_id">
                                <option value="">-- Select Program --</option>
                                <?php mysqli_data_seek($programs_result, 0);
                                while($row = mysqli_fetch_array($programs_result)) { ?>
                                    <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div align="center">
                            <input type="submit" name="btn-submit" class="btn btn-primary" value="Add User" />
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('role').addEventListener('change', function() {
    var role = this.value;
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
});
</script>

<?php include 'footer.php';?>