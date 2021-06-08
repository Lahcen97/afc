<h2>Frais hors forfait</h2>
    <?php
    if (count($ficheFrais->getLesFraisHorsForfait())>0){
        ?> 
                <form name="frmFraisHorsForfait" id="frmFraisHorsForfait" method="post" action="index.php?uc=validerFicheFrais&action=enregModifFHF"
                      onsubmit="return confirm('Voulez-vous réellement enregistrer les modifications apportées aux frais hors forfait ?');">
                    <table>
                        <tr>
                            <th>Date</th><th>Libellé</th><th>Montant</th><th>Ok</th><th>Reporter</th><th>Supprimer</th>
                        </tr>
                        <?php
                        $i=0;
                        foreach ($lesFraisHorsForfaitises as $key => $unFraisHorsForfait)
                        {?>
                            <tr>
                            <td><input value="<?php echo $unFraisHorsForfait->getDate(); ?>" name="tableau[<?php echo $i ?>][txtHFDate]" type="text" size="12"  id="txtHFDate2" readonly="readonly" /></td>
                            <td><input value="<?php echo $unFraisHorsForfait->getLibelle(); ?>" name="tableau[<?php echo $i ?>][txtHFLibelle]"  type="text" size="50"  id="txtHFLibelle2" readonly="readonly" /></td>
                            <td><input value="<?php echo $unFraisHorsForfait->getMontant(); ?>" name="tableau[<?php echo $i ?>][txtHFMontant]"  type="text" size="10" id="txtHFMontant2" readonly="readonly" /></td>
                                <input value="<?php echo $unFraisHorsForfait->getNumFrais(); ?>" name="tableau[<?php echo $i ?>][hiddenHFNum]" type="hidden" id="prodId" >
        
                            <td><input type="radio" name="tableau[<?php echo $i ?>][rbHFaction]" value="O" tabindex="100" checked="checked" /></td>
                            <td><input type="radio" name="tableau[<?php echo $i ?>][rbHFaction]" value="R" tabindex="110"  /></td>
                            <td><input type="radio" name="tableau[<?php echo $i ?>][rbHFaction]" value="S" tabindex="120"  /></td>
                            </tr>
                            <?php
                            $i++;
                        };?>

                    </table>
                    <p>Nb de justificatifs pris en compte :&nbsp;
                        <input  value="<?php ECHO $ficheFrais->getNbJustificatifs(); ?> " type="text" size="4" name="txtHFNbJustificatifsPEC" id="txtHFNbJustificatifsPEC" tabindex="130" /><br />

                    </p>
                    <p>
                        <input type="submit" id="btnEnregistrerModifFHF" name="btnEnregistrerModifFHF" value="Enregistrer les modifications des lignes hors forfait" tabindex="140" />&nbsp;
                        <input type="reset" id="btnReinitialiserFHF" name="btnReinitialiserFHF" value="Réinitialiser" tabindex="150" />
                    </p>
                </form>
            </div>
            <br />
            <br />
    <?php 
    } 
    else 
    {
    ?>
    <p> Pas de frais hors forfait </p></div>
    <?php
    } ?>