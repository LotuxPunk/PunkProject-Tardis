<?php
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

    function sendDuplicateHook($title, $username, $id){
        discordHook("Duplicate", "The suggestion ".$title." has been marked as duplicated", $username, "https://punkproject.xyz/index.php?p=focus&id=".$id, 0x47C0BA);
    }

    function discordHook($title, $content, $username, $url, $color){
        #$image = 'https://via.placeholder.com/400x400';
        $data = json_encode([
            'username' => 'PunkProject',
            'embeds' => [
                [
                    'title' => $title,
                    'description' => $content,
                    'url' => $url,
                    'color' => $color,
                    'timestamp' => (new DateTime())->format('c'),
                    'author' => [
                        'name' => $username,
                        'url' => 'https://punkproject.xyz/index.php'
                    ],
                    'footer' => [
                        'text' => 'PunkProject by LotuxPunk'
                    ]
                ]
            ]
        ]);
        $ch = curl_init(getWebhook());
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        curl_exec($ch);
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
                        'url' => 'https://punkproject.xyz/index.php'
                    ],
                    'footer' => [
                        'text' => 'PunkProject by LotuxPunk'
                    ]
                ]
            ]
        ]);
        $ch = curl_init(getWebhook());
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
                    'description' => 'A new member has come to add their suggestions to the PunkProject!',
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
        $ch = curl_init(getWebhook());
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
        $ch = curl_init(getWebhook());
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        curl_exec($ch);
    }

    function checkPass($password){ //TODO : I have to refactor this stupid check
        return strlen($password) > 7;
    }

    function isEmailValid($email){
        $ch = curl_init("https://raw.githubusercontent.com/andreis/disposable-email-domains/master/domains.txt");
        $fp = fopen("./controller/banned.txt", "w+");

        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        $bannedProviders = file("./controller/banned.txt", FILE_IGNORE_NEW_LINES);
        foreach($bannedProviders as $key => $value){
            if(preg_match("/\b({$value}\w*)\b/", $email)) return false;
        }
        return true;
    }

    /**
    * Check $_FILES[][name]
    *
    * @param (string) $filename - Uploaded file name.
    * @author Yousef Ismaeil Cliprz
    */
    function check_file_uploaded_name ($filename) {
        return (bool) preg_match("`^[-0-9A-Z_\.]+$`i",$filename);
    }

    /**
    * Check $_FILES[][name] length.
    *
    * @param (string) $filename - Uploaded file name.
    * @author Yousef Ismaeil Cliprz.
    */
    function check_file_uploaded_length ($filename) {
        return (bool) mb_strlen($filename,"UTF-8") > 225;
    }

    function getWebhook(){
        $json = file_get_contents('./config.json');
        $json_data = json_decode($json,true);
        return $json_data['discord'];
    }