<?php

namespace Education\StandardFooter\Tests;

use SilverStripe\Dev\SapphireTest;
use Education\StandardFooter\Model\AbstractLinkObject;
use SilverStripe\Forms\FieldList;

class AbstractLinkObjectTest extends SapphireTest
{
    protected static $fixture_file = 'fixtures.yml';

    public function testGetCMSFields()
    {
        $link = $this->objFromFixture(AbstractLinkObject::class, 'internal');

        $this->assertInstanceOf(FieldList::class, $link->getCMSFields());
    }

    public function testGetLink()
    {
        $internal = $this->objFromFixture(AbstractLinkObject::class, 'internal');
        $this->assertEquals('/test-page/', $internal->Link());

        $external = $this->objFromFixture(AbstractLinkObject::class, 'external');
        $this->assertEquals('http://google.com', $external->Link());
    }
}
