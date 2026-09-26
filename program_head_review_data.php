<?php include 'header.php';?>

<?php
if ($_SESSION['user_type'] != 'program_head') {
    header("Location: index.php");
}
?>

<?php
$kpi_id = $_GET['kpi_id'];
$program_id = $_SESSION['program_id'];
$academic_year = '2024-2025';

// Get raw data for this KPI
$raw_data_query = "SELECT kpi_raw_data.*, users.name AS faculty_name 
                   FROM kpi_raw_data 
                   LEFT JOIN users ON kpi_raw_data.entered_by = users.id 
                   WHERE kpi_raw_data.kpi_id = '$kpi_id' AND kpi_raw_data.academic_year = '$academic_year'";
$raw_data_result = mysqli_query($link, $raw_data_query);

// Get KPI info
$kpi_result = mysqli_query($link, "SELECT * FROM kpis WHERE id='$kpi_id'");
$kpi_row = mysqli_fetch_array($kpi_result);

// Get target
$target_result = mysqli_query($link, "SELECT * FROM kpi_targets WHERE kpi_id='$kpi_id' AND academic_year='$academic_year'");
$target_row = mysqli_fetch_array($target_result);
$target_value = $target_row['target_value'];

// Collect all values
$values = array();
while($data = mysqli_fetch_array($raw_data_result)) {
    $values[] = $data['value'];
}
mysqli_data_seek($raw_data_result, 0);

// ==========================================
// حساب القيمة الفعلية حسب نوع المعادلة
// ==========================================
if($kpi_row['formula_type'] == 'average') {
    // المتوسط: مجموع القيم / عددها
    if(count($values) > 0) {
        $actual_value = array_sum($values) / count($values);
    } else {
        $actual_value = 0;
    }
    
} elseif($kpi_row['formula_type'] == 'percentage') {
    // النسبة المئوية: (القيمة الأولى / القيمة الثانية) * 100
    if(count($values) >= 2 && $values[1] > 0) {
        $actual_value = ($values[0] / $values[1]) * 100;
    } else {
        $actual_value = 0;
    }
    
} else { // count
    // العدد: مجموع القيم
    $actual_value = array_sum($values);
}

// ==========================================
// حساب نسبة الإنجاز مع تجنب القسمة على صفر
// ==========================================
if ($target_value > 0) {
    $achievement = ($actual_value / $target_value) * 100;
} else {
    $achievement = 0;
}

$variance = $actual_value - $target_value;

// ==========================================
// تحديد الحالة (Status)
// ==========================================
if($target_value > 0 && $actual_value >= $target_value) {
    $status = 'Achieved';
} elseif($target_value > 0 && $achievement >= 95) {
    $status = 'Warning';
} else {
    $status = 'Critical';
}
?>

<?php
// ==========================================
// معالجة قبول البيانات
// ==========================================
if (isset($_POST['btn-approve'])) {
    // Insert or update result
    $check = mysqli_query($link, "SELECT * FROM kpi_results WHERE kpi_id='$kpi_id' AND academic_year='$academic_year'");
    if(mysqli_num_rows($check) > 0) {
        $query = "UPDATE kpi_results SET actual_value='$actual_value', achievement_percentage='$achievement', 
                  variance='$variance', status='$status', approved_by='{$_SESSION['user_id']}' 
                  WHERE kpi_id='$kpi_id' AND academic_year='$academic_year'";
    } else {
        $query = "INSERT INTO kpi_results (kpi_id, academic_year, actual_value, achievement_percentage, variance, status, approved_by) 
                  VALUES ('$kpi_id', '$academic_year', '$actual_value', '$achievement', '$variance', '$status', '{$_SESSION['user_id']}')";
    }
    
    if(mysqli_query($link, $query)) {
        echo "<script>alert('Data approved successfully');</script>";
        echo "<meta http-equiv='Refresh' content='0; url=program_head_show_kpis.php'>";
    } else {
        echo "<script>alert('Error in approval');</script>";
    }
}

// ==========================================
// معالجة رفض البيانات مع ملاحظة
// ==========================================
if (isset($_POST['btn-reject'])) {
    $note = $_POST['rejection_note'];
    echo "<script>alert('Data returned with note: $note');</script>";
    echo "<meta http-equiv='Refresh' content='0; url=program_head_show_kpis.php'>";
}
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Review Data for: <?php echo $kpi_row['code'] . ' - ' . $kpi_row['title'];?></div>
            <div class="panel-body">
                
                <!-- جدول عرض البيانات المدخلة من Faculty -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>Faculty Name</th>
                            <th>Value Entered</th>
                            <th>Entered At</th>
                        </tr>
                        <?php 
                        $has_data = false;
                        while($data = mysqli_fetch_array($raw_data_result)) { 
                            $has_data = true;
                        ?>
                        <tr>
                            <td><?php echo $data['faculty_name'];?></td>
                            <td><?php echo $data['value'];?></td>
                            <td><?php echo $data['entered_at'];?></td>
                        </tr>
                        <?php } 
                        if(!$has_data) { ?>
                        <tr>
                            <td colspan="3" align="center" style="color:red;">No data submitted yet for this KPI</td>
                        </tr>
                        <?php } ?>
                        
                        <!-- عرض النتائج المحسوبة -->
                        <tr style="background-color:#f0f0f0; font-weight:bold;">
                            <td>Calculated Value (<?php echo $kpi_row['formula_type'];?>):</td>
                            <td colspan="2"><?php echo number_format($actual_value, 2);?></td>
                        </tr>
                        <tr style="background-color:#e0e0e0;">
                            <td>Target: <?php echo $target_value ? $target_value : 'Not Set';?></td>
                            <td>Achievement: <?php echo $target_value > 0 ? number_format($achievement,2) . '%' : 'N/A';?></td>
                            <td>Status: <strong><?php echo $target_value > 0 ? $status : 'No Target';?></strong></td>
                        </tr>
                    </table>
                </div>
                
                <!-- أزرار القبول والرفض -->
                <?php if($has_data && $target_value > 0) { ?>
                <form method="post" style="display:inline-block;">
                    <input type="submit" name="btn-approve" class="btn btn-success" value="Approve Data" />
                </form>
                
                <button type="button" class="btn btn-danger" onclick="showRejectForm()">Return with Note</button>
                
                <div id="reject_form" style="display:none; margin-top:20px;">
                    <form method="post">
                        <div class="form-group">
                            <label>Rejection Note:</label>
                            <textarea name="rejection_note" class="form-control" rows="3" required></textarea>
                        </div>
                        <input type="submit" name="btn-reject" class="btn btn-warning" value="Submit Rejection" />
                    </form>
                </div>
                <?php } else if(!$has_data) { ?>
                    <div class="alert alert-warning">
                        <strong>Warning:</strong> No data has been submitted for this KPI yet.
                    </div>
                <?php } else if($target_value == 0 || !$target_value) { ?>
                    <div class="alert alert-danger">
                        <strong>Error:</strong> Target value is not set for this KPI. Please ask Admin to set a target first.
                    </div>
                <?php } ?>
                
            </div>
        </div>
    </div>
</div>

<script>
function showRejectForm() {
    document.getElementById('reject_form').style.display = 'block';
}
</script>

<?php include 'footer.php';?>