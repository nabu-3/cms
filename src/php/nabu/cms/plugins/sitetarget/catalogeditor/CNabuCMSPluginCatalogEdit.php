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
use nabu\data\catalog\CNabuCatalog;
use nabu\data\lang\CNabuLanguage;
use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\cms\plugins\sitetarget
 */
class CNabuCMSPluginCatalogEdit extends CNabuHTTPSiteTargetPluginAdapter
{
    /** @var CNabuCatalog $edit_catalog Catalog instance to be edited. */
    private $edit_catalog;
    /** @var string $title_part Title fragment to be added to the page header title. */
    private $title_part;
    /** @var array $breadcrumb_part Array of breadcrumb parts. */
    private $breadcrumb_part;

    public function prepareTarget()
    {
        $this->edit_catalog = null;
        $this->title_part = null;
        $this->breadcrumb_part = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) > 1) {
            $id = $fragments[1];
            $this->title_part = '#' . $id;
            if (($this->edit_catalog = $this->nb_work_customer->getCatalog($id)) !== false) {
                $this->edit_catalog->refresh(true, true);
                if (($translation = $this->edit_catalog->getTranslation($this->nb_language)) !== false &&
                    (strlen($this->title_part = $translation->getTitle()) === 0) &&
                    (strlen($this->title_part = $this->edit_catalog->getKey()) === 0)
                ) {
                    $this->title_part = '#' . $id;
                }
            }

            if ($this->edit_catalog) {
                $this->edit_catalog->populate(0);
                $this->edit_catalog->getTaxonomies();
            }

            $this->breadcrumb_part['catalog_edit'] = array(
                'title' => $this->title_part,
                'slug' => $id
            );
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        $render->smartyAssign('nb_languages', $this->edit_catalog->getLanguages());
        $render->smartyAssign('edit_catalog', $this->edit_catalog, $this->nb_language);
        $render->smartyAssign('title_part', $this->title_part);
        $render->smartyAssign('breadcrumb_part', $this->breadcrumb_part);

        $render->smartyAssign(
            'nb_all_languages',
            CNabuLanguage::getNaturalLanguages(),
            $this->nb_language
        );

        return true;
    }
}
