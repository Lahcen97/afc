<?php
$action = $_REQUEST['action'];

switch($action){
	case 'choixInitialVisiteur':{
		include("vues/v_valideFraisChoixVisiteur.php");
		break;
	}

    case 'afficherFicheFraisSelectionnee':{
        require_once './Include/class.FicheFrais.inc.php';
        $_SESSION['visiteur_name']=$_POST["visiteur_name"];
        $_SESSION['txtMoisFiche']=$_POST['txtMoisFiche'];

        $ficheFrais=new FicheFrais($_SESSION['visiteur_name'],$_SESSION['txtMoisFiche']);
        $fraisForfait=$ficheFrais->initAvecInfosBDD();
        // CETTE METHODE PERMET DE GENRER une liste de frais forfaitisé et frais hors forfait dans la classe FicheFrais.
        $lesFraisForfaitises=$ficheFrais->getLesFraisForfaitises();
        $lesFraisHorsForfaitises=$ficheFrais->getLesFraisHorsForfait();
        // Générer les frais hors forfait
        $montant=$ficheFrais->calculerLeMontantValide();
        echo " le montant total est :" . $montant;
        include("vues/v_valideFraisChoixVisiteur.php");
        include("vues/v_valideFraisCorpsFiche.php");          
        break;
    }

    case 'enregModifFF':
        { 
            require_once './Include/class.FicheFrais.inc.php';
            require_once './Include/class.pdogsb.inc.php';
           
            if (isset($_POST["btnEnregistrerFF"]))
            {
                $ficheFrais=new FicheFrais($_SESSION['visiteur_name'],$_SESSION['txtMoisFiche']);
                $ficheFrais->initAvecInfosBDDSansFF();
                // ajouter les frais forfaitisés
                $TableQuantites=
                [["ETP",$_POST["txtEtape"]]
                ,["KM",$_POST["txtKm"]]
                ,["NUI",$_POST["txtNuitee"]]
                ,["REP",$_POST["txtRepas"]]];

                foreach ($TableQuantites as $value) {
                    $ficheFrais->ajouterUnFraisForfaitise($value[0], $value[1]) ; 
                }
 
                if ($ficheFrais->controlerQtesFraisForfaitises()){                   
                    $ficheFrais->mettreAJourLesFraisForfaitises();
                    $lesFraisForfaitises=$ficheFrais->getLesFraisForfaitises();
                    $lesFraisHorsForfaitises=$ficheFrais->getLesFraisHorsForfait();
                    $montant=$ficheFrais->calculerLeMontantValide();
                    echo " le montant total est :" . $montant;

                    include("vues/v_valideFraisChoixVisiteur.php");
                    include("vues/v_valideFraisCorpsFiche.php");
                } else {
                echo "La quantité de frais doit strictement positif";
            }

        }
    }
    case 'enregModifFHF':  
        {
            require_once './Include/class.FicheFrais.inc.php';
            require_once './Include/class.pdogsb.inc.php';

            if (isset($_POST['btnEnregistrerModifFHF'])){
                $ficheFrais=new FicheFrais($_SESSION['visiteur_name'],$_SESSION['txtMoisFiche']);
                $ficheFrais->initAvecInfosBDDSansFHF();
                $ficheFrais->setNbJustJusitificatifs($_POST["txtHFNbJustificatifsPEC"]);
                if ($ficheFrais->controlerNbJustificatifs()){
                    $tableDeModificationFHF=$_POST["tableau"];
                    foreach ($tableDeModificationFHF as $key => $value){
                        // ajouter objet par objet des frais hors forfait modifier.
                        $ficheFrais->ajouterUnFraisHorsForfait($value["hiddenHFNum"],$value["txtHFLibelle"], $value["txtHFDate"],$value["txtHFMontant"], $value["rbHFaction"] );              
                    } 
                    // mettre à jour les frais 
                    $nbJustificatif=$ficheFrais->getNbJustificatifs();
                    echo " le nombre de justificatifs est :( " . $nbJustificatif .")";  
                    $ficheFrais->mettreAJourLesFraisHorsForfait();
                    // CETTE METHODE PERMET DE GENRER une liste de frais forfaitisé et frais hors forfait dans la classe FicheFrais.
                    
                    $lesFraisForfaitises=$ficheFrais->getLesFraisForfaitises();
                    
                    // Générer les frais hors forfait
                    $lesFraisHorsForfaitises=$ficheFrais->getLesFraisHorsForfait();
                   
                    $montant=$ficheFrais->calculerLeMontantValide();
                    echo " le montant total est :" . $montant;
                    include("vues/v_valideFraisChoixVisiteur.php");
                    include("vues/v_valideFraisCorpsFiche.php");    
                }else {
                    echo "<p style='color:red' > le nombre de justificatif est incorrect. </p>";
                }   
            }
        }
    
    case 'validerFicheFrais':
        {
            require_once './Include/class.FicheFrais.inc.php';
            require_once './Include/class.pdogsb.inc.php';
            if (isset($_POST['btnValiderFiche'])){
                $ficheFrais=new FicheFrais($_SESSION['visiteur_name'],$_SESSION['txtMoisFiche']);
                $ficheFrais->valider();
                $ficheFrais->initAvecInfosBDD();
                // CETTE METHODE PERMET DE GENRER une liste de frais forfaitisé et frais hors forfait dans la classe FicheFrais.
                $lesFraisForfaitises=$ficheFrais->getLesFraisForfaitises();
                // Générer les frais hors forfait
                $lesFraisHorsForfaitises=$ficheFrais->getLesFraisHorsForfait();
                $montant=$ficheFrais->calculerLeMontantValide();
                
                include("vues/v_valideFraisChoixVisiteur.php");
                include("vues/v_valideFraisCorpsFiche.php");
    
            }
           
        }
   
}
    