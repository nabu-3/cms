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

namespace nabu\cms\plugins\sitetarget\commerceeditor;

use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @since 3.0.0 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\commerceeditor
 */
class CNabuCMSPluginCommerceList extends CNabuHTTPSiteTargetPluginAdapter
{
    /**
     * Site Target key for API Call
     * @var string
     */
    const TARGET_API_CALL = 'api_commerces';
    /**
     * Site Target key for Users Profile editor.
     * @var string
     */
    const TARGET_USER_EDIT = 'commerce_edit';

    /**
     * Commerce list
     * @var array
     */
    private $commerce_data = null;

    public function prepareTarget()
    {
        $this->commerce_data = $this->nb_work_customer->getCommerces(true);

        return true;
    }

    public function methodGET()
    {
        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        $render->smartyAssign('nb_languages', $this->nb_work_customer->getCommerceSetUsedLanguages());
        $render->smartyAssign('data', $this->commerce_data, $this->nb_language);

        return true;
    }
}
