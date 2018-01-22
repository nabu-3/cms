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

namespace nabu\cms\plugins\sitetarget\securityeditor;
use nabu\cms\plugins\sitetarget\base\CNabuCMSPluginAbstractAPI;
use nabu\data\security\CNabuUser;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\securityeditor
 */
class CNabuCMSPluginUserAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuUser $edit_user User instance */
    private $edit_user = null;

    public function prepareTarget()
    {
        if (count($fragments = $this->nb_request->getRegExprURLFragments()) === 2) {
            if (is_numeric($fragments[1])) {
                if ($fragments[1] == $this->nb_user->getId()) {
                    $this->edit_user = $this->nb_user;
                } else {
                    $this->edit_user = $this->nb_work_customer->getUser($fragments[1]);
                }
                $this->edit_user->refresh(true, false);
            } elseif (!$fragments[1]) {
                $this->edit_user = new CNabuUser();
                $this->edit_user->setCustomer($this->nb_work_customer);
            }
        }

        return true;
    }

    public function methodPOST()
    {
        if ($this->edit_user instanceof CNabuUser) {
            $this->nb_request->updateObjectFromPost(
                $this->edit_user,
                array(
                    'prescriber_id' => 'nb_user_prescriber_id',
                    'medioteca_id' => 'nb_user_medioteca_id',
                    'medioteca_item_id' => 'nb_user_medioteca_item_id',
                    'search_visibility' => 'nb_user_search_visibility',
                    'login' => 'nb_user_login',
                    'validation_status' => 'nb_user_validation_status',
                    'policies_accepted' => 'nb_user_policies_accepted',
                    'tester' => 'nb_user_tester',
                    'alive' => 'nb_user_alive',
                    'first_name' => 'nb_user_first_name',
                    'last_name' => 'nb_user_last_name',
                    'fiscal_number' => 'nb_user_fiscal_number',
                    'birth_date' => 'nb_user_birth_date',
                    'address_1' => 'nb_user_address_1',
                    'address_2' => 'nb_user_address_2',
                    'zip_code' => 'nb_user_zip_code',
                    'city_name' => 'nb_user_city_name',
                    'province_name' => 'nb_user_province_name',
                    'country_name' => 'nb_user_country_name',
                    'telephone_prefix' => 'nb_user_telephone_prefix',
                    'telephone' => 'nb_user_telephone',
                    'cellular_prefix' => 'nb_user_cellular_prefix',
                    'cellular' => 'nb_user_cellular',
                    'fax_prefix' => 'nb_user_fax_prefix',
                    'fax' => 'nb_user_fax',
                    'web' => 'nb_user_web',
                    'work_centre' => 'nb_user_work_centre',
                    'work_position' => 'nb_user_work_position',
                    'work_city' => 'nb_user_work_city',
                    'about' => 'nb_user_about',
                    'allow_notification' => 'nb_user_allow_notification'
                )
            );

            if (is_string($pass_1 = $this->nb_request->getPOSTField('pass_1')) &&
                strlen($pass_1) > 0 &&
                is_string($pass_2 = $this->nb_request->getPOSTField('pass_2')) &&
                $pass_1 === $pass_2
            ) {
                $this->edit_user->setPassword($pass_1);
            }

            if (is_string($email = $this->nb_request->getPOSTField('email')) && strlen($email) > 0) {
                $this->edit_user->setEmail($email);
            }

            if ($this->edit_user->save()) {
                $this->setStatusOK();
                $this->setData($this->edit_user->getTreeData(null, true));
            }
        }

        return true;
    }
}
