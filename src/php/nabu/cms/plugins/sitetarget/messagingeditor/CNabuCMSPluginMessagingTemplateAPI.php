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
use nabu\data\messaging\CNabuMessagingTemplate;
use nabu\data\messaging\CNabuMessagingTemplateLanguage;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @since 3.0.1 Surface
 * @version 3.0.1 Surface
 * @package \nabu\cms\plugins\sitetarget\messagingeditor
 */
class CNabuCMSPluginMessagingTemplateAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuMessaging $nb_messaging Messaging selected */
    private $nb_messaging = null;
    /** @var CNabuMessagingTemplate $nb_messaging_template Template selected */
    private $nb_messaging_template = null;

    public function prepareTarget()
    {
        $this->nb_messaging = null;
        $this->nb_messaging_template = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 3) {
            $this->nb_messaging = $this->nb_work_customer->getMessaging($fragments[1]);
            if ($this->nb_messaging instanceof CNabuMessaging) {
                $this->nb_messaging->refresh();
                if (is_numeric($fragments[2])) {
                    $this->nb_messaging_template = $this->nb_messaging->getTemplates()->getItem($fragments[2]);
                    $this->nb_messaging_template->refresh();
                } elseif (!$fragments[2]) {
                    $this->nb_messaging_template = new CNabuMessagingTemplate();
                    $this->nb_messaging_template->setMessaging($this->nb_messaging);
                    $this->nb_messaging_template->setHash(nb_generateGUID());
                }
            }
        }

        return true;
    }

    public function methodGET()
    {
        if ($this->nb_messaging_template instanceof CNabuMessagingTemplate) {
            $this->setStatusOK();
            $this->setData($this->nb_messaging_template->getTreeData(null, true));
        }

        return true;
    }

    public function methodPOST()
    {
        if ($this->nb_messaging_template instanceof CNabuMessagingTemplate) {
            $this->nb_request->updateObjectFromPost(
                $this->nb_messaging_template,
                array(
                    'key' => 'nb_messaging_template_key',
                    'hash' => 'nb_messaging_template_hash'
                )
            );
            if ($this->nb_messaging_template->isValueEmpty('nb_messaging_template_hash')) {
                $this->nb_messaging_template->setHash(nb_generateGUID());
            }
            if ($this->nb_messaging_template->save()) {
                $languages = $this->nb_request->getCombinedPostIndexes(array('name'));
                if (count($languages) > 0) {
                    foreach ($languages as $lang_id) {
                        $nb_translation = $this->nb_messaging_template->getTranslations()->getItem($lang_id);
                        error_log(print_r($nb_translation->getTreeData(), true));
                        if (!$nb_translation) {
                            $nb_translation = new CNabuMessagingTemplateLanguage();
                            $nb_translation->setMessagingTemplateId($this->nb_messaging_template->getId());
                            $nb_translation->setLanguageId($lang_id);
                            $this->nb_messaging_template->setTranslation($nb_translation);
                        }
                        $this->nb_request->updateObjectFromPost(
                            $nb_translation,
                            array(
                                'name' => 'nb_messaging_template_lang_name',
                                'subject' => 'nb_messaging_template_lang_subject',
                                'html' => 'nb_messaging_template_lang_html'
                            ),
                            null,
                            null,
                            $lang_id
                        );
                        $nb_translation->save(true);
                    }
                }

                $this->setStatusOK();
                $this->setData($this->nb_messaging_template->getTreeData(null, true));
            }
        }

        return true;
    }
}
