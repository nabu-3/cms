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

namespace nabu\cms\plugins\sitetarget\icontacteditor;
use nabu\cms\plugins\sitetarget\base\CNabuCMSPluginAbstractAPI;
use nabu\data\icontact\CNabuIContact;
use nabu\data\icontact\CNabuIContactLanguage;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\icontacteditor
 */
class CNabuCMSPluginIContactAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuIContact $nb_icontact i-Contact instance */
    private $nb_icontact = null;

    public function prepareTarget()
    {
        if (count($fragments = $this->nb_request->getRegExprURLFragments()) === 2) {
            if (is_numeric($fragments[1])) {
                $this->nb_icontact = $this->nb_work_customer->getIContact($fragments[1]);
                $this->nb_icontact->refresh(true, false);
                $this->nb_icontact->grantHash(true);
            } elseif (!$fragments[1]) {
                $this->nb_icontact = new CNabuIContact();
                $this->nb_icontact->setCustomer($this->nb_work_customer);
                $this->nb_icontact->grantHash();
            }
        }

        return true;
    }

    public function methodPOST()
    {
        if ($this->nb_icontact instanceof CNabuIContact) {
            $this->nb_request->updateObjectFromPost(
                $this->nb_icontact,
                array(
                    'key' => 'nb_icontact_key',
                    'hash' => 'nb_icontact_hash',
                    'default_language_id' => 'nb_icontact_default_language_id',
                    'emailing_id' => 'nb_emailing_id',
                    'email_template_acknowledge' => 'nb_icontact_email_template_acknowledge',
                    'email_template_arrival' => 'nb_icontact_email_template_arrival'
                )
            );
            if ($this->nb_icontact->save()) {
                $languages = $this->nb_request->getCombinedPostIndexes(array('name'));
                if (count($languages) > 0) {
                    foreach ($languages as $lang_id) {
                        $nb_translation = $this->nb_icontact->getTranslation($lang_id);
                        if (!$nb_translation) {
                            $nb_translation = new CNabuIContactLanguage();
                            $nb_translation->setIContactId($this->nb_icontact->getId());
                            $nb_translation->setLanguageId($lang_id);
                            $this->nb_icontact->setTranslation($nb_translation);
                        }
                        $this->nb_request->updateObjectFromPost(
                            $nb_translation,
                            array(
                                'name' => 'nb_icontact_lang_name'
                            ),
                            null,
                            null,
                            $lang_id
                        );
                        $nb_translation->save(true);
                    }
                }
                $this->setStatusOK();
                $this->setData($this->nb_icontact->getTreeData(null, true));
            }
        }

        return true;
    }
}
