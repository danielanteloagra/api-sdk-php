<?php

include_once '../vendor/autoload.php';

use Smartling\Api\Sdk\SmartlingAPI;

$key  = parse_ini_file('../key.ini');

//init api object
$api = new SmartlingAPI($key['key'], $key['pid']);

// file to send
$file = './test.json';
$fileType = 'json';

// file to create in smarlting project
$fileUri = 'doc2.json';

// rename to parameters
$newFileUri = 'newfile.xml';
$fileName = 'translated.xml';

// locales to approve
$locales_array = array('es-ES', 'fr-FR', 'zh-CN', 'en-US');

// locale to retrieve
$locale = 'es-ES';

// import state
$translationState = 'PUBLISHED';

// build upload parameters
echo "\nBuild upload parameters\n";
$paramBuilder = $api->getParameterBuilder();
$paramBuilder->setFileUri($fileUri)
    ->setFileType($fileType);
    //->setLocalesToApprove($locales_array)
    //->setApproved(0);
    //->setOverwriteApprovedLocales(0)
    //->setApproved(1)
    //->setCallbackUrl('http://test.com/smartling');
$uploadParams = $paramBuilder->buildParameters();
var_dump($uploadParams);

// upload file
echo "\nThis is a upload file\n";
$result = $api->uploadFile($file, $uploadParams);
var_dump($result->content);

//  upload content string
echo "\nThis is a upload content\n";
$content = file_get_contents(realpath($file));
$result = $api->uploadContent($content, $uploadParams);
var_dump($result->content);

// download file
echo "\nThis is a download file\n";
$result = $api->downloadFile($fileUri, $locale);
var_dump($result);

// retrieve file status
echo "\nThis is a get status\n";
$result = $api->getStatus($fileUri, $locale);
var_dump($result);

// get files list
echo "\nThis is a get list\n";
$result = $api->getList($locale);
var_dump($result);

// rename file
echo "\nThis is a rename file\n";
$result = $api->renameFile($fileUri, $newFileUri);
var_dump($result->content);

// import
/*
echo "\nThis is a import file\n";
$result = $api->import($newFileUri, $fileType, $locale, $file, true, $translationState);
var_dump($result->content);
 * 
 */

// delete file
echo "\nThis is delete file\n";
$result = $api->deleteFile($newFileUri);
var_dump($result->content);