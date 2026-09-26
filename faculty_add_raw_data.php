<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'faculty') {
    header("Location: index.php");
}
?>

<?php
$kpi_id = $_GET['kpi_id'];
$faculty_id = $_SESSION['user_id'];
$academic_year = '2024-2025';

// Get KPI info
$kpi_result = mysqli_query($link, "SELECT * FROM kpis WHERE id='$kpi_id'");
$kpi_row = mysqli_fetch_array($kpi_result);
?>

<?php
if (isset($_POST['btn-submit'])) {
    $value = $_POST['value'];
    
    $query = "INSERT INTO kpi_raw_data (kpi_id, academic_year, value, entered_by) 
              VALUES ('$kpi_id', '$academic_year', '$value', '$faculty_id')";
    
    if (mysqli_query($link, $query)) {
        echo "<script>alert('Data entered successfully');</script>";
        echo "<meta http-equiv='Refresh' content='0; url=faculty_show_kpis.php'>";
    } else {
        echo "<script>alert('Error in enter data');</script>";
    }
}
?>

<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Enter Data for: <?php echo $kpi_row['code'] . ' - ' . $kpi_row['title'];?></div>
            <div class="panel-body">
                <form name="my_form" role="form" method="post">
                    <fieldset>
                        <div class="form-group">
                            Formula Type: <strong><?php echo $kpi_row['formula_type'];?></strong>
                        </div>
                        <div class="form-group">
                            Value 
                            <input type="number" step="0.01" class="form-control" name="value" required 
                                   placeholder="<?php 
                                   if($kpi_row['formula_type']=='average') echo 'Enter average value (e.g., 4.5)';
                                   else if($kpi_row['formula_type']=='percentage') echo 'Enter percentage (e.g., 85)';
                                   else echo 'Enter count (e.g., 10)';
                                   ?>" />
                        </div>
                        <div class="form-group">
                            Academic Year: <strong><?php echo $academic_year;?></strong>
                            <input type="hidden" name="academic_year" value="<?php echo $academic_year;?>" />
                        </div>
                        <div align="center">
                            <input type="submit" name="btn-submit" class="btn btn-primary" value="Submit Data" />
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php';?>