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
use nabu\data\catalog\CNabuCatalogLanguage;
use nabu\data\site\CNabuSiteTargetCTAList;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\catalogeditor
 */
class CNabuCMSPluginCatalogLanguageAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuCatalog $nb_catalog Catalog selected */
    private $nb_catalog = null;
    /** @var CNabuCatalogLanguage $nb_catalog_language Translation selected */
    private $nb_catalog_language = null;

    public function prepareTarget()
    {
        $this->nb_catalog = null;
        $this->nb_catalog_language = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 3) {
            $this->nb_catalog = $this->nb_work_customer->getCatalog($fragments[1]);
            if ($this->nb_catalog instanceof CNabuCatalog) {
                $this->nb_catalog->refresh(true, true);
                if (is_numeric($fragments[2])) {
                    $this->nb_catalog_language = $this->nb_catalog->getTranslations()->getItem($fragments[2]);
                    $this->nb_catalog_language->refresh();
                } elseif (!$fragments[2]) {
                    $this->nb_catalog_language = new CNabuCatalogLanguage();
                    $this->nb_catalog_language->setCatalog($this->nb_catalog);
                    $this->nb_catalog_language->setTranslatedObject($this->nb_catalog);
                }
            }
        }

        return true;
    }

    public function methodGET()
    {
        if ($this->nb_catalog_language instanceof CNabuCatalogLanguage) {
            $this->setStatusOK();
            $this->setData($this->nb_catalog_language->getTreeData(null, true));
        }

        return true;
    }

    public function methodPOST()
    {
        if ($this->nb_catalog_language instanceof CNabuCatalogLanguage) {
            $this->nb_request->updateObjectFromPost(
                $this->nb_catalog_language,
                array(
                    'language_id' => 'nb_language_id',
                    'status' => 'nb_catalog_lang_status',
                    'slug' => 'nb_catalog_lang_slug',
                    'image' => 'nb_catalog_lang_image',
                    'title' => 'nb_catalog_lang_title',
                    'subtitle' => 'nb_catalog_lang_subtitle',
                    'anchor_text' => 'nb_catalog_lang_anchor_text',
                    'opening' => 'nb_catalog_lang_opening',
                    'content' => 'nb_catalog_lang_content',
                    'footer' => 'nb_catalog_lang_footer',
                    'aside' => 'nb_catalog_lang_aside'
                )
            );
            if ($this->nb_request->hasPOSTField('attrs')) {
                $this->nb_catalog_language->setAttributes($this->nb_request->getPOSTField('attrs'));
            }
            if ($this->nb_catalog_language->save()) {
                $this->setStatusOK();
                $this->setData($this->nb_catalog_language->getTreeData(null, true));
            }
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        if ($this->getStatus() === 'OK') {
            $api_mask = $this->nb_site_target->getFullyQualifiedURL($this->nb_language, true);
            $api_url = sprintf($api_mask, $this->nb_catalog->getId(), $this->nb_catalog_language->getLanguageId());
            $this->setAPICall($api_url);
            $editor_cta = $this->nb_site_target->getCTAs()->getItem('ajax_languages', CNabuSiteTargetCTAList::INDEX_KEY);
            $editor_cta->canonize();
            $urls = array();
            $editor_cta->getTranslations()->iterate(function ($key, $translation) use(&$urls) {
                $editor_mask = $translation->getFinalURL();
                $urls[$key] = sprintf($editor_mask, $this->nb_catalog->getId(), $this->nb_catalog_language->getLanguageId());
            });
            $this->setEditorCall($urls);
        }

        return parent::beforeDisplayTarget();
    }
}
