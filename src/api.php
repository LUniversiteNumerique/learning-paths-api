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

        foreach ($files as $filename) {
            $parts = explode("/", $filename);
            $fileId = explode("-", $parts[count($parts) - 1])[0];

            if (is_numeric($fileId) && $fileId === $id) {
                $data = Yaml::parseFile($filename);

                return $this->addMoodleAttribute($data);
            }
        }

        return $result;
    }

    public function filterData($id, $origin) {
        $files = glob(__DIR__ . '/../parcours-hybridation/**/*.yml');

        foreach ($files as $filename) {
            $parts  = explode('/', $filename);
            $fileId = explode('-', end($parts))[0];

            if (!is_numeric($fileId) || (int)$fileId !== (int)$id) {
                continue;
            }

            $data = Yaml::parseFile($filename);
            $data = $this->addMoodleAttribute($data);

            // Flat structure
            if (isset($data['resources']) && is_array($data['resources'])) {
                $data['resources'] = array_values(array_filter(
                    $data['resources'],
                    function ($resource) use ($origin) {
                        return isset($resource['url']) &&
                            stripos($resource['url'], $origin) !== false;
                    }
                ));

                return $data;
            }

            // Structure with years
            if (!isset($data['years']) || !is_array($data['years'])) {
                return [];
            }

            foreach ($data['years'] as $yKey => &$year) {
                // Avoid errors if ue is empty.
                if (empty($year['ue']) || !is_array($year['ue'])) {
                    unset($data['years'][$yKey]);
                    continue;
                }

                foreach ($year['ue'] as $ueKey => &$ue) {
                    if (!isset($ue['resources'])) {
                        unset($year['ue'][$ueKey]);
                        continue;
                    }

                    $ue['resources'] = array_values(array_filter(
                        $ue['resources'],
                        function ($resource) use ($origin) {
                            return isset($resource['url'])
                                && stripos($resource['url'], $origin) !== false;
                        }
                    ));

                    // Remove the UE if no remaining resources after filtering
                    if (empty($ue['resources'])) {
                        unset($year['ue'][$ueKey]);
                    }
                }

                // Reindex UE after removals
                $year['ue'] = array_values($year['ue']);

                // Remove the year if no remaining UE
                if (empty($year['ue'])) {
                    unset($data['years'][$yKey]);
                }
            }

            // Reindex years array after removals
            $data['years'] = array_values($data['years']);

            return $data;
        }

        return [];
    }

    private function addMoodleAttribute(array $data): array {
        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                continue;
            }

            // If the element has an URL, it's a resource
            if (isset($value['url']) && is_string($value['url'])) {
                $value['moodle'] = stripos($value['url'], 'moodle') !== false;
            }

            // Walk the children recursively
            $data[$key] = $this->addMoodleAttribute($value);
        }

        return $data;
    }
}
