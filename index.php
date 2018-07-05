<?php
    ini_set('display_errors', 'On');
    error_reporting(E_ALL);

    session_start();
    
    require('controller/controller.php');

    if(isset($_GET['p'])){
        //TODO Le reste
    }
    else{
        getHome();
    }