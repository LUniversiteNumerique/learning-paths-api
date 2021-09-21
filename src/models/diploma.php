<?php

/**
 * Plugin Name: Learning Paths API
 *
 * @author            Pierre Duverneix
 * @copyright         2021 Fondation UNIT
 * @license           GPL-2.0-or-later
 * Plugin URI:        https://example.com/plugin-name
 * Description:       Learning paths API of L'Université Numérique
 * Version:           1.0.0
*/

require_once dirname( __DIR__ ) . '/../../../../wp-load.php';

class Diploma {
    protected function prepareDiploma($diplomaid) {
        global $wpdb;
        return $wpdb->get_row(
            $wpdb->prepare(
                "SELECT diploma.*
                FROM {$wpdb->prefix}learningpathsapi_diploma as diploma
                WHERE diploma.id = %d LIMIT 1", $diplomaid,
            ),
            OBJECT
        );
    }

    protected function prepareDiplomaYears($diplomaid) {
        global $wpdb;
        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT year.*
                FROM {$wpdb->prefix}learningpathsapi_year as year
                WHERE year.id IN (
                    SELECT dyu.year_id 
                    FROM {$wpdb->prefix}learningpathsapi_diploma_year_ue as dyu
                    WHERE dyu.diploma_id = %d
                )", $diplomaid,
            ),
            OBJECT
        );
    }

    protected function prepareDiplomaYearUe($diplomaid, $yearid) {
        global $wpdb;
        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ue.*
                FROM {$wpdb->prefix}learningpathsapi_ue as ue
                INNER JOIN {$wpdb->prefix}learningpathsapi_diploma_year_ue as dyu ON dyu.ue_id = ue.id 
                WHERE dyu.diploma_id = %d
                AND dyu.year_id = %d", array($diplomaid, $yearid),
            ),
            OBJECT
        );
    }

    protected function prepareResources($ueid) {
        global $wpdb;
        return $wpdb->get_results(
            $wpdb->prepare("SELECT resource.*, GROUP_CONCAT(rtype.name) AS types
                FROM wp_learningpathsapi_resource as resource
                LEFT OUTER JOIN wp_learningpathsapi_ue_resource ur 
                    ON ur.resource_id = resource.id
                LEFT OUTER JOIN wp_learningpathsapi_resource_resourcetype rrtype 
                    ON rrtype.resource_id = resource.id
                INNER JOIN wp_learningpathsapi_resourcetype rtype 
                    ON rtype.id = rrtype.resourcetype_id
                WHERE ur.ue_id = %d
                GROUP BY resource.id
                ORDER BY resource.id ASC", $ueid
            ),
            OBJECT
        );
    }

    public function getDiplomas() {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}learningpathsapi_diploma", OBJECT);
    }

    public function getDiploma($id) {
        $result = new \stdClass();
        $diploma = $this->prepareDiploma($id);

        if (isset($diploma->name)) {
            $result->name = $diploma->name;
            $diplomaYears = $this->prepareDiplomaYears($diploma->id);

            if (isset($diplomaYears)) {
                $result->years = array();
                foreach($diplomaYears as $dy) {
                    $year = new \stdClass;
                    $year->name = $dy->name;
                    $year->ue = array();

                    $diplomaYearUe = $this->prepareDiplomaYearUe($diploma->id, $dy->id);

                    foreach($diplomaYearUe as $dyu) {
                        $ue = new \stdClass;
                        $ue->name = $dyu->name;
                        $ue->resources = array();

                        $resources = $this->prepareResources($dyu->id);

                        foreach($resources as $resource) {
                            $res = new \stdClass;
                            $res->name = $resource->name;
                            $res->url = $resource->url;
                            $res->type[] = $resource->types;
                            $ue->resources[] = $res;
                        }

                        $year->ue[] = $ue;
                    }

                    $result->years[] = $year;
                }
            }
        }
        return $result;
    }
}
