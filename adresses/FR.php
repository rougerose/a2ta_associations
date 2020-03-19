<?php

if (!defined("_ECRIRE_INC_VERSION")) {
	return;
}


function adresses_FR() {
  $saisies = array(
    array(
      'saisie' => 'input',
      'options' => array(
        'nom' => 'voie',
        'label' => _T('coordonnees:label_voie'),
      ),
    ),
    array(
      'saisie' => 'input',
      'options' => array(
        'nom' => 'complement',
        'label' => _T('coordonnees:label_complement'),
        'placeholder' => _T('coordonnees:placeholder_complement_adresse')
      ),
    ),
    array(
      'saisie' => 'input',
      'options' => array(
        'nom' => 'code_postal',
        'label' => _T('coordonnees:label_code_postal')
      ),
    ),
    array(
      'saisie' => 'input',
      'options' => array(
        'nom' => 'ville',
        'label' => _T('coordonnees:label_ville')
      ),
    ),
    array(
      'saisie' => 'geo_departement_code',
      'options' => array(
        'nom' => 'departement',
        'label' => _T('association:label_departement')
      ),
    ),
    array(
      'saisie' => 'geo_region_code',
      'options' => array(
        'nom' => 'region',
        'label' => _T('association:label_region')
      ),
    ),
  );
  return $saisies;
}
