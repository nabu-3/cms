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

namespace nabu\cms\plugins\sitetarget;
use nabu\data\security\CNabuUser;
use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\cms\plugins\sitetarget
 */
class CNabuCMSPluginuserList extends CNabuHTTPSiteTargetPluginAdapter
{
    /**
     * Site Target key for API Call
     * @var string
     */
    const TARGET_API_CALL = 'api_users';
    /**
     * Site Target key for Users Profile editor.
     * @var string
     */
    const TARGET_USER_EDIT = 'user_edit';

    private $user_data = null;
    private $api_call = null;

    public function prepareTarget()
    {
        $nb_site_target = $this->nb_site->getTargetByKey(self::TARGET_API_CALL);
        $this->user_data = CNabuUser::getFilteredUserList(
            $this->nb_work_customer,
            null,
            array('id', 'first_name', 'last_name', 'login')
        );

        return true;
    }

    public function methodGET()
    {
        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        $render->smartyAssign('data', $this->user_data);

        return true;
    }
}
