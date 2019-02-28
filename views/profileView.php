<?php
    require('viewsFunctions.php');
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
                    <h1><?=$row_profile['username']?> <?php echo getRoleBadge($row_profile['level']);?></h1>
                    <p class="lead">Member since : <?= $days ?></p>
                </div>
            </div>
            <div class="bg-white rounded mb-3 request">
                <ul class="list-group list-group-flush">    
                    <?php
                        while($row = $data_requests->fetch_assoc()){
                            $status = getStatusBadge($row['done'], $row['rejected']);
                            echo "<li class='list-group-item'><a href='index.php?p=focus&id={$row['id']}'>{$row['title']}</a> {$status}<div class='float-right'><span class='badge badge-primary'><i class='fas fa-thumbs-up'></i> {$row['vote']}</span></div></li>";
                        }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>
    
<?php $content = ob_get_clean(); ?>

<?php ob_start(); ?>

<?php $modal = ob_get_clean(); ?>

<?php require('template.php'); ?>