<?php

//==================================================================================================
//    Nom du module : counter.php développé par Frédéric de Marion - frederic.de.marion@free.fr
//--------------------------------------------------------------------------------------------------
//  Version |    Date    | Commentaires
//--------------------------------------------------------------------------------------------------
//    V1.00 | 12/04/2017 | Version originale
//==================================================================================================
// 14/04/2017 : erreur sur l'incrémentation du counter de connexion


$host="localhost"; // Host name
$username=""; // Mysql username
$password=""; // Mysql password
$db_name="test"; // Database name
$tbl_name="members"; // Table name

// Connect to server and select database.
require('sqlconf.php');
$db=mysqli_connect( $sqlserver , $login , $password, $sqlbase ) or die("cannot connect 02");

$sql='SELECT * FROM Admin_counter';
$result=mysqli_query($db, $sql);
$rows=mysqli_fetch_assoc($db, $result);
$counter=$rows['counter'];

// if have no counter value set counter = 1
if(empty($counter)){
	$counter=1;
	$sql1='INSERT INTO Admin_counter(counter) VALUES('.$counter.')';
	$result1=mysqli_query($db, $sql1);
}

mysqli_close($db);
?>