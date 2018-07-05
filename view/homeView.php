<?php $title = 'Manager'; ?>   

<?php ob_start(); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="http://192.168.1.57">Accueil</a></li>
        <li class="breadcrumb-item active" aria-current="page">Manager</li>
    </ol>
</nav>
<?php $nav = ob_get_clean(); ?>

<?php ob_start(); ?>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>