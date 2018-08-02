<?php

namespace Education\StandardFooter\Extension;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use Page;
use Education\StandardFooter\Model\EducationFooterLink;
use Education\StandardFooter\Model\EducationSocialMediaLink;
use SilverStripe\AssetAdmin\Forms\UploadField;

class EducationSiteConfig extends DataExtension
{
    private static $db = [
        'UpperFooterLinkTitle' => 'Varchar(255)'
    ];

    private static $has_one = [
        'FooterLogoLink' => Page::class
    ];

    private static $has_many = [
        'UpperFooterLinks' => 'Education\StandardFooter\Model\EducationFooterLink.Upper',
        'LowerFooterLinks' => 'Education\StandardFooter\Model\EducationFooterLink.Lower',
        'SocialMediaLinks' => EducationSocialMediaLink::class
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab('Root.Footer', [
            TextField::create('UpperFooterLinkTitle', 'Header for links in upper footer'),
            TreeDropdownField::create('FooterLogoLinkID', 'Education logo link', SiteTree::class),
            GridField::create('UpperFooterLinks', 'Upper', $this->owner->UpperFooterLinks(), GridFieldConfig_RecordEditor::create()
                ->addComponent(new GridFieldOrderableRows('SortOrder'))),
            LiteralField::create('Br', '<hr style="margin-bottom: 20px" />'), // needed to stop grid fields running into each other
            GridField::create('LowerFooterLinks', 'Lower', $this->owner->LowerFooterLinks(), GridFieldConfig_RecordEditor::create()
                ->addComponent(new GridFieldOrderableRows('SortOrder')))
        ]);

        $config = GridFieldConfig_RecordEditor::create()
            ->addComponent(new GridFieldOrderableRows('SortOrder'));

        $fields->addFieldsToTab('Root.SocialMedia', [
            GridField::create(
                'SocialMediaLinks',
                '',
                $this->SocialMediaLinks(),
                $config
            ),
            CheckboxField::create('HasShield')
        ]);
    }

    public function SocialMediaLinks()
    {
        return $this->owner->getComponents('SocialMediaLinks')
            ->sort('SortOrder');
    }

    public function SocialMediaLinksFooter()
    {
        return $this->owner->SocialMediaLinks()
            ->filter('ShowInFooter', true)
            ->sort('SortOrder');
    }
}
