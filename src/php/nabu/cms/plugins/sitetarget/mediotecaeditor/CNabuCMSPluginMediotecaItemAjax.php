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
use nabu\data\medioteca\CNabuMedioteca;
use nabu\data\medioteca\CNabuMediotecaItem;
use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;
use providers\smarty\smarty\renders\CSmartyHTTPRender;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @since 3.0.1 Surface
 * @version 3.0.1 Surface
 * @package \nabu\cms\plugins\sitetarget\mediotecaeditor
 */
class CNabuCMSPluginMediotecaItemAjax extends CNabuHTTPSiteTargetPluginAdapter
{
    /** @var CNabuMedioteca $nb_medioteca Medioteca selected */
    private $nb_medioteca = null;
    /** @var CNabuMediotecaItem $nb_medioteca_item Item selected */
    private $nb_medioteca_item = null;

    public function prepareTarget()
    {
        $this->nb_medioteca = null;
        $this->nb_medioteca_item = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 3 && is_numeric($fragments[1])) {
            $this->nb_medioteca = $this->nb_work_customer->getMedioteca($fragments[1]);
            if ($this->nb_medioteca instanceof CNabuMedioteca) {
                $this->nb_medioteca->refresh();
                if (is_numeric($fragments[2])) {
                    $this->nb_medioteca_item = $this->nb_medioteca->getItems()->getItem($fragments[2]);
                }
            }
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();

        if ($render instanceof CSmartyHTTPRender) {
            $render->smartyAssign('nb_medioteca', $this->nb_medioteca, $this->nb_language);
            $render->smartyAssign('edit_item', $this->nb_medioteca_item, $this->nb_language);
            $render->smartyAssign('nb_all_languages', $this->nb_medioteca->getLanguages());
        }

        return true;
    }
}
