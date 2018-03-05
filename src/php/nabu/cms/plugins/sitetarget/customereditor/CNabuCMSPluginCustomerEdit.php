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

namespace nabu\cms\plugins\sitetarget\customereditor;
use nabu\data\lang\CNabuLanguage;
use nabu\data\customer\CNabuCustomer;
use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\customereditor
 */
class CNabuCMSPluginCustomerEdit extends CNabuHTTPSiteTargetPluginAdapter
{
    /** @var CNabuCustomer $nb_edit_customer Customer instance to be edited. */
    private $nb_edit_customer;
    /** @var string $title_part Title fragment to be added to the page header title. */
    private $title_part;
    /** @var array $breadcrumb_part Array of breadcrumb parts. */
    private $breadcrumb_part;

    public function prepareTarget()
    {
        $this->nb_edit_customer = null;
        $this->title_part = null;
        $this->breadcrumb_part = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) > 1) {
            $id = $fragments[1];
            $this->title_part = '#' . $id;
            if (($this->nb_edit_customer = new CNabuCustomer($id))->isFetched() &&
                $this->nb_edit_customer->refresh(true, true) &&
                (strlen($this->title_part = $this->nb_edit_customer->getFiscalName()) === 0)
            ) {
                $this->title_part = '#' . $id;
            }
            $this->breadcrumb_part['customer_edit'] = array(
                'title' => $this->title_part,
                'slug' => $id
            );
        }

        return ($this->nb_edit_customer instanceof CNabuCustomer)
               ? true
               : $this->nb_site->getTargetByKey('customer_list');
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        $render->smartyAssign('edit_customer', $this->nb_edit_customer);
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
