<?php

namespace Education\StandardFooter\Tests;

use SilverStripe\Dev\FunctionalTest;
use SilverStripe\Forms\FieldList;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Forms\GridField\GridField;

class FooterSiteConfigTest extends FunctionalTest
{
    protected static $fixture_file = 'fixtures.yml';

    public function testCMSFields()
    {
        $config = SiteConfig::current_site_config();

        $this->assertInstanceOf(FieldList::class, $config->getCMSFields());
        $this->assertInstanceOf(GridField::class, $config->getCMSFields()->dataFieldByName('LowerFooterLinks'));
    }
}
