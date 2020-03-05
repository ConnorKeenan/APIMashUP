<?php
function ConnectDB() {
	
	$hostname = '';
	$username = '';
	$password = '';
	$dbname = '';
	
	
    /*try {
        $dbh = new PDO("mysql:host=$hostname;dbname=$dbname",
                       $username, $password);
    } catch(PDOException $e) {
        die ('PDO error in "ConnectDB()": ' . $e->getMessage() );
    }
    return $dbh;*/


try {
    $dbh = new PDO("mysql:host=db4free.net;dbname=ckcapstone", $username, $password);
    // set the PDO error mode to exception
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
return $dbh;




}


?>