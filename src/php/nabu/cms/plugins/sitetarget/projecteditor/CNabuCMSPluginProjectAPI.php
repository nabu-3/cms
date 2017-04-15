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

namespace nabu\cms\plugins\sitetarget\projecteditor;
use nabu\cms\plugins\sitetarget\base\CNabuCMSPluginAbstractAPI;
use nabu\data\project\CNabuProject;
use nabu\data\project\CNabuProjectLanguage;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\projecteditor
 */
class CNabuCMSPluginProjectAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuProject $nb_project Project instance */
    private $nb_project = null;

    public function prepareTarget()
    {
        if (count($fragments = $this->nb_request->getRegExprURLFragments()) === 2) {
            if (is_numeric($fragments[1])) {
                $this->nb_project = $this->nb_work_customer->getProject($fragments[1]);
            } elseif (!$fragments[1]) {
                $this->nb_project = new CNabuProject();
                $this->nb_project->setCustomer($this->nb_work_customer);
                $this->nb_project->setHash(nb_generateGUID());
            }
        }

        return true;
    }

    public function methodPOST()
    {
        if ($this->nb_project instanceof CNabuProject) {
            $this->nb_request->updateObjectFromPost(
                $this->nb_project,
                array(
                    'hash' => 'nb_project_hash',
                    'default_language_id' => 'nb_project_default_language_id',
                    'current_version_id' => 'nb_project_current_version_id',
                    'medioteca_id' => 'nb_medioteca_id',
                    'front_image_id' => 'nb_project_front_image_id'
                )
            );
            if ($this->nb_project->save()) {
                $languages = $this->nb_request->getCombinedPostIndexes(array('name'));
                if (count($languages) > 0) {
                    foreach ($languages as $lang_id) {
                        $nb_translation = $this->nb_project->getTranslation($lang_id);
                        if (!$nb_translation) {
                            $nb_translation = new CNabuProjectLanguage();
                            $nb_translation->setProjectId($this->nb_project->getId());
                            $nb_translation->setLanguageId($lang_id);
                            $this->nb_project->setTranslation($nb_translation);
                        }
                        $this->nb_request->updateObjectFromPost(
                            $nb_translation,
                            array(
                                'title' => 'nb_project_lang_title',
                                'subtitle' => 'nb_project_lang_subtitle',
                                'opening' => 'nb_project_lang_opening'
                            ),
                            null,
                            null,
                            $lang_id
                        );
                        $nb_translation->save(true);
                    }
                }
                $this->setStatusOK();
                $this->setData($this->nb_project->getTreeData(null, true));
            }
        }

        return true;
    }
}
