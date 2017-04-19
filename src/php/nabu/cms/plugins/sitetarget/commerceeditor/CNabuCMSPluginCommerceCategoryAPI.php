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

namespace nabu\cms\plugins\sitetarget\commerceeditor;
use nabu\cms\plugins\sitetarget\base\CNabuCMSPluginAbstractAPI;
use nabu\data\commerce\CNabuCommerce;
use nabu\data\commerce\CNabuCommerceProductCategory;
use nabu\data\commerce\CNabuCommerceProductCategoryLanguage;
use nabu\data\site\CNabuSiteTargetCTAList;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\commerceeditor
 */
class CNabuCMSPluginCommerceCategoryAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuCommerce $edit_commerce Commerce selected */
    private $edit_commerce = null;
    /** @var CNabuCommerceProductCategory $edit_commerce_product_category Product Category selected */
    private $edit_commerce_product_category = null;

    public function prepareTarget()
    {
        $this->edit_commerce = null;
        $this->edit_commerce_product_category = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 3) {
            $this->edit_commerce = $this->nb_work_customer->getCommerce($fragments[1]);
            if ($this->edit_commerce instanceof CNabuCommerce) {
                $this->edit_commerce->refresh();
                if (is_numeric($fragments[2])) {
                    $this->edit_commerce_product_category =
                        $this->edit_commerce->getProductCategory($fragments[2]);
                    $this->edit_commerce_product_category->refresh();
                } elseif (!$fragments[2]) {
                    $this->edit_commerce_product_category = new CNabuCommerceProductCategory();
                    $this->edit_commerce_product_category->setCommerce($this->edit_commerce);
                    $this->edit_commerce_product_category->setHash(nb_generateGUID());
                }
            }
        }

        return true;
    }

    public function methodGET()
    {
        if ($this->edit_commerce_product_category instanceof CNabuCommerceProductCategory) {
            $this->setStatusOK();
            $this->setData($this->edit_commerce_product_category->getTreeData(null, true));
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
        if ($this->edit_commerce_product_category instanceof CNabuCommerceProductCategory) {
            $this->nb_request->updateObjectFromPost(
                $this->edit_commerce_product_category,
                array(
                    'hash' => 'nb_commerce_product_category_hash',
                    'order' => 'nb_commerce_product_category_order',
                    'key' => 'nb_commerce_product_category_key',
                    'attrs' => 'nb_commerce_product_category_attributes'
                )
            );
            $this->edit_commerce_product_category->grantHash(false);
            if ($this->edit_commerce_product_category->save()) {
                $languages = $this->nb_request->getCombinedPostIndexes(array('title'));
                if (count($languages) > 0) {
                    foreach ($languages as $lang_id) {
                        $nb_translation = $this->edit_commerce_product_category->getTranslation($lang_id);
                        if (!$nb_translation) {
                            $nb_translation = new CNabuCommerceProductCategoryLanguage();
                            $nb_translation->setCommerceProductCategoryId($this->edit_commerce_product_category->getId());
                            $nb_translation->setLanguageId($lang_id);
                            $this->edit_commerce_product_category->setTranslation($nb_translation);
                        }
                        $this->nb_request->updateObjectFromPost(
                            $nb_translation,
                            array(
                                'title' => 'nb_commerce_product_category_lang_title',
                                'slug' => 'nb_commerce_product_category_lang_slug',
                                'attrs_lang' => 'nb_commerce_product_category_lang_attributes'
                            ),
                            null,
                            null,
                            $lang_id
                        );
                        $nb_translation->save();
                    }
                }

                $this->setStatusOK();
                $this->setData($this->edit_commerce_product_category->getTreeData(null, true));
            }
        }
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        if ($this->getStatus() === 'OK') {
            $api_mask = $this->nb_site_target->getFullyQualifiedURL($this->nb_language, true);
            $api_url = sprintf($api_mask, $this->edit_commerce->getId(), $this->edit_commerce_product_category->getId());
            $this->setAPICall($api_url);
            $editor_cta = $this->nb_site_target->getCTAs()->getItem('ajax_category', CNabuSiteTargetCTAList::INDEX_KEY);
            $editor_cta->canonize();
            $urls = array();
            $editor_cta->getTranslations()->iterate(function ($key, $translation) use(&$urls) {
                $editor_mask = $translation->getFinalURL();
                $urls[$key] = sprintf($editor_mask, $this->edit_commerce->getId(), $this->edit_commerce_product_category->getId());
            });
            $this->setEditorCall($urls);
        }

        return parent::beforeDisplayTarget();
    }
}
