<?php

/**
 *
 * @author            Pierre Duverneix
 * @copyright         2021 Fondation UNIT
 * @license           GPL-2.0-or-later
*/

require_once dirname(__DIR__) . '/../../../wp-load.php';
require plugin_dir_path(__FILE__) . '../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

class LearningPathApi {
    public function getFields() {
        $seeds = Yaml::parseFile(__DIR__ . '/../parcours-hybridation/fields.yml');
        return $seeds;
    }

    public function getData($id) {
        $result = new \stdClass();
        $result = array();
        $files = glob(__DIR__ . '/../parcours-hybridation/**/*.yml');
        
        foreach($files as $filename) {
            $parts = explode("/", $filename);
            $fileId = explode("-", $parts[count($parts)-1])[0];
            if (is_numeric($fileId) && $fileId === $id) {
                return Yaml::parseFile($filename);
            }
        }
        return $result;
    }

    public function filterData($id, $origin) {
        $result = new \stdClass();
        $result = array();
        $files = glob(__DIR__ . '/../parcours-hybridation/**/*.yml');
        
        foreach($files as $filename) {
            $parts = explode("/", $filename);
            $fileId = explode("-", $parts[count($parts)-1])[0];
            if (is_numeric($fileId) && $fileId === $id) {
                $data = Yaml::parseFile($filename);
                $filteredResults = $data;

                foreach ($filteredResults['years'] as &$year) {
                    $toKeep = [];

                    foreach ($year['ue'] as &$ue) {
                        if (isset($ue['resources'])) {
                            $filtered = array_filter($ue['resources'], function($obj) use ($origin, &$toKeep) {
                                if (isset($obj['origin']) && $obj['origin'] == $origin) {
                                    return true;
                                }
                                return false;
                            });

                            $ue['resources'] = $filtered;

                            if (count($filtered) > 0) {
                                array_push($toKeep, self::index_of($year['ue'], $ue));
                            }
                        }

                        // keep the filtered UE
                        $newUEs = [];
                        foreach($toKeep as $val) {
                            array_push($newUEs, $year['ue'][$val]);
                        }
                    }

                    // replace the UE with the filtered values only
                    $year['ue'] = $newUEs;
                }

                $filteredResults['years'] = array_filter($filteredResults['years'], function($obj) {
                    if (!isset($obj['ue']) || count($obj['ue']) == 0) {
                        return false;
                    }
                    return true;
                });

                return $filteredResults;
            }
        }
        return $result;
    }

    private static function index_of($haystack, $element) {
        $elementCount = count($haystack);
        for ($i = 0 ; $i < $elementCount ; $i++){
            if ($element == $haystack[$i]) {
                return $i;   
            }
        }
        return -1;
    }
}
