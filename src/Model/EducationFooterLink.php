<?php

namespace Education\StandardFooter\Model;

use SilverStripe\SiteConfig\SiteConfig;

class EducationFooterLink extends AbstractLinkObject
{
    /**
     * @config
     *
     * @var $top_links_enabled boolean
     */
    private static $top_links_enabled = true;

    private static $singular_name = 'Footer Link';

    private static $plural_name = 'Footer Links';

    private static $table_name = 'EducationFooterLink';

    private static $has_one = [
        'Upper' => SiteConfig::class,
        'Lower' => SiteConfig::class
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('UpperID');
        $fields->removeByName('LowerID');

        return $fields;
    }
}
