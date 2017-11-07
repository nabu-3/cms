<?php

/*  Copyright 2009-2011 Rafael Gutierrez Martinez
 *  Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 *  Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 *  Copyright 2017 nabu-3 Group
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

namespace nabu\cms\plugins\sitetarget\catalogeditor;
use nabu\cms\plugins\sitetarget\base\CNabuCMSPluginAbstractAPI;
use nabu\data\catalog\CNabuCatalog;
use nabu\data\catalog\CNabuCatalogTaxonomy;
use nabu\data\catalog\CNabuCatalogTaxonomyLanguage;
use nabu\data\site\CNabuSiteTargetCTAList;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\catalogeditor
 */
class CNabuCMSPluginCatalogTaxonomyAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuCatalog $nb_catalog Catalog selected */
    private $nb_catalog = null;
    /** @var CNabuCatalogTaxonomy $nb_catalog_taxonomy Taxonomy selected */
    private $nb_catalog_taxonomy = null;

    public function prepareTarget()
    {
        $this->nb_catalog = null;
        $this->nb_catalog_taxonomy = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 3) {
            $this->nb_catalog = $this->nb_work_customer->getCatalog($fragments[1]);
            if ($this->nb_catalog instanceof CNabuCatalog) {
                $this->nb_catalog->refresh(true, true);
                if (is_numeric($fragments[2])) {
                    $this->nb_catalog_taxonomy = $this->nb_catalog->getTaxonomies()->getItem($fragments[2]);
                    $this->nb_catalog_taxonomy->refresh(true, true);
                } elseif (!$fragments[2]) {
                    $this->nb_catalog_taxonomy = new CNabuCatalogTaxonomy();
                    $this->nb_catalog_taxonomy->setCatalog($this->nb_catalog);
                    $this->nb_catalog_taxonomy->grantHash();
                }
            }
        }

        return true;
    }

    /**
     * Process all GET calls.
     * @return bool Returns true if all is done.
     */
    public function methodGET()
    {
        if ($this->nb_catalog_taxonomy instanceof CNabuCatalogTaxonomy) {
            $this->setStatusOK();
            $this->setData($this->nb_catalog_taxonomy->getTreeData(null, true));
        }

        return true;
    }

    /**
     * Process all POST calls.
     * @return bool Returns true if all is done.
     */
    public function methodPOST()
    {
        if ($this->nb_request->hasGETField('action')) {
            $action = $this->nb_request->getGETField('action');
            switch (strtolower($action)) {
                default:
                    $this->setStatusError(sprintf('Action [%s] not supported', $action));
            }
        } else {
            $this->doPOSTSave();
        }

        return true;
    }

    /**
     * Save data in the database storage.
     * @return bool Returns true if all is done.
     */
    public function doPOSTSave()
    {
        if ($this->nb_catalog_taxonomy instanceof CNabuCatalogTaxonomy) {
            $this->nb_request->updateObjectFromPost(
                $this->nb_catalog_taxonomy,
                array(
                    'key' => 'nb_catalog_taxonomy_key',
                    'hash' => 'nb_catalog_taxonomy_hash',
                    'level' => 'nb_catalog_taxonomy_level',
                    'scope' => 'nb_catalog_taxonomy_scope'
                )
            );
            $this->nb_catalog_taxonomy->grantHash();
            if ($this->nb_request->hasPOSTField('attrs')) {
                $this->nb_catalog_taxonomy->setAttributes($this->nb_request->getPOSTField('attrs'));
            }
            if ($this->nb_catalog_taxonomy->save()) {
                $languages = $this->nb_request->getCombinedPostIndexes(array('title', 'internal_notes'));
                if ($this->nb_request->hasPOSTField('lang_attrs')) {
                    $lang_attrs = $this->nb_request->getPOSTField('lang_attrs');
                } else {
                    $lang_attrs = array();
                }

                if (count($languages) > 0) {
                    foreach ($languages as $lang_id) {
                        $nb_translation = $this->nb_catalog_taxonomy->getTranslation($lang_id);
                        if (!$nb_translation) {
                            $nb_translation = new CNabuCatalogTaxonomyLanguage();
                            $nb_translation->setCatalogTaxonomyId($this->nb_catalog_taxonomy->getId());
                            $nb_translation->setLanguageId($lang_id);
                            $this->nb_catalog_taxonomy->setTranslation($nb_translation);
                        }
                        $this->nb_request->updateObjectFromPost(
                            $nb_translation,
                            array(
                                'slug' => 'nb_catalog_taxonomy_lang_slug',
                                'image' => 'nb_catalog_taxonomy_lang_image',
                                'title' => 'nb_catalog_taxonomy_lang_title',
                                'subtitle' => 'nb_catalog_taxonomy_lang_subtitle',
                                'anchor_text' => 'nb_catalog_taxonomy_lang_anchor_text',
                                'opening' => 'nb_catalog_taxonomy_lang_opening',
                                'content' => 'nb_catalog_taxonomy_lang_content',
                                'footer' => 'nb_catalog_taxonomy_lang_footer',
                                'aside' => 'nb_catalog_taxonomy_lang_aside',
                                'internal_notes' => 'nb_catalog_taxonomy_lang_internal_notes'
                            ),
                            null,
                            null,
                            $lang_id
                        );
                        if (array_key_exists($lang_id, $lang_attrs)) {
                            $nb_translation->setAttributes($lang_attrs[$lang_id]);
                        }
                        $nb_translation->save(true);
                    }
                }

                $this->setStatusOK();
                $this->setData($this->nb_catalog_taxonomy->getTreeData(null, true));
            }
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        if ($this->getStatus() === 'OK') {
            $api_mask = $this->nb_site_target->getFullyQualifiedURL($this->nb_language, true);
            $api_url = sprintf($api_mask, $this->nb_catalog->getId(), $this->nb_catalog_taxonomy->getId());
            $this->setAPICall($api_url);
            $editor_cta = $this->nb_site_target->getCTAs()->getItem('ajax_taxonomies', CNabuSiteTargetCTAList::INDEX_KEY);
            $editor_cta->canonize();
            $urls = array();
            $editor_cta->getTranslations()->iterate(function ($key, $translation) use(&$urls) {
                $editor_mask = $translation->getFinalURL();
                $urls[$key] = sprintf($editor_mask, $this->nb_catalog->getId(), $this->nb_catalog_taxonomy->getId());
            });
            $this->setEditorCall($urls);
        }

        return parent::beforeDisplayTarget();
    }
}
