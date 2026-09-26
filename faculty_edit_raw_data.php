<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'faculty') {
    header("Location: index.php");
}
?>

<?php
$data_id = $_GET['id'];
$data_result = mysqli_query($link, "SELECT * FROM kpi_raw_data WHERE id='$data_id' AND entered_by='{$_SESSION['user_id']}'");
$data_row = mysqli_fetch_array($data_result);

if (!$data_row) {
    echo "<script>alert('You can only edit your own data');</script>";
    echo "<meta http-equiv='Refresh' content='0; url=faculty_show_kpis.php'>";
    exit;
}

$kpi_result = mysqli_query($link, "SELECT * FROM kpis WHERE id='{$data_row['kpi_id']}'");
$kpi_row = mysqli_fetch_array($kpi_result);
?>

<?php
if (isset($_POST['btn-submit'])) {
    $value = $_POST['value'];
    
    $query = "UPDATE kpi_raw_data SET value='$value' WHERE id='$data_id'";
    
    if (mysqli_query($link, $query)) {
        echo "<script>alert('Data updated successfully');</script>";
        echo "<meta http-equiv='Refresh' content='0; url=faculty_show_kpis.php'>";
    } else {
        echo "<script>alert('Error in update');</script>";
    }
}
?>

<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Edit Data for: <?php echo $kpi_row['code'];?></div>
            <div class="panel-body">
                <form name="my_form" role="form" method="post">
                    <fieldset>
                        <div class="form-group">
                            Current Value: <strong><?php echo $data_row['value'];?></strong>
                        </div>
                        <div class="form-group">
                            New Value 
                            <input type="number" step="0.01" class="form-control" name="value" required 
                                   value="<?php echo $data_row['value'];?>" />
                        </div>
                        <div align="center">
                            <input type="submit" name="btn-submit" class="btn btn-primary" value="Update Data" />
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php';?>