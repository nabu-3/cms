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
use nabu\cms\plugins\sitetarget\base\CNabuCMSPluginAbstractAPI;
use nabu\data\site\CNabuSite;
use nabu\data\site\CNabuSiteTarget;

class CNabuCMSPluginSiteTargetAPI extends CNabuCMSPluginAbstractAPI
{
    /**
     * Current Site to be edited
     * @var CNabuSite
     */
    private $nb_site_edit;
    /**
     * Current Site Target to be edited
     * @var CNabuSiteTarget
     */
    private $nb_site_target_edit;
    /**
     * Action status
     * @var bool
     */
    private $action_status;

    public function prepareTarget()
    {
        $this->nb_site_edit = null;
        $this->nb_site_target_edit = null;
        $this->action_status = false;

        if (is_array($fragments = $this->nb_request->getRegExprURLFragments()) && count($fragments) > 1) {
            $nb_site = $this->nb_work_customer->getSite($fragments[1]);
            if (count($fragments) > 2) {
                $nb_site_target = $nb_site->getTarget($fragments[2]);
                if ($nb_site_target !== null) {
                    $this->nb_site_edit = $nb_site;
                    $this->nb_site_target_edit = $nb_site_target;
                    $this->action_status = true;
                }
            } else {
                $this->nb_site_edit = $nb_site;
                $this->nb_site_target_edit = new CNabuSiteTarget();
                $this->nb_site_target_edit->setSite($nb_site);
                $this->action_status = true;
            }
        }

        return true;
    }

    public function methodPOST()
    {
        if ($this->action_status) {
            $this->nb_site_target_edit->relinkDB();
            $this->nb_request->updateObjectFromPost($this->nb_site_target_edit,
                array(
                    'mimetype_id' => 'nb_mimetype_id',
                    'order' => 'nb_site_target_order',
                    'output_type' => 'nb_site_target_output_type',
                    'url_filter' => 'nb_site_target_url_filter',
                    'zone' => 'nb_site_target_zone'
                )
            );
            if ($this->nb_site_target_edit->isNew()) {
                if ($this->action_status = $this->nb_site_target_edit->save(true)) {
                    $this->nb_site_edit->addTarget($this->nb_site_target);
                }
            } else {
                $this->action_status = $this->nb_site_target_edit->save();
            }

            if ($this->action_status) {
                $this->setStatusOK();
                $this->setData(array(
                    'id' => $this->nb_site_target_edit->getId()
                ));
                $url = sprintf(
                    $this->nb_site_target->getFullyQualifiedURL($this->nb_site->getApiLanguageId()),
                    $this->nb_site_edit->getId(),
                    $this->nb_site_target_edit->getId()
                );
                $this->setPage(1, 0, 0, $url);
            } else {
                $this->setStatusError('Error 500: Internal server error');
            }
        }

        return true;
    }
}
