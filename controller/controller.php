<?php
    require('./model/model.php');
    require('functions.php');

    function getHomePage($message = ""){
        $_SESSION['page'] = 1;

        $request = getLastRequest(10,1,true);
        $data = getLastRequest(10,1,true);
        $voted = array();
        while($row = $data->fetch_assoc()){
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

    function getFocusPage($id, $message = ""){
        $request = getRequestByID($id);
        $postsData = getPostsTitle($id);
        $row = $request->fetch_assoc();
        $voted = 0;

        if (isset($_SESSION['connected'])) {
            $voted = isVoted($_SESSION['id'], $row['id']);
        }

        require('./views/singleView.php');
    }

    function getResetPage($code){
        require('./views/resetPasswordView.php');
    }

    function getMemberListPage(){
        $membersData = getUsersInfo();
        require('./views/membersListView.php');
    }

    #Fonctions se servant des pages

    function addRequest($title, $content){
        if(!checkBan()){
            $today = date('Y-m-d H:i:s');
            $result = setRequest($title, $content, $today);
            sendWebhook($title, strip_tags(htmlspecialchars_decode($content)), $_SESSION['username'], getIDLastRequest());
            getHomePage($result);
        }
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
            if(!checkBan()){
                getHomePage();
            }
        }
        else {
            $echec = "Incorrect credentials or inactive account";
            getLoginPage($echec);
        }
    }

    function checkInsc($username, $password, $password2, $email) {
        $echec ="";
        $success ="";
        if(isEmailValid($email)){
            if($username != ""){
                if(checkPass($password)){
                    if($password == $password2){
                        $pass = hash('sha256', $password);
                        $code = uniqid("", true);
                        $today = date('Y-m-d H:i:s');
                        if(sendConfirmMail($email, $code)){
                            $success = setInsc($username, $pass, $email, $code, $today);
                            sendWebhookInsc($username);
                        }
                        else {
                            $echec = "Error : something wrong with email";
                        }
                    }
                    else {
                        $echec = "Passwords must be identical";
                    }
                }
                else {
                    $echec = "Your password must contain at least 8 letters";
                }
            }
            else {
                $echec = "Your have to provide an username";
            }
        }
        else{
            $echec = "Your email provider isn't approved";
        }

        getLoginPage($echec, $success);
    }

    function sendMailPassword($email){
        $code = uniqid("", true);
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
        $activation = "";
        $error = "";
        if(getActive($code) == 0){
            $activation = setActive($code);
        }
        else{
            $error = "Account already activated";
        }
        
        getLoginPage($error,"",$activation);
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

    function checkBan(){
        if(getLevel($_SESSION['id']) < 0){
            session_destroy();
            getLoginPage("Banned, sorry dude.");
            return true;
        }
        return false;
    }

    function handleBan($id_user){
        $message ="";
        if(banUser($id_user)){
            $message = "User #{$id_user} sucessfully banned.";
        }
        else{
            $message = "Error : ban user #{$id_user}.";
        }
        getHomePage($message);
    }

    function handleDeletePost($id){
        $message ="";
        if(deletePost($id)){
            $message = "Post #{$id} sucessfully deleted.";
        }
        else{
            $message = "Error : delete post #{$id}.";
        }
        getRequestView($_SESSION['page'],$message);
    }

    function getProfilePage($id){
        $data = getUserByID($id);
        $row_profile = $data->fetch_assoc();

        if($row_profile != null){
            $data_requests = getRequestByUserID($row_profile['id']);
            $datetime1 = new DateTime($row_profile['date']);
            $datetime2 = new DateTime('now');
            $interval = $datetime1->diff($datetime2);
            $days = $interval->format('%R%a days');
            require('./views/profileView.php');
        }
        else{
            getHomePage("No profile found");
        }       
    }

    function editRequest($id, $title, $content){
        $request = getRequestByID($id);
        $row = $request->fetch_assoc();
        $message = "";
        if($row['id_user'] ==  $_SESSION['id']){
            $message = updateRequest($id, $title, $content);
        }
        else{
            $message = "You're not allowed to edit this suggestion";
        }

        getFocusPage($id, $message);
    }

    function handleDuplicate($id, $id_dup){
        if(isset($_SESSION['level']) && $_SESSION['level'] >= 5){
            if(addDuplicate($id, $id_dup)){
                $message = "Duplication noted";
                $title = getPostTitle($id);
                sendDuplicateHook($title, $_SESSION['username'], $id);
            }
            else{
                $message = "Error when reporting duplication";
            }
        }
        getFocusPage($id, $message);
    }

    function getSubmissions($message = ""){
        $dataAssets = getAssets();
        require('./views/submissionsView.php');
    }

    function addAsset(){
        $uploads_dir = "./data/uploads";
        $assets_dir = $uploads_dir."/assets";
        $screenshot_dir = $uploads_dir."/screenshots";
        $temp_dir = $uploads_dir."/temp";

        $title = htmlspecialchars($_POST['title']);

        if($_FILES['screenshotFile']['error'] > 0 || $title == ""){
            var_dump($_FILES['screenshotFile']['error'] > 0, $_FILES['assetFile']['error'] > 0, $title == "");
            getSubmissions("Error : something happen while the transfer");
        }
        else{
            $zip = new ZipArchive();
            $name = uniqid("", true);

            //Saving screenshot
            $tmp_name = $_FILES["screenshotFile"]["tmp_name"];
            $name_screenshot = $_FILES["screenshotFile"]["name"];
            $ext = pathinfo($name_screenshot, PATHINFO_EXTENSION);
            move_uploaded_file($tmp_name, "$screenshot_dir/$name.$ext");

            //Saving assets
            $filesToZip = array();
            $total = count($_FILES['assetFile']['name']);

            for( $i=0 ; $i < $total ; $i++ ) {
                $tmp_name = $_FILES["assetFile"]["tmp_name"][$i];
                $name_file = basename($_FILES["assetFile"]["name"][$i]);
                $filesToZip[] = $name_file;
                move_uploaded_file($tmp_name, "$temp_dir/$name_file");
            }

            if ($zip->open("$assets_dir/$name.zip", ZipArchive::CREATE)!==TRUE) {
                exit("File <$name> can't be open.\n");
            }
            else{
                foreach ($filesToZip as $filename) {
                    $zip->addFile("$temp_dir/$filename", $filename);
                }
                $zip->close();
            }

            foreach($filesToZip as $filename){
                unlink("$temp_dir/$filename");
            }

            $message = addAssetDB("$name.$ext","$name.zip", $title);
            sendAssetWebhook($title, $_SESSION['username']);
            getSubmissions($message);
        }
    }

    function handleDeleteAsset($id){
        $message = "";
        if(deleteAsset($id)){
            $message = "Asset deleted";
        }
        else{
            $message = "Error : Asset not deleted";
        }
        getSubmissions($message);
    }