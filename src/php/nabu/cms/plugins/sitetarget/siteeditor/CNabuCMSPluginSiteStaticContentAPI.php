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
use nabu\data\site\CNabuSiteStaticContent;
use nabu\data\site\CNabuSiteStaticContentLanguage;
use nabu\data\site\CNabuSiteTargetCTAList;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\siteeditor
 */
class CNabuCMSPluginSiteStaticContentAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuSite $nb_edit_site Site selected */
    private $nb_edit_site = null;
    /** @var CNabuSiteStaticContent $nb_edit_site_static_content Static Content selected */
    private $nb_edit_site_static_content = null;

    public function prepareTarget()
    {
        $this->nb_edit_site = null;
        $this->nb_edit_site_static_content = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 3) {
            $this->nb_edit_site = $this->nb_work_customer->getSite($fragments[1]);
            if ($this->nb_edit_site instanceof CNabuSite) {
                $this->nb_edit_site->refresh();
                if (is_numeric($fragments[2])) {
                    $this->nb_edit_site_static_content = $this->nb_edit_site->getStaticContent($fragments[2]);
                    $this->nb_edit_site_static_content->refresh();
                } elseif (!$fragments[2]) {
                    $this->nb_edit_site_static_content = new CNabuSiteStaticContent();
                    $this->nb_edit_site_static_content->setSite($this->nb_edit_site);
                    $this->nb_edit_site_static_content->grantHash(false);
                }
            }
        }

        return true;
    }

    public function methodGET()
    {
        if ($this->nb_edit_site_static_content instanceof CNabuSiteStaticContent) {
            $this->setStatusOK();
            $this->setData($this->nb_edit_site_static_content->getTreeData(null, true));
        }

        return true;
    }

    public function methodPOST()
    {
        if ($this->nb_request->hasGETField('action')) {
            $action = $this->nb_request->getGETField('action');
            switch (strtolower($action)) {
                default:
                    $this->setStatusError(sprintf('Action [%s] not supported', $action));
            }
        } else {
            $this->doPOSTSave();
        }

        return true;
    }

    private function doPOSTSave()
    {
        if ($this->nb_edit_site_static_content instanceof CNabuSiteStaticContent) {
            $this->nb_request->updateObjectFromPost(
                $this->nb_edit_site_static_content,
                array(
                    'key' => 'nb_site_static_content_key',
                    'hash' => 'nb_site_static_content_hash',
                    'type' => 'nb_site_static_content_type',
                    'use_alternative' => 'nb_site_static_content_use_alternative',
                    'notes' => 'nb_site_static_content_notes'
                )
            );
            $this->nb_edit_site_static_content->grantHash(false);
            if ($this->nb_edit_site_static_content->save()) {
                $languages = $this->nb_request->getCombinedPostIndexes(array('text'));
                if (count($languages) > 0) {
                    foreach ($languages as $lang_id) {
                        $nb_translation = $this->nb_edit_site_static_content->getTranslation($lang_id);
                        if (!$nb_translation) {
                            $nb_translation = new CNabuSiteStaticContentLanguage();
                            $nb_translation->setSiteStaticContentId($this->nb_edit_site_static_content->getId());
                            $nb_translation->setLanguageId($lang_id);
                            $this->nb_edit_site_static_content->setTranslation($nb_translation);
                        }
                        $this->nb_request->updateObjectFromPost(
                            $nb_translation,
                            array(
                                'text' => 'nb_site_static_content_lang_text'
                            ),
                            null,
                            null,
                            $lang_id
                        );
                        $nb_translation->save();
                    }
                }

                $this->setStatusOK();
                $this->setData($this->nb_edit_site_static_content->getTreeData(null, true));
            }
        }
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        if ($this->getStatus() === 'OK') {
            $api_mask = $this->nb_site_target->getFullyQualifiedURL($this->nb_language, true);
            $api_url = sprintf($api_mask, $this->nb_edit_site->getId(), $this->nb_edit_site_static_content->getId());
            $this->setAPICall($api_url);
            $editor_cta = $this->nb_site_target->getCTAs()->getItem('ajax_static_contents', CNabuSiteTargetCTAList::INDEX_KEY);
            $editor_cta->canonize();
            $urls = array();
            $editor_cta->getTranslations()->iterate(function ($key, $translation) use(&$urls) {
                $editor_mask = $translation->getFinalURL();
                $urls[$key] = sprintf($editor_mask, $this->nb_edit_site->getId(), $this->nb_edit_site_static_content->getId());
            });
            $this->setEditorCall($urls);
        }

        return parent::beforeDisplayTarget();
    }
}
