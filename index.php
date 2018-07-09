<?php
    ini_set('display_errors', 'On');
    error_reporting(E_ALL);

    session_start();
    
    require('controller/controller.php');

    if(isset($_GET['p'])){
        if(isset($_SESSION['connected'])){
            if($_GET['p'] == "logout"){
                session_destroy();
                header("Refresh:0");
                getHomePage();
            }
            elseif($_GET['p'] == "add-request"){
                addRequest(htmlspecialchars($_POST['title']), htmlspecialchars($_POST['description']));
            }
            elseif($_GET['p'] == "request"){
                $page = htmlspecialchars($_GET['n']);
                getRequestView($page);
            }
            else{
                getHomePage();
            }
        }
        elseif($_GET['p'] == "check-insc"){
            checkInsc(htmlspecialchars($_POST['username']), htmlspecialchars($_POST['password']), htmlspecialchars($_POST['password2']), htmlspecialchars($_POST['email']));
        }
        elseif($_GET['p'] == "check-login"){
            checkConn(htmlspecialchars($_POST['password']), htmlspecialchars($_POST['email']));
        }
        elseif($_GET['p'] == "login"){
            getLoginPage();
        }
        elseif($_GET['p'] == "request"){
            $page = htmlspecialchars($_GET['n']);
            getRequestView($page);
        }
        else{
            getHomePage();
        }
    }
    elseif(isset($_GET['code'])){
        checkActive(htmlspecialchars($_GET['code']));
    }
    elseif (isset($_GET['up']) || isset($_GET['down'])) {
        if(isset($_SESSION['connected'])){
            $isUp = (isset($_GET['up']))?true : false;
            $id = (isset($_GET['up']))? htmlspecialchars($_GET['up']) : htmlspecialchars($_GET['down']);
            setVote($isUp, $id);
        }
        else{
            getHomePage("You must be logged in to vote");
        }
       
    }
    else{
        getHomePage();
    }