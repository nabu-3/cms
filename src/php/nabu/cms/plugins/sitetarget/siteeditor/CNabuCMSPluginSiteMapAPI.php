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
use nabu\cms\plugins\sitetarget\base\CNabuCMSPluginAbstractAPI;
use nabu\data\site\CNabuSite;
use nabu\data\site\CNabuSiteMap;
use nabu\data\site\CNabuSiteTarget;
use nabu\data\site\CNabuSiteTargetCTA;
use nabu\data\site\CNabuSiteMapLanguage;
use nabu\data\site\CNabuSiteTargetCTAList;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.1 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\siteeditor
 */
class CNabuCMSPluginSiteMapAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuSite $nb_site_edit Current Site to be edited */
    private $nb_site_edit;
    /** @var CNabuSiteTarget $nb_site_map_edit Current Site Target to be edited */
    private $nb_site_map_edit;
    /** @var bool $action_status Action status */
    private $action_status;

    public function prepareTarget()
    {
        $this->nb_site_edit = null;
        $this->nb_site_map_edit = null;
        $this->action_status = false;

        if (is_array($fragments = $this->nb_request->getRegExprURLFragments()) && count($fragments) === 3) {
            $this->nb_site_edit = $this->nb_work_customer->getSite($fragments[1]);
            if ($this->nb_site_edit instanceof CNabuSite) {
                $this->nb_site_edit->refresh();
                if (is_numeric($fragments[2])) {
                    $this->nb_site_map_edit = $this->nb_site_edit->getSiteMap($fragments[2]);
                    $this->nb_site_map_edit->refresh(true, true);
                } elseif (!$fragments[2]) {
                    $this->nb_site_map_edit = new CNabuSiteMap();
                    $this->nb_site_map_edit->setSite($this->nb_site_edit);
                    $this->nb_site_map_edit->grantHash();
                }
            }
        }

        return true;
    }

    public function methodGET()
    {
        if ($this->nb_site_map_edit instanceof CNabuSiteMap) {
            $this->setStatusOK();
            $this->setData($this->nb_site_map_edit->getTreeData(null, true));
        }

        return true;
    }

    public function methodPOST()
    {
        if ($this->nb_site_map_edit instanceof CNabuSiteMap) {
            $this->nb_request->updateObjectFromPost($this->nb_site_map_edit,
                array(
                    'hash' => 'nb_site_map_hash',
                    'parent_id' => 'nb_site_map_parent_id',
                    'order' => 'nb_site_map_order',
                    'customer_required' => 'nb_site_map_customer_required',
                    'site_target_id' => 'nb_site_target_id',
                    'use_uri' => 'nb_site_target_use_uri',
                    'open_popup' => 'nb_site_target_open_popup',
                    'key' => 'nb_site_map_key',
                    'visible' => 'nb_site_map_visible',
                    'separator' => 'nb_site_map_separator',
                    'icon' => 'nb_site_map_icon',
                    'css_class' => 'nb_site_map_css_class'
                )
            );

            if ($this->nb_site_map_edit->save()) {
                $languages = $this->nb_request->getCombinedPostIndexes(array('title', 'subtitle', 'url', 'content'));
                if (count($languages) > 0) {
                    foreach ($languages as $lang_id) {
                        $nb_translation = $this->nb_site_map_edit->getTranslation($lang_id);
                        if (!$nb_translation) {
                            $nb_translation = new CNabuSiteMapLanguage();
                            $nb_translation->setSiteMapId($this->nb_site_map_edit->getId());
                            $nb_translation->setLanguageId($lang_id);
                            $this->nb_site_map_edit->setTranslation($nb_translation);
                        }
                        $this->nb_request->updateObjectFromPost(
                            $nb_translation,
                            array(
                                'translation_status' => 'nb_site_map_lang_translation_status',
                                'title' => 'nb_site_map_lang_title',
                                'subtitle' => 'nb_site_map_lang_subtitle',
                                'content' => 'nb_site_map_lang_content',
                                'url' => 'nb_site_map_lang_url',
                                'image' => 'nb_site_map_lang_image',
                                'match_url_fragment' => 'nb_site_map_lang_match_url_fragment'
                            ),
                            null,
                            null,
                            $lang_id
                        );
                        $nb_translation->save();
                    }
                }

                $this->setStatusOK();
                $this->setData($this->nb_site_map_edit->getTreeData(null, true));
            }
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        if ($this->getStatus() === 'OK') {
            $api_mask = $this->nb_site_target->getFullyQualifiedURL($this->nb_language, true);
            $api_url = sprintf($api_mask, $this->nb_site_edit->getId(), $this->nb_site_map_edit->getId());
            $this->setAPICall($api_url);
            $editor_cta = $this->nb_site_target->getCTAs()->getItem('ajax_items', CNabuSiteTargetCTAList::INDEX_KEY);
            if ($editor_cta instanceof CNabuSiteTargetCTA) {
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
