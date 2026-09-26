<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'qa_director') {
    header("Location: index.php");
}
?>

<?php
// Get all KPIs for dropdown
$kpis_result = mysqli_query($link, "SELECT kpis.*, programs.name AS program_name 
                                    FROM kpis 
                                    LEFT JOIN programs ON kpis.program_id = programs.id");
?>

<?php
if (isset($_POST['btn-submit'])) {
    $kpi_id = $_POST['kpi_id'];
    $academic_year = $_POST['academic_year'];
    $description = $_POST['description'];
    $qa_id = $_SESSION['user_id'];
    
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_name = time() . '_' . basename($_FILES["evidence_file"]["name"]);
    $target_file = $target_dir . $file_name;
    
    if (move_uploaded_file($_FILES["evidence_file"]["tmp_name"], $target_file)) {
        $query = "INSERT INTO evidence (kpi_id, academic_year, file_path, description, uploaded_by) 
                  VALUES ('$kpi_id', '$academic_year', '$target_file', '$description', '$qa_id')";
        
        if (mysqli_query($link, $query)) {
            echo "<script>alert('Evidence uploaded successfully');</script>";
            echo "<meta http-equiv='Refresh' content='0; url=qa_director_show_all.php'>";
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
            <div class="panel-heading">Upload Evidence (QA Director)</div>
            <div class="panel-body">
                <form name="my_form" role="form" method="post" enctype="multipart/form-data">
                    <fieldset>
                        <div class="form-group">
                            Select KPI
                            <select class="form-control" name="kpi_id" required>
                                <option value="">-- Select KPI --</option>
                                <?php while($row = mysqli_fetch_array($kpis_result)) { ?>
                                    <option value="<?php echo $row['id'];?>"><?php echo $row['code'] . ' - ' . $row['program_name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            Academic Year
                            <select class="form-control" name="academic_year" required>
                                <option value="2023-2024">2023-2024</option>
                                <option value="2024-2025" selected>2024-2025</option>
                                <option value="2025-2026">2025-2026</option>
                            </select>
                        </div>
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