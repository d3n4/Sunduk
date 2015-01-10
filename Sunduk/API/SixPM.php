<?php
    namespace Sunduk\API {
        /**
         * Class SixPM
         * @todo getCategories() of SixPM_Category[] -> getProducts() of SixPM_Product[]
         * @package Sunduk\API
         */
        class SixPM {
            public function __construct() {
                /* tests */
                libxml_use_internal_errors(true);
                $dom = new \DOMDocument();
                $dom->loadHTMLFile('http://localhost/Sunduk/cache/6pm.com_googles~2.html');
                $xpath = new \DomXPath($dom);
                $nodes = $xpath->query("//*[@id='searchResults']/a");
                $stack = array();
                /* @var \DOMElement $node */
                foreach ($nodes as $i => $node) {
                    $attrs = array();
                    $attrs['url'] = $node->getAttribute('href');
                    /* @var \DOMElement $childNode */
                    foreach($node->childNodes as $childNode) {
                        if($childNode instanceof \DOMElement) {
                            $attrs[$childNode->getAttribute('class')] = $childNode->getAttribute('src') ? $childNode->getAttribute('src') : $childNode->nodeValue;
                        }
                    }
                    $stack[] = $attrs;
                }
                print_r($stack);
            }
        }
    }