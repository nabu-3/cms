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
use nabu\data\site\CNabuSiteAlias;
use nabu\data\site\CNabuSiteMap;
use nabu\data\site\CNabuSiteLanguage;
use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;

class CNabuCMSPluginSiteMapEdit extends CNabuHTTPSiteTargetPluginAdapter
{
    /** @var CNabuSite $edit_site Site instance that contains the Site Map instance. */
    private $edit_site;
    /** @var CNabuSiteMap $edit_site_map Site Map instance to be edited. */
    private $edit_site_map;
    /** @var array Title fragments for the title. */
    private $title_part;
    /** @var array $breadcrumb_part Breadcrumb fragments. */
    private $breadcrumb_part;
    /** @var string $base_url URL base of $edit_site. */
    private $base_url;

    public function prepareTarget()
    {
        $this->edit_site = null;
        $this->edit_site_map = null;
        $this->title_part = array();
        $this->breadcrumb_part = null;

        $retval = true;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) > 1) {
            $site_id = $fragments[1];
            $this->title_part[0] = '#' . $site_id;
            $this->nb_work_customer->refresh(true, false);
            if (($this->edit_site = $this->nb_work_customer->getSite($site_id)) instanceof CNabuSite) {
                if ($this->edit_site->refresh(true, false) &&
                    ($translation = $this->edit_site->getTranslation($this->nb_language)) instanceof CNabuSiteLanguage &&
                    (strlen($this->title_part[0] = $translation->getName()) === 0) &&
                    (strlen($this->title_part[0] = $this->edit_site->getKey()) === 0)
                ) {
                    $this->title_part[0] = '#' . $site_id;
                }
                $this->breadcrumb_part['site_edit'] = array(
                    'title' => $this->title_part[0],
                    'slug' => $site_id
                );
                if (count($fragments) > 2) {
                    $map_id = $fragments[2];
                    $this->title_part[1] = '#' . $map_id;
                    if (($this->edit_site_map = $this->edit_site->getMap($map_id)) instanceof CNabuSiteMap &&
                        ($this->edit_site_map->refresh(true, true)) &&
                        ($translation = $this->edit_site_map->getTranslation($this->nb_language)) !== false &&
                        (strlen($this->title_part[1] = $translation->getTitle()) === 0) &&
                        (strlen($this->title_part[1] = $this->edit_site_map->getKey()) === 0)
                    ) {
                        $this->title_part[1] = '#' . $map_id;
                    }
                    $this->breadcrumb_part['site_map_edit'] = array(
                        'title' => $this->title_part[1],
                        'slug' => $map_id
                    );
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
            } else {
                $retval = $this->nb_site->getMapByKey('site_list');
            }
        }

        return $retval;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        $render->smartyAssign('edit_site', $this->edit_site, $this->nb_language);
        $render->smartyAssign('edit_site_map', $this->edit_site_map, $this->nb_language);
        $render->smartyAssign('title_part', $this->title_part);
        $render->smartyAssign('breadcrumb_part', $this->breadcrumb_part);
        $render->smartyAssign('base_url', $this->base_url);

        return true;
    }
}
