<?php

namespace Education\StandardFooter\Tests;

use SilverStripe\Dev\FunctionalTest;
use SilverStripe\Forms\FieldList;
use SilverStripe\SiteConfig\SiteConfig;

class FooterSiteConfigTest extends FunctionalTest
{
    public function testCMSFields()
    {
        $config = SiteConfig::current_site_config();

        $this->assertInstanceOf(FieldList::class, $config->getCMSFields());
        $this->assertInstanceOf(GridField::class, $config->getCMSFields()->dataFieldByName('LowerFooterLinks'));
    }
}
