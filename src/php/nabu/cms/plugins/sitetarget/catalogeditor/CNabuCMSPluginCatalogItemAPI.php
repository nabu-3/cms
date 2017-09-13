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

namespace nabu\cms\plugins\sitetarget\catalogeditor;
use nabu\cms\plugins\sitetarget\base\CNabuCMSPluginAbstractAPI;
use nabu\core\exceptions\ENabuCoreException;
use nabu\data\catalog\CNabuCatalog;
use nabu\data\catalog\CNabuCatalogItem;
use nabu\data\catalog\CNabuCatalogItemLanguage;
use nabu\data\catalog\exceptions\ENabuCatalogException;
use nabu\data\site\CNabuSiteTargetCTAList;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\catalogeditor
 */
class CNabuCMSPluginCatalogItemAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuCatalog $nb_catalog Catalog selected */
    private $nb_catalog = null;
    /** @var CNabuCatalogItem $nb_catalog_item Item selected */
    private $nb_catalog_item = null;

    public function prepareTarget()
    {
        $this->nb_catalog = null;
        $this->nb_catalog_item = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 3) {
            $this->nb_catalog = $this->nb_work_customer->getCatalog($fragments[1]);
            if ($this->nb_catalog instanceof CNabuCatalog) {
                $this->nb_catalog->refresh(true, true);
                if (is_numeric($fragments[2])) {
                    $this->nb_catalog_item = $this->nb_catalog->getItems()->getItem($fragments[2]);
                    $this->nb_catalog_item->refresh(true, true);
                } elseif (!$fragments[2]) {
                    $this->nb_catalog_item = new CNabuCatalogItem();
                    $this->nb_catalog_item->setCatalog($this->nb_catalog);
                    $this->nb_catalog_item->grantHash();
                }
            }
        }

        return true;
    }

    /**
     * Process all GET calls.
     * @return bool Returns true if all is done.
     */
    public function methodGET()
    {
        if ($this->nb_catalog_item instanceof CNabuCatalogItem) {
            $this->setStatusOK();
            $this->setData($this->nb_catalog_item->getTreeData(null, true));
        }

        return true;
    }

    /**
     * Process all POST calls.
     * @return bool Returns true if all is done.
     */
    public function methodPOST()
    {
        if ($this->nb_request->hasGETField('action')) {
            $action = $this->nb_request->getGETField('action');
            switch (strtolower($action)) {
                case 'move':
                    $this->doMove();
                    break;
                case 'append':
                    $this->doAppend();
                    break;
                default:
                    $this->setStatusError(sprintf('Action [%s] not supported', $action));
            }
        } else {
            $this->doPOSTSave();
        }

        return true;
    }

    /**
     * Save data in the database storage.
     * @return bool Returns true if all is done.
     */
    public function doPOSTSave()
    {
        if ($this->nb_catalog_item instanceof CNabuCatalogItem) {
            $this->nb_request->updateObjectFromPost(
                $this->nb_catalog_item,
                array(
                    'key' => 'nb_catalog_item_key',
                    'hash' => 'nb_catalog_item_hash',
                    'catalog_taxonomy_id' => 'nb_catalog_taxonomy_id'
                )
            );
            $this->nb_catalog_item->grantHash();
            if ($this->nb_request->hasPOSTField('attrs')) {
                $this->nb_catalog_item->setAttributes($this->nb_request->getPOSTField('attrs'));
            }
            if ($this->nb_catalog_item->save()) {
                $languages = $this->nb_request->getCombinedPostIndexes(array('title'));
                if ($this->nb_request->hasPOSTField('lang_attrs')) {
                    $lang_attrs = $this->nb_request->getPOSTField('lang_attrs');
                } else {
                    $lang_attrs = array();
                }

                if (count($languages) > 0) {
                    foreach ($languages as $lang_id) {
                        $nb_translation = $this->nb_catalog_item->getTranslation($lang_id);
                        if (!$nb_translation) {
                            $nb_translation = new CNabuCatalogItemLanguage();
                            $nb_translation->setCatalogItemId($this->nb_catalog_item->getId());
                            $nb_translation->setLanguageId($lang_id);
                            $this->nb_catalog_item->setTranslation($nb_translation);
                        }
                        $this->nb_request->updateObjectFromPost(
                            $nb_translation,
                            array(
                                'sku' => 'nb_catalog_item_lang_sku',
                                'slug' => 'nb_catalog_item_lang_slug',
                                'image' => 'nb_catalog_item_lang_image',
                                'title' => 'nb_catalog_item_lang_title',
                                'subtitle' => 'nb_catalog_item_lang_subtitle',
                                'anchor_text' => 'nb_catalog_item_lang_anchor_text',
                                'opening' => 'nb_catalog_item_lang_opening',
                                'content' => 'nb_catalog_item_lang_content',
                                'footer' => 'nb_catalog_item_lang_footer',
                                'aside' => 'nb_catalog_item_lang_aside'
                            ),
                            null,
                            null,
                            $lang_id
                        );
                        if (array_key_exists($lang_id, $lang_attrs)) {
                            $nb_translation->setAttributes($lang_attrs[$lang_id]);
                        }
                        $nb_translation->save(true);
                    }
                }

                $this->setStatusOK();
                $this->setData($this->nb_catalog_item->getTreeData(null, true));
            }
        }

        return true;
    }

    /**
     * Performs a movement inside the Catalog Items Tree to change the position of an item respect to other.
     */
    private function doMove()
    {
        if ($this->nb_request->hasGETField('before')) {
            $before_id = $this->nb_request->getGETField('before');
            if (is_numeric($before_id)) {
                try {
                    if ($this->nb_catalog_item->moveBefore($before_id)) {
                        $this->nb_catalog_item->refresh(true);
                    }
                    $this->setStatusOK();
                    $this->setData($this->nb_catalog_item->getTreeData(null, true));
                } catch (ENabuCoreException $ex) {
                    if ($ex->getCode() === ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE) {
                        $this->setStatusError('Invalid [before] node');
                    } else {
                        throw $ex;
                    }
                } catch (ENabuCatalogException $ex) {
                    if ($ex->getCode() === ENabuCatalogException::ERROR_ITEM_NOT_INCLUDED_IN_CATALOG) {
                        $this->setStatusError('Invalid [before] node');
                    } else {
                        throw $ex;
                    }
                }
            } else {
                $this->setStatusError('Invalid [before] node');
            }
        } elseif ($this->nb_request->hasGETField('after')) {
            $after_id = $this->nb_request->getGETField('after');
            if (is_numeric($after_id)) {
                try {
                    if ($this->nb_catalog_item->moveAfter($after_id)) {
                        $this->nb_catalog_item->refresh(true);
                    }
                    $this->setStatusOK();
                    $this->setData($this->nb_catalog_item->getTreeData(null, true));
                } catch (ENabuCoreException $ex) {
                    if ($ex->getCode() === ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE) {
                        $this->setStatusError('Invalid [after] node');
                    } else {
                        throw $ex;
                    }
                } catch (ENabuCatalogException $ex) {
                    if ($ex->getCode() === ENabuCatalogException::ERROR_ITEM_NOT_INCLUDED_IN_CATALOG) {
                        $this->setStatusError('Invalid [after] node');
                    } else {
                        throw $ex;
                    }
                }
            } else {
                $this->setStatusError('Invalid [after] node');
            }
        }
    }

    /**
     * Appends an existent element (move to a different branch) in the Catalog Items Tree.
     */
    private function doAppend()
    {
        if ($this->nb_request->hasGETField('parent')) {
            $parent_id = $this->nb_request->getGETField('parent');
            if (is_numeric($parent_id)) {
                try {
                    if ($this->nb_catalog_item->moveInto($parent_id)) {
                        $this->nb_catalog_item->refresh(true);
                    }
                    $this->setStatusOK();
                    $this->setData($this->nb_catalog_item->getTreeData(null, true));
                } catch (ENabuCoreException $ex) {
                    if ($ex->getCode() === ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE) {
                        $this->setStatusError('Invalid [parent] node');
                    } else {
                        throw $ex;
                    }
                } catch (ENabuCatalogException $ex) {
                    if ($ex->getCode() === ENabuCatalogException::ERROR_ITEM_NOT_INCLUDED_IN_CATALOG) {
                        $this->setStatusError('Invalid [parent] node');
                    } else {
                        throw $ex;
                    }
                }
            } else {
                $this->setStatusError('Invalid [parent] node');
            }
        }
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        if ($this->getStatus() === 'OK') {
            $api_mask = $this->nb_site_target->getFullyQualifiedURL($this->nb_language, true);
            $api_url = sprintf($api_mask, $this->nb_catalog->getId(), $this->nb_catalog_item->getId());
            $this->setAPICall($api_url);
            $editor_cta = $this->nb_site_target->getCTAs()->getItem('ajax_items', CNabuSiteTargetCTAList::INDEX_KEY);
            $editor_cta->canonize();
            $urls = array();
            $editor_cta->getTranslations()->iterate(function ($key, $translation) use(&$urls) {
                $editor_mask = $translation->getFinalURL();
                $urls[$key] = sprintf($editor_mask, $this->nb_catalog->getId(), $this->nb_catalog_item->getId());
            });
            $this->setEditorCall($urls);
        }

        return parent::beforeDisplayTarget();
    }
}
