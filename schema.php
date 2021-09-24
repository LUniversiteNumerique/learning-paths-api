<?php

/**
 *
 * @author            Pierre Duverneix
 * @copyright         2021 Fondation UNIT
 * @license           GPL-2.0-or-later
*/

require_once dirname( __DIR__ ) . '/../../wp-load.php';
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

class LPASchema {
    public function __construct($table_prefix, $charset_collate) {
        $this->table_prefix = $table_prefix;
        $this->charset_collate = $charset_collate;
    }

    public function build_tables() {
        $tables = array();

        $table_name = $this->table_prefix . "learningpathsapi_field"; 
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            PRIMARY KEY (id)
        ) $this->charset_collate;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_diploma"; 
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            field_id mediumint(9) NOT NULL,
            name varchar(255) NOT NULL,
            description text,
            PRIMARY KEY (id),
            FOREIGN KEY (field_id)
                REFERENCES " . $this->table_prefix . "learningpathsapi_field(id)
                ON DELETE CASCADE
        ) $this->charset_collate;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_year"; 
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            PRIMARY KEY (id)
        ) $this->charset_collate;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_ue"; 
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            PRIMARY KEY (id)
        ) $this->charset_collate;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_resource"; 
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            volume varchar(8),
            url text DEFAULT '',
            PRIMARY KEY (id)
        ) $this->charset_collate;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_resourcetype"; 
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            PRIMARY KEY (id)
        ) $this->charset_collate;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_diploma_year_ue"; 
        $sql = "CREATE TABLE $table_name (
            diploma_id mediumint(9) NOT NULL,
            year_id mediumint(9) NOT NULL,
            ue_id mediumint(9) NOT NULL,
            FOREIGN KEY (diploma_id)
                REFERENCES " . $this->table_prefix . "learningpathsapi_diploma(id)
                ON DELETE CASCADE,
            FOREIGN KEY (year_id)
                REFERENCES " . $this->table_prefix . "learningpathsapi_year(id)
                ON DELETE CASCADE,
            FOREIGN KEY (ue_id)
                REFERENCES " . $this->table_prefix . "learningpathsapi_ue(id)
                ON DELETE CASCADE
        ) $this->charset_collate;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_resource_resourcetype"; 
        $sql = "CREATE TABLE $table_name (
            resource_id mediumint(9) NOT NULL,
            resourcetype_id mediumint(9) NOT NULL,
            FOREIGN KEY (resource_id)
                REFERENCES " . $this->table_prefix . "learningpathsapi_resource(id)
                ON DELETE CASCADE,
            FOREIGN KEY (resourcetype_id)
                REFERENCES " . $this->table_prefix . "learningpathsapi_resourcetype(id)
                ON DELETE CASCADE
        ) $this->charset_collate;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_ue_resource"; 
        $sql = "CREATE TABLE $table_name (
            ue_id mediumint(9) NOT NULL,
            resource_id mediumint(9) NOT NULL,
            FOREIGN KEY (ue_id)
                REFERENCES " . $this->table_prefix . "learningpathsapi_ue(id)
                ON DELETE CASCADE,
            FOREIGN KEY (resource_id)
                REFERENCES " . $this->table_prefix . "learningpathsapi_resource(id)
                ON DELETE CASCADE
        ) $this->charset_collate;";
        array_push($tables, $sql);
        unset($sql);

        return $tables;
    }

    public function seed() {
        $seeds = array();

        // learningpathsapi_field

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_field"; 
        $seed->data = array("name" => "Sciences, Technologie, Santé");
        array_push($seeds, $seed);
        unset($seed);
        
        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_field"; 
        $seed->data = array("name" => "Sciences Humaines et Sociales");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_field"; 
        $seed->data = array("name" => "Droit, Économie et Gestion");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_field"; 
        $seed->data = array("name" => "Arts, Lettres et Langues");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_field"; 
        $seed->data = array("name" => "Compétences transversales");
        array_push($seeds, $seed);
        unset($seed);

        // learningpathsapi_diploma

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_diploma"; 
        $seed->data = array(
            "name" => "Licence mention informatique",
            "field_id" => 1,
            "description" => "Ce parcours d’<strong>informatique générale</strong> permet d’hybrider les enseignements des domaines suivants : concepts informatiques, algorithmique et programmation, systèmes d’exploitation, outils et technologies Internet, bases de données. Il propose également une composante mathématique importante."
        );
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_diploma"; 
        $seed->data = array(
            "name" => "DUT spécialité informatique",
            "field_id" => 1,
            "description" => "Ce DUT forme des assistants ingénieurs et des chefs de projet en <strong>informatique de gestion et en informatique industrielle</strong>. Immédiatement opérationnels en développement logiciel et matériel, ils participent à la conception, la réalisation et la mise en œuvre de systèmes informatiques en fonction de cahiers des charges. Ils sont capables de répondre aux besoins en matière d’administration de réseaux, de conception et de réalisation de programmes, d’assistance technique, de gestion de bases de données."
        );
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_diploma"; 
        $seed->data = array(
            "name" => "DUT spécialité réseaux et télécoms",
            "field_id" => 1,
            "description" => "Le titulaire de ce DUT peut constituer ou analyser un cahier des charges, élaborer ou choisir des solutions techniques et des produits. Il peut aussi installer et mettre au point des équipements (réseaux, environnements applicatifs, systèmes d’exploitation) et assurer leur maintenance. Il peut s’agir de <strong>réseaux informatiques classiques, à intégration de services, mobiles ou autres.</strong>"
        );
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_diploma"; 
        $seed->data = array(
            "name" => "Licence mention sciences pour l’ingénieur",
            "field_id" => 1,
            "description" => "Ce parcours est composé de contenus pluridisciplinaires ayant pour objectif l’acquisition d’un tronc commun dans lequel s’articulent mathématiques, physique et informatique. Elle permet également de former les étudiants en sciences pour l’ingénieur dans les domaines de l’électronique, des matériaux, de la mécanique etc."
        );
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_diploma"; 
        $seed->data = array(
            "name" => "Licence mention mécanique",
            "field_id" => 1,
            "description" => "Ce parcours type apporte des ressources pour hybrider cette formation principalement orientée vers la modélisation, la simulation numérique et l’expérimentation en mécanique. Elle met l’accent sur un ensemble de connaissances fondamentales en mécanique, mathématiques, informatique, physique, nécessaires pour assurer la pluridisciplinarité qui caractérise cette science du mouvement."
        );
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_diploma"; 
        $seed->data = array(
            "name" => "Licence mention génie civil",
            "field_id" => 1,
            "description" => "Ce parcours type propose des ressources éducatives libres pour préparer aux métiers du bâtiment et des travaux publics. Elle forme les étudiants avec une approche scientifique pluridisciplinaire en première année et une spécialisation ensuite."
        );
        array_push($seeds, $seed);
        unset($seed);

        // learningpathsapi_year

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_year"; 
        $seed->data = array("name" => "1ère année");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_year"; 
        $seed->data = array("name" => "2ème année");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_year"; 
        $seed->data = array("name" => "3ème année");
        array_push($seeds, $seed);
        unset($seed);

        // learningpathsapi_ue

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_ue"; 
        $seed->data = array("name" => "Algorithmique et programmation");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_ue"; 
        $seed->data = array("name" => "Internet et outils");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_ue"; 
        $seed->data = array("name" => "Base de données 1");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_ue"; 
        $seed->data = array("name" => "Systèmes d’exploitation");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_ue"; 
        $seed->data = array("name" => "Réseaux informatiques 1");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_ue"; 
        $seed->data = array("name" => "Calculs et mathématiques 1");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_ue"; 
        $seed->data = array("name" => "Suites et séries");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_ue"; 
        $seed->data = array("name" => "Logique et raisonnement");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_ue"; 
        $seed->data = array("name" => "Algèbre linéaire");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_ue"; 
        $seed->data = array("name" => "Nombres complexes");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_ue"; 
        $seed->data = array("name" => "Équations différentielles");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_ue"; 
        $seed->data = array("name" => "Géométrie");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_ue"; 
        $seed->data = array("name" => "Anglais");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_ue"; 
        $seed->data = array("name" => "Méthodologie");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_ue"; 
        $seed->data = array("name" => "Orthographe scientifique");
        array_push($seeds, $seed);
        unset($seed);

        // learningpathsapi_resource
        /*
        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resource"; 
        $seed->data = array(
            "name" => "",
            "volume" => "",
            "url" => ""
        );
        array_push($seeds, $seed);
        unset($seed);
        */

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resource"; 
        $seed->data = array(
            "name" => "Socle en informatique",
            "volume" => "33h",
            "url" => "https://www.fun-campus.fr/fr/cours/socle-en-informatique/"
        );
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resource"; 
        $seed->data = array(
            "name" => "Cfacile : Introduction au langage C",
            "volume" => "40h",
            "url" => "http://www.unit.eu/moteur-ressources-educatives-libres/notice/view/unit-ori-wf-1-3577"
        );
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resource"; 
        $seed->data = array(
            "name" => "Algorithmique et programmation",
            "volume" => "",
            "url" => "http://www.unit.eu/moteur-ressources-educatives-libres/notice/view/unit-ori-wf-1-3437"
        );
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resource"; 
        $seed->data = array(
            "name" => "Algorithmique et programmation",
            "volume" => "",
            "url" => "https://unisciel.org/r/25.4/algoprog/s00aaroot/aa00321/co/aa00321_web.html"
        );
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resource"; 
        $seed->data = array(
            "name" => "Programmation en langage C",
            "volume" => "",
            "url" => "http://www.unit.eu/moteur-ressources-educatives-libres/notice/view/unit-ori-wf-1-3003"
        );
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resource"; 
        $seed->data = array(
            "name" => "Programmation C++ (2019)",
            "volume" => "40h",
            "url" => "https://unisciel.org/r/3socles3.4/11/12.php0id=225"
        );
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resource"; 
        $seed->data = array(
            "name" => "Programmation Python (2019)",
            "volume" => "40h",
            "url" => "https://unisciel.org/r/3socles3.4/11/12.php0id=222"
        );
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resource"; 
        $seed->data = array(
            "name" => "Java’s Cool",
            "volume" => "",
            "url" => "https://unisciel.org/r/2javascool.gforge.inria70/28.php"
        );
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resource"; 
        $seed->data = array(
            "name" => "Introduction à la programmation en assembleur",
            "volume" => "",
            "url" => "https://unisciel.org/r/2e-5.9-avignon70/assembleur/co/65_webUnisciel.html"
        );
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resource"; 
        $seed->data = array(
            "name" => "Apprendre à programmer avec des cartes",
            "volume" => "",
            "url" => "https://unisciel.org/r/25.4/progcartes/co/_web.html"
        );
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resource"; 
        $seed->data = array(
            "name" => "Technologies Web 1",
            "volume" => "",
            "url" => "https://moodle.insa-rouen.fr/course/view.php?id=153"
        );
        array_push($seeds, $seed);
        unset($seed);

        // learningpathsapi_resourcetype

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resourcetype"; 
        $seed->data = array("name" => "cours complet");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resourcetype"; 
        $seed->data = array("name" => "MOOC");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resourcetype"; 
        $seed->data = array("name" => "module");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resourcetype"; 
        $seed->data = array("name" => "TP exercices");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resourcetype"; 
        $seed->data = array("name" => "quiz");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resourcetype"; 
        $seed->data = array("name" => "grain ou éléments de cours");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resourcetype"; 
        $seed->data = array("name" => "TD");
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resourcetype"; 
        $seed->data = array("name" => "études de cas");
        array_push($seeds, $seed);
        unset($seed);

        // learningpathsapi_diploma_year_ue

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_diploma_year_ue"; 
        $seed->data = array(
            "diploma_id" => 1,
            "year_id" => 1,
            "ue_id" => 1
        );
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_diploma_year_ue"; 
        $seed->data = array(
            "diploma_id" => 1,
            "year_id" => 1,
            "ue_id" => 2
        );
        array_push($seeds, $seed);
        unset($seed);

        // learningpathsapi_resource_resourcetype
        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resource_resourcetype"; 
        $seed->data = array("resource_id" => 1, "resourcetype_id" => 1);
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resource_resourcetype"; 
        $seed->data = array("resource_id" => 1, "resourcetype_id" => 2);
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resource_resourcetype"; 
        $seed->data = array("resource_id" => 2, "resourcetype_id" => 3);
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resource_resourcetype"; 
        $seed->data = array("resource_id" => 3, "resourcetype_id" => 1);
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resource_resourcetype"; 
        $seed->data = array("resource_id" => 3, "resourcetype_id" => 2);
        array_push($seeds, $seed);
        unset($seed);

        $seed = new \stdClass();
        $seed->table = $this->table_prefix . "learningpathsapi_resource_resourcetype"; 
        $seed->data = array("resource_id" => 4, "resourcetype_id" => 3);
        array_push($seeds, $seed);
        unset($seed);

        // learningpathsapi_ue_resource

        $i = 1;
        for ($i; $i < 12; $i++) {
            $seed = new \stdClass();
            $seed->table = $this->table_prefix . "learningpathsapi_ue_resource"; 
            $seed->data = array("ue_id" => 1, "resource_id" => $i);
            array_push($seeds, $seed);
            unset($seed);
        }
    
        return $seeds;
    }

    public function drop_tables() {
        $tables = array();

        // Drop tables

        $table_name = $this->table_prefix . "learningpathsapi_field"; 
        $sql = "DROP TABLE IF EXISTS $table_name;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_year"; 
        $sql = "DROP TABLE IF EXISTS $table_name;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_ue"; 
        $sql = "DROP TABLE IF EXISTS $table_name;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_resource"; 
        $sql = "DROP TABLE IF EXISTS $table_name;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_resourcetype"; 
        $sql = "DROP TABLE IF EXISTS $table_name;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_diploma_year_ue";
        $sql = "DROP TABLE IF EXISTS $table_name;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_resource_resourcetype"; 
        $sql = "DROP TABLE IF EXISTS $table_name;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_ue_resource"; 
        $sql = "DROP TABLE IF EXISTS $table_name;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_diploma"; 
        $sql = "DROP TABLE IF EXISTS $table_name;";
        array_push($tables, $sql);
        unset($sql);

        return $tables;
    }
};