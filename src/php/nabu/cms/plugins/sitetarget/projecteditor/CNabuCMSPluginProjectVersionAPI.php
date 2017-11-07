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

namespace nabu\cms\plugins\sitetarget\projecteditor;
use nabu\cms\plugins\sitetarget\base\CNabuCMSPluginAbstractAPI;
use nabu\data\project\CNabuProject;
use nabu\data\project\CNabuProjectVersion;
use nabu\data\project\CNabuProjectVersionLanguage;
use nabu\data\site\CNabuSiteTargetCTAList;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\projecteditor
 */
class CNabuCMSPluginProjectVersionAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuProject $nb_project Project selected */
    private $nb_project = null;
    /** @var CNabuProjectVersion $nb_project_version Version selected */
    private $nb_project_version = null;

    public function prepareTarget()
    {
        $this->nb_project = null;
        $this->nb_project_version = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 3) {
            $this->nb_project = $this->nb_work_customer->getProject($fragments[1]);
            if ($this->nb_project instanceof CNabuProject) {
                $this->nb_project->refresh();
                if (is_numeric($fragments[2])) {
                    $this->nb_project_version = $this->nb_project->getVersions()->getItem($fragments[2]);
                    $this->nb_project_version->refresh();
                } elseif (!$fragments[2]) {
                    $this->nb_project_version = new CNabuProjectVersion();
                    $this->nb_project_version->setProject($this->nb_project);
                    $this->nb_project_version->setHash(nb_generateGUID());
                }
            }
        }

        return true;
    }

    public function methodGET()
    {
        if ($this->nb_project_version instanceof CNabuProjectVersion) {
            $this->setStatusOK();
            $this->setData($this->nb_project_version->getTreeData(null, true));
        }

        return true;
    }

    public function methodPOST()
    {
        if ($this->nb_project_version instanceof CNabuProjectVersion) {
            $this->nb_request->updateObjectFromPost(
                $this->nb_project_version,
                array(
                    'code' => 'nb_project_version_code',
                    'hash' => 'nb_project_version_hash'
                )
            );
            if ($this->nb_project_version->isValueEmpty('nb_project_version_hash')) {
                $this->nb_project_version->setHash(nb_generateGUID());
            }
            if ($this->nb_project_version->save()) {
                $languages = $this->nb_request->getCombinedPostIndexes(array('name'));
                if (count($languages) > 0) {
                    foreach ($languages as $lang_id) {
                        $nb_translation = $this->nb_project_version->getTranslation($lang_id);
                        if (!$nb_translation) {
                            $nb_translation = new CNabuProjectVersionLanguage();
                            $nb_translation->setProjectVersionId($this->nb_project_version->getId());
                            $nb_translation->setLanguageId($lang_id);
                            $this->nb_project_version->setTranslation($nb_translation);
                        }
                        $this->nb_request->updateObjectFromPost(
                            $nb_translation,
                            array(
                                'name' => 'nb_project_version_lang_name',
                                'description' => 'nb_project_version_lang_description'
                            ),
                            null,
                            null,
                            $lang_id
                        );
                        $nb_translation->save(true);
                    }
                }

                $this->setStatusOK();
                $this->setData($this->nb_project_version->getTreeData(null, true));
            }
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        if ($this->getStatus() === 'OK') {
            /*
            $api_mask = $this->nb_site_target->getFullyQualifiedURL($this->nb_language, true);
            $api_url = sprintf($api_mask, $this->nb_project->getId(), $this->nb_project_version->getId());
            $this->setAPICall($api_url);
            $editor_cta = $this->nb_site_target->getCTAs()->getItem('ajax_versions', CNabuSiteTargetCTAList::INDEX_KEY);
            $editor_cta->canonize();
            $urls = array();
            $editor_cta->getTranslations()->iterate(function ($key, $translation) use(&$urls) {
                $editor_mask = $translation->getFinalURL();
                $urls[$key] = sprintf($editor_mask, $this->nb_project->getId(), $this->nb_project_version->getId());
            });
            $this->setEditorCall($urls);
            */
        }

        return parent::beforeDisplayTarget();
    }
}
