<?php

session_start(); // créer des variables session $_SESSION[]
require_once("include/fct.inc.php");  // Les fonctions utilisables de partout. 
require_once ("include/class.pdogsb.inc.php"); // 

include("vues/v_entete.php");

$pdo = PdoGsb::getPdoGsb();
$estConnecte = estConnecte();

if (!isset($_REQUEST['uc']) || !$estConnecte) {
    $_REQUEST['uc'] = 'connexion';
}

$uc = $_REQUEST['uc'];

switch ($uc) {
    case 'connexion': {
            include("controleurs/c_connexion.php");
            break;
        }
    case 'validerFicheFrais':{
            include("controleurs/c_valideFrais.php");
            break;
        }
    
    case 'cloturerSaisieFichesFrais' :{
		    include("controleurs/c_clotureFicheSaisie.php");
            break;
    	}
    
}

include("vues/v_pied.php");
?>

