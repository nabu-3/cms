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
use nabu\data\site\CNabuSiteTargetLanguage;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.1 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\siteeditor
 */
class CNabuCMSPluginSiteTargetAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuSite $nb_site_edit Current Site to be edited */
    private $nb_site_edit;
    /** @var CNabuSiteTarget $nb_site_target_edit Current Site Target to be edited */
    private $nb_site_target_edit;
    /** @var bool $action_status Action status */
    private $action_status;

    public function prepareTarget()
    {
        $this->nb_site_edit = null;
        $this->nb_site_target_edit = null;
        $this->action_status = false;

        if (is_array($fragments = $this->nb_request->getRegExprURLFragments()) && count($fragments) === 3) {
            $this->nb_site_edit = $this->nb_work_customer->getSite($fragments[1]);
            if ($this->nb_site_edit instanceof CNabuSite) {
                $this->nb_site_edit->refresh();
                if (is_numeric($fragments[2])) {
                    $this->nb_site_target_edit = $this->nb_site_edit->getTarget($fragments[2]);
                    $this->nb_site_target_edit->refresh();
                } elseif (!$fragments[2]) {
                    $this->nb_site_target_edit = new CNabuSiteTarget();
                    $this->nb_site_target_edit->setSite($this->nb_site_edit);
                    $this->nb_site_target_edit->grantHash();
                }
            }
        }

        return true;
    }

    public function methodGET()
    {
        if ($this->nb_site_target_edit instanceof CNabuSiteTarget) {
            $this->setStatusOK();
            $this->setData($this->nb_site_target_edit->getTreeData(null, true));
        }

        return true;
    }

    public function methodPOST()
    {
        if ($this->nb_site_target_edit instanceof CNabuSiteTarget) {
            $this->nb_request->updateObjectFromPost($this->nb_site_target_edit,
                array(
                    'mimetype_id' => 'nb_mimetype_id',
                    'order' => 'nb_site_target_order',
                    'output_type' => 'nb_site_target_output_type',
                    'url_filter' => 'nb_site_target_url_filter',
                    'zone' => 'nb_site_target_zone'
                )
            );
        }

        if ($this->nb_site_target_edit->save()) {
            $languages = $this->nb_request->getCombinedPostIndexes(array('title'));
            if (count($languages) > 0) {
                foreach ($languages as $lang_id) {
                    $nb_translation = $this->nb_site_target->getTranslation($lang_id);
                    if (!$nb_translation) {
                        $nb_translation = new CNabuSiteTargetLanguage();
                        $nb_translation->setSiteTargetId($this->nb_site_target_edit->getId());
                        $nb_translation->setLanguageId($lang_id);
                        $this->nb_site_target_edit->setTranslation($nb_translation);
                    }
                    $this->nb_request->updateObjectFromPost(
                        $nb_translation,
                        array(
                            'title' => 'nb_site_target_lang_title',
                            'url' => 'nb_site_target_lang_url'
                        ),
                        null,
                        null,
                        $lang_id
                    );
                    $nb_translation->save();
                }
            }

            $this->setStatusOK();
            $this->setData($this->nb_site_target_edit->getTreeData(null, true));
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        if ($this->getStatus() === 'OK') {
            $api_mask = $this->nb_site_target->getFullyQualifiedURL($this->nb_language, true);
            $api_url = sprintf($api_mask, $this->nb_site_edit->getId(), $this->nb_site_target_edit->getId());
            $this->setAPICall($api_url);
            $editor_cta = $this->nb_site_target->getCTAs()->getItem('ajax_items', CNabuSiteTargetCTAList::INDEX_KEY);
            if ($editor_cta !== null) {
                $editor_cta->canonize();
                $urls = array();
                $editor_cta->getTranslations()->iterate(function ($key, $translation) use(&$urls) {
                    $editor_mask = $translation->getFinalURL();
                    $urls[$key] = sprintf($editor_mask, $this->nb_medioteca->getId(), $this->nb_medioteca_item->getId());
                });
                $this->setEditorCall($urls);
            }
        }

        return parent::beforeDisplayTarget();
    }

}
