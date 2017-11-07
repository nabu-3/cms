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
use nabu\data\customer\CNabuCustomer;
use nabu\data\site\CNabuSite;
use nabu\data\site\CNabuSiteTarget;
use nabu\data\site\CNabuSiteTargetCTA;
use nabu\data\site\CNabuSiteTargetCTAList;
use nabu\data\site\CNabuSiteTargetCTALanguage;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\siteeditor
 */
class CNabuCMSPluginSiteTargetCTAAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuSite $edit_site Current Site to be edited */
    private $edit_site;
    /** @var CNabuSiteTarget $edit_site_target Current Site Target to be edited */
    private $edit_site_target;
    /** @var CNabuSiteTargetCTA $edit_site_target_cta Site Target CTA instance to be edited. */
    private $edit_site_target_cta = null;
    /** @var bool $action_status Action status */
    private $action_status;

    public function prepareTarget()
    {
        $this->edit_site = null;
        $this->edit_site_target = null;
        $this->edit_site_target_cta = null;
        $this->action_status = false;

        if (is_array($fragments = $this->nb_request->getRegExprURLFragments()) &&
            count($fragments) === 4 &&
            is_numeric($site_id = $fragments[1]) &&
            is_numeric($target_id = $fragments[2]) &&
            $this->nb_work_customer instanceof CNabuCustomer &&
            $this->nb_work_customer->refresh(true, false) &&
            ($this->edit_site = $this->nb_work_customer->getSite($site_id)) instanceof CNabuSite &&
            $this->edit_site->refresh(true, true) &&
            ($this->edit_site_target = $this->edit_site->getTarget($target_id)) instanceof CNabuSiteTarget &&
            $this->edit_site_target->refresh(true, true)
        ) {
            if (is_numeric($cta_id = $fragments[3])) {
                $this->edit_site_target_cta = $this->edit_site_target->getCTA($cta_id);
            } else {
                $this->edit_site_target_cta = new CNabuSiteTargetCTA();
                $this->edit_site_target_cta->setSiteTarget($this->edit_site_target);
            }
        }

        return true;
    }

    public function methodGET()
    {
        if ($this->edit_site_target_cta instanceof CNabuSiteTargetCTA) {
            $this->setStatusOK();
            $this->setData($this->edit_site_target_cta->getTreeData(null, true));
        }

        return true;
    }

    public function methodPOST()
    {
        if ($this->edit_site_target_cta instanceof CNabuSiteTargetCTA) {
            $this->edit_site_target_cta->grantHash();
            $this->nb_request->updateObjectFromPost($this->edit_site_target_cta,
                array(
                    'hash' => 'nb_site_target_cta_hash',
                    'key' => 'nb_site_target_cta_key',
                    'target_use_uri' => 'nb_site_target_cta_target_use_uri',
                    'target_id' => 'nb_site_target_cta_target_id',
                    'order' => 'nb_site_target_cta_order',
                    'css_class' => 'nb_site_target_cta_css_class'
                )
            );

            if ($this->edit_site_target_cta->save()) {
                $languages = $this->nb_request->getCombinedPostIndexes(array('title', 'main_image'));
                if (count($languages) > 0) {
                    foreach ($languages as $lang_id) {
                        $nb_translation = $this->edit_site_target_cta->getTranslation($lang_id);
                        if (!$nb_translation) {
                            $nb_translation = new CNabuSiteTargetCTALanguage();
                            $nb_translation->setSiteTargetCTAId($this->edit_site_target_cta->getId());
                            $nb_translation->setLanguageId($lang_id);
                            $this->edit_site_target_cta->setTranslation($nb_translation);
                        }
                        $this->nb_request->updateObjectFromPost(
                            $nb_translation,
                            array(
                                'target_url' => 'nb_site_target_cta_lang_target_url',
                                'title' => 'nb_site_target_cta_lang_title',
                                'alternate' => 'nb_site_target_cta_lang_alternate',
                                'image' => 'nb_site_target_cta_lang_image',
                                'anchor_text' => 'nb_site_target_cta_lang_anchor_text'
                            ),
                            null,
                            null,
                            $lang_id
                        );
                        $nb_translation->save();
                    }
                }

                $this->setStatusOK();
                $this->setData($this->edit_site_target_cta->getTreeData(null, true));
            }
        }

        return true;
    }

    public function methodDELETE()
    {
        if ($this->nb_request->hasREQUESTField('ids')) {
            $ids = $this->nb_request->getREQUESTField('ids');
            $nb_site_target_cta_list = new CNabuSiteTargetCTAList();
            foreach (array_keys($ids) as $id) {
                $nb_site_target_cta = $this->edit_site_target->getCTA($id);
                if ($nb_site_target_cta instanceof CNabuSiteTargetCTA) {
                    $nb_site_target_cta_list->setItem($nb_site_target_cta);
                } else {
                    break;
                }
            }
            if (count($ids) === $nb_site_target_cta_list->getSize()) {
                $this->setStatusOK();
                $nb_site_target_cta_list->iterate(function($key, $nb_site_target_cta) {
                    $nb_site_target_cta->delete();
                    return true;
                });
                $this->setData(array(
                    'ids' => array_keys($ids)
                ));
            }
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        if ($this->getStatus() === 'OK') {
            $api_mask = $this->nb_site_target->getFullyQualifiedURL($this->nb_language, true);
            $api_url = sprintf(
                $api_mask, $this->edit_site->getId(),
                $this->edit_site_target->getId(),
                $this->edit_site_target_cta->getId()
            );
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
