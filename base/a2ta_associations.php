<?php
/**
 * Déclarations relatives à la base de données
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
 * Déclaration des alias de tables et filtres automatiques de champs
 *
 * @pipeline declarer_tables_interfaces
 * @param array $interfaces
 *     Déclarations d'interface pour le compilateur
 * @return array
 *     Déclarations d'interface pour le compilateur
 */
function a2ta_associations_declarer_tables_interfaces($interfaces) {

	$interfaces['table_des_tables']['associations'] = 'associations';
	$interfaces['table_des_tables']['associations_import_logs'] = 'associations_import_logs';

	return $interfaces;
}


/**
 * Déclaration des objets éditoriaux
 *
 * @pipeline declarer_tables_objets_sql
 * @param array $tables
 *     Description des tables
 * @return array
 *     Description complétée des tables
 */
function a2ta_associations_declarer_tables_objets_sql($tables) {

	$tables['spip_associations'] = array(
		'type' => 'association',
		'principale' => 'oui',
		'field'=> array(
			'id_association' => 'bigint(21) NOT NULL',
			'nom'            => 'tinytext NOT NULL DEFAULT ""',
			'membre_fraap'   => 'tinyint(1) NOT NULL DEFAULT 0',
			'url_site'       => 'tinytext NOT NULL DEFAULT ""',
			'url_site_supp'  => 'tinytext NOT NULL DEFAULT ""',
			'date_creation'  => 'datetime NOT NULL DEFAULT "0000-00-00 00:00:00"',
			'date'           => 'datetime NOT NULL DEFAULT "0000-00-00 00:00:00"',
			'statut'         => 'varchar(20)  DEFAULT "0" NOT NULL',
			'log_import' => 'text NOT NULL DEFAULT ""',
			'maj'            => 'TIMESTAMP'
		),
		'key' => array(
			'PRIMARY KEY'        => 'id_association',
			'KEY statut'         => 'statut',
		),
		'titre' => 'nom AS titre, "" AS lang',
		'date' => 'date',
		'champs_editables'  => array('nom', 'membre_fraap', 'date_creation', 'url_site', 'url_site_supp', 'log_import'),
		'champs_versionnes' => array('nom', 'membre_fraap', 'date_creation', 'url_site', 'url_site_supp', 'log_import'),
		'rechercher_champs' => array('nom' => 5),
		'tables_jointures'  => array(),
		'statut_textes_instituer' => array(
			'prepa'    => 'texte_statut_en_cours_redaction',
			'prop'     => 'texte_statut_propose_evaluation',
			'publie'   => 'texte_statut_publie',
			'refuse'   => 'texte_statut_refuse',
			'poubelle' => 'texte_statut_poubelle',
		),
		'statut'=> array(
			array(
				'champ'     => 'statut',
				'publie'    => 'publie',
				'previsu'   => 'publie,prop,prepa',
				'post_date' => 'date',
				'exception' => array('statut','tout')
			)
		),
		'texte_changer_statut' => 'association:texte_changer_statut_association',
	);

	$tables['spip_associations_imports'] = array(
		'type' => 'associations_import',
		'principale' => 'oui',
		'table_objet_surnoms' => array('associationsimport'), // table_objet('associations_importlog') => 'associations_import_logs'
		'field'=> array(
			'id_associations_import' => 'bigint(21) NOT NULL',
			'date'               => 'datetime NOT NULL DEFAULT "0000-00-00 00:00:00"',
			'associations' => 'text NOT NULL DEFAULT ""',
			'maj'                => 'TIMESTAMP'
		),
		'key' => array(
			'PRIMARY KEY'        => 'id_associations_import',
		),
		'titre' => '"" AS titre, "" AS lang',
		 #'date' => '',
		'champs_editables'  => array(),
		'champs_versionnes' => array(),
		'rechercher_champs' => array(),
		'tables_jointures'  => array(),
	);

  $tables['spip_adresses']['field']['region'] = 'text not null default ""';
  $tables['spip_adresses']['field']['departement'] = 'text not null default ""';
  $tables['spip_adresses']['champs_editables'][] = 'region';
  $tables['spip_adresses']['champs_editables'][] = 'departement';
  $tables['spip_adresses']['champs_versionnes'][] = 'region';
  $tables['spip_adresses']['champs_versionnes'][] = 'departement';

	return $tables;
}
