<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'super_admin') {
    header("Location: index.php");
}
?>

<?php
$departments_result = mysqli_query($link, "SELECT departments.*, colleges.name AS college_name 
                                           FROM departments 
                                           LEFT JOIN colleges ON departments.college_id = colleges.id");
?>

<?php
if (isset($_POST['btn-submit'])) {
    $department_id = $_POST['department_id'];
    $name = $_POST['name'];
    $degree_type = $_POST['degree_type'];

    if (mysqli_query($link, "INSERT INTO programs (department_id, name, degree_type) VALUES ('$department_id', '$name', '$degree_type')")) {
        echo "<script>alert('Program added successfully');</script>";
        echo "<meta http-equiv='Refresh' content='0; url=admin_show_programs.php'>";
    } else {
        echo "<script>alert('Error in add program');</script>";
    }
}
?>

<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Add Academic Program</div>
            <div class="panel-body">
                <form name="my_form" role="form" method="post">
                    <fieldset>
                        <div class="form-group">
                            Department
                            <select class="form-control" name="department_id" required>
                                <option value="">-- Select Department --</option>
                                <?php while($row = mysqli_fetch_array($departments_result)) { ?>
                                    <option value="<?php echo $row['id'];?>"><?php echo $row['college_name'] . ' - ' . $row['name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            Program Name <input type="text" class="form-control" name="name" required />
                        </div>
                        <div class="form-group">
                            Degree Type
                            <select class="form-control" name="degree_type">
                                <option value="">-- Select Degree --</option>
                                <option value="BSc">Bachelor of Science (BSc)</option>
                                <option value="BA">Bachelor of Arts (BA)</option>
                                <option value="MSc">Master of Science (MSc)</option>
                                <option value="MA">Master of Arts (MA)</option>
                                <option value="PhD">PhD</option>
                                <option value="Diploma">Diploma</option>
                            </select>
                        </div>
                        <div align="center">
                            <input type="submit" name="btn-submit" class="btn btn-primary" value="Add Program" />
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php';?>