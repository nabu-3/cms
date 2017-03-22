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

namespace nabu\cms\plugins\sitetarget\messagingeditor;
use nabu\cms\plugins\sitetarget\base\CNabuCMSPluginAbstractAPI;
use nabu\core\CNabuEngine;
use nabu\data\messaging\CNabuMessaging;
use nabu\data\messaging\CNabuMessagingService;
use nabu\data\site\CNabuSiteTargetCTAList;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @since 3.0.1 Surface
 * @version 3.0.1 Surface
 * @package \nabu\cms\plugins\sitetarget\messagingeditor
 */
class CNabuCMSPluginMessagingServiceAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuMessaging $nb_messaging Messaging selected */
    private $nb_messaging = null;
    /** @var CNabuMessagingService $nb_messaging_service Service selected */
    private $nb_messaging_service = null;

    public function prepareTarget()
    {
        $this->nb_messaging = null;
        $this->nb_messaging_service = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 3) {
            $this->nb_messaging = $this->nb_work_customer->getMessaging($fragments[1]);
            if ($this->nb_messaging instanceof CNabuMessaging) {
                $this->nb_messaging->refresh();
                if (is_numeric($fragments[2])) {
                    $this->nb_messaging_service = $this->nb_messaging->getServices()->getItem($fragments[2]);
                    $this->nb_messaging_service->refresh();
                } elseif (!$fragments[2]) {
                    $this->nb_messaging_service = new CNabuMessagingService();
                    $this->nb_messaging_service->setMessaging($this->nb_messaging);
                    $this->nb_messaging_service->setHash(nb_generateGUID());
                }
            }
        }

        return true;
    }

    public function methodGET()
    {
        if ($this->nb_messaging_service instanceof CNabuMessagingService) {
            $this->setStatusOK();
            $this->setData($this->nb_messaging_service->getTreeData(null, true));
        }

        return true;
    }

    public function methodPOST()
    {
        if ($this->nb_request->hasGETField('action')) {
            $action = $this->nb_request->getGETField('action');
            switch (strtolower($action)) {
                case 'test':
                    $this->doTest();
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
        if ($this->nb_messaging_service instanceof CNabuMessagingService) {
            $this->nb_request->updateObjectFromPost(
                $this->nb_messaging_service,
                array(
                    'key' => 'nb_messaging_service_key',
                    'hash' => 'nb_messaging_service_hash',
                    'name' => 'nb_messaging_service_name',
                    'provider' => 'nb_messaging_service_provider',
                    'interface' => 'nb_messaging_service_interface',
                    'attrs' => 'nb_messaging_service_attributes'
                )
            );
            if ($this->nb_messaging_service->isValueEmpty('nb_messaging_service_hash')) {
                $this->nb_messaging_service->setHash(nb_generateGUID());
            }
            if ($this->nb_messaging_service->save()) {
                $this->setStatusOK();
                $this->setData($this->nb_messaging_service->getTreeData(null, true));
            }
        }
    }

    private function doTest()
    {
        if ($this->nb_messaging_service instanceof CNabuMessagingService) {
            $vendor = explode(":", $this->nb_messaging_service->getProvider());
            $nb_messaging_manager = $this->nb_engine->getProviderManager($vendor[0], $vendor[1]);
            $nb_service_interface =
                $nb_messaging_manager->createServiceInterface($this->nb_messaging_service->getInterface());
            $nb_service_interface->connect($this->nb_messaging_service);

            $nb_service_interface->disconnect();
            $nb_messaging_manager->releaseServiceInterface($nb_service_interface);
            
            $this->setStatusOK();
        }
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        if ($this->getStatus() === 'OK') {
            $api_mask = $this->nb_site_target->getFullyQualifiedURL($this->nb_language, true);
            $api_url = sprintf($api_mask, $this->nb_messaging->getId(), $this->nb_messaging_service->getId());
            $this->setAPICall($api_url);
            $editor_cta = $this->nb_site_target->getCTAs()->getItem('ajax_services', CNabuSiteTargetCTAList::INDEX_KEY);
            $editor_cta->canonize();
            $urls = array();
            $editor_cta->getTranslations()->iterate(function ($key, $translation) use(&$urls) {
                $editor_mask = $translation->getFinalURL();
                $urls[$key] = sprintf($editor_mask, $this->nb_messaging->getId(), $this->nb_messaging_service->getId());
            });
            $this->setEditorCall($urls);
        }

        return parent::beforeDisplayTarget();
    }
}
