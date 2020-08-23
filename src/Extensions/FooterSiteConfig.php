<?php

namespace Education\StandardFooter\Extensions;

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
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Core\Config\Config;
use Page;
use Education\StandardFooter\Model\EducationFooterLink;
use Education\StandardFooter\Model\EducationSocialMediaLink;

class FooterSiteConfig extends DataExtension
{
    private static $db = [
        'UpperFooterLinkTitle' => 'Varchar(255)'
    ];

    private static $has_one = [
        'FooterLogoLink' => Page::class
    ];

    private static $has_many = [
        'UpperFooterLinks' => 'Education\StandardFooter\Model\EducationFooterLink.Upper',
        'UpperLowerFooterLinks' => 'Education\StandardFooter\Model\EducationFooterLink.UpperLower',
        'LowerFooterLinks' => 'Education\StandardFooter\Model\EducationFooterLink.Lower',
        'SocialMediaLinks' => EducationSocialMediaLink::class
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $config = GridFieldConfig_RecordEditor::create()
            ->addComponent(new GridFieldOrderableRows('SortOrder'));

        $delete = $config->getComponentByType(GridFieldDeleteAction::class);

        if ($delete) {
            $delete->setRemoveRelation(false);
        }

        if ($this->owner->getTopLinksEnabled()) {
            $fields->addFieldsToTab('Root.Footer', [
                TextField::create('UpperFooterLinkTitle', 'Header for links in upper footer'),
                GridField::create('UpperFooterLinks', 'Upper', $this->owner->UpperFooterLinks(), $config)
            ]);
        }

        $upperconfig = GridFieldConfig_RecordEditor::create()
            ->addComponent(new GridFieldOrderableRows('SortOrder'));

        $config = GridFieldConfig_RecordEditor::create()
            ->addComponent(new GridFieldOrderableRows('SortOrder'));

        $fields->addFieldsToTab('Root.Footer', [
            TreeDropdownField::create('FooterLogoLinkID', 'Logo link', SiteTree::class),
            LiteralField::create('Br', '<hr style="margin-bottom: 20px" />'), // needed to stop grid fields running into each other
            GridField::create('UpperLowerFooterLinks', 'UpperLower', $this->owner->UpperLowerFooterLinks(), $upperconfig),
            GridField::create('LowerFooterLinks', 'Lower', $this->owner->LowerFooterLinks(), $config)
        ]);

        $config = GridFieldConfig_RecordEditor::create()
            ->addComponent(new GridFieldOrderableRows('SortOrder'));

        $fields->addFieldsToTab('Root.SocialMedia', [
            GridField::create(
                'SocialMediaLinks',
                '',
                $this->owner->SocialMediaLinks(),
                $config
            ),
            CheckboxField::create('HasShield')
        ]);
    }

    /**
     * @return boolean
     */
    public function getTopLinksEnabled()
    {
        return Config::inst()->get(EducationFooterLink::class, 'top_links_enabled');
    }

    /**
     * @return SilverStripe\ORM\HasManyList
     */
    public function SocialMediaLinks()
    {
        return $this->owner->getComponents('SocialMediaLinks')
            ->sort('SortOrder');
    }

    /**
     * @return SilverStripe\ORM\HasManyList
     */
    public function SocialMediaLinksFooter()
    {
        return $this->owner->SocialMediaLinks()
            ->filter('ShowInFooter', true)
            ->sort('SortOrder');
    }
}
