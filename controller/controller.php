<?php
    require('./model/model.php');

    #Fonction de pages


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

    function checkActive($code){
        $activation = setActif($code);
        getLoginPage("","",$activation);
    } 

    function setVote($isUp, $id){
        $result = addThumb($isUp, $id);
        getRequestView(1,$result);
    }

    function setStatus($isRejected, $id){
        $result = addStatus($isRejected, $id);
        sendWebhookStatus(!$isRejected, $id, getTitleRequestByID($id));
        getRequestView(1,$result);
    }

    #Fonctions utilitaires

    function sendMail($to, $code) {
        $from = "no-reply@punkproject.xyz";
        $subject = "Confirmation of PunkProject registration";
        $message = "Click here to activate your account: https://punkproject.xyz/index.php?code=".$code;
        $headers = "From:" . $from;
        return mail($to,$subject,$message, $headers);
    }

    function sendWebhook($title, $content, $username,$id){
        $url = "https://discordapp.com/api/webhooks/464923140036755456/CuY_mJflj43EfPK6X_dMYx_SztT578ts1NfV10BzwrCbCjLzG7yF9Gy4mwwd2kmpU1vr";
        #$image = 'https://via.placeholder.com/400x400';
        $data = json_encode([
            // These 2 should usually be left out
            // as it will overwrite whatever your
            // users have set
            'username' => 'PunkProject',
            #'avatar_url' => $image,
            'content' => 'New suggestion added by '.$username.'!',
            'embeds' => [
                [
                    'title' => $title,
                    'description' => $content,
                    'url' => 'https://punkproject.xyz/index.php?p=focus&id='.$id,
                    'color' => 0xFFFFFF,
                    'timestamp' => (new DateTime())->format('c'),
                    'author' => [
                        'name' => $username,
                        'url' => 'https://punkproject.xyz',
                        #'icon_url' => $image
                    ],
                    // 'thumbnail' => [
                    //     'url' => $image
                    // ],
                    'footer' => [
                        'text' => 'PunkProject by LotuxPunk',
                        // 'icon_url' => $image
                    ],
                    // 'image' => [
                    //     'url' => $image
                    // ],
                    // 'fields' => [
                    //     [
                    //         'name' => 'My First Field Name',
                    //         'value' => 'My First Field Value',
                    //         'inline' => true
                    //     ],
                    //     [
                    //         'name' => 'My Second Field Name',
                    //         'value' => 'My Second Field Value',
                    //         'inline' => true
                    //     ]
                    // ]
                ]
            ]
        ]);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        echo curl_exec($ch);
        
    }

    function sendWebhookInsc($username){
        $url = "https://discordapp.com/api/webhooks/464923140036755456/CuY_mJflj43EfPK6X_dMYx_SztT578ts1NfV10BzwrCbCjLzG7yF9Gy4mwwd2kmpU1vr";
        #$image = 'https://via.placeholder.com/400x400';
        $data = json_encode([
            'username' => 'PunkProject',
            'content' => 'New member on PunkProject',
            'embeds' => [
                [
                    'title' => 'Welcome '.$username.'!',
                    'description' => 'A new member has come to add his suggestions on the PunkProject !',
                    'url' => 'https://punkproject.xyz',
                    'color' => 0xB9FC81,
                    'timestamp' => (new DateTime())->format('c'),
                    'author' => [
                        'name' => $username,
                        'url' => 'https://punkproject.xyz',
                        #'icon_url' => $image
                    ],
                    'footer' => [
                        'text' => 'PunkProject by LotuxPunk',
                        // 'icon_url' => $image
                    ]
                ]
            ]
        ]);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        echo curl_exec($ch);
    }

    function sendWebhookStatus($status, $id, $title){
        $content = "One request was denied";
        $color = 0x92003B;
        if($status){
            $content = "A request has been implemented";
            $color = 0x63F63B;
        }

        $url = "https://discordapp.com/api/webhooks/464923140036755456/CuY_mJflj43EfPK6X_dMYx_SztT578ts1NfV10BzwrCbCjLzG7yF9Gy4mwwd2kmpU1vr";
        $data = json_encode([
            'username' => 'PunkProject',
            'embeds' => [
                [
                    'title' => $title,
                    'description' => $content,
                    'url' => 'https://punkproject.xyz/index.php?p=focus&id='.$id,
                    'color' => $color,
                    'timestamp' => (new DateTime())->format('c'),
                    'footer' => [
                        'text' => 'PunkProject by LotuxPunk',
                        // 'icon_url' => $image
                    ]
                ]
            ]
        ]);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        echo curl_exec($ch);
        

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