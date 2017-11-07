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

namespace nabu\cms\plugins\sitetarget\messagingeditor;
use nabu\cms\plugins\sitetarget\base\CNabuCMSPluginAbstractAPI;
use nabu\data\messaging\CNabuMessaging;
use nabu\data\messaging\CNabuMessagingLanguage;
use nabu\data\site\CNabuSiteTargetCTAList;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.1 Surface
 * @version 3.0.1 Surface
 * @package \nabu\cms\plugins\sitetarget\messagingeditor
 */
class CNabuCMSPluginMessagingLanguageAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuMessaging $nb_messaging Messaging selected */
    private $nb_messaging = null;
    /** @var CNabuMessagingLanguage $nb_messaging_language Translation selected */
    private $nb_messaging_language = null;

    public function prepareTarget()
    {
        $this->nb_messaging = null;
        $this->nb_messaging_language = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 3) {
            $this->nb_messaging = $this->nb_work_customer->getMessaging($fragments[1]);
            if ($this->nb_messaging instanceof CNabuMessaging) {
                $this->nb_messaging->refresh();
                if (is_numeric($fragments[2])) {
                    $this->nb_messaging_language = $this->nb_messaging->getTranslations()->getItem($fragments[2]);
                    $this->nb_messaging_language->refresh();
                } elseif (!$fragments[2]) {
                    $this->nb_messaging_language = new CNabuMessagingLanguage();
                    $this->nb_messaging_language->setTranslatedObject($this->nb_messaging);
                }
            }
        }

        return true;
    }

    public function methodGET()
    {
        if ($this->nb_messaging_language instanceof CNabuMessagingLanguage) {
            $this->setStatusOK();
            $this->setData($this->nb_messaging_language->getTreeData(null, true));
        }

        return true;
    }

    public function methodPOST()
    {
        if ($this->nb_messaging_language instanceof CNabuMessagingLanguage) {
            $this->nb_request->updateObjectFromPost(
                $this->nb_messaging_language,
                array(
                    'key' => 'nb_messaging_lang_key',
                    'hash' => 'nb_messaging_lang_hash'
                )
            );
            if ($this->nb_messaging_language->save()) {
                $this->setStatusOK();
                $this->setData($this->nb_messaging_language->getTreeData(null, true));
            }
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        if ($this->getStatus() === 'OK') {
            $api_mask = $this->nb_site_target->getFullyQualifiedURL($this->nb_language, true);
            $api_url = sprintf($api_mask, $this->nb_messaging->getId(), $this->nb_messaging_language->getId());
            $this->setAPICall($api_url);
            $editor_cta = $this->nb_site_target->getCTAs()->getItem('ajax_languages', CNabuSiteTargetCTAList::INDEX_KEY);
            $editor_cta->canonize();
            $urls = array();
            $editor_cta->getTranslations()->iterate(function ($key, $translation) use(&$urls) {
                $editor_mask = $translation->getFinalURL();
                $urls[$key] = sprintf($editor_mask, $this->nb_messaging->getId(), $this->nb_messaging_language->getId());
            });
            $this->setEditorCall($urls);
        }

        return parent::beforeDisplayTarget();
    }
}
