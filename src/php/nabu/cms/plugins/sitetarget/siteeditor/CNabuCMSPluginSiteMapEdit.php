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

namespace nabu\cms\plugins\sitetarget\siteeditor;
use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;
use nabu\data\site\CNabuSite;

class CNabuCMSPluginSiteMapEdit extends CNabuHTTPSiteTargetPluginAdapter
{
    /**
     * @var CNabuSite
     */
    private $edit_site = null;
    private $edit_sitemap = null;

    public function prepareTarget()
    {
        $this->edit_site = null;
        $this->edit_sitemap = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) > 1) {
            $id = $fragments[1];
            $this->edit_sitemap = $this->nb_site->getSitemap($id);
            if (is_object($this->edit_sitemap)) {
                $this->edit_site = $this->edit_sitemap->getSite($this->nb_work_customer);
            }
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        $render->smartyAssign('edit_site', $this->edit_site, $this->nb_language);
        $render->smartyAssign('edit_sitemap', $this->edit_sitemap, $this->nb_language);
        $render->smartyAssign('edit_site_targets', $this->edit_site->getTargets(true), $this->nb_language);

        return true;
    }
}
