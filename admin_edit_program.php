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

    if (mysqli_query($link, "UPDATE programs SET department_id='$department_id', name='$name', degree_type='$degree_type' WHERE id=$_GET[id]")) {
        echo "<script>alert('Program updated successfully');</script>";
        echo "<meta http-equiv='Refresh' content='0; url=admin_show_programs.php'>";
    } else {
        echo "<script>alert('Error in update');</script>";
    }
}
?>

<?php
$result = mysqli_query($link, "SELECT * FROM programs WHERE id='$_GET[id]'");
$row = mysqli_fetch_array($result);
?>

<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Edit Program</div>
            <div class="panel-body">
                <form name="my_form" role="form" method="post">
                    <fieldset>
                        <div class="form-group">
                            Department
                            <select class="form-control" name="department_id" required>
                                <?php while($drow = mysqli_fetch_array($departments_result)) { ?>
                                    <option value="<?php echo $drow['id'];?>" <?php if($row['department_id']==$drow['id']) echo 'selected';?>><?php echo $drow['college_name'] . ' - ' . $drow['name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            Program Name <input type="text" class="form-control" name="name" required value="<?php echo $row['name'];?>" />
                        </div>
                        <div class="form-group">
                            Degree Type
                            <select class="form-control" name="degree_type">
                                <option value="">-- Select Degree --</option>
                                <option value="BSc" <?php if($row['degree_type']=='BSc') echo 'selected';?>>Bachelor of Science (BSc)</option>
                                <option value="BA" <?php if($row['degree_type']=='BA') echo 'selected';?>>Bachelor of Arts (BA)</option>
                                <option value="MSc" <?php if($row['degree_type']=='MSc') echo 'selected';?>>Master of Science (MSc)</option>
                                <option value="MA" <?php if($row['degree_type']=='MA') echo 'selected';?>>Master of Arts (MA)</option>
                                <option value="PhD" <?php if($row['degree_type']=='PhD') echo 'selected';?>>PhD</option>
                                <option value="Diploma" <?php if($row['degree_type']=='Diploma') echo 'selected';?>>Diploma</option>
                            </select>
                        </div>
                        <div align="center">
                            <input type="submit" name="btn-submit" class="btn btn-primary" value="Update Program" />
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php';?>