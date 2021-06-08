<?php

/**
 * Classe d'accès aux données.

 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO
 * $monPdoGsb qui contiendra l'unique instance de la classe

 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */
class PdoGsb {

    private static $serveur = 'sqlsrv:server=DESKTOP-GG7SU0L';
    //private static $bdd='dbname=gsbV2';
    private static $bdd = 'Database=GSB_VALIDE_VMM';
    private static $user = 'afc-vmm';
    private static $mdp = 'afc-vmm';
    private static $monPdo;
    private static $monPdoGsb = null;

    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     *
     * @version 1.1 Utilise self:: en lieu et place de PdoGsb::
     *
     */
    private function __construct() {
        self::$monPdo = new PDO(self::$serveur . ';' . self::$bdd, self::$user, self::$mdp);
        self::$monPdo->query("SET CHARACTER SET utf8");
    }

    public function _destruct() {
        self::$monPdo = null;
    }

    /**
     * Fonction statique qui crée l'unique instance de la classe

     * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();

     * @return l'unique objet de la classe PdoGsb
     *
     * @version 1.1 Utilise self:: en lieu et place de PdoGsb::
     *
     */ 
        
    public static function getPdoGsb() {
        if (self::$monPdoGsb == null) {
            self::$monPdoGsb = new PdoGsb();
        }
        return self::$monPdoGsb;
    }

    /**
     * Retourne les informations d'un visiteur

     * @param $login
     * @param $mdp
     * @return l'id, le nom et le prénom sous la forme d'un tableau associatif
     */
    public function getInfosVisiteur($login, $mdp) {
        $req = "select VIS_ID as id, VIS_NOM as nom, VIS_PRENOM as prenom from visiteur
		where VIS_LOGIN=:login and VIS_MDP=:mdp";
        $rs = PdoGsb::$monPdo->prepare($req);
        $rs->bindParam(':login',$login);
        $rs->bindParam(':mdp',$mdp);
        $rs->execute();
        $ligne = $rs->fetch();
        return $ligne;
        
    }

    public function getInfosComptable($login, $mdp) {
        $req = "select CPT_NUM as id, CPT_NOM as nom, CPT_PRENOM as prenom from COMPTABLE
		where CPT_LOGIN=:login and CPT_MDP=:mdp";
        $rs = PdoGsb::$monPdo->prepare($req);
        $rs->bindParam(':login',$login);
        $rs->bindParam(':mdp',$mdp);
        $rs->execute();
        $ligne = $rs->fetch();
        return $ligne;

    }
    

    public function getVisiteurs() {
        $req = "exec list_visiteur";
        $rs = PdoGsb::$monPdo->prepare($req);
        $rs->execute();
        return $rs;
      }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
     * concernées par les deux arguments

     * La boucle foreach ne peut être utilisée ici car on procède
     * à une modification de la structure itérée - transformation du champ date-

     * @param $idVisiteur
     * @param $mois sous la forme aaaamm
     * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif
     */
    public function getLesFraisHorsForfait($idVisiteur, $mois) {
        $req = "exec getFraisHorsForfait :idVisiteur, :mois ";
        $rs = PdoGsb::$monPdo->prepare($req);
        $rs->bindParam(':idVisiteur',$idVisiteur);
        $rs->bindParam(':mois',$mois);
        $rs->execute();
        $laLigne=$rs->fetchAll();
        return $laLigne;
    }
    
    public function getFicheFrais($idVisiteur, $mois) {
        $req = "exec getFraisHorsForfait :idVisiteur, :mois ";
        $rs = PdoGsb::$monPdo->prepare($req);
        $rs->bindParam(':idVisiteur',$idVisiteur);
        $rs->bindParam(':mois',$mois);
        $rs->execute();
        $laLigne=$rs->fetch(PDO::FETCH_ASSOC);
        return $laLigne;
    }


    public function infoFicheFraisVisiteur($idVisiteur, $mois) {
        $req = "exec fiche_frais_visiteur :mois, :idVisiteur ";
        $rs = PdoGsb::$monPdo->prepare($req);
        $rs->bindParam(':idVisiteur',$idVisiteur);
        $rs->bindParam(':mois',$mois);
        $rs->execute();
        $laLigne=$rs->fetch();
       return $laLigne;
    }
    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
     * concernées par les deux arguments

     * @param $idVisiteur
     * @param $mois sous la forme aaaamm
     * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif
     */
    public function getLesFraisForfait($idVisiteur, $mois) {
        $req = "exec getFraisForfait :idVisiteur, :mois";
        $rs = PdoGsb::$monPdo->prepare($req);
        $rs->bindParam(':idVisiteur',$idVisiteur);
        $rs->bindParam(':mois',$mois);
        $rs->execute();
        return $rs->fetchAll();
        
         
    }
    

    /**
     * Retourne le nombre de justificatif d'un visiteur pour un mois donné

     * @param $idVisiteur
     * @param $mois sous la forme aaaamm
     * @return le nombre entier de justificatifs
     */
    public function getNbjustificatifs($idVisiteur, $mois) {
        $req = "select fichefrais.nbjustificatifs as nb from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetch();
        return $laLigne['nb'];
    }

    

    /**
     * Retourne tous les id de la table FraisForfait

     * @return un tableau associatif
     */
    public function getLesIdFrais() {
        $req = "select fraisforfait.id as idfrais from fraisforfait order by fraisforfait.id";
        $res = PdoGsb::$monPdo->query($req);
        $lesLignes = $res->fetchAll();
        return $lesLignes;
    }

    /**
     * Met à jour la table ligneFraisForfait

     * Met à jour la table ligneFraisForfait pour un visiteur et
     * un mois donné en enregistrant les nouveaux montants

     * @param $idVisiteur
     * @param $mois sous la forme aaaamm
     * @param $lesFrais tableau associatif de clé idFrais et de valeur la quantité pour ce frais
     * @return un tableau associatif
     */
    public function majFraisForfait($idVisiteur, $mois, $lesFrais) {
        $lesCles = array_keys($lesFrais);
        foreach ($lesCles as $unIdFrais) {
            $qte = $lesFrais[$unIdFrais];
            $req = "update lignefraisforfait set lignefraisforfait.quantite = $qte
			where lignefraisforfait.idvisiteur = '$idVisiteur' and lignefraisforfait.mois = '$mois'
			and lignefraisforfait.idfraisforfait = '$unIdFrais'";
            PdoGsb::$monPdo->exec($req);
        }
    }

    /**
     * met à jour le nombre de justificatifs de la table ficheFrais
     * pour le mois et le visiteur concerné

     * @param $idVisiteur
     * @param $mois sous la forme aaaamm
     */
    public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs) {
        $req = "update fichefrais set nbjustificatifs = $nbJustificatifs
		where fichefrais.idvisiteur = '$idVisiteur' and fichefrais.mois = '$mois'";
        PdoGsb::$monPdo->exec($req);
    }

    /**
     * Teste si un visiteur possède une fiche de frais pour le mois passé en argument

     * @param $idVisiteur
     * @param $mois sous la forme aaaamm
     * @return vrai ou faux
     */
    public function estPremierFraisMois($idVisiteur, $mois) {
        $ok = false;
        $req = "select count(*) as nblignesfrais from fichefrais
		where fichefrais.mois = '$mois' and fichefrais.idvisiteur = '$idVisiteur'";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetch();
        if ($laLigne['nblignesfrais'] == 0) {
            $ok = true;
        }
        return $ok;
    }

    /**
     * Retourne le dernier mois en cours d'un visiteur

     * @param $idVisiteur
     * @return le mois sous la forme aaaamm
     */
    public function dernierMoisSaisi($idVisiteur) {
        $req = "select max(mois) as dernierMois from fichefrais where fichefrais.idvisiteur = '$idVisiteur'";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetch();
        $dernierMois = $laLigne['dernierMois'];
        return $dernierMois;
    }

    /**
     * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un visiteur et un mois donnés

     * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
     * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles
     * @param $idVisiteur
     * @param $mois sous la forme aaaamm
     */
    public function creeNouvellesLignesFrais($idVisiteur, $mois) {
        $dernierMois = $this->dernierMoisSaisi($idVisiteur);
        $laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur, $dernierMois);
        if ($laDerniereFiche['idEtat'] == 'CR') {
            $this->majEtatFicheFrais($idVisiteur, $dernierMois, 'CL');
        }
        $req = "insert into fichefrais(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat)
		values('$idVisiteur','$mois',0,0,now(),'CR')";
        PdoGsb::$monPdo->exec($req);
        $lesIdFrais = $this->getLesIdFrais();
        foreach ($lesIdFrais as $uneLigneIdFrais) {
            $unIdFrais = $uneLigneIdFrais['idfrais'];
            $req = "insert into lignefraisforfait(idvisiteur,mois,idFraisForfait,quantite)
			values('$idVisiteur','$mois','$unIdFrais',0)";
            PdoGsb::$monPdo->exec($req);
        }
    }

    /**
     * Crée un nouveau frais hors forfait pour un visiteur un mois donné
     * à partir des informations fournies en paramètre

     * @param $idVisiteur
     * @param $mois sous la forme aaaamm
     * @param $libelle : le libelle du frais
     * @param $date : la date du frais au format français jj//mm/aaaa
     * @param $montant : le montant
     */
    public function creeNouveauFraisHorsForfait($idVisiteur, $mois, $libelle, $date, $montant) {
        $dateFr = dateFrancaisVersAnglais($date);
        $req = "insert into lignefraishorsforfait
		values('','$idVisiteur','$mois','$libelle','$dateFr','$montant')";
        PdoGsb::$monPdo->exec($req);
    }

    /**
     * Supprime le frais hors forfait dont l'id est passé en argument

     * @param $idFrais
     */
    public function supprimerFraisHorsForfait($idFrais) {
        $req = "delete from lignefraishorsforfait where lignefraishorsforfait.id =$idFrais ";
        PdoGsb::$monPdo->exec($req);
    }

    /**
     * Retourne les mois pour lesquel un visiteur a une fiche de frais

     * @param $idVisiteur
     * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant
     */
    public function getLesMoisDisponibles($idVisiteur) {
        $req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idvisiteur ='$idVisiteur'
		order by fichefrais.mois desc ";
        $res = PdoGsb::$monPdo->query($req);
        $lesMois = array();
        $laLigne = $res->fetch();
        while ($laLigne != null) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois["$mois"] = array(
                "mois" => "$mois",
                "numAnnee" => "$numAnnee",
                "numMois" => "$numMois"
            );
            $laLigne = $res->fetch();
        }
        return $lesMois;
    }

    /**
     * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné

     * @param $idVisiteur
     * @param $mois sous la forme aaaamm
     * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état
     */
    public function getLesInfosFicheFrais($idVisiteur, $mois) {
        $req = "exec liste_visiteur @Mois=:mois , @IdVisiteur=:visiteur ";
        $rs = PdoGsb::$monPdo->prepare($req);
        $rs->bindParam(':mois',$mois);
        $rs->bindParam(':visiteur',$idVisiteur);
        $rs->execute();
        $ligne = $rs->fetch();
        return $ligne;
    }

    public function validerFicheFrais($idVisiteur, $mois) {
        $req = "exec SP_FICHE_VALIDE :visiteur , :mois ";
        $rs = PdoGsb::$monPdo->prepare($req);
        $rs->bindParam(':visiteur',$idVisiteur);
        $rs->bindParam(':mois',$mois);
        $rs->execute();
    }
 
    /**
     * Modifie l'état et la date de modification d'une fiche de frais

     * Modifie le champ idEtat et met la date de modif à aujourd'hui
     * @param $idVisiteur
     * @param $mois sous la forme aaaamm
     */
    public function majEtatFicheFrais($idVisiteur, $mois, $etat) {
        $req = "update ficheFrais set idEtat = '$etat', dateModif = now()
		where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
        PdoGsb::$monPdo->exec($req);
    }

    /**
     *
     * Met à jour dans la base de données les quantités des lignes de frais forfaitisées
     * pour la fiche de frais dont l'id du visiteur et le mois de la fiche sont passés en paramètre.
     * Une transaction est utilisée pour garantir que toutes les mises à jour ont bien abouti, ou aucune.
     * 
     * @param string $unIdVisiteur L'id du visiteur.
     * @param string $unMois Le mois de la fiche de frais.
     * @param array $lesFraisForfaitises Un tableau à 2 dimensions contenant pour chaque frais forfaitisé
     * le numéro de ligne et la quantité.
     * @return boolean Le résultat de la mise à jour.
     */
    public function setLesQuantitesFraisForfaitises($unIdVisiteur, $unMois, $lesFraisForfaitises) {
        $req = "exec SP_LIGNE_FF_MAJ :idVisiteur , :mois,:numFrais, :quantite  ";
        $rs = PdoGsb::$monPdo->prepare($req);
        $rs->bindParam(':idVisiteur',$unIdVisiteur);
        $rs->bindParam(':mois',$unMois); 
        try {
            self::$monPdo->beginTransaction();
            foreach ($lesFraisForfaitises as $key => $value) {
                $numFrais=$value->getNumFrais();
                $quantite=$value->getQuantite();
                $rs->bindParam(':numFrais',$numFrais,PDO::PARAM_INT); // Les variables qui vont être modifier à chaque fois.
                $rs->bindParam(':quantite',$quantite,PDO::PARAM_INT);
                $rs->execute();
            }
            self::$monPdo->commit();    
        } catch(PDOException $e){
            echo " <p> erreur ; ". $e->getMessage() . "</p>";
            self::$monPdo->rollback();
        }
        
    }

    
    /**
     *
     * Met à jour les frais hors forfait dans la base de données.
     * La mise à jour consiste à :
     * - reporter ou supprimer certaine(s) ligne(s) des frais hors forfait ;
     * - mettre à jour le nombre de justificatifs pris en compte.
     * Une transaction est utilisée pour assurer la cohérence des données.
     * 
     * @param string $unIdVisiteur L'id du visiteur.
     * @param string $unMois Le mois de la fiche de frais.
     * @param array $lesFraisHorsForfait Un tableau à 2 dimensions contenant
     * pour chaque frais hors forfaitisé le numéro de ligne et l'action (R ou S) à effectuer.
     * @param type $nbJustificatifsPEC Le nombre de justificatifs pris en compte.
     * @return bool Le résultat de la mise à jour (TRUE : ok ; FALSE : pas ok).
     */
    public function setNbJustificatif($unIdVisiteur, $unMois, $nbJustificatifsPEC){
        $req2 = "exec SP_FICHE_NB_JPEC_MAJ :idVisiteur,:mois,:nbJustificatifPec";
        $rs2 = PdoGsb::$monPdo->prepare($req2);
        $rs2->bindParam(':idVisiteur',$unIdVisiteur);
        $rs2->bindParam(':mois',$unMois);
        $rs2->bindParam(':nbJustificatifPec',$nbJustificatifsPEC);
        $rs2->execute();
        
    }
    
    public function setLesFraisHorsForfait($unIdVisiteur, $unMois, $lesFraisHorsForfait, $nbJustificatifsPEC) {
        $req = "exec SP_LIGNE_FHF_SUPRIMME :idVisiteur , :mois, :numFrais";
        $rs = PdoGsb::$monPdo->prepare($req);
        $rs->bindParam(':idVisiteur',$unIdVisiteur);
        $rs->bindParam(':mois',$unMois);
        $rs->bindParam(':numFrais', $numFrais , PDO::PARAM_INT);   

        $req1="exec SP_LIGNE_FHF_REPORTE :idVisiteur , :mois, :numFrais ";
        $rs1= PdoGsb::$monPdo->prepare($req1);
        $rs1->bindParam(':idVisiteur',$unIdVisiteur);
        $rs1->bindParam(':mois',$unMois);
        $rs1->bindParam(':numFrais', $numFrais , PDO::PARAM_INT);

        try {
        self::$monPdo->beginTransaction();
        foreach ($lesFraisHorsForfait as $key => $UnFraisHorsForfait) {
            if ($UnFraisHorsForfait[1]=='S'){       
                    $numFrais=$UnFraisHorsForfait[0];
                    $rs->execute();
                    unset($lesFraisHorsForfait[$key]);
                            
            }else 
                if ($UnFraisHorsForfait[1]=='R'){ 
                    $numFrais=$UnFraisHorsForfait[0];
                    $rs1->execute();
                    unset($lesFraisHorsForfait[$key]);
                   
                }
            }

        echo 'hey';
        $this->setNbJustificatif($unIdVisiteur, $unMois, 15);
        self::$monPdo->commit();      
        } catch(PDOException $e){
            echo " <p> erreur ; ". $e->getMessage() . "</p>";
            self::$monPdo->rollback();
        }      
    }
    
        

    public function getInfosCategorieFrais($unIdCategorie){
        $req = "exec getInfosCategorieFrais :idCategorie";
        $rs = PdoGsb::$monPdo->prepare($req);
        $rs->bindParam(':idCategorie',$unIdCategorie);
        $rs->execute();
        $ligne = $rs->fetch(PDO::FETCH_ASSOC);
        return $ligne;
    }

    public function NombreFichACloturer($mois) {
        $req = "SELECT dbo.F_FICHE_A_CLOTURER (:mois)";
        $rs = PdoGsb::$monPdo->prepare($req);
        $rs->bindParam(':mois',$mois);
        $rs->execute();
        $ligne=$rs->fetch(PDO::FETCH_NUM); // nombre de ligne à cloturer
        return $ligne;
    }

    public function CloturerFicheMois($mois) {
        $req = "exec CLOTURER_FICHE_MOIS :mois";
        $rs = PdoGsb::$monPdo->prepare($req);
        $rs->bindParam(':mois',$mois);
        try {
            self::$monPdo->beginTransaction();
            $rs->execute();   
            self::$monPdo->commit();      
            } 
        catch(PDOException $e){
                echo " <p> erreur ; ". $e->getMessage() . "</p>";
                self::$monPdo->rollback();
        }
    }
}
?>