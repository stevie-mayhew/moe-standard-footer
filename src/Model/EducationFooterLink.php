<?php

namespace Education\StandardFooter\Model;

use SilverStripe\SiteConfig\SiteConfig;

class EducationFooterLink extends AbstractLinkObject
{
    private static $singular_name = 'Footer Link';

    private static $plural_name = 'Footer Links';

    private static $table_name = 'EducationFooterLink';

    private static $has_one = [
        'Upper' => SiteConfig::class,
        'Lower' => SiteConfig::class,
        'UpperLower' => SiteConfig::class
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('UpperID');
        $fields->removeByName('LowerID');
        $fields->removeByName('UpperLowerID');

        return $fields;
    }
}
