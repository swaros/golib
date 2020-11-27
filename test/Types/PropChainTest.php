<?php

namespace Types;

use golib\Types\PropChain;
use PHPUnit\Framework\TestCase;

class PropChainTest extends TestCase
{

    public function testGetChild()
    {
        $node1 = new TestPropChainNode();
        $node2 = new TestPropChainNodeLast();

        $node1->applyProp($node2);

        $propChainTest = new TestPropChain();
        $propChainTest->applyProp($node1);

        $child = $propChainTest->getChild();
        $this->assertInstanceOf(TestPropChainNode::class, $child);

    }

    public function testApplyProp()
    {
        $values = [
            "first" => 1,
            "second" => "dream"
        ];
        $propChainTest = new TestPropChain();
        $this->assertEquals(5, $propChainTest->first);
        $propChainTest = new TestPropChain($values);
        $this->assertEquals(1, $propChainTest->first);
        $this->assertInstanceOf(TestPropChain::class, $propChainTest);

        $propChainTest->applyProp(new TestPropChainNode());
        $this->assertTrue($propChainTest->hasChild());

        $child = $propChainTest->getChild();
        $this->assertInstanceOf(TestPropChainNode::class, $child);

        $this->assertEquals(1, $propChainTest->first);

    }
}

############## testClass ###########

class TestPropChain extends PropChain
{
    public int $first = 5;
}

class TestPropChainNode extends PropChain
{

}

class TestPropChainNodeLast extends PropChain
{

}