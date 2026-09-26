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

    $query = "UPDATE kpis SET code='$code', title='$title', description='$description', 
              category='$category', formula_type='$formula_type', program_id='$program_id' 
              WHERE id=$_GET[id]";
    
    if (mysqli_query($link, $query)) {
        echo "<script>alert('KPI updated successfully');</script>";
        echo "<meta http-equiv='Refresh' content='0; url=admin_show_kpis.php'>";
    } else {
        echo "<script>alert('Error in update');</script>";
    }
}
?>

<?php
$result = mysqli_query($link, "SELECT * FROM kpis WHERE id='$_GET[id]'");
$row = mysqli_fetch_array($result);
?>

<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Edit KPI</div>
            <div class="panel-body">
                <form name="my_form" role="form" method="post">
                    <fieldset>
                        <div class="form-group">
                            KPI Code <input type="text" class="form-control" name="code" required value="<?php echo $row['code'];?>" />
                        </div>
                        <div class="form-group">
                            Title <input type="text" class="form-control" name="title" required value="<?php echo $row['title'];?>" />
                        </div>
                        <div class="form-group">
                            Description <textarea class="form-control" name="description" rows="3"><?php echo $row['description'];?></textarea>
                        </div>
                        <div class="form-group">
                            Category
                            <select class="form-control" name="category" required>
                                <option value="Program" <?php if($row['category']=='Program') echo 'selected';?>>Program</option>
                                <option value="Course" <?php if($row['category']=='Course') echo 'selected';?>>Course</option>
                                <option value="Institutional" <?php if($row['category']=='Institutional') echo 'selected';?>>Institutional</option>
                                <option value="Research" <?php if($row['category']=='Research') echo 'selected';?>>Research</option>
                                <option value="Community Service" <?php if($row['category']=='Community Service') echo 'selected';?>>Community Service</option>
                                <option value="Administrative" <?php if($row['category']=='Administrative') echo 'selected';?>>Administrative</option>
                            </select>
                        </div>
                        <div class="form-group">
                            Formula Type
                            <select class="form-control" name="formula_type" required>
                                <option value="average" <?php if($row['formula_type']=='average') echo 'selected';?>>Average</option>
                                <option value="percentage" <?php if($row['formula_type']=='percentage') echo 'selected';?>>Percentage</option>
                                <option value="count" <?php if($row['formula_type']=='count') echo 'selected';?>>Count</option>
                            </select>
                        </div>
                        <div class="form-group">
                            Program
                            <select class="form-control" name="program_id" required>
                                <?php while($prow = mysqli_fetch_array($programs_result)) { ?>
                                    <option value="<?php echo $prow['id'];?>" <?php if($row['program_id']==$prow['id']) echo 'selected';?>><?php echo $prow['college_name'] . ' - ' . $prow['dept_name'] . ' - ' . $prow['name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div align="center">
                            <input type="submit" name="btn-submit" class="btn btn-primary" value="Update KPI" />
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php';?>