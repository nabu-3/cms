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

namespace nabu\cms\plugins\sitetarget\siteeditor;
use nabu\cms\plugins\sitetarget\base\CNabuCMSPluginAbstractAPI;
use nabu\data\site\CNabuSite;
use nabu\data\site\CNabuSiteMap;
use nabu\data\site\CNabuSiteTarget;
use nabu\data\site\CNabuSiteTargetCTA;
use nabu\visual\site\CNabuSiteVisualEditorItem;

class CNabuCMSPluginSiteVisualEditorCellAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuSite $edit_site Site instance */
    private $edit_site = null;
    /** @var CNabuSiteTargetCTA Site Target CTA instance */
    private $edit_site_target_cta = null;

    public function prepareTarget()
    {
        if (count($fragments = $this->nb_request->getRegExprURLFragments()) >= 2) {
            if (is_numeric($fragments[1])) {
                $this->nb_work_customer->refresh(true, false);
                $this->edit_site = $this->nb_work_customer->getSite($fragments[1]);
                if ($this->edit_site instanceof CNabuSite) {
                    $this->edit_site->refresh(true, true);
                }
            }
        }

        return true;
    }

    /**
     * Treats post calls.
     * @return mixed Returns the result status
     */
    public function methodPOST()
    {
        $retval = true;

        if ($this->nb_request->hasGETField('action')) {
            switch($this->nb_request->getGETField('action')) {
                case 'mass-geometry':
                    $retval = $this->massGeometry();
                    break;
                case 'set-cta':
                    $retval = $this->setCTA();
                    break;
                case 'set-map-target':
                    $retval = $this->setMapTarget();
                    break;
                default:
                    $this->setStatusError('Invalid action ' . $this->nb_request->getGETField('action'));
            }
        }

        return $retval;
    }

    /**
     * Treats delete calls.
     * @return mixed Returns the result status
     */
    public function methodDELETE()
    {
        $retval = true;

        if ($this->nb_request->hasGETField('action')) {
            switch($this->nb_request->getGETField('action')) {
                case 'remove-cta':
                    $retval = $this->removeCTA();
                    break;
                default:
                    $this->setStatusError('Invalid action ' . $this->nb_request->getGETField('action'));
            }
        }

        return $retval;
    }

    private function massGeometry()
    {
        if ($this->edit_site instanceof CNabuSite) {
            $cells = $this->nb_request->getBody();
            foreach ($cells as $cell) {
                if (array_key_exists('id', $cell)) {
                    $vr_cell = new CNabuSiteVisualEditorItem($this->edit_site, $cell['id']);
                    if ($vr_cell->isNew()) {
                        $vr_cell->setSite($this->edit_site);
                        $vr_cell->setVRId($cell['id']);
                    }
                    $vr_cell->setX($cell['x']);
                    $vr_cell->setY($cell['y']);
                    $vr_cell->setWidth($cell['width']);
                    $vr_cell->setHeight($cell['height']);
                    $vr_cell->setValueJSONEncoded('nb_site_visual_editor_item_points', $cell['points']);
                    $vr_cell->save();
                }
            }
            $this->setStatusOK();
        } else {
            $this->setStatusError('Site not valid');
        }

        return true;
    }

    private function setCTA()
    {
        if ($this->edit_site instanceof CNabuSite) {
            $ids = $this->nb_request->getBody();
            if (array_key_exists('id', $ids) &&
                array_key_exists('source_id', $ids) &&
                array_key_exists('target_id', $ids)
            ) {
                $nb_site_target = is_numeric($ids['source_id'])
                                ? $this->edit_site->getTarget($ids['source_id'])
                                : null
                ;
                if ($nb_site_target instanceof CNabuSiteTarget) {
                    $nb_site_target_cta = is_numeric($ids['id'])
                                        ? $this->edit_site->getCTA($ids['id'])
                                        : new CNabuSiteTargetCTA()
                    ;
                    if ($nb_site_target_cta instanceof CNabuSiteTargetCTA) {
                        $nb_site_target_cta->grantHash();
                        $nb_site_target_cta->setSiteTarget($nb_site_target);
                        if (is_numeric($ids['target_id'])) {
                            if (($nb_remote_target = $this->edit_site->getTarget($ids['target_id'])) instanceof CNabuSiteTarget) {
                                $nb_site_target_cta->setCTATarget($nb_remote_target);
                                $nb_site_target_cta->save();
                                $this->setStatusOK();
                                $this->setData($nb_site_target_cta->getTreeData(null, true));
                            } else {
                                $this->setStatusError('Invalid target destination.');
                            }
                        } else {
                            $nb_site_target_cta->emptyCTATarget();
                            $nb_site_target_cta->save();
                            $this->setStatusOK();
                            $this->setData($nb_site_target_cta->getTreeData(null, true));
                        }
                    } else {
                        $this->setStatusError('Invalid CTA Id');
                    }
                } else {
                    $this->setStatusError('Source target required.');
                }
            }
        }

        return true;
    }

    private function removeCTA()
    {
        if ($this->edit_site instanceof CNabuSite) {
            $ids = $this->nb_request->getBody();
            if (array_key_exists('id', $ids) &&
                is_numeric($ids['id']) &&
                ($nb_site_target_cta = $this->edit_site->getCTA($ids['id'])) instanceof CNabuSiteTargetCTA
            ) {
                $nb_site_target_cta->delete();
                $this->setStatusOK();
                $this->setData($ids);
            } else {
                error_log($ids['id']);
                error_log(print_r($nb_site_target_cta, true));
                $this->setStatusError('Invalid CTA Id');
            }
        }

        return true;
    }

    private function setMapTarget()
    {
        if ($this->edit_site instanceof CNabuSite) {
            if ($this->nb_request->hasPOSTField('map_id') &&
                is_numeric($nb_map_id = $this->nb_request->getPOSTField('map_id')) &&
                $this->nb_request->hasPOSTField('target_id') &&
                ($nb_site_map = $this->edit_site->getSiteMap($nb_map_id)) instanceof CNabuSiteMap &&
                $nb_site_map->refresh(true, false)
            ) {
                if (is_numeric($nb_target_id = $this->nb_request->getPOSTField('target_id'))) {
                    if (($nb_site_target = $this->edit_site->getTarget($nb_target_id)) instanceof CNabuSiteTarget) {
                        $nb_site_map->setSiteTarget($nb_site_target);
                        $nb_site_map->save();
                        $this->setStatusOK();
                        $this->setData($nb_site_map->getTreeData(null, true));
                    }
                } elseif ($nb_target_id === null) {
                    $nb_site_map->setSiteTarget(null);
                    $nb_site_map->save();
                    $this->setStatusOK();
                    $this->setData($nb_site_map->getTreeData(null, true));
                }
            } else {
                $this->setStatusError('Target or Map are not allowed in this Site');
            }
        }

        return true;
    }
}
