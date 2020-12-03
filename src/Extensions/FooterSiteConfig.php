<?php

namespace Education\StandardFooter\Extensions;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Core\Config\Config;
use Page;
use Education\StandardFooter\Model\EducationSocialMediaLink;
use SilverStripe\Forms\Tab;

class FooterSiteConfig extends DataExtension
{
    private static $banner_enabled = true;

    private static $top_links_enabled = false;

    private static $social_media_enabled = true;

    private static $db = [
        'UpperFooterLinkTitle' => 'Varchar(255)',
        'FooterBannerText' => 'Text',
        'FooterBannerButtonText' => 'Varchar'
    ];

    private static $has_one = [
        'FooterLogoLink' => Page::class,
        'FooterBannerButton' => SiteTree::class
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

        $fields->addFieldsToTab('Root.Footer', [
            TreeDropdownField::create('FooterLogoLinkID', 'Logo link', SiteTree::class),
            GridField::create('LowerFooterLinks', 'Links', $this->owner->LowerFooterLinks(), $config)
        ]);


        if ($this->owner->getFooterBannerEnabled()) {
            $fields->addFieldToTab('Root.Footer', new Tab('Banner', 'Banner',
                TextField::create('FooterBannerText'),
                TextField::create('FooterBannerButtonText'),
                TreeDropdownField::create('FooterBannerButtonID', 'Link', SiteTree::class)
            ));
        }

        if ($this->owner->getFooterTopLinksEnabled()) {
            $upperconfig = GridFieldConfig_RecordEditor::create()
                ->addComponent(new GridFieldOrderableRows('SortOrder'));

            $fields->addFieldsToTab('Root.Footer', [
                TextField::create('UpperFooterLinkTitle', 'Header for links in upper footer'),
                GridField::create('UpperFooterLinks', 'Upper', $this->owner->UpperFooterLinks(), $config),
                GridField::create('UpperLowerFooterLinks', 'Middle links', $this->owner->UpperLowerFooterLinks(), $upperconfig),
            ]);
        }

        if ($this->owner->getFooterSocialMediaEnabled()) {
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
    }

    /**
     * @return boolean
     */
    public function getFooterTopLinksEnabled()
    {
        return Config::inst()->get(FooterSiteConfig::class, 'top_links_enabled');
    }

    /**
     * @return boolean
     */
    public function getFooterBannerEnabled()
    {
        return Config::inst()->get(FooterSiteConfig::class, 'banner_enabled');
    }

    /**
     * @return boolean
     */
    public function getFooterSocialMediaEnabled()
    {
        return Config::inst()->get(FooterSiteConfig::class, 'social_media_enabled');
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

    /**
     * Returns the link for the footer banner. Use `updateFooterBannerLink` to
     * filter or modify the link value.
     *
     * @return string
     */
    public function getFooterBannerLink()
    {
        $linkedPage = $this->owner->FooterBannerButton();

        if ($linkedPage && $linkedPage->exists()) {
            $link = $linkedPage->AbsoluteLink();

            $this->owner->extend('updateFooterBannerLink', $link);

            return $link;
        }
    }
}
