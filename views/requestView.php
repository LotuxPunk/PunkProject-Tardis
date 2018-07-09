<?php $title = 'Login - PunkProject'; ?>

<?php ob_start(); ?>
<li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
<li class="nav-item active"><a class="nav-link" href="index.php?p=request&n=1">All requests</a></li>
<?php $nav = ob_get_clean(); ?>

<?php ob_start(); ?>
<?php if($message != ""){?><div class="alert alert-info" role="alert"><?= $message ?></div><?php } ?>
<div class="bg-white rounded mb-3 request">
    <h3>Page <?= $page ?></h3>
    <?php $i = 0; $vote = "";
    while($row = $request->fetch_assoc()){
        if($voted[$i] == 0){
            $vote = '<div class="float-right btn-group" style="margin-top:20px;" role="group"><a class="btn btn-success" role="button" href="index.php?up='.$row['id'].'"><i class="fas fa-thumbs-up"></i></a><span class="btn btn-secondary">'.$row['vote'].'</span><a class="btn btn-danger" href="index.php?down='.$row['id'].'" role="button"><i class="fas fa-thumbs-down"></i></a></div>';
        }
        elseif($voted[$i] == 1){
            $vote = '<div class="float-right btn-group" style="margin-top:20px;" role="group"><span class="btn btn-success"><i class="fas fa-thumbs-up"></i></span><span class="btn btn-secondary">'.$row['vote'].'</span><a class="btn btn-danger disabled" href="index.php?down='.$row['id'].'" role="button" aria-disabled="true"><i class="fas fa-thumbs-down"></i></a></div>';
        }
        else{
            $vote = '<div class="float-right btn-group" style="margin-top:20px;" role="group"><a class="btn btn-success disabled" href="index.php?up='.$row['id'].'" role="button" aria-disabled="true"><i class="fas fa-thumbs-up"></i></a><span class="btn btn-secondary">'.$row['vote'].'</span><span class="btn btn-danger"><i class="fas fa-thumbs-down"></i></span></div>';
        }
        echo '<div class="border-bottom row"><div class="col-9"><h5>'.$row['title'].'<small> by '.$users[$i++].'</small></h5><p>'.$row['content'].'</p></div><div class="col-3">'.$vote.'</div></div>';
    } ?>
</div>
<?php $content = ob_get_clean(); ?>

<?php ob_start(); ?>

<?php $modal = ob_get_clean(); ?>

<?php require('template.php'); ?>