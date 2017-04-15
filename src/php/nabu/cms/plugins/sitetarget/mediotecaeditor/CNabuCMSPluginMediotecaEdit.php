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

namespace nabu\cms\plugins\sitetarget\mediotecaeditor;
use nabu\data\lang\CNabuLanguage;
use nabu\data\medioteca\CNabuMedioteca;
use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\mediotecaeditor
 */
class CNabuCMSPluginMediotecaEdit extends CNabuHTTPSiteTargetPluginAdapter
{
    /** @var CNabuMedioteca $nb_medioteca Medioteca instance to be edited. */
    private $nb_medioteca;
    /** @var string $title_part Title fragment to be added to the page header title. */
    private $title_part;
    /** @var array $breadcrumb_part Array of breadcrumb parts. */
    private $breadcrumb_part;

    public function prepareTarget()
    {
        $this->nb_medioteca = null;
        $this->title_part = null;
        $this->breadcrumb_part = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) > 1) {
            $id = $fragments[1];
            $this->title_part = '#' . $id;
            if (($this->nb_medioteca = $this->nb_work_customer->getMedioteca($id)) !== false &&
                $this->nb_medioteca->refresh(true, true) &&
                ($translation = $this->nb_medioteca->getTranslation($this->nb_language)) !== false &&
                (strlen($this->title_part = $translation->getTitle()) === 0) &&
                (strlen($this->title_part = $this->nb_medioteca->getKey()) === 0)
            ) {
                $this->title_part = '#' . $id;
            }
            $this->breadcrumb_part['medioteca_edit'] = array(
                'title' => $this->title_part,
                'slug' => $id
            );
        }

        return ($this->nb_medioteca instanceof CNabuMedioteca)
               ? true
               : $this->nb_site->getTargetByKey('medioteca_list');
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        $render->smartyAssign('edit_medioteca', $this->nb_medioteca);
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
