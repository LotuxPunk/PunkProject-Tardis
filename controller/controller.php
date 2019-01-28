<?php
    require('./model/model.php');
    require('functions.php');

    function getHomePage($message = ""){
        $_SESSION['page'] = 1;

        $request = getLastRequest(10,1,true);
        $data = getLastRequest(10,1,true);
        $users = array();
        $voted = array();
        while($row = $data->fetch_assoc()){
            $users[] = getUsernameByID($row['id_user']);
            if (isset($_SESSION['connected'])) {
                $voted[] = isVoted($_SESSION['id'], $row['id']);
            }
            else{
                $voted[] = 0;
            }
        }
        require('./views/homeView.php');
    }

    function getRequestView($page = 1, $message = ""){
        $_SESSION['page'] = $page;

        $nb_elem = 10;
        if(isset($_SESSION['nb_elem'])){
            $nb_elem = $_SESSION['nb_elem'];
        }

        $nb_requests = getNbRequest();
        $nb_pages = intdiv($nb_requests, $nb_elem) + 1;

        $request = getLastRequest($nb_elem, $page);
        $data = getLastRequest($nb_elem, $page);
        $users = array();
        $voted = array();
        while($row = $data->fetch_assoc()){
            $users[] = getUsernameByID($row['id_user']);
            if (isset($_SESSION['connected'])) {
                $voted[] = isVoted($_SESSION['id'], $row['id']);
            }
            else{
                $voted[] = 0;
            }
        }
        require('./views/requestView.php');
    }

    function getLoginPage($echec ="", $success = "", $activation =""){
        require('./views/loginView.php');
    }

    function getFocusPage($id){
        $request = getRequestByID($id);
        $row = $request->fetch_assoc();
        $voted = 0;
        $user = getUsernameByID($row['id_user']);

        if (isset($_SESSION['connected'])) {
            $voted = isVoted($_SESSION['id'], $row['id']);
        }

        require('./views/singleView.php');
    }

    #Fonctions se servant des pages

    function addRequest($title, $content){
        $today = date('Y-m-d H:i:s');
        $result = setRequest($title, $content, $today);
        sendWebhook($title, $content, $_SESSION['username'], getIDLastRequest());
        getHomePage($result);
    }

    function checkConn($password, $email){
        $pass = hash('sha256', $password);
        $checkPass = getPass($email);
        if($pass == $checkPass){
            $_SESSION['connected'] = true;
            $_SESSION['username'] = getNom($email);
            $_SESSION['id'] = getId($email);
            $_SESSION['nb_elem'] = 10;
            $_SESSION['level'] = getLevel($_SESSION['id']);
            $_SESSION['page'] = 1;
            $_SESSION['showdone'] = true;
            getHomePage();
        }
        else {
            $echec = "Incorrect credentials or inactive account";
            getLoginPage($echec);
        }
    }

    function checkInsc($username, $password, $password2, $email) {
        $echec ="";
        $success ="";
        if(checkPass($password)){
            if($password == $password2){
                $pass = hash('sha256', $password);
                $code = chaineAleatoire(20);
                $today = date('Y-m-d H:i:s');
                if(sendMail($email, $code)){
                    $success = setInsc($username, $pass, $email, $code, $today);
                    sendWebhookInsc($username);
                }
                else {
                    $echec = "Email error";
                }
            }
            else {
                $echec = "Passwords must be identical";
            }
        }
        else {
            $echec = "Your password must contain at least 8 letters.";
        }    

        getLoginPage($echec, $success);
    }

    function sendMailPassword($email){
        $code = chaineAleatoire(20);
        $echec = "";
        $success = "";      
        if(updateCode($email, $code)){
            if(sendResetMail($email,$code)){
                $success = "Email sent, check your box";
            }
            else{
                $echec = "Error on sending email";
            }            
        }
        else{
            $echec = "Email not found";
        }
        getLoginPage($echec, $success);
    }

    function getResetPage($code){
        require('./views/resetPasswordView.php');
    }

    function checkResetPass($password, $code){
        $passHash = hash('sha256', $password);
        if(updatePassword($passHash, $code)){
            getLoginPage("", "Password successfully changed");
        }
        else{
            getLoginPage("Error when changing the password");
        }
    }

    function checkActive($code){
        $activation = setActif($code);
        getLoginPage("","",$activation);
    } 

    function setVote($isUp, $id){
        $result = addThumb($isUp, $id);
        getRequestView($_SESSION['page'],$result);
    }

    function setStatus($isRejected, $id){
        $result = addStatus($isRejected, $id);
        sendWebhookStatus(!$isRejected, $id, getTitleRequestByID($id));
        getRequestView($_SESSION['page'],$result);
    }