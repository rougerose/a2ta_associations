<?php

if (!defined("_ECRIRE_INC_VERSION")) {
  return;
}


include_spip('base/abstract_sql');
include_spip('base/objets');

/**
 * API REST GET sur une collection associations
 * @param  request $requete objet contenant la requete HTTP
 * @param  response $reponse objet qui contiendra la réponse, format json
 * @return response
 */
function http_collectionjson_associations_get_collection($requete, $reponse) {

	$collection = $requete->attributes->get('collection');
	$offset = $requete->query->get('offset');

	// parametres possibles
	$parametres = array(
		'id_association' => $requete->query->get('id_association'),
		'id_mot' => $requete->query->get('id_mot'),
		'ville' => $requete->query->get('ville')
	);

	$parametres = a2ta_associations_collectionjson_traiter_parametres($parametres);

	$cle = id_table_objet($collection);
	$table = table_objet_sql($collection);
	$objet = objet_type($collection);

	$contexte = array(
		'table' => $table,
		'id_table_objet' => $cle,
		'parametres' => $parametres,
		'offset' => isset($offset) ? $offset : 0,
		'pagination' => 10
	);

	// récupérer le contenu
	$lignes = a2ta_associations_recuperer_contenu_collection($contexte);

	if ($lignes) {
		$objets = array();
		// le total sans les critères de pagination
		$nb_objets = a2ta_associations_recuperer_contenu_collection($contexte, true);
		$pagination = $contexte['pagination'];
		$offset = $contexte['offset'];

		foreach ($lignes as $champ) {
			$objets[] = collectionjson_get_objet($objet, $champ[$cle], $champ);
		}

		// pagination
		$links = array();

		if ($offset > 0) {
			$offset_precedent = max(0, $offset-$pagination);
			$links[] = array(
				'rel' => 'prev',
				'prompt' => _T('public:page_precedente'),
				'href' => url_absolue(
					parametre_url(self('&'), 'offset', $offset_precedent)),
			);
		}

		if (($offset + $pagination) < $nb_objets) {
			$offset_suivant = $offset + $pagination;
			$links[] = array(
				'rel' => 'next',
				'prompt' => _T('public:page_suivante'),
				'href' => url_absolue(
					parametre_url(self('&'), 'offset', $offset_suivant)),
			);
		}

		$json = array(
			'collection' => array(
				'version' => '1.0',
				'href' => url_absolue(parse_url(self('&'), PHP_URL_PATH)),
				'links' => $links,
				'items' => $objets,
			),
		);

		// pipelines
		$json = pipeline(
			'collectionjson_get_collection',
			array(
				'args' => array(
					'collection' => $collection,
					'contexte' => $contexte,
				),
				'data' => $json,
			)
		);

		$json = pipeline(
			'http_collectionjson_get_collection_contenu',
			array(
				'args' => array(
					'requete' => $requete,
					'reponse' => $reponse,
				),
				'data' => $json,
			)
		);

		$json = json_encode($json);
		$reponse->setStatusCode(200);
		$reponse->setCharset('utf-8');
		$reponse->headers->set('Content-Type', 'application/json');
		$reponse->setContent($json);

	} else {
		$fonction_erreur = charger_fonction('erreur', 'http/collectionjson/');
		$response = $fonction_erreur(404, $requete, $reponse);
	}

	return $reponse;
}


/**
 * API REST GET sur une ressource association
 * @param  request $requete objet contenant la requete HTTP
 * @param  response $reponse objet qui contiendra la réponse, format json
 * @return response
 */
function http_collectionjson_associations_get_ressource($requete, $reponse) {
	$format = $requete->attributes->get('format');
	$collection = $requete->attributes->get('collection');
	$ressource = $requete->attributes->get('ressource');

	// C'est une ressource, aucune prise en compte de parametres éventuels
	$parametres = array(
		'id_association' => null,
		'id_mot' => null,
		'ville' => null
	);

	$cle = id_table_objet($collection);
	$table = table_objet_sql($collection);
	$id_objet = intval($ressource);
	$objet = objet_type($collection);

	$select = array($cle, 'nom', 'url_site', 'url_site_supp');

	$champs = sql_fetsel($select, $table, $cle . '=' . $id_objet);

	if ($champs) {
		$item = collectionjson_get_objet($objet, $id_objet, $champs);

		$json = array(
			'collection' => array(
				'version' => '1.0',
				'href' => url_absolue(self('&')),
				'items' => array(
					$item,
				)
			),
		);

		// On passe le json dans un pipeline
		$json = pipeline(
			'http_collectionjson_get_ressource_contenu',
			array(
				'args' => array(
					'requete' => $requete,
					'reponse' => $reponse,
				),
				'data' => $json,
			)
		);

		$json = json_encode($json);
		$reponse->setStatusCode(200);
		$reponse->setCharset('utf-8');
		$reponse->headers->set('Content-Type', 'application/json');
		$reponse->setContent($json);

	} else {

		$fonction_erreur = charger_fonction('erreur', "http/$format/");
		$reponse = $fonction_erreur(404, $requete, $reponse);

	}

	return $reponse;
}

/**
 * Traiter les parametres ajoutés au GET
 * @param  array $parametres
 * @return array
 */
function a2ta_associations_collectionjson_traiter_parametres($parametres) {
	$parametres_post_traitement = array();

	if (is_array($parametres)) {
		foreach ($parametres as $cle => $parametre) {
			if ($parametre and is_string($parametre)) {
				$parametre = explode(',', $parametre);
			}
			$parametres_post_traitement[$cle] = $parametre;
		}
	}

	return $parametres_post_traitement;
}


/**
 * [a2ta_associations_recuperer_contenu_collection description]
 * @param  array  $contexte
 * 	- table
 * 	- id_table_objet
 * 	- parametres : id_association, id_mots, ville
 * 	- offset
 * 	- pagination
 *
 * @param  boolean $count
 * 	le nombre total de résultats de la requete
 * @return array|integer
 * 	Un tableau contenant tous les résultats de la requete
 * 	ou le nombre total des résultats.
 */
function a2ta_associations_recuperer_contenu_collection($contexte, $count = false) {
	$select = array();
	$where = array();
	$having = array();
	$groupby = array();
	$orderby = array();
	$limit = '';
	$having = array();

	$cle = $contexte['id_table_objet'];
	$table = $contexte['table'];

	$limit = $contexte['offset'].','.$contexte['pagination'];
	$from = $table.' AS L1';
	$orderby[] = 'L1.nom';

	$id_association = $contexte['parametres']['id_association'];
	$id_mot = $contexte['parametres']['id_mot'];
	$ville = $contexte['parametres']['ville'];

	$lignes = array();
	$select = array($cle, 'nom', 'url_site', 'url_site_supp');

	if (is_null($id_association) and is_null($id_mot) and is_null($ville)) {

		if ($count) {
			$lignes = sql_countsel($from);

		} else {
			$lignes = sql_allfetsel($select, $from, '', '', $orderby, $limit, '');

		}
	} else {

		if ($id_association) {
			$where[] = 'L1.statut='.sql_quote('publie');
			$where[] = sql_in('L1.id_association', $id_association);
		}

		if ($ville) {
			$from .= ' INNER JOIN spip_adresses_liens AS L2 ON (L2.objet="association" AND L2.id_objet=L1.id_association)';
			$from  .= ' INNER JOIN spip_adresses AS L3 ON (L3.id_adresse=L2.id_adresse)';
			$where[] = 'L3.ville=' .sql_quote($ville[0]);
			$where[] = 'L1.statut='.sql_quote('publie');
		}

		if ($id_mot) {
			$nb = count($id_mot);

			$from .= ' INNER JOIN spip_mots_liens AS L4 ON (L4.objet="association" AND L4.id_objet=L1.id_association)';
			$where[] = 'L1.statut='.sql_quote('publie');
			$where[] = sql_in('L4.id_mot ', $id_mot);

			$groupby[] = 'L1.id_association';
			$having[] = 'COUNT(DISTINCT L4.id_mot)='.$nb;
		}

		if ($count) {
			$lignes = sql_countsel($from, $where, $groupby, $having);

		} else {
			$lignes = sql_allfetsel($select, $from, $where, $groupby, $orderby, $limit, $having);

		}

	}

	return $lignes;
}
