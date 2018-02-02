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

namespace nabu\cms\plugins\sitetarget\securityeditor;
use nabu\cms\plugins\sitetarget\base\CNabuCMSPluginAbstractAPI;
use nabu\data\security\CNabuRole;
use nabu\data\security\CNabuRoleLanguage;
use nabu\data\site\CNabuSiteTargetCTAList;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\securityeditor
 */
class CNabuCMSPluginRoleLanguageAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuRole $edit_role Role selected */
    private $edit_role = null;
    /** @var CNabuRoleLanguage $edit_role_language Translation selected */
    private $edit_role_language = null;

    public function prepareTarget()
    {
        $this->edit_role = null;
        $this->edit_role_language = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 3) {
            $this->edit_role = $this->nb_work_customer->getRole($fragments[1]);
            if ($this->edit_role instanceof CNabuRole) {
                $this->edit_role->refresh(true, true);
                if (is_numeric($fragments[2])) {
                    $this->edit_role_language = $this->edit_role->getTranslations()->getItem($fragments[2]);
                    $this->edit_role_language->refresh(true, true);
                } elseif (!$fragments[2]) {
                    $this->edit_role_language = new CNabuRoleLanguage();
                    $this->edit_role_language->setTranslatedObject($this->edit_role);
                }
            }
        }

        return true;
    }

    public function methodGET()
    {
        if ($this->edit_role_language instanceof CNabuRoleLanguage) {
            $this->setStatusOK();
            $this->setData($this->edit_role_language->getTreeData(null, true));
        }

        return true;
    }

    public function methodPOST()
    {
        if ($this->edit_role_language instanceof CNabuRoleLanguage &&
            $this->edit_role_language->isNew() &&
            $this->nb_request->hasPOSTField('language_id') &&
            is_numeric($nb_language_id = $this->nb_request->getPOSTField('language_id'))
        ) {
            $this->edit_role_language->setLanguageId($nb_language_id);
            $this->edit_role_language->setRoleId($this->edit_role->getId());
        }

        if ($this->edit_role_language instanceof CNabuRoleLanguage) {
            $this->nb_request->updateObjectFromPost(
                $this->edit_role_language,
                array(
                    'name' => 'nb_role_lang_name'
                )
            );
            if ($this->edit_role_language->save()) {
                $this->setStatusOK();
                $this->setData($this->edit_role_language->getTreeData(null, true));
            }
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        if ($this->getStatus() === 'OK') {
            $api_mask = $this->nb_site_target->getFullyQualifiedURL($this->nb_language, true);
            $api_url = sprintf($api_mask, $this->edit_role->getId(), $this->edit_role_language->getLanguageId());
            $this->setAPICall($api_url);
            $editor_cta = $this->nb_site_target->getCTAs()->getItem('ajax_languages', CNabuSiteTargetCTAList::INDEX_KEY);
            $editor_cta->canonize();
            $urls = array();
            $editor_cta->getTranslations()->iterate(function ($key, $translation) use(&$urls) {
                $editor_mask = $translation->getFinalURL();
                $urls[$key] = sprintf($editor_mask, $this->edit_role->getId(), $this->edit_role_language->getLanguageId());
                return true;
            });
            $this->setEditorCall($urls);
        }

        return parent::beforeDisplayTarget();
    }
}
