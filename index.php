<?php
    $timestamp_start = microtime(true);
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    session_start();
    
    require('controller/controller.php');

    if(isset($_SESSION['connected'])){
        checkBan();
    }

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
            elseif($_GET['p'] == 'focus'){
                $id = htmlspecialchars($_GET['id']);
                getFocusPage($id);
            }
            elseif($_GET['p'] == 'showdone'){
                if(isset($_POST["showdone"])){
                    $_SESSION["showdone"] = true;
                }
                else{
                    $_SESSION["showdone"] = false;
                }
                getRequestView($_SESSION["page"]);
            }
            elseif($_GET['p'] == 'edit-request'){
                if(isset($_GET['id'])){
                    $id = htmlspecialchars($_GET['id']);
                    $title = htmlspecialchars($_POST['title'], ENT_QUOTES);
                    $content = htmlspecialchars($_POST['desc_edit'], ENT_QUOTES);
                    editRequest($id, $title,$content);
                }
                else{
                    getHomePage();
                }
            }
            elseif($_GET['p'] == "memberslist"){
                getMemberListPage();
            }
            elseif($_GET['p'] == "duplicate"){
                $id = htmlspecialchars($_GET['id']);
                $id_dup = $_POST['post'];
                handleDuplicate($id, $id_dup);
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
        elseif($_GET['p'] == "check-reset-pass"){
            checkResetPass(htmlspecialchars($_POST['password']), htmlspecialchars($_GET['code']));
        }
        elseif($_GET['p'] == "login"){
            getLoginPage();
        }
        elseif($_GET['p'] == "request"){
            $page = htmlspecialchars($_GET['n']);
            getRequestView($page);
        }
        elseif($_GET['p'] == 'focus'){
            $id = htmlspecialchars($_GET['id']);
            getFocusPage($id);
        }
        elseif($_GET['p'] == 'forgot-password'){
            sendMailPassword(htmlspecialchars($_POST['email']));
        }
        elseif($_GET['p'] == "memberslist"){
            getMemberListPage();
        }
        else{
            getHomePage();
        }
    }
    elseif(isset($_GET['done']) || isset($_GET['rejected'])){
        if(isset($_SESSION['level']) && $_SESSION['level'] >= 5){
            $isRejected = (isset($_GET['done']))? false : true;
            $id = (isset($_GET['done']))? htmlspecialchars($_GET['done']) : htmlspecialchars($_GET['rejected']);
            setStatus($isRejected, $id);
        }
        else{
            getHomePage("You do not have the permission to execute that.");
        }
    }
    elseif(isset($_GET['profile'])){
        getProfilePage(htmlspecialchars($_GET['profile']));
    }
    elseif(isset($_GET['delete'])){
        if(isset($_SESSION['level']) && $_SESSION['level'] >= 5){
            $idToDelete = htmlspecialchars($_GET['delete']);
            handleDeletePost($idToDelete);
        }
        else{
            getHomePage("You do not have the permission to execute that.");
        }
    }
    elseif(isset($_GET['ban'])){
        if(isset($_SESSION['level']) && $_SESSION['level'] >= 5){
            $idToBan = htmlspecialchars($_GET['ban']);
            handleBan($idToBan);
        }
        else{
            getHomePage("You do not have the permission to execute that.");
        }
    }
    elseif(isset($_GET['code'])){
        checkActive(htmlspecialchars($_GET['code']));
    }
    elseif(isset($_GET['reset-pass'])){
        getResetPage(htmlspecialchars($_GET['reset-pass']));
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
    $timestamp_end = microtime(true);
    $difference_ms = ($timestamp_end - $timestamp_start)*1000;
    echo '<center>Page generated in : ' . $difference_ms . ' ms.</center>';