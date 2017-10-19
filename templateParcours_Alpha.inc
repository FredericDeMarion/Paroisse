<?php

//==================================================================================================
//    Nom du module : templateParcours_Alpha.inc développé par Frédéric de Marion - frederic.de.marion@free.fr
//--------------------------------------------------------------------------------------------------
//  Version |    Date    | Commentaires
//--------------------------------------------------------------------------------------------------
//    V1.00 | 12/04/2017 | Version originale
//==================================================================================================


$Liste_SSSessions = array("Session ?", "Biot Octobre soirée", "Sophia Janvier midi", "Sophia Avril soirée", "Sophia Octobre soirée", "Valbonne Janvier soirée");
$Liste_Genre = array(" ", "F", "M");

function debug_plus($ch) {
	?><SCRIPT language=javascript>
		alert('<?php print $ch; ?>')
	</SCRIPT><?php

}

// Base QuiQuoi.Activite
// 1 - Pretre
// 2 - Accompagnateur Fiances preparation mariage
// 4 - Diacre
// 8 - Accompagnateur Bapteme

function get_liste_accompagnateurs() {
	if ($_SESSION["Session"]=="All") {
		$requete = 'SELECT DISTINCT Accompagnateur FROM QuiQuoi WHERE Activite=8 ORDER by Accompagnateur';
	} else {
		$requete = 'SELECT DISTINCT Accompagnateur FROM QuiQuoi WHERE Session=' . $_SESSION["Session"] . ' and Activite&8>0 ORDER by Accompagnateur';
	}
	$result = mysql_query($requete);
	$Liste_Accompagnateurs = array("A affecter");
	$Item = 1;
	while($row = mysql_fetch_array($result)){
		$Liste_Accompagnateurs[$Item]=$row[Accompagnateur];
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

function get_liste_celebrants() {
	$Liste_Celebrants = array("Autre ->"); // definit affichage par defaut - ici "Autre ->"
	$Item = 1;
	if ($_SESSION["Session"]=="All") {
		$requete = 'SELECT DISTINCT Accompagnateur FROM QuiQuoi WHERE Activite&1>0 || Activite&4>0 ORDER by Accompagnateur';
	} else {
		$requete = 'SELECT DISTINCT Accompagnateur FROM QuiQuoi WHERE (Activite&1>0 || Activite&4>0) && Session=' . $_SESSION["Session"] . ' ORDER by Accompagnateur';
	}
	$result = mysql_query($requete);
	$Liste_Celebrants[$Item]="En attente";
	$Item =$Item + 1;
	while($row = mysql_fetch_array($result)){
		$Liste_Celebrants[$Item]=$row[Accompagnateur];
		$Item =$Item + 1;
	//	debug("celebrant acronyme=".$row[Acronyme] . "<BR>\n");
	//	sleep(5);
	}
	//debug_plus('templateMariage.php celebrants are = "'.$Liste_Celebrants[1].'"');
	$Liste_Celebrants[$Item]="Célébrant Extérieur";
	$Item =$Item + 1;
	return $Liste_Celebrants;
}

function get_liste_lieu_celebration() {
	$Item = 1;
	$requete_Lieux = 'SELECT * FROM Lieux ORDER BY Lieu';
	$result_Lieux = mysql_query($requete_Lieux);
	while($row_lieu = mysql_fetch_array($result_Lieux)){
		$Liste_Lieu_Celebration[$Item]=$row_lieu[Lieu];
		$Item =$Item + 1;
	}
	return $Liste_Lieu_Celebration;
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

//list all table 
function list_top ()
{
	?>	
	<table border=0 cellpadding=2 cellspacing=1 border=0 width=100%>
	<tr><td bgcolor=#CCCCCC><font face=verdana size=2><B><CENTER>Actions</CENTER></B></font></td>
	<td bgcolor=#CCCCCC><font face=verdana size=2><B><CENTER>Accompagnateurs:</CENTER></B></font></td>
	<td bgcolor=#CCCCCC><font face=verdana size=2><B><CENTER>LUI Prenom:</CENTER></B></font></td>
	<td bgcolor=#CCCCCC><font face=verdana size=2><B><CENTER>Nom:</CENTER></B></font></td>
	<td bgcolor=#CCCCCC><font face=verdana size=2><B><CENTER>ELLE Prenom:</CENTER></B></font></td>
	<td bgcolor=#CCCCCC><font face=verdana size=2><B><CENTER>Nom:</CENTER></B></font></td>
	<td bgcolor=#CCCCCC><font face=verdana size=2><B><CENTER>Celebrant:</CENTER></B></font></td>
	<td bgcolor=#CCCCCC><font face=verdana size=2><B><CENTER>Date:</CENTER></B></font></td>
<?php
}


 
//header for all pages
function address_top () 
{
global $SessionEnCours;
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<TITLE> Database Parcours Alpha </TITLE>
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

		OpenWindow = window.open("<?php echo $PHP_SELF; ?>?action=profile&id=" + id, "profile", windowprops); 
		
}
	//  End --> 
</script>

<?php
$debug = false;
debug("Session(template)=".$Session . "<BR>\n");
debug("SessionEnCours(template)=".$SessionEnCours . "<BR>\n");
//$Activite= 8; //Parcours Alpha
$levelAutorisation = fCOM_Get_Autorization( $_SESSION["Activite_id"] );

if(empty($_SESSION["Session"])) {
	//Re-initialiser la valeur de $Session en revenant a l'accueil
	echo '<META http-equiv="refresh" content="1; URL=http://frederic.de.marion.free.fr/index.php">';
	exit;
}
?>

<TABLE WIDTH="98%" BORDER="0" CELLSPACING="0" CELLPADDING="2" BGCOLOR="#000000">
	<TR>
		<TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">
			<TR BGCOLOR="#F7F7F7">
				<TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
				<TR>
				<TD><FONT FACE="Verdana" SIZE="2"><B>Parcours Alpha <?php echo "$SessionEnCours" ?> </B><BR>
				<FONT FACE="Verdana" SIZE="1">
				<?php
				require('sqlconf.php');
				if ($_SESSION["Session"]=="All") {
					$result = mysql_query("SELECT * FROM ParcoursAlpha ");
				} else {
					$result = mysql_query("SELECT * FROM ParcoursAlpha WHERE Session = ".$_SESSION["Session"]." ");
				}
				//$result = mysql_query("SELECT * FROM ".$Table." WHERE Session = ".$_SESSION["Session"]." ");
				$num_total = mysql_num_rows($result);
				echo("Il y a " . $num_total . " tables.</FONT></TD>\n"); 
				?>
				<TD ALIGN="right">
				<script type="text/javascript">var dominocounter_a = "compteurvalue2";</script>
				<script type="text/javascript" src="http://www.dominocounter.net/webcounter.js"></script></TD>
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
									echo '<li><a href="Parcours_Alpha.php?Session='.$_SESSION["Session"].'">Liste des Participants</a></li>';
									echo '<li><a href="Parcours_Alpha.php?Session='.$_SESSION["Session"].'&action=list_tables">Composition des Tables</a></li>';
									if ($_SERVER['PHP_AUTH_USER'] == "administrateur" || $_SERVER['PHP_AUTH_USER'] == "comptable" || $_SERVER['PHP_AUTH_USER'] == "gestionnaire" || fCOM_Get_Autorization( $_SESSION["Activite_id"] ) >= 30) {
										echo '<li><a href="Parcours_Alpha.php?Session='.$_SESSION["Session"].'&action=edit&id=0">Ajouter une table</a></li>';
										echo '<li><a href="Parcours_Alpha.php?Session='.$_SESSION["Session"].'&action=edit_Individu&id=0">Ajouter un Paroissien</a></li>';
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
									echo '<li><a href="Parcours_Alpha.php?Session='.$_SESSION["Session"].'&action=list_accomp">Liste</a></li>';
									if ($_SERVER['PHP_AUTH_USER'] == "administrateur" || $_SERVER['PHP_AUTH_USER'] == "comptable" || $_SERVER['PHP_AUTH_USER'] == "gestionnaire" || fCOM_Get_Autorization( $_SESSION["Activite_id"] ) >= 30) {
										echo '<li><a href="Parcours_Alpha.php?Session='.$_SESSION["Session"].'&action=Configuration_Accompagnateur&id=0&Activite='.$_SESSION["Activite_id"].'">Ajouter et retirer</a></li>';
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
									echo '<li><a href="Parcours_Alpha.php?Session='.$_SESSION["Session"].'&action=rencontres">Liste</a></li>';
									?>
								</ul>
							</div>
						</div>
					</li>
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
				<TD ALIGN="right" width="300px" ><FONT FACE="Verdana" SIZE="1">Connecté en tant que : <?php echo '<A HREF="/Login/logout.php">'.$_SERVER[PHP_AUTH_USER].'</A>'; ?>
				</TR></TABLE></TD>
			</TR>
		</TABLE></TD>
	</TR>
</TABLE>
<P>
		
<TABLE WIDTH="98%" BORDER="0" CELLSPACING="0" CELLPADDING="2" BGCOLOR="#000000"><TR><TD>
	

<?php
}

//footer all pages
function address_bottom () {

global $SessionEnCours;

?>

		
			
	<TR BGCOLOR="#DDDDDD">
	<TD Colspan="2">
	<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
	<TR>
	<?php
	//if ($_SERVER['PHP_AUTH_USER'] == "administrateur" || $_SERVER['PHP_AUTH_USER'] == "gestionnaire") {
	//	echo '<TD><FONT FACE="Verdana" SIZE="1"><A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.' ">lister</A> | <A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.'&action=list_accomp">Accompagnateurs</A> | <A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.'&action=Configuration_Accompagnateur&id=0">Configuration</A> | <A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.'&action=edit&id=0">Ajouter Invité</A> | <A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.'&action=edit_Individu&id=0">Ajouter paroissien</A> | <A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.'&action=rencontres">Rencontres</A> | <A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.'&action=vue_financiere">Finance</A> | <A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.'&action=printall">Imprimer tout</A> | <A HREF="index.php">Accueil</A> </FONT></TD>';
	//} else {
	//	echo '<TD><FONT FACE="Verdana" SIZE="1"><A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.' ">lister</A> | <A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.'&action=list_accomp">Accompagnateurs</A> | <A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.'&action=rencontres">Rencontres</A> | <A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.'&action=printall">Imprimer tout</A> | <A HREF="index.php">Accueil</A> </FONT></TD>';
	//}
	?>
	<TD ALIGN="right"><FONT FACE="Verdana" SIZE="1">Connecté en tant que : <?php echo "$_SERVER[PHP_AUTH_USER]" ?>
	</TR>
	</TABLE></TD>
	</TR>
	</TABLE>
	
</TD></TR>
</TABLE>

</BODY>
</HTML>

									  
   <?php
}
