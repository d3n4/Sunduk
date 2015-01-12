<?php
    namespace Sunduk {
        use Sunduk\API\SixPM;

        class app {
            protected $api;

            function main() {
                $this->api = new SixPM();
                foreach($this->api->types as $cat) {
                    echo $cat->name." - " . $cat->count . '('.$cat->url.')' . "\r\n";
                    if($subcats = $cat->categories) {
                        foreach($subcats as $subcat) {
                            echo '--- '.$subcat->name." - " . $subcat->count . '('.$subcat->url.')' . "\r\n";
                        }
                    }
                }
            }
        }
    }