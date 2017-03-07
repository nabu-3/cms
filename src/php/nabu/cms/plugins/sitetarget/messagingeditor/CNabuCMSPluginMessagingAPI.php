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
use nabu\data\messaging\CNabuMessaging;
use nabu\data\messaging\CNabuMessagingLanguage;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @since 3.0.1 Surface
 * @version 3.0.1 Surface
 * @package \nabu\cms\plugins\sitetarget
 */
class CNabuCMSPluginMessagingAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuMessaging $nb_messaging Messaging instance */
    private $nb_messaging = null;

    public function prepareTarget()
    {
        if (count($fragments = $this->nb_request->getRegExprURLFragments()) === 2) {
            if (is_numeric($fragments[1])) {
                $this->nb_messaging = $this->nb_work_customer->getMessaging($fragments[1]);
            } elseif (!$fragments[1]) {
                $this->nb_messaging = new CNabuMessaging();
                $this->nb_messaging->setCustomer($this->nb_work_customer);
                $this->nb_messaging->setHash(nb_generateGUID());
            }
        }

        return true;
    }

    public function methodPOST()
    {
        if ($this->nb_messaging instanceof CNabuMessaging) {
            $this->nb_request->updateObjectFromPost(
                $this->nb_messaging,
                array(
                    'key' => 'nb_messaging_key',
                    'hash' => 'nb_messaging_hash',
                    'default_lang_id' => 'nb_messaging_default_language_id'
                )
            );
            if ($this->nb_messaging->save()) {
                $languages = $this->nb_request->getCombinedPostIndexes(array('name'));
                if (count($languages) > 0) {
                    foreach ($languages as $lang_id) {
                        $nb_translation = $this->nb_messaging->getTranslation($lang_id);
                        if (!$nb_translation) {
                            $nb_translation = new CNabuMessagingLanguage();
                            $nb_translation->setMessagingId($this->nb_messaging->getId());
                            $nb_translation->setLanguageId($lang_id);
                            $this->nb_messaging->setTranslation($nb_translation);
                        }
                        $this->nb_request->updateObjectFromPost(
                            $nb_translation,
                            array(
                                'name' => 'nb_messaging_lang_name',
                                'templates_status' => 'nb_messaging_templates_status'
                            ),
                            null,
                            null,
                            $lang_id
                        );
                        $nb_translation->save(true);
                    }
                }
                $this->setStatusOK();
                $this->setData($this->nb_messaging->getTreeData(null, true));
            }
        }

        return true;
    }
}
