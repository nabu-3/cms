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
use nabu\data\commerce\CNabuCommerce;
use nabu\data\lang\CNabuLanguage;
use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.1 Surface
 * @version 3.0.1 Surface
 * @package \nabu\cms\plugins\sitetarget\commerceeditor
 */
class CNabuCMSPluginCommerceEdit extends CNabuHTTPSiteTargetPluginAdapter
{
    /** @var CNabuCommerce Commerce instance to be edited. */
    private $edit_commerce = null;
    /** @var string Title part. */
    private $title_part = null;
    /** @var array Breadcrumb part. */
    private $breadcrumb_part = null;

    public function prepareTarget()
    {
        $this->edit_commerce = null;
        $this->title_part = null;
        $this->breadcrumb_part = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 2) {
            $id = $fragments[1];
            $this->title_part = '#' . $id;
            if (($this->edit_commerce = $this->nb_work_customer->getCommerce($id)) !== false) {
                $this->edit_commerce->refresh();
                if (($translation = $this->edit_commerce->getTranslation($this->nb_language)) !== false &&
                    (strlen($this->title_part = $translation->getName()) === 0) &&
                    (strlen($this->title_part = $this->edit_commerce->getKey()) === 0)
                ) {
                    $this->title_part = '#' . $id;
                }
            }
            $this->breadcrumb_part['commerce_edit'] = array(
                'title' => $this->title_part,
                'slug' => $id
            );
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        $render->smartyAssign('edit_commerce', $this->edit_commerce, $this->nb_language);
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
