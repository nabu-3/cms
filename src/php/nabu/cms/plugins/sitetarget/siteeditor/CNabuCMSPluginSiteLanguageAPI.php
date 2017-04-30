<?php

/*  Copyright 2009-2011 Rafael Gutierrez Martinez
 *  Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 *  Copyright 2014-2016 Where Ideas Simply Come True, S.L.
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

namespace nabu\cms\plugins\sitetarget\siteeditor;
use nabu\cms\plugins\sitetarget\base\CNabuCMSPluginAbstractAPI;
use nabu\data\site\CNabuSite;
use nabu\data\site\CNabuSiteLanguage;
use nabu\data\site\CNabuSiteTargetCTAList;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\siteeditor
 */
class CNabuCMSPluginSiteLanguageAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuSite $nb_edit_site Site selected */
    private $nb_edit_site = null;
    /** @var CNabuSiteLanguage $nb_edit_site_language Translation selected */
    private $nb_edit_site_language = null;

    public function prepareTarget()
    {
        $this->nb_edit_site = null;
        $this->nb_edit_site_language = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 3) {
            $this->nb_edit_site = $this->nb_work_customer->getSite($fragments[1]);
            if ($this->nb_edit_site instanceof CNabuSite) {
                $this->nb_edit_site->refresh();
                if (is_numeric($fragments[2])) {
                    $this->nb_edit_site_language = $this->nb_edit_site->getTranslations()->getItem($fragments[2]);
                    $this->nb_edit_site_language->refresh();
                } elseif (!$fragments[2]) {
                    $this->nb_edit_site_language = new CNabuSiteLanguage();
                    $this->nb_edit_site_language->setTranslatedObject($this->nb_edit_site);
                }
            }
        }

        return true;
    }

    public function methodGET()
    {
        if ($this->nb_edit_site_language instanceof CNabuSiteLanguage) {
            $this->setStatusOK();
            $this->setData($this->nb_edit_site_language->getTreeData(null, true));
        }

        return true;
    }

    public function methodPOST()
    {
        if ($this->nb_edit_site_language instanceof CNabuSiteLanguage &&
            $this->nb_edit_site_language->isNew() &&
            $this->nb_request->hasPOSTField('language_id') &&
            is_numeric($nb_language_id = $this->nb_request->getPOSTField('language_id'))
        ) {
            $this->nb_edit_site_language->setLanguageId($nb_language_id);
            $this->nb_edit_site_language->setSite($this->nb_edit_site);
        }

        if ($this->nb_edit_site_language instanceof CNabuSiteLanguage) {
            $this->nb_request->updateObjectFromPost(
                $this->nb_edit_site_language,
                array(
                    'enabled' => 'nb_site_lang_enabled',
                    'translation_status' => 'nb_site_lang_translation_status',
                    'editable' => 'nb_site_lang_editable',
                    'order' => 'nb_site_lang_order',
                    'name' => 'nb_site_lang_name',
                    'short_datetime_format' => 'nb_site_lang_short_datetime_format',
                    'middle_datetime_format' => 'nb_site_lang_middle_datetime_format',
                    'full_datetime_format' => 'nb_site_lang_full_datetime_format',
                    'short_date_format' => 'nb_site_lang_short_date_format',
                    'middle_date_format' => 'nb_site_lang_middle_date_format',
                    'full_date_format' => 'nb_site_lang_full_date_format',
                    'short_time_format' => 'nb_site_lang_short_time_format',
                    'full_time_format' => 'nb_site_lang_full_time_format'
                )
            );
            if ($this->nb_edit_site_language->save()) {
                $this->setStatusOK();
                $this->setData($this->nb_edit_site_language->getTreeData(null, true));
            }
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        if ($this->getStatus() === 'OK') {
            $api_mask = $this->nb_site_target->getFullyQualifiedURL($this->nb_language, true);
            $api_url = sprintf($api_mask, $this->nb_edit_site->getId(), $this->nb_edit_site_language->getLanguageId());
            $this->setAPICall($api_url);
            $editor_cta = $this->nb_site_target->getCTAs()->getItem('ajax_languages', CNabuSiteTargetCTAList::INDEX_KEY);
            $editor_cta->canonize();
            $urls = array();
            $editor_cta->getTranslations()->iterate(function ($key, $translation) use(&$urls) {
                $editor_mask = $translation->getFinalURL();
                $urls[$key] = sprintf($editor_mask, $this->nb_edit_site->getId(), $this->nb_edit_site_language->getLanguageId());
            });
            $this->setEditorCall($urls);
        }

        return parent::beforeDisplayTarget();
    }
}
