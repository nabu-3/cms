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
use nabu\data\lang\CNabuLanguage;
use nabu\data\site\CNabuSite;
use nabu\data\site\CNabuSiteLanguage;
use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\cms\plugins\sitetarget
 */
class CNabuCMSPluginSiteEdit extends CNabuHTTPSiteTargetPluginAdapter
{
    /** @var CNabuSite $edit_site Site instance to be edited. */
    private $edit_site;
    /** @var array Title fragments for the title. */
    private $title_part;
    /** @var array $breadcrumb_part Breadcrumb fragments. */
    private $breadcrumb_part;

    public function prepareTarget()
    {
        $this->edit_site = null;
        $this->title_part = null;
        $this->breadcrumb_part = null;

        $retval = true;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) > 1) {
            $id = $fragments[1];
            $this->title_part = '#' . $id;
            $this->nb_work_customer->refresh(true, true);
            if (($this->edit_site = $this->nb_work_customer->getSite($id)) instanceof CNabuSite &&
                $this->edit_site->refresh(true, true)
            ) {
                if (($translation = $this->edit_site->getTranslation($this->nb_language)) instanceof CNabuSiteLanguage &&
                    (strlen($this->title_part = $translation->getName()) === 0) &&
                    (strlen($this->title_part = $this->edit_site->getKey()) === 0)
                ) {
                    $this->title_part = '#' . $id;
                }
                $this->breadcrumb_part['site_edit'] = array(
                    'title' => $this->title_part,
                    'slug' => $id
                );
            } else {
                $retval = $this->nb_site->getTargetByKey('site_list');
            }
        }

        return $retval;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        $render->smartyAssign('edit_site', $this->edit_site, $this->nb_language);
        $render->smartyAssign('edit_targets', $this->edit_site->getTargets(), $this->nb_language);
        $render->smartyAssign('edit_statics', $this->edit_site->getStaticContents(), $this->nb_language);
        $render->smartyAssign('edit_sitemaps', $this->edit_site->getSiteMaps(), $this->nb_language);
        $render->smartyAssign('title_part', $this->title_part);
        $render->smartyAssign('breadcrumb_part', $this->breadcrumb_part);

        $render->smartyAssign(
            'nb_all_languages',
            CNabuLanguage::getAllLanguages(),
            $this->nb_language
        );

        return true;
    }
}
