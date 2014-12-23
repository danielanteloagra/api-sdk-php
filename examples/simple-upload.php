<?php

include_once '../vendor/autoload.php';

use Smartling\Api\Sdk\SmartlingAPI;

$key  = parse_ini_file('../key.ini');

// init api object
$api = new SmartlingAPI($key['key'], $key['pid']);

// array of texts to send for translation
$texts = array(
    'field1'=>'my newer txt to translate'
);

/*
 *  send texts to Smartling in "Awaiting Authorization" status for all languages
 *  as a file named SampleTexts
 */
$result = $api->upload($texts, 'prop-1234');
var_dump($result);

/*
 * to then retrieve the translated content in the same array fomat we would
 * use getTranslations
 */
$result = $api->getTranslations('prop-1234', 'es-ES');
var_dump($result);

