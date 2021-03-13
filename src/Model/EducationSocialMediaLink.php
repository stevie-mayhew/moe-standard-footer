<?php

namespace Education\StandardFooter\Model;

use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\DropdownField;

class EducationSocialMediaLink extends AbstractLinkObject
{
    private static $singular_name = 'Social Link';

    private static $plural_name = 'Social Links';

    private static $db = [
        'Type' => 'Varchar(50)',
        'ShowInBanner' => 'Boolean(0)',
        'ShowInFooter' => 'Boolean(0)',
    ];

    private static $table_name = 'EducationSocialMediaLink';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab('Root.Main', [
            HeaderField::create('Settings', "Extra settings", 5),

            OptionsetField::create('ShowInBanner', 'Show in banner')
                ->setSource([true => 'Yes', false => 'No']),

            OptionsetField::create('ShowInFooter', 'Show in footer')
                ->setSource([true => 'Yes', false => 'No']),

            DropdownField::create('Type', 'Icon type')
                ->setSource([
                    'fb' => 'Facebook',
                    'lkin' => 'LinkedIn',
                    'rss' => 'RSS',
                    'twtr' => 'Twitter',
                    'vmo' => 'Vimeo',
                    'ytb' => 'YouTube'
                ]),
        ]);

        return $fields;
    }
}
