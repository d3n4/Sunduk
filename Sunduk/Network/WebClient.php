<?php
    namespace Sunduk\Network {
        use Sunduk\IDisposable;

        /**
         * Class WebClient
         * @package Sunduk\Network
         * @todo Cookie container
         */
        class WebClient {

            protected $_url;
            protected $_isPost = false;
            protected $_postFields = false;
            protected $_isForm = false;
            protected $_lastResponse;
            protected $_error;

            public function __construct(){

            }

            /**
             * Execute current formatted request
             * @return bool Request success
             */
            protected function Execute() {
                $this->_error = null;
                clog('[WebClient] Sending {0} Request to {1}', $this->_isPost ? 'POST' : 'GET', $this->_url);
                if($curl = curl_init()) {
                    curl_setopt($curl, CURLOPT_URL, $this->_url);
                    if($this->_isForm) {
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
                        curl_setopt($curl, CURLOPT_HEADER, true);
                    }

                    if($this->_isPost === true){
                        curl_setopt($curl, CURLOPT_POST, true);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->_postFields);
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