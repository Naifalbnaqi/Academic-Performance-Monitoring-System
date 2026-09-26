<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'super_admin') {
    header("Location: index.php");
}
?>

<?php
$colleges_result = mysqli_query($link, "SELECT * FROM colleges");
?>

<?php
if (isset($_POST['btn-submit'])) {
    $college_id = $_POST['college_id'];
    $name = $_POST['name'];

    if (mysqli_query($link, "UPDATE departments SET college_id='$college_id', name='$name' WHERE id=$_GET[id]")) {
        echo "<script>alert('Department updated successfully');</script>";
        echo "<meta http-equiv='Refresh' content='0; url=admin_show_departments.php'>";
    } else {
        echo "<script>alert('Error in update');</script>";
    }
}
?>

<?php
$result = mysqli_query($link, "SELECT * FROM departments WHERE id='$_GET[id]'");
$row = mysqli_fetch_array($result);
?>

<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Edit Department</div>
            <div class="panel-body">
                <form name="my_form" role="form" method="post">
                    <fieldset>
                        <div class="form-group">
                            College
                            <select class="form-control" name="college_id" required>
                                <?php while($crow = mysqli_fetch_array($colleges_result)) { ?>
                                    <option value="<?php echo $crow['id'];?>" <?php if($row['college_id']==$crow['id']) echo 'selected';?>><?php echo $crow['name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            Department Name <input type="text" class="form-control" name="name" required value="<?php echo $row['name'];?>" />
                        </div>
                        <div align="center">
                            <input type="submit" name="btn-submit" class="btn btn-primary" value="Update Department" />
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php';?>