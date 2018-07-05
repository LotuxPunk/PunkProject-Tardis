<?php
    require('./model/model.php');

    function getHome(){
        require('./views/homeView.php');
    }

    function sendMail($to, $code) {
        $from = "contact@lotuxpunk.ovh";
        $subject = "Verification PunkWishes";
        $message = "https://lotuxpunk.ovh/index.php?code=".$code;
        $headers = "From:" . $from;
        mail($to,$subject,$message, $headers);
    }

    function checkConn($password, $email){
        $pass = hash('sha256', $password);
        $checkPass = getPass($email);
        if($pass == $checkPass){
            $_SESSION['connecté'] = true;
            $_SESSION['nom'] = getNom($email);
            $_SESSION['id'] = getId($email);
            getHome();
        }
        else {
            $echec = "Mauvais identifiants ou compte inactif";
            //require('./views/connView.php');
        }
    }

    function checkInsc($name, $password, $email, $birthday) {
        $pass = hash('sha256', $password);
        $code = chaineAleatoire(20);
        $success = setInsc($name, $pass, $email, $birthday, $code);
        sendMail($email, $code);

        //require('./views/connView.php');
    }

    function chaineAleatoire($nb_car) {
        $caracteres = "123ABcDE456fGHiJKLMN789PQRSTUVWXYZ";
        $chaine = "";
        srand(time());
        for ($i=0;$i<=$nb_car;$i++)
            {
            $chaine.=substr($caracteres,(rand()%(strlen($caracteres))),1);
            }
        return $chaine;
    }