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

    if (mysqli_query($link, "INSERT INTO colleges (university_id, name, dean) VALUES ('$university_id', '$name', '$dean')")) {
        echo "<script>alert('College added successfully');</script>";
        echo "<meta http-equiv='Refresh' content='0; url=admin_show_colleges.php'>";
    } else {
        echo "<script>alert('Error in add college');</script>";
    }
}
?>

<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Add College</div>
            <div class="panel-body">
                <form name="my_form" role="form" method="post">
                    <fieldset>
                        <div class="form-group">
                            University
                            <select class="form-control" name="university_id" required>
                                <option value="">-- Select University --</option>
                                <?php while($row = mysqli_fetch_array($universities_result)) { ?>
                                    <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            College Name <input type="text" class="form-control" name="name" required />
                        </div>
                        <div class="form-group">
                            Dean Name <input type="text" class="form-control" name="dean" />
                        </div>
                        <div align="center">
                            <input type="submit" name="btn-submit" class="btn btn-primary" value="Add College" />
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php';?>