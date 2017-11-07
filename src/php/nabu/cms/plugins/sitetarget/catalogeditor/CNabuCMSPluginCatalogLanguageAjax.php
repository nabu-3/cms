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

namespace nabu\cms\plugins\sitetarget\catalogeditor;
use nabu\data\lang\CNabuLanguage;
use nabu\data\catalog\CNabuCatalog;
use nabu\data\catalog\CNabuCatalogLanguage;
use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;
use providers\smarty\smarty\renders\CSmartyHTTPRender;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\catalogeditor
 */
class CNabuCMSPluginCatalogLanguageAjax extends CNabuHTTPSiteTargetPluginAdapter
{
    /** @var CNabuCatalog $nb_catalog Catalog selected */
    private $nb_catalog = null;
    /** @var CNabuCatalogLanguage $nb_catalog_language Language selected */
    private $nb_catalog_language = null;

    public function prepareTarget()
    {
        $this->nb_catalog = null;
        $this->nb_catalog_language = null;

        $fragments = $this->nb_request->getRegExprURLFragments();
        if (is_array($fragments) && count($fragments) === 3 && is_numeric($fragments[1])) {
            $this->nb_catalog = $this->nb_work_customer->getCatalog($fragments[1]);
            if ($this->nb_catalog instanceof CNabuCatalog) {
                $this->nb_catalog->refresh(true, true);
                if (is_numeric($fragments[2])) {
                    $this->nb_catalog_language = $this->nb_catalog->getTranslation($fragments[2]);
                }
            }
        }

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();

        if ($render instanceof CSmartyHTTPRender) {
            $render->smartyAssign('nb_catalog', $this->nb_catalog, $this->nb_language);
            $render->smartyAssign('edit_language', $this->nb_catalog_language, $this->nb_language);
            $render->smartyAssign('nb_all_languages', CNabuLanguage::getNaturalLanguages());
        }

        return true;
    }
}
