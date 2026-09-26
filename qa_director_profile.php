<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'qa_director') {
    header("Location: index.php");
    exit;
}
?>

<?php
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $id = $_SESSION['user_id'];

    if (mysqli_query($link, "UPDATE users SET name='$name', email='$email', password='$password' WHERE id=$id")) {
        $_SESSION['user_name'] = $name;
        ?>
        <script>alert('Profile updated successfully');</script>
        <?php header("REFRESH:0; url=qa_director_profile.php");?>
    <?php } else { ?>
        <script>alert('Error in update');</script>
    <?php }
}
?>

<?php
$res = mysqli_query($link, "SELECT * FROM users WHERE id=" . $_SESSION['user_id']) or die("error: " . mysqli_error($link));
$row = mysqli_fetch_array($res);
?>

<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Update QA Director Profile</div>
            <div class="panel-body">
                <form name="my_form" role="form" method="post">
                    <fieldset>
                        <div class="form-group">
                            Name <input type="text" name="name" required value="<?php echo $row['name'];?>" class="form-control" />
                        </div>
                        <div class="form-group">
                            Email <input type="email" name="email" required value="<?php echo $row['email'];?>" class="form-control" />
                        </div>
                        <div class="form-group">
                            Password <input type="password" name="password" required value="<?php echo $row['password'];?>" class="form-control" />
                        </div>
                        <div align="center">
                            <input type="submit" name="submit" class="btn btn-primary" value="Update Profile" />
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php';?>