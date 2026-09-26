<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'super_admin') {
    header("Location: index.php");
}
?>

<?php
$programs_result = mysqli_query($link, "SELECT programs.*, departments.name AS dept_name, colleges.name AS college_name 
                                        FROM programs 
                                        LEFT JOIN departments ON programs.department_id = departments.id
                                        LEFT JOIN colleges ON departments.college_id = colleges.id");
?>

<?php
if (isset($_POST['btn-submit'])) {
    $code = $_POST['code'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $formula_type = $_POST['formula_type'];
    $program_id = $_POST['program_id'];

    $query = "INSERT INTO kpis (code, title, description, category, formula_type, program_id) 
              VALUES ('$code', '$title', '$description', '$category', '$formula_type', '$program_id')";
    
    if (mysqli_query($link, $query)) {
        echo "<script>alert('KPI added successfully');</script>";
        echo "<meta http-equiv='Refresh' content='0; url=admin_show_kpis.php'>";
    } else {
        echo "<script>alert('Error in add KPI');</script>";
    }
}
?>

<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Add New KPI</div>
            <div class="panel-body">
                <form name="my_form" role="form" method="post">
                    <fieldset>
                        <div class="form-group">
                            KPI Code <input type="text" class="form-control" name="code" required placeholder="e.g., KPI-P-01" />
                        </div>
                        <div class="form-group">
                            Title <input type="text" class="form-control" name="title" required />
                        </div>
                        <div class="form-group">
                            Description <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            Category
                            <select class="form-control" name="category" required>
                                <option value="">-- Select Category --</option>
                                <option value="Program">Program</option>
                                <option value="Course">Course</option>
                                <option value="Institutional">Institutional</option>
                                <option value="Research">Research</option>
                                <option value="Community Service">Community Service</option>
                                <option value="Administrative">Administrative</option>
                            </select>
                        </div>
                        <div class="form-group">
                            Formula Type
                            <select class="form-control" name="formula_type" required>
                                <option value="">-- Select Formula Type --</option>
                                <option value="average">Average</option>
                                <option value="percentage">Percentage</option>
                                <option value="count">Count</option>
                            </select>
                        </div>
                        <div class="form-group">
                            Program
                            <select class="form-control" name="program_id" required>
                                <option value="">-- Select Program --</option>
                                <?php while($row = mysqli_fetch_array($programs_result)) { ?>
                                    <option value="<?php echo $row['id'];?>"><?php echo $row['college_name'] . ' - ' . $row['dept_name'] . ' - ' . $row['name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div align="center">
                            <input type="submit" name="btn-submit" class="btn btn-primary" value="Add KPI" />
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php';?>