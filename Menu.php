<?php
//==================================================================================================
//    Nom du module : template.inc développé par Frédéric de Marion - frederic.de.marion@free.fr
//--------------------------------------------------------------------------------------------------
//  Version |    Date    | Commentaires
//--------------------------------------------------------------------------------------------------
//    V1.00 | 12/04/2017 | Version originale
//==================================================================================================
// 17/07/2017 : Suppression du menu "Célébration"
// 21/05/2018 : Gestion des annonces en fin de messe (Ajouter une table Annonces, créé un répertoire "Annonces" sous "images", ajouter dans la table "Activites" le service 170-"Annnonces fin de messe")
// 12/05/2019 : fMENU_top passage en <meta charset="iso-8859-1"> 
//==================================================================================================





//header for all pages
function fMENU_top () 
{
	header( 'content-type: text/html; charset=iso-8859-1' );
	//header( 'content-type: text/html; charset=utf-8' );

	echo '<!DOCTYPE HTML>';
	echo '<HTML><HEAD>';
	echo '<meta charset="iso-8859-1">';
	//echo '<meta charset="utf-8">';
	echo '<meta name="viewport" content="width=device-width,  initial-scale=1, shrink-to-fit=no">';
	echo '<TITLE> Database '.pCOM_Get_NomParoisse().' </TITLE>';
	echo '<link rel="footer" href="Menu.css" />';
	echo '<link rel="stylesheet" href="css/style_paroisse.css" />';
	//echo '<meta name="generator" content="WYSIWYG Web Builder - http://www.wysiwygwebbuilder.com">';
	// bootstarp 3 only : echo '<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>';
	echo '<link rel="icon" type="image/png" href="logo.png" />';
	fCOM_Bootstrap_init();
	?>
	<script language="JavaScript" type="text/javascript">
	$(function () {
$('a[data-toggle="tooltip"]').tooltip({
    animated: 'fade',
    placement: 'right',
    html: true
	})});
	$(function(){
     $(".table").on("click", "tr[role=\"button\"]", function (e) {
          window.location = $(this).data("href");
     });
	});
	</script>


	<?php
	// initial-scale règle le niveau de zoom à 100%
echo '</HEAD>';

Global $eCOM_db;
$debug = false;

$levelAutorisation = fCOM_Get_Autorization(0);

if (isset($_POST['SessionSelection'])) { $_SESSION["Session"]=$_POST['SessionSelection']; }
if (!isset($_SESSION["Session"])) {
	if (date("n") >= 8) {
		$_SESSION["Session"]=date("Y")+1;
	} else {
		$_SESSION["Session"]=date("Y");
	}
}
setlocale(LC_TIME, 'fr_FR.utf8','fra');
?>
<BODY>

<div class="header">
<div class="top-header">

	<div class="col bg-dark">
	<div class="text-secondary">
	<?php
	// Gestion affichage de la première ligne
	//---------------------------------------
	echo '<div style="color:white">';
	if ($_SERVER['PHP_SELF'] == "/index.php") {
		$ModulePhp = $_SERVER['PHP_SELF'];
		echo pCOM_Get_NomParoisse();
	} else {
		$result = mysqli_query( $eCOM_db, "SELECT Nom FROM Activites WHERE id=".$_SESSION["Activite_id"]." ");
		$row = mysqli_fetch_assoc( $result );
		$Titre_Page = $row['Nom'];
		if ( $_SESSION["Activite_id"] == 3 OR $_SESSION["Activite_id"] == 46) {
			$Titre_Page = "Préparation ".$row['Nom'];
		} elseif ( $_SESSION["Activite_id"] == 86 ) {
			$Titre_Page = $Titre_Page." et Célébrations ";
		}
		if ($_SESSION["Session"]=="All") {
			echo $Titre_Page;
		} else {
			echo $Titre_Page.' '.($_SESSION["Session"] - 1).' - '.$_SESSION["Session"];
		}
	}
	echo '</div>';
	?>
	</div></div>
</div>
<nav class="navbar navbar-expand-md navbar-dark bg-dark">

  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  
  <a class="navbar-brand" href="index.php">
    <img src="logo.jpg" width="40" class="d-inline-block align-top" alt=""> Sovabi
  </a>
  
  <div class="navbar-collapse collapse" id="navbarTogglerDemo02">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
	

	  
	<?php
	$requete = 'SELECT id, Nom, Menu_Ordre, Menu_PHP_File 
				FROM `Activites` 
				WHERE Menu_Ordre > 0 
				ORDER BY Menu_Ordre ASC';
	$result = mysqli_query($eCOM_db, $requete);
	while( $row = mysqli_fetch_assoc( $result)) {
		if ($_SESSION["Activite_id"] == $row['id']) {
			$ModulePhp = substr($row['Menu_PHP_File'],9);
		}
	}
	if (!isset($ModulePhp)) { $ModulePhp = 'SuiviParoissien.php';}
	
	
	if ($_SESSION["Activite_id"]==2) { // Préparation mariage
		$NomDuConcerne="fiancé";
	} elseif ($_SESSION["Activite_id"]==3) { // Baptême bébé
		$NomDuConcerne="baptisé";
	} elseif ($_SESSION["Activite_id"]==4 OR // Parcours Alpha
			  $_SESSION["Activite_id"]==22 OR // Emmaüs
			  $_SESSION["Activite_id"]==59 OR // 40 jours motivés par l'essentiel
			  $_SESSION["Activite_id"]==85 ) { // Sophia DEO
		$NomDuConcerne="Participant";
	} elseif ($_SESSION["Activite_id"]==12) { // Catéchèse
		$NomDuConcerne="enfant";
	} elseif ($_SESSION["Activite_id"]==26) { // Aumônerie
		$NomDuConcerne="adolescent";
	} elseif ($_SESSION["Activite_id"]==46) { // Baptême adulte
		$NomDuConcerne="baptisé";
	} elseif ($_SESSION["Activite_id"]==86) { // Messe et célébration
		$NomDuConcerne="Célébration";
		$ModulePhp = "Evenements.php";
	} else {
		$NomDuConcerne="Paroissien";
		$ModulePhp = "Fraternite.php";
	}
	
	// tester si le paroissien est gestionnaire
	$Gestionnaire = False;
	$Administrateur = False;
	$Requete = 'SELECT * from QuiQuoi WHERE Individu_id='.$_SESSION['USER_ID'].' AND Engagement_id=0 AND WEB_G = 1 AND Session="'.$_SESSION["Session"].'"';
	$result=mysqli_query($eCOM_db, $Requete);
	$count_1=mysqli_num_rows($result);
	if ($count_1 > 0) {
		$Gestionnaire = True;
	}
	if (fCOM_Get_Autorization(0) == 90 ) {
		$Administrateur = True;
	}
	//============================
	// Afficher
	//============================
	//if ($_SERVER['PHP_SELF'] == "/index.php" OR $_SERVER['PHP_SELF'] == "/index2.php") {
	//	$_SESSION["Activite_id"]=0;
	//}
	
	if ((fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 20 AND 
		$_SERVER['PHP_SELF'] != "/index.php" ) OR $Gestionnaire == True)  {
	
		?>
		<li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle" style="color:white" href="http://example.com" id="navbarServicesLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Afficher
		</a>
		
		<div class="dropdown-menu" aria-labelledby="navbarServicesLink">
		<?php
		

		if (fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 20 AND 
			$_SERVER['PHP_SELF'] != "/index.php" ) {

			if ($_SESSION["Activite_id"]==2 OR // Préparation mariage
				$_SESSION["Activite_id"]==3 OR // Baptême bébé
				$_SESSION["Activite_id"]==46 OR // Baptême adulte
				$_SESSION["Activite_id"]==86 OR // Messe et célébration
				$_SESSION["Activite_id"]==4 OR // Parcours Alpha
				$_SESSION["Activite_id"]==59 OR // 40 jours motivés par l'essentiel
				$_SESSION["Activite_id"]==12 OR // Catéchèse
				$_SESSION["Activite_id"]==85 OR // SophiaDeo
				$_SESSION["Activite_id"]==22 OR // Emmaüs
				$_SESSION["Activite_id"]==26) { // Aumônerie Lycée et collège

				echo '<a class="dropdown-item" href="'.$ModulePhp.'?Session='.$_SESSION["Session"].'">les '.$NomDuConcerne.'s</a>';
				
				if ($_SESSION["Activite_id"] == 26 OR // Aumônerie Lycée et collège
					$_SESSION["Activite_id"] == 4 OR // Parcours Alpha
					$_SESSION["Activite_id"] == 22 OR // Emmaüs
					$_SESSION["Activite_id"] == 12) { // Cathéchèse
					echo '<a class="dropdown-item" href="'.$ModulePhp.'?action=list_fraternite">la liste des Fraternités</a>';
				}
				
				if ($_SESSION["Activite_id"]!=86) { // Messe et célébration
					if ($_SESSION["Activite_id"]==2 OR // Préparation mariage
						$_SESSION["Activite_id"]==12 OR // Catéchèse
						$_SESSION["Activite_id"]==26) { // Aumônerie Lycée et collège
						echo '<a class="dropdown-item" href="'.$ModulePhp.'?action=trombinoscope">Le Trombinoscope</a>';
					}
					if ($_SESSION["Activite_id"] == 26 || // Aumônerie Lycée et collège
						$_SESSION["Activite_id"] == 12) { // Cathéchèse
						echo '<a class="dropdown-item" href="'.$_SERVER['PHP_SELF'].'?action=list_fraternite">La composition des frats</a>';
					}
					echo '<div class="dropdown-divider"></div>';
					echo '<a class="dropdown-item" href="'.$ModulePhp.'?Session='.$_SESSION["Session"].'&action=list_accomp">la liste des accompagnateurs</a>';
					if ($_SESSION["Activite_id"] == 3 ) { // Baptême bébé
						echo '<a class="dropdown-item" href="'.$ModulePhp.'?Session='.$_SESSION["Session"].'&action=list_accompagnateur_sssession">Les accompagnateurs par session</a>';
					}
					echo '<div class="dropdown-divider"></div>';
					echo '<a class="dropdown-item" href="'.$ModulePhp.'?Session='.$_SESSION["Session"].'&action=rencontres">Les rencontres</a>';
				}
			} else {
				echo '<a class="dropdown-item" href="'.$ModulePhp.'?Session='.$_SESSION["Session"].'">les '.$NomDuConcerne.'s</a>';
				if ($_SESSION["Activite_id"] == 26 OR // Aumônerie Lycée et collège
					$_SESSION["Activite_id"] == 4 OR // Parcours Alpha
					$_SESSION["Activite_id"] == 22 OR // Emmaüs
					$_SESSION["Activite_id"] == 12) { // Cathéchèse
					echo '<a class="dropdown-item" href="'.$ModulePhp.'?action=list_fraternite">liste des fraternités</a>';
				}
				echo '<a class="dropdown-item" href="'.$ModulePhp.'?action=trombinoscope">Le Trombinoscope</a>';
			}
		}
		
		if ($Gestionnaire == True) {
			echo '<div class="dropdown-divider"></div>';
			echo '<a class="dropdown-item" href="SuiviParoissien.php?action=AfficherParoissiensParAge">Par Age</a>';
			echo '<a class="dropdown-item" href="SuiviParoissien.php?action=list_langue">Langue Maternelle</a>';
			echo '<a class="dropdown-item" href="SuiviParoissien.php?Session='.$_SESSION["Session"].'&action=list_services">Paroissiens au service</a>';
			echo '<a class="dropdown-item" href="SuiviParoissien.php?Session='.$_SESSION["Session"].'&action=list_ressourcements">Paroissiens en ressourcement</a>';
			echo '<a class="dropdown-item" href="SuiviParoissien.php?Session='.$_SESSION["Session"].'&action=list_souhaits">Souhaits des paroissiens</a>';
			echo '<a class="dropdown-item" href="SuiviParoissien.php?Session='.$_SESSION["Session"].'&action=list_gestionnaires">Liste des gestionnaires de la base</a>';
			if ($Administrateur == True) {
				echo '<a class="dropdown-item" href="SuiviParoissien.php?Session='.$_SESSION["Session"].'&action=list_paroissiens_RGPD">RGPD Consultation des paroissiens pour consentement</a>';
			}
			echo '<a class="dropdown-item" href="organigramme/index2.php">Organigramme</a>';
		}
		
		echo '</div>';
		echo '</li>';
	
	} else  {
		?>
		<li class="nav-item">
		<a class="nav-link disabled" href="#">Afficher</a>
		</li>
		<?php
	}
	
	//============================
	// Ajouter
	//============================
	if (fCOM_Get_Autorization( 0 )>= 30 ) {
		?>		
		<li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle" style="color:white" href="http://example.com" id="navbarServicesLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ajouter
		</a>
		<div class="dropdown-menu" aria-labelledby="navbarServicesLink">
		<?php

		if ($_SERVER['PHP_SELF'] != "/index.php") {
			if ($_SESSION["Activite_id"] == 26 OR // Aumônerie Lycée et collège
				$_SESSION["Activite_id"] == 4 OR // Parcours Alpha
				$_SESSION["Activite_id"] == 22 OR // Emmaüs
				$_SESSION["Activite_id"] == 12) { // Cathéchèse
				if ($_SESSION["Activite_id"] == 4 ){ // Parcours Alpha
					//echo '<a class="dropdown-item" href="'.$ModulePhp.'?Session='.$_SESSION["Session"].'&action=edit&id=0">Un.e Participant.e</a>';
				} else {
					echo '<a class="dropdown-item" href="'.$_SERVER['PHP_SELF'].'?action=edit_Inscription&id=0">Une Inscription</a>';
				}
				echo '<a class="dropdown-item" href="'.$_SERVER['PHP_SELF'].'?action=edit&id=0">Une Fraternité</a>';

			} else {
			
				if ($_SESSION["Activite_id"]==2) { // Préparation mariage
					$NomDuConcerne="un futurs mariage";
				} elseif ($_SESSION["Activite_id"]==86) { // Evénement
					$NomDuConcerne='Une '.$NomDuConcerne;
				} else {
					$NomDuConcerne='Un '.$NomDuConcerne;
				}
				echo '<a class="dropdown-item" href="'.$ModulePhp.'?Session='.$_SESSION["Session"].'&action=edit&id=0">'.$NomDuConcerne.'</a>';
			}

			if ($_SESSION["Activite_id"]==86) { // Messe et célébration
				if (fCOM_Get_Autorization( 171 )>= 20 ) { // Annonces fin des messes
					echo '<a class="dropdown-item" href="Evenements.php?action=Annonce">Configurer les Annonces de fin de messe</a>';
				}
				echo '<a class="dropdown-item" href="Evenements.php?Session='.$_SESSION["Session"].'&action=Prog_Recurrente_Celebration&id=0">Configurer les Célébrations récurrentes</a>';
				
			} else {
				if ($_SESSION["Activite_id"] == 26 OR // Aumônerie Lycée et collège
					$_SESSION["Activite_id"] == 12 OR // Cathéchèse
					$_SESSION["Activite_id"] == 22 OR // Emmaüs
					$_SESSION["Activite_id"] ==  3 OR // Baptême Bébé
					$_SESSION["Activite_id"] ==  4 OR // Alpha Classic
					$_SESSION["Activite_id"] == 46) { // Baptême Adulte
					echo '<a class="dropdown-item" href="'.$ModulePhp.'?Session='.$_SESSION["Session"].'&action=Configuration_Accompagnateur&id=0&Activite='.$_SESSION["Activite_id"].'">Ajouter/Retirer accompagnateur</a>';
				}
			}
		}
		echo '<div class="dropdown-divider"></div>';
		echo '<a class="dropdown-item" href="'.$ModulePhp.'?Session='.$_SESSION["Session"].'&action=edit_Individu&id=0">Créer un nouveau paroissien dans la base</a>';
		echo '</div>';
		echo '</li>';

	} else {
		?>
		<li class="nav-item">
		<a class="nav-link disabled" href="#">Ajouter</a>
		</li>
		<?php
	}
		
	//============================
	// Divers
	//============================
	if (fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 20 AND $_SERVER['PHP_SELF'] != "/index.php" AND ($_SESSION["Activite_id"]==2 OR $_SESSION["Activite_id"]==3)) {
		?>		
		<li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle" style="color:white" href="http://example.com" id="navbarServicesLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Divers
		</a>
		<div class="dropdown-menu" aria-labelledby="navbarServicesLink">
		<?php

		if ($_SESSION["Activite_id"]==2) { // Préparation Mariage
			echo '<a class="dropdown-item" href="http://nd.sagesse.free.fr/Celebration">Préparation célébration</a>';
			echo '<a class="dropdown-item" href="/Chants_Mariage/index.htm">Chants célébration</a>';
			echo '<a class="dropdown-item" href="Reference/Carnet de note V6.pdf">Questionnaire du livret</a>';
			
		}elseif ($_SESSION["Activite_id"]==3) { // Baptême bébé	
			echo '<a class="dropdown-item" href="Reference/Bapteme/0_La preparation bapteme en 6 etapes.pdf">0-Prépa Baptême en 6 rencontres</a>';
			echo '<a class="dropdown-item" href="Reference/Bapteme/0_Qui_fait_quoi_repartition_des_taches.pdf">0-Répartition des tâches</a>';
			echo '<a class="dropdown-item" href="Reference/Bapteme/1_Introduction_1ere soiree_topo JeCrois.pdf">1- Introduction 1ère rencontre</a>';
			echo '<a class="dropdown-item" href="Reference/Bapteme/1_Questionnaire pour la seconde soiree.pdf">1- Questionnaire pour les couples</a>';
			echo '<a class="dropdown-item" href="Reference/Bapteme/1_Role_Parrain_Marraine.pdf">1-Rôle des parrains marraines</a>';
			echo '<a class="dropdown-item" href="http://public.ndsagesse.com/fr/training/2014-09-14_Bapteme-a-Sophia-Antipolis/">3-Video Signes</a>';
			echo '<a class="dropdown-item" href="Reference/Bapteme/3_Attestation Bapteme.pdf">4-Attestation Prépa Baptême</a>';
			echo '<a class="dropdown-item" href="http://ndbapteme.azurewebsites.net/#">5-Prépa célébration Baptême</a>';
			echo '<a class="dropdown-item" href="Reference/Bapteme/Images_du_kerycube.docm">6-Images du Kérycube</a>';
			
			echo '</div>';
			echo '</li>';
		
		} elseif ($_SESSION["Activite_id"] == 26 || // Aumônerie Lycée et collège
			      $_SESSION["Activite_id"] == 12) { // Cathéchèse
			echo '<a href="'.$_SERVER['PHP_SELF'].'?action=Inviter">Requete invitation nouveaux</a>';									
		} else {
			?>
			<li class="nav-item">
			<a class="nav-link disabled" href="#">Divers</a>
			</li>
			<?php
		}

	} else {
		?>
		<li class="nav-item">
		<a class="nav-link disabled" href="#">Divers</a>
		</li>
		<?php
	}
	
	// Comptabilité
	if (fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 20 AND 
		$_SERVER['PHP_SELF'] != "/index.php" AND 
		$_SERVER['PHP_SELF'] != "/index2.php" AND $_SESSION["Activite_id"]!=86) { // Messe et 
		?>		
		<li class="nav-item">
		<?php
		echo '<a class="nav-link" style="color:white" href="'.$ModulePhp.'?Session='.$_SESSION["Session"].'&action=vue_financiere">Comptabilité</a>';
		echo '</li>';
	}


?>

		<!-- SELECTION ANNEE -->
		<form method="post" action="#">
		<?php
		echo "&nbsp&nbsp";
	  	echo '<SELECT class="btn btn-primary btn-sm bg-dark" name="SessionSelection" onchange="this.form.submit()">';
		echo '<option value="All">All</option>';
		for ($i=2006; $i<=(intval(date("Y"))+5); $i++) {
			if ($i == intval($_SESSION["Session"])) {
				echo '<option value="'.$i.'" selected="selected">'.($i-1).' - '.$i.'</option>';
			} else {
				echo '<option value="'.$i.'">'.($i-1).' - '.$i.'</option>';
			}
		}
		echo '</SELECT></font></TD>';
		?>
		</form>
		<!-- SELECTION ANNEE -->
	
		<!-- SERVICES -->
		<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" style="color:white" href="http://example.com" id="navbarServicesLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Services
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarServicesLink">
		
		
		<?php
		$Debug=False;
		if (date("n") >= 8) {
			$Annee=date("Y")+1;
		} else {
			$Annee=date("Y");
		}
		
		$requete = '(SELECT id, Nom, Menu_Ordre, Menu_PHP_File 
					 FROM `Activites` WHERE Menu_Ordre > 0)
					UNION ALL
					(SELECT DISTINCT T1.`id`, T1.`Nom`, 99 as Menu_Ordre, T1.`Menu_PHP_File`
					 FROM QuiQuoi T0
					 LEFT JOIN Activites T1 ON T1.`id` = T0.`Activite_id`
					 WHERE MID(T0.`Session`,1,4)="'.$Annee.'" AND (T0.`QuoiQuoi_id`=1 OR T0.`QuoiQuoi_id`=2 ) AND T0.`Individu_id`='.$_SESSION['USER_ID'].' AND ISNULL(T1.`Menu_PHP_File`)
					)
					ORDER BY Menu_Ordre, Nom ASC';
		$Debug=False;
		$result = mysqli_query($eCOM_db, $requete);
		while( $row = mysqli_fetch_assoc( $result)) {
			if (fCOM_Get_Autorization($row['id']) >= 20 AND $row['Nom']!= "Toutes") {
				$ModulePhp = "Fraternite.php";
				if ($row['Menu_PHP_File'] != '') {
					$ModulePhp = substr($row['Menu_PHP_File'],9);
				}
				pCOM_DebugAdd($Debug, "Index:Menu - ".$row['Nom']);
				echo '<a class="dropdown-item" href="'.$ModulePhp.'?Service='.$row['id'].'">'.$row['Nom'].'</a>';
			}
		}
		//if (fCOM_Get_Autorization( 0) >= 20 OR
		//	fCOM_Get_Autorization(87) >= 20 OR  // Accueil Messe
		//	fCOM_Get_Autorization(20) >= 20 OR  // Broadcast
		//	fCOM_Get_Autorization(47) >= 20 OR  // Projection
		//	fCOM_Get_Autorization(16) >= 20 OR  // Sacristie
		//	fCOM_Get_Autorization(51) >= 20 OR  // Animateur
		//	fCOM_Get_Autorization(19) >= 20 OR  // Sono
		//	fCOM_Get_Autorization(14) >= 20 OR  // Eveil à la Foi
		//	fCOM_Get_Autorization(90) >= 20	) { // Garderie
			echo '<div class="dropdown-divider"></div>';
			echo '<a class="dropdown-item" href="Evenements.php?Service=86">Messes et célébrations</a>';
		//}	
		?>
        </div>
      </li>
	  


	<!-- BAPTEME ADULTE -->	
	
	<!-- MARIAGE -->	
	
	<!-- BAPTEME BEBE -->	
	
	
    </ul>
	
	<?php
	if ($Gestionnaire == True) {
		?>
		<!-- SEARCH -->	
		<form class="form-inline my-2 my-lg-0" action="SuiviParoissien.php" method=POST>
		<input class="form-control form-control-sm mr-sm-1" type="search" name="any" placeholder="Rechercher paroissien" aria-label="Search">
		<button class="btn btn-outline-primary btn-sm" type="submit" value="Lancer recherche" >Search</button>
		</form>
		<!-- SEARCH -->	
		<?php
	}
	?>
	

  </div>
</nav>
</div>
</div>


<?php

 
}


function fMENU_Title ($pTitle1, $pTitle2="") {

//echo '<TR BGCOLOR="#F7F7F7"><TD>';
//echo '<B>&nbsp'.$pTitle1.'</B></TD>';
//echo '<TD align="right">';
//echo $pTitle2;
//echo '</TD></TR>';

echo '<div class="row">';
echo '<div class="col">';
echo '<B>'.$pTitle1.'</B>';
echo '</div>';
echo '<div class="col" align="right">';
echo $pTitle2;
echo '</div>';
echo '</div>';
//
}


function fMENU_bottom () {

Global $eCOM_db;

$niveau=fCOM_Get_Autorization(0);
// compter le nombre de personne connecté
$sql3='SELECT * FROM Admin_user_online';
$result3=mysqli_query($eCOM_db, $sql3);
$count_user_online=mysqli_num_rows($result3);

$sql3='SELECT sum(membre_counter) as Somme FROM Admin_membres';
$result3=mysqli_query($eCOM_db, $sql3);
$row = mysqli_fetch_assoc( $result3);
$count_connection=$row['Somme'];

$Liste_user_connected='';
if ($count_user_online >= 1) {
	$sql4='SELECT T0.`id`, T1.`Nom`, T1.`Prenom` 
		   FROM Admin_user_online T0
		   LEFT JOIN Individu T1 ON T1.`id`=T0.`id`
		   ORDER BY T1.`Nom`, T1.`Prenom`';
	$result4=mysqli_query($eCOM_db, $sql4);
	while( $row = mysqli_fetch_assoc( $result4)) {
		$Liste_user_connected=$Liste_user_connected.'- '.$row['Nom'].' '.$row['Prenom'].'<BR>';
	}
}

?>

<footer class="bg-dark text-white mt-0">
    <div class="container-fluid py-3">
		<div class="row">
			<div class="ml-2 small">
				<a class="btn btn-outline-secondary btn-sm" href="/Login/logout.php" role="button" data-toggle="tooltip" data-placement="top" title="Déconnexion"><?php echo $_SERVER['PHP_AUTH_USER'];?></a>
			</div>
			<div class="mx-auto small">Connexion :&nbsp
			<button type="button" class="btn btn-outline-secondary btn-sm">Compteur <span class="badge badge-light"><?php echo $count_connection;?></span></button> 
			<button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="tooltip" data-placement="top" data-html="true" title="<?php echo $Liste_user_connected; ?>">en cours <span class="badge badge-light"><?php echo $count_user_online;?></span></button>
			</div>
            <div class="mr-2 small">2018 - V2.0</div>
		</div>
    </div>
</footer>

<script type="text/javascript">

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

$(document).ready(function() {
    var table = $('#TableauTrier').DataTable( {
		responsive: true,
		stateSave: true,
		"lengthChange": false,
		"paging" : false,
		language: {
        decimal: ",",
		processing:     "Traitement en cours...",
        search:         "Filtrer&nbsp;:",
        lengthMenu:    "Afficher&nbsp _MENU_ &nbsp&eacute;l&eacute;ments",
        info:           "Affichage de _TOTAL_ &eacute;l&eacute;ments",
        infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
        infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
        infoPostFix:    "",
        loadingRecords: "Chargement en cours...",
        zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
        emptyTable:     "Aucune donnée disponible dans le tableau",
        paginate: {
            first:      "Premier",
            previous:   "Pr&eacute;c&eacute;dent",
            next:       "Suivant",
            last:       "Dernier"
        },
        aria: {
            sortAscending:  ": activer pour trier la colonne par ordre croissant",
            sortDescending: ": activer pour trier la colonne par ordre décroissant"
        }
		}

	} );
 
	$('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
 
        // Get the column API object
        var column = table.column( $(this).attr('data-column') );
 
        // Toggle the visibility
        column.visible( ! column.visible() );
    } );
	
	new $.fn.dataTable.FixedHeader( table );
} );


$(document).ready(function() {
    var table = $('#TableauSansTriero').DataTable( {
		"columnDefs": [{ "targets": [ 0 ],
						  "visible": false }],
		"drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
 
            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td bgcolor="#BBBBBB" colspan="25">'+group+'</td></tr>'
                    );
 
                    last = group;
                }
            } );
        },
		responsive: true,
		stateSave: true,
		"lengthChange": false,
		"paging" : false,
		language: {
        decimal: ",",
		processing:     "Traitement en cours...",
        search:         "Rechercher&nbsp;:",
        lengthMenu:    "Afficher&nbsp _MENU_ &nbsp&eacute;l&eacute;ments",
        info:           "Affichage de _TOTAL_ &eacute;l&eacute;ments",
        infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
        infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
        infoPostFix:    "",
        loadingRecords: "Chargement en cours...",
        zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
        emptyTable:     "Aucune donnée disponible dans le tableau",
        paginate: {
            first:      "Premier",
            previous:   "Pr&eacute;c&eacute;dent",
            next:       "Suivant",
            last:       "Dernier"
        },
        aria: {
            sortAscending:  ": activer pour trier la colonne par ordre croissant",
            sortDescending: ": activer pour trier la colonne par ordre décroissant"
        }
		}

	} );
 
	$('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
 
        // Get the column API object
        var column = table.column( $(this).attr('data-column') );
 
        // Toggle the visibility
        column.visible( ! column.visible() );
    } );
	
	new $.fn.dataTable.FixedHeader( table );
} );

</script>



<?php

echo '</BODY>';
echo '</HTML>';
mysqli_close($eCOM_db);


}
