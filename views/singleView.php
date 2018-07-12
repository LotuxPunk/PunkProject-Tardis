<?php $title = 'Focus on request - PunkProject'; ?>

<?php ob_start(); ?>
<li class="nav-item"><a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a></li>
<li class="nav-item"><a class="nav-link" href="index.php?p=request&n=1">All requests</a></li>
<?php $nav = ob_get_clean(); ?>

<?php ob_start(); ?>
<div class="bg-white rounded mb-3 request">
    <?php
        $status = "";
        $vote = "";
        $moderation = "";
        
        if($row['done'] == 1){
            $status = '<span class="badge badge-success">Done !</span>';
        }
        elseif($row['rejected'] == 1){
            $status = '<span class="badge badge-danger">Rejected !</span>';
        }
        else{
            if($voted == 0){
                $vote = '<div class="float-right btn-group" style="margin-top:20px;" role="group"><a class="btn btn-success" role="button" href="index.php?up='.$row['id'].'"><i class="fas fa-thumbs-up"></i></a><span class="btn btn-secondary">'.$row['vote'].'</span><a class="btn btn-danger" href="index.php?down='.$row['id'].'" role="button"><i class="fas fa-thumbs-down"></i></a></div>';
            }
            elseif($voted == 1){
                $vote = '<div class="float-right btn-group" style="margin-top:20px;" role="group"><span class="btn btn-success"><i class="fas fa-thumbs-up"></i></span><span class="btn btn-secondary">'.$row['vote'].'</span><a class="btn btn-danger disabled" href="index.php?down='.$row['id'].'" role="button" aria-disabled="true"><i class="fas fa-thumbs-down"></i></a></div>';
            }
            else{
                $vote = '<div class="float-right btn-group" style="margin-top:20px;" role="group"><a class="btn btn-success disabled" href="index.php?up='.$row['id'].'" role="button" aria-disabled="true"><i class="fas fa-thumbs-up"></i></a><span class="btn btn-secondary">'.$row['vote'].'</span><span class="btn btn-danger"><i class="fas fa-thumbs-down"></i></span></div>';
            }
        }

        if(isset($_SESSION['connected']) && $_SESSION['level'] >= 5){
            $moderation = '<div class="btn-group" style="margin-bottom:20px;" role="group"><a class="btn btn-success" role="button" href="index.php?done='.$row['id'].'">Done !</a><a class="btn btn-danger" href="index.php?rejected='.$row['id'].'" role="button">Reject</a></div>';
        }
    ?>
    <h3><?= $row['title'] ?><small> by <?= $user ?></small><?= $status ?></h3>
    <?php 
        echo '<div class="row"><div class="col-9"><p>'.$row['content'].'</p>'.$moderation.'</div><div class="col-3">'.$vote.'</div></div>';
     ?>
</div>
<?php $content = ob_get_clean(); ?>

<?php ob_start(); ?>

<?php $modal = ob_get_clean(); ?>

<?php require('template.php'); ?>