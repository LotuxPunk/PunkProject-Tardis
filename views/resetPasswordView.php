<?php 
    $title = 'Password Reset - PunkProject';
    $desc = 'Suggestion handler for the New TARDIS mod';
?>

<?php ob_start(); ?>
<li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
<li class="nav-item"><a class="nav-link" href="index.php?p=request&n=1">All requests</a></li>
<li class="nav-item"><a class="nav-link" href="index.php?p=memberslist">Members list</a></li>
<?php $nav = ob_get_clean(); ?>

<?php ob_start(); ?>
<div class="container">
    <div class="row">
        <div class="col-sm">
        </div>
        <div class="col-sm bg-light login-form">
            <form action="index.php?p=check-reset-pass&code=<?php echo $code; ?>" method="POST">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                    <small id="passwordHelp" class="form-text text-muted">Your password must contain at least 8 letters.</small>
                </div>
                <div class="form-group">
                    <label for="password2">Confirm your password</label>
                    <input type="password" name="password2" class="form-control" id="password2" placeholder="Confirm your password">
                </div>
                <div class="btn-group" role="group" aria-label="">
                    <button type="submit" id="btnConfirm" class="btn btn-primary disabled" disabled><i class="fas fa-check"></i> Confirm</button>
                </div>
            </form>
        </div>
        <div class="col-sm"></div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php ob_start(); ?>
<script src="./views/js/password.js"></script>
<?php $modal = ob_get_clean(); ?>

<?php require('template.php'); ?>