<!-- SEO & Title stuff -->
<?php
    require('viewsFunctions.php');
    $title = ""; // TODO : Set title
    $desc = "";// TODO : Set desc
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
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSubmission"><i class="fas fa-palette"></i> I have an asset!</button>
<?php $content = ob_get_clean(); ?>

<!-- Modal & bottom stuff -->
<?php ob_start(); ?>

<?php $modal = ob_get_clean(); ?>

<?php require('template.php'); ?>