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
use nabu\data\icontact\CNabuIContactProspectStatusType;
use nabu\data\icontact\CNabuIContactProspectStatusTypeLanguage;
use nabu\data\site\CNabuSiteTargetCTAList;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\icontacteditor
 */
class CNabuCMSPluginIContactProspectStatusTypeAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuIContact $nb_icontact iContact selected */
    private $nb_icontact = null;
    /** @var CNabuIContactProspectStatusType $nb_icontact_prospect_status_type Item selected */
    private $nb_icontact_prospect_status_type = null;

    public function prepareTarget()
    {
        $this->nb_icontact = null;
        $this->nb_icontact_prospect_status_type = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 3) {
            $this->nb_icontact = $this->nb_work_customer->getIContact($fragments[1]);
            if ($this->nb_icontact instanceof CNabuIContact) {
                $this->nb_icontact->refresh();
                if (is_numeric($fragments[2])) {
                    $this->nb_icontact_prospect_status_type = $this->nb_icontact->getItems()->getItem($fragments[2]);
                    $this->nb_icontact_prospect_status_type->refresh();
                } elseif (!$fragments[2]) {
                    $this->nb_icontact_prospect_status_type = new CNabuIContactProspectStatusType();
                    $this->nb_icontact_prospect_status_type->setIContact($this->nb_icontact);
                    $this->nb_icontact_prospect_status_type->setHash(nb_generateGUID());
                }
            }
        }

        return true;
    }

    public function methodGET()
    {
        if ($this->nb_icontact_prospect_status_type instanceof CNabuIContactProspectStatusType) {
            $this->setStatusOK();
            $this->setData($this->nb_icontact_prospect_status_type->getTreeData(null, true));
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
        if ($this->nb_icontact_prospect_status_type instanceof CNabuIContactProspectStatusType) {
            $this->nb_request->updateObjectFromPost(
                $this->nb_icontact_prospect_status_type,
                array(
                    'key' => 'nb_icontact_prospect_status_type_key',
                    'hash' => 'nb_icontact_prospect_status_type_hash'
                )
            );
            $this->nb_icontact_prospect_status_type->grantHash();
            if ($this->nb_icontact_prospect_status_type->save()) {
                $languages = $this->nb_request->getCombinedPostIndexes(array('name'));
                if (count($languages) > 0) {
                    foreach ($languages as $lang_id) {
                        $nb_translation = $this->nb_icontact_prospect_status_type->getTranslation($lang_id);
                        if (!$nb_translation) {
                            $nb_translation = new CNabuIContactProspectStatusTypeLanguage();
                            $nb_translation->setIContactProspectStatusTypeId($this->nb_icontact_prospect_status_type->getId());
                            $nb_translation->setLanguageId($lang_id);
                            $this->nb_icontact_prospect_status_type->setTranslation($nb_translation);
                        }
                        $this->nb_request->updateObjectFromPost(
                            $nb_translation,
                            array(
                                'name' => 'nb_icontact_prospect_status_type_lang_name'
                            ),
                            null,
                            null,
                            $lang_id
                        );
                        $nb_translation->save();
                    }
                }

                $this->setStatusOK();
                $this->setData($this->nb_icontact_prospect_status_type->getTreeData(null, true));
            }
        }
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        if ($this->getStatus() === 'OK') {
            $api_mask = $this->nb_site_target->getFullyQualifiedURL($this->nb_language, true);
            $api_url = sprintf($api_mask, $this->nb_icontact->getId(), $this->nb_icontact_prospect_status_type->getId());
            $this->setAPICall($api_url);
            $editor_cta = $this->nb_site_target->getCTAs()->getItem('ajax_status_type', CNabuSiteTargetCTAList::INDEX_KEY);
            $editor_cta->canonize();
            $urls = array();
            $editor_cta->getTranslations()->iterate(function ($key, $translation) use(&$urls) {
                $editor_mask = $translation->getFinalURL();
                $urls[$key] = sprintf($editor_mask, $this->nb_icontact->getId(), $this->nb_icontact_prospect_status_type->getId());
            });
            $this->setEditorCall($urls);
        }

        return parent::beforeDisplayTarget();
    }
}
