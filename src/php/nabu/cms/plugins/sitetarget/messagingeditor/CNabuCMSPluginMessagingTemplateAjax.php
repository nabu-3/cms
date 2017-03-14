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
use nabu\data\messaging\CNabuMessaging;
use nabu\data\messaging\CNabuMessagingTemplate;
use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;
use providers\smarty\smarty\renders\CSmartyHTTPRender;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @since 3.0.1 Surface
 * @version 3.0.1 Surface
 * @package \nabu\cms\plugins\sitetarget\messagingeditor
 */
class CNabuCMSPluginMessagingTemplateAjax extends CNabuHTTPSiteTargetPluginAdapter
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
        error_log(print_r($fragments, true));
        if (is_array($fragments) && count($fragments) === 3 && is_numeric($fragments[1])) {
            $this->nb_messaging = $this->nb_work_customer->getMessaging($fragments[1]);
            if ($this->nb_messaging instanceof CNabuMessaging) {
                $this->nb_messaging->refresh();
                if (is_numeric($fragments[2])) {
                    $this->nb_messaging_template = $this->nb_messaging->getTemplates()->getItem($fragments[2]);
                }
            }
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();

        if ($render instanceof CSmartyHTTPRender) {
            $render->smartyAssign('nb_messaging', $this->nb_messaging, $this->nb_language);
            $render->smartyAssign('edit_template', $this->nb_messaging_template, $this->nb_language);
            $render->smartyAssign('nb_all_languages', $this->nb_messaging->getLanguages());
        }

        return true;
    }
}
