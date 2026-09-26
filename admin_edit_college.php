<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'super_admin') {
    header("Location: index.php");
}
?>

<?php
$universities_result = mysqli_query($link, "SELECT * FROM universities");
?>

<?php
if (isset($_POST['btn-submit'])) {
    $university_id = $_POST['university_id'];
    $name = $_POST['name'];
    $dean = $_POST['dean'];

    if (mysqli_query($link, "UPDATE colleges SET university_id='$university_id', name='$name', dean='$dean' WHERE id=$_GET[id]")) {
        echo "<script>alert('College updated successfully');</script>";
        echo "<meta http-equiv='Refresh' content='0; url=admin_show_colleges.php'>";
    } else {
        echo "<script>alert('Error in update');</script>";
    }
}
?>

<?php
$result = mysqli_query($link, "SELECT * FROM colleges WHERE id='$_GET[id]'");
$row = mysqli_fetch_array($result);
?>

<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Edit College</div>
            <div class="panel-body">
                <form name="my_form" role="form" method="post">
                    <fieldset>
                        <div class="form-group">
                            University
                            <select class="form-control" name="university_id" required>
                                <?php while($urow = mysqli_fetch_array($universities_result)) { ?>
                                    <option value="<?php echo $urow['id'];?>" <?php if($row['university_id']==$urow['id']) echo 'selected';?>><?php echo $urow['name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            College Name <input type="text" class="form-control" name="name" required value="<?php echo $row['name'];?>" />
                        </div>
                        <div class="form-group">
                            Dean Name <input type="text" class="form-control" name="dean" value="<?php echo $row['dean'];?>" />
                        </div>
                        <div align="center">
                            <input type="submit" name="btn-submit" class="btn btn-primary" value="Update College" />
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php';?>