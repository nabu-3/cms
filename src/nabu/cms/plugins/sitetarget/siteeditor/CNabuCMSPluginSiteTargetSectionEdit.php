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
use nabu\data\site\CNabuSite;
use nabu\data\site\CNabuSiteTarget;
use nabu\data\site\CNabuSiteTargetSection;
use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;

class CNabuCMSPluginSiteTargetSectionEdit extends CNabuHTTPSiteTargetPluginAdapter
{
    /**
     * @var CNabuSite
     */
    private $edit_site = null;
    /**
     * @var CNabuSiteTarget
     */
    private $edit_site_target = null;
    /**
     * @var CNabuSiteTargetSection
     */
    private $edit_section = null;
    private $base_url;

    public function prepareTarget()
    {
        $this->edit_site = null;
        $this->edit_site_target = null;
        $this->edit_section = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if ($this->nb_work_customer !== null && is_array($fragments) && count($fragments) > 3) {
            $site_id = $fragments[1];
            $target_id = $fragments[2];
            $section_id = $fragments[3];

            if (($this->edit_site = $this->nb_work_customer->getSite($site_id)) !== false &&
                ($this->edit_site_target = $this->edit_site->getTarget($target_id)) !== false
            ) {
                $this->edit_section = $this->edit_site_target->getSection($section_id);
                $this->base_url =
                    ($this->edit_site->isHTTPSSupportEnabled()
                        ? 'https://' . $this->edit_site->getMainAlias()->getDNSName()
                        : ($this->edit_site->isHTTPSupportEnabled()
                        ? 'http://' . $this->edit_site->getMainAlias()->getDNSName()
                        : null)
                    )
                ;
            }
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        $render->smartyAssign('edit_site', $this->edit_site, $this->nb_language);
        $render->smartyAssign('edit_site_target', $this->edit_site_target, $this->nb_language);
        $render->smartyAssign('edit_site_target_section', $this->edit_section, $this->nb_language);
        $render->smartyAssign('base_url', $this->base_url);

        return true;
    }
}
