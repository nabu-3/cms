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

namespace nabu\cms\plugins\sitetarget\base;
use nabu\http\adapters\CNabuHTTPSiteTargetPluginAdapter;
use nabu\http\renders\CNabuHTTPResponseJSONRender;

abstract class CNabuCMSPluginAbstractAPI extends CNabuHTTPSiteTargetPluginAdapter
{
    /**
     * Status of the Call
     * @var string
     */
    private $result;
    /**
     * Data to be sent as response
     * @var array
     */
    private $data;
    /**
     * Page information in case of $data was an array
     * @var array
     */
    private $page;

    public function __construct()
    {
        $this->result = null;
        $this->data = null;
        $this->page = null;
    }

    /**
     * Gets the response status
     * @return bool|string Returns the status if setted, or false if not.
     */
    public function getStatus()
    {
        return is_array($this->result) && array_key_exists('status', $this->result) ? $this->result['status'] : false;
    }

    public function setStatusError(string $message)
    {
        $this->result = array(
            'status' => 'ERROR',
            'message' => $message
        );
    }

    public function setStatusOK()
    {
        $this->result = array(
            'status' => 'OK'
        );
    }

    public function setData(array $data = null)
    {
        if ($this->getStatus() === 'OK') {
            $this->data = $data;
        }
    }

    public function setPage(int $count, int $offset, int $next_page, string $update_url = null)
    {
        if ($this->getStatus() === 'OK') {
            $this->page = array(
                'count' => $count,
                'offset' => $offset,
                'next_page' => $next_page
            );

            if ($update_url !== null) {
                $this->page['update_url'] = $update_url;
            }
        }
    }

    public function setAPICall(string $url)
    {
        if ($this->getStatus() === 'OK') {
            $this->result['api'] = $url;
        }
    }

    public function setEditorCall(array $url)
    {
        if ($this->getStatus() === 'OK') {
            $this->result['editor'] = $url;
        }
    }

    public function beforeDisplayTarget()
    {
        $render = $this->nb_response->getRender();
        if ($render instanceof CNabuHTTPResponseJSONRender) {
            if (!is_array($this->result)) {
                $this->result = array(
                    'status' => 'ERROR',
                    'message' => 'Error 500: Internal server error'
                );
                $this->data = null;
                $this->page = null;
            }
            $render->setValue('result', $this->result);
            if ($this->getStatus() === 'OK' && is_array($this->data)) {
                $render->setValue('data', $this->data);
                if (is_array($this->page)) {
                    $render->setValue('page', $this->page);
                }
            } elseif ($this->getStatus() === 'ERROR') {
                $this->nb_response->setHTTPResponseCode(500);
            }
        }

        return true;
    }
}
