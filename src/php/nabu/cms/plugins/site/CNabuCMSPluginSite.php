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

namespace nabu\cms\plugins\site;
use nabu\data\customer\CNabuCustomer;
use nabu\data\security\CNabuRole;
use nabu\data\security\CNabuUser;
use nabu\data\site\CNabuSiteUser;
use nabu\http\adapters\CNabuHTTPSitePluginAdapter;
use providers\smarty\smarty\renders\CSmartyHTTPRender;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\cms\plugins\site
 */
class CNabuCMSPluginSite extends CNabuHTTPSitePluginAdapter
{
    /**
     * Customers list allowed for current User
     */
    private $nb_customer_list;

    public function prepareSite()
    {
        return true;
    }

    public function validateCORSOrigin($origin)
    {
        return true;
    }

    public function redirectRoot()
    {
        return true;
    }

    public function targetNotFound($path)
    {
        return true;
    }

    public function afterLogin(CNabuUser $nb_user, CNabuRole $nb_role, CNabuSiteUser $nb_site_user)
    {
        error_log("HOLA afterLogin");
        return true;
    }

    public function beforeLogout()
    {
        return true;
    }

    public function prepareTarget()
    {
        $this->nb_customer_list = null;

        if ($this->nb_user !== null) {
            $this->nb_customer_list = CNabuCustomer::getAllCustomers();
        }

        if ($this->nb_request->hasPOSTField('__x_nb_wc')) {
            $id = $this->nb_request->getPOSTField('__x_nb_wc');
            $this->nb_application->getSecurityManager()->validateWorkCustomer($id);
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        if ($render instanceof CSmartyHTTPRender) {
            $render->smartyAssign('nb_customer_list', $this->nb_customer_list);
        }

        return true;
    }
}
