<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'qa_director') {
    header("Location: index.php");
}
?>

<?php
$result_id = $_GET['id'];
$result_query = mysqli_query($link, "SELECT kpi_results.*, kpis.code, kpis.title, programs.name AS program_name 
                                     FROM kpi_results 
                                     LEFT JOIN kpis ON kpi_results.kpi_id = kpis.id
                                     LEFT JOIN programs ON kpis.program_id = programs.id
                                     WHERE kpi_results.id='$result_id'");
$result_row = mysqli_fetch_array($result_query);
?>

<?php
if (isset($_POST['btn-approve'])) {
    $query = "UPDATE kpi_results SET qa_approved='Approved', qa_notes='{$_POST['qa_notes']}' WHERE id='$result_id'";
    mysqli_query($link, $query);
    echo "<script>alert('KPI approved by QA Director');</script>";
    echo "<meta http-equiv='Refresh' content='0; url=qa_director_show_all.php'>";
}

if (isset($_POST['btn-return'])) {
    $query = "UPDATE kpi_results SET qa_approved='Returned', qa_notes='{$_POST['qa_notes']}' WHERE id='$result_id'";
    mysqli_query($link, $query);
    echo "<script>alert('KPI returned to Program Head with notes');</script>";
    echo "<meta http-equiv='Refresh' content='0; url=qa_director_show_all.php'>";
}
?>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">QA Director Review: <?php echo $result_row['code'];?></div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <tr><th>Program</th><td><?php echo $result_row['program_name'];?></td></tr>
                    <tr><th>Title</th><td><?php echo $result_row['title'];?></td></tr>
                    <tr><th>Target</th><td><?php echo $result_row['achievement_percentage'];?>%</td></tr>
                    <tr><th>Status</th><td><?php echo $result_row['status'];?></td></tr>
                </table>
                
                <form method="post">
                    <div class="form-group">
                        <label>QA Notes / Comments</label>
                        <textarea name="qa_notes" class="form-control" rows="3" required></textarea>
                    </div>
                    <input type="submit" name="btn-approve" class="btn btn-success" value="Approve" />
                    <input type="submit" name="btn-return" class="btn btn-danger" value="Return with Notes" />
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php';?>