<?php

require_once './Include/class.pdogsb.inc.php';
require_once './Include/fct.inc.php';
require_once './Include/class.Frais.inc.php';

final class FicheFrais {

    private $idVisiteur;
    private $moisFiche;
    private $nbJustificatifs = 0;
    private $montantValide = 0;
    private $dateDerniereModif;
    private $idEtat;
    private $libelleEtat;
    /**
     * On utilise 2 collections pour stocker les frais :
     * plus efficace car on doit extraire soit les FF soit les FHF.
     * Avec une seule collection on serait toujours obligé de parcourir et
     * de tester le type de tous les frais avant de les extraires.
     *
     */
    private $lesFraisForfaitises = []; // Un tableau asociatif de la forme : <idCategorie>, <objet FraisForfaitise>
    private $lesFraisHorsForfait = [];

    /**
     * Un tableau des numéros de ligne des frais forfaitisés.
     * Les lignes de frais forfaitisés sont numérotées en fonction de leur catégorie.
     * Le tableau est static ce qui évite de le déclarer dans chaque instance de
     * FicheFrais.
     *
     */
    private $tabNumLigneFraisForfaitise = ['ETP' => 1,
        'KM' => 2,
        'NUI' => 3,
        'REP' => 4];

    function __construct($leIdVisiteur,$leMois) {
        $this->idVisiteur=$leIdVisiteur;
        $this->moisFiche=$leMois;
    }

    function __destruct(){
        
    }
   

    public function initLesFraisForfaitises(){
        $pdo = PdoGsb::getPdoGsb();
        $estConnecte = estConnecte();
        $lesLignes=$pdo->getLesFraisForfait($this->idVisiteur, $this->moisFiche);
        $i=0; //  permet de vérifier si le num ligne frais est égale à 2 
        foreach ($this->tabNumLigneFraisForfaitise as $key => $value) {
            $this->lesFraisForfaitises[$key]=new FraisForfaitise($this->idVisiteur, $this->moisFiche, $value, $value!=2? $lesLignes[$i][4] : 0, $value!=2? $lesLignes[$i][3] : "KM" );
            if ($value==2){
                $i=1;
            }else {
                $i++;
            }
        }
    }
    public function initLesFraisHorsForfaitises(){
        $pdo = PdoGsb::getPdoGsb();
        $estConnecte = estConnecte();
        $lesLignes=$pdo->getLesFraisHorsForfait($this->idVisiteur,$this->moisFiche);
        $nombreLigne=count($lesLignes);
        for ($i = 0; $i < $nombreLigne; $i++) {
                $this->lesFraisHorsForfait[$i]=new FraisHorsForfait($this->idVisiteur, $this->moisFiche, $lesLignes[$i]['FRAIS_NUM'], $lesLignes[$i]['LFHF_LIBELLE'], $lesLignes[$i]['LFHF_DATE'] , $lesLignes[$i]['LFHF_MONTANT']);
        }
    }
    public function initInfosFicheSansLesFrais(){
        $pdo = PdoGsb::getPdoGsb();
        $estConnecte = estConnecte();
        $ligne=$pdo->infoFicheFraisVisiteur($this->idVisiteur,$this->moisFiche);
        $this->idEtat=$ligne[0];
        $this->libelleEtat=$ligne[1];
        $this->dateDerniereModif=$ligne[4];
        $this->nbJustificatifs=$ligne[2];
        $this->montantValide=$ligne[3];
       
        
        
    }   
    public function initAvecInfosBDD() {
        $this->initInfosFicheSansLesFrais();
        $this->initLesFraisForfaitises();
        $this->initLesFraisHorsForfaitises();
    }
    
    public function initAvecInfosBDDSansFF(){
        $this->initInfosFicheSansLesFrais();
        $this->initLesFraisHorsForfaitises();   
    }

    public function initAvecInfosBDDSansFHF(){
        $this->initInfosFicheSansLesFrais();
        $this->initLesFraisForfaitises();
    }
    



    /**
     *
     * Ajoute à la fiche de frais un frais forfaitisé (une ligne) dont
     * l'id de la catégorie et la quantité sont passés en paramètre.
     * Le numéro de la ligne est automatiquement calculé à partir de l'id de
     * sa catégorie.
     *
     * @param string $idCategorie L'ide de la catégorie du frais forfaitisé.
     * @param int $quantite Le nombre d'unité(s).
     */
    public function ajouterUnFraisForfaitise($idCategorie, $quantite) {
        $numeroLigneFrais=$this->getNumLigneFraisForfaitise($idCategorie);
        $this->lesFraisForfaitises[$idCategorie]= new FraisForfaitise($this->idVisiteur, $this->moisFiche, $numeroLigneFrais,$quantite,$idCategorie);
    }

    /**
     *
     * Ajoute à la fiche de frais un frais forfaitisé (une ligne) dont
     * l'id de la catégorie et la quantité sont passés en paramètre.
     * Le numéro de la ligne est automatiquement calculé à partir de l'id de
     * sa catégorie.
     *
     * @param int $numFrais Le numéro de la ligne de frais hors forfait.
     * @param string $libelle Le libellé du frais.
     * @param string $date La date du frais, sous la forme AAAA-MM-JJ.
     * @param float $montant Le montant du frais.
     * @param string $action L'action à réaliser éventuellement sur le frais.
     */
  
    public function ajouterUnFraisHorsForfait($numFrais, $libelle, $date, $montant, $action = NULL) {
         $this->lesFraisHorsForfait[]=new FraisHorsForfait($this->idVisiteur, $this->moisFiche, $numFrais, $libelle, $date , $montant, $action);
    }   

    public function calculerLeMontantValide(){
        $montant=0;
        $tableauFrais=array_merge($this->lesFraisForfaitises, $this->lesFraisHorsForfait);
        foreach($this->lesFraisHorsForfait as $value){
           
        }
        foreach($this->lesFraisForfaitises as $value){
           
        }

        foreach ($tableauFrais as $key => $fraisMontant) {
                
                $montant+= $key!=="KM" ? $fraisMontant->getMontant() : 0;
                echo $montant ." ";
        }
        return $montant;  
    }

    public function valider(){
        $pdo = PdoGsb::getPdoGsb();
        $estConnecte = estConnecte();
        $pdo->validerFicheFrais($this->idVisiteur, $this->moisFiche);
    }
    /**
     *
     * Retourne la collection des frais forfaitisés de la fiche de frais.
     *
     * @return array La collections des frais forfaitisés.
     */
    public function getLesFraisForfaitises() {

        return $this->lesFraisForfaitises;
    }

    /**
     *
     * Retourne un tableau contenant les quantités pour chaque ligne de frais
     * forfaitisé de la fiche de frais.
     *
     * @return array Le tableau demandé.
     */
    public function getLesQuantitesDeFraisForfaitises() {
        $TableauQuantite=[];
        foreach($this->lesFraisForfaitises as $key => $value){
            $TableauQuantite[$key]=intval($value->getQuantite());
        }
        return $TableauQuantite;
    }

    /**
     *
     * Retourne la collection des frais forfaitisés de la fiche de frais.
     *
     * @return array la collections des frais forfaitisés.
     */
    public function getLesFraisHorsForfait() {
        return $this->lesFraisHorsForfait;
    }

    /**
     *
     * Retourne un tableau associatif d'informations sur les frais hors forfaitisés
     * de la fiche de frais :
     * - le numéro du frais (numFrais),
     * - son libellé (libelle),
     * - sa date (date),
     * - son montant (montant).
     *
     * @return array Le tableau demandé.
     */
    public function getLesInfosFraisHorsForfait() {
        $tableAssociatifInfoFraisHorsForfait=[];
        foreach ($this->lesFraisHorsForfait as $key => $value) {
            $tableAssociatifInfoFraisHorsForfait[$key]=
            [['numFrais'=>$value->getNumFrais(),
                'libelle' =>$value->getLibelle(),
                'date' =>$value->getDate(),
                'montant'=>$value->getMontant(),
                'action'=>$value->getAction()]];
        }
    }

    /**
     *
     * Retourne le numéro de ligne d'un frais forfaitisé dont l'identifiant de
     * la catégorie est passé en paramètre.
     * Chaque fiche de frais comporte systématiquement 4 lignes de frais forfaitisés.
     * Chaque ligne de frais forfaitisé correspond à une catégorie de frais forfaitisé.
     * Les lignes de frais forfaitisés d'une fiche sont numérotées de 1 à 4.
     * Ce numéro dépend de la catégorie de frais forfaitisé :
     * - ETP : 1,
     * - KM  : 2,
     * - NUI : 3,
     * - REP : 4.
     *
     * @param string $idCategorieFraisForfaitise L'identifiant de la catégorie de frais forfaitisé.
     * @return int Le numéro de ligne du frais.
     *
     */
    private function getNumLigneFraisForfaitise($idCategorieFraisForfaitise) {
        $numeroLigne;
        foreach ($this->tabNumLigneFraisForfaitise as $key => $value) {
            if ($idCategorieFraisForfaitise==$key){
                $numeroLigne = $value;
                break;
            }

        }
        return $numeroLigne;
    }

    /**
     *
     * Contrôle que les quantités de frais forfaitisés passées en paramètre
     * dans un tableau sont bien des numériques entiers et positifs.
     * Cette méthode s'appuie sur la fonction lesQteFraisValides().
     *
     * @return booléen Le résultat du contrôle.
     *
     * 
     */
    public function controlerQtesFraisForfaitises() {
        if (lesQteFraisValides($this->getLesQuantitesDeFraisForfaitises())){
            return true;
        }
        else {
            return false;
        }
    }

    public function controlerNbJustificatifs(){
        if ($this->nbJustificatifs>=0){
            return true;
        }else {
            return false;
        }
    } 

    /**
     *
     * Met à jour dans la base de données les quantités des lignes de frais forfaitisées.
     *
     * @return bool Le résultat de la mise à jour.
     *
     */
    
    public function mettreAJourLesFraisForfaitises(){
        $pdo = PdoGsb::getPdoGsb();
        $estConnecte = estConnecte();
        return $pdo->setLesQuantitesFraisForfaitises($this->idVisiteur, $this->moisFiche,$this->lesFraisForfaitises);// avec le $ POST on doit créer un tableau 2D
        
    }
    
    public function mettreAJourLesFraisHorsForfait(){
        $pdo = PdoGsb::getPdoGsb();
        $estConnecte = estConnecte();
        $table2d=[];
        foreach ( $this->lesFraisHorsForfait as $value) {
            $table2d[]=[$value->getNumFrais(),$value->getAction()];
        }
        $pdo->setLesFraisHorsForfait($this->idVisiteur, $this->moisFiche, $table2d, $this->nbJustificatifs);
    }

    public function getLibelleEtat(){
        return $this->libelleEtat;
    }

    public function getNbJustificatifs(){
        return $this->nbJustificatifs;
    }

    public function setNbJustJusitificatifs($unNbJustificatifs){
        $this->nbJustificatifs=$unNbJustificatifs;
    }

  

}
