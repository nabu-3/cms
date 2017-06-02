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
use nabu\data\site\CNabuSiteMap;
use nabu\data\site\CNabuSiteTarget;
use nabu\data\site\CNabuSiteMapTree;
use nabu\data\site\CNabuSiteTargetSectionLanguage;
use nabu\visual\site\CNabuSiteVisualEditorItem;
use nabu\visual\site\CNabuSiteVisualEditorItemList;
use nabu\visual\site\base\CNabuSiteVisualEditorItemListBase;

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
        $vr_site_cell_list = new CNabuSiteVisualEditorItemList($nb_site);

        $parent = $this->graph->getDefaultParent();

        $this->model->beginUpdate();

        $nb_target_list = $nb_site->getTargets();

        $nb_target_list->iterate(function($key, $nb_site_target) use ($nb_site, $nb_language, $parent, $vr_site_cell_list) {
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
            $shape = ($nb_site_target->getAttachment() === 'T'
                      ? ($nb_site_target->getURLFilter() === CNabuSiteTarget::URL_TYPE_REGEXP ? 'document-multi' : 'document')
                      : ($nb_site_target->getURLFilter() === CNabuSiteTarget::URL_TYPE_REGEXP ? 'page-multi' : 'page')
                     )
            ;
            $vr_cell = $vr_site_cell_list->getItem('st-' . $key);
            if (!$vr_cell) {
                $vr_cell = new CNabuSiteVisualEditorItem();
                $vr_cell->setSite($nb_site);
                $vr_cell->setVRId('st-' . $key);
                $vr_cell->setX(20);
                $vr_cell->setY(20);
                $vr_cell->setWidth(120);
                $vr_cell->setHeight(160);
                $vr_cell->save();
            }
            $vertex = $this->graph->insertVertex(
                $parent, 'st-' . $key, $name,
                $vr_cell->getX(), $vr_cell->getY(),
                $vr_cell->getWidth(), $vr_cell->getHeight(),
                "shape=$shape;edgeStyle=elbowEdgeStyle;elbow=Vertical;whiteSpace=wrap;"
            );
            //$vertex = $this->model->cells['st-' . $key];
            $vertex->type = $shape;
            $vertex->objectId = $key;
            $sections = $this->prepareSectionsList($nb_site, $nb_site_target);
            $vertex->section_ids = is_array($sections) ? array_keys($sections) : null;
            $vertex->section_names = is_array($sections) ? array_values($sections) : null;

            return true;
        });

        $nb_target_list->iterate(function($key, $nb_site_target) use ($parent, $vr_site_cell_list) {
            $nb_cta_list = $nb_site_target->getCTAs();
            $nb_cta_list->iterate(function($key, $nb_site_target_cta) use ($nb_site_target, $parent, $vr_site_cell_list) {
                $from_id = 'st-' . $nb_site_target->getId();
                $from = $this->model->cells[$from_id];
                if (is_numeric($nb_target_id = $nb_site_target_cta->getTargetId())) {
                    $to_id = 'st-' . $nb_site_target_cta->getTargetId();
                    $to = $this->model->cells[$to_id];
                    $edge = $this->graph->insertEdge($parent, 'cta-' . $key, $nb_site_target_cta->getKey(), $from, $to, 'edgeStyle=orthogonalEdgeStyle;whiteSpace=wrap;');
                } else {
                    $edge = $this->graph->insertEdge($parent, 'cta-' . $key, $nb_site_target_cta->getKey(), $from, null, 'edgeStyle=orthogonalEdgeStyle;whiteSpace=wrap;');
                }
                $edge->type = 'cta';
                $edge->objectId = $key;
                $vr_cell = $vr_site_cell_list->getItem('cta-' . $key);
                if ($vr_cell instanceof CNabuSiteVisualEditorItem) {
                    $vr_cell->applyGeometryToEdge($edge);
                }
                return true;
            });
            return true;
        });

        $nb_map_list = $nb_site->getSiteMaps();
        $this->paintMaps($nb_site, $nb_language, $vr_site_cell_list, $nb_map_list, true);

        $this->model->endUpdate();
    }

    private function paintMaps(CNabuSite $nb_site, CNabuLanguage $nb_language, CNabuSiteVisualEditorItemList $vr_site_cell_list, CNabuSiteMapTree $nb_map_list, bool $root = false)
    {
        $parent = $this->graph->getDefaultParent();

        $nb_map_list->iterate(function($key, CNabuSiteMap $nb_site_map) use ($nb_site, $nb_language, $parent, $vr_site_cell_list) {
            if (($nb_translation = $nb_site_map->getTranslation($nb_language)) instanceof INabuTranslation ||
                ($nb_translation = $nb_site_map->getTranslation($nb_site->getDefaultLanguageId())) instanceof INabuTranslation ||
                ($nb_translation = $nb_site_map->getTranslation($nb_site->getApiLanguageId())) instanceof INabuTranslation
            ) {
                if (strlen($name = $nb_translation->getContent()) === 0 &&
                    strlen($name = $nb_translation->getTitle()) === 0 &&
                    strlen($name = $nb_translation->getKey()) === 0
                ) {
                    $name = '#' . $nb_site_map->getId();
                }
            } else {
                $name = '#' . $nb_site_map->getId();
            }

            $vr_cell = $vr_site_cell_list->getItem('smclu-' . $key);
            if (!$vr_cell) {
                $vr_cell = new CNabuSiteVisualEditorItem();
                $vr_cell->setSite($nb_site);
                $vr_cell->setVRId('smclu-' . $key);
                $vr_cell->setX(20);
                $vr_cell->setY(20);
                $vr_cell->setWidth(60);
                $vr_cell->setHeight(60);
                $vr_cell->save();
            }
            $vertex = $this->graph->insertVertex(
                $parent, 'smclu-' . $key, $name,
                $vr_cell->getX(), $vr_cell->getY(),
                $vr_cell->getWidth(), $vr_cell->getHeight(),
                "shape=cluster;whiteSpace=wrap;"
            );
            $vertex->type = 'cluster';
            $vertex->objectId = $key;

            if ($nb_site_map->isUsingURIAsTarget() && is_numeric($nb_st_id = $nb_site_map->getSiteTargetId())) {
                $from = $this->model->cells['st-' . $nb_st_id];
                $edge = $this->graph->insertEdge($parent, 'smclues-' . $key, '', $from, $vertex, "endArrow=none;");
                $edge->type = 'cluster-target';
                $edge->objectId = $key;
            }

            if (($nb_sm_parent_id = $nb_site_map->getParentId()) !== null) {
                $from = $this->model->cells['smclu-' . $nb_sm_parent_id];
                $edge = $this->graph->insertEdge($parent, 'smcluep-' . $key, '', $from, $vertex);
                $edge->type = 'cluster-parent';
                $edge->objectId = $key;
            }

            /*
            if (is_numeric($nb_parent_id = $nb_site_map->getParentId())) {
                $from = $this->model->cells['sm-' . $nb_parent_id];
            }

            if ($nb_child_list->getSize() > 0 || $nb_site_map->getLevel() === 1) {
                if ($nb_site_map->getUseURI() === 'T' && isset($from) && is_numeric($nb_st_id = $nb_site_map->getSiteTargetId())) {
                    $this->graph->insertVertex($parent, 'smclu-' . $key, $name, 20, 20, 40, 40, "portConstraint=northsouth;shape=cluster;whiteSpace=wrap;");
                    $cluster = $this->model->cells['smclu-' . $key];
                    $this->graph->insertEdge($parent, 'smclue-' . $key, '', $from, $cluster);
                }
                $to_id = 'sm-' . $key;
                $this->graph->insertVertex($parent, $to_id, $name, 20, 20, 120, 40, "portConstraint=northsouth;shape=conditional-selector;whiteSpace=wrap;");
                if ($nb_site_map->getParentId() !== null) {
                    $to = $this->model->cells[$to_id];
                    $this->graph->insertEdge($parent, 'smc-' . $key, '', (isset($cluster) ? $cluster : $from), $to);
                }
            }

            if ($nb_site_map->getUseURI() === 'T' && isset($from) && is_numeric($nb_st_id = $nb_site_map->getSiteTargetId())) {
                $to = $this->model->cells['st-' . $nb_st_id];
                $this->graph->insertEdge($parent, 'smt-' . $nb_st_id, (isset($cluster) ? '' : $name), (isset($cluster) ? $cluster : $from), $to);
            }
            */

            $nb_child_list = $nb_site_map->getChilds();
            $this->paintMaps($nb_site, $nb_language, $vr_site_cell_list, $nb_child_list);

            return true;
        });
    }

    public function getModelAsXML()
    {
        $enc = new \mxCodec();
        $xmlNode = $enc->encode($this->model);

        return new SimpleXMLElement(str_replace("\n", "&#xa;", $xmlNode->ownerDocument->saveXML($xmlNode)));
    }

    private function prepareSectionsList(CNabuSite $nb_site, CNabuSiteTarget $nb_site_target)
    {
        $list = array();

        $nb_language_id = $nb_site->getDefaultLanguageId();

        $nb_site_target->getSections()->iterate(function($key, $nb_site_target_section) use (&$list, $nb_language_id)
        {
            $nb_translation = $nb_site_target_section->getTranslation($nb_language_id);
            if ($nb_translation instanceof CNabuSiteTargetSectionLanguage) {
                $name = trim($nb_translation->getTitle());
            }
            if (strlen($name) === 0) {
                $name = trim($nb_site_target_section->getKey());
            }
            if (strlen($name) === 0) {
                $name = "#$key";
            }
            $list[$key] = $name;

            return true;
        });

        return count($list) > 0 ? $list : null;
    }
}
