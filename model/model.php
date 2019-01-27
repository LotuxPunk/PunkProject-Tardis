<?php
    function dbConnect(){
        $servername = "punkprojhytardis.mysql.db";
        $username = "punkprojhytardis";
        $password = "Tardis2018";
        $dbname = "punkprojhytardis";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {
            return $conn;
        }
    }

    function getPass($email){
        $conn = dbConnect();
        $sql = "SELECT password FROM user WHERE email = '".$email."' AND active LIMIT 1";
        $result = $conn->query($sql);
        $conn->close();

        $row = $result->fetch_assoc();
        $pass = $row['password'];
        return $pass;
    }

    function setInsc($name, $pass, $email, $code, $date_ins){
        $servername = "punkprojhytardis.mysql.db";
        $username = "punkprojhytardis";
        $password = "Tardis2018";
        $dbname = "punkprojhytardis";
        $active = false;

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("INSERT INTO user (email, username, password, code, active, date) VALUES (:email, :username, :password, :code, :active, :date)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':username', $name);
            $stmt->bindParam(':password', $pass);
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':active', $active);
            $stmt->bindParam(':date', $date_ins);
            $stmt->execute();
            $result = 'New account created! Check your email to activate it !';
        }
        catch(PDOException $e)
            {
                $result = "Error: " . $e->getMessage();
            }
        $conn = null;
        return $result;
    }

    function setActif($code){
        $conn = dbConnect();
        $sql = "UPDATE user SET active = 1 WHERE code = '".$code."'";
        if ($conn->query($sql) === TRUE) {
            $result = "Account activated. Congratulations !";            
        } else {
            $result = "Error, it's embarassing.";
        }
        $conn->close();
        return $result;
    }

    function getNom($email){
        $conn = dbConnect();
        $sql = "SELECT username FROM user WHERE email = '".$email."'";
        $result = $conn->query($sql);
        $conn->close();

        $row = $result->fetch_assoc();
        $result = $row['username'];
        return $result;
    }

    function getId($email){
        $conn = dbConnect();
        $sql = "SELECT id FROM user WHERE email = '".$email."'";
        $result = $conn->query($sql);
        $conn->close();

        $row = $result->fetch_assoc();
        $result = $row['id'];
        return $result;
    }

    function setRequest($title, $content, $date){
        $servername = "punkprojhytardis.mysql.db";
        $username = "punkprojhytardis";
        $password = "Tardis2018";
        $dbname = "punkprojhytardis";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("INSERT INTO request (id_user, title, content, date) VALUES (:id_user, :title, :content, :date)");
            $stmt->bindParam(':id_user', $_SESSION['id']);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':date', $date);
            $stmt->execute();
            $result = 'New request added, thank you for your contribution !';
        }
        catch(PDOException $e)
            {
                $result = "Error: " . $e->getMessage();
            }
        $conn = null;
        return $result;
    }

    function getLastRequest($nb = 10, $page = 1, $homePage = false){
        $conn = dbConnect();
        $page = ($page - 1) * $nb;
        $sql = "";
        if ($homePage) {
            $sql = "SELECT * FROM request WHERE rejected = 0 AND done = 0 ORDER BY id DESC LIMIT ".$page.",".$nb;
        }
        else{
            if(isset($_SESSION['showdone']) && $_SESSION['showdone'] || !isset($_SESSION['connected'])){
                $sql = "SELECT * FROM request ORDER BY id DESC LIMIT ".$page.",".$nb;
            }
            else{
                $sql = "SELECT * FROM request WHERE rejected = 0 AND done = 0 ORDER BY id DESC LIMIT ".$page.",".$nb;
            }
            
        }
        
        $result = $conn->query($sql);
        $conn->close();
        return $result;
    }

    function getUsernameByID($id){
        $conn = dbConnect();
        $sql = "SELECT username FROM user WHERE id = ".$id;
        $result = $conn->query($sql);
        $conn->close();

        $row = $result->fetch_assoc();
        $result = $row['username'];
        return $result;
    }

    function addThumb($isUp,$id_request){
        $servername = "punkprojhytardis.mysql.db";
        $username = "punkprojhytardis";
        $password = "Tardis2018";
        $dbname = "punkprojhytardis";

        try {
            if(updateVote($id_request, $isUp)){
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $conn->prepare("INSERT INTO vote (id_request, id_user, is_positive) VALUES (:id_request, :id_user, :is_positive)");
                $stmt->bindParam(':id_request', $id_request);
                $stmt->bindParam(':id_user', $_SESSION['id']);
                $stmt->bindParam(':is_positive', $isUp);
                $stmt->execute();

                if($isUp){
                    $result = 'Request liked';    
                }
                else{
                    $result = 'Request unliked';
                }
            }
            else{
                $result = "Error when updating vote";
            }

            
        }
        catch(PDOException $e)
            {
                $result = "Error: " . $e->getMessage();
            }
        $conn = null;
        return $result;


    }

    function updateVote($id_request, $isUp){
        $conn = dbConnect();
        if($isUp){
            $sql = "UPDATE request SET vote = vote + 1 WHERE id = ".$id_request;
        }
        else{
            $sql = "UPDATE request SET vote = vote - 1 WHERE id = ".$id_request;
        }
        return $conn->query($sql) === TRUE;
    }

    function isVoted($id_user, $id_request){
        $servername = "punkprojhytardis.mysql.db";
        $username = "punkprojhytardis";
        $password = "Tardis2018";
        $dbname = "punkprojhytardis";

        $conn = mysqli_connect($servername, $username, $password, $dbname);

        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        $sql = "SELECT * FROM vote WHERE id_user = ".$id_user." AND id_request = ".$id_request;
        $value = 0;
        if($result = mysqli_query($conn, $sql)){
            $nb_rows = mysqli_num_rows($result);
            if($nb_rows > 0){
                $row = mysqli_fetch_assoc($result);
                if($row['is_positive'] == 1){
                    $value = 1;
                }
                else {
                    $value = 2; 
                }
                               
            }
                
        }
        mysqli_close($conn);
        return $value;        
    }

    function getLevel($id){
        $conn = dbConnect();
        $sql = "SELECT level FROM user WHERE id = ".$id;
        $result = $conn->query($sql);
        $conn->close();

        $row = $result->fetch_assoc();
        $result = $row['level'];
        return $result;        
    }

    function addStatus($isRejected, $id){
        $conn = dbConnect();
        if($isRejected){
            $sql = "UPDATE request SET rejected = 1 WHERE id = ".$id;
        }
        else{
            $sql = "UPDATE request SET done = 1 WHERE id = ".$id;
        }

        if($conn->query($sql) === TRUE){
            $result = "Request updated";
        }
        else{
            $result = "Error when updating request";
        }
        $conn->close();
        return $result;
    }

    function getIDLastRequest(){
        $conn = dbConnect();
        $sql = "SELECT id FROM request ORDER BY id DESC LIMIT 1";
        $result = $conn->query($sql);
        $conn->close();

        $row = $result->fetch_assoc();
        return $row['id'];
    }

    function getRequestByID($id){
        $conn = dbConnect();
        $sql = "SELECT * FROM request WHERE id = ".$id;
        $result = $conn->query($sql);
        $conn->close();

        return $result;
    }

    function getTitleRequestByID($id){
        $conn = dbConnect();
        $sql = "SELECT title FROM request WHERE id = ".$id;
        $result = $conn->query($sql);
        $conn->close();

        $row = $result->fetch_assoc();
        return $row['title'];
    }

    function getNbRequest(){
        $servername = "punkprojhytardis.mysql.db";
        $username = "punkprojhytardis";
        $password = "Tardis2018";
        $dbname = "punkprojhytardis";

        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT COUNT(*) AS NbRequests FROM request";
        $result = $conn->prepare($sql); 
        $result->execute(); 
        $number_of_rows = $result->fetchColumn();

        $conn = null;
        return $number_of_rows;
    }