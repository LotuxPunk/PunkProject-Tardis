<?php $title = 'Login - PunkProject'; ?>

<?php ob_start(); ?>
<li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
<li class="nav-item"><a class="nav-link" href="#">Another useless link</a></li>
<?php $nav = ob_get_clean(); ?>

<?php ob_start(); ?>
<div class="container">
    <div class="row">
        <div class="col-sm">
        <?php
            if($success != ""){
                echo '<div class="alert alert-info" role="alert">'.$success.'</div>';
            }
            elseif($activation != ""){
                echo '<div class="alert alert-success" role="alert">'.$activation.'</div>';
            }
            elseif($echec != ""){
                echo '<div class="alert alert-danger" role="alert">'.$echec.'</div>';
            }
        ?>
        </div>
        <div class="col-sm bg-light login-form">
            <form action="index.php?p=check-login" method="post">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                </div>
                <a href="#" data-toggle="modal" data-target="#register" class="btn btn-secondary"><i class="fas fa-address-book"></i> I'm not register yet</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Login</button>
            </form>
        </div>
        <div class="col-sm"></div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php ob_start(); ?>
<div class="modal fade" id="register" tabindex="-1" role="dialog" aria-labelledby="registerLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-Username" id="registerLabel">Sign in</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="index.php?p=check-insc" method="post">
                    <div class="form-group">
                        <label for="username" class="col-form-label">Username</label>
                        <input type="text" class="form-control" name="username" id="username">
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                        <!-- <small id="passwordHelp" class="form-text text-muted">Your password must contain at least 8 letters and have a capital, lower case and a number.</small> -->
                        <small id="passwordHelp" class="form-text text-muted">Your password must contain at least 8 letters.</small>
                    </div>
                    <div class="form-group">
                        <label for="password2">Confirm your password</label>
                        <input type="password" name="password2" class="form-control" id="password2" placeholder="Confirm your password">
                    </div>
                    <button class="btn btn-primary" type="submit"><i class="fas fa-address-book"></i> Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $modal = ob_get_clean(); ?>

<?php require('template.php'); ?>