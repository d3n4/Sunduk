<?php
    namespace Sunduk {
        use Sunduk\API\SixPM;

        class app {
            protected $api;

            function main() {
                $this->api = new SixPM();
            }
        }
    }