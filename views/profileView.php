<?php
    $title = "{$row_profile['username']} - PunkProject";
    $desc = "Profile of {$row_profile['username']}";
?>

<?php ob_start(); ?>
<li class="nav-item"><a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a></li>
<li class="nav-item"><a class="nav-link" href="index.php?p=request&n=1">All requests</a></li>
<li class="nav-item"><a class="nav-link" href="index.php?p=memberslist">Members list</a></li>
<?php $nav = ob_get_clean(); ?>

<?php ob_start(); ?>
<div class="container">
    <div class="row align-items-start">
        <div class="col-sm-3 position-sticky">
            <img src="./data/p_img/default.png" class="img-thumbnail rounded d-block">
        </div>
        <div class="col-md-9">
            <div class="jumbotron jumbotron-fluid rounded">
                <div class="container">
                    <h1><?=$row_profile['username']?> <?php if($row_profile['level'] == 1) echo '<span class="badge badge-success">Donator</span>'; elseif($row_profile['level'] == 5)echo '<span class="badge badge-secondary">Team</span>';elseif($row_profile['level'] == 9) echo '<span class="badge badge-primary">Owner</span>'; elseif($row_profile['level'] == 10) echo '<span class="badge badge-info">Admin</span>';?></h1>
                    <p class="lead">Member since : <?= $days ?></p>
                </div>
            </div>
            <div class="bg-white rounded mb-3 request">
            <?php
            while($row = $data_requests->fetch_assoc()){
                echo '<div class="border-bottom row"><div class="col-9"><a href="index.php?p=focus&id='.$row['id'].'"><h5>'.$row['title'].'</h5></a><p>'.htmlspecialchars_decode($row['content']).'</p></div></div>';
            } ?>
            </div>
        </div>
    </div>
</div>
    
<?php $content = ob_get_clean(); ?>

<?php ob_start(); ?>

<?php $modal = ob_get_clean(); ?>

<?php require('template.php'); ?>