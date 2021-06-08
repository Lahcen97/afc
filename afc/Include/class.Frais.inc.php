<?php
require_once './Include/class.pdogsb.inc.php';
require_once './Include/fct.inc.php';
require_once './Include/class.CategorieFraisForfaitise.inc.php';
/**
 * Classe Frais
 *
 */
abstract class Frais {

    protected $idVisiteur;
    protected $moisFicheFrais;
    protected $numFrais;

    function __construct($unIdVisiteur, $unMoisFicheFrais, $unNumFrais) {
        $this->idVisiteur = $unIdVisiteur;
        $this->moisFicheFrais = $unMoisFicheFrais;
        $this->numFrais = $unNumFrais;
    }

    public function getIdVisiteur() {
        return $this->idVisiteur;
    }


    public function getMoisFiche() {
        return $this->moisFicheFrais;
    }

    public function getNumFrais() {
        return $this->numFrais;
    }

    abstract public function getMontant();

}

final class FraisForfaitise extends Frais { 

    private $quantite;
    private $laCategorieFraisForfaitise;

    function __construct($unIdVisiteur, $unMoisFicheFrais, $unNumFrais, $uneQuantite,$unIdCategorie ) {
        parent::__construct($unIdVisiteur, $unMoisFicheFrais, $unNumFrais);

        $this->quantite = $uneQuantite;
        $this->laCategorieFraisForfaitise=new CategorieFraisForfaitise($unIdCategorie);

    }

    public function getLaCategorieFraisForfaitise() // PROCEDURE POUR VALIDATION 
    {
        return $this->$laCategorieFraisForfaitise;

    }

    public function getQuantite()
    {
        return $this->quantite; 
    }

    public function getMontant()
    {
        return $this->quantite*$this->laCategorieFraisForfaitise->getMontant(); 
    }
   
}

final class FraisHorsForfait extends Frais {
    private $libelle;
    private $date;
    private $montant;
    private $action='O';

    function __construct($unIdVisiteur, $unMoisFicheFrais, $unNumFrais, $unLibelle, $uneDate , $unMontant, $uneAction=null) {
        parent::__construct($unIdVisiteur, $unMoisFicheFrais, $unNumFrais);
            
        $this->libelle = $unLibelle;
        $this->date = $uneDate;
        $this->montant = $unMontant;
        if ($uneAction!=null){
            $this->action=$uneAction;
        }
    }

    public function getAction(){
        return $this->action;
    }
    public function getDate(){
        return $this->date;
    }
    public function getLibelle(){
        return $this->libelle;
    }
    public function getMontant(){
        return $this->montant;
    }

}