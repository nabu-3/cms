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

namespace nabu\cms\visualeditor\site;
use SimpleXMLElement;
use nabu\core\CNabuObject;
use nabu\data\lang\CNabuLanguage;
use nabu\data\lang\interfaces\INabuTranslation;
use nabu\data\site\CNabuSite;
use nabu\data\site\CNabuSiteTarget;

require_once "providers/mxgraph/mxgraph/3.7.2/mxServer.php";

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.2 Surface
 * @version 3.0.2 Surface
 * @package \nabu\cms\visualeditor\site
 */
class CNabuCMSVisualEditorSiteBuilder extends CNabuObject
{
    /** @var \mxGraph $graph */
    private $graph = null;
    /** @var \mxGraphModel $model */
    private $model = null;

    public function __construct()
    {
        parent::__construct();
    }

    public function create()
    {
        $this->graph = new \mxGraph();
        $this->model = $this->graph->getModel();
    }

    public function fillTest()
    {
        $parent = $this->graph->getDefaultParent();

        $this->model->beginUpdate();

        $v1 = $this->graph->insertVertex($parent, null, "Hello", 20, 20, 80, 30);
        $v2 = $this->graph->insertVertex($parent, null, "World", 200, 150, 80, 30);
        $this->graph->insertEdge($parent, null, "", $v1, $v2);

        $this->model->endUpdate();
    }

    public function fillFromSite(CNabuSite $nb_site, CNabuLanguage $nb_language)
    {
        $nb_target_list = $nb_site->getTargets();

        $parent = $this->graph->getDefaultParent();

        $this->model->beginUpdate();

        $nb_target_list->iterate(function($key, $nb_site_target) use ($nb_site, $nb_language, $parent) {
            if (($nb_translation = $nb_site_target->getTranslation($nb_language)) instanceof INabuTranslation ||
                ($nb_translation = $nb_site_target->getTranslation($nb_site->getDefaultLanguageId())) instanceof INabuTranslation ||
                ($nb_translation = $nb_site_target->getTranslation($nb_site->getApiLanguageId())) instanceof INabuTranslation
            ) {
                if (strlen($name = $nb_translation->getTitle()) === 0 &&
                    strlen($name = $nb_translation->getHeadTitle()) === 0 &&
                    strlen($name = $nb_translation->getKey()) === 0
                ) {
                    $name = '#' . $nb_site_target->getId();
                }
            } else {
                $name = '#' . $nb_site_target->getId();
            }
            $shape = ($nb_site_target->getURLFilter() === CNabuSiteTarget::URL_TYPE_REGEXP ? 'page-multi' : 'page');
            $this->graph->insertVertex($parent, 'st-' . $key, $name, 20, 20, 120, 160, "shape=$shape");
            return true;
        });

        $nb_target_list->iterate(function($key, $nb_site_target) use ($parent) {
            $nb_cta_list = $nb_site_target->getCTAs();
            $nb_cta_list->iterate(function($key, $nb_site_target_cta) use ($nb_site_target, $parent) {
                $from_id = 'st-' . $nb_site_target->getId();
                $to_id = 'st-' . $nb_site_target_cta->getTargetId();
                $from = $this->model->cells[$from_id];
                $to = $this->model->cells[$to_id];
                $this->graph->insertEdge($parent, 'cta-' . $nb_site_target_cta->getId(), $nb_site_target_cta->getKey(), $from, $to, 'color=#FF0000');
            });
            return true;
        });

        $this->model->endUpdate();
    }

    public function getModelAsXML()
    {
        $enc = new \mxCodec();
        $xmlNode = $enc->encode($this->model);

        return new SimpleXMLElement(str_replace("\n", "&#xa;", $xmlNode->ownerDocument->saveXML($xmlNode)));
    }
}
