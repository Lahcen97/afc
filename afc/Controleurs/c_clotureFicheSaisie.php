<?php
$action = $_REQUEST['action'];

switch($action){
    
	case 'demanderConfirmationClotureFiches':{
        require_once './Include/class.pdogsb.inc.php';
        require_once './Include/fct.inc.php';
        $pdo = PdoGsb::getPdoGsb();
        $estConnecte = estConnecte();
        $mois=getMoisFichesATraiter();
        $ligne=$pdo->NombreFichACloturer($mois);
        if ($ligne>1){
            include("vues/v_messageOuiNon.php");
        }else {
            $erreur="il y ' a 0  fiche à cloturer pour le mois : " .$mois ;
            include("vues/v_erreurs.php");
        }
       
		
		break;
	}

    case 'traiterReponseClotureFiches':{
        require_once './Include/class.pdogsb.inc.php';
        if (isset($_POST['valider'])){
            $pdo = PdoGsb::getPdoGsb();
            $estConnecte = estConnecte();
            $pdo->CloturerFicheMois($_POST['mois']);
            $message= $_POST["nombreFicheCloture"] .  " fiche(s) ont été clôturée(s).";
            include("vues/v_message.php");
        }
		break;
    }
   
}