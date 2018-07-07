<?php $title = 'PunkProject'; ?>

<?php ob_start(); ?>
<li class="nav-item active"><a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a></li>
<li class="nav-item"><a class="nav-link" href="#">Another useless link</a></li>
<?php $nav = ob_get_clean(); ?>

<?php ob_start(); ?>
<?php if($message != ""){?><div class="alert alert-info" role="alert"><?= $message ?></div><?php } ?>
<div class="card bg-light mb-3">
    <div class="card-body">
        <div class="d-flex">
            <img src="./views/img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">&nbsp;<h5 class="card-title">New TARDIS Mod</h5>
        </div>
        <p class="card-text">You have a suggestion, an idea, or a remark to improve the mod ? Go ahead, we're listening !</p>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addRequest">I have an idea !</button>
    </div>
</div>
<div class="bg-white rounded mb-3 request">
    <h3>Latest requests</h3>
    <?php $i = 0; 
    while($row = $request->fetch_assoc()){
        echo '<div class="border-bottom row"><div class="col-9"><h5>'.$row['title'].'<small> by '.$users[$i++].'</small></h5><p>'.$row['content'].'</p></div><div class="col-3"><div class="float-right btn-group" style="margin-top:20px;" role="group"><a class="btn btn-success" href="#"><i class="fas fa-thumbs-up"></i></a><span class="btn btn-secondary">0</span><a class="btn btn-danger" href="#"><i class="fas fa-thumbs-down"></i></a></div></div></div>';
    } ?>
</div>
<?php $content = ob_get_clean(); ?>

<?php ob_start(); ?>

<?php $modal = ob_get_clean(); ?>

<?php require('template.php'); ?>