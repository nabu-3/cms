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
use nabu\data\security\CNabuUser;
use nabu\data\site\CNabuSite;
use nabu\data\site\CNabuSiteUser;
use nabu\data\site\CNabuSiteTargetCTAList;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\securityeditor
 */
class CNabuCMSPluginSiteUserAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuUser $nb_edit_user User selected */
    private $nb_edit_user = null;
    /** @var CNabuSite $nb_edit_site Site selected */
    private $nb_edit_site = null;
    /** @var CNabuSiteUser $nb_edit_site_user Service selected */
    private $nb_edit_site_user = null;

    public function prepareTarget()
    {
        $this->nb_edit_user = null;
        $this->nb_edit_site = null;
        $this->nb_edit_site_user = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 3) {
            $this->nb_edit_user = $this->nb_work_customer->getUser($fragments[1]);
            if ($this->nb_edit_user instanceof CNabuUser) {
                $this->nb_edit_user->refresh();
                if (is_numeric($fragments[2]) &&
                    ($this->nb_edit_site = $this->nb_work_customer->getSite($fragments[2])) instanceof CNabuSite &&
                    ($this->nb_edit_site_user = $this->nb_edit_site->getUserProfile($this->nb_edit_user)) instanceof CNabuSiteUser
                ) {
                    $this->nb_edit_site_user->refresh();
                } elseif (!$fragments[2]) {
                    $this->nb_edit_site_user = new CNabuSiteUser();
                    $this->nb_edit_site_user->setUserId($this->nb_edit_user->getId());
                }
            }
        }

        return true;
    }

    public function methodGET()
    {
        if ($this->nb_edit_site_user instanceof CNabuSiteUser) {
            $this->setStatusOK();
            $this->setData($this->nb_edit_site_user->getTreeData(null, true));
        }

        return true;
    }

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

    private function doPOSTSave()
    {
        if ($this->nb_edit_site_user->isNew()) {
            if ($this->nb_edit_site === null &&
                $this->nb_request->hasPOSTField('site_id') &&
                is_numeric($nb_site_id = $this->nb_request->getPOSTField('site_id')) &&
                ($this->nb_edit_site = $this->nb_work_customer->getSite($nb_site_id)) instanceof CNabuSite
            ) {
                $this->nb_edit_site_user->setSiteId($nb_site_id);
                $this->nb_edit_site_user->setRoleId($this->nb_edit_site->getDefaultRoleId());
                $this->nb_edit_site_user->setLanguageId($this->nb_edit_site->getDefaultLanguageId());
            } else {
                $this->nb_edit_site_user = null;
            }
        }

        if ($this->nb_edit_site_user instanceof CNabuSiteUser) {
            $this->nb_request->updateObjectFromPost(
                $this->nb_edit_site_user,
                array(
                    'role_id' => 'nb_role_id',
                    'language_id' => 'nb_language_id',
                    'force_default_lang' => 'nb_site_user_force_default_lang'
                )
            );
            if ($this->nb_request->hasPOSTField('attributes')) {
                $this->nb_edit_site_user->setAttributes($this->nb_request->getPOSTField('attributes'));
            } elseif ($this->nb_request->hasPOSTField('attrs')) {
                $this->nb_edit_site_user->setAttributes($this->nb_request->getPOSTField('attrs'));
            }
            if ($this->nb_edit_site_user->save()) {
                $this->setStatusOK();
                $this->setData($this->nb_edit_site_user->getTreeData(null, true));
            }
        }
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        if ($this->getStatus() === 'OK') {
            $api_mask = $this->nb_site_target->getFullyQualifiedURL($this->nb_language, true);
            $api_url = sprintf($api_mask, $this->nb_edit_user->getId(), $this->nb_edit_site->getId());
            $this->setAPICall($api_url);
            $editor_cta = $this->nb_site_target->getCTAs()->getItem('ajax_user_site', CNabuSiteTargetCTAList::INDEX_KEY);
            $editor_cta->canonize();
            $urls = array();
            $editor_cta->getTranslations()->iterate(function ($key, $translation) use(&$urls) {
                $editor_mask = $translation->getFinalURL();
                $urls[$key] = sprintf($editor_mask, $this->nb_edit_user->getId(), $this->nb_edit_site->getId());
            });
            $this->setEditorCall($urls);
        }

        return parent::beforeDisplayTarget();
    }
}
