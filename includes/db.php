<?php

$db['dbServer'] = "localhost";
$db['dbUsername'] = "root";
$db['dbPassword'] = "";
$db['dbName'] = "no_fr";

// looping through each to make them into constants
foreach($db as $key => $value){
  define(strtoupper($key), $value); //it is good practice to upper case it
}

$no_fr_conn = new mysqli(DBSERVER, DBUSERNAME, DBPASSWORD, DBNAME);

//$conn = mysqli_connect(DBSERVER, DBUSERNAME, DBPASSWORD, DBNAME);

if(!$no_fr_conn){
  echo "We are not connected";
}


 ?>
