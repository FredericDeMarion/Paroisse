<?php

//==================================================================================================
//    Nom du module : templateEvenement.inc développé par Frédéric de Marion - frederic.de.marion@free.fr
//--------------------------------------------------------------------------------------------------
//  Version |    Date    | Commentaires
//--------------------------------------------------------------------------------------------------
//    V1.00 | 12/04/2017 | Version originale
//==================================================================================================


$Liste_SSSessions = array("Session ?", "Biot Octobre soirée", "Sophia Janvier midi", "Sophia Avril soirée", "Sophia Octobre soirée", "Valbonne Janvier soirée");
$Liste_Genre = array(" ", "F", "M");

function debug_plus($ch) {
	?><SCRIPT language=javascript>
		alert('<?php echo  $ch; ?>')
	</SCRIPT><?php

}

// Base QuiQuoi.Activite
// 1 - Pretre
// 2 - Accompagnateur Fiances preparation mariage
// 4 - Diacre
// 8 - Accompagnateur Bapteme



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
<TITLE> Database Evénements </TITLE>
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
$debug = false;
//debug("Session(template)=".$Session . "<BR>\n");
//debug("SessionEnCours(template)=".$SessionEnCours . "<BR>\n");
$levelAutorisation = fCOM_Get_Autorization(0);

if(empty($_SESSION["Session"])) {
	//Re-initialiser la valeur de $Session en revenant a l'accueil
	//echo '<META http-equiv="refresh" content="1; URL=https://'.$_SERVER['SERVER_NAME'].'/index.php">';
	echo '<META http-equiv="refresh" content="1; URL=/index.php">';
	exit;
}
?>

<TABLE WIDTH="98%" BORDER="0" CELLSPACING="0" CELLPADDING="2" BGCOLOR="#000000">
	<TR>
		<TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">
			<TR BGCOLOR="#F7F7F7">
				<TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
				<TR>
				<TD><FONT FACE="Verdana" SIZE="2"><B>Evénements <?php echo "$SessionEnCours" ?> </B><BR>
				<FONT FACE="Verdana" SIZE="1">
				<?php
				//require('sqlconf.php');
				//if ($_SESSION["Session"]=="All") {
				//	$result = mysqli_query( $eCOM_db, "SELECT * FROM ParcoursAlpha ");
				//} else {
				//	$result = mysqli_query( $eCOM_db, "SELECT * FROM ParcoursAlpha WHERE Session = ".$_SESSION["Session"]." ");
				//}
				//$num_total = mysqli_num_rows($result);
				//echo("Il y a " . $num_total . " tables.</FONT></TD>\n"); 
				echo "</FONT></TD>\n"; 
				
				echo '<TD ALIGN="right">';
				//echo '<script type="text/javascript">var dominocounter_a = "compteurvalue2";</script>';
				//echo '<script type="text/javascript" src="http://www.dominocounter.net/webcounter.js"></script>';
				echo '</TD></TR></TABLE></TD>';
				?>
			</TR>
			<TR BGCOLOR="#DDDDDD">
				<TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
				<TR><TD>
				<ul id="menu">
					<li class="menu_right"><a href="#" class="drop">Célébrations</a><!-- classique -->
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<ul class="simple">
									<?php
									echo '<li><a href="Evenements.php?Session='.$_SESSION["Session"].'">Afficher Liste</a></li>';
									if (fCOM_Get_Autorization( $_SESSION["Activite_id"] ) >= 30) {
										echo '<li><a href="Evenements.php?Session='.$_SESSION["Session"].'&action=edit&id=0">Ajouter</a></li>';
									}
									?>
								</ul>
							</div>
						</div>
					</li>
					<?php
					if (fCOM_Get_Autorization( $_SESSION["Activite_id"] ) >= 30) {
					?>
					<li class="menu_right"><a href="#" class="drop">Configuration</a><!-- classique -->
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<ul class="simple">
									<?php
									echo '<li><a href="Evenements.php?Session='.$_SESSION["Session"].'&action=Prog_Recurrente_Celebration&id=0">Célébrations récurrentes</a></li>';
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
				<TD ALIGN="right" width="300px" ><FONT FACE="Verdana" SIZE="1">Connecté en tant que : <?php echo '<A HREF="/Login/logout.php">'.$_SERVER['PHP_AUTH_USER'].'</A>'; ?>
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

	
	echo '<TR BGCOLOR="#DDDDDD">';
	echo '<TD Colspan="2">';
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">';
	echo '<TR>';
	//if ($_SERVER['PHP_AUTH_USER'] == "administrateur" || $_SERVER['PHP_AUTH_USER'] == "gestionnaire") {
	//	echo '<TD><FONT FACE="Verdana" SIZE="1"><A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.' ">lister</A> | <A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.'&action=list_accomp">Accompagnateurs</A> | <A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.'&action=Configuration_Accompagnateur&id=0">Configuration</A> | <A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.'&action=edit&id=0">Ajouter Invité</A> | <A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.'&action=edit_Individu&id=0">Ajouter paroissien</A> | <A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.'&action=rencontres">Rencontres</A> | <A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.'&action=vue_financiere">Finance</A> | <A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.'&action=printall">Imprimer tout</A> | <A HREF="index.php">Accueil</A> </FONT></TD>';
	//} else {
	//	echo '<TD><FONT FACE="Verdana" SIZE="1"><A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.' ">lister</A> | <A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.'&action=list_accomp">Accompagnateurs</A> | <A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.'&action=rencontres">Rencontres</A> | <A HREF="Parcours_Alpha.php?Session='.$SessionEnCours.'&action=printall">Imprimer tout</A> | <A HREF="index.php">Accueil</A> </FONT></TD>';
	//}
	echo '<TD ALIGN="right"><FONT FACE="Verdana" SIZE="1">Connecté en tant que : '.$_SERVER['PHP_AUTH_USER'].'</TR>
	</TABLE>';
	echo '</TD></TR></TABLE>';
	
	echo '</TD></TR></TABLE>';

	echo '</BODY>';
	echo '</HTML>';

  
}
