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

  $maj['create'] = array(array('maj_tables', array('spip_associations', 'spip_adresses')));

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
