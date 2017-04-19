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
use nabu\data\commerce\CNabuCommerceProductCategory;
use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;
use providers\smarty\smarty\renders\CSmartyHTTPRender;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\commerceeditor
 */
class CNabuCMSPluginCommerceCategoryAjax extends CNabuHTTPSiteTargetPluginAdapter
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
        if (is_array($fragments) && count($fragments) === 3 && is_numeric($fragments[1])) {
            $this->edit_commerce = $this->nb_work_customer->getCommerce($fragments[1]);
            if ($this->edit_commerce instanceof CNabuCommerce) {
                $this->edit_commerce->refresh();
                if (is_numeric($fragments[2])) {
                    $this->edit_commerce_product_category =
                        $this->edit_commerce->getProductCategory($fragments[2]);
                }
            }
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();

        if ($render instanceof CSmartyHTTPRender) {
            $render->smartyAssign('edit_commerce', $this->edit_commerce, $this->nb_language);
            $render->smartyAssign('edit_category', $this->edit_commerce_product_category, $this->nb_language);
            $render->smartyAssign('nb_all_languages', $this->edit_commerce->getLanguages());
        }

        return true;
    }
}
