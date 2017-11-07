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
use nabu\data\site\CNabuSiteStaticContent;
use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;
use providers\smarty\smarty\renders\CSmartyHTTPRender;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\siteeditor
 */
class CNabuCMSPluginSiteStaticContentAjax extends CNabuHTTPSiteTargetPluginAdapter
{
    /** @var CNabuSite $nb_edit_site Site selected */
    private $nb_edit_site = null;
    /** @var CNabuSiteStaticContent $nb_edit_site_static_content Static Content selected */
    private $nb_edit_site_static_content = null;

    public function prepareTarget()
    {
        $this->nb_edit_site = null;
        $this->nb_edit_site_static_content = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 3 && is_numeric($fragments[1])) {
            $this->nb_edit_site = $this->nb_work_customer->getSite($fragments[1]);
            if ($this->nb_edit_site instanceof CNabuSite) {
                $this->nb_edit_site->refresh();
                if (is_numeric($fragments[2])) {
                    $this->nb_edit_site_static_content = $this->nb_edit_site->getStaticContent($fragments[2]);
                }
            }
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();

        if ($render instanceof CSmartyHTTPRender) {
            $render->smartyAssign('edit_site', $this->nb_edit_site, $this->nb_language);
            $render->smartyAssign('edit_static_content', $this->nb_edit_site_static_content, $this->nb_language);
            $render->smartyAssign('nb_all_languages', $this->nb_edit_site->getLanguages());
        }

        return true;
    }
}
