<?php
    namespace Sunduk\Network {

        /**
         * Class WebClient
         * @todo Cookie container
         * @package Sunduk\Network
         */
        class WebClient {

            protected $_url;
            protected $_isPost;
            protected $_fields;
            protected $_isForm;
            protected $_lastResponse;
            protected $_error;

            public function __construct() {
            }

            protected function reset() {
                $this->_url = null;
                $this->_fields = null;
                $this->_isPost = false;
                $this->_isForm = false;
                $this->_lastResponse = null;
                $this->_error = null;
            }

            public function get($url, $fields = null) {
                $this->reset();
                $this->_url = $url;
                $this->_fields = $fields;
                return $this->execute() ? $this->_lastResponse : null;
            }

            protected function formatGetFields($fields) {
                if($fields == null)
                    return '';
                $format = '?';
                foreach((array)$fields as $key => $value)
                    $format .= $key . '=' . urlencode($value) . '&';
                return $format;
            }

            /**
             * Execute current formatted request
             * @return bool Request success
             */
            protected function execute() {
                clog('[WebClient] Sending {0} Request to {1}', $this->_isPost ? 'POST' : 'GET', $this->_url);
                if($curl = curl_init()) {
                    curl_setopt($curl, CURLOPT_URL, $this->_url . (!$this->_isPost ? $this->formatGetFields($this->_fields) : ''));
                    if($this->_isForm) {
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
                        curl_setopt($curl, CURLOPT_HEADER, true);
                    }

                    if($this->_isPost === true) {
                        curl_setopt($curl, CURLOPT_POST, true);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->_fields);
                    }

                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $this->_lastResponse = curl_exec($curl);
                    if(!curl_errno($curl)){
                        $info = curl_getinfo($curl);
                        clog('[WebClient] Took {0} seconds to send a request to {1}', $info['total_time'], $info['url']);
                    } else {
                        $this->_error = curl_error($curl);
                        clog('[WebClient] Error: {0}', $this->_error);
                    }

                    curl_close($curl);

                    return !curl_errno($curl);
                }

                return false;
            }

            public function __toString()
            {
                return $this->_lastResponse;
            }
        }
    }