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
use nabu\cms\plugins\sitetarget\base\CNabuCMSPluginAbstractAPI;
use nabu\data\security\CNabuRole;
use nabu\data\security\CNabuRoleLanguage;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\securityeditor
 */
class CNabuCMSPluginRoleAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuRole $edit_role Role instance */
    private $edit_role = null;

    public function prepareTarget()
    {
        if (count($fragments = $this->nb_request->getRegExprURLFragments()) === 2) {
            if (is_numeric($fragments[1])) {
                if ($fragments[1] == $this->nb_role->getId()) {
                    $this->edit_role = $this->nb_role;
                } else {
                    $this->edit_role = $this->nb_work_customer->getRole($fragments[1]);
                }
                $this->edit_role->refresh(true, false);
            } elseif (!$fragments[1]) {
                $this->edit_role = new CNabuRole();
                $this->edit_role->setCustomer($this->nb_work_customer);
            }
        }

        return true;
    }

    public function methodPOST()
    {
        if ($this->edit_role instanceof CNabuRole) {
            $this->edit_role->grantHash();
            $this->nb_request->updateObjectFromPost(
                $this->edit_role,
                array(
                    'key' => 'nb_role_key',
                    'root' => 'nb_role_root'
                )
            );

            if ($this->edit_role->save()) {
                $languages = $this->nb_request->getCombinedPostIndexes(array('name'));
                if (count($languages) > 0) {
                    foreach ($languages as $lang_id) {
                        $nb_translation = $this->edit_role->getTranslation($lang_id);
                        if (!$nb_translation) {
                            $nb_translation = new CNabuRoleLanguage();
                            $nb_translation->setRoleId($this->edit_role->getId());
                            $nb_translation->setLanguageId($lang_id);
                            $this->edit_role->setTranslation($nb_translation);
                        }
                        $this->nb_request->updateObjectFromPost(
                            $nb_translation,
                            array(
                                'name' => 'nb_role_lang_name'
                            ),
                            null,
                            null,
                            $lang_id
                        );
                        $nb_translation->save();
                    }
                }

                $this->setStatusOK();
                $this->setData($this->edit_role->getTreeData(null, true));
            }
        }

        return true;
    }
}
