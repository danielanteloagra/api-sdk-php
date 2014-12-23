<?php

namespace Smartling\Api\Sdk\Util;

class ParameterBuilder
{
    /**
     * api parameters array
     *
     * @var array
     */
    private $parametersArray = array();

    /**
     * api file type
     *
     * @var string
     */
    private $fileType = "";

    /**
     * api file uri
     *
     * @var string
     */
    private $fileUri = "";

    /**
     * api callback url
     *
     * @var string
     */
    private $callbackUrl = "";

    /**
     * api approved
     *
     * @var bool
     */
    private $approved = false;

    /**
     * api locales to approve
     *
     * @var array
     */
    private $localesToApprove = array();

    /**
     * api overwrite approved locales
     *
     * @var bool
     */
    private $overwriteApprovedLocales = false;

    /**
     * Resets all the parameters
     *
     */
    public function reset()
    {
        $this->parametersArray = array();

        return $this;
    }

    /**
     * set parameter approved
     *
     * @param bool $approved
     */
    public function setApproved($approved = true)
    {
        $this->approved = $approved;
        $this->parametersArray['approved'] = $approved;

        return $this;
    }

    /**
     * set parameter fileType
     *
     * @param string $fileType
     */
    public function setFileType($fileType)
    {
        $this->fileType = $fileType;
        $this->parametersArray['fileType'] = $fileType;

        return $this;
    }

    /**
     * set parameter fileUri
     *
     * @param string $fileUri
     */
    public function setFileUri($fileUri)
    {
        $this->fileUri = $fileUri;
        $this->parametersArray['fileUri'] = $fileUri;

        return $this;
    }

    /**
     * set parameter callbackUrl
     *
     * @param string $callbackUrl
     */
    public function setCallbackUrl($callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;
        $this->parametersArray['callbackUrl'] = $callbackUrl;

        return $this;
    }

    /**
     * set parameter overwriteApprovedLocales
     *
     * @param bool $overwriteApprovedLocales
     */
    public function setOverwriteApprovedLocales($overwriteApprovedLocales = 0)
    {
        $this->overwriteApprovedLocales = (int) $overwriteApprovedLocales;
        $this->parametersArray['overwriteApprovedLocales'] = (int) $overwriteApprovedLocales;

        return $this;
    }

    /**
     * set parameter localesToApprove
     *
     * @param array $localesToApprove
     */
    public function setLocalesToApprove(Array $localesToApprove)
    {
        if (is_array($localesToApprove)) {
            $this->localesToApprove = array_unique($localesToApprove);
            $i = 0;
            foreach ($localesToApprove as $locale_code) {
                // locale must be in format xx-XX
                $formated_locale = str_replace("_", "-", $locale_code);
                $this->parametersArray['localesToApprove['.$i.']'] = $formated_locale;
                $i++;
            }
        }

        return $this;
    }

    /**
     * return all parameters
     *
     * @return array
     */
    public function buildParameters()
    {
        $params = array();
        foreach ($this->parametersArray as $key => $value) {
            $params[$key] = $value;
        }

        return $params;
    }
}
