<center>
<?php 
    echo "<p class=info> Le nombre fiche à cloturer : ". $ligne[0] . "<br> Voulez vous vraiment cloturer les fiches pour le mois ".$mois." ? </p>";
?>
<form name="frmFraisForfait" id="frmFraisForfait" method="post" action="index.php?uc=cloturerSaisieFichesFrais&action=traiterReponseClotureFiches">
    <input type="hidden" name="mois" value="<?php echo $mois;?>" >
    <input type="hidden" name="nombreFicheCloture" value="<?php echo $ligne[0];?>" >
    <input type="submit" name="valider" value="valider">
    
</form>
<form name="frmFraisForfait" id="frmFraisForfait" method="post" action="index.php?uc=validerFicheFrais&action=choixInitialVisiteur">
    <input type="submit" name="annuler" value="annuler">
</form>
</center>