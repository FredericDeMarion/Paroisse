<?php
//==================================================================================================
//    Nom du module : template.inc développé par Frédéric de Marion - frederic.de.marion@free.fr
//--------------------------------------------------------------------------------------------------
//  Version |    Date    | Commentaires
//--------------------------------------------------------------------------------------------------
//    V1.00 | 12/04/2017 | Version originale
//==================================================================================================
// 17/07/2017 : Suppression du menu "Célébration"
//==================================================================================================


function sqlDateToOut($sqldate) {

  setlocale (LC_TIME, "fr");

  $year= substr($sqldate,0,4);
  $month= substr($sqldate,5,2);
  $day = substr($sqldate,8,2);
  $hour = substr($sqldate,11,2);
  $min = substr($sqldate,14,2);

  return mktime($hour,$min,0,$month,$day, $year); 
}


function debug_plus($ch) {
	?><SCRIPT language=javascript>
		alert('<?php echo $ch; ?>')
	</SCRIPT><?php

}


//header for all pages
function address_top () 
{
header( 'content-type: text/html; charset=iso-8859-1' );

echo '<!DOCTYPE HTML>';
echo '<HTML><HEAD>';
echo '<TITLE> Database '.pCOM_Get_NomParoisse().' </TITLE>';
?>
<link rel="stylesheet" href="css/style_index.css" />
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
<link rel="icon" type="image/png" href="logo.png" />
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
//debug("Session(template)=".$Session . "<BR>\n");
//debug("SessionEnCours(template)=".$SessionEnCours . "<BR>\n");
//$Activite= 1;


// compter le nombre de personne connecté
$sql3='SELECT * FROM Admin_user_online';
$result3=mysqli_query($eCOM_db, $sql3);
$count_user_online=mysqli_num_rows($result3);
$levelAutorisation = fCOM_Get_Autorization(0);
?>

<entete>
<style type="text/css">
#Corps { width: 100%; height: 190px; display:inline; color: white; position: relative; top: 90px; left: 0px; right: 0px; }
</style>


<TABLE WIDTH="98%" BORDER="0" CELLSPACING="0" CELLPADDING="2" BGCOLOR="#000000">
	<TR>
		<TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">
			<TR BGCOLOR="#F7F7F7">
				<TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
				<TR>
				<TD><FONT FACE="Verdana" SIZE="2">
				<?php
				echo '<B>Database '.pCOM_Get_NomParoisse().'</B>';
				$phpmyvisites_Title = "Accueil";
				include 'phpmyvisites.php';
				echo '<BR><FONT FACE="Verdana" SIZE="1">';
				echo '<TD ALIGN="right">';
				$result = Afficher_compteur_de_login();
				?>
				</TR></TABLE></TD>
			</TR>
			<TR BGCOLOR="#DDDDDD">
				<TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
				<TR><TD>
				<ul id="menu">
					<li class="menu_right"><a href="#" class="drop">Accueil</a><!-- classique -->
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<ul class="simple">
									<?php
									echo '<li><a href="'.$_SERVER['PHP_SELF'].'">Retour menu principal</a></li>';
									?>
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
<P>
<?php
// '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
//echo '</TABLE>';
echo '</entete>';

 
}

//footer all pages
function address_bottom () {
	echo '<TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#FFFFFF"></TD>';
	echo '<TR><TD height="20" BGCOLOR="#F7F7F7"> </TD></TR>';
	echo '<TR>';
	echo '<TD>';

	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">';
	echo '<TR>';
	echo '<TD BGCOLOR="#DDDDDD" ALIGN="right"><FONT FACE="Verdana" SIZE="1">Connecté en tant que : '.$_SERVER['PHP_AUTH_USER'].'&nbsp ';
	echo '</TR>';
	echo '</TABLE>';
	echo '</TD></TABLE>';


}
