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

$kpi_result = mysqli_query($link, "SELECT * FROM kpis WHERE id='$kpi_id'");
$kpi_row = mysqli_fetch_array($kpi_result);
?>

<?php
if (isset($_POST['btn-submit'])) {
    $description = $_POST['description'];
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_name = time() . '_' . basename($_FILES["evidence_file"]["name"]);
    $target_file = $target_dir . $file_name;
    
    if (move_uploaded_file($_FILES["evidence_file"]["tmp_name"], $target_file)) {
        $query = "INSERT INTO evidence (kpi_id, academic_year, file_path, description, uploaded_by) 
                  VALUES ('$kpi_id', '$academic_year', '$target_file', '$description', '$faculty_id')";
        
        if (mysqli_query($link, $query)) {
            echo "<script>alert('Evidence uploaded successfully');</script>";
            echo "<meta http-equiv='Refresh' content='0; url=faculty_show_kpis.php'>";
        } else {
            echo "<script>alert('Error in save to database');</script>";
        }
    } else {
        echo "<script>alert('Error in file upload');</script>";
    }
}
?>

<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Upload Evidence for: <?php echo $kpi_row['code'];?></div>
            <div class="panel-body">
                <form name="my_form" role="form" method="post" enctype="multipart/form-data">
                    <fieldset>
                        <div class="form-group">
                            Description <input type="text" class="form-control" name="description" required />
                        </div>
                        <div class="form-group">
                            Evidence File <input type="file" class="form-control" name="evidence_file" required />
                        </div>
                        <div align="center">
                            <input type="submit" name="btn-submit" class="btn btn-primary" value="Upload Evidence" />
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php';?>