<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'program_head') {
    header("Location: index.php");
}
?>

<?php
$kpi_id = $_GET['kpi_id'];
$academic_year = '2024-2025';

// Get KPI result
$result_query = mysqli_query($link, "SELECT * FROM kpi_results WHERE kpi_id='$kpi_id' AND academic_year='$academic_year'");
$result_row = mysqli_fetch_array($result_query);
$kpi_result_id = $result_row['id'];

$kpi_query = mysqli_query($link, "SELECT * FROM kpis WHERE id='$kpi_id'");
$kpi_row = mysqli_fetch_array($kpi_query);
?>

<?php
if (isset($_POST['btn-submit'])) {
    $issue_description = $_POST['issue_description'];
    $root_cause = $_POST['root_cause'];
    $corrective_action = $_POST['corrective_action'];
    $responsible_person = $_POST['responsible_person'];
    $deadline = $_POST['deadline'];

    $query = "INSERT INTO action_plans (kpi_result_id, issue_description, root_cause, corrective_action, responsible_person, deadline, status) 
              VALUES ('$kpi_result_id', '$issue_description', '$root_cause', '$corrective_action', '$responsible_person', '$deadline', 'Pending')";
    
    if (mysqli_query($link, $query)) {
        echo "<script>alert('Action plan created successfully');</script>";
        echo "<meta http-equiv='Refresh' content='0; url=program_head_show_kpis.php'>";
    } else {
        echo "<script>alert('Error in create action plan');</script>";
    }
}
?>

<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Gap Analysis & Action Plan for: <?php echo $kpi_row['code'];?></div>
            <div class="panel-body">
                <form name="my_form" role="form" method="post">
                    <fieldset>
                        <div class="form-group">
                            Issue Description (Why KPI not achieved?)
                            <textarea class="form-control" name="issue_description" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            Root Cause Analysis
                            <textarea class="form-control" name="root_cause" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            Corrective Action Plan
                            <textarea class="form-control" name="corrective_action" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            Responsible Person <input type="text" class="form-control" name="responsible_person" required />
                        </div>
                        <div class="form-group">
                            Completion Deadline <input type="date" class="form-control" name="deadline" required />
                        </div>
                        <div align="center">
                            <input type="submit" name="btn-submit" class="btn btn-primary" value="Create Action Plan" />
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php';?>