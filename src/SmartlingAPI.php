<?php

namespace Smartling\Api\Sdk;

use Curl\Sdk\HttpClient;
use Smartling\Api\Sdk\Util\ParameterBuilder;
use Smartling\Api\Sdk\Util\ContentBuilder;

class SmartlingAPI
{
    const SANDBOX_MODE = 'SANDBOX';
    const PRODUCTION_MODE = 'PRODUCTION';
    const SANDBOX_URL = 'https://sandbox-api.smartling.com/v1';
    const PRODUCTION_URL = 'https://api.smartling.com/v1';

    /**
     * @var string
     */
    protected $baseUrl = "";

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $projectId;

    /**
     * @var HttpClient
     */
    protected $connection = null;

    /**
     * @var ParameterBuilder
     */
    protected $paramBuilder = null;

    /**
     * @var ContentBuilder
     */
    protected $contentBuilder = null;

    /**
     *
     * @param string  $apiKey
     * @param string  $projectId
     * @param boolean $sandbox
     */
    public function __construct($apiKey, $projectId, $sandbox = false)
    {
        $this->apiKey = $apiKey;
        $this->projectId = $projectId;
        $this->connection = new HttpClient();
        $this->paramBuilder = new ParameterBuilder();
        $this->contentBuilder = new ContentBuilder();
        if ($sandbox) {
            $this->baseUrl = self::SANDBOX_URL;
        } else {
            $this->baseUrl = self::PRODUCTION_URL;
        }
    }

    /**
     * upload translations from an array to Smartling
     *
     * @param  array     $values
     * @param  string    $name
     * @param  string    $callback
     * @return \stdClass $response
     */
    public function upload(array $values, $name, $callback = null)
    {
        // build the content
        $content = $this->contentBuilder->getJsonContent($values);

        // build the params
        $this->paramBuilder->reset()
            ->setFileUri($name)
            ->setFileType('json');

        // add callback if needed
        if (!is_null($callback)) {
            $this->paramBuilder->setCallbackUrl($callback);
        }

        return $this->uploadContent($content, $this->paramBuilder->buildParameters());
    }

    /**
     * gets the translations for a file and locale in array format
     *
     * @param type $fileUri
     * @param type $locale
     * @return array
     */
    public function getTranslations($fileUri, $locale)
    {
        $json = $this->downloadFile($fileUri, $locale);
        $translations = $this->contentBuilder->getContentFromJson($json);

        return $translations;
    }

    /**
     * upload content string to Smartling service
     *
     * @param  string    $content
     * @param  array     $params
     * @return \stdClass $response
     */
    public function uploadContent($content, Array $params = array())
    {
        // less dump the content into a temporary file
        $tempfile = sprintf('%s.%s', tempnam("/tmp", "tmp"), $params['fileType']);
        $handle = fopen($tempfile, "w");
        fwrite($handle, $content);
        fclose($handle);
        // send the tmp file
        $result = $this->uploadFile($tempfile, $params);
        // delete tmp file
        @unlink($tempfile);

        return $result;
    }

    /**
     * upload file to Smartling service
     *
     * @param  string    $path
     * @param  array     $params
     * @return \stdClass $response
     */
    public function uploadFile($path, Array $params = array())
    {
        $url = sprintf('%s/file/upload', $this->baseUrl);
        $params = array_merge($params, array(
            'apiKey' => $this->apiKey,
            'projectId' => $this->projectId,
            'file' => $this->getFile($path)
        ));

        return $this->connection->post($url, $params);
    }

    /**
     * download translated content from Smartling Service
     *
     * @param  string $fileUri
     * @param  string $locale
     * @return string
     */
    public function downloadFile($fileUri, $locale)
    {
        $url = sprintf(
            '%s/file/get?apiKey=%s&projectId=%s&fileUri=%s&locale=%s',
            $this->baseUrl,
            $this->apiKey,
            $this->projectId,
            $fileUri,
            $locale
        );

        return $this->connection->get($url)->content;
    }

    /**
     * retrieve status about file translation progress
     *
     * @param  string $fileUri
     * @param  string $locale
     * @return string
     */
    public function getStatus($fileUri, $locale)
    {
        $url = sprintf(
            '%s/file/status?apiKey=%s&projectId=%s&fileUri=%s&locale=%s',
            $this->baseUrl,
            $this->apiKey,
            $this->projectId,
            $fileUri,
            $locale
        );

        return $this->connection->get($url)->content;
    }

    /**
     * get uploaded files list
     *
     * @param  string $locale
     * @return string
     */
    public function getList($locale = '')
    {
        $url = sprintf(
            '%s/file/list?apiKey=%s&projectId=%s&locale=%s',
            $this->baseUrl,
            $this->apiKey,
            $this->projectId,
            $locale
        );

        return $this->connection->get($url)->content;
    }

    /**
     * rename uploaded file
     *
     * @param  string    $fileUri
     * @param  string    $newFileUri
     * @return \stdClass $response
     */
    public function renameFile($fileUri, $newFileUri)
    {
        $url = sprintf('%s/file/rename', $this->baseUrl);
        $params = array(
            'apiKey' => $this->apiKey,
            'projectId' => $this->projectId,
            'fileUri' => $fileUri,
            'newFileUri' => $newFileUri,
        );

        return $this->connection->post($url, $params);
    }

    /**
     * remove uploaded files from Smartling Service
     *
     * @param  string    $fileUri
     * @return \stdClass $response
     */
    public function deleteFile($fileUri)
    {
        $url = sprintf('%s/file/delete', $this->baseUrl);
        $params = array(
            'apiKey' => $this->apiKey,
            'projectId' => $this->projectId,
            'fileUri' => $fileUri,
        );

        return $this->connection->post($url, $params);
    }

    /**
     * import files form Service
     *
     * @param  string    $fileUri
     * @param  string    $fileType
     * @param  string    $locale
     * @param  string    $file
     * @param  string    $overwrite
     * @param  string    $translationState
     * @return \stdClass $response
     */
    public function import($fileUri, $fileType, $locale, $file, $overwrite, $translationState)
    {
        $url = sprintf('%s/file/delete', $this->baseUrl);
        $params = array(
            'apiKey' => $this->apiKey,
            'projectId' => $this->projectId,
            'fileUri' => $fileUri,
            'fileType' => $fileType,
            'locale' => $locale,
            'file' => $this->getFile($file),
            'overwrite' => $overwrite,
            'translationState' => $translationState,
        );

        return $this->connection->post($url, $params);
    }

    /**
     * Returns the file upload parameter builder
     *
     * @return ParameterBuilder
     */
    public function getParameterBuilder()
    {
        return $this->paramBuilder;
    }

    /**
     * Returns the content builder
     *
     * @return ContentBuilder
     */
    public function getContentBuilder()
    {
        return $this->contentBuilder;
    }

    /**
     *
     * @param string $path
     * @return CURLFile|@String $file
     */
    private function getFile($path)
    {
        if (class_exists('\CURLFile')) {
            return new \CURLFile(realpath($path));
        } else {
            return '@'.realpath($path);
        }
    }
}
