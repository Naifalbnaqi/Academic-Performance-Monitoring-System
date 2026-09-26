<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'super_admin') {
    header("Location: index.php");
}
?>

<?php
mysqli_query($link, "DELETE FROM kpis WHERE id='$_GET[id]'") or die('error ' . mysqli_error($link));

if (mysqli_affected_rows($link) == 1) {
    echo "<script>alert('Deleted successfully');</script>";
    echo "<meta http-equiv='Refresh' content='0; url=admin_show_kpis.php'>";
} else {
    echo "<script>alert('Error in delete');</script>";
    echo "<meta http-equiv='Refresh' content='0; url=admin_show_kpis.php'>";
}
?>

<?php include 'footer.php';?>