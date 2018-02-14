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
use nabu\data\messaging\CNabuMessagingTemplateLanguage;
use nabu\data\security\CNabuUser;
use nabu\data\site\CNabuSite;
use nabu\cms\plugins\sitetarget\base\CNabuCMSPluginAbstractAPI;
use nabu\data\site\CNabuSiteUser;
use nabu\http\CNabuHTTPRequest;
use nabu\http\renders\CNabuHTTPResponseFileRender;
use nabu\messaging\CNabuMessagingFactory;
use nabu\messaging\managers\CNabuMessagingPoolManager;
use nabu\sdk\package\CNabuPackage;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\siteeditor
 */
class CNabuCMSPluginSiteAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuSite $edit_site Site instance */
    private $edit_site = null;

    public function prepareTarget()
    {
        if (count($fragments = $this->nb_request->getRegExprURLFragments()) === 2) {
            if (is_numeric($fragments[1])) {
                $this->edit_site = $this->nb_work_customer->getSite($fragments[1]);
            } elseif (!$fragments[1] && !$this->nb_request->hasGETField('action')) {
                $this->edit_site = new CNabuSite();
                $this->edit_site->setCustomer($this->nb_work_customer);
                $this->edit_site->setHash(nb_generateGUID());
            }
        }

        return true;
    }

    public function methodPOST()
    {
        if ($this->nb_request->hasGETField('action')) {
            $action = $this->nb_request->getGETField('action');
            switch (strtolower($action)) {
                case 'download':
                    $this->doDownload();
                    break;
                case 'notify':
                    $this->doNotify();
                    break;
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
        if ($this->edit_site instanceof CNabuSite) {
            $this->nb_request->updateObjectFromPost(
                $this->edit_site,
                array(
                    'hash' => 'nb_site_hash',
                    'key' => 'nb_site_key',
                    'cluster_group_id' => 'nb_cluster_group_id',
                    'cluster_user_id' => 'nb_cluster_user_id',
                    'project_id' => 'nb_project_id',
                    'commerce_id' => 'nb_commerce_id',
                    'messaging_id' => 'nb_messaging_id',
                    'app_os_id' => 'app_os_id',
                    'delegate_for_role' => 'nb_site_delegate_for_role',
                    'default_role_id' => 'nb_site_default_role_id',
                    'default_language_id' => 'nb_site_default_language_id',
                    'api_language_id' => 'nb_site_api_language_id',
                    'main_alias_id' => 'nb_site_main_alias_id',
                    'mounting_order' => 'nb_site_mounting_order',
                    'published' => 'nb_site_published',
                    'default_target_use_uri' => 'nb_site_default_target_use_uri',
                    'default_target_id' => 'nb_site_default_target_id',
                    'page_not_found_target_use_uri' => 'nb_site_page_not_found_target_use_uri',
                    'page_not_found_target_id' => 'nb_site_page_not_found_target_id',
                    'page_not_found_error_code' => 'nb_site_page_not_found_error_code',
                    'login_target_use_uri' => 'nb_site_login_target_use_uri',
                    'login_target_id' => 'nb_site_login_target_id',
                    'login_redirection_target_use_uri' => 'nb_site_login_redirection_target_use_uri',
                    'login_redirection_target_id' => 'nb_site_login_redirection_target_id',
                    'alias_not_found_target_use_uri' => 'nb_site_alias_not_found_target_use_uri',
                    'alias_not_found_target_id' => 'nb_site_alias_not_found_target_id',
                    'alias_locked_target_use_uri' => 'nb_site_alias_locked_target_use_uri',
                    'alias_locked_target_id' => 'nb_site_alias_locked_target_id',
                    'policies_target_use_uri' => 'nb_site_policies_target_use_uri',
                    'policies_target_id' => 'nb_site_policies_target_id',
                    'require_policies_after_login' => 'nb_site_require_policies_after_login',
                    'use_cache' => 'nb_site_use_cache',
                    'cache_handler' => 'nb_site_cache_handler',
                    'use_smarty' => 'nb_site_use_smarty',
                    'smarty_error_reporting' => 'nb_site_smarty_error_reporting',
                    'smarty_debugging' => 'nb_site_smarty_debugging',
                    'smarty_template_path' => 'nb_site_smarty_template_path',
                    'smarty_compile_path' => 'nb_site_smarty_compile_path',
                    'smarty_cache_path' => 'nb_site_smarty_cache_path',
                    'smarty_configs_path' => 'nb_site_smarty_configs_path',
                    'smarty_models_path' => 'nb_site_smarty_models_path',
                    'plugin_name' => 'nb_site_plugin_name',
                    'http_suport' => 'nb_site_http_support',
                    'https_support' => 'nb_site_https_support',
                    'google_wmr_domain_id' => 'nb_site_google_wmr_domain_id',
                    'google_wmr_site_id' => 'nb_site_google_wmr_site_id',
                    'use_awstats' => 'nb_site_use_awstats',
                    'local_path' => 'nb_site_local_path',
                    'dynamic_cache_control' => 'nb_site_dynamic_cache_control',
                    'dynamic_cache_default_max_age' => 'nb_site_dynamic_cache_default_max_age',
                    'messaging_template_new_user' => 'nb_messaging_template_new_user',
                    'messaging_template_forgot_password' => 'nb_messaging_template_forgot_password',
                    'messaging_template_notify_new_user' => 'nb_messaging_template_notify_new_user',
                    'messaging_template_remember_new_user' => 'nb_messaging_template_remember_new_user',
                    'messaging_template_invite_user' => 'nb_messaging_template_invite_user',
                    'messaging_template_invite_friend' => 'nb_messaging_template_invite_friend',
                    'messaging_template_new_message' => 'nb_messaging_template_new_message',
                    'wsearch_enabled' => 'nb_site_wsearch_enabled',
                    'use_framework' => 'nb_site_use_framework',
                    'enable_vhost_file' => 'nb_site_enable_vhost_file',
                    'session_timeout_interval' => 'nb_site_session_timeout_interval',
                    'session_preserve_interval' => 'nb_site_session_preserve_interval',
                    'enable_session_strict_policies' => 'nb_site_enable_session_strict_policies',
                    'static_content_use_alternative' => 'nb_site_static_content_use_alternative',
                    'base_path' => 'nb_site_base_path',
                    'modules_slots' => 'nb_site_modules_slots',
                    'notification_email' => 'nb_site_notification_email'
                )
            );
            if ($this->edit_site->save()) {
                $this->setStatusOK();
                $this->setData($this->edit_site->getTreeData(null, true));
            }
        }
    }

    /**
     * Process download action.
     */
    private function doDownload()
    {
        if ($this->nb_request->hasPOSTField('ids')) {
            $ids = $this->nb_request->getPOSTField('ids');
            if (is_numeric($ids)) {
                $ids = array($ids);
            } else if (is_string($ids)) {
                $ids = preg_split('/\s*,\s*/', $ids);
            }

            if (is_array($ids) && count($ids) > 0) {
                $nb_package = new CNabuPackage($this->nb_work_customer);
                $nb_package->addSites($ids);
                $filename = tempnam('/tmp', 'nb_site_export_');
                $nb_package->export($filename);

                $render = new CNabuHTTPResponseFileRender($this->nb_application);
                $this->nb_response->setRender($render);
                $this->nb_response->setMimetype('application/nak');
                $this->nb_response->setHeader('Content-Disposition', 'attachment; filename=nabu-3-dump.nak');
                $render->setSourceFile($filename);
                $render->unlinkSourceFileAfterRender();
            }
        } else {
            $this->setStatusError("Ids array not found");
        }
    }

    /**
     * Process notify action.
     */
    private function doNotify()
    {
        $nb_user_list = $this->nb_work_customer->getUsers();
        $force_notification = ($this->nb_request->getPOSTField('reset_notifications') === 'T');
        $force_role = $this->nb_request->hasPOSTField('apply_role') ? $this->nb_request->getPOSTField('apply_role') : '-1';

        if ($nb_user_list->getSize() > 0) {
            $nb_messaging = $this->edit_site->getMessaging($this->nb_work_customer);
            $nb_messaging_template = $nb_messaging->getTemplateByKey('new_update');
            $nb_messaging_pool_manager = new CNabuMessagingPoolManager($this->nb_work_customer);

            if (($nb_messaging_factory = $nb_messaging_pool_manager->getFactory($nb_messaging)) instanceof CNabuMessagingFactory) {
                error_log("Items " . $nb_user_list->getSize());
                $nb_user_list->iterate(
                    function ($key, CNabuUser $nb_user)
                         use ($nb_messaging_factory, $nb_messaging_template, $force_notification, $force_role)
                    {
                        $nb_site_user = $this->edit_site->getUserProfile($nb_user);
                        if ($force_notification &&
                            $nb_user->getAllowNotification() !== 'T' &&
                            ($force_role == -1 || $force_role == $nb_site_user->getRoleId())
                        ) {
                            $nb_user->setAllowNotification('T');
                            $nb_user->save();
                        }
                        if ($nb_user->getAllowNotification() === 'T') {
                            if ($nb_site_user instanceof CNabuSiteUser &&
                                ($force_role == -1 || $force_role == $nb_site_user->getRoleId())
                            ) {
                                error_log("Sending to " . $nb_user->getEmail());
                                $nb_messaging_factory->postTemplateMessage(
                                    $nb_messaging_template, $nb_site_user->getLanguageId(), $nb_user, null, null, null
                                );
                            }
                        }
                        return true;
                    }
                );

                $this->setStatusOK();
            } else {
                $this->setStatusError('Notify cannot be performed');
            }

        }

        $this->setStatusOK();

        return true;
    }
}
