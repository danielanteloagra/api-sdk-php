<?php

namespace Smartling\Api\Sdk\Tests;

use Smartling\Api\Sdk\SmartlingAPI;

/**
 * Test class for SmartlingAPI.
 */
class SmartlingAPITest extends \PHPUnit_Framework_TestCase
{
    const TEST_FILE = 'tests/test.xml';

    protected $api;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $key  = parse_ini_file('key.ini');
        $this->api = new SmartlingAPI($key['key'], $key['pid']);
    }

    /**
     * @test
     * @covers SmartlingAPI::upload
     */
    public function testUpload()
    {
        // array of texts to send for translation
        $texts = array(
            'field1'=>'some text to translate',
            'field2'=>'some other text to translate'
        );
        $result = $this->api->upload($texts, "simple upload");
        $reply = json_decode($result->content);
        $this->assertNotEmpty($result);
        $this->assertTrue("200" == $result->code);
        $this->assertTrue("SUCCESS" == $reply->response->code);
    }

    /**
     * @test
     * @covers SmartlingAPI::uploadFile
     */
    public function testUploadFile()
    {
        $result = $this->api->uploadFile(self::TEST_FILE, $this->getUploadFileParams());
        $reply = json_decode($result->content);
        $this->assertNotEmpty($result);
        $this->assertTrue("200" == $result->code);
        $this->assertTrue("SUCCESS" == $reply->response->code);
    }

    /**
     * @test
     * @covers SmartlingAPI::downloadFile
     */
    public function testDownloadFile()
    {
        $result = $this->api->downloadFile('testing.xml', 'es-ES');
        $this->assertNotEmpty($result);
    }

    /**
     * @test
     * @covers SmartlingAPI::getStatus
     */
    public function testGetStatus()
    {
        $result = $this->api->getStatus('testing.xml', "es-ES");
        $this->assertNotEmpty($result);
        $this->assertInternalType('string', $result);
    }

    /**
     * @test
     * @covers SmartlingAPI::getList
     */
    public function testGetList()
    {
        $result = $this->api->getList("es-ES");
        $this->assertNotEmpty($result);
    }

    /**
     * @test
     * @covers SmartlingAPI::renameFile
     */
    public function testRenameFile()
    {
        $result = $this->api->renameFile('testing.xml', 'renamedFile.xml');
        $reply = json_decode($result->content);
        $this->assertNotEmpty($result);
        $this->assertTrue("200" == $result->code);
        $this->assertTrue("SUCCESS" == $reply->response->code);
    }

    /**
     * @test
     * @covers SmartlingAPI::import
     */
    public function testImport()
    {
       /* $result = $this->api->import('translated.xml', 'xml', 'es-ES', self::TEST_FILE, true, 'PUBLISHED');
        $reply = json_decode($result->content);
        $this->assertNotEmpty($result);
        $this->assertTrue("200" == $result->code);
        $this->assertTrue("SUCCESS" == $reply->response->code);
        * 
        */
    }

    /**
     * @test
     * @depends testRenameFile
     * @covers SmartlingAPI::deleteFile
     */
    public function testDeleteFile()
    {
        $result = $this->api->deleteFile('renamedFile.xml');
        $reply = json_decode($result->content);
        $this->assertNotEmpty($result);
        $this->assertTrue("200" == $result->code);
        $this->assertTrue("SUCCESS" == $reply->response->code);

    }

    /**
     * Build upload parameters
     *
     */
    private function getUploadFileParams()
    {
        $paramBuilder = $this->api->getParameterBuilder();
        $paramBuilder->setFileUri('testing.xml')
            ->setFileType('xml');
            //->setLocalesToApprove(array('es-ES'))
            //->setOverwriteApprovedLocales(0)
            //->setApproved(1)
            //->setCallbackUrl('http://test.com/smartling');
        return $paramBuilder->buildParameters();
    }

    /**
     *
     * @param  object $object
     * @param  string $methodName
     * @param  array  $parameters
     * @return string | null | int | object | bool | resource | float
     */
    private function invokeMethod(&$object, $methodName, Array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
