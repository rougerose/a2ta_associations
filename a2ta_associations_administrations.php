<?php
/**
 * Fichier gérant l'installation et désinstallation du plugin A2TA Associations
 *
 * @plugin     A2TA Associations
 * @copyright  2020
 * @author     christophe le drean
 * @licence    GNU/GPL v3
 * @package    SPIP\A2ta_associations\Installation
 */

if (!defined('_ECRIRE_INC_VERSION')) {
  return;
}


/**
 * Fonction d'installation et de mise à jour du plugin A2TA Associations.
 *
 * @param string $nom_meta_base_version
 *     Nom de la meta informant de la version du schéma de données du plugin installé dans SPIP
 * @param string $version_cible
 *     Version du schéma de données dans ce plugin (déclaré dans paquet.xml)
 * @return void
**/
function a2ta_associations_upgrade($nom_meta_base_version, $version_cible) {
  $maj = array();

	$maj['create'] = array(
		array('maj_tables', array('spip_associations', 'spip_adresses', 'spip_associations_imports')),
		array('a2ta_associations_configurer_dependances')
	);

	// Adresses : ajout champs régions et département
  $maj['1.0.1'] = array(
    array('maj_tables', array('spip_adresses'))
  );

	// Associations : ajout champs sites web
  $maj['1.0.4'] = array(
    array('maj_tables', array('spip_associations'))
  );

  $maj['1.0.5'] = array(
		array('sql_alter', 'TABLE spip_associations CHANGE site_web url_site TINYTEXT NOT NULL DEFAULT ""'),
		array('sql_alter', 'TABLE spip_associations CHANGE site_web_supp url_site_supp TINYTEXT NOT NULL DEFAULT ""'),
  );

	$maj['1.0.6'] = array(
		array('maj_tables', array('spip_associations_import_logs'))
	);

	$maj['1.0.9'] = array(
		array('sql_alter', 'TABLE spip_associations_import_logs CHANGE log log MEDIUMTEXT NOT NULL DEFAULT ""')
	);

	// Logs import :
	// - supprimer la table associations_import_logs
	// - chaque log est inséré dans la table associations
	// - ajouter une table associations_imports
	$maj['1.0.10'] = array(
		array('sql_drop_table', 'spip_associations_import_logs'),
		array('sql_alter', 'TABLE spip_associations ADD log_import text not null default "" AFTER statut'),
		array('maj_tables', array('spip_associations_imports', 'spip_associations'))
	);

	// Ajouter champ Associations
	$maj['1.0.11'] = array(
		array('sql_alter', 'TABLE spip_associations_imports ADD associations text not null default "" AFTER id_associations_import')
	);

  include_spip('base/upgrade');
  maj_plugin($nom_meta_base_version, $version_cible, $maj);
}


/**
 * Fonction de désinstallation du plugin A2TA Associations.
 *
 * @param string $nom_meta_base_version
 *     Nom de la meta informant de la version du schéma de données du plugin installé dans SPIP
 * @return void
**/
function a2ta_associations_vider_tables($nom_meta_base_version) {

  sql_drop_table('spip_associations');
  sql_drop_table('spip_associations_imports');

  # Nettoyer les liens courants (le génie optimiser_base_disparus se chargera de nettoyer toutes les tables de liens)
  sql_delete('spip_documents_liens', sql_in('objet', array('association')));
  sql_delete('spip_mots_liens', sql_in('objet', array('association')));
  sql_delete('spip_auteurs_liens', sql_in('objet', array('association')));
  sql_delete('spip_adresses_liens', sql_in('objet', array('association')));
  sql_delete('spip_emails_liens', sql_in('objet', array('association')));
	sql_delete('spip_numeros_liens', sql_in('objet', array('association')));
  sql_delete('spip_gis_liens', sql_in('objet', array('association')));
  sql_delete('spip_rezosocios_liens', sql_in('objet', array('association')));
  # Nettoyer les versionnages et forums
  sql_delete('spip_versions', sql_in('objet', array('association')));
  sql_delete('spip_versions_fragments', sql_in('objet', array('association')));
  sql_delete('spip_forum', sql_in('objet', array('association')));

  effacer_meta($nom_meta_base_version);
}

/**
 * Adapter la configuration des plugins nécessaires
 * (GIS, Coordonnées et Réseaux sociaux)
 * @return void
 */
function a2ta_associations_configurer_dependances() {
	include_spip('inc/config');

	// GIS
	$gis_conf = lire_config('gis', array());
	$gis_conf_associations = array(
		'lat' => '48.8566969', // Paris
		'lon' => '2.3514616',
		'zoom' => '10',
		'geocoder' => 'on',
		'adresse' => 'on',
		'gis_objets' => array('spip_associations')
	);
	$gis_conf = array_merge($gis_conf, $gis_conf_associations);
	ecrire_config('gis', $gis_conf);

	// Coordonnées
	$coord_conf = lire_config('coordonnees', array());
	$coord_conf['objets'] = array_merge($coord_conf['objets'], array('spip_associations'));
	ecrire_config('coordonnees', $coord_conf);

	// Réseaux sociaux
	$rezos_conf = lire_config('rezosocios', array());
	$rezos_conf['rezosocios_objets'] = array_merge($rezos_conf['rezosocios_objets'], array('spip_associations'));
	ecrire_config('rezosocios', $rezos_conf);
}
