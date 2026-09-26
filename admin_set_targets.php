<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'super_admin') {
    header("Location: index.php");
}
?>

<?php
$kpi_id = $_GET['kpi_id'];
$kpi_result = mysqli_query($link, "SELECT * FROM kpis WHERE id='$kpi_id'");
$kpi_row = mysqli_fetch_array($kpi_result);
?>

<?php
if (isset($_POST['btn-submit'])) {
    $academic_year = $_POST['academic_year'];
    $target_value = $_POST['target_value'];
    $internal_benchmark = $_POST['internal_benchmark'];
    $external_benchmark = $_POST['external_benchmark'];
    $new_target = $_POST['new_target'];

    // Check if target already exists for this KPI and year
    $check = mysqli_query($link, "SELECT * FROM kpi_targets WHERE kpi_id='$kpi_id' AND academic_year='$academic_year'");
    
    if (mysqli_num_rows($check) > 0) {
        $query = "UPDATE kpi_targets SET target_value='$target_value', internal_benchmark='$internal_benchmark', 
                  external_benchmark='$external_benchmark', new_target='$new_target' 
                  WHERE kpi_id='$kpi_id' AND academic_year='$academic_year'";
    } else {
        $query = "INSERT INTO kpi_targets (kpi_id, academic_year, target_value, internal_benchmark, external_benchmark, new_target) 
                  VALUES ('$kpi_id', '$academic_year', '$target_value', '$internal_benchmark', '$external_benchmark', '$new_target')";
    }
    
    if (mysqli_query($link, $query)) {
        echo "<script>alert('Target saved successfully');</script>";
        echo "<meta http-equiv='Refresh' content='0; url=admin_show_kpis.php'>";
    } else {
        echo "<script>alert('Error in save target');</script>";
    }
}
?>

<?php
$targets_result = mysqli_query($link, "SELECT * FROM kpi_targets WHERE kpi_id='$kpi_id' ORDER BY academic_year DESC");
?>

<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Set Targets for: <?php echo $kpi_row['code'] . ' - ' . $kpi_row['title'];?></div>
            <div class="panel-body">
                <form name="my_form" role="form" method="post">
                    <fieldset>
                        <div class="form-group">
                            Academic Year
                            <select class="form-control" name="academic_year" required>
                                <option value="2023-2024">2023-2024</option>
                                <option value="2024-2025" selected>2024-2025</option>
                                <option value="2025-2026">2025-2026</option>
                                <option value="2026-2027">2026-2027</option>
                            </select>
                        </div>
                        <div class="form-group">
                            Target Value <input type="number" step="0.01" class="form-control" name="target_value" required />
                        </div>
                        <div class="form-group">
                            Internal Benchmark <input type="number" step="0.01" class="form-control" name="internal_benchmark" />
                        </div>
                        <div class="form-group">
                            External Benchmark <input type="number" step="0.01" class="form-control" name="external_benchmark" />
                        </div>
                        <div class="form-group">
                            New Target <input type="number" step="0.01" class="form-control" name="new_target" />
                        </div>
                        <div align="center">
                            <input type="submit" name="btn-submit" class="btn btn-primary" value="Save Target" />
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if(mysqli_num_rows($targets_result) > 0) { ?>
<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">Previous Targets</div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>Academic Year</th>
                        <th>Target Value</th>
                        <th>Internal Benchmark</th>
                        <th>External Benchmark</th>
                        <th>New Target</th>
                    </tr>
                    <?php while($trow = mysqli_fetch_array($targets_result)) { ?>
                    <tr>
                        <td><?php echo $trow['academic_year'];?></td>
                        <td><?php echo $trow['target_value'];?></td>
                        <td><?php echo $trow['internal_benchmark'];?></td>
                        <td><?php echo $trow['external_benchmark'];?></td>
                        <td><?php echo $trow['new_target'];?></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php include 'footer.php';?>