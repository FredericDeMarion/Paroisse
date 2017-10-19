<?php
session_start();
require('Login/sqlconf.php');
header( 'content-type: text/html; charset=iso-8859-1' );

$eCOM_db = mysqli_connect( $sqlserver , $login , $password, $sqlbase ) or die('Common: Cannot connect MySql : ' . mysqli_error());
mysqli_query($eCOM_db, "SET sql_mode = ''");
mysqli_query($eCOM_db, "SET NAMES 'ISO-8859-1'");
mysqli_query($eCOM_db, 'SET NAMES latin1');


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%BRUSA%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Stéphane%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Stéphane BRUSA existe déjà =========');
      echo '<BR>===== La fiche de Stéphane BRUSA existe déjà id='.$row['id'].' =========';
   }
   $ID_Pere_21=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.37.20.00.32") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.37.20.00.32"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.37.20.00.32" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "stephane.brusa@free.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "stephane.brusa@free.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="stephane.brusa@free.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "96 route d'Antibes 06410 BIOT") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "96 route d\'Antibes 06410 BIOT"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="96 route d\'Antibes 06410 BIOT" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Pere de Antoine BRUSA") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Pere de Antoine BRUSA"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Pere de Antoine BRUSA" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("BRUSA", "Stéphane", "M", 0, "06.37.20.00.32", "stephane.brusa@free.fr", "96 route d\'Antibes 06410 BIOT", "", "", "", "Pere de Antoine BRUSA")') or die(mysqli_error($eCOM_db));
   $ID_Pere_21 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Stéphane BRUSA  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_21.')');
   echo '<BR>Individu : Stéphane BRUSA  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_21.')';
}






// -----------------
// Ligne No 4
// -----------------
Error_Log('Traitement ligne No 4 ===========================================');
echo '<BR>Traitement ligne No 4 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%BRUSA%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Karine%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Karine BRUSA existe déjà =========');
      echo '<BR>===== La fiche de Karine BRUSA existe déjà id='.$row['id'].' =========';
   }
   $ID_Mere_21=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.65.28.10.91") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.65.28.10.91"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.65.28.10.91" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "stephane.brusa@free.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "stephane.brusa@free.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="stephane.brusa@free.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "96 route d'Antibes 06410 BIOT") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "96 route d\'Antibes 06410 BIOT"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="96 route d\'Antibes 06410 BIOT" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Mere de Antoine BRUSA") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Mere de Antoine BRUSA"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Mere de Antoine BRUSA" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("BRUSA", "Karine", "F", 0, "06.65.28.10.91", "stephane.brusa@free.fr", "96 route d\'Antibes 06410 BIOT", "", "", "", "Mere de Antoine BRUSA")') or die(mysqli_error($eCOM_db));
   $ID_Mere_21 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Karine BRUSA  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_21.')');
   echo '<BR>Individu : Karine BRUSA  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_21.')';
}






// -----------------
// Ligne No 5
// -----------------
Error_Log('Traitement ligne No 5 ===========================================');
echo '<BR>Traitement ligne No 5 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%BRUSA%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Antoine%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Antoine BRUSA existe déjà =========');
      echo '<BR>===== La fiche de Antoine BRUSA existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "07.82.81.79.49") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "07.82.81.79.49"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 07.82.81.79.49" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "96 route d'Antibes 06410 BIOT") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "96 route d\'Antibes 06410 BIOT"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="96 route d\'Antibes 06410 BIOT" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_21){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_21.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_21.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_21){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_21.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_21.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2003-11-07") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2003-11-07"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2003-11-07" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Aime Chanter; Instrument : Galoubet") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Aime Chanter; Instrument : Galoubet"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Aime Chanter; Instrument : Galoubet" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("BRUSA", "Antoine", "M", 0, "07.82.81.79.49", "", "96 route d\'Antibes 06410 BIOT", '.$ID_Pere_21.', '.$ID_Mere_21.', "2003-11-07", "Aime Chanter; Instrument : Galoubet")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Antoine BRUSA  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Antoine BRUSA  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Collège l\'Eganaude"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Collège l\'Eganaude existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Collège l\'Eganaude existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Collège l\'Eganaude")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Antoine BRUSA  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Antoine BRUSA  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Antoine BRUSA excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Antoine BRUSA excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 70){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 70';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=70 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "3ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "3ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="3ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 70, "3ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Antoine BRUSA  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Antoine BRUSA  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 6
// -----------------
Error_Log('Traitement ligne No 6 ===========================================');
echo '<BR>Traitement ligne No 6 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%JACQUIN%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Pierre%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Pierre JACQUIN existe déjà =========');
      echo '<BR>===== La fiche de Pierre JACQUIN existe déjà id='.$row['id'].' =========';
   }
   $ID_Pere_20=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.98.08.50.04") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.98.08.50.04"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.98.08.50.04" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "pierrotjacquin@gmail.com") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "pierrotjacquin@gmail.com"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="pierrotjacquin@gmail.com" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "143 rte de St Mathieu, villa 14 06130 GRASSE") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "143 rte de St Mathieu, villa 14 06130 GRASSE"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="143 rte de St Mathieu, villa 14 06130 GRASSE" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Pere de Sam Jacquin") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Pere de Sam Jacquin"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Pere de Sam Jacquin" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("JACQUIN", "Pierre", "M", 0, "06.98.08.50.04", "pierrotjacquin@gmail.com", "143 rte de St Mathieu, villa 14 06130 GRASSE", "", "", "", "Pere de Sam Jacquin")') or die(mysqli_error($eCOM_db));
   $ID_Pere_20 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Pierre JACQUIN  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_20.')');
   echo '<BR>Individu : Pierre JACQUIN  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_20.')';
}






// -----------------
// Ligne No 7
// -----------------
Error_Log('Traitement ligne No 7 ===========================================');
echo '<BR>Traitement ligne No 7 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%JACQUIN%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Kristina%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Kristina JACQUIN existe déjà =========');
      echo '<BR>===== La fiche de Kristina JACQUIN existe déjà id='.$row['id'].' =========';
   }
   $ID_Mere_20=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.63.53.02.04") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.63.53.02.04"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.63.53.02.04" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "kristinajacquin@gmail.com") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "kristinajacquin@gmail.com"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="kristinajacquin@gmail.com" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "143 rte de St Mathieu, villa 14 06130 GRASSE") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "143 rte de St Mathieu, villa 14 06130 GRASSE"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="143 rte de St Mathieu, villa 14 06130 GRASSE" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Mere de Sam Jacquin") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Mere de Sam Jacquin"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Mere de Sam Jacquin" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("JACQUIN", "Kristina", "F", 0, "06.63.53.02.04", "kristinajacquin@gmail.com", "143 rte de St Mathieu, villa 14 06130 GRASSE", "", "", "", "Mere de Sam Jacquin")') or die(mysqli_error($eCOM_db));
   $ID_Mere_20 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Kristina JACQUIN  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_20.')');
   echo '<BR>Individu : Kristina JACQUIN  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_20.')';
}






// -----------------
// Ligne No 8
// -----------------
Error_Log('Traitement ligne No 8 ===========================================');
echo '<BR>Traitement ligne No 8 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%JACQUIN%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Sam%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Sam JACQUIN existe déjà =========');
      echo '<BR>===== La fiche de Sam JACQUIN existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "07.69.99.98.94") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "07.69.99.98.94"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 07.69.99.98.94" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "143 rte de St Mathieu, villa 14 06130 GRASSE") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "143 rte de St Mathieu, villa 14 06130 GRASSE"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="143 rte de St Mathieu, villa 14 06130 GRASSE" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_20){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_20.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_20.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_20){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_20.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_20.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2006-12-22") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2006-12-22"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2006-12-22" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Aime Animer; Aime Chanter; Instrument : oui, guitare") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Aime Animer; Aime Chanter; Instrument : oui, guitare"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Aime Animer; Aime Chanter; Instrument : oui, guitare" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("JACQUIN", "Sam", "M", 0, "07.69.99.98.94", "", "143 rte de St Mathieu, villa 14 06130 GRASSE", '.$ID_Pere_20.', '.$ID_Mere_20.', "2006-12-22", "Aime Animer; Aime Chanter; Instrument : oui, guitare")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Sam JACQUIN  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Sam JACQUIN  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Centre International de Valbonne"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Centre International de Valbonne existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Centre International de Valbonne existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Centre International de Valbonne")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Sam JACQUIN  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Sam JACQUIN  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Sam JACQUIN excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Sam JACQUIN excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 60){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 60';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=60 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "6ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "6ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="6ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 60, "6ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Sam JACQUIN  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Sam JACQUIN  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 9
// -----------------
Error_Log('Traitement ligne No 9 ===========================================');
echo '<BR>Traitement ligne No 9 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%JACQUIN%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Lena%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Lena JACQUIN existe déjà =========');
      echo '<BR>===== La fiche de Lena JACQUIN existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "07.68.69.38.47") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "07.68.69.38.47"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 07.68.69.38.47" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "143 rte de St Mathieu, villa 14 06130 GRASSE") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "143 rte de St Mathieu, villa 14 06130 GRASSE"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="143 rte de St Mathieu, villa 14 06130 GRASSE" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_20){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_20.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_20.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_20){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_20.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_20.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2004-12-30") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2004-12-30"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2004-12-30" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Aime Animer; Aime Chanter;") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Aime Animer; Aime Chanter;"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Aime Animer; Aime Chanter;" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("JACQUIN", "Lena", "F", 0, "07.68.69.38.47", "", "143 rte de St Mathieu, villa 14 06130 GRASSE", '.$ID_Pere_20.', '.$ID_Mere_20.', "2004-12-30", "Aime Animer; Aime Chanter;")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Lena JACQUIN  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Lena JACQUIN  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Centre International de Valbonne"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Centre International de Valbonne existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Centre International de Valbonne existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Centre International de Valbonne")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Lena JACQUIN  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Lena JACQUIN  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Lena JACQUIN excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Lena JACQUIN excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 70){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 70';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=70 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "5ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "5ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="5ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 70, "5ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Lena JACQUIN  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Lena JACQUIN  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 10
// -----------------
Error_Log('Traitement ligne No 10 ===========================================');
echo '<BR>Traitement ligne No 10 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%MAJERI%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Omar%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Omar MAJERI existe déjà =========');
      echo '<BR>===== La fiche de Omar MAJERI existe déjà id='.$row['id'].' =========';
   }
   $ID_Pere_18=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Pere de adam majeri") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Pere de adam majeri"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Pere de adam majeri" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("MAJERI", "Omar", "M", 0, "", "", "", "", "", "", "Pere de adam majeri")') or die(mysqli_error($eCOM_db));
   $ID_Pere_18 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Omar MAJERI  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_18.')');
   echo '<BR>Individu : Omar MAJERI  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_18.')';
}






// -----------------
// Ligne No 11
// -----------------
Error_Log('Traitement ligne No 11 ===========================================');
echo '<BR>Traitement ligne No 11 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%PONS%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Stephanie%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Stephanie PONS existe déjà =========');
      echo '<BR>===== La fiche de Stephanie PONS existe déjà id='.$row['id'].' =========';
   }
   $ID_Mere_18=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "07.88.36.90.52") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "07.88.36.90.52"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 07.88.36.90.52" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "pons-stephanie@hotmail.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "pons-stephanie@hotmail.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="pons-stephanie@hotmail.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "11 rue de la fontaine 06620 Le Bar-sur-Loup") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "11 rue de la fontaine 06620 Le Bar-sur-Loup"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="11 rue de la fontaine 06620 Le Bar-sur-Loup" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Mere de adam majeri") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Mere de adam majeri"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Mere de adam majeri" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("PONS", "Stephanie", "F", 0, "07.88.36.90.52", "pons-stephanie@hotmail.fr", "11 rue de la fontaine 06620 Le Bar-sur-Loup", "", "", "", "Mere de adam majeri")') or die(mysqli_error($eCOM_db));
   $ID_Mere_18 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Stephanie PONS  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_18.')');
   echo '<BR>Individu : Stephanie PONS  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_18.')';
}






// -----------------
// Ligne No 12
// -----------------
Error_Log('Traitement ligne No 12 ===========================================');
echo '<BR>Traitement ligne No 12 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%MAJERI%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Adam%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Adam MAJERI existe déjà =========');
      echo '<BR>===== La fiche de Adam MAJERI existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "07.88.36.90.52") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "07.88.36.90.52"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 07.88.36.90.52" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "11 rue de la fontaine 06620 Le Bar-sur-Loup") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "11 rue de la fontaine 06620 Le Bar-sur-Loup"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="11 rue de la fontaine 06620 Le Bar-sur-Loup" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_18){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_18.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_18.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_18){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_18.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_18.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2005-07-04") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2005-07-04"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2005-07-04" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Aime Animer; Aime Chanter;") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Aime Animer; Aime Chanter;"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Aime Animer; Aime Chanter;" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("MAJERI", "Adam", "M", 0, "07.88.36.90.52", "", "11 rue de la fontaine 06620 Le Bar-sur-Loup", '.$ID_Pere_18.', '.$ID_Mere_18.', "2005-07-04", "Aime Animer; Aime Chanter;")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Adam MAJERI  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Adam MAJERI  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Collège César (Roquefort-Les-Pins)"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Collège César (Roquefort-Les-Pins) existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Collège César (Roquefort-Les-Pins) existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Collège César (Roquefort-Les-Pins)")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Adam MAJERI  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Adam MAJERI  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Adam MAJERI excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Adam MAJERI excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 70){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 70';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=70 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "5ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "5ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="5ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 70, "5ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Adam MAJERI  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Adam MAJERI  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 13
// -----------------
Error_Log('Traitement ligne No 13 ===========================================');
echo '<BR>Traitement ligne No 13 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%VESTRI%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Christophe%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Christophe VESTRI existe déjà =========');
      echo '<BR>===== La fiche de Christophe VESTRI existe déjà id='.$row['id'].' =========';
   }
   $ID_Pere_17=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.21.13.81.28") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.21.13.81.28"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.21.13.81.28" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "vestri@gmail.com") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "vestri@gmail.com"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="vestri@gmail.com" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "355 chemin de la basse ferme 06330 Roquefort les Pins") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "355 chemin de la basse ferme 06330 Roquefort les Pins"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="355 chemin de la basse ferme 06330 Roquefort les Pins" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Pere de Gabriel vestri") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Pere de Gabriel vestri"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Pere de Gabriel vestri" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("VESTRI", "Christophe", "M", 0, "06.21.13.81.28", "vestri@gmail.com", "355 chemin de la basse ferme 06330 Roquefort les Pins", "", "", "", "Pere de Gabriel vestri")') or die(mysqli_error($eCOM_db));
   $ID_Pere_17 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Christophe VESTRI  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_17.')');
   echo '<BR>Individu : Christophe VESTRI  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_17.')';
}






// -----------------
// Ligne No 14
// -----------------
Error_Log('Traitement ligne No 14 ===========================================');
echo '<BR>Traitement ligne No 14 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%VESTRI%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Virginie%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Virginie VESTRI existe déjà =========');
      echo '<BR>===== La fiche de Virginie VESTRI existe déjà id='.$row['id'].' =========';
   }
   $ID_Mere_17=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.64.65.91.99") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.64.65.91.99"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.64.65.91.99" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "vvestri@free.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "vvestri@free.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="vvestri@free.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "355 chemin de la basse ferme 06330 Roquefort les Pins") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "355 chemin de la basse ferme 06330 Roquefort les Pins"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="355 chemin de la basse ferme 06330 Roquefort les Pins" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Mere de Gabriel vestri") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Mere de Gabriel vestri"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Mere de Gabriel vestri" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("VESTRI", "Virginie", "F", 0, "06.64.65.91.99", "vvestri@free.fr", "355 chemin de la basse ferme 06330 Roquefort les Pins", "", "", "", "Mere de Gabriel vestri")') or die(mysqli_error($eCOM_db));
   $ID_Mere_17 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Virginie VESTRI  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_17.')');
   echo '<BR>Individu : Virginie VESTRI  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_17.')';
}






// -----------------
// Ligne No 15
// -----------------
Error_Log('Traitement ligne No 15 ===========================================');
echo '<BR>Traitement ligne No 15 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%VESTRI%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Gabriel%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Gabriel VESTRI existe déjà =========');
      echo '<BR>===== La fiche de Gabriel VESTRI existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.64.65.91.99") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.64.65.91.99"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.64.65.91.99" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "355 chemin de la basse ferme 06330 Roquefort les Pins") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "355 chemin de la basse ferme 06330 Roquefort les Pins"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="355 chemin de la basse ferme 06330 Roquefort les Pins" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_17){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_17.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_17.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_17){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_17.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_17.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2006-05-22") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2006-05-22"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2006-05-22" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("VESTRI", "Gabriel", "M", 0, "06.64.65.91.99", "", "355 chemin de la basse ferme 06330 Roquefort les Pins", '.$ID_Pere_17.', '.$ID_Mere_17.', "2006-05-22", "")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Gabriel VESTRI  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Gabriel VESTRI  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Collège César (Roquefort-Les-Pins)"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Collège César (Roquefort-Les-Pins) existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Collège César (Roquefort-Les-Pins) existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Collège César (Roquefort-Les-Pins)")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Gabriel VESTRI  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Gabriel VESTRI  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Gabriel VESTRI excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Gabriel VESTRI excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 60){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 60';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=60 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "6ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "6ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="6ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 60, "6ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Gabriel VESTRI  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Gabriel VESTRI  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 16
// -----------------
Error_Log('Traitement ligne No 16 ===========================================');
echo '<BR>Traitement ligne No 16 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%VESTRI%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Romain%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Romain VESTRI existe déjà =========');
      echo '<BR>===== La fiche de Romain VESTRI existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "07.81.18.03.33") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "07.81.18.03.33"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 07.81.18.03.33" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "355 chemin de la basse ferme 06330 Roquefort les Pins") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "355 chemin de la basse ferme 06330 Roquefort les Pins"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="355 chemin de la basse ferme 06330 Roquefort les Pins" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_17){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_17.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_17.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_17){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_17.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_17.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2002-12-18") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2002-12-18"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2002-12-18" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Instrument : batterie") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Instrument : batterie"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Instrument : batterie" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("VESTRI", "Romain", "M", 0, "07.81.18.03.33", "", "355 chemin de la basse ferme 06330 Roquefort les Pins", '.$ID_Pere_17.', '.$ID_Mere_17.', "2002-12-18", "Instrument : batterie")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Romain VESTRI  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Romain VESTRI  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Lycée Simone Veil (valbonne)"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Lycée Simone Veil (valbonne) existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Lycée Simone Veil (valbonne) existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Lycée Simone Veil (valbonne)")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Romain VESTRI  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Romain VESTRI  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Romain VESTRI excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Romain VESTRI excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 70){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 70';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=70 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "2nd") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "2nd"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="2nd" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 70, "2nd", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Romain VESTRI  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Romain VESTRI  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 17
// -----------------
Error_Log('Traitement ligne No 17 ===========================================');
echo '<BR>Traitement ligne No 17 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%CHEM LENHOF%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Visith%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Visith CHEM LENHOF existe déjà =========');
      echo '<BR>===== La fiche de Visith CHEM LENHOF existe déjà id='.$row['id'].' =========';
   }
   $ID_Pere_15=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.89.08.62.98") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.89.08.62.98"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.89.08.62.98" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "visith.chem@yahoo.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "visith.chem@yahoo.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="visith.chem@yahoo.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "1700 ch Peyniblou 06560 Valbonne") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "1700 ch Peyniblou 06560 Valbonne"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="1700 ch Peyniblou 06560 Valbonne" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Pere de Juliette CHEM LENHOF") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Pere de Juliette CHEM LENHOF"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Pere de Juliette CHEM LENHOF" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("CHEM LENHOF", "Visith", "M", 0, "06.89.08.62.98", "visith.chem@yahoo.fr", "1700 ch Peyniblou 06560 Valbonne", "", "", "", "Pere de Juliette CHEM LENHOF")') or die(mysqli_error($eCOM_db));
   $ID_Pere_15 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Visith CHEM LENHOF  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_15.')');
   echo '<BR>Individu : Visith CHEM LENHOF  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_15.')';
}






// -----------------
// Ligne No 18
// -----------------
Error_Log('Traitement ligne No 18 ===========================================');
echo '<BR>Traitement ligne No 18 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%CHEM LENHOF%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Anne%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Anne CHEM LENHOF existe déjà =========');
      echo '<BR>===== La fiche de Anne CHEM LENHOF existe déjà id='.$row['id'].' =========';
   }
   $ID_Mere_15=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.15.34.57.37") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.15.34.57.37"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.15.34.57.37" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "chemanne@gmail.com") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "chemanne@gmail.com"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="chemanne@gmail.com" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "1700 ch Peyniblou 06560 Valbonne") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "1700 ch Peyniblou 06560 Valbonne"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="1700 ch Peyniblou 06560 Valbonne" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Mere de Juliette CHEM LENHOF") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Mere de Juliette CHEM LENHOF"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Mere de Juliette CHEM LENHOF" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("CHEM LENHOF", "Anne", "F", 0, "06.15.34.57.37", "chemanne@gmail.com", "1700 ch Peyniblou 06560 Valbonne", "", "", "", "Mere de Juliette CHEM LENHOF")') or die(mysqli_error($eCOM_db));
   $ID_Mere_15 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Anne CHEM LENHOF  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_15.')');
   echo '<BR>Individu : Anne CHEM LENHOF  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_15.')';
}






// -----------------
// Ligne No 19
// -----------------
Error_Log('Traitement ligne No 19 ===========================================');
echo '<BR>Traitement ligne No 19 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%CHEM LENHOF%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Juliette%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Juliette CHEM LENHOF existe déjà =========');
      echo '<BR>===== La fiche de Juliette CHEM LENHOF existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.15.34.57.37") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.15.34.57.37"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.15.34.57.37" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "1700 ch Peyniblou 06560 Valbonne") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "1700 ch Peyniblou 06560 Valbonne"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="1700 ch Peyniblou 06560 Valbonne" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_15){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_15.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_15.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_15){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_15.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_15.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2006-09-06") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2006-09-06"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2006-09-06" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Aime Animer; Aime Chanter; Instrument : flute") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Aime Animer; Aime Chanter; Instrument : flute"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Aime Animer; Aime Chanter; Instrument : flute" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("CHEM LENHOF", "Juliette", "F", 0, "06.15.34.57.37", "", "1700 ch Peyniblou 06560 Valbonne", '.$ID_Pere_15.', '.$ID_Mere_15.', "2006-09-06", "Aime Animer; Aime Chanter; Instrument : flute")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Juliette CHEM LENHOF  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Juliette CHEM LENHOF  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Centre International de Valbonne"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Centre International de Valbonne existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Centre International de Valbonne existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Centre International de Valbonne")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Juliette CHEM LENHOF  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Juliette CHEM LENHOF  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Juliette CHEM LENHOF excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Juliette CHEM LENHOF excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 50){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 50';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=50 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "6ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "6ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="6ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 50, "6ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Juliette CHEM LENHOF  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Juliette CHEM LENHOF  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 20
// -----------------
Error_Log('Traitement ligne No 20 ===========================================');
echo '<BR>Traitement ligne No 20 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%CHEM LENHOF%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Benoit%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Benoit CHEM LENHOF existe déjà =========');
      echo '<BR>===== La fiche de Benoit CHEM LENHOF existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.15.34.57.37") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.15.34.57.37"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.15.34.57.37" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "1700 ch Peyniblou 06560 Valbonne") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "1700 ch Peyniblou 06560 Valbonne"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="1700 ch Peyniblou 06560 Valbonne" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_15){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_15.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_15.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_15){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_15.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_15.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2003-02-19") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2003-02-19"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2003-02-19" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Aime Animer; Aime Chanter;") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Aime Animer; Aime Chanter;"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Aime Animer; Aime Chanter;" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("CHEM LENHOF", "Benoit", "M", 0, "06.15.34.57.37", "", "1700 ch Peyniblou 06560 Valbonne", '.$ID_Pere_15.', '.$ID_Mere_15.', "2003-02-19", "Aime Animer; Aime Chanter;")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Benoit CHEM LENHOF  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Benoit CHEM LENHOF  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Centre International de Valbonne"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Centre International de Valbonne existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Centre International de Valbonne existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Centre International de Valbonne")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Benoit CHEM LENHOF  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Benoit CHEM LENHOF  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Benoit CHEM LENHOF excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Benoit CHEM LENHOF excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 60){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 60';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=60 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "3ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "3ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="3ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 60, "3ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Benoit CHEM LENHOF  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Benoit CHEM LENHOF  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 21
// -----------------
Error_Log('Traitement ligne No 21 ===========================================');
echo '<BR>Traitement ligne No 21 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%CHEM%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Valentine%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Valentine CHEM existe déjà =========');
      echo '<BR>===== La fiche de Valentine CHEM existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.15.34.57.37") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.15.34.57.37"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.15.34.57.37" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "1700 ch Peyniblou 06560 Valbonne") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "1700 ch Peyniblou 06560 Valbonne"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="1700 ch Peyniblou 06560 Valbonne" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_15){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_15.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_15.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_15){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_15.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_15.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2001-08-22") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2001-08-22"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2001-08-22" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Aime Animer; Aime Chanter; Instrument : clarinette") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Aime Animer; Aime Chanter; Instrument : clarinette"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Aime Animer; Aime Chanter; Instrument : clarinette" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("CHEM", "Valentine", "F", 0, "06.15.34.57.37", "", "1700 ch Peyniblou 06560 Valbonne", '.$ID_Pere_15.', '.$ID_Mere_15.', "2001-08-22", "Aime Animer; Aime Chanter; Instrument : clarinette")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Valentine CHEM  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Valentine CHEM  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Centre International de Valbonne"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Centre International de Valbonne existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Centre International de Valbonne existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Centre International de Valbonne")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Valentine CHEM  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Valentine CHEM  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Valentine CHEM excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Valentine CHEM excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 70){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 70';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=70 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "1ère") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "1ère"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="1ère" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 70, "1ère", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Valentine CHEM  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Valentine CHEM  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 22
// -----------------
Error_Log('Traitement ligne No 22 ===========================================');
echo '<BR>Traitement ligne No 22 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%LE SINQ%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Ludovic%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Ludovic LE SINQ existe déjà =========');
      echo '<BR>===== La fiche de Ludovic LE SINQ existe déjà id='.$row['id'].' =========';
   }
   $ID_Pere_12=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "04.92.28.07.81") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "04.92.28.07.81"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 04.92.28.07.81" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "ludovic.lesinq@wanadoo.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "ludovic.lesinq@wanadoo.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="ludovic.lesinq@wanadoo.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "17 avenue Pythagore 06560 VALBONNE") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "17 avenue Pythagore 06560 VALBONNE"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="17 avenue Pythagore 06560 VALBONNE" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Pere de Nora LE SINQ") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Pere de Nora LE SINQ"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Pere de Nora LE SINQ" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("LE SINQ", "Ludovic", "M", 0, "04.92.28.07.81", "ludovic.lesinq@wanadoo.fr", "17 avenue Pythagore 06560 VALBONNE", "", "", "", "Pere de Nora LE SINQ")') or die(mysqli_error($eCOM_db));
   $ID_Pere_12 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Ludovic LE SINQ  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_12.')');
   echo '<BR>Individu : Ludovic LE SINQ  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_12.')';
}






// -----------------
// Ligne No 23
// -----------------
Error_Log('Traitement ligne No 23 ===========================================');
echo '<BR>Traitement ligne No 23 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%DELION - LE SINQ%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Cécile%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Cécile DELION - LE SINQ existe déjà =========');
      echo '<BR>===== La fiche de Cécile DELION - LE SINQ existe déjà id='.$row['id'].' =========';
   }
   $ID_Mere_12=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "04.92.28.07.81") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "04.92.28.07.81"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 04.92.28.07.81" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "cecile.dls@orange.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "cecile.dls@orange.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="cecile.dls@orange.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "17 avenue Pythagore 06560 VALBONNE") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "17 avenue Pythagore 06560 VALBONNE"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="17 avenue Pythagore 06560 VALBONNE" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Mere de Nora LE SINQ") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Mere de Nora LE SINQ"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Mere de Nora LE SINQ" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("DELION - LE SINQ", "Cécile", "F", 0, "04.92.28.07.81", "cecile.dls@orange.fr", "17 avenue Pythagore 06560 VALBONNE", "", "", "", "Mere de Nora LE SINQ")') or die(mysqli_error($eCOM_db));
   $ID_Mere_12 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Cécile DELION - LE SINQ  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_12.')');
   echo '<BR>Individu : Cécile DELION - LE SINQ  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_12.')';
}






// -----------------
// Ligne No 24
// -----------------
Error_Log('Traitement ligne No 24 ===========================================');
echo '<BR>Traitement ligne No 24 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%LE SINQ%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Nora%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Nora LE SINQ existe déjà =========');
      echo '<BR>===== La fiche de Nora LE SINQ existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "04.92.28.07.81") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "04.92.28.07.81"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 04.92.28.07.81" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "nora.lesinq@orange.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "nora.lesinq@orange.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="nora.lesinq@orange.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "17 avenue Pythagore 06560 VALBONNE") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "17 avenue Pythagore 06560 VALBONNE"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="17 avenue Pythagore 06560 VALBONNE" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_12){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_12.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_12.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_12){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_12.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_12.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2006-05-22") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2006-05-22"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2006-05-22" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Instrument : Violon Alto - Nora souhaite être en groupe avec son amie Eléa Rioual") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Instrument : Violon Alto - Nora souhaite être en groupe avec son amie Eléa Rioual"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Instrument : Violon Alto - Nora souhaite être en groupe avec son amie Eléa Rioual" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("LE SINQ", "Nora", "F", 0, "04.92.28.07.81", "nora.lesinq@orange.fr", "17 avenue Pythagore 06560 VALBONNE", '.$ID_Pere_12.', '.$ID_Mere_12.', "2006-05-22", "Instrument : Violon Alto - Nora souhaite être en groupe avec son amie Eléa Rioual")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Nora LE SINQ  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Nora LE SINQ  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Collège l\'Eganaude"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Collège l\'Eganaude existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Collège l\'Eganaude existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Collège l\'Eganaude")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Nora LE SINQ  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Nora LE SINQ  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Nora LE SINQ excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Nora LE SINQ excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 60){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 60';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=60 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "6ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "6ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="6ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 60, "6ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Nora LE SINQ  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Nora LE SINQ  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 25
// -----------------
Error_Log('Traitement ligne No 25 ===========================================');
echo '<BR>Traitement ligne No 25 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%LE SINQ%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Solène%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Solène LE SINQ existe déjà =========');
      echo '<BR>===== La fiche de Solène LE SINQ existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.31.54.94.75") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.31.54.94.75"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.31.54.94.75" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "solene.lesinq@orange.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "solene.lesinq@orange.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="solene.lesinq@orange.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "17 avenue Pythagore 06560 VALBONNE") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "17 avenue Pythagore 06560 VALBONNE"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="17 avenue Pythagore 06560 VALBONNE" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_12){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_12.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_12.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_12){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_12.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_12.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2003-07-02") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2003-07-02"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2003-07-02" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Instrument : Flute Traversière - Solène souhaite être en groupe de Frat avec Charlotte de Rivoire et Mathilde Munera") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Instrument : Flute Traversière - Solène souhaite être en groupe de Frat avec Charlotte de Rivoire et Mathilde Munera"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Instrument : Flute Traversière - Solène souhaite être en groupe de Frat avec Charlotte de Rivoire et Mathilde Munera" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("LE SINQ", "Solène", "F", 0, "06.31.54.94.75", "solene.lesinq@orange.fr", "17 avenue Pythagore 06560 VALBONNE", '.$ID_Pere_12.', '.$ID_Mere_12.', "2003-07-02", "Instrument : Flute Traversière - Solène souhaite être en groupe de Frat avec Charlotte de Rivoire et Mathilde Munera")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Solène LE SINQ  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Solène LE SINQ  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Lycée Simone Veil (valbonne)"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Lycée Simone Veil (valbonne) existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Lycée Simone Veil (valbonne) existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Lycée Simone Veil (valbonne)")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Solène LE SINQ  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Solène LE SINQ  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Solène LE SINQ excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Solène LE SINQ excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 70){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 70';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=70 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "2nd") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "2nd"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="2nd" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 70, "2nd", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Solène LE SINQ  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Solène LE SINQ  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 26
// -----------------
Error_Log('Traitement ligne No 26 ===========================================');
echo '<BR>Traitement ligne No 26 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%SCHALLER%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Roland%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Roland SCHALLER existe déjà =========');
      echo '<BR>===== La fiche de Roland SCHALLER existe déjà id='.$row['id'].' =========';
   }
   $ID_Pere_10=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.26.34.40.26") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.26.34.40.26"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.26.34.40.26" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "roland_schaller@yahoo.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "roland_schaller@yahoo.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="roland_schaller@yahoo.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "401 chemin du Val Martin 06560 VALBONNE") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "401 chemin du Val Martin 06560 VALBONNE"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="401 chemin du Val Martin 06560 VALBONNE" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Pere de Victor SCHALLER") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Pere de Victor SCHALLER"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Pere de Victor SCHALLER" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("SCHALLER", "Roland", "M", 0, "06.26.34.40.26", "roland_schaller@yahoo.fr", "401 chemin du Val Martin 06560 VALBONNE", "", "", "", "Pere de Victor SCHALLER")') or die(mysqli_error($eCOM_db));
   $ID_Pere_10 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Roland SCHALLER  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_10.')');
   echo '<BR>Individu : Roland SCHALLER  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_10.')';
}






// -----------------
// Ligne No 27
// -----------------
Error_Log('Traitement ligne No 27 ===========================================');
echo '<BR>Traitement ligne No 27 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%SCHALLER%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Christelle%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Christelle SCHALLER existe déjà =========');
      echo '<BR>===== La fiche de Christelle SCHALLER existe déjà id='.$row['id'].' =========';
   }
   $ID_Mere_10=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.76.02.32.81") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.76.02.32.81"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.76.02.32.81" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "christelle.schaller@free.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "christelle.schaller@free.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="christelle.schaller@free.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "401 chemin du Val Martin 06560 VALBONNE") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "401 chemin du Val Martin 06560 VALBONNE"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="401 chemin du Val Martin 06560 VALBONNE" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Mere de Victor SCHALLER") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Mere de Victor SCHALLER"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Mere de Victor SCHALLER" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("SCHALLER", "Christelle", "F", 0, "06.76.02.32.81", "christelle.schaller@free.fr", "401 chemin du Val Martin 06560 VALBONNE", "", "", "", "Mere de Victor SCHALLER")') or die(mysqli_error($eCOM_db));
   $ID_Mere_10 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Christelle SCHALLER  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_10.')');
   echo '<BR>Individu : Christelle SCHALLER  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_10.')';
}






// -----------------
// Ligne No 28
// -----------------
Error_Log('Traitement ligne No 28 ===========================================');
echo '<BR>Traitement ligne No 28 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%SCHALLER%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Victor%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Victor SCHALLER existe déjà =========');
      echo '<BR>===== La fiche de Victor SCHALLER existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.76.02.32.81") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.76.02.32.81"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.76.02.32.81" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "christelle.schaller@free.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "christelle.schaller@free.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="christelle.schaller@free.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "401 chemin du Val Martin 06560 VALBONNE") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "401 chemin du Val Martin 06560 VALBONNE"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="401 chemin du Val Martin 06560 VALBONNE" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_10){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_10.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_10.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_10){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_10.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_10.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2006-07-28") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2006-07-28"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2006-07-28" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("SCHALLER", "Victor", "M", 0, "06.76.02.32.81", "christelle.schaller@free.fr", "401 chemin du Val Martin 06560 VALBONNE", '.$ID_Pere_10.', '.$ID_Mere_10.', "2006-07-28", "")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Victor SCHALLER  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Victor SCHALLER  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Centre International de Valbonne"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Centre International de Valbonne existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Centre International de Valbonne existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Centre International de Valbonne")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Victor SCHALLER  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Victor SCHALLER  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Victor SCHALLER excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Victor SCHALLER excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 60){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 60';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=60 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "6ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "6ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="6ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 60, "6ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Victor SCHALLER  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Victor SCHALLER  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 29
// -----------------
Error_Log('Traitement ligne No 29 ===========================================');
echo '<BR>Traitement ligne No 29 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%SCHALLER%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Thomas%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Thomas SCHALLER existe déjà =========');
      echo '<BR>===== La fiche de Thomas SCHALLER existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.52.52.37.41") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.52.52.37.41"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.52.52.37.41" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "tschaller11@yahoo.com") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "tschaller11@yahoo.com"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="tschaller11@yahoo.com" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "401 chemin du Val Martin 06560 VALBONNE") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "401 chemin du Val Martin 06560 VALBONNE"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="401 chemin du Val Martin 06560 VALBONNE" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_10){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_10.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_10.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_10){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_10.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_10.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2003-04-09") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2003-04-09"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2003-04-09" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("SCHALLER", "Thomas", "M", 0, "06.52.52.37.41", "tschaller11@yahoo.com", "401 chemin du Val Martin 06560 VALBONNE", '.$ID_Pere_10.', '.$ID_Mere_10.', "2003-04-09", "")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Thomas SCHALLER  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Thomas SCHALLER  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Centre International de Valbonne"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Centre International de Valbonne existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Centre International de Valbonne existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Centre International de Valbonne")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Thomas SCHALLER  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Thomas SCHALLER  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Thomas SCHALLER excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Thomas SCHALLER excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 70){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 70';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=70 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "3ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "3ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="3ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 70, "3ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Thomas SCHALLER  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Thomas SCHALLER  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 30
// -----------------
Error_Log('Traitement ligne No 30 ===========================================');
echo '<BR>Traitement ligne No 30 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%PECOURT%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Jean%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Jean PECOURT existe déjà =========');
      echo '<BR>===== La fiche de Jean PECOURT existe déjà id='.$row['id'].' =========';
   }
   $ID_Pere_8=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.60.25.77.37") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.60.25.77.37"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.60.25.77.37" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "j.pecourt@free.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "j.pecourt@free.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="j.pecourt@free.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "1260 chemin des Cabrières 06250 Mougins") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "1260 chemin des Cabrières 06250 Mougins"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="1260 chemin des Cabrières 06250 Mougins" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Pere de ANTOINE PECOURT") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Pere de ANTOINE PECOURT"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Pere de ANTOINE PECOURT" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("PECOURT", "Jean", "M", 0, "06.60.25.77.37", "j.pecourt@free.fr", "1260 chemin des Cabrières 06250 Mougins", "", "", "", "Pere de ANTOINE PECOURT")') or die(mysqli_error($eCOM_db));
   $ID_Pere_8 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Jean PECOURT  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_8.')');
   echo '<BR>Individu : Jean PECOURT  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_8.')';
}






// -----------------
// Ligne No 31
// -----------------
Error_Log('Traitement ligne No 31 ===========================================');
echo '<BR>Traitement ligne No 31 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%LE GALLO%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Barbara%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Barbara LE GALLO existe déjà =========');
      echo '<BR>===== La fiche de Barbara LE GALLO existe déjà id='.$row['id'].' =========';
   }
   $ID_Mere_8=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.80.96.15.11") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.80.96.15.11"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.80.96.15.11" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "barbara.le.gallo@gmail.com") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "barbara.le.gallo@gmail.com"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="barbara.le.gallo@gmail.com" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "200 chemin des Clausonnes 06560 Valbonne") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "200 chemin des Clausonnes 06560 Valbonne"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="200 chemin des Clausonnes 06560 Valbonne" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Mere de ANTOINE PECOURT") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Mere de ANTOINE PECOURT"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Mere de ANTOINE PECOURT" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("LE GALLO", "Barbara", "F", 0, "06.80.96.15.11", "barbara.le.gallo@gmail.com", "200 chemin des Clausonnes 06560 Valbonne", "", "", "", "Mere de ANTOINE PECOURT")') or die(mysqli_error($eCOM_db));
   $ID_Mere_8 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Barbara LE GALLO  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_8.')');
   echo '<BR>Individu : Barbara LE GALLO  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_8.')';
}






// -----------------
// Ligne No 32
// -----------------
Error_Log('Traitement ligne No 32 ===========================================');
echo '<BR>Traitement ligne No 32 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%PECOURT%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Antoine%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Antoine PECOURT existe déjà =========');
      echo '<BR>===== La fiche de Antoine PECOURT existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.98.06.25.09") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.98.06.25.09"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.98.06.25.09" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "antoine.pecourt@gmail.com") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "antoine.pecourt@gmail.com"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="antoine.pecourt@gmail.com" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "200 chemin des Clausonnes 06560 Valbonne") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "200 chemin des Clausonnes 06560 Valbonne"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="200 chemin des Clausonnes 06560 Valbonne" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_8){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_8.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_8.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_8){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_8.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_8.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2003-09-14") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2003-09-14"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2003-09-14" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Aime Chanter; Instrument : guitare") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Aime Chanter; Instrument : guitare"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Aime Chanter; Instrument : guitare" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("PECOURT", "Antoine", "M", 0, "06.98.06.25.09", "antoine.pecourt@gmail.com", "200 chemin des Clausonnes 06560 Valbonne", '.$ID_Pere_8.', '.$ID_Mere_8.', "2003-09-14", "Aime Chanter; Instrument : guitare")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Antoine PECOURT  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Antoine PECOURT  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Centre International de Valbonne"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Centre International de Valbonne existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Centre International de Valbonne existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Centre International de Valbonne")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Antoine PECOURT  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Antoine PECOURT  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Antoine PECOURT excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Antoine PECOURT excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 70){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 70';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=70 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "3ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "3ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="3ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 70, "3ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Antoine PECOURT  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Antoine PECOURT  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 33
// -----------------
Error_Log('Traitement ligne No 33 ===========================================');
echo '<BR>Traitement ligne No 33 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%LATERRA%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Ugo%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Ugo LATERRA existe déjà =========');
      echo '<BR>===== La fiche de Ugo LATERRA existe déjà id='.$row['id'].' =========';
   }
   $ID_Pere_7=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.88.47.62.49") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.88.47.62.49"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.88.47.62.49" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "ulaterra@gmail.com") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "ulaterra@gmail.com"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="ulaterra@gmail.com" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "750 Chemin des Cabots 06410 Biot") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "750 Chemin des Cabots 06410 Biot"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="750 Chemin des Cabots 06410 Biot" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Pere de Elisa Laterra") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Pere de Elisa Laterra"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Pere de Elisa Laterra" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("LATERRA", "Ugo", "M", 0, "06.88.47.62.49", "ulaterra@gmail.com", "750 Chemin des Cabots 06410 Biot", "", "", "", "Pere de Elisa Laterra")') or die(mysqli_error($eCOM_db));
   $ID_Pere_7 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Ugo LATERRA  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_7.')');
   echo '<BR>Individu : Ugo LATERRA  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_7.')';
}






// -----------------
// Ligne No 34
// -----------------
Error_Log('Traitement ligne No 34 ===========================================');
echo '<BR>Traitement ligne No 34 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%BACIC LATERRA%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Durdica%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Durdica BACIC LATERRA existe déjà =========');
      echo '<BR>===== La fiche de Durdica BACIC LATERRA existe déjà id='.$row['id'].' =========';
   }
   $ID_Mere_7=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.24.61.51.06") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.24.61.51.06"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.24.61.51.06" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "zuza1904@hotmail.com") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "zuza1904@hotmail.com"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="zuza1904@hotmail.com" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "750 Chemin des Cabots 06410 Biot") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "750 Chemin des Cabots 06410 Biot"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="750 Chemin des Cabots 06410 Biot" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Mere de Elisa Laterra") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Mere de Elisa Laterra"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Mere de Elisa Laterra" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("BACIC LATERRA", "Durdica", "F", 0, "06.24.61.51.06", "zuza1904@hotmail.com", "750 Chemin des Cabots 06410 Biot", "", "", "", "Mere de Elisa Laterra")') or die(mysqli_error($eCOM_db));
   $ID_Mere_7 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Durdica BACIC LATERRA  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_7.')');
   echo '<BR>Individu : Durdica BACIC LATERRA  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_7.')';
}






// -----------------
// Ligne No 35
// -----------------
Error_Log('Traitement ligne No 35 ===========================================');
echo '<BR>Traitement ligne No 35 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%LATERRA%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Elisa%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Elisa LATERRA existe déjà =========');
      echo '<BR>===== La fiche de Elisa LATERRA existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "07.83.38.13.08") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "07.83.38.13.08"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 07.83.38.13.08" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "laterraelisa18@gmail.com") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "laterraelisa18@gmail.com"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="laterraelisa18@gmail.com" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "750 Chemin des Cabots 06410 Biot") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "750 Chemin des Cabots 06410 Biot"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="750 Chemin des Cabots 06410 Biot" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_7){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_7.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_7.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_7){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_7.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_7.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2004-03-18") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2004-03-18"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2004-03-18" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Aime Animer; Aime Chanter; Instrument : base de piano") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Aime Animer; Aime Chanter; Instrument : base de piano"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Aime Animer; Aime Chanter; Instrument : base de piano" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("LATERRA", "Elisa", "F", 0, "07.83.38.13.08", "laterraelisa18@gmail.com", "750 Chemin des Cabots 06410 Biot", '.$ID_Pere_7.', '.$ID_Mere_7.', "2004-03-18", "Aime Animer; Aime Chanter; Instrument : base de piano")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Elisa LATERRA  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Elisa LATERRA  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Collège l\'Eganaude"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Collège l\'Eganaude existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Collège l\'Eganaude existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Collège l\'Eganaude")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Elisa LATERRA  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Elisa LATERRA  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Elisa LATERRA excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Elisa LATERRA excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 60){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 60';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=60 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "4ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "4ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="4ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 60, "4ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Elisa LATERRA  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Elisa LATERRA  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 36
// -----------------
Error_Log('Traitement ligne No 36 ===========================================');
echo '<BR>Traitement ligne No 36 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%LATERRA%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Lara%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Lara LATERRA existe déjà =========');
      echo '<BR>===== La fiche de Lara LATERRA existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.52.79.91.02") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.52.79.91.02"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.52.79.91.02" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "laterra.lara@laposte.net") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "laterra.lara@laposte.net"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="laterra.lara@laposte.net" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "750 Chemin des Cabots 06410 Biot") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "750 Chemin des Cabots 06410 Biot"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="750 Chemin des Cabots 06410 Biot" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_7){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_7.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_7.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_7){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_7.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_7.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2001-03-09") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2001-03-09"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2001-03-09" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Aime Animer; Aime Chanter; Instrument : flute traversiere") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Aime Animer; Aime Chanter; Instrument : flute traversiere"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Aime Animer; Aime Chanter; Instrument : flute traversiere" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("LATERRA", "Lara", "F", 0, "06.52.79.91.02", "laterra.lara@laposte.net", "750 Chemin des Cabots 06410 Biot", '.$ID_Pere_7.', '.$ID_Mere_7.', "2001-03-09", "Aime Animer; Aime Chanter; Instrument : flute traversiere")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Lara LATERRA  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Lara LATERRA  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="CIV Valbonne SI Italienne"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école CIV Valbonne SI Italienne existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école CIV Valbonne SI Italienne existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("CIV Valbonne SI Italienne")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Lara LATERRA  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Lara LATERRA  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Lara LATERRA excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Lara LATERRA excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 70){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 70';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=70 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "1ère") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "1ère"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="1ère" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 70, "1ère", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Lara LATERRA  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Lara LATERRA  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 37
// -----------------
Error_Log('Traitement ligne No 37 ===========================================');
echo '<BR>Traitement ligne No 37 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%THOUVENIN%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Philippe%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Philippe THOUVENIN existe déjà =========');
      echo '<BR>===== La fiche de Philippe THOUVENIN existe déjà id='.$row['id'].' =========';
   }
   $ID_Pere_5=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.73.63.16.16") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.73.63.16.16"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.73.63.16.16" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "philippe.thouvenin@orange.com") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "philippe.thouvenin@orange.com"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="philippe.thouvenin@orange.com" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "110 chemin du Bruguet 06560 Valbonne") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "110 chemin du Bruguet 06560 Valbonne"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="110 chemin du Bruguet 06560 Valbonne" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Pere de Magali Thouvenin") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Pere de Magali Thouvenin"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Pere de Magali Thouvenin" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("THOUVENIN", "Philippe", "M", 0, "06.73.63.16.16", "philippe.thouvenin@orange.com", "110 chemin du Bruguet 06560 Valbonne", "", "", "", "Pere de Magali Thouvenin")') or die(mysqli_error($eCOM_db));
   $ID_Pere_5 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Philippe THOUVENIN  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_5.')');
   echo '<BR>Individu : Philippe THOUVENIN  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_5.')';
}






// -----------------
// Ligne No 38
// -----------------
Error_Log('Traitement ligne No 38 ===========================================');
echo '<BR>Traitement ligne No 38 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%THOUVENIN%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Béatrice%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Béatrice THOUVENIN existe déjà =========');
      echo '<BR>===== La fiche de Béatrice THOUVENIN existe déjà id='.$row['id'].' =========';
   }
   $ID_Mere_5=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.60.06.24.14") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.60.06.24.14"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.60.06.24.14" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "beatrice.thouvenin@laposte.net") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "beatrice.thouvenin@laposte.net"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="beatrice.thouvenin@laposte.net" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "110 chemin du Bruguet 06560 Valbonne") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "110 chemin du Bruguet 06560 Valbonne"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="110 chemin du Bruguet 06560 Valbonne" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Mere de Magali Thouvenin") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Mere de Magali Thouvenin"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Mere de Magali Thouvenin" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("THOUVENIN", "Béatrice", "F", 0, "06.60.06.24.14", "beatrice.thouvenin@laposte.net", "110 chemin du Bruguet 06560 Valbonne", "", "", "", "Mere de Magali Thouvenin")') or die(mysqli_error($eCOM_db));
   $ID_Mere_5 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Béatrice THOUVENIN  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_5.')');
   echo '<BR>Individu : Béatrice THOUVENIN  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_5.')';
}






// -----------------
// Ligne No 39
// -----------------
Error_Log('Traitement ligne No 39 ===========================================');
echo '<BR>Traitement ligne No 39 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%THOUVENIN%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Magali%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Magali THOUVENIN existe déjà =========');
      echo '<BR>===== La fiche de Magali THOUVENIN existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.82.22.41.33") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.82.22.41.33"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.82.22.41.33" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "magali-thouvenin@laposte.net") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "magali-thouvenin@laposte.net"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="magali-thouvenin@laposte.net" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "110 chemin du Bruguet 06560 Valbonne") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "110 chemin du Bruguet 06560 Valbonne"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="110 chemin du Bruguet 06560 Valbonne" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_5){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_5.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_5.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_5){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_5.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_5.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2001-11-20") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2001-11-20"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2001-11-20" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("THOUVENIN", "Magali", "F", 0, "06.82.22.41.33", "magali-thouvenin@laposte.net", "110 chemin du Bruguet 06560 Valbonne", '.$ID_Pere_5.', '.$ID_Mere_5.', "2001-11-20", "")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Magali THOUVENIN  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Magali THOUVENIN  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Lycée Fénelon (Grasse)"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Lycée Fénelon (Grasse) existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Lycée Fénelon (Grasse) existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Lycée Fénelon (Grasse)")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Magali THOUVENIN  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Magali THOUVENIN  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Magali THOUVENIN excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Magali THOUVENIN excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 70){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 70';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=70 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "1ère") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "1ère"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="1ère" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 70, "1ère", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Magali THOUVENIN  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Magali THOUVENIN  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 40
// -----------------
Error_Log('Traitement ligne No 40 ===========================================');
echo '<BR>Traitement ligne No 40 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%VOGEL%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Michael%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Michael VOGEL existe déjà =========');
      echo '<BR>===== La fiche de Michael VOGEL existe déjà id='.$row['id'].' =========';
   }
   $ID_Pere_4=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.32.36.59.94") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.32.36.59.94"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.32.36.59.94" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "Domaine Pythagore, 5 placette du levant, 06560 VALBONNE") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "Domaine Pythagore, 5 placette du levant, 06560 VALBONNE"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="Domaine Pythagore, 5 placette du levant, 06560 VALBONNE" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Pere de Christine VOGEL") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Pere de Christine VOGEL"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Pere de Christine VOGEL" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("VOGEL", "Michael", "M", 0, "06.32.36.59.94", "", "Domaine Pythagore, 5 placette du levant, 06560 VALBONNE", "", "", "", "Pere de Christine VOGEL")') or die(mysqli_error($eCOM_db));
   $ID_Pere_4 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Michael VOGEL  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_4.')');
   echo '<BR>Individu : Michael VOGEL  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_4.')';
}






// -----------------
// Ligne No 41
// -----------------
Error_Log('Traitement ligne No 41 ===========================================');
echo '<BR>Traitement ligne No 41 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%VOGEL%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Laetitia%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Laetitia VOGEL existe déjà =========');
      echo '<BR>===== La fiche de Laetitia VOGEL existe déjà id='.$row['id'].' =========';
   }
   $ID_Mere_4=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.69.40.86.96") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.69.40.86.96"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.69.40.86.96" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "les.vogel06@gmail.com") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "les.vogel06@gmail.com"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="les.vogel06@gmail.com" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "Domaine Pythagore, 5 placette du levant, 06560 VALBONNE") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "Domaine Pythagore, 5 placette du levant, 06560 VALBONNE"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="Domaine Pythagore, 5 placette du levant, 06560 VALBONNE" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Mere de Christine VOGEL") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Mere de Christine VOGEL"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Mere de Christine VOGEL" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("VOGEL", "Laetitia", "F", 0, "06.69.40.86.96", "les.vogel06@gmail.com", "Domaine Pythagore, 5 placette du levant, 06560 VALBONNE", "", "", "", "Mere de Christine VOGEL")') or die(mysqli_error($eCOM_db));
   $ID_Mere_4 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Laetitia VOGEL  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_4.')');
   echo '<BR>Individu : Laetitia VOGEL  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_4.')';
}






// -----------------
// Ligne No 42
// -----------------
Error_Log('Traitement ligne No 42 ===========================================');
echo '<BR>Traitement ligne No 42 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%VOGEL%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Christine%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Christine VOGEL existe déjà =========');
      echo '<BR>===== La fiche de Christine VOGEL existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.19.23.27.08.") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.19.23.27.08."';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.19.23.27.08." WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "christine.s.vogel@gmail.com") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "christine.s.vogel@gmail.com"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="christine.s.vogel@gmail.com" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "Domaine Pythagore, 5 placette du levant, 06560 VALBONNE") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "Domaine Pythagore, 5 placette du levant, 06560 VALBONNE"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="Domaine Pythagore, 5 placette du levant, 06560 VALBONNE" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_4){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_4.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_4.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_4){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_4.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_4.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2005-03-16") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2005-03-16"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2005-03-16" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Aime Animer; Aime Chanter; Instrument : clavecin") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Aime Animer; Aime Chanter; Instrument : clavecin"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Aime Animer; Aime Chanter; Instrument : clavecin" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("VOGEL", "Christine", "F", 0, "06.19.23.27.08.", "christine.s.vogel@gmail.com", "Domaine Pythagore, 5 placette du levant, 06560 VALBONNE", '.$ID_Pere_4.', '.$ID_Mere_4.', "2005-03-16", "Aime Animer; Aime Chanter; Instrument : clavecin")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Christine VOGEL  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Christine VOGEL  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Collège International de Valbonne (CoIV)"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Collège International de Valbonne (CoIV) existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Collège International de Valbonne (CoIV) existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Collège International de Valbonne (CoIV)")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Christine VOGEL  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Christine VOGEL  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Christine VOGEL excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Christine VOGEL excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 60){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 60';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=60 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "5ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "5ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="5ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 60, "5ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Christine VOGEL  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Christine VOGEL  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 43
// -----------------
Error_Log('Traitement ligne No 43 ===========================================');
echo '<BR>Traitement ligne No 43 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%VOGEL%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Elisabeth%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Elisabeth VOGEL existe déjà =========');
      echo '<BR>===== La fiche de Elisabeth VOGEL existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.43.39.72.60.") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.43.39.72.60."';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.43.39.72.60." WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "vogelisabeth@gmail.com") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "vogelisabeth@gmail.com"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="vogelisabeth@gmail.com" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "Domaine Pythagore, 5 placette du levant, 06560 VALBONNE") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "Domaine Pythagore, 5 placette du levant, 06560 VALBONNE"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="Domaine Pythagore, 5 placette du levant, 06560 VALBONNE" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_4){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_4.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_4.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_4){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_4.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_4.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2002-02-28") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2002-02-28"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2002-02-28" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Aime Chanter;") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Aime Chanter;"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Aime Chanter;" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("VOGEL", "Elisabeth", "F", 0, "06.43.39.72.60.", "vogelisabeth@gmail.com", "Domaine Pythagore, 5 placette du levant, 06560 VALBONNE", '.$ID_Pere_4.', '.$ID_Mere_4.', "2002-02-28", "Aime Chanter;")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Elisabeth VOGEL  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Elisabeth VOGEL  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Lycée International de Valbonne (LIV)"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Lycée International de Valbonne (LIV) existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Lycée International de Valbonne (LIV) existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Lycée International de Valbonne (LIV)")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Elisabeth VOGEL  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Elisabeth VOGEL  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Elisabeth VOGEL excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Elisabeth VOGEL excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 70){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 70';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=70 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "2nd") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "2nd"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="2nd" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 70, "2nd", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Elisabeth VOGEL  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Elisabeth VOGEL  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 44
// -----------------
Error_Log('Traitement ligne No 44 ===========================================');
echo '<BR>Traitement ligne No 44 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%GILLET%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Jean Christophe%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Jean Christophe GILLET existe déjà =========');
      echo '<BR>===== La fiche de Jean Christophe GILLET existe déjà id='.$row['id'].' =========';
   }
   $ID_Pere_2=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.51.51.30.48") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.51.51.30.48"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.51.51.30.48" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "jchristophe.gillet@gmail.com") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "jchristophe.gillet@gmail.com"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="jchristophe.gillet@gmail.com" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "12 chemin du collet de Saint Marc 06130 GRASSE") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "12 chemin du collet de Saint Marc 06130 GRASSE"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="12 chemin du collet de Saint Marc 06130 GRASSE" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Pere de Come GILLET") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Pere de Come GILLET"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Pere de Come GILLET" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("GILLET", "Jean Christophe", "M", 0, "06.51.51.30.48", "jchristophe.gillet@gmail.com", "12 chemin du collet de Saint Marc 06130 GRASSE", "", "", "", "Pere de Come GILLET")') or die(mysqli_error($eCOM_db));
   $ID_Pere_2 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Jean Christophe GILLET  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_2.')');
   echo '<BR>Individu : Jean Christophe GILLET  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_2.')';
}






// -----------------
// Ligne No 45
// -----------------
Error_Log('Traitement ligne No 45 ===========================================');
echo '<BR>Traitement ligne No 45 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%GILLET%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Anne Sophie%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Anne Sophie GILLET existe déjà =========');
      echo '<BR>===== La fiche de Anne Sophie GILLET existe déjà id='.$row['id'].' =========';
   }
   $ID_Mere_2=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "07.82.87.95.11") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "07.82.87.95.11"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 07.82.87.95.11" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "annso.gillet@gmail.com") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "annso.gillet@gmail.com"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="annso.gillet@gmail.com" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "12 chemin du collet de Saint Marc 06130 GRASSE") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "12 chemin du collet de Saint Marc 06130 GRASSE"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="12 chemin du collet de Saint Marc 06130 GRASSE" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Mere de Come GILLET") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Mere de Come GILLET"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Mere de Come GILLET" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("GILLET", "Anne Sophie", "F", 0, "07.82.87.95.11", "annso.gillet@gmail.com", "12 chemin du collet de Saint Marc 06130 GRASSE", "", "", "", "Mere de Come GILLET")') or die(mysqli_error($eCOM_db));
   $ID_Mere_2 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Anne Sophie GILLET  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_2.')');
   echo '<BR>Individu : Anne Sophie GILLET  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_2.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Collège Fénelon (Grasse)"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Collège Fénelon (Grasse) existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Collège Fénelon (Grasse) existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Collège Fénelon (Grasse)")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Anne Sophie GILLET  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Anne Sophie GILLET  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}




// -----------------
// Ligne No 46
// -----------------
Error_Log('Traitement ligne No 46 ===========================================');
echo '<BR>Traitement ligne No 46 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%GILLET%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Come%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Come GILLET existe déjà =========');
      echo '<BR>===== La fiche de Come GILLET existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "07.82.87.95.11") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "07.82.87.95.11"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 07.82.87.95.11" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "12 chemin du collet de Saint Marc 06130 GRASSE") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "12 chemin du collet de Saint Marc 06130 GRASSE"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="12 chemin du collet de Saint Marc 06130 GRASSE" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_2){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_2.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_2.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_2){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_2.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_2.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2004-02-19") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2004-02-19"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2004-02-19" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("GILLET", "Come", "M", 0, "07.82.87.95.11", "", "12 chemin du collet de Saint Marc 06130 GRASSE", '.$ID_Pere_2.', '.$ID_Mere_2.', "2004-02-19", "")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Come GILLET  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Come GILLET  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Lycée Fénelon (Grasse)"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Lycée Fénelon (Grasse) existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Lycée Fénelon (Grasse) existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Lycée Fénelon (Grasse)")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Come GILLET  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Come GILLET  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Come GILLET excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Come GILLET excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 60){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 60';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=60 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "4ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "4ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="4ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 60, "4ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Come GILLET  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Come GILLET  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 47
// -----------------
Error_Log('Traitement ligne No 47 ===========================================');
echo '<BR>Traitement ligne No 47 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%GILLET%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Salome%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Salome GILLET existe déjà =========');
      echo '<BR>===== La fiche de Salome GILLET existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "07.82.99.58.14") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "07.82.99.58.14"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 07.82.99.58.14" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "12 chemin du collet de Saint Marc 06130 GRASSE") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "12 chemin du collet de Saint Marc 06130 GRASSE"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="12 chemin du collet de Saint Marc 06130 GRASSE" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_2){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_2.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_2.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_2){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_2.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_2.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2001-09-04") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2001-09-04"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2001-09-04" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Aime Animer; Aime Chanter; Instrument : guitare") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Aime Animer; Aime Chanter; Instrument : guitare"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Aime Animer; Aime Chanter; Instrument : guitare" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("GILLET", "Salome", "F", 0, "07.82.99.58.14", "", "12 chemin du collet de Saint Marc 06130 GRASSE", '.$ID_Pere_2.', '.$ID_Mere_2.', "2001-09-04", "Aime Animer; Aime Chanter; Instrument : guitare")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Salome GILLET  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Salome GILLET  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Lycée Fénelon (Grasse)"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Lycée Fénelon (Grasse) existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Lycée Fénelon (Grasse) existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Lycée Fénelon (Grasse)")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Salome GILLET  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Salome GILLET  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Salome GILLET excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Salome GILLET excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 70){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 70';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=70 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "1ère") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "1ère"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="1ère" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 70, "1ère", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Salome GILLET  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Salome GILLET  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 48
// -----------------
Error_Log('Traitement ligne No 48 ===========================================');
echo '<BR>Traitement ligne No 48 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%COCHETEUX%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Thierry%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Thierry COCHETEUX existe déjà =========');
      echo '<BR>===== La fiche de Thierry COCHETEUX existe déjà id='.$row['id'].' =========';
   }
   $ID_Pere_34=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.71.25.48.94") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.71.25.48.94"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.71.25.48.94" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "math.cocheteux@gmail.com") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "math.cocheteux@gmail.com"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="math.cocheteux@gmail.com" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "Chemin de la Ponchette, CIDEX 450 06330 ROQUEFORT LES PINS") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "Chemin de la Ponchette, CIDEX 450 06330 ROQUEFORT LES PINS"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="Chemin de la Ponchette, CIDEX 450 06330 ROQUEFORT LES PINS" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Pere de Bérangère COCHETEUX") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Pere de Bérangère COCHETEUX"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Pere de Bérangère COCHETEUX" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("COCHETEUX", "Thierry", "M", 0, "06.71.25.48.94", "math.cocheteux@gmail.com", "Chemin de la Ponchette, CIDEX 450 06330 ROQUEFORT LES PINS", "", "", "", "Pere de Bérangère COCHETEUX")') or die(mysqli_error($eCOM_db));
   $ID_Pere_34 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Thierry COCHETEUX  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_34.')');
   echo '<BR>Individu : Thierry COCHETEUX  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_34.')';
}






// -----------------
// Ligne No 49
// -----------------
Error_Log('Traitement ligne No 49 ===========================================');
echo '<BR>Traitement ligne No 49 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%COCHETEUX%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Marie-astrid%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Marie-astrid COCHETEUX existe déjà =========');
      echo '<BR>===== La fiche de Marie-astrid COCHETEUX existe déjà id='.$row['id'].' =========';
   }
   $ID_Mere_34=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.68.95.22.90") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.68.95.22.90"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.68.95.22.90" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "math.cocheteux@gmail.com") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "math.cocheteux@gmail.com"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="math.cocheteux@gmail.com" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "Chemin de la Ponchette, CIDEX 450 06330 ROQUEFORT LES PINS") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "Chemin de la Ponchette, CIDEX 450 06330 ROQUEFORT LES PINS"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="Chemin de la Ponchette, CIDEX 450 06330 ROQUEFORT LES PINS" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Mere de Bérangère COCHETEUX") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Mere de Bérangère COCHETEUX"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Mere de Bérangère COCHETEUX" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("COCHETEUX", "Marie-astrid", "F", 0, "06.68.95.22.90", "math.cocheteux@gmail.com", "Chemin de la Ponchette, CIDEX 450 06330 ROQUEFORT LES PINS", "", "", "", "Mere de Bérangère COCHETEUX")') or die(mysqli_error($eCOM_db));
   $ID_Mere_34 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Marie-astrid COCHETEUX  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_34.')');
   echo '<BR>Individu : Marie-astrid COCHETEUX  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_34.')';
}






// -----------------
// Ligne No 50
// -----------------
Error_Log('Traitement ligne No 50 ===========================================');
echo '<BR>Traitement ligne No 50 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%COCHETEUX%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Bérangère%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Bérangère COCHETEUX existe déjà =========');
      echo '<BR>===== La fiche de Bérangère COCHETEUX existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "07.68.03.45.80") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "07.68.03.45.80"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 07.68.03.45.80" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "Chemin de la Ponchette, CIDEX 450 06330 ROQUEFORT LES PINS") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "Chemin de la Ponchette, CIDEX 450 06330 ROQUEFORT LES PINS"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="Chemin de la Ponchette, CIDEX 450 06330 ROQUEFORT LES PINS" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_34){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_34.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_34.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_34){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_34.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_34.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2004-10-19") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2004-10-19"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2004-10-19" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Aime Animer; Aime Chanter; Instrument : violoncelle") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Aime Animer; Aime Chanter; Instrument : violoncelle"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Aime Animer; Aime Chanter; Instrument : violoncelle" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("COCHETEUX", "Bérangère", "F", 0, "07.68.03.45.80", "", "Chemin de la Ponchette, CIDEX 450 06330 ROQUEFORT LES PINS", '.$ID_Pere_34.', '.$ID_Mere_34.', "2004-10-19", "Aime Animer; Aime Chanter; Instrument : violoncelle")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Bérangère COCHETEUX  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Bérangère COCHETEUX  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Collège César (Roquefort-Les-Pins)"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Collège César (Roquefort-Les-Pins) existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Collège César (Roquefort-Les-Pins) existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Collège César (Roquefort-Les-Pins)")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Bérangère COCHETEUX  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Bérangère COCHETEUX  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Bérangère COCHETEUX excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Bérangère COCHETEUX excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 70){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 70';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=70 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "4ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "4ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="4ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 70, "4ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Bérangère COCHETEUX  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Bérangère COCHETEUX  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 51
// -----------------
Error_Log('Traitement ligne No 51 ===========================================');
echo '<BR>Traitement ligne No 51 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%CADET%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Georges%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Georges CADET existe déjà =========');
      echo '<BR>===== La fiche de Georges CADET existe déjà id='.$row['id'].' =========';
   }
   $ID_Pere_33=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.17.16.77.69") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.17.16.77.69"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.17.16.77.69" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "georges2304@hotmail.com") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "georges2304@hotmail.com"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="georges2304@hotmail.com" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "873 route de la Colle 06270 Villeneuve loubet") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "873 route de la Colle 06270 Villeneuve loubet"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="873 route de la Colle 06270 Villeneuve loubet" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Pere de Marie Cadet") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Pere de Marie Cadet"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Pere de Marie Cadet" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("CADET", "Georges", "M", 0, "06.17.16.77.69", "georges2304@hotmail.com", "873 route de la Colle 06270 Villeneuve loubet", "", "", "", "Pere de Marie Cadet")') or die(mysqli_error($eCOM_db));
   $ID_Pere_33 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Georges CADET  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_33.')');
   echo '<BR>Individu : Georges CADET  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_33.')';
}






// -----------------
// Ligne No 52
// -----------------
Error_Log('Traitement ligne No 52 ===========================================');
echo '<BR>Traitement ligne No 52 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%CADET%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Micheline%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Micheline CADET existe déjà =========');
      echo '<BR>===== La fiche de Micheline CADET existe déjà id='.$row['id'].' =========';
   }
   $ID_Mere_33=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.12.74.57.89") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.12.74.57.89"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.12.74.57.89" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "mirazzouk@hotmail.com") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "mirazzouk@hotmail.com"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="mirazzouk@hotmail.com" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "873 route de la Colle 06270 Villeneuve loubet") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "873 route de la Colle 06270 Villeneuve loubet"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="873 route de la Colle 06270 Villeneuve loubet" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Mere de Marie Cadet") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Mere de Marie Cadet"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Mere de Marie Cadet" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("CADET", "Micheline", "F", 0, "06.12.74.57.89", "mirazzouk@hotmail.com", "873 route de la Colle 06270 Villeneuve loubet", "", "", "", "Mere de Marie Cadet")') or die(mysqli_error($eCOM_db));
   $ID_Mere_33 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Micheline CADET  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_33.')');
   echo '<BR>Individu : Micheline CADET  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_33.')';
}






// -----------------
// Ligne No 53
// -----------------
Error_Log('Traitement ligne No 53 ===========================================');
echo '<BR>Traitement ligne No 53 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%CADET%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Marie%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Marie CADET existe déjà =========');
      echo '<BR>===== La fiche de Marie CADET existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "07.82.51.66.46") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "07.82.51.66.46"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 07.82.51.66.46" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "873 route de la Colle 06270 Villeneuve loubet") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "873 route de la Colle 06270 Villeneuve loubet"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="873 route de la Colle 06270 Villeneuve loubet" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_33){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_33.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_33.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_33){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_33.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_33.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2003-08-10") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2003-08-10"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2003-08-10" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Aime Animer; Aime Chanter; Instrument : Oui piano et guitare") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Aime Animer; Aime Chanter; Instrument : Oui piano et guitare"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Aime Animer; Aime Chanter; Instrument : Oui piano et guitare" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("CADET", "Marie", "F", 0, "07.82.51.66.46", "", "873 route de la Colle 06270 Villeneuve loubet", '.$ID_Pere_33.', '.$ID_Mere_33.', "2003-08-10", "Aime Animer; Aime Chanter; Instrument : Oui piano et guitare")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Marie CADET  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Marie CADET  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Collège César (Roquefort-Les-Pins)"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Collège César (Roquefort-Les-Pins) existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Collège César (Roquefort-Les-Pins) existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Collège César (Roquefort-Les-Pins)")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Marie CADET  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Marie CADET  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Marie CADET excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Marie CADET excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 70){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 70';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=70 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "3ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "3ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="3ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 70, "3ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Marie CADET  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Marie CADET  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 54
// -----------------
Error_Log('Traitement ligne No 54 ===========================================');
echo '<BR>Traitement ligne No 54 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%VRIGNAULT%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Jérôme%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Jérôme VRIGNAULT existe déjà =========');
      echo '<BR>===== La fiche de Jérôme VRIGNAULT existe déjà id='.$row['id'].' =========';
   }
   $ID_Pere_32=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.73.55.55.66") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.73.55.55.66"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.73.55.55.66" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "jerome.vrignault@free.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "jerome.vrignault@free.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="jerome.vrignault@free.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "305 avenue St Philippe, Villa Panorama n°10 06410 BIOT") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "305 avenue St Philippe, Villa Panorama n°10 06410 BIOT"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="305 avenue St Philippe, Villa Panorama n°10 06410 BIOT" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Pere de Nils Vrignault") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Pere de Nils Vrignault"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Pere de Nils Vrignault" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("VRIGNAULT", "Jérôme", "M", 0, "06.73.55.55.66", "jerome.vrignault@free.fr", "305 avenue St Philippe, Villa Panorama n°10 06410 BIOT", "", "", "", "Pere de Nils Vrignault")') or die(mysqli_error($eCOM_db));
   $ID_Pere_32 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Jérôme VRIGNAULT  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_32.')');
   echo '<BR>Individu : Jérôme VRIGNAULT  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_32.')';
}






// -----------------
// Ligne No 55
// -----------------
Error_Log('Traitement ligne No 55 ===========================================');
echo '<BR>Traitement ligne No 55 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%VRIGNAULT%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Frédérique%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Frédérique VRIGNAULT existe déjà =========');
      echo '<BR>===== La fiche de Frédérique VRIGNAULT existe déjà id='.$row['id'].' =========';
   }
   $ID_Mere_32=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.82.06.60.11") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.82.06.60.11"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.82.06.60.11" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "fredvrignault@free.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "fredvrignault@free.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="fredvrignault@free.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "305 avenue St Philippe, Villa Panorama n°10 06410 BIOT") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "305 avenue St Philippe, Villa Panorama n°10 06410 BIOT"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="305 avenue St Philippe, Villa Panorama n°10 06410 BIOT" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Mere de Nils Vrignault") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Mere de Nils Vrignault"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Mere de Nils Vrignault" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("VRIGNAULT", "Frédérique", "F", 0, "06.82.06.60.11", "fredvrignault@free.fr", "305 avenue St Philippe, Villa Panorama n°10 06410 BIOT", "", "", "", "Mere de Nils Vrignault")') or die(mysqli_error($eCOM_db));
   $ID_Mere_32 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Frédérique VRIGNAULT  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_32.')');
   echo '<BR>Individu : Frédérique VRIGNAULT  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_32.')';
}






// -----------------
// Ligne No 56
// -----------------
Error_Log('Traitement ligne No 56 ===========================================');
echo '<BR>Traitement ligne No 56 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%VRIGNAULT%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Nils%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Nils VRIGNAULT existe déjà =========');
      echo '<BR>===== La fiche de Nils VRIGNAULT existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.52.52.94.47") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.52.52.94.47"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.52.52.94.47" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "305 avenue St Philippe, Villa Panorama n°10 06410 BIOT") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "305 avenue St Philippe, Villa Panorama n°10 06410 BIOT"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="305 avenue St Philippe, Villa Panorama n°10 06410 BIOT" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_32){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_32.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_32.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_32){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_32.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_32.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2002-05-29") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2002-05-29"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2002-05-29" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("VRIGNAULT", "Nils", "M", 0, "06.52.52.94.47", "", "305 avenue St Philippe, Villa Panorama n°10 06410 BIOT", '.$ID_Pere_32.', '.$ID_Mere_32.', "2002-05-29", "")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Nils VRIGNAULT  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Nils VRIGNAULT  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Lycée Simone Veil (valbonne)"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Lycée Simone Veil (valbonne) existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Lycée Simone Veil (valbonne) existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Lycée Simone Veil (valbonne)")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Nils VRIGNAULT  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Nils VRIGNAULT  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Nils VRIGNAULT excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Nils VRIGNAULT excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 70){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 70';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=70 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "2nd") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "2nd"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="2nd" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 70, "2nd", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Nils VRIGNAULT  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Nils VRIGNAULT  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 57
// -----------------
Error_Log('Traitement ligne No 57 ===========================================');
echo '<BR>Traitement ligne No 57 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%MASSIE%" AND Prenom COLLATE latin1_swedish_ci LIKE "%François%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de François MASSIE existe déjà =========');
      echo '<BR>===== La fiche de François MASSIE existe déjà id='.$row['id'].' =========';
   }
   $ID_Pere_31=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.27.19.31.13") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.27.19.31.13"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.27.19.31.13" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "francois.massie@yahoo.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "francois.massie@yahoo.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="francois.massie@yahoo.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "850 bvd de la source 06410 Biot") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "850 bvd de la source 06410 Biot"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="850 bvd de la source 06410 Biot" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Pere de Domitille Massie") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Pere de Domitille Massie"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Pere de Domitille Massie" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("MASSIE", "François", "M", 0, "06.27.19.31.13", "francois.massie@yahoo.fr", "850 bvd de la source 06410 Biot", "", "", "", "Pere de Domitille Massie")') or die(mysqli_error($eCOM_db));
   $ID_Pere_31 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : François MASSIE  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_31.')');
   echo '<BR>Individu : François MASSIE  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_31.')';
}






// -----------------
// Ligne No 58
// -----------------
Error_Log('Traitement ligne No 58 ===========================================');
echo '<BR>Traitement ligne No 58 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%MASSIE%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Elisabeth%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Elisabeth MASSIE existe déjà =========');
      echo '<BR>===== La fiche de Elisabeth MASSIE existe déjà id='.$row['id'].' =========';
   }
   $ID_Mere_31=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.09.13.93.23") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.09.13.93.23"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.09.13.93.23" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "elimassie@yahoo.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "elimassie@yahoo.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="elimassie@yahoo.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "850 bvd de la source 06410 Biot") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "850 bvd de la source 06410 Biot"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="850 bvd de la source 06410 Biot" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Mere de Domitille Massie") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Mere de Domitille Massie"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Mere de Domitille Massie" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("MASSIE", "Elisabeth", "F", 0, "06.09.13.93.23", "elimassie@yahoo.fr", "850 bvd de la source 06410 Biot", "", "", "", "Mere de Domitille Massie")') or die(mysqli_error($eCOM_db));
   $ID_Mere_31 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Elisabeth MASSIE  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_31.')');
   echo '<BR>Individu : Elisabeth MASSIE  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_31.')';
}






// -----------------
// Ligne No 59
// -----------------
Error_Log('Traitement ligne No 59 ===========================================');
echo '<BR>Traitement ligne No 59 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%MASSIE%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Domitille%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Domitille MASSIE existe déjà =========');
      echo '<BR>===== La fiche de Domitille MASSIE existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "09.73.50.73.79") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "09.73.50.73.79"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 09.73.50.73.79" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "850 bvd de la source 06410 Biot") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "850 bvd de la source 06410 Biot"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="850 bvd de la source 06410 Biot" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_31){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_31.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_31.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_31){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_31.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_31.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2006-01-21") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2006-01-21"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2006-01-21" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Aime Animer; Aime Chanter; Instrument : Flûte traversière") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Aime Animer; Aime Chanter; Instrument : Flûte traversière"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Aime Animer; Aime Chanter; Instrument : Flûte traversière" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("MASSIE", "Domitille", "F", 0, "09.73.50.73.79", "", "850 bvd de la source 06410 Biot", '.$ID_Pere_31.', '.$ID_Mere_31.', "2006-01-21", "Aime Animer; Aime Chanter; Instrument : Flûte traversière")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Domitille MASSIE  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Domitille MASSIE  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Collège Niki De Saint Phalle"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Collège Niki De Saint Phalle existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Collège Niki De Saint Phalle existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Collège Niki De Saint Phalle")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Domitille MASSIE  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Domitille MASSIE  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Domitille MASSIE excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Domitille MASSIE excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 60){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 60';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=60 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "6ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "6ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="6ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 60, "6ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Domitille MASSIE  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Domitille MASSIE  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 60
// -----------------
Error_Log('Traitement ligne No 60 ===========================================');
echo '<BR>Traitement ligne No 60 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%MASSIE%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Pauline%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Pauline MASSIE existe déjà =========');
      echo '<BR>===== La fiche de Pauline MASSIE existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.70.14.71.47") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.70.14.71.47"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.70.14.71.47" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "850 bvd de la source 06410 Biot") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "850 bvd de la source 06410 Biot"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="850 bvd de la source 06410 Biot" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_31){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_31.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_31.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_31){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_31.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_31.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2003-05-03") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2003-05-03"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2003-05-03" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("MASSIE", "Pauline", "F", 0, "06.70.14.71.47", "", "850 bvd de la source 06410 Biot", '.$ID_Pere_31.', '.$ID_Mere_31.', "2003-05-03", "")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Pauline MASSIE  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Pauline MASSIE  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Collège Niki De Saint Phalle"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Collège Niki De Saint Phalle existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Collège Niki De Saint Phalle existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Collège Niki De Saint Phalle")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Pauline MASSIE  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Pauline MASSIE  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Pauline MASSIE excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Pauline MASSIE excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 70){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 70';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=70 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "3ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "3ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="3ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 70, "3ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Pauline MASSIE  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Pauline MASSIE  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 61
// -----------------
Error_Log('Traitement ligne No 61 ===========================================');
echo '<BR>Traitement ligne No 61 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%DELORME%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Marc%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Marc DELORME existe déjà =========');
      echo '<BR>===== La fiche de Marc DELORME existe déjà id='.$row['id'].' =========';
   }
   $ID_Pere_29=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.64.96.95.88") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.64.96.95.88"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.64.96.95.88" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "delorme.marc@bbox.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "delorme.marc@bbox.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="delorme.marc@bbox.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "chemin du puits cidex 120 06330 Roquefort les pins") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "chemin du puits cidex 120 06330 Roquefort les pins"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="chemin du puits cidex 120 06330 Roquefort les pins" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Pere de Mayeul DELORME") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Pere de Mayeul DELORME"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Pere de Mayeul DELORME" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("DELORME", "Marc", "M", 0, "06.64.96.95.88", "delorme.marc@bbox.fr", "chemin du puits cidex 120 06330 Roquefort les pins", "", "", "", "Pere de Mayeul DELORME")') or die(mysqli_error($eCOM_db));
   $ID_Pere_29 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Marc DELORME  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_29.')');
   echo '<BR>Individu : Marc DELORME  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_29.')';
}






// -----------------
// Ligne No 62
// -----------------
Error_Log('Traitement ligne No 62 ===========================================');
echo '<BR>Traitement ligne No 62 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%DELORME%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Ségolène%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Ségolène DELORME existe déjà =========');
      echo '<BR>===== La fiche de Ségolène DELORME existe déjà id='.$row['id'].' =========';
   }
   $ID_Mere_29=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.62.96.63.32") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.62.96.63.32"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.62.96.63.32" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "segolene.delorme@bbox.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "segolene.delorme@bbox.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="segolene.delorme@bbox.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "chemin du puits cidex 120 06330 Roquefort les pins") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "chemin du puits cidex 120 06330 Roquefort les pins"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="chemin du puits cidex 120 06330 Roquefort les pins" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Mere de Mayeul DELORME") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Mere de Mayeul DELORME"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Mere de Mayeul DELORME" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("DELORME", "Ségolène", "F", 0, "06.62.96.63.32", "segolene.delorme@bbox.fr", "chemin du puits cidex 120 06330 Roquefort les pins", "", "", "", "Mere de Mayeul DELORME")') or die(mysqli_error($eCOM_db));
   $ID_Mere_29 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Ségolène DELORME  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_29.')');
   echo '<BR>Individu : Ségolène DELORME  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_29.')';
}






// -----------------
// Ligne No 63
// -----------------
Error_Log('Traitement ligne No 63 ===========================================');
echo '<BR>Traitement ligne No 63 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%DELORME%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Mayeul%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Mayeul DELORME existe déjà =========');
      echo '<BR>===== La fiche de Mayeul DELORME existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.50.10.98.55") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.50.10.98.55"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.50.10.98.55" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "chemin du puits cidex 120 06330 Roquefort les pins") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "chemin du puits cidex 120 06330 Roquefort les pins"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="chemin du puits cidex 120 06330 Roquefort les pins" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_29){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_29.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_29.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_29){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_29.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_29.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2005-01-17") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2005-01-17"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2005-01-17" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Instrument : VIOLON") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Instrument : VIOLON"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Instrument : VIOLON" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("DELORME", "Mayeul", "M", 0, "06.50.10.98.55", "", "chemin du puits cidex 120 06330 Roquefort les pins", '.$ID_Pere_29.', '.$ID_Mere_29.', "2005-01-17", "Instrument : VIOLON")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Mayeul DELORME  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Mayeul DELORME  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Collège Fénelon (Grasse)"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Collège Fénelon (Grasse) existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Collège Fénelon (Grasse) existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Collège Fénelon (Grasse)")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Mayeul DELORME  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Mayeul DELORME  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Mayeul DELORME excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Mayeul DELORME excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 70){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 70';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=70 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "4ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "4ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="4ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 70, "4ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Mayeul DELORME  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Mayeul DELORME  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 64
// -----------------
Error_Log('Traitement ligne No 64 ===========================================');
echo '<BR>Traitement ligne No 64 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%MANQUAT%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Thierry%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Thierry MANQUAT existe déjà =========');
      echo '<BR>===== La fiche de Thierry MANQUAT existe déjà id='.$row['id'].' =========';
   }
   $ID_Pere_27=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "papa@chezJesus.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "papa@chezJesus.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="papa@chezJesus.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "les tilleuls bt2 esc1 1620 avenue jules grec 06600 antibes") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "les tilleuls bt2 esc1 1620 avenue jules grec 06600 antibes"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="les tilleuls bt2 esc1 1620 avenue jules grec 06600 antibes" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Pere de sian manquat") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Pere de sian manquat"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Pere de sian manquat" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("MANQUAT", "Thierry", "M", 0, "", "papa@chezJesus.fr", "les tilleuls bt2 esc1 1620 avenue jules grec 06600 antibes", "", "", "", "Pere de sian manquat")') or die(mysqli_error($eCOM_db));
   $ID_Pere_27 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Thierry MANQUAT  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_27.')');
   echo '<BR>Individu : Thierry MANQUAT  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_27.')';
}






// -----------------
// Ligne No 65
// -----------------
Error_Log('Traitement ligne No 65 ===========================================');
echo '<BR>Traitement ligne No 65 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%SIRVENTE%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Sylvie%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Sylvie SIRVENTE existe déjà =========');
      echo '<BR>===== La fiche de Sylvie SIRVENTE existe déjà id='.$row['id'].' =========';
   }
   $ID_Mere_27=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.61.10.05.32") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.61.10.05.32"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.61.10.05.32" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "rigolette06@hotmail.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "rigolette06@hotmail.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="rigolette06@hotmail.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "les tilleuls bt2 esc1 1620 avenue jules grec 06600 antibes") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "les tilleuls bt2 esc1 1620 avenue jules grec 06600 antibes"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="les tilleuls bt2 esc1 1620 avenue jules grec 06600 antibes" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Mere de sian manquat") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Mere de sian manquat"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Mere de sian manquat" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("SIRVENTE", "Sylvie", "F", 0, "06.61.10.05.32", "rigolette06@hotmail.fr", "les tilleuls bt2 esc1 1620 avenue jules grec 06600 antibes", "", "", "", "Mere de sian manquat")') or die(mysqli_error($eCOM_db));
   $ID_Mere_27 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Sylvie SIRVENTE  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_27.')');
   echo '<BR>Individu : Sylvie SIRVENTE  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_27.')';
}






// -----------------
// Ligne No 66
// -----------------
Error_Log('Traitement ligne No 66 ===========================================');
echo '<BR>Traitement ligne No 66 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%MANQUAT%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Sian%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Sian MANQUAT existe déjà =========');
      echo '<BR>===== La fiche de Sian MANQUAT existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.67.28.06.40") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.67.28.06.40"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.67.28.06.40" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "lilalila06@hotmail.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "lilalila06@hotmail.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="lilalila06@hotmail.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "les tilleuls bt2 esc1 1620 avenue jules grec 06600 antibes") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "les tilleuls bt2 esc1 1620 avenue jules grec 06600 antibes"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="les tilleuls bt2 esc1 1620 avenue jules grec 06600 antibes" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_27){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_27.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_27.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_27){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_27.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_27.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2003-11-13") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2003-11-13"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2003-11-13" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Aime Animer; Aime Chanter; Instrument : école de chant") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Aime Animer; Aime Chanter; Instrument : école de chant"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Aime Animer; Aime Chanter; Instrument : école de chant" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("MANQUAT", "Sian", "M", 0, "06.67.28.06.40", "lilalila06@hotmail.fr", "les tilleuls bt2 esc1 1620 avenue jules grec 06600 antibes", '.$ID_Pere_27.', '.$ID_Mere_27.', "2003-11-13", "Aime Animer; Aime Chanter; Instrument : école de chant")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Sian MANQUAT  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Sian MANQUAT  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="CNED"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école CNED existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école CNED existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("CNED")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Sian MANQUAT  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Sian MANQUAT  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Sian MANQUAT excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Sian MANQUAT excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 70){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 70';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=70 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "4ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "4ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="4ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 70, "4ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Sian MANQUAT  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Sian MANQUAT  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 67
// -----------------
Error_Log('Traitement ligne No 67 ===========================================');
echo '<BR>Traitement ligne No 67 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%BARGHI%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Frédéric%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Frédéric BARGHI existe déjà =========');
      echo '<BR>===== La fiche de Frédéric BARGHI existe déjà id='.$row['id'].' =========';
   }
   $ID_Pere_26=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.32.52.65.27") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.32.52.65.27"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.32.52.65.27" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "fredbarghi@gmail.com") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "fredbarghi@gmail.com"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="fredbarghi@gmail.com" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "Les Villas du Rouret, 12 chemin de Saint-Jean, 06650 Le Rouret") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "Les Villas du Rouret, 12 chemin de Saint-Jean, 06650 Le Rouret"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="Les Villas du Rouret, 12 chemin de Saint-Jean, 06650 Le Rouret" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Pere de Marc BARGHI") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Pere de Marc BARGHI"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Pere de Marc BARGHI" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("BARGHI", "Frédéric", "M", 0, "06.32.52.65.27", "fredbarghi@gmail.com", "Les Villas du Rouret, 12 chemin de Saint-Jean, 06650 Le Rouret", "", "", "", "Pere de Marc BARGHI")') or die(mysqli_error($eCOM_db));
   $ID_Pere_26 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Frédéric BARGHI  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_26.')');
   echo '<BR>Individu : Frédéric BARGHI  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_26.')';
}






// -----------------
// Ligne No 68
// -----------------
Error_Log('Traitement ligne No 68 ===========================================');
echo '<BR>Traitement ligne No 68 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%BARGHI%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Virginie%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Virginie BARGHI existe déjà =========');
      echo '<BR>===== La fiche de Virginie BARGHI existe déjà id='.$row['id'].' =========';
   }
   $ID_Mere_26=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.61.05.33.84") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.61.05.33.84"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.61.05.33.84" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "virginie.barghi@free.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "virginie.barghi@free.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="virginie.barghi@free.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "Les Villas du Rouret, 12 chemin de Saint-Jean, 06650 Le Rouret") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "Les Villas du Rouret, 12 chemin de Saint-Jean, 06650 Le Rouret"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="Les Villas du Rouret, 12 chemin de Saint-Jean, 06650 Le Rouret" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Mere de Marc BARGHI") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Mere de Marc BARGHI"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Mere de Marc BARGHI" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("BARGHI", "Virginie", "F", 0, "06.61.05.33.84", "virginie.barghi@free.fr", "Les Villas du Rouret, 12 chemin de Saint-Jean, 06650 Le Rouret", "", "", "", "Mere de Marc BARGHI")') or die(mysqli_error($eCOM_db));
   $ID_Mere_26 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Virginie BARGHI  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_26.')');
   echo '<BR>Individu : Virginie BARGHI  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_26.')';
}






// -----------------
// Ligne No 69
// -----------------
Error_Log('Traitement ligne No 69 ===========================================');
echo '<BR>Traitement ligne No 69 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%BARGHI%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Marc%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Marc BARGHI existe déjà =========');
      echo '<BR>===== La fiche de Marc BARGHI existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "07.83.75.43.02") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "07.83.75.43.02"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 07.83.75.43.02" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "Les Villas du Rouret, 12 chemin de Saint-Jean, 06650 Le Rouret") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "Les Villas du Rouret, 12 chemin de Saint-Jean, 06650 Le Rouret"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="Les Villas du Rouret, 12 chemin de Saint-Jean, 06650 Le Rouret" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_26){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_26.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_26.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_26){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_26.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_26.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2004-06-13") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2004-06-13"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2004-06-13" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Instrument : Violon") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Instrument : Violon"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Instrument : Violon" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("BARGHI", "Marc", "M", 0, "07.83.75.43.02", "", "Les Villas du Rouret, 12 chemin de Saint-Jean, 06650 Le Rouret", '.$ID_Pere_26.', '.$ID_Mere_26.', "2004-06-13", "Instrument : Violon")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Marc BARGHI  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Marc BARGHI  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Collège César (Roquefort-Les-Pins)"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Collège César (Roquefort-Les-Pins) existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Collège César (Roquefort-Les-Pins) existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Collège César (Roquefort-Les-Pins)")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Marc BARGHI  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Marc BARGHI  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Marc BARGHI excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Marc BARGHI excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 60){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 60';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=60 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "4ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "4ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="4ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 60, "4ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Marc BARGHI  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Marc BARGHI  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 70
// -----------------
Error_Log('Traitement ligne No 70 ===========================================');
echo '<BR>Traitement ligne No 70 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%BARGHI%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Alexandra%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Alexandra BARGHI existe déjà =========');
      echo '<BR>===== La fiche de Alexandra BARGHI existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "07.83.42.34.81") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "07.83.42.34.81"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 07.83.42.34.81" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "Les Villas du Rouret, 12 chemin de Saint-Jean, 06650 Le Rouret") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "Les Villas du Rouret, 12 chemin de Saint-Jean, 06650 Le Rouret"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="Les Villas du Rouret, 12 chemin de Saint-Jean, 06650 Le Rouret" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_26){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_26.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_26.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_26){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_26.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_26.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2003-04-25") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2003-04-25"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2003-04-25" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Instrument : Piano") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Instrument : Piano"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Instrument : Piano" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("BARGHI", "Alexandra", "F", 0, "07.83.42.34.81", "", "Les Villas du Rouret, 12 chemin de Saint-Jean, 06650 Le Rouret", '.$ID_Pere_26.', '.$ID_Mere_26.', "2003-04-25", "Instrument : Piano")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Alexandra BARGHI  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Alexandra BARGHI  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Collège César (Roquefort-Les-Pins)"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Collège César (Roquefort-Les-Pins) existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Collège César (Roquefort-Les-Pins) existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Collège César (Roquefort-Les-Pins)")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Alexandra BARGHI  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Alexandra BARGHI  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Alexandra BARGHI excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Alexandra BARGHI excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 70){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 70';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=70 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "3ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "3ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="3ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 70, "3ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Alexandra BARGHI  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Alexandra BARGHI  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 71
// -----------------
Error_Log('Traitement ligne No 71 ===========================================');
echo '<BR>Traitement ligne No 71 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%SEPULCHRE%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Jacques-Alexandre%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Jacques-Alexandre SEPULCHRE existe déjà =========');
      echo '<BR>===== La fiche de Jacques-Alexandre SEPULCHRE existe déjà id='.$row['id'].' =========';
   }
   $ID_Pere_24=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "0678392043") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "0678392043"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 0678392043" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "y.ja.sepulchre@free.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "y.ja.sepulchre@free.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="y.ja.sepulchre@free.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "chemin des mouis, cidex 411, 06330 Roquefort les Pins") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "chemin des mouis, cidex 411, 06330 Roquefort les Pins"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="chemin des mouis, cidex 411, 06330 Roquefort les Pins" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Pere de Jean-Christophe Sepulchre") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Pere de Jean-Christophe Sepulchre"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Pere de Jean-Christophe Sepulchre" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("SEPULCHRE", "Jacques-Alexandre", "M", 0, "0678392043", "y.ja.sepulchre@free.fr", "chemin des mouis, cidex 411, 06330 Roquefort les Pins", "", "", "", "Pere de Jean-Christophe Sepulchre")') or die(mysqli_error($eCOM_db));
   $ID_Pere_24 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Jacques-Alexandre SEPULCHRE  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_24.')');
   echo '<BR>Individu : Jacques-Alexandre SEPULCHRE  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_24.')';
}






// -----------------
// Ligne No 72
// -----------------
Error_Log('Traitement ligne No 72 ===========================================');
echo '<BR>Traitement ligne No 72 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%SEPULCHRE%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Yannick%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Yannick SEPULCHRE existe déjà =========');
      echo '<BR>===== La fiche de Yannick SEPULCHRE existe déjà id='.$row['id'].' =========';
   }
   $ID_Mere_24=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.88.37.35.93") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.88.37.35.93"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.88.37.35.93" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "y.ja.sepulchre@free.fr") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "y.ja.sepulchre@free.fr"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="y.ja.sepulchre@free.fr" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "chemin des mouis, cidex 411, 06330 Roquefort les Pins") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "chemin des mouis, cidex 411, 06330 Roquefort les Pins"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="chemin des mouis, cidex 411, 06330 Roquefort les Pins" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Mere de Jean-Christophe Sepulchre") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Mere de Jean-Christophe Sepulchre"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Mere de Jean-Christophe Sepulchre" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("SEPULCHRE", "Yannick", "F", 0, "06.88.37.35.93", "y.ja.sepulchre@free.fr", "chemin des mouis, cidex 411, 06330 Roquefort les Pins", "", "", "", "Mere de Jean-Christophe Sepulchre")') or die(mysqli_error($eCOM_db));
   $ID_Mere_24 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Yannick SEPULCHRE  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_24.')');
   echo '<BR>Individu : Yannick SEPULCHRE  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_24.')';
}






// -----------------
// Ligne No 73
// -----------------
Error_Log('Traitement ligne No 73 ===========================================');
echo '<BR>Traitement ligne No 73 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%SEPULCHRE%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Jean-Christophe%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Jean-Christophe SEPULCHRE existe déjà =========');
      echo '<BR>===== La fiche de Jean-Christophe SEPULCHRE existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.95.60.17.53") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.95.60.17.53"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.95.60.17.53" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "chemin des mouis, cidex 411, 06330 Roquefort les Pins") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "chemin des mouis, cidex 411, 06330 Roquefort les Pins"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="chemin des mouis, cidex 411, 06330 Roquefort les Pins" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_24){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_24.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_24.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_24){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_24.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_24.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2001-08-01") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2001-08-01"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2001-08-01" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Aime Chanter;") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Aime Chanter;"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Aime Chanter;" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("SEPULCHRE", "Jean-Christophe", "M", 0, "06.95.60.17.53", "", "chemin des mouis, cidex 411, 06330 Roquefort les Pins", '.$ID_Pere_24.', '.$ID_Mere_24.', "2001-08-01", "Aime Chanter;")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Jean-Christophe SEPULCHRE  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Jean-Christophe SEPULCHRE  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Lycée Simone Veil"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Lycée Simone Veil existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Lycée Simone Veil existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Lycée Simone Veil")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Jean-Christophe SEPULCHRE  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Jean-Christophe SEPULCHRE  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Jean-Christophe SEPULCHRE excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Jean-Christophe SEPULCHRE excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 70){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 70';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=70 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "1ère") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "1ère"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="1ère" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 70, "1ère", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Jean-Christophe SEPULCHRE  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Jean-Christophe SEPULCHRE  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 74
// -----------------
Error_Log('Traitement ligne No 74 ===========================================');
echo '<BR>Traitement ligne No 74 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%CONSTANCIAS%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Jean-Michel%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Jean-Michel CONSTANCIAS existe déjà =========');
      echo '<BR>===== La fiche de Jean-Michel CONSTANCIAS existe déjà id='.$row['id'].' =========';
   }
   $ID_Pere_23=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "M") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "M"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="M" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "6 avenue Mélanie Tombarel 06560 Valbonne") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "6 avenue Mélanie Tombarel 06560 Valbonne"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="6 avenue Mélanie Tombarel 06560 Valbonne" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Pere de Gaëlle Constancias") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Pere de Gaëlle Constancias"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Pere de Gaëlle Constancias" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("CONSTANCIAS", "Jean-Michel", "M", 0, "", "", "6 avenue Mélanie Tombarel 06560 Valbonne", "", "", "", "Pere de Gaëlle Constancias")') or die(mysqli_error($eCOM_db));
   $ID_Pere_23 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Jean-Michel CONSTANCIAS  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_23.')');
   echo '<BR>Individu : Jean-Michel CONSTANCIAS  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Pere_23.')';
}






// -----------------
// Ligne No 75
// -----------------
Error_Log('Traitement ligne No 75 ===========================================');
echo '<BR>Traitement ligne No 75 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%CONSTANCIAS%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Christelle%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Christelle CONSTANCIAS existe déjà =========');
      echo '<BR>===== La fiche de Christelle CONSTANCIAS existe déjà id='.$row['id'].' =========';
   }
   $ID_Mere_23=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.33.33.50.93") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.33.33.50.93"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.33.33.50.93" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['e_mail'], "christelle.i.constancias@gmail.com") !== 0 ){
      echo '<BR>    Champs e_mail : '.$row['e_mail'].' --Devient--> "christelle.i.constancias@gmail.com"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET e_mail="christelle.i.constancias@gmail.com" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "6 avenue Mélanie Tombarel 06560 Valbonne") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "6 avenue Mélanie Tombarel 06560 Valbonne"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="6 avenue Mélanie Tombarel 06560 Valbonne" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Mere de Gaëlle Constancias") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Mere de Gaëlle Constancias"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Mere de Gaëlle Constancias" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("CONSTANCIAS", "Christelle", "F", 0, "06.33.33.50.93", "christelle.i.constancias@gmail.com", "6 avenue Mélanie Tombarel 06560 Valbonne", "", "", "", "Mere de Gaëlle Constancias")') or die(mysqli_error($eCOM_db));
   $ID_Mere_23 = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Christelle CONSTANCIAS  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_23.')');
   echo '<BR>Individu : Christelle CONSTANCIAS  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID_Mere_23.')';
}






// -----------------
// Ligne No 76
// -----------------
Error_Log('Traitement ligne No 76 ===========================================');
echo '<BR>Traitement ligne No 76 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%CONSTANCIAS%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Gaëlle%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Gaëlle CONSTANCIAS existe déjà =========');
      echo '<BR>===== La fiche de Gaëlle CONSTANCIAS existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "07.81.59.47.82") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "07.81.59.47.82"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 07.81.59.47.82" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "6 avenue Mélanie Tombarel 06560 Valbonne") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "6 avenue Mélanie Tombarel 06560 Valbonne"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="6 avenue Mélanie Tombarel 06560 Valbonne" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_23){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_23.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_23.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_23){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_23.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_23.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2004-12-21") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2004-12-21"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2004-12-21" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Aime Chanter;") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Aime Chanter;"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Aime Chanter;" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("CONSTANCIAS", "Gaëlle", "F", 0, "07.81.59.47.82", "", "6 avenue Mélanie Tombarel 06560 Valbonne", '.$ID_Pere_23.', '.$ID_Mere_23.', "2004-12-21", "Aime Chanter;")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Gaëlle CONSTANCIAS  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Gaëlle CONSTANCIAS  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Collège Niki De Saint Phalle"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Collège Niki De Saint Phalle existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Collège Niki De Saint Phalle existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Collège Niki De Saint Phalle")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Gaëlle CONSTANCIAS  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Gaëlle CONSTANCIAS  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Gaëlle CONSTANCIAS excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Gaëlle CONSTANCIAS excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 60){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 60';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=60 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "4ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "4ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="4ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 60, "4ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Gaëlle CONSTANCIAS  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Gaëlle CONSTANCIAS  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 77
// -----------------
Error_Log('Traitement ligne No 77 ===========================================');
echo '<BR>Traitement ligne No 77 ===========================================';


// Rechercher si le paroissien existe déjà dans la base
$requete = 'SELECT * FROM Individu WHERE Nom COLLATE latin1_swedish_ci LIKE "%CONSTANCIAS%" AND Prenom COLLATE latin1_swedish_ci LIKE "%Émilie%"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== La fiche de Émilie CONSTANCIAS existe déjà =========');
      echo '<BR>===== La fiche de Émilie CONSTANCIAS existe déjà id='.$row['id'].' =========';
   }
   $ID=$row['id'];
   Error_Log('Id:'.$row['id'].' -- ');
   Error_Log('Nom:'.$row['Nom'].' -- ');
   Error_Log('Prénom:'.$row['Prenom'].' -- ');
   Error_Log('---');
   if ( strcmp($row['Sex'], "F") !== 0 ){
      echo '<BR>    Champs Sex : '.$row['Sex'].' --Devient--> "F"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Sex="F" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pretre'] != 0){
      echo '<BR>    Champs Pretre : '.$row['Pretre'].' --Devient--> 0';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre=0 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Telephone'], "06.52.53.11.17") === FALSE ){
      echo '<BR>    Champs Telephone : '.$row['Telephone'].' --Devient--> "06.52.53.11.17"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Telephone="'.$row['Telephone'].' 06.52.53.11.17" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Adresse'], "6 avenue Mélanie Tombarel 06560 Valbonne") !== 0 ){
      echo '<BR>    Champs Adresse : '.$row['Adresse'].' --Devient--> "6 avenue Mélanie Tombarel 06560 Valbonne"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Adresse="6 avenue Mélanie Tombarel 06560 Valbonne" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Pere_id'] != $ID_Pere_23){
      echo '<BR>    Champs Pere_id : '.$row['Pere_id'].' --Devient--> '.$ID_Pere_23.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Pere_id='.$ID_Pere_23.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Mere_id'] != $ID_Mere_23){
      echo '<BR>    Champs Mere_id : '.$row['Mere_id'].' --Devient--> '.$ID_Mere_23.'';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Mere_id='.$ID_Mere_23.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Naissance'], "2003-01-08") !== 0 ){
      echo '<BR>    Champs Naissance : '.$row['Naissance'].' --Devient--> "2003-01-08"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Naissance="2003-01-08" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strpos($row['Commentaire'], "Aime Chanter;") === FALSE ){
      echo '<BR>    Champs Commentaire : '.$row['Commentaire'].' --Devient--> "Aime Chanter;"';
      mysqli_query($eCOM_db, 'UPDATE Individu SET Commentaire="'.$row['Commentaire'].' Aime Chanter;" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Individu` (`Nom`, `Prenom`, `Sex`, `Pretre`, `Telephone`, `e_mail`, `Adresse`, `Pere_id`, `Mere_id`, `Naissance`, `Commentaire`) VALUES ("CONSTANCIAS", "Émilie", "F", 0, "06.52.53.11.17", "", "6 avenue Mélanie Tombarel 06560 Valbonne", '.$ID_Pere_23.', '.$ID_Mere_23.', "2003-01-08", "Aime Chanter;")') or die(mysqli_error($eCOM_db));
   $ID = mysqli_insert_id($eCOM_db);
   Error_Log('Individu : Émilie CONSTANCIAS  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')');
   echo '<BR>Individu : Émilie CONSTANCIAS  ====>  Nouvelle fiche créée dans la table Individu (id='.$ID.')';
}


// Rechercher si l'école existe déjà
$requete = 'SELECT * FROM Ecoles WHERE Nom="Collège Niki de Saint Phalle"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== école Collège Niki de Saint Phalle existe déjà id='.$row['id'].'=========');
      echo '<BR>===== école Collège Niki de Saint Phalle existe déjà id='.$row['id'].'=========';
   }
   $Ecole_ID=$row['id'];
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `Ecoles` (`Nom`) VALUES ("Collège Niki de Saint Phalle")') or die(mysqli_error($eCOM_db));
   $Ecole_ID = mysqli_insert_id($eCOM_db);
   Error_Log('Ecoles : Émilie CONSTANCIAS  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')');
   echo '<BR>Ecoles : Émilie CONSTANCIAS  ====>  Nouvelle fiche créée dans la table Ecoles (id='.$Ecole_ID.')';
}


// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Émilie CONSTANCIAS excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Émilie CONSTANCIAS excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 70){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 70';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=70 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "3ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "3ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="3ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 70, "3ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Émilie CONSTANCIAS  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Émilie CONSTANCIAS  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


// -----------------
// Ligne No 78
// -----------------
Error_Log('Traitement ligne No 78 ===========================================');
echo '<BR>Traitement ligne No 78 ===========================================';
// Rechercher si le paroissien a déjà cette activité
$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id='.$ID.' AND Activite_id=26 AND QuoiQuoi_id=1 AND Session="2018"'; 
$result = mysqli_query($eCOM_db, $requete);
$FicheExist=False;
while($row = mysqli_fetch_assoc($result)){
   if ($FicheExist == False){
      $FicheExist = True;
      Error_Log('===== Émilie CONSTANCIAS excerce déjà cette activitée id='.$row['id'].'=========');
      echo '<BR>===== Émilie CONSTANCIAS excerce déjà cette activitée id='.$row['id'].'=========';
   }
   $QuiQuoi_ID=$row['id'];
   Error_Log('Activite_id:'.$row['Activite_id'].' -- ');
   Error_Log('QuoiQuoi_id:'.$row['QuoiQuoi_id'].' -- ');
   Error_Log('Session:'.$row['Session'].' -- ');
   Error_Log('---');
   if ( $row['Participation'] != 70){
      echo '<BR>    Champs Participation : '.$row['Participation'].' --Devient--> 70';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation=70 WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( strcmp($row['Detail'], "3ème") !== 0 ){
      echo '<BR>    Champs Detail : '.$row['Detail'].' --Devient--> "3ème"';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="3ème" WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
   if ( $row['Ecole_id'] != $Ecole_ID){
      echo '<BR>    Champs Ecole_id : '.$row['Ecole_id'].' --Devient--> '.$Ecole_ID.'';
      mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id='.$Ecole_ID.' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
   }
}
if ($FicheExist == False){
   $Result = mysqli_query($eCOM_db, 'INSERT INTO `QuiQuoi` (`Individu_id`, `Activite_id`, `QuoiQuoi_id`, `Session`, `Participation`, `Detail`, `Ecole_id`) VALUES ('.$ID.', 26, 1, "2018", 70, "3ème", '.$Ecole_ID.')') or die(mysqli_error($eCOM_db));
   $QuiQuoi_ID = mysqli_insert_id($eCOM_db);
   Error_Log('QuiQuoi : Émilie CONSTANCIAS  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')');
   echo '<BR>QuiQuoi : Émilie CONSTANCIAS  ====>  Nouvelle fiche créée dans la table QuiQuoi (id='.$QuiQuoi_ID.')';
}


mysqli_close($eCOM_db);
?>
