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

namespace nabu\cms\plugins\sitetarget;
use nabu\data\security\CNabuUser;
use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\cms\plugins\sitetarget
 */
class CNabuCMSPluginAjaxUser extends CNabuHTTPSiteTargetPluginAdapter
{
    /**
     * Results of queried users
     * @var array
     */
    private $results = null;

    public function prepareTarget()
    {
        $q = ($this->nb_request->hasGETField('q') ? $this->nb_request->getGETField('q') : null);
        $f = ($this->nb_request->hasGETField('fields') ? $this->nb_request->getGETField('fields') : null);
        $o = ($this->nb_request->hasGETField('order') ? $this->nb_request->getGETField('order') : null);
        $b = ($this->nb_request->hasGETField('begin') ? $this->nb_request->getGETField('begin') : 0);
        $c = ($this->nb_request->hasGETField('count') ? $this->nb_request->getGETField('count') : 0);

        $this->results = CNabuUser::getFilteredUserList($this->nb_customer, $q, $f, $o, $b, $c);

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        $render->setValue('data', $this->results);

        return true;
    }
}
