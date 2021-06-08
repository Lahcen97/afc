<!-- Division pour le sommaire -->
<div id="menuGauche">
    <div id="infosUtil">
        <h2>
            Agent comptable :<br />
            <?php echo $prenom . " " . $nom . "\n"; ?>
        </h2>
    </div>
    <ul id="menuList">
        <li>
        </li>
        <li class="smenu">
            <a href="index.php?uc=validerFicheFrais&action=choixInitialVisiteur" title="Saisie fiche de frais ">Valider les fiches de frais</a>
        </li>
        <li class="smenu">
            <a href="index.php?uc=cloturerSaisieFichesFrais&action=demanderConfirmationClotureFiches" title="Se déconnecter">Clôturer  la saisie des fiches de frais  </a>
        </li>
        <li class="smenu">
            <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
        </li>
    </ul>
</div>
