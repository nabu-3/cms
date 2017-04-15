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

namespace nabu\cms\plugins\sitetarget;
use nabu\data\security\CNabuUser;
use nabu\http\CNabuHTTPRequest;
use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget
 */
class CNabuCMSPluginUserEdit extends CNabuHTTPSiteTargetPluginAdapter
{
    /** @var CNabuUser $edit_user User instance to be edited. */
    private $edit_user = null;
    /** @var string Title part with the visible name of current user. */
    private $title_part;
    /** @var array Breadcrumb part of current user. */
    private $breadcrumb_part;

    public function prepareTarget()
    {
        $retval = true;

        if ($this->nb_site_target->getKey() === 'my_profile') {
            $this->edit_user = $this->nb_user;
            $this->edit_user->refresh();
        } elseif ($this->nb_site_target->isURLRegExpExpression()) {
            if (count($fragments = $this->nb_request->getRegExprURLFragments()) === 2 &&
                is_numeric($fragments[1])
            ) {
                $id = $fragments[1];
                $this->edit_user = new CNabuUser($id);
                if ($this->edit_user->isNew() || !$this->edit_user->validateCustomer($this->nb_customer)) {
                    $this->edit_user = null;
                    $retval = $this->nb_site->getTargetByKey('user_list');
                } else {
                    $this->title_part = '#' . $id;
                    $name = trim($this->edit_user->getFirstName() . ' ' . $this->edit_user->getLastName());
                    if (strlen($name) === 0) {
                        $name = $this->edit_user->getLogin();
                    }
                    if (strlen($name) > 0) {
                        $this->title_part = $name;
                    }
                    $this->breadcrumb_part['user_edit'] = array(
                        'title' => $this->title_part,
                        'slug' => $id
                    );
                }
            }
        }

        return $retval;
    }

    public function commandUpdate()
    {
        if ($this->nb_request->getMethod() === CNabuHTTPRequest::METHOD_POST) {
            $this->nb_request->updateObjectFromPost(
                $this->edit_user,
                array(
                    'first_name' => 'nb_user_first_name',
                    'last_name' => 'nb_user_last_name'
                )
            );
            if (is_string($pass_1 = $this->nb_request->getPOSTField('pass_1')) &&
                strlen($pass_1) > 0 &&
                is_string($pass_2 = $this->nb_request->getPOSTField('pass_2')) &&
                $pass_1 === $pass_2
            ) {
                $this->edit_user->setPassword($pass_1);
            }

            $this->edit_user->save();
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        $render->smartyAssign('edit_user', $this->edit_user);
        $render->smartyAssign('title_part', $this->title_part);
        $render->smartyAssign('breadcrumb_part', $this->breadcrumb_part);

        return true;
    }
}
