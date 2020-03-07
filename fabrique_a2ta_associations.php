<?php

/**
 *  Fichier généré par la Fabrique de plugin v6
 *   le 2020-03-06 15:03:17
 *
 *  Ce fichier de sauvegarde peut servir à recréer
 *  votre plugin avec le plugin «Fabrique» qui a servi à le créer.
 *
 *  Bien évidemment, les modifications apportées ultérieurement
 *  par vos soins dans le code de ce plugin généré
 *  NE SERONT PAS connues du plugin «Fabrique» et ne pourront pas
 *  être recréées par lui !
 *
 *  La «Fabrique» ne pourra que régénerer le code de base du plugin
 *  avec les informations dont il dispose.
 *
**/

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

$data = array (
  'fabrique' => 
  array (
    'version' => 6,
  ),
  'paquet' => 
  array (
    'prefixe' => 'a2ta_associations',
    'nom' => 'A2TA Associations',
    'slogan' => '',
    'description' => 'Gestion des données relatives aux associations',
    'logo' => 
    array (
      0 => '',
    ),
    'version' => '1.0.0',
    'auteur' => 'christophe le drean',
    'auteur_lien' => 'http://rouge-rose.net',
    'licence' => 'GNU/GPL v3',
    'categorie' => 'divers',
    'etat' => 'dev',
    'compatibilite' => '[3.2.7;3.2.*]',
    'documentation' => '',
    'administrations' => 'on',
    'schema' => '1.0.0',
    'formulaire_config' => '',
    'formulaire_config_titre' => '',
    'inserer' => 
    array (
      'paquet' => '<necessite nom="coordonnees" />',
      'administrations' => 
      array (
        'maj' => '',
        'desinstallation' => '',
        'fin' => '',
      ),
      'base' => 
      array (
        'tables' => 
        array (
          'fin' => '',
        ),
      ),
    ),
    'scripts' => 
    array (
      'pre_copie' => '',
      'post_creation' => '',
    ),
    'exemples' => '',
  ),
  'objets' => 
  array (
    0 => 
    array (
      'nom' => 'Associations',
      'nom_singulier' => 'Association',
      'genre' => 'feminin',
      'logo' => 
      array (
        0 => '',
        32 => '',
        24 => '',
        16 => '',
        12 => '',
      ),
      'logo_variantes' => '',
      'table' => 'spip_associations',
      'cle_primaire' => 'id_association',
      'cle_primaire_sql' => 'bigint(21) NOT NULL',
      'table_type' => 'association',
      'champs' => 
      array (
        0 => 
        array (
          'nom' => 'Nom',
          'champ' => 'nom',
          'sql' => 'tinytext NOT NULL DEFAULT \'\'',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
            2 => 'obligatoire',
          ),
          'recherche' => '',
          'saisie' => 'input',
          'explication' => '',
          'saisie_options' => '',
        ),
        1 => 
        array (
          'nom' => 'Membre FRAAP',
          'champ' => 'membre_fraap',
          'sql' => 'tinyint(1) NOT NULL DEFAULT 0',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
            2 => 'obligatoire',
          ),
          'recherche' => '',
          'saisie' => 'selection',
          'explication' => '',
          'saisie_options' => 'datas=[(#ARRAY{1,oui,0,non})],defaut=0',
        ),
        2 => 
        array (
          'nom' => 'Date de création',
          'champ' => 'date_creation',
          'sql' => 'datetime NOT NULL DEFAULT \'0000-00-00 00:00:00\'',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
          ),
          'recherche' => '',
          'saisie' => 'date',
          'explication' => '',
          'saisie_options' => '',
        ),
      ),
      'champ_titre' => 'nom',
      'champ_date' => 'date',
      'statut' => 'on',
      'chaines' => 
      array (
        'titre_objets' => 'Associations',
        'titre_objet' => 'Association',
        'info_aucun_objet' => 'Aucune association',
        'info_1_objet' => 'Une association',
        'info_nb_objets' => '@nb@ associations',
        'icone_creer_objet' => 'Créer une association',
        'icone_modifier_objet' => 'Modifier cette association',
        'titre_logo_objet' => 'Logo de cette association',
        'titre_langue_objet' => 'Langue de cette association',
        'texte_definir_comme_traduction_objet' => 'Cette association est une traduction de la association numéro :',
        'titre_\\objets_lies_objet' => 'Liés à cette association',
        'titre_objets_rubrique' => 'Associations de la rubrique',
        'info_objets_auteur' => 'Les associations de cet auteur',
        'retirer_lien_objet' => 'Retirer cette association',
        'retirer_tous_liens_objets' => 'Retirer toutes les associations',
        'ajouter_lien_objet' => 'Ajouter cette association',
        'texte_ajouter_objet' => 'Ajouter une association',
        'texte_creer_associer_objet' => 'Créer et associer une association',
        'texte_changer_statut_objet' => 'Cette association est :',
        'supprimer_objet' => 'Supprimer cette association',
        'confirmer_supprimer_objet' => 'Confirmez-vous la suppression de cette association ?',
      ),
      'liaison_directe' => '',
      'table_liens' => '',
      'afficher_liens' => '',
      'roles' => '',
      'auteurs_liens' => '',
      'vue_auteurs_liens' => '',
      'fichiers' => 
      array (
        'echafaudages' => 
        array (
          0 => 'prive/squelettes/contenu/objet.html',
        ),
        'explicites' => 
        array (
          0 => 'action/supprimer_objet.php',
        ),
      ),
      'autorisations' => 
      array (
        'objet_creer' => '',
        'objet_voir' => '',
        'objet_modifier' => '',
        'objet_supprimer' => '',
        'associerobjet' => '',
      ),
      'boutons' => 
      array (
        0 => 'menu_edition',
      ),
    ),
  ),
  'images' => 
  array (
    'paquet' => 
    array (
      'logo' => 
      array (
        0 => 
        array (
          'extension' => '',
          'contenu' => '',
        ),
      ),
    ),
    'objets' => 
    array (
      0 => 
      array (
        'logo' => 
        array (
          0 => 
          array (
            'extension' => 'png',
            'contenu' => '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',
          ),
          32 => 
          array (
            'extension' => 'png',
            'contenu' => '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',
          ),
        ),
      ),
    ),
  ),
);
