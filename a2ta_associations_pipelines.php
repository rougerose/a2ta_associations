<?php
/**
 * Utilisations de pipelines par A2TA Associations
 *
 * @plugin     A2TA Associations
 * @copyright  2020
 * @author     christophe le drean
 * @licence    GNU/GPL v3
 * @package    SPIP\A2ta_associations\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}





/**
 * Optimiser la base de données
 *
 * Supprime les objets à la poubelle.
 *
 * @pipeline optimiser_base_disparus
 * @param  array $flux Données du pipeline
 * @return array       Données du pipeline
 */
function a2ta_associations_optimiser_base_disparus($flux) {

	sql_delete('spip_associations', "statut='poubelle' AND maj < " . $flux['args']['date']);

	return $flux;
}

/**
 * Modifier les saisies adresse/email/téléphone :
 *  - suppression du titre
 *  - type "professionnel" par défaut
 *
 * @param  array $flux Le flux du pipeline
 * @return array       Le flux modifié
 */
function a2ta_associations_formulaire_saisies($flux) {
  $forms = array('editer_adresse', 'editer_email', 'editer_numero');

  if (in_array($flux['args']['form'], $forms)) {
    include_spip('inc/saisies');
    $flux['data'] = saisies_supprimer($flux['data'], 'titre');
    $flux['data'] = saisies_modifier($flux['data'], 'type', array('options' => array('defaut' => _COORDONNEES_TYPE_DEFAUT)));
  }

  return $flux;
}


// function a2ta_associations_recuperer_fond($flux) {
// 	$f = $flux;
// 	if ($flux['args']['fond'] == 'prive/objets/liste/adresses') {
// 		$f = $flux;
// 	}
// 	return $flux;
// }

// function a2ta_associations_formulaire_traiter($flux) {
// 	if ($flux['args']['form'] == 'editer_adresse') {
// 		include_spip('action/editer_liens');
// 		$id_adresse = _request('id_adresse');
// 		$departement = _request('departement');
// 		$region = _request('region');
// 	}
//
// 	return $flux;
// }
