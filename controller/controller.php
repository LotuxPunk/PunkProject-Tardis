<?php
    require('./model/model.php');

    function getHomePage($message = ""){
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
        $nb_elem = 100;
        if(isset($_SESSION['nb_elem'])){
            $nb_elem = $_SESSION['nb_elem'];
        }

        $request = getLastRequest($nb_elem, $page);
        $data = getLastRequest();
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

    function addRequest($title, $content){
        $today = date('Y-m-d H:i:s');
        $result = setRequest($title, $content, $today);
        getHomePage($result);
    }

    //Some funtions
    function sendMail($to, $code) {
        $from = "no-reply@punkproject.xyz";
        $subject = "Confirmation of PunkProject registration";
        $message = "Click here to activate your account: https://punkproject.xyz/index.php?code=".$code;
        $headers = "From:" . $from;
        return mail($to,$subject,$message, $headers);
    }

    function checkConn($password, $email){
        $pass = hash('sha256', $password);
        $checkPass = getPass($email);
        if($pass == $checkPass){
            $_SESSION['connected'] = true;
            $_SESSION['username'] = getNom($email);
            $_SESSION['id'] = getId($email);
            $_SESSION['nb_elem'] = 100;
            $_SESSION['level'] = getLevel($_SESSION['id']);
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

    function checkActive($code){
        $activation = setActif($code);
        getLoginPage("","",$activation);
    }

    function chaineAleatoire($nb_car) {
        $caracteres = "123ABcDE456fGHiJKLMN789PQRSTUVWXYZ";
        $chaine = "";
        srand(time());
        for ($i=0;$i<=$nb_car;$i++) {
            $chaine.=substr($caracteres,(rand()%(strlen($caracteres))),1);
        }
        return $chaine;
    }

    function checkPass($password){
        return strlen($password) > 7;
    }

    function sendWebhook($title, $content, $username){
        $url = "https://discordapp.com/api/webhooks/464923140036755456/CuY_mJflj43EfPK6X_dMYx_SztT578ts1NfV10BzwrCbCjLzG7yF9Gy4mwwd2kmpU1vr";
        
    }

    function setVote($isUp, $id){
        $result = addThumb($isUp, $id);
        getRequestView(1,$result);
    }

    function setStatus($isRejected, $id){
        $result = addStatus($isRejected, $id);
        getRequestView(1,$result);
    }