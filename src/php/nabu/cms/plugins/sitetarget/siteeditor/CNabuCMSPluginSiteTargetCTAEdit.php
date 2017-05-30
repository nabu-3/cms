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
use nabu\data\customer\CNabuCustomer;
use nabu\data\site\CNabuSite;
use nabu\data\site\CNabuSiteAlias;
use nabu\data\site\CNabuSiteTarget;
use nabu\data\site\CNabuSiteTargetCTA;
use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\siteeditor
 */
class CNabuCMSPluginSiteTargetCTAEdit extends CNabuHTTPSiteTargetPluginAdapter
{
    /** @var CNabuSite $edit_site Site instance that contains the Site Target CTA instance. */
    private $edit_site = null;
    /** @var CNabuSiteTarget $edit_site_target Site Target instance that contains the Site Target CTA instance. */
    private $edit_site_target = null;
    /** @var CNabuSiteTargetCTA $edit_site_target_cta Site Target CTA instance to be edited. */
    private $edit_site_target_cta = null;
    /** @var string $base_url URL base of $edit_site. */
    private $base_url;

    public function prepareTarget()
    {
        $this->edit_site = null;
        $this->edit_site_target = null;
        $this->edit_site_target_cta = null;

        if (is_array($fragments = $this->nb_request->getRegExprURLFragments()) &&
            count($fragments) === 4 &&
            is_numeric($site_id = $fragments[1]) &&
            is_numeric($target_id = $fragments[2]) &&
            $this->nb_work_customer instanceof CNabuCustomer &&
            $this->nb_work_customer->refresh(true, false) &&
            ($this->edit_site = $this->nb_work_customer->getSite($site_id)) instanceof CNabuSite &&
            $this->edit_site->refresh(true, true) &&
            ($this->edit_site_target = $this->edit_site->getTarget($target_id)) instanceof CNabuSiteTarget &&
            $this->edit_site_target->refresh(true, true)
        ) {
            if (is_numeric($cta_id = $fragments[3])) {
                $this->edit_site_target_cta = $this->edit_site_target->getCTA($cta_id);
            }

            $nb_main_alias = $this->edit_site->getMainAlias();
            $this->base_url = $nb_main_alias instanceof CNabuSiteAlias
                ? ($this->edit_site->isHTTPSSupportEnabled()
                      ? 'https://' . $nb_main_alias->getDNSName()
                      : ($this->edit_site->isHTTPSupportEnabled()
                      ? 'http://' . $nb_main_alias->getDNSName()
                      : null)
                  )
                : null
            ;
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        $render->smartyAssign('edit_site', $this->edit_site, $this->nb_language);
        $render->smartyAssign('edit_site_target', $this->edit_site_target, $this->nb_language);
        $render->smartyAssign('edit_site_target_cta', $this->edit_site_target_cta, $this->nb_language);
        $render->smartyAssign('base_url', $this->base_url);

        return true;
    }
}
