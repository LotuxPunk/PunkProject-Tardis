<?php
    $GLOBALS['webhook_url'] = "https://discordapp.com/api/webhooks/544426367647744001/lAwMrVcivSTpXTV5W_E5DaYJPhBUK9-w4Cmd3BTvQlhyVq5uhbzZpGwWkbnncLkQcAjQ";

    function sendMail($to, $subject,$message) {
        $headers = 'From: no-reply@punkproject.xyz' . "\r\n" . 'Reply-To: lotuxstyle@gmail.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
        return mail($to,$subject,$message, $headers);
    }

    function sendConfirmMail($to, $code) {
        $subject = "Confirmation of PunkProject registration";
        $message = "Click here to activate your account: https://punkproject.xyz/index.php?code={$code}";
        return sendMail($to,$subject,$message);
    }

    function sendResetMail($to, $code){
        $subject = "Password reset : PunkProject";
        $message = "Click here to reset your password: https://punkproject.xyz/index.php?reset-pass={$code}";
        return sendMail($to,$subject,$message);
    }

    function sendWebhook($title, $content, $username,$id){
        #$image = 'https://via.placeholder.com/400x400';
        $data = json_encode([
            // These 2 should usually be left out
            // as it will overwrite whatever your
            // users have set
            'username' => 'PunkProject',
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
                        'url' => 'https://punkproject.xyz'
                    ],
                    'footer' => [
                        'text' => 'PunkProject by LotuxPunk'
                    ]
                ]
            ]
        ]);
        $ch = curl_init($GLOBALS['webhook_url']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        curl_exec($ch);
        
    }

    function sendWebhookInsc($username){
        $data = json_encode([
            'username' => 'PunkProject',
            'content' => 'New member on PunkProject',
            'embeds' => [
                [
                    'title' => 'Welcome '.$username.'!',
                    'description' => 'A new member has added their suggestions on the PunkProject!',
                    'url' => 'https://punkproject.xyz',
                    'color' => 0xB9FC81,
                    'timestamp' => (new DateTime())->format('c'),
                    'author' => [
                        'name' => $username,
                        'url' => 'https://punkproject.xyz'
                    ],
                    'footer' => [
                        'text' => 'PunkProject by LotuxPunk'
                    ]
                ]
            ]
        ]);
        $ch = curl_init($GLOBALS['webhook_url']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        curl_exec($ch);
    }

    function sendWebhookStatus($status, $id, $title){
        $content = "One request was denied";
        $color = 0x92003B;
        if($status){
            $content = "A request has been implemented";
            $color = 0x63F63B;
        }

        $data = json_encode([
            'username' => 'PunkProject',
            'embeds' => [
                [
                    'title' => $title,
                    'description' => strip_tags($content),
                    'url' => 'https://punkproject.xyz/index.php?p=focus&id='.$id,
                    'color' => $color,
                    'timestamp' => (new DateTime())->format('c'),
                    'footer' => [
                        'text' => 'PunkProject by LotuxPunk',
                    ]
                ]
            ]
        ]);
        $ch = curl_init($GLOBALS['webhook_url']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        curl_exec($ch);
        

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

    function isEmailValid($email){
        $bannedProviders = file("./controller/banned.txt", FILE_IGNORE_NEW_LINES);
        //var_dump($bannedProviders);
        foreach($bannedProviders as $key => $value){
            $pos = strpos($email, $value);
            if($pos !== false){
                return false;
            }
        }
        return true;
    }