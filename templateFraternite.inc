<?php

//==================================================================================================
//    Nom du module : templateFraternite.inc développé par Frédéric de Marion - frederic.de.marion@free.fr
//--------------------------------------------------------------------------------------------------
//  Version |    Date    | Commentaires
//--------------------------------------------------------------------------------------------------
//    V1.00 | 12/04/2017 | Version originale
//==================================================================================================


$Liste_Genre = array(" ", "F", "M");

function debug_plus($pString) {
	?><SCRIPT language=javascript>
		alert('<?php print $pString; ?>')
	</SCRIPT><?php

}

// Base QuiQuoi.Activite
// 1 - Pretre
// 2 - Accompagnateur Fiances preparation mariage
// 4 - Diacre
// 8 - Accompagnateur Bapteme

function get_liste_accompagnateurs() {
	Global $eCOM_db;
	if ($_SESSION["Session"]=="All") {
		$requete = 'SELECT DISTINCT Accompagnateur FROM QuiQuoi WHERE Activite=8 ORDER by Accompagnateur';
	} else {
		$requete = 'SELECT DISTINCT Accompagnateur FROM QuiQuoi WHERE Session=' . $_SESSION["Session"] . ' and Activite&8>0 ORDER by Accompagnateur';
	}
	$result = mysqli_query($eCOM_db, $requete);
	$Liste_Accompagnateurs = array("A affecter");
	$Item = 1;
	while($row = mysqli_fetch_assoc($result)){
		$Liste_Accompagnateurs[$Item]=$row['Accompagnateur'];
		$Item =$Item + 1;
	}
	$Liste_Accompagnateurs[$Item]="PLAULT";
	$Item =$Item + 1;
	$Liste_Accompagnateurs[$Item]="BILLE";
	$Item =$Item + 1;
	$Liste_Accompagnateurs[$Item]="Autre";
	$Item =$Item + 1;
	$Liste_Accompagnateurs[$Item]="Prépa. Ext.";
	$Item =$Item + 1;
	$Liste_Accompagnateurs[$Item]="Annulé/Reporté";
	$Item =$Item + 1;
	$Liste_Accompagnateurs[$Item]="WE";
	$Item =$Item + 1;
	$Liste_Accompagnateurs[$Item]="WE Annulé";
	$Item =$Item + 1;
	$Liste_Accompagnateurs[$Item]="CANA WE";
	$Item =$Item + 1;
	$Liste_Accompagnateurs[$Item]="CANA WE Annulé";
	$Item =$Item + 1;
	return $Liste_Accompagnateurs;
}

function get_liste_celebrants_testold() {
	Global $eCOM_db;
	$Liste_Celebrants = array("Autre ->"); // definit affichage par defaut - ici "Autre ->"
	$Item = 1;
	if ($_SESSION["Session"]=="All") {
		$requete = 'SELECT DISTINCT Accompagnateur FROM QuiQuoi WHERE Activite&1>0 || Activite&4>0 ORDER by Accompagnateur';
	} else {
		$requete = 'SELECT DISTINCT Accompagnateur FROM QuiQuoi WHERE (Activite&1>0 || Activite&4>0) && Session=' . $_SESSION["Session"] . ' ORDER by Accompagnateur';
	}
	$result = mysqli_query($eCOM_db, $requete);
	$Liste_Celebrants[$Item]="En attente";
	$Item =$Item + 1;
	while($row = mysqli_fetch_assoc($result)){
		$Liste_Celebrants[$Item]=$row['Accompagnateur'];
		$Item =$Item + 1;
	//	debug("celebrant acronyme=".$row[Acronyme] . "<BR>\n");
	//	sleep(5);
	}
	//debug_plus('templateMariage.php celebrants are = "'.$Liste_Celebrants[1].'"');
	$Liste_Celebrants[$Item]="Célébrant Extérieur";
	$Item =$Item + 1;
	return $Liste_Celebrants;
}


function stripAccents($string){
	return strtr($string,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}

function sqlDateToOut($sqldate) {

  setlocale (LC_TIME, "fr");

  $year= substr($sqldate,0,4);
  $month= substr($sqldate,5,2);
  $day = substr($sqldate,8,2);
  $hour = substr($sqldate,11,2);
  $min = substr($sqldate,14,2);

  return mktime($hour,$min,0,$month,$day, $year); 
}


 
//header for all pages
function address_top () 
{

if(empty($_SESSION["Session"])) {
	//Re-initialiser la valeur de $Session en revenant a l'accueil
	echo '<META http-equiv="refresh" content="1; URL=/index.php">';
	exit;
}
Global $eCOM_db;
$SessionEnCours = $_SESSION["Session"];
//global $Fraternite_Title;

$result = mysqli_query($eCOM_db, 'SELECT T0.`Nom` FROM Activites AS T0 WHERE T0.`id`='.$_SESSION["Activite_id"].' ');
while($row = mysqli_fetch_assoc($result)){
	$Titre_Fenetre=$row['Nom'];
}

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<TITLE>Database <?php echo $Titre_Fenetre ?></TITLE>
<meta name="generator" content="WYSIWYG Web Builder - http://www.wysiwygwebbuilder.com">
<script language="JavaScript" type="text/javascript">
<!--
function popupwnd(url, toolbar, menubar, locationbar, resize, scrollbars, statusbar, left, top, width, height)
{
   var popupwindow = this.open(url, '', 'toolbar=' + toolbar + ',menubar=' + menubar + ',location=' + locationbar + ',scrollbars=' + scrollbars + ',resizable=' + resize + ',status=' + statusbar + ',left=' + left + ',top=' + top + ',width=' + width + ',height=' + height);
}
//-->
</script>
<link rel="stylesheet" href="css/style_titre.css" type="text/css" media="screen" />
<link rel="icon" type="image/png" href="icone.png" />
</HEAD>
<BODY BGCOLOR="#FFFFFF" link="blue" vlink="blue" alink="blue">
<CENTER>
<!-- Provided by: Ross Poulton -->
<SCRIPT LANGUAGE="JavaScript">
	<!-- Begin
	function showProfile(id) {
		var windowprops = "toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,width=400,height=350";

		OpenWindow = window.open("<?php echo $_SERVER['PHP_SELF']; ?>?action=profile&id=" + id, "profile", windowprops); 
		
}
	//  End --> 
</script>

<?php
Global $eCOM_db;
$debug = false;

$Activite_id= $_SESSION["Activite_id"];
$debug = True;
$levelAutorisation = fCOM_Get_Autorization( $_SESSION["Activite_id"] );

// compter le nombre de personne connecté
$sql3='SELECT * FROM Admin_user_online';
$result3=mysqli_query($eCOM_db, $sql3);
$count_user_online=mysqli_num_rows($result3);
?>
<style type="text/css">
#Titre { width: 100%; height: 90px; display:inline; background-color:white; color: white; position: fixed; z-index: 2; top: 0px; left: 0px; right: 0px; }
</style>
<style type="text/css">
#Corps { width: 100%; height: 190px; display:inline; color: white; position: relative; top: 90px; left: 0px; right: 0px; }
</style>

<div id="Titre">
<TABLE WIDTH="98%" BORDER="0" CELLSPACING="0" CELLPADDING="2" BGCOLOR="#000000">
	<TR>
		<TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">
			<TR BGCOLOR="#F7F7F7">
				<TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
				<TR>
				<?php
				$result = mysqli_query($eCOM_db, 'SELECT T0.`Nom` FROM Activites AS T0 WHERE T0.`id`='.$_SESSION["Activite_id"].' ');
				while($row = mysqli_fetch_assoc($result)){
					if ($_SESSION["Session"]=="All") {
						echo '<TD><FONT FACE="Verdana" SIZE="2"><B>'.$row['Nom'].' : All Sessions</B>';
					} else {
						echo '<TD><FONT FACE="Verdana" SIZE="2"><B>'.$row['Nom'].' : '.($_SESSION["Session"] - 1).'-'.$_SESSION["Session"].'</B>';
					}
				}
				include 'phpmyvisites.php';
				
				echo '<FONT FACE="Verdana" SIZE="1">';
				if ($_SESSION["Session"]=="All") {
					$Ajouter_Session = '';
				} else {
					$Ajouter_Session = 'And Session = '.$_SESSION["Session"].' ';
				}				
				$result = mysqli_query($eCOM_db, 'SELECT * FROM Fraternite T0 WHERE T0.`Activite_id`='.$_SESSION["Activite_id"].' '.$Ajouter_Session.' ');
				$num_total = mysqli_num_rows($result);
				echo '<BR>Il y a '.$num_total.' groupes de fraternité.</FONT></TD>'; 
				echo '<TD ALIGN="right">';
				$result = Afficher_compteur_de_login();
				?>
				</TR></TABLE></TD>
			</TR>
			<TR BGCOLOR="#DDDDDD">
				<TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
				<TR><TD>
				<ul id="menu">
					<li class="menu_right"><a href="#" class="drop">Participants</a><!-- classique -->
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<ul class="simple">
									<?php
									echo '<li><a href="'.$_SERVER['PHP_SELF'].'?">Liste des Participants</a></li>';
									if ($_SESSION["Activite_id"] == 26) { // Aumônerie Lycée et collège
										echo '<li><a href="'.$_SERVER['PHP_SELF'].'?action=trombinoscope">Trombinoscope</a></li>';									
									}
									if ($_SESSION["Activite_id"] == 26 || // Aumônerie Lycée et collège
									    $_SESSION["Activite_id"] == 12) { // Cathéchèse
										echo '<li><a href="'.$_SERVER['PHP_SELF'].'?action=Inviter">Requete invitation nouveaux</a></li>';									
									}									
									echo '<li><a href="'.$_SERVER['PHP_SELF'].'?action=list_fraternite">Composition des frats</a></li>';
									if ($_SERVER['PHP_AUTH_USER'] == "administrateur" || $_SERVER['PHP_AUTH_USER'] == "comptable" || $_SERVER['PHP_AUTH_USER'] == "gestionnaire" || fCOM_Get_Autorization( $_SESSION["Activite_id"] ) >= 30) {
										echo '<li><a href="'.$_SERVER['PHP_SELF'].'?action=edit_Individu&id=0">Ajouter un Paroissien</a></li>';
										if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
											($_SESSION["Activite_id"] == 22) || // Emmaüs
											($_SESSION["Activite_id"] == 12)) { // Cathéchèse
											//if ($_SERVER['PHP_AUTH_USER'] == "administrateur" ) {
												echo '<li><a href="'.$_SERVER['PHP_SELF'].'?action=edit_Inscription&id=0">Ajouter une Inscription</a></li>';
											//}
										}
										echo '<li><a href="'.$_SERVER['PHP_SELF'].'?action=edit&id=0">Ajouter une Fraternité</a></li>';
									}
									?>
								</ul>
							</div>
						</div>
					</li>
					<li class="menu_right"><a href="#" class="drop">Accompagnateurs</a><!-- classique -->
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<ul class="simple">
									<?php
									echo '<li><a href="'.$_SERVER['PHP_SELF'].'?action=list_accomp">Liste</a></li>';
									if ($_SERVER['PHP_AUTH_USER'] == "administrateur" || $_SERVER['PHP_AUTH_USER'] == "comptable" || $_SERVER['PHP_AUTH_USER'] == "gestionnaire" || fCOM_Get_Autorization( $_SESSION["Activite_id"] ) >= 30) {
										echo '<li><a href="'.$_SERVER['PHP_SELF'].'?action=Configuration_Accompagnateur&id=0">Ajouter et retirer</a></li>';
									}
									?>
								</ul>
							</div>
						</div>
					</li>
					<li class="menu_right"><a href="#" class="drop">Rencontres</a><!-- classique -->
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<ul class="simple">
									<?php
									echo '<li><a href="'.$_SERVER['PHP_SELF'].'?action=rencontres">Liste</a></li>';
									//echo '<li><a href="'.$_SERVER['PHP_SELF'].'?action=e_mail&filtre=6eme">e_mail 6ème</a></li>';
									?>
								</ul>
							</div>
						</div>
					</li>
					<?php
					if (($_SERVER['PHP_AUTH_USER'] == "administrateur" || $_SERVER['PHP_AUTH_USER'] == "comptable" || $_SERVER['PHP_AUTH_USER'] == "gestionnaire" || fCOM_Get_Autorization( $_SESSION["Activite_id"] ) >= 30) && 
						(($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
						 ($_SESSION["Activite_id"] == 22) || // Emmaüs
						 ($_SESSION["Activite_id"] == 12))) { // Cathéchèse
					?>
					<li class="menu_right"><a href="#" class="drop">Compta</a><!-- classique -->
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<ul class="simple">
									<?php
									echo '<li><a href="'.$_SERVER['PHP_SELF'].'?action=vue_financiere">Participation financière</a></li>';
									?>
								</ul>
							</div>
						</div>
					</li>
					<?php
					}
					?>
					<li class="menu_right"><a href="#" class="drop">Accueil</a><!-- classique -->
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<ul class="simple">
									<li><a href="index.php">Retour menu principal</a></li>
								</ul>
							</div>
						</div>
					</li>
				</ul>
				</TD>
				<TD ALIGN="right" width="300px" ><FONT FACE="Verdana" SIZE="1">Connecté en tant que : <?php echo '<A HREF="/Login/logout.php">'.$_SERVER['PHP_AUTH_USER'].'</A><BR>Utilisateur(s) connecté(s) : '.$count_user_online.''; ?>
				</TR></TABLE></TD>
			</TR>
		</TABLE></TD>
	</TR>
</TABLE>
</div>
<P>
<div id="Corps">
<TABLE WIDTH="99%" BORDER="0" CELLSPACING="0" CELLPADDING="2" BGCOLOR="#000000"><TR><TD>
	

<?php
}

//footer all pages
function address_bottom () {

	echo '<TR BGCOLOR="#DDDDDD">';
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="0" BGCOLOR="#DDDDDD">';
	echo '<TR><TD HEIGHT="20" ALIGN="right" VALIGN="middle" ><FONT FACE="Verdana" SIZE="1">Connecté en tant que : '.$_SERVER['PHP_AUTH_USER'].' &nbsp</TD></TR>';
	echo '</DIV></TABLE>';
	echo '</TR>';
	
	echo '</TABLE>';
	
	echo '</TD></TR>';
	echo '</TABLE>';

	echo '</BODY>';
	echo '</HTML>';


}
