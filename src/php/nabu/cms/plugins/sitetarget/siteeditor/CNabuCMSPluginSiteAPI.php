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

    /**
     * Treats the action passed as GET parameter.
     * @return mixed Return true if success.
     */
    public function commandaction()
    {
        if ($this->nb_request->hasGETField('action')) {
            $action = $this->nb_request->getGETField('action');
            if ($this->nb_request->getMethod() === CNabuHTTPRequest::METHOD_POST) {
                switch ($action) {
                    case 'download':
                        $this->doDownload();
                        break;
                    case 'notify':
                        $this->doNotify();
                        break;
                    default:
                        $this->setStatusError("action value [$action] not supported by get verb");
                }
            }
        }

        return true;
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

    private function doNotify()
    {
        $nb_user_list = $this->nb_work_customer->getUsers();

        if ($nb_user_list->getSize() > 0) {
            $nb_messaging = $this->edit_site->getMessaging($this->nb_work_customer);
            $nb_messaging_template = $nb_messaging->getTemplateByKey('new_update');
            $nb_messaging_pool_manager = new CNabuMessagingPoolManager($this->nb_work_customer);

            if (($nb_messaging_factory = $nb_messaging_pool_manager->getFactory($nb_messaging)) instanceof CNabuMessagingFactory) {
                error_log("Items " . $nb_user_list->getSize());
                $nb_user_list->iterate(
                    function ($key, CNabuUser $nb_user) use ($nb_messaging_factory, $nb_messaging_template)
                    {
                        if ($nb_user->getAllowNotification() === 'T') {
                            error_log("Sending to " . $nb_user->getEmail());
                            $nb_site_user = $this->edit_site->getUserProfile($nb_user);
                            if ($nb_site_user instanceof CNabuSiteUser) {
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
