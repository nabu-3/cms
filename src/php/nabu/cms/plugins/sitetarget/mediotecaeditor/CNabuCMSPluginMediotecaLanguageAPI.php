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

namespace nabu\cms\plugins\sitetarget\mediotecaeditor;
use nabu\cms\plugins\sitetarget\base\CNabuCMSPluginAbstractAPI;
use nabu\data\medioteca\CNabuMedioteca;
use nabu\data\medioteca\CNabuMediotecaLanguage;
use nabu\data\site\CNabuSiteTargetCTAList;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\mediotecaeditor
 */
class CNabuCMSPluginMediotecaLanguageAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuMedioteca $nb_medioteca Medioteca selected */
    private $nb_medioteca = null;
    /** @var CNabuMediotecaLanguage $nb_medioteca_language Translation selected */
    private $nb_medioteca_language = null;

    public function prepareTarget()
    {
        $this->nb_medioteca = null;
        $this->nb_medioteca_language = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 3) {
            $this->nb_medioteca = $this->nb_work_customer->getMedioteca($fragments[1]);
            if ($this->nb_medioteca instanceof CNabuMedioteca) {
                $this->nb_medioteca->refresh();
                if (is_numeric($fragments[2])) {
                    $this->nb_medioteca_language = $this->nb_medioteca->getTranslations()->getItem($fragments[2]);
                    $this->nb_medioteca_language->refresh();
                } elseif (!$fragments[2]) {
                    $this->nb_medioteca_language = new CNabuMediotecaLanguage();
                    $this->nb_medioteca_language->setTranslatedObject($this->nb_medioteca);
                }
            }
        }

        return true;
    }

    public function methodGET()
    {
        if ($this->nb_medioteca_language instanceof CNabuMediotecaLanguage) {
            $this->setStatusOK();
            $this->setData($this->nb_medioteca_language->getTreeData(null, true));
        }

        return true;
    }

    public function methodPOST()
    {
        if ($this->nb_medioteca_language instanceof CNabuMediotecaLanguage &&
            $this->nb_medioteca_language->isNew() &&
            $this->nb_request->hasPOSTField('language_id') &&
            is_numeric($nb_language_id = $this->nb_request->getPOSTField('language_id'))
        ) {
            $this->nb_medioteca_language->setLanguageId($nb_language_id);
            $this->nb_medioteca_language->setMedioteca($this->nb_medioteca);
        }

        if ($this->nb_medioteca_language instanceof CNabuMediotecaLanguage) {
            $this->nb_request->updateObjectFromPost(
                $this->nb_medioteca_language,
                array(
                    'status' => 'nb_medioteca_lang_status',
                    'title' => 'nb_medioteca_lang_title',
                    'subtitle' => 'nb_medioteca_lang_subtitle',
                    'content' => 'nb_medioteca_lang_content'
                )
            );
            if ($this->nb_medioteca_language->save()) {
                $this->setStatusOK();
                $this->setData($this->nb_medioteca_language->getTreeData(null, true));
            }
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        if ($this->getStatus() === 'OK') {
            $api_mask = $this->nb_site_target->getFullyQualifiedURL($this->nb_language, true);
            $api_url = sprintf($api_mask, $this->nb_medioteca->getId(), $this->nb_medioteca_language->getLanguageId());
            $this->setAPICall($api_url);
            $editor_cta = $this->nb_site_target->getCTAs()->getItem('ajax_languages', CNabuSiteTargetCTAList::INDEX_KEY);
            $editor_cta->canonize();
            $urls = array();
            $editor_cta->getTranslations()->iterate(function ($key, $translation) use(&$urls) {
                $editor_mask = $translation->getFinalURL();
                $urls[$key] = sprintf($editor_mask, $this->nb_medioteca->getId(), $this->nb_medioteca_language->getLanguageId());
            });
            $this->setEditorCall($urls);
        }

        return parent::beforeDisplayTarget();
    }
}
