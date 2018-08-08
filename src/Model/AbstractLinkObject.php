<?php

namespace Education\StandardFooter\Model;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Control\Director;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\SiteConfig\SiteConfig;
use UncleCheese\DisplayLogic\Forms\Wrapper;
use Education\Cwp\Extension\SiteConfigableObjectExtension;

/**
 * All link object should extend from this class. This class provides basic
 * internal and external link fields.
 *
 */
class AbstractLinkObject extends DataObject
{
    private static $singular_name = 'Link object';

    private static $plural_name = 'Link object';

    private static $db = [
        'Title' => 'Varchar(255)',
        'LinkTitle' => 'Varchar(255)',
        'ExternalLink' => 'Varchar(255)',
        'SortOrder' => 'Int',
        'Archived' => 'Boolean'
    ];

    private static $has_one = [
        'SiteConfig' => SiteConfig::class,
        'InternalLink' => SiteTree::class
    ];

    private static $summary_fields = [
        'getTemplateTitle' => 'Link Text',
        'getLink' => 'Link',
    ];

    private static $extensions = [
        SiteConfigableObjectExtension::class
    ];

    private static $default_sort = "SortOrder";

    private static $table_name = 'AbstractLinkObject';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeFieldsFromTab('Root.Main', [
            'SiteConfigID',
            'SortOrder',
            'InternalLinkID',
            'ExternalLink',
            'LinkTitle'
        ]);

        if ($this->ExternalLink) {
            $this->LinkTo = 'External';
        } else {
            $this->LinkTo = 'Internal';
        }

        $fields->addFieldsToTab('Root.Main', [
            OptionsetField::create('LinkTo', 'Link', [
                'External' => 'External',
                'Internal' => 'Internal'
            ], $this->LinkTo),

            Wrapper::create(TreeDropdownField::create(
                'InternalLinkID',
                'Internal Page',
                SiteTree::class
            ))->displayIf("LinkTo")->isEqualTo('Internal')->end(),

            TextField::create('ExternalLink', 'External link')
                ->displayIf("LinkTo")->isEqualTo("External")->end(),

            TextField::create('LinkTitle', 'Link Text')
                ->setRightTitle('Leave blank to use Internal Links navigation title'),

        ]);

        return $fields;
    }


    public function onBeforeWrite()
    {
        if ($this->LinkTo == 'External') {
            $this->InternalLinkID = 0;
        } else if ($this->LinkTo == 'Internal') {
            $this->ExternalLink = '';
        }

        if (!$this->Title) {
            if ($this->LinkTitle && strlen($this->LinkTitle) > 0) {
                $this->Title = $this->LinkTitle;
            } else {
                $this->Title = $this->InternalLink()->MenuTitle;
            }
        } else if (!$this->LinkTitle) {
            $this->LinkTitle = $this->InternalLink()->MenuTitle;
        }

        if (!$this->SiteConfigID) {
            // ensure the link is attached to the most current page
            $this->SiteConfigID = SiteConfig::current_site_config()->ID;
        }

        parent::onBeforeWrite();
    }

    public function getTemplateTitle()
    {
        if ($this->LinkTitle && strlen($this->LinkTitle) > 0) {
            return html_entity_decode($this->LinkTitle);
        } else {
            return html_entity_decode($this->Title);
        }
    }

    public function Link()
    {
        return $this->getLink();
    }

    public function AbsoluteLink()
    {
        return $this->getTileAbsoluteLink();
    }

    public function getLink()
    {
        if ($this->ExternalLink && strlen($this->ExternalLink) > 0) {
            return !isset(parse_url($this->ExternalLink)['scheme']) ? 'http://' . $this->ExternalLink : $this->ExternalLink;
        } else {
            return $this->InternalLink()->Link();
        }
    }

    public function getTileAbsoluteLink()
    {
        if ($this->ExternalLink && strlen($this->ExternalLink) > 0) {
            return $this->getLink();
        } else if ($this->InternalLinkID) {
            return Director::absoluteURL($this->InternalLink()->Link());
        }
    }

    public function getTileLinkTarget()
    {
        if ($this->ExternalLink && strlen($this->ExternalLink) > 0) {
            return '_blank';
        } elseif ($this->InternalLinkID) {
            return null;
        }
    }
}
