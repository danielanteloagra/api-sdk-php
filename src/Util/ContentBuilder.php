<?php

namespace Smartling\Api\Sdk\Util;

class ContentBuilder
{
    /**
     * Converts an array into smartling json for upload
     *
     * @var array $values
     * @return String $json
     */
    public function getJsonContent(array $values)
    {
        $content = array();
        $content['smartling'] = array(
            'translate_mode' => 'custom',
            'translate_paths' => ["*/translation"],
            'source_key_paths' => ["{*}/translation"],
            'placeholder_format_custom' => ["\\{([^\\}]*)\\}"],
            'variants_enabled' => true,
        );

        foreach ($values as $key => $value) {
            $content[$key] = array('translation' => $value);
        }

        return json_encode($content);
    }

    /**
     * Converts the smartling json into the original array
     * inverse of getJsonContent
     *
     * @param string $json
     * @return array
     */
    public function getContentFromJson($json)
    {
        $tmp = json_decode($json, true);
        unset($tmp['smartling']);

        $content = array();
        foreach ($tmp as $key => $value) {
            $content[$key] = $value['translation'];
        }

        return $content;
    }
}
