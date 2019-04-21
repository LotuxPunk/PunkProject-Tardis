<!-- SEO & Title stuff -->
<?php
    require('viewsFunctions.php');
    $title = "Assets submission";
    $desc = "All assets submissions for the NEW TARDIS MOD";
?>

<!-- Menu -->
<?php ob_start(); ?>
<li class="nav-item"><a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a></li>
<li class="nav-item"><a class="nav-link" href="index.php?p=request&n=1">All requests</a></li>
<li class="nav-item"><a class="nav-link" href="index.php?p=memberslist">Members list</a></li>
<li class="nav-item active"><a class="nav-link" href="#">Assets submissions</a></li>
<?php $nav = ob_get_clean(); ?>

<!-- Content -->
<?php ob_start(); ?>
<?php if($message != ""){?><div class="alert alert-info" role="alert"><?= $message ?></div><?php } ?>
<div class="jumbotron">
    <h2 class="display-4">Assets submissions</h2>
    <p class="lead">Here you can submit your artwork and graphic assets to contribute to the "<a href="https://minecraft.curseforge.com/projects/new-tardis-mod" target="_blank">New TARDIS mod</a>".</p>
    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#addSubmission"><i class="fas fa-palette"></i> I have an asset!</button>
</div>
<div class="container">
    <div class="row">
        <div class="col-3"></div>
        <div class="col-6">
            <?php while($row = $dataAssets->fetch_assoc()){ ?>
                <div class="card" style="margin-bottom:10px;">
                    <img class="card-img-top" src="<?php echo "./data/uploads/screenshots/{$row['screenshot']}";?>" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row["title"];?> <small>by <?php echo $row["username"]; ?></small></h5>
                        <?php if(isset($_SESSION['level']) && $_SESSION['level'] >= 5) {?><a href="<?php echo "./data/uploads/assets/{$row['filename']}";?>" class="btn btn-primary"><i class="fas fa-download"></i> Download</a><?php }?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="col-3"></div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<!-- Modal & bottom stuff -->
<?php ob_start(); ?>

<?php $modal = ob_get_clean(); ?>

<?php require('template.php'); ?>