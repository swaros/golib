<?php

namespace Content\Dom;

use golib\Content\Dom\XmlSimple;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;

class XmlSimpleTest extends TestCase
{
    private string $source = <<<EOT
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
  <config truetest="true" falsetest="false"  stringcheck="hello" nullcheck="" intcheck="288"/>
</data>
EOT;

    /**
     * big chunk of tests for
     * the default xml functions.
     *
     */
    public function testXmlParsing()
    {

        $xml = new TestDatabaseConfig1($this->source);
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
        $this->assertEquals('', $contentNull);

        $keyArr = $xml->getNodeKeyedArray('//data', 'url', 'id');

        $this->assertArrayHasKey('github.com', $keyArr);
        $this->assertArrayHasKey('google.com', $keyArr);
        $this->assertEquals(1337, $keyArr['github.com']);
        $this->assertEquals(1007, $keyArr['google.com']);

        // same without flatting
        $keyArr = $xml->getNodeKeyedArray('//data', 'url', 'id', false);


        // dangerous test. this test will fail if the
        // order of the nodes is changed. this test rely on
        // the exact expected order of the nodes.
        $this->assertArrayHasKey('github.com', $keyArr[0]);
        $this->assertArrayHasKey('google.com', $keyArr[1]);
        $this->assertEquals(1337, $keyArr[0]['github.com']);
        $this->assertEquals(1007, $keyArr[1]['google.com']);

        $nodeArr = $xml->getNodeAttrAsArray('//data/*');
        $this->assertArrayHasKey('id', $nodeArr);
        $this->assertEquals('1337', $nodeArr['id']);

        $nodeArr = $xml->getNodeAttrAsArray('//lala/*');
        $this->assertEmpty($nodeArr);

        $nodeAttr = $xml->getNodeAttribute('//*[@id=1007]/subentrie', 'parent');
        $this->assertEquals("1337", $nodeAttr);

        $this->assertNull($xml->getNodeAttribute('//*[@id=888]/subentrie', 'parent'));

    }

    public function testFailXml()
    {
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

    public function testGetNodeAttrBySmplXml() {
        $xml = new TestDatabaseConfig1($this->source);
        $node = $xml->xPath("//*[@id=1007]");
        $prop = $xml->getNodeAttrBySmplXml(current($node),"id");
        $this->assertEquals("1007", $prop);
    }

    public function testNodeFuncNodeAttrArrayBySmplXml()
    {
        $xml = new TestDatabaseConfig1($this->source);
        $node = $xml->xPath("//*[@id=1007]");
        $props = $xml->getNodeAttrArrayBySmplXml(current($node));
        $this->assertNotEmpty($props);
        $this->assertArrayHasKey('id', $props);
        $this->assertEquals("1007", $props["id"]);
        $this->assertArrayHasKey('url', $props);
        $this->assertEquals("google.com", $props["url"]);
    }

    public function testNodeFuncNodeAttrArrayBySmplXmlEmpty()
    {
        $xml = new TestDatabaseConfig1($this->source);
        $node = $xml->xPath('//*[@id=1007]/database');
        $props = $xml->getNodeAttrArrayBySmplXml(current($node));
        $this->assertEmpty($props);

    }


    public function testNodeFuncNodeChildBySmplXml()
    {
        $xml = new TestDatabaseConfig1($this->source);
        $node = $xml->xPath("//data/entrypoint");
        $xmlNode = $xml->getNodeChildBySmplXml(current($node), 'subentrie');
        $this->assertInstanceOf(SimpleXMLElement::class, $xmlNode);
        $props = $xml->getNodeAttrArrayBySmplXml($xmlNode);
        $this->assertNotEmpty($props);
        $this->assertArrayHasKey('parent', $props);
        $this->assertEquals("1337", $props["parent"]);
        $this->assertArrayHasKey('region', $props);
        $this->assertEquals("EU", $props["region"]);
    }

    public function testNodeFuncNodeChildBySmplXmlOnNull()
    {
        $xml = new TestDatabaseConfig1($this->source);
        $node = $xml->xPath("//data/entrypoint");
        $xmlNode = $xml->getNodeChildBySmplXml(current($node), 'something');
        $this->assertNull($xmlNode);
    }

    public function testGetNodeAttributeBool() {
        $xml = new TestDatabaseConfig1($this->source);
        $trueTest = $xml->getNodeAttributeBool("//data/config",'truetest');
        $this->assertTrue($trueTest);
        $this->assertFalse( $xml->getNodeAttributeBool("//data/config",'falsetest', true));
        $this->assertFalse( $xml->getNodeAttributeBool("//data/config",'nullcheck', true));
        $this->assertTrue( $xml->getNodeAttributeBool("//data/config",'stringcheck', false));
        // invalid path
        $this->assertFalse( $xml->getNodeAttributeBool("//data/master",'stringcheck', false));
    }
    public function testGetNodeAttributeInt()
    {
        $xml = new TestDatabaseConfig1($this->source);
        // node not exists
        $this->assertEquals(777, $xml->getNodeAttributeInt("//data/master",'intcheck', 777));
        $this->assertEquals(288, $xml->getNodeAttributeInt("//data/config",'intcheck', 777));
        $this->assertEquals(999, $xml->getNodeAttributeInt("//data/config",'stringcheck', 999));
    }
}


################ test class ##################

class TestDatabaseConfig1 extends XmlSimple
{

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

class TestDatabaseProperty
{
    public string $host;
    public string $name;
    public string $username;
    public string $password;

    public function applyFromXml(SimpleXMLElement $simpleXMLElement)
    {
        if (isset($simpleXMLElement->host)) {
            $this->host = (string)$simpleXMLElement->host;
        }
        if (!empty($simpleXMLElement->name)) {
            $this->name = (string)$simpleXMLElement->name;
        }
        if (!empty($simpleXMLElement->username)) {
            $this->username = (string)$simpleXMLElement->username;
        }
        if (isset($simpleXMLElement->password)) {
            $this->password = (string)$simpleXMLElement->password;
        }
    }
}