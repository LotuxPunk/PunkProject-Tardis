<?php
    ini_set('display_errors', 'On');
    error_reporting(E_ALL);

    session_start();
    
    require('controller/controller.php');

    if(isset($_GET['p'])){
        if(isset($_SESSION['connected']) && $_GET['p'] != "login"){
            getHomePage();
        }
        elseif($_GET['p'] == "check-insc"){
            checkInsc(htmlspecialchars($_POST['username']), htmlspecialchars($_POST['password']), htmlspecialchars($_POST['password2']), htmlspecialchars($_POST['email']));
        }
        elseif($_GET['p'] == "check-login"){
            checkConn(htmlspecialchars($_POST['password']), htmlspecialchars($_POST['email']));
        }
        else{
            getLoginPage();
        }
    }
    elseif(isset($_GET['code'])){
        checkActive(htmlspecialchars($_GET['code']));
    }
    else{
        getHomePage();
    }