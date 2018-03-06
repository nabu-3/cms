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
use nabu\cms\plugins\sitetarget\base\CNabuCMSPluginAbstractAPI;
use nabu\data\customer\CNabuCustomer;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\customereditor
 */
class CNabuCMSPluginCustomerAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuCustomer $edit_customer Customer instance */
    private $edit_customer = null;

    public function prepareTarget()
    {
        if (count($fragments = $this->nb_request->getRegExprURLFragments()) === 2) {
            if (is_numeric($fragments[1])) {
                if ($fragments[1] == $this->nb_customer->getId()) {
                    $this->edit_customer = $this->nb_customer;
                } elseif ($this->nb_work_customer instanceof CNabuCustomer &&
                          $fragments[1] == $this->nb_work_customer->getId()
                ) {
                    $this->edit_customer = $this->nb_work_customer;
                } elseif (($this->edit_customer = new CNabuCustomer($fragments[1]))->isNew()) {
                    $this->edit_customer = null;
                }
                $this->edit_customer->refresh(true, false);
            } else {
                $this->edit_customer = new CNabuCustomer();
            }
        }

        return true;
    }

    public function methodPOST()
    {
        if ($this->edit_customer instanceof CNabuCustomer) {
            $this->nb_request->updateObjectFromPost(
                $this->edit_customer,
                array(
                    'fiscal_name' => 'nb_customer_fiscal_name'
                )
            );

            if ($this->edit_customer->save()) {
                $this->setStatusOK();
                $this->setData($this->edit_customer->getTreeData(null, true));
            }
        }

        return true;
    }
}
