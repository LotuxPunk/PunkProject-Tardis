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
                            $status = getStatusBadge($row['done'], $row['rejected'], $row['id_duplicate']);
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
<div class="modal fade" id="editProfile" tabindex="-1" role="dialog" aria-labelledby="editProfileLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileLabel">Edit profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-info">
                <form enctype="multipart/form-data" action="index.php?p=edit-profile" method="post">
                    <div class="form-group">
                        <label for="pfp">Profile picture</label>
                        <input type="file" class="form-control-file" id="pfp">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php $modal = ob_get_clean(); ?>

<?php require('template.php'); ?>