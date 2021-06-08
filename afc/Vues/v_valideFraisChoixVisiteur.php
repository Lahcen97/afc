<div id="contenu">
    <h2>Validation d'une fiche de frais visiteur</h2>
    <br />
    <form name="frmChoixVisiteurMoisFiche" id="frmChoixVisiteurMoisFiche" method="post"
        action="index.php?uc=validerFicheFrais&action=afficherFicheFraisSelectionnee">
 
        <?php
        require_once ("include/class.pdogsb.inc.php");
        require_once ("include/Bibliotheque01.inc.php");
        $ligne=$pdo->getVisiteurs();
       
        if (isset($_SESSION['visiteur_name'])){
            echo formSelectDepuisRecordset('Les visiteurs :', 'visiteur_name', '',$ligne,1 ,$_SESSION['visiteur_name']);
        }else{
            echo formSelectDepuisRecordset('Les visiteurs :', 'visiteur_name', '',$ligne,1);
        }
        
        ?>
        <label for="txtMoisFiche">Mois : </label>
        <input value="<?php echo getMoisAnne() ?>" type="text" name="txtMoisFiche" id="txtMoisFiche" readonly="readonly" />
        <input type="submit" id="btnOk" name="btnOk" value="Ok" tabindex="20" />
    </form>
    <br />
