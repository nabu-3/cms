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
use nabu\data\site\CNabuSiteTarget;
use nabu\data\site\CNabuSiteLanguage;
use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;
use nabu\provider\CNabuProviderFactory;

class CNabuCMSPluginSiteTargetEdit extends CNabuHTTPSiteTargetPluginAdapter
{
    /** @var CNabuSite $edit_site Site instance that contains the Site Target instance. */
    private $edit_site;
    /** @var CNabuSiteTarget $edit_site_target Site Target instance to be edited. */
    private $edit_site_target;
    /** @var array Title fragments for the title. */
    private $title_part;
    /** @var array $breadcrumb_part Breadcrumb fragments. */
    private $breadcrumb_part;
    /** @var string $base_url URL base of $edit_site. */
    private $base_url;

    public function prepareTarget()
    {
        $this->edit_site = null;
        $this->edit_site_target = null;
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
                    $target_id = $fragments[2];
                    $this->title_part[1] = '#' . $target_id;
                    if (($this->edit_site_target = $this->edit_site->getTarget($target_id)) instanceof CNabuSiteTarget &&
                        ($this->edit_site_target->refresh(true, true)) &&
                        ($translation = $this->edit_site_target->getTranslation($this->nb_language)) !== false &&
                        (strlen($this->title_part[1] = $translation->getTitle()) === 0) &&
                        (strlen($this->title_part[1] = $this->edit_site_target->getKey()) === 0)
                    ) {
                        $this->title_part[1] = '#' . $target_id;
                    }
                    $this->breadcrumb_part['site_target_edit'] = array(
                        'title' => $this->title_part[1],
                        'slug' => $target_id
                    );

                    $this->edit_site_target->getSections();
                    $this->edit_site_target->getCTAs();
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
                $retval = $this->nb_site->getTargetByKey('site_list');
            }
        }

        return $retval;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        $render->smartyAssign('edit_site', $this->edit_site, $this->nb_language);
        $render->smartyAssign('edit_site_target', $this->edit_site_target, $this->nb_language);
        $render->smartyAssign('title_part', $this->title_part);
        $render->smartyAssign('breadcrumb_part', $this->breadcrumb_part);
        $render->smartyAssign('base_url', $this->base_url);

        if ($this->nb_site_target->getKey() === 'ajax_site_target_edit_policy') {
            $render->smartyAssign(
                'transform_interfaces',
                $this->nb_engine->getProvidersInterfaceDescriptors(CNabuProviderFactory::INTERFACE_RENDER_TRANSFORM),
                $this->nb_language
            );
            $render->smartyAssign(
                'used_mimetypes',
                $this->nb_site->getUsedMimeTypes()
            );
        }

        return true;
    }
}
