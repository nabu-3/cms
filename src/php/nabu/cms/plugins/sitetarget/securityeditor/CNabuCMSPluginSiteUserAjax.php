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
use nabu\data\security\CNabuUser;
use nabu\data\site\CNabuSite;
use nabu\data\site\CNabuSiteUser;
use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;
use providers\smarty\smarty\renders\CSmartyHTTPRender;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\securityeditor
 */
class CNabuCMSPluginSiteUserAjax extends CNabuHTTPSiteTargetPluginAdapter
{
    /** @var CNabuUser $nb_edit_user User selected */
    private $nb_edit_user = null;
    /** @var CNabuSite $nb_edit_site_user Site selected */
    private $nb_edit_site = null;
    /** @var CNabuSiteUser $nb_edit_site_user Site User Profile selected */
    private $nb_edit_site_user = null;

    public function prepareTarget()
    {
        $this->nb_edit_user = null;
        $this->nb_edit_site = null;
        $this->nb_edit_site_user = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 3 && is_numeric($fragments[1]) &&
            ($this->nb_edit_user = $this->nb_work_customer->getUser($fragments[1])) instanceof CNabuUser &&
            $this->nb_edit_user->refresh(true, true) &&
            is_numeric($fragments[2]) &&
            ($this->nb_edit_site = $this->nb_work_customer->getSite($fragments[2])) instanceof CNabuSite
        ) {
            $this->nb_edit_site_user = $this->nb_edit_site->getUserProfile($fragments[1]);
        }

        $this->nb_work_customer->getRoles(true);

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();

        if ($render instanceof CSmartyHTTPRender) {
            $render->smartyAssign('edit_user', $this->nb_edit_user);
            $render->smartyAssign('edit_site', $this->nb_edit_site, $this->nb_language);
            $render->smartyAssign('edit_site_user', $this->nb_edit_site_user);
            $render->smartyAssign('nb_available_sites',
                CNabuSiteUser::getAvailableSitesForUser($this->nb_work_customer, $this->nb_edit_user),
                $this->nb_language
            );
        }

        return true;
    }
}
