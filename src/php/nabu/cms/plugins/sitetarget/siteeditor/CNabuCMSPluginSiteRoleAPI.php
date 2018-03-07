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

namespace nabu\cms\plugins\sitetarget\siteeditor;
use nabu\cms\plugins\sitetarget\base\CNabuCMSPluginAbstractAPI;
use nabu\data\security\CNabuRole;
use nabu\data\site\CNabuSite;
use nabu\data\site\CNabuSiteRole;
use nabu\data\site\CNabuSiteTargetCTAList;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\siteeditor
 */
class CNabuCMSPluginSiteRoleAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuSite $nb_edit_site Site selected */
    private $nb_edit_site = null;
    /** @var CNabuRole $nb_edit_role Role selected */
    private $nb_edit_role = null;
    /** @var CNabuSiteRole $nb_edit_site_role Static Content selected */
    private $nb_edit_site_role = null;

    public function prepareTarget()
    {
        $this->nb_edit_site = null;
        $this->nb_edit_role = null;
        $this->nb_edit_site_role = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 3) {
            $this->nb_edit_site = $this->nb_work_customer->getSite($fragments[1]);
            if ($this->nb_edit_site instanceof CNabuSite) {
                $this->nb_edit_site->refresh();
                if (is_numeric($fragments[2])) {
                    $this->nb_edit_role = $this->nb_work_customer->getRole($fragments[2]);
                    $this->nb_edit_role->refresh(true, true);
                    $this->nb_edit_site_role = $this->nb_edit_site->getSiteRole($fragments[2]);
                    $this->nb_edit_site_role->refresh();
                } elseif (!$fragments[2]) {
                    $this->nb_edit_site_role = new CNabuSiteRole();
                    $this->nb_edit_site_role->setSite($this->nb_edit_site);
                }
            }
        }

        return true;
    }

    public function methodGET()
    {
        if ($this->nb_edit_site_role instanceof CNabuSiteRole) {
            $this->setStatusOK();
            $this->setData($this->nb_edit_site_role->getTreeData(null, true));
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
        if ($this->nb_edit_site_role instanceof CNabuSiteRole) {
            $this->nb_request->updateObjectFromPost(
                $this->nb_edit_site_role,
                array(
                    'role_id' => 'nb_role_id',
                    'login_redirection_target_use_uri' => 'nb_site_role_login_redirection_target_use_uri',
                    'login_redirection_target_id' => 'nb_site_role_login_redirection_target_id',
                    'messaging_template_new_user' => 'nb_messaging_template_new_user',
                    'messaging_template_forgot_password' => 'nb_messaging_template_forgot_password',
                    'messaging_template_notify_new_user' => 'nb_messaging_template_notify_new_user',
                    'messaging_template_remember_new_user' => 'nb_messaging_template_remember_new_user',
                    'messaging_template_invite_user' => 'nb_messaging_template_invite_user',
                    'messaging_template_invite_friend' => 'nb_messaging_template_invite_friend',
                    'messaging_template_new_message' => 'nb_messaging_template_new_message'
                ),
                null,
                array(
                    'login_redirection_target_url' => '',
                    'messaging_template_new_user' => '0',
                    'messaging_template_forgot_password' => '0',
                    'messaging_template_notify_new_user' => '0',
                    'messaging_template_remember_new_user' => '0',
                    'messaging_template_invite_user' => '0',
                    'messaging_template_invite_friend' => '0',
                    'messaging_template_new_message' => '0'
                )
            );
            if ($this->nb_edit_site_role->save()) {
                $this->setStatusOK();
                $this->setData($this->nb_edit_site_role->getTreeData(null, true));
            }
        }
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        if ($this->getStatus() === 'OK') {
            $api_mask = $this->nb_site_target->getFullyQualifiedURL($this->nb_language, true);
            $api_url = sprintf($api_mask, $this->nb_edit_site->getId(), $this->nb_edit_site_role->getRoleId());
            $this->setAPICall($api_url);
            $editor_cta = $this->nb_site_target->getCTAs()->getItem('ajax_roles', CNabuSiteTargetCTAList::INDEX_KEY);
            $editor_cta->canonize();
            $urls = array();
            $editor_cta->getTranslations()->iterate(function ($key, $translation) use(&$urls) {
                $editor_mask = $translation->getFinalURL();
                $urls[$key] = sprintf($editor_mask, $this->nb_edit_site->getId(), $this->nb_edit_site_role->getRoleId());
            });
            $this->setEditorCall($urls);
        }

        return parent::beforeDisplayTarget();
    }
}
