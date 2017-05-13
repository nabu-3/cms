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
use nabu\cms\plugins\sitetarget\base\CNabuCMSPluginAbstractAPI;
use nabu\data\site\CNabuSite;
use nabu\visual\site\CNabuSiteVisualEditorItem;

class CNabuCMSPluginSiteVisualEditorCellAPI extends CNabuCMSPluginAbstractAPI
{
    /** @var CNabuSite $edit_site Site instance */
    private $edit_site = null;

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
                    $vr_cell->save();
                }
            }
            $this->setStatusOK();
        } else {
            $this->setStatusError('Site not valid');
        }

        return true;
    }
}
