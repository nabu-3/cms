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
use nabu\data\site\CNabuSiteTargetCTA;
use nabu\data\site\CNabuSiteTargetCTAList;
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
                    'hash' => 'nb_site_target_hash',
                    'order' => 'nb_site_target_order',
                    'url_filter' => 'nb_site_target_url_filter',
                    'key' => 'nb_site_target_key',
                    'zone' => 'nb_site_target_zone',
                    'use_http' => 'nb_site_target_use_http',
                    'use_https' => 'nb_site_target_use_https',
                    'output_type' => 'nb_site_target_output_type',
                    'mimetype_id' => 'nb_mimetype_id',
                    'smarty_display_file' => 'nb_site_target_smarty_display_file',
                    'smarty_content_file' => 'nb_site_target_smarty_content_file',
                    'smarty_debugging' => 'nb_site_target_smarty_debugging',
                    'plugin_name' => 'nb_site_target_plugin_name',
                    'php_trace' => 'nb_site_target_php_trace',
                    'ignore_policies' => 'nb_site_target_ignore_policies',
                    'use_commerce' => 'nb_site_target_use_commerce',
                    'use_apps' => 'nb_site_target_use_apps',
                    'dynamic_cache_control' => 'nb_site_target_dynamic_cache_control',
                    'dynamic_cache_max_age' => 'nb_site_target_dynamic_cache_max_age',
                    'script_file' => 'nb_site_target_script_file',
                    'css_file' => 'nb_site_target_css_file',
                    'css_class' => 'nb_site_target_css_class',
                    'commands' => 'nb_site_target_commands',
                    'meta_robots' => 'nb_site_target_meta_robots',
                    'icon' => 'nb_site_target_icon',
                    'apps_slot' => 'nb_site_target_apps_slot'
                )
            );

            if ($this->nb_request->hasPOSTField('attributes')) {
                $this->nb_site_target_edit->setAttributes($this->nb_request->getPOSTField('attributes'));
            }

            if ($this->nb_site_target_edit->save()) {
                $languages = $this->nb_request->getCombinedPostIndexes(array('title'));
                if (count($languages) > 0) {
                    foreach ($languages as $lang_id) {
                        $nb_translation = $this->nb_site_target_edit->getTranslation($lang_id);
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
                                'subtitle' => 'nb_site_target_lang_subtitle',
                                'opening' => 'nb_site_target_lang_opening',
                                'content' => 'nb_site_target_lang_content',
                                'footer' => 'nb_site_target_lang_footer',
                                'aside' => 'nb_site_target_lang_aside',
                                'main_image' => 'nb_site_target_lang_main_image',
                                'url' => 'nb_site_target_lang_url',
                                'url_rebuild' => 'nb_site_target_lang_url_rebuild',
                                'head_title' => 'nb_site_target_lang_head_title',
                                'meta_description' => 'nb_site_target_lang_meta_description',
                                'meta_keywords' => 'nb_site_target_lang_meta_keywords'
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
