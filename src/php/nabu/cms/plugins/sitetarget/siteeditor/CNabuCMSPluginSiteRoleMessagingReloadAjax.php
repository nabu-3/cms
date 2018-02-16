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

use nabu\data\site\CNabuSite;
use nabu\data\site\CNabuSiteRole;

use nabu\data\messaging\CNabuMessaging;

use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\siteeditor
 */
class CNabuCMSPluginSiteRoleMessagingReloadAjax extends CNabuHTTPSiteTargetPluginAdapter
{
    /** @var CNabuSite The Site instance to be requested */
    private $nb_edit_site = null;
    /** @var CNabuSiteRole The Site Role instance to be edited */
    private $nb_edit_site_role = null;
    /** @var CNabuMessaging The Messaging instance to be requested */
    private $nb_edit_messaging = null;

    public function prepareTarget()
    {
        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 4) {
            $this->nb_edit_site = $this->nb_work_customer->getSite($fragments[1]);
            $this->nb_edit_site_role = $this->nb_edit_site->getSiteRole($fragments[2]);
            $this->nb_edit_messaging = $this->nb_work_customer->getMessaging($fragments[3]);
            if (!($this->nb_edit_messaging instanceof CNabuMessaging)) {
                $this->nb_edit_messaging = null;
            }
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        $render->smartyAssign('edit_source', $this->nb_edit_site_role, $this->nb_language);
        $render->smartyAssign('templates', ($this->nb_edit_messaging !== null ? $this->nb_edit_messaging->getTemplates(true) : null), $this->nb_language);

        return true;
    }
}
