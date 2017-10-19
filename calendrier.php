	<?php
//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//demarrage des sessions****************************************************************************
//----   pour sauvegarder les selections de mois annees mettre en tout debut de page ce code    ----
//                session_start();                                                              ----
//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//session_start(); 

//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//----                  Script de gestion pour calendrier de reservation                        ----
//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//----    Version 2.0                                                                          ----
//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//----     Paramètres de configurations générales et modifiables                               -----
//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------

//nom de la page ou se trouve le script*************************************************************
$adresse_page         = "calendrier.php";

// indiquez le caractère séparateur de date ********************************************************
$format_separateur_date = "/";

$avec_bdd            = false ;

//***************************************************************************************************
// fichier de paramètrage de l'apparence du calendrier
//***************************************************************************************************
// si vous souhaitez avoir une apparence différente pour le calendrier administrateur et
// pour la celndrier visiteurs, il faut créer de fichier parametres_calendrier.php
// et modifier le chemin vers ces fichiers
// d'autres paramètres propres à chaque calendrier peuvent etre sélectionnés au début des fichiers
// calendrier.php, ils permettent de conditionner l'affichage de sélecteur d'année, mois, couleur
// champs de réservation
//***************************************************************************************************

//***************************************************************************************************
//pour personnaliser facilement votre calendrier, rendez vous sur cette page :
//http://www.mathieuweb.fr/calendrier/personnaliser-calendrier.php
//***************************************************************************************************

//déclaration des variables initiales du tableau*****************************************************
$taille_police_mois          = 16;
$couleur_police_mois         = '#FFFFFF';
$taille_police_nom_jour      = 12 ;
$couleur_police_nom_jour     = '#666666';
$taille_police_jour          = 12 ;
$couleur_police_jour         = '#282828';
$police                      = 'Arial';
$nombre_mois_afficher        = 6 ;
$nombre_mois_afficher_ligne  = 3  ;
$decalage_ligne              = 0 ;
$bordure_du_tableau          = 0  ;
// jouer sur ce paramètre pour uniformiser la taille des calendriers
$hauteur_mini_cellule_date   = "17px";
$couleur_bordure_tableau     = "#000000" ;
$largeur_tableau             = "148px"; // 190 il y a quatre mois par ligne
//$largeur_tableau             = "100px";
$espace_entre_cellule        = "1";
$espace_dans_cellule         = "1";
$couleur_nom_numero_semaine  = '#EFF5FC';
$couleur_numero_semaine      = '#8EFB78';
$couleur_jour_semaine        = '#E6EFFB';
$couleur_nom_jour_week_end   = '#FFFFFF';
$couleur_jour_week_end       = '#DAE9F8';
$couleur_fond_mois           = '#ABCDEF';
$largeur_sel_mois_annee      = 60 ;
$taille_police_sel_mois_annee= 14 ;
$couleur_sel_mois_annee      = '#000000';
// couleur libre est également la couleur de fond des dates du calendrier
$couleur_libre               = '#B9CBDD';
$avec_marquage_du_jour_d_aujourd_hui = true;
$couleur_jour_aujourd_hui    = '#FF666';
$couleur_reserve[1]          = '#FF0000';
$intitule_couleur_reserve[1] = "Réservé";
$couleur_texte_jour_reserve[1]= '#FFFFFF';
// si true alors les cellules "vides" des week end et numero semaines seront dans leur couleur respectif
// si false alors les cellules "vides" des week end et numero semaines seront dans la couleur $couleur_libre
$avec_continuite_couleur      = true;
// indiquer en toute lettre le nom du premier jour de la semaine *********************
// lundi, mardi, mercredi, jeudi, vendredi, samedi, dimanche *************************
//attention le numéro de la semaine indiquée sera toujours le numéro de semaine commencant le lundi
$texte_jour_debut_semaine = "lundi";


echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Sélection date</title>
<meta name="generator" content="mathieuweb http://www.mathieuweb.fr/calendrier/calendrier.php"> ';

//style pour lien sur numero de jour dans le calendrier*********************************************
echo '<style type="text/css"> a.date:link { color: ',$couleur_police_jour,'; } a.date:visited { color: ',$couleur_police_jour,'; text-decoration: underline; } a.date:active { color: ',$couleur_police_jour,'; text-decoration: underline;} a.date:hover { color: ',$couleur_police_jour,'; text-decoration: underline; } </style>';
foreach ($couleur_texte_jour_reserve as $cle => $val_couleur )  {
//style pour lien sur numero de jour dans le calendrier*********************************************
echo '<style type="text/css"> a.date',$cle,':link { color: ',$val_couleur,'; } a.date',$cle,':visited { color: ',$val_couleur,'; text-decoration: underline; } a.date',$cle,':active { color: ',$val_couleur,'; text-decoration: underline;} a.date',$cle,':hover { color: ',$val_couleur,'; text-decoration: underline; } </style>';
}
//style pour lien sur selection mois annee**********************************************************
echo '<style type="text/css"> a.selection:link { color: ',$couleur_sel_mois_annee,'; text-decoration: none; } a.selection:visited { color: ',$couleur_sel_mois_annee,'; text-decoration: none; } a.selection:active { color: ',$couleur_sel_mois_annee,'; text-decoration: none;} a.selection:hover { color: ',$couleur_sel_mois_annee,'; text-decoration: none; } </style>';
echo '
</head>
<body bgcolor="#FFFFFF" text="#000000">';

//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
// sélection de l'affichage des modules ************************************************************
//avec selection possible du mois--------------------------------------------------------------------
$selection_mois    = true ;
//avec selection possible des annnées----------------------------------------------------------------
$selection_an      = true ;

//format de date sur le lien des jours dans le calendrier--------------------------------------------
// si true alors selection format francais, si false alors format date anglais-----------------------
$format_date_fr    = true ;

//déclaration des noms des mois et jours en francais************************************************
$mois_fr           = Array ( "", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Décembre" );
$jour_fr           = Array ( "Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa", "Di", "S" );
//déclaration des noms des mois et jours en allemand************************************************
$mois_all          = Array ( "", "Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember" );
$jour_all          = Array ( "So", "Mo", "Di", "Mi", "Do", "Fr", "Sa", "So", "W" );
//déclaration des noms des mois et jours en anglais*************************************************
$mois_eng          = Array ( "", "Jaunary", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" );
$jour_eng          = Array ( "Su", "Mo", "Tu", "We", "Th", "Fr", "Sa", "Su", "W" );
//déclaration des noms des mois et jours en italien*************************************************
$mois_it           = Array ( "", "Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre" );
$jour_it           = Array ( "Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa", "Do", "S" );
//déclaration des noms des mois et jours en espagnol*************************************************
$mois_esp           = Array ( "", "Enero", "FebreroO", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" );
$jour_esp           = Array ( "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa", "Do", "S" );

//langue par défaut*********************************************************************************
if ( !(isset($_SESSION['langue'])) || ((empty($_SESSION['langue']))) )
$langue = 'fr' ;
//controle si choix de la langue dans l'url*********************************************************
if ( (isset($_GET['langue'])) && (!(empty($_GET['langue']))) )
    $_SESSION['langue'] = $_GET['langue'] ;
//si session langue existe alors la langue de la session devient prioritaire************************
if ( (isset($_SESSION['langue'])) && (!(empty($_SESSION['langue']))) )
   $langue = $_SESSION['langue'];
//sélection des tableaux suivant la langue choisie**************************************************
if ( $langue == 'fr' ) {
     $mois_texte = $mois_fr ;
     $jour_texte = $jour_fr ; }
if ( $langue == 'all' ) {
     $mois_texte = $mois_all ;
     $jour_texte = $jour_all ; }
if ( $langue == 'eng' ) {
     $mois_texte = $mois_eng ;
     $jour_texte = $jour_eng ; }
if ( $langue == 'it' ) {
     $mois_texte = $mois_it  ;
     $jour_texte = $jour_it ; }
if ( $langue == 'esp' ) {
     $mois_texte = $mois_esp  ;
     $jour_texte = $jour_esp ; }


$date_lien = 0;

//choix du mois*************************************************************************************
$selection_mois_depart = 0;
$offset_annee          = 0;
$premier_mois       = date ("m") + $selection_mois_depart;
if ($premier_mois >12) {
    $premier_mois = 1;
    $offset_annee = 1; }
if ($premier_mois < 1) {
    $premier_mois = 12; 
    $offset_annee = -1; }

//controle si choix du mois dans l'url**************************************************************
if ( (isset($_GET['mois'])) && (empty($_GET['mois'])) )
    $_SESSION['mois'] = '' ;
if ( (isset($_GET['mois'])) && (!(empty($_GET['mois']))) )
    $_SESSION['mois'] = $_GET['mois'] ;
//si session mois existe alors la session devient prioritaire***************************************
if ( (isset($_SESSION['mois'])) && (!(empty($_SESSION['mois']))) )
   $premier_mois = $_SESSION['mois'] ;

//choix de l'année**********************************************************************************
$annee_premier_mois       = date ("Y") + $offset_annee ;
//controle si choix de l'année dans l'url***********************************************************
if ( (isset($_GET['an'])) && (empty($_GET['an'])) )
    $_SESSION['an'] = '' ;
if ( (isset($_GET['an'])) && (!(empty($_GET['an']))) )
    $_SESSION['an'] = $_GET['an'] ;
//si session année existe alors la session devient prioritaire**************************************
if ( (isset($_SESSION['an'])) && (!(empty($_SESSION['an']))) )
   $annee_premier_mois = $_SESSION['an'] ;

//controle si choix nom du champs dans lequel la date cliquée doit être inscrite dans l'url*********
if ( (isset($_GET['idcible'])) && (empty($_GET['idcible'])) )
    $_SESSION['idcible'] = '' ;
if ( (isset($_GET['idcible'])) && (!(empty($_GET['idcible']))) )
    $_SESSION['idcible'] = $_GET['idcible'] ;
//si session mois existe alors la session devient prioritaire***************************************
if ( (isset($_SESSION['idcible'])) && (!(empty($_SESSION['idcible']))) )
   $nom_champs_selecteur = $_SESSION['idcible'] ;

function jour_debut_semaine ($jour,$mois ,$annee) {
  $premier_jour_mois = date("w",mktime ( 0,0,0,$mois ,1,$annee)) ;
  switch ($jour) {
    case "lundi":
    if ( $premier_jour_mois == 0)
       $premier_jour_mois = 7;
    break;
    case "mardi":
     $premier_jour_mois = $premier_jour_mois + 6;
     if ( $premier_jour_mois > 7)
     $premier_jour_mois = $premier_jour_mois - 7;
    break;
    case "mercredi":
     $premier_jour_mois = $premier_jour_mois + 5;
     if ( $premier_jour_mois > 7)
     $premier_jour_mois = $premier_jour_mois - 7 ;
    break;
    case "jeudi":
     $premier_jour_mois = $premier_jour_mois + 4;
     if ( $premier_jour_mois > 7)
     $premier_jour_mois = $premier_jour_mois - 7 ;
    break;
    case "vendredi":
     $premier_jour_mois = $premier_jour_mois + 3;
     if ( $premier_jour_mois > 7)
     $premier_jour_mois = $premier_jour_mois - 7 ;
    break;
    case "samedi":
     $premier_jour_mois = $premier_jour_mois + 2;
     if ( $premier_jour_mois > 7)
     $premier_jour_mois = $premier_jour_mois - 7 ;
    break;
    case "dimanche":
     $premier_jour_mois  = $premier_jour_mois + 1;
     if ( $premier_jour_mois > 7)
     $premier_jour_mois = $premier_jour_mois - 7 ;
    break;
    }
  return ($premier_jour_mois);
  }
  

function correction_debut_semaine ($jour,$cle) {
  global $index_jour_lundi;
  global $index_jour_samedi;
  global $index_jour_dimanche;
  $nouvelle_cle = $cle ;
  switch ($jour) {
    case "lundi":
      $nouvelle_cle = $cle ;
    break;
    case "mardi":
      if ( $cle < 8)
      $nouvelle_cle = $cle + 1;
      if ( $nouvelle_cle >= 7)
         $nouvelle_cle = $nouvelle_cle - 7;
      if ( $cle > 7)
      $nouvelle_cle = $cle ;
    break;
    case "mercredi":
      if ( $cle < 8)
      $nouvelle_cle = $cle + 2;
      if ( $nouvelle_cle >= 7)
         $nouvelle_cle = $nouvelle_cle - 7;
      if ( $cle > 7)
      $nouvelle_cle = $cle ;
    break;
    case "jeudi":
      if ( $cle < 8)
      $nouvelle_cle = $cle + 3;
      if ( $nouvelle_cle >= 7)
         $nouvelle_cle = $nouvelle_cle - 7;
      if ( $cle > 7)
      $nouvelle_cle = $cle ;
    break;
    case "vendredi":
      if ( $cle < 8)
      $nouvelle_cle = $cle + 4;
      if ( $nouvelle_cle >= 7)
         $nouvelle_cle = $nouvelle_cle - 7;
      if ( $cle > 7)
      $nouvelle_cle = $cle ;
    break;
    case "samedi":
      if ( $cle < 8)
      $nouvelle_cle = $cle + 5;
      if ( $nouvelle_cle >= 7)
         $nouvelle_cle = $nouvelle_cle - 7;
      if ( $cle > 7)
      $nouvelle_cle = $cle ;
    break;
    case "dimanche":
      if ( $cle < 8)
      $nouvelle_cle = $cle + 6;
      if ( $nouvelle_cle >= 7)
         $nouvelle_cle = $nouvelle_cle - 7;
      if ( $cle > 7)
      $nouvelle_cle = $cle ;
    break;
    }
  //recherche index du lundi 
  if ( $nouvelle_cle == 1 )
      $index_jour_lundi = $cle;
    //recherche index du samedi
  if ( $nouvelle_cle == 6 )
      $index_jour_samedi = $cle;
    //recherche index du dimanche
  if ( $nouvelle_cle == 0 || $nouvelle_cle == 7)
      $index_jour_dimanche = $cle;
  return ($nouvelle_cle);
  }

//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//----     Ne plus rein modifié                                                                -----
//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------

$largeur_div = $largeur_tableau * $nombre_mois_afficher_ligne ;

//controle si utilisation avec base de données******************************************************
if ( $avec_bdd) {
  // chemin vers fichier identifiant de connection a la base de donnees*******************************
  include($_SERVER["DOCUMENT_ROOT"].$fichier_connect_bdd);

  //connection a la base de donnees*******************************************************************
  $connect = mysql_connect($hote, $user, $password);
  mysql_select_db($base, $connect);
  }

//selection du mois et année en cours***************************************************************
$mois_en_cours  = (int)$premier_mois ;
$annee_en_cours = $annee_premier_mois ;

// affichage sélection mois, année, couleur et champs de réservations ********************************
   // si nécessaire affichage du sélecteur d'année **************************************************
   if ( $selection_an ) {
        echo '<a href="',$adresse_page,'?an=',$annee_en_cours - 1, '&mois='. $premier_mois.'&nom_champs_selecteur=',$nom_champs_selecteur, '" class = selection><font style="font-size:',$taille_police_sel_mois_annee,'px" color="',$couleur_sel_mois_annee,'" face="',$police,'" >&nbsp;<< </a></font>';
        echo '<b><font style="font-size:',$taille_police_sel_mois_annee,'px" color="',$couleur_sel_mois_annee,'" face="',$police,'" >&nbsp;',$annee_en_cours,'&nbsp;</font></b>';
        echo '<a href="',$adresse_page,'?an=',$annee_en_cours + 1, '&mois='. $premier_mois.'&nom_champs_selecteur=',$nom_champs_selecteur, '" class = selection><font style="font-size:',$taille_police_sel_mois_annee,'px" color="',$couleur_sel_mois_annee,'" face="',$police,'" >&nbsp;>> </a></font>';
        }
//echo '<br>';
   // si nécessaire affichage du sélecteur de mois **********************************************
   if ( $selection_mois ) {
        echo '<form name="sel_mois" method="get" action="',$adresse_page,'" id="Form1">';
        echo '<select name="mois" size="1" id="Combobox1" onchange="document.sel_mois.submit();return false;" style="position:font-family:',$police,';font-size:',$taille_police_sel_mois_annee,'px;z-index:2">';
        //echo '<select name="mois" size="1" id="Combobox1" onchange="document.sel_mois.submit();nom_champs_selecteur='$nom_champs_selecteur';return false;" style="position:font-family:',$police,';font-size:',$taille_police_sel_mois_annee,'px;z-index:2">';
        for ($i=1; $i<13; $i++)  {
            if  ( $premier_mois == $i )
                  echo '<option selected value="',$i,'">',$mois_texte[$i],'</option>' ;
             else
                  echo '<option value="',$i,'">',$mois_texte[$i],'</option>' ;
        }
		echo '<input type="hidden" name="an" value="',$annee_en_cours,'">';
		echo '<input type="hidden" name="nom_champs_selecteur" value="',$nom_champs_selecteur,'">';
        echo '</select>';
        echo '</form>';
        }


echo '<form name="Calendar">  ';


//initailisation compteur de mois par ligne*********************************************************
$compteur_mois_ligne = 1 ;

echo '<table >';
echo '<tr>';
echo '<td>';
echo '<div style="width:',$largeur_div,'px;">';

//affichage des tableaux des mois desirés***********************************************************
for ( $compteur_mois = 1; $compteur_mois <= $nombre_mois_afficher; $compteur_mois++ )
 {
   $compteur_mois_ligne = $compteur_mois_ligne + 1 ;

//creation du tableau des mois**********************************************************************
echo '<table cellPadding="',$espace_entre_cellule,'" cellSpacing="',$espace_dans_cellule,'" style = "width:',$largeur_tableau,'px;border :',$couleur_bordure_tableau,' ',$bordure_du_tableau,'px solid " align="left">';
//affichage du mois*********************************************************************************
echo '<TR><TD align=center bgColor=',$couleur_fond_mois,' colspan = 8><b><font style="font-size:',$taille_police_mois,'px" color="',$couleur_police_mois,'" face="',$police,'" >',$mois_texte[$mois_en_cours],' ',$annee_en_cours,'</b></font></TD></TR>';

//affichage nom des jours et numéro de semaine******************************************************
echo '<TR>';
//temporaire pour initailisation variable globales
for ($j=1; $j<9; $j++)
     $tempor = $jour_texte[correction_debut_semaine ($texte_jour_debut_semaine,$j)];
for ($j=1; $j<9; $j++)
     {
       if  ($j == $index_jour_samedi || $j == $index_jour_dimanche)
          $couleur_fond_nom_jour = $couleur_nom_jour_week_end;
       elseif ( $j == 8)
          $couleur_fond_nom_jour = $couleur_nom_numero_semaine;
        else
          $couleur_fond_nom_jour = $couleur_jour_semaine ;
       echo '<TD align = center bgColor=',$couleur_fond_nom_jour,'><font style="font-size:',$taille_police_nom_jour,'px" color="',$couleur_police_nom_jour,'" face="',$police,'" >',$jour_texte[correction_debut_semaine ($texte_jour_debut_semaine,$j)],'</font></td>';
     }
echo '</TR>';

//initialisation des calendriers*******************************************************************
$fin_tableau              = false ;
$premier_jour_depasse     = false ;
$numero_premier_jour_mois = jour_debut_semaine ($texte_jour_debut_semaine,$mois_en_cours ,$annee_en_cours) ;
$temp_annee_mois_suivant  = $annee_en_cours ;
$temp_mois_suivant        = $mois_en_cours + 1 ;
if ( $temp_mois_suivant > 12 )  {
    $temp_mois_suivant = 1;
    $temp_annee_mois_suivant++;
    }
$numero_dernier_jour_mois = strftime("%d",mktime ( 0,0,0,$temp_mois_suivant ,0,$temp_annee_mois_suivant)) ;
$compteur_jour            = 1 ;
//variable pour uniformiser la taille des tableau mois en nombre de ligne pour tous les mois *******
$compteur_ligne           = 0 ;
$lundi_trouve = false;

//creation du tableau avec numero des jours*********************************************************
while ( !($fin_tableau) )
      {
        echo '<TR>';
        $compteur_ligne++;
        $au_moins_une_date_sur_la_ligne = false;
        //creation des cases par semaine************************************************************
        for ($j=1; $j<9; $j++)
             {
              $couleur_disponibilite = $couleur_libre ;
              //Test pour debut tableau pour premier jour du mois***********************************
              if ( $numero_premier_jour_mois == $j  )
                  $premier_jour_depasse = true ;
              if ( $premier_jour_depasse && ($compteur_jour <= $numero_dernier_jour_mois) && $j < 8)
                  {
                    $coul_police_jour = $couleur_police_jour ;
                    if ( $j == $index_jour_samedi || $j == $index_jour_dimanche)
                        $couleur_disponibilite = $couleur_jour_week_end ;
                    // test si le jour affiché correspond au jour d'aujourd'hui *******************
                    if ( $avec_marquage_du_jour_d_aujourd_hui ) {
                        $date_aujourd_hui = date("Y")."-".(int)date("m")."-".(int)date("d");
                        $jour_aujourd_hui = $annee_en_cours."-".$mois_en_cours."-".$compteur_jour;
                        if ( $date_aujourd_hui ==  $jour_aujourd_hui )
                            $couleur_disponibilite = $couleur_jour_aujourd_hui ;
			    $coul_police_jour = $couleur_police_mois ;
                       }
                    //$coul_police_jour = $couleur_police_jour ;
                    $class_date_lien = '' ;
                    echo '<TD bgColor=',$couleur_disponibilite,' align=center><font style="font-size:',$taille_police_jour,'px" color="',$coul_police_jour,'" face="',$police,'" >';
                    if ( $date_lien == 0 && $format_date_fr)
                        echo '<a style="text-decoration:none" href="javascript:window.opener.document.getElementById(\'',$nom_champs_selecteur,'\').value=\'',$compteur_jour,'',$format_separateur_date,'',$mois_en_cours,'',$format_separateur_date,'',$annee_en_cours,'\';;window.close();" class = date',$class_date_lien,' >';
                    if ( $date_lien == 0 && (!($format_date_fr)) )
                        echo '<a style="text-decoration:none" href="javascript:window.opener.document.getElementById(\'',$nom_champs_selecteur,'\').value=\'',$annee_en_cours,'',$format_separateur_date,'',$mois_en_cours,'',$format_separateur_date,'',$compteur_jour,'\';;window.close();" class = date',$class_date_lien,' >';
                    //memoire date du lundi de la semaine en cours ****************************************
                    //recherche de la date du lundi de la semaine
                    if ( $j == $index_jour_lundi )  {
                        $memoire_numero_premier_jour_sem_en_cours =  $compteur_jour;
                        $memoire_numero_mois_premier_jour_sem_en_cours =  $mois_en_cours;
                        $memoire_numero_annee_premier_jour_sem_en_cours =  $annee_en_cours;
                        $lundi_trouve = true;
                        }
                    echo $compteur_jour;
                    if ( $date_lien == 0 )
                        echo '</a>';
                    echo '</TD>';
                    $compteur_jour++ ;
                    $au_moins_une_date_sur_la_ligne = true ;
                  }
              elseif  ( $j == 8  && $au_moins_une_date_sur_la_ligne)  {
                    //indique numéro de semaine*************************************************************************************************
                    if ( !$lundi_trouve && $compteur_ligne == 1) {  // si aucun lundi dans premier ligne, calcul numéro semaine sur dernier lundi du mois précédent****
                    $temp_mois_precedent = $mois_en_cours -1 ;
                    $temp_annee_precedent = $annee_en_cours ;
                    if  ( $temp_mois_precedent <= 0 ) {
                      $temp_mois_precedent = 12;
                      $temp_annee_precedent = $annee_en_cours - 1 ;
                      }
                    $numero_dernier_jour_calcul_semaine = strftime("%d",mktime ( 0,0,0,$mois_en_cours,0,$temp_annee_precedent)) ;
                    $premiere_boucle_recherche_lundi = true;
                    while ( !$lundi_trouve) {
                        if ( !$premiere_boucle_recherche_lundi )
                           $numero_dernier_jour_calcul_semaine = $numero_dernier_jour_calcul_semaine - 1 ;
                        $premiere_boucle_recherche_lundi = false;
                        $nom_jour_temp_calcul_semaine = strftime("%a",mktime ( 0,0,0,$temp_mois_precedent,$numero_dernier_jour_calcul_semaine,$temp_annee_precedent)) ;
                        if ( $nom_jour_temp_calcul_semaine == "Mon" ) {
                        $memoire_numero_premier_jour_sem_en_cours =  $numero_dernier_jour_calcul_semaine;
                        $memoire_numero_mois_premier_jour_sem_en_cours =  $temp_mois_precedent;
                        $memoire_numero_annee_premier_jour_sem_en_cours =  $temp_annee_precedent;
                        $lundi_trouve = true;
                           }
                        }
                      }
                    $temp_semaine_en_cours = date("W",mktime ( 0,0,0,$memoire_numero_mois_premier_jour_sem_en_cours ,$memoire_numero_premier_jour_sem_en_cours ,$memoire_numero_annee_premier_jour_sem_en_cours ));
                    echo '<TD bgColor=',$couleur_numero_semaine,' align=center><font style="font-size:',$taille_police_jour,'px" color="',$couleur_police_jour,'" face="',$police,'" >';
                    $lundi_trouve = false;
                    echo $temp_semaine_en_cours;
                    echo '</td>';
                    }
              else  {
                     if ( ( $j == $index_jour_samedi || $j == $index_jour_dimanche)  && $avec_continuite_couleur )
                        $couleur_disponibilite = $couleur_jour_week_end ;
                    if ( $j == 8 && $avec_continuite_couleur )
                        $couleur_disponibilite = $couleur_numero_semaine ;
                    echo '<TD bgColor=',$couleur_disponibilite,' height="',$hauteur_mini_cellule_date,'"></TD>';
                    }
             }
        echo '</TR>';
        if ( $compteur_jour > $numero_dernier_jour_mois && $compteur_ligne >= 6)
                        $fin_tableau = true ;
      }
//fin de la table du mois
echo '</TABLE>';

//incrementation du mois et annee en cours********************************************************
$mois_en_cours = $mois_en_cours + 1;
if ( $mois_en_cours > 12 )
    {
     $mois_en_cours = 1;
     $annee_en_cours = $annee_en_cours + 1 ;
    }
 if ( $compteur_mois_ligne > $nombre_mois_afficher_ligne )
    {
     echo '</tr></td><tr><td>';
     //echo '</td></tr><tr><td>';
    $compteur_mois_ligne = 1;
    }
 }
//fin de paragraphe du tableau*********************************************************************
echo '</div>';
echo '</td>';
echo '</tr>';
echo '</table>';

echo '</form>';

echo '</body>';

?>