<?php
    require('./model/model.php');

    function getHomePage(){
        require('./views/homeView.php');
    }

    function getLoginPage($echec ="", $success = "", $activation =""){
        require('./views/loginView.php');
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