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