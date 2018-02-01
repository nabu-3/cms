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

namespace nabu\cms\plugins\sitetarget\securityeditor;
use nabu\data\lang\CNabuLanguage;
use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;
use nabu\provider\CNabuProviderFactory;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\securityeditor
 */
class CNabuCMSPluginRoleEdit extends CNabuHTTPSiteTargetPluginAdapter
{
    private $edit_role = null;
    private $title_part = null;
    private $breadcrumb_part = null;

    public function prepareTarget()
    {
        $this->edit_role = null;
        $this->title_part = null;
        $this->breadcrumb_part = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 2) {
            $id = $fragments[1];
            $this->title_part = '#' . $id;
            if (($this->edit_role = $this->nb_work_customer->getRole($id)) !== false) {
                $this->edit_role->refresh(true, true);
                if (($translation = $this->edit_role->getTranslation($this->nb_language)) !== false &&
                    (strlen($this->title_part = $translation->getName()) === 0) &&
                    (strlen($this->title_part = $this->edit_role->getKey()) === 0)
                ) {
                    $this->title_part = '#' . $id;
                }
            }
            $this->breadcrumb_part['role_edit'] = array(
                'title' => $this->title_part,
                'slug' => $id
            );
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        $render->smartyAssign('edit_role', $this->edit_role, $this->nb_language);
        $render->smartyAssign('title_part', $this->title_part);
        $render->smartyAssign('breadcrumb_part', $this->breadcrumb_part);
        $render->smartyAssign('nb_languages', CNabuLanguage::getAllLanguages(), $this->nb_language);

        return true;
    }
}
