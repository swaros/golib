<?php

namespace Content\Dom;

use golib\Content\Dom\XmlSimple;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;

class XmlSimpleTest extends TestCase
{
    public function testXmlParsing() {
        $source = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<data>
  <entrypoint id="1337" url="github.com" geocode="de">
    <subentrie parent="1337" region="EU"/>
    <database>
      <host>hostname-1337</host>
      <name>database-1337</name>
      <username>chuck-norris-at-1337</username>
      <password><![CDATA[1722afcfGt#5fb3/(kj66645%a]]></password>
    </database>
  </entrypoint>
  <entrypoint id="1007" url="google.com" geocode="us">
    <subentrie parent="1337" region="EU"/>
    <database>
      <host>hostname-1007</host>
      <name>database-1007</name>
      <username>lemurs-choice</username>
      <password><![CDATA[ZtUtBnhtre%%&545789_kkglsdhm]]></password>
    </database>
  </entrypoint>
</data>
EOT;
        $xml = new TestDatabaseConfig1($source);
        $this->assertInstanceOf(TestDatabaseConfig1::class, $xml);

        $config = $xml->getSetupByInstance(1337);

        $this->assertEquals("1722afcfGt#5fb3/(kj66645%a", $config->password);
        $this->assertEquals("database-1337", $config->name);
        $this->assertEquals("chuck-norris-at-1337", $config->username);
        $this->assertEquals("hostname-1337", $config->host);

        $config2 = $xml->getSetupByInstance(1007);
        $this->assertEquals("ZtUtBnhtre%%&545789_kkglsdhm", $config2->password);

        $content = $xml->getNodeContent('//*[@id=1007]/database/host');
        $this->assertEquals("hostname-1007", $content);

        $contentNull = $xml->getNodeContent('uno');
        $this->assertEquals( '', $contentNull);

        $keyArr = $xml->getNodeKeyedArray('//data','url','id');

        $this->assertArrayHasKey( 'github.com', $keyArr);
        $this->assertArrayHasKey( 'google.com', $keyArr);
        $this->assertEquals( 1337, $keyArr['github.com']);
        $this->assertEquals( 1007, $keyArr['google.com']);

        // same without flatting
        $keyArr = $xml->getNodeKeyedArray('//data','url','id', false);

        $this->assertArrayHasKey( 'github.com', $keyArr[0]);
        $this->assertArrayHasKey( 'google.com', $keyArr[1]);
        $this->assertEquals( 1337, $keyArr[0]['github.com']);
        $this->assertEquals( 1007, $keyArr[1]['google.com']);

        //$nodeArr = $xml->getNodeAttrAsArray('//data/entrypoint/database');
        $nodeArr = $xml->getNodeAttrAsArray('//data/*');
        $this->assertArrayHasKey( 'id', $nodeArr);
        $this->assertEquals('1337',$nodeArr['id']);

        $nodeArr = $xml->getNodeAttrAsArray('//lala/*');
        $this->assertEmpty($nodeArr);

        $nodeAttr = $xml->getNodeAttribute('//*[@id=1007]/subentrie','parent');
        $this->assertEquals("1337", $nodeAttr);

        $this->assertNull($xml->getNodeAttribute('//*[@id=888]/subentrie','parent'));

        // for debug
        #$this->assertTrue(false);
    }

    public function testFailXml() {
        $source = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<data>
  <entrypoint id="1337" url="github.com" geocode="de">
    <subentrie parent="1337" region="EU"/>
    <database>
      <host>hostname-1337</host>
      <name>database-1337</name>
      <username>chuck-norris-at-1337</username>
      <password><![CDATA[1722afcfGt#5fb3/(kj66645%a]]></password>
    </database>
  </entrypoint
  <entrypoint id="1007" url="google.com" geocode="us">
    <subentrie parent="1337" region="EU"/>
    <database>
      <host>hostname-1007</host>
      <name>database-1007</name>
      <username>lemurs-choice</username>
      <password><![CDATA[ZtUtBnhtre%%&545789_kkglsdhm]]></password>
    </database>
  </entrypoint>
</data>
EOT;
        $this->expectError();
        new TestDatabaseConfig1($source);
    }
}




################ test class ##################

class TestDatabaseConfig1 extends XmlSimple {

    public function getSetupByInstance($id)
    {
        $node = $this->xPath('//*[@id=' . $id . ']/database');
        if ($node && is_array($node) && !empty($node)) {
            $prop = new TestDatabaseProperty();
            $prop->applyFromXml(current($node));
            return $prop;
        } else {
            throw new InvalidArgumentException("invalid id:{$id} ");
        }

    }
}

class TestDatabaseProperty {
    public string $host;
    public string $name;
    public string $username;
    public string $password;

    public function applyFromXml(SimpleXMLElement $simpleXMLElement){
        if (isset($simpleXMLElement->host)) {
            $this->host = (string) $simpleXMLElement->host;
        }
        if (!empty($simpleXMLElement->name)) {
            $this->name = (string) $simpleXMLElement->name;
        }
        if (!empty($simpleXMLElement->username)) {
            $this->username = (string) $simpleXMLElement->username;
        }
        if (isset($simpleXMLElement->password)) {
            $this->password = (string) $simpleXMLElement->password;
        }
    }
}