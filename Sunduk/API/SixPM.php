<?php
    namespace Sunduk\API {
        use Sunduk\Network\WebClient;

        class SixPM {
            protected $webClient;

            public function __construct() {
                $this->webClient = new WebClient();
            }
        }
    }