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

namespace nabu\cms\plugins\sitetarget\siteeditor;
use nabu\cms\visualeditor\site\CNabuCMSVisualEditorSiteBuilder;
use nabu\data\lang\CNabuLanguage;
use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;

require_once "providers/mxgraph/mxgraph/3.7.2/mxServer.php";

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\plugins\sitetarget\siteeditor
 */
class CNabuCMSPluginSiteTargetVisualEditor extends CNabuHTTPSiteTargetPluginAdapter
{
    private $xml = null;

    public function prepareTarget()
    {
        $lang = new CNabuLanguage($this->nb_request->getGETField('lang'));
        $builder = new CNabuCMSVisualEditorSiteBuilder();

        $builder->create();
        $builder->fillFromSite($this->nb_site, $lang);

        $this->xml = $builder->getModelAsXML();

        return true;
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        $render->setXML($this->xml);

        return true;
    }
}
