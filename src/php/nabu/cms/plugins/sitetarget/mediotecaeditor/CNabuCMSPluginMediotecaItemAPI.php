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

namespace nabu\cms\plugins\sitetarget\mediotecaeditor;
use nabu\cms\plugins\sitetarget\base\CNabuCMSPluginAbstractAPI;
use nabu\data\medioteca\CNabuMedioteca;
use nabu\data\medioteca\CNabuMediotecaItem;
use nabu\data\medioteca\CNabuMediotecaItemLanguage;
use nabu\data\site\CNabuSiteTargetCTAList;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.1 Surface
 * @version 3.0.1 Surface
 * @package \nabu\cms\plugins\sitetarget\mediotecaeditor
 */
class CNabuCMSPluginMediotecaItemAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuMedioteca $nb_medioteca Medioteca selected */
    private $nb_medioteca = null;
    /** @var CNabuMediotecaItem $nb_medioteca_item Item selected */
    private $nb_medioteca_item = null;

    public function prepareTarget()
    {
        $this->nb_medioteca = null;
        $this->nb_medioteca_item = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 3) {
            $this->nb_medioteca = $this->nb_work_customer->getMedioteca($fragments[1]);
            if ($this->nb_medioteca instanceof CNabuMedioteca) {
                $this->nb_medioteca->refresh();
                if (is_numeric($fragments[2])) {
                    $this->nb_medioteca_item = $this->nb_medioteca->getItems()->getItem($fragments[2]);
                    $this->nb_medioteca_item->refresh();
                } elseif (!$fragments[2]) {
                    $this->nb_medioteca_item = new CNabuMediotecaItem();
                    $this->nb_medioteca_item->setMedioteca($this->nb_medioteca);
                    $this->nb_medioteca_item->setHash(nb_generateGUID());
                }
            }
        }

        return true;
    }

    public function methodGET()
    {
        if ($this->nb_medioteca_item instanceof CNabuMediotecaItem) {
            $this->setStatusOK();
            $this->setData($this->nb_medioteca_item->getTreeData(null, true));
        }

        return true;
    }

    public function methodPOST()
    {
        if ($this->nb_request->hasGETField('action')) {
            $action = $this->nb_request->getGETField('action');
            switch (strtolower($action)) {
                case 'test':
                    $this->doTest();
                    break;
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
        if ($this->nb_medioteca_item instanceof CNabuMediotecaItem) {
            $this->nb_request->updateObjectFromPost(
                $this->nb_medioteca_item,
                array(
                    'key' => 'nb_medioteca_item_key',
                    'hash' => 'nb_medioteca_item_hash',
                    'order' => 'nb_medioteca_item_order',
                    'visible' => 'nb_medioteca_item_visible',
                    'css_class' => 'nb_medioteca_item_css_class',
                    'icon' => 'nb_medioteca_item_icon'
                ),
                array(
                    'visible' => 'F'
                )
            );
            if ($this->nb_medioteca_item->isValueEmpty('nb_medioteca_item_hash')) {
                $this->nb_medioteca_item->setHash(nb_generateGUID());
            }
            if ($this->nb_medioteca_item->save()) {
                $languages = $this->nb_request->getCombinedPostIndexes(array('title'));
                if (count($languages) > 0) {
                    foreach ($languages as $lang_id) {
                        $nb_translation = $this->nb_medioteca_item->getTranslation($lang_id);
                        if (!$nb_translation) {
                            $nb_translation = new CNabuMediotecaItemLanguage();
                            $nb_translation->setMediotecaItemId($this->nb_medioteca_item->getId());
                            $nb_translation->setLanguageId($lang_id);
                            $this->nb_medioteca_item->setTranslation($nb_translation);
                        }
                        $this->nb_request->updateObjectFromPost(
                            $nb_translation,
                            array(
                                'title' => 'nb_medioteca_item_lang_title',
                                'subtitle' => 'nb_medioteca_item_lang_subtitle',
                                'opening' => 'nb_medioteca_item_lang_opening',
                                'content' => 'nb_medioteca_item_lang_content',
                                'footer' => 'nb_medioteca_item_lang_footer',
                                'mime_type' => 'nb_medioteca_item_lang_mime_type',
                                'url' => 'nb_medioteca_item_lang_url',
                                'html_object' => 'nb_medioteca_item_lang_html_object',
                                'public_path' => 'nb_medioteca_item_lang_public_path'
                            ),
                            null,
                            null,
                            $lang_id
                        );
                        $nb_translation->save();
                    }
                }

                $this->setStatusOK();
                $this->setData($this->nb_medioteca_item->getTreeData(null, true));
            }
        }
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        if ($this->getStatus() === 'OK') {
            $api_mask = $this->nb_site_target->getFullyQualifiedURL($this->nb_language, true);
            $api_url = sprintf($api_mask, $this->nb_medioteca->getId(), $this->nb_medioteca_item->getId());
            $this->setAPICall($api_url);
            $editor_cta = $this->nb_site_target->getCTAs()->getItem('ajax_items', CNabuSiteTargetCTAList::INDEX_KEY);
            $editor_cta->canonize();
            $urls = array();
            $editor_cta->getTranslations()->iterate(function ($key, $translation) use(&$urls) {
                $editor_mask = $translation->getFinalURL();
                $urls[$key] = sprintf($editor_mask, $this->nb_medioteca->getId(), $this->nb_medioteca_item->getId());
            });
            $this->setEditorCall($urls);
        }

        return parent::beforeDisplayTarget();
    }
}
