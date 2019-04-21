<?php
    require('viewsFunctions.php');
    $title = "Members - PunkProject";
    $desc = "Members list of PunkProject";
?>

<?php ob_start(); ?>
<li class="nav-item"><a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a></li>
<li class="nav-item"><a class="nav-link" href="index.php?p=request&n=1">All requests</a></li>
<li class="nav-item active"><a class="nav-link" href="index.php?p=memberslist">Members list</a></li>
<li class="nav-item"><a class="nav-link" href="index.php?p=submissions">Assets submissions</a></li>
<?php $nav = ob_get_clean(); ?>

<?php ob_start(); ?>
<div class="jumbotron">
    <h2 class="display-4">Members list</h2>
</div>
<div class="container bg-white rounded mb-3">
    <ul class="list-group list-group-flush">    
    <?php 
        while ($row = $membersData->fetch_assoc()){
            $role = getRoleBadge($row['level']);
            echo "<li class='list-group-item'><a href='index.php?profile={$row['id']}'>{$row['username']}</a> {$role}</li>";
        }
    ?>
    </ul>
</div>
<?php $content = ob_get_clean(); ?>

<?php ob_start(); ?>

<?php $modal = ob_get_clean(); ?>

<?php require('template.php'); ?>