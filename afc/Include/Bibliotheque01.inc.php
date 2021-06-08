<?php
function formSelectDepuisRecordset($TitreLabel, $name, $id, $recordset, $tabindex, $selected = null) {

    $code = '<label for="' . $id . '">' . $TitreLabel . '</label>' . "\n"
            . '    <select name="' . $name . '" id="' . $id . '" class="titre" tabindex="' . $tabindex . '">' . "\n";

    $recordset->setFetchMode(PDO::FETCH_NUM);
    $ligne = $recordset->fetch();

    if ($selected == null) {
        while ($ligne) {
            $code .= '<option value="' . $ligne[0] . '">' . $ligne[1] . '</option>' . "\n";
            $ligne = $recordset->fetch();
        }
    } else {
        while ($ligne) {
            $code .= '<option ' . ($ligne[0] == $selected ? 'selected="selected"' :'') . ' value="' . $ligne[0] . '">' . $ligne[1] . '</option>';
            $ligne = $recordset->fetch();
        }     
    }
    $code .= '</select>';
    return $code;
}

function formInputText($label, $name, $idText, $value, $taille, $maxLength, $tabIndex, $readOnly,$required) {
    $readyOnlyText = '';
    $requireText = '';
    
    if ($readOnly != false) {
        $readyOnlyText = ' readonly="readonly"';
    }
    if ($required!=false) {
        $requireText =' required="required"';
    }
    
    return '<label for="'.$idText.'">' . $label . ' </label>'
         . '<input type="text" id="' . $idText . '" name="' . $name . '" class="zone" value="' . $value . '" size="' . $taille . '" maxlength="' . $maxLength . '" tabindex="' . $tabIndex . '" '."$requireText".' "'.$readyOnlyText.'">';
}

function formInputPassword($label,$name,$id,$value,$size,$maxLength,$tabIndex)
{
    $code = '<label for="'.$id.'">' . $label . ' </label><input type="password" id="' . $id . '" name="' . $name . '" size="' . $size . '" maxlength="' . $maxLength . '" tabindex="' . $tabIndex . '"';

    $code .= ' required="required" class="zone" value="' . $value . '" />';
    return $code;

}

function formBoutonSubmit($nom, $id, $valeur, $indextab) {
    return $code = '<input type="submit" name=' . $nom . ' id=' . $id . ' value=' . $valeur . ' tabindex=' . $indextab . "/>";
}

function formInputHidden($nom, $id, $valeur) {
    return $code = '<inpute type="hidden" name=' . $nom . 'id=' . $id . 'value=' . $valeur . '/>';
}

function formTextArea($label, $nom, $id, $valeur, $cols, $rows, $LongueurMaxi, $Index, $ReadOnly = false) {
    $code = '<label class="titre">' . $label . ' :</label><textarea name=' . $nom . ' id=' . $id . ' cols=' . $cols . ' rows=' . $rows . ' maxlength=' . $LongueurMaxi . ' tabindex=' . $Index;
    if ($ReadOnly = true) {
        $code .= ' readonly="readonly"> ' . $valeur . ' </textarea><br>';
    } else {
        $code .= ' > ' . $valeur . '</textarea><br>';
    }
    return $code;
}

function getMoisAnne(){
    $laDate= new DateTime();
    $leMois= (int) ($laDate->format('m')) -1;
    $lAnnee= (int) ($laDate->format('Y'));
    if($leMois==0){
        $lAnnee--;
        $leMois=12;
    }
    return (new DateTime ((string) $lAnnee . '-' .(string) $leMois))->format('Ym');
}

?>


