<?php
    namespace Sunduk\API {
        use Sunduk\Network\WebClient;

        /**
         * Class SixPM
         * @todo getCategories() of SixPM_Category[] -> getProduct() of SixPM_Product
         * @package Sunduk\API
         */
        class SixPM {
            protected $webClient;

            public function __construct() {
                $this->webClient = new WebClient();
                $t = $this->webClient->get('http://www.6pm.com/goggles~2');
                clog('response: {0}', $t);
            }
        }
    }