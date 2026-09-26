<?php include 'header.php';?>

<?php
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $upass = $_POST['password'];

    // check user in users table
    $res = mysqli_query($link, "SELECT * FROM users WHERE email='$email' AND password='$upass'");
    
    if (mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_array($res);
        $_SESSION['user_type'] = $row['role'];
        $_SESSION['user_id']   = $row['id'];
        $_SESSION['user_name'] = $row['name'];
        $_SESSION['college_id'] = $row['college_id'];
        $_SESSION['department_id'] = $row['department_id'];
        $_SESSION['program_id'] = $row['program_id'];
        
        header("Location: index.php");
        exit;
    } else {
        echo "<script>alert('Invalid email or password');</script>";
        header("REFRESH:0; url=login.php");
    }
}
?>

<?php include 'footer.php';?>