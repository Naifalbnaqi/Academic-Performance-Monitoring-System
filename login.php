<?php include 'header.php';?>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Login to KPI System</div>
            <div class="panel-body">
                <form name="my_form" role="form" action="login_check.php" method="post">
                    <fieldset>
                        <div class="form-group">
                            <input class="form-control" placeholder="Email" name="email" type="text" required>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Password" name="password" type="password" value="" required>
                        </div>
                        <div align="center">
                            <input type="submit" class="btn btn-primary" value="Login" />
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php';?>