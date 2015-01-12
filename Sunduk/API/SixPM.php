<?php
    namespace Sunduk\API {

        libxml_use_internal_errors(true);

        use \Sunduk\Extensions\GetterSetter;

        /**
         * Class SixPM_Category
         * @todo dynamic filter
         * @property string name
         * @property int count
         * @property string url
         * @property SixPM_Category[] categories
         * @package Sunduk\API
         */
        class SixPM_Category extends GetterSetter {
            /**
             * protected native DOM element for inner operations (dynamic methods, generators, etc.)
             * <A> tag
             * @var \DOMElement
             */
            protected $node;

            /**
             * @var string
             */
            protected $_name;

            /**
             * @var int
             */
            protected $_size;

            /**
             * @var string Category URL
             */
            protected $_url;

            /**
             * @var \DOMDocument
             */
            protected $_categoriesDocument;

            /**
             * @var SixPM_Category[] Sub categories (cache field)
             */
            protected $_categories;

            /**
             * @param \DOMElement $node
             */
            public function __construct($node) {
                // todo: check is right node was accepted else throw exception or silence idk.

                $this->node = $node;

                /** cache category name and size/count **/

                // todo: better method to extract category name
                $this->_name = trim($this->node->childNodes->item(0)->textContent);

                // todo: better method to extract products count
                $this->_size = intval( // contert to int (48 bytes. used for convert) todo: maybe remove it, if don't needed?! (then just trim)
                    str_replace( // remove trash ents... todo: check is substr is better for removing operation
                        array('(',')'),
                        '',
                        $this->node->childNodes->item(1)->textContent // second element inner A tag (must be span) todo: check is span else throw
                    )
                );


                // is tag <A> then get URL from href attribute
                if($this->node->tagName == 'a')
                    $this->_url = $this->node->getAttribute('href');
            }

            /** Readonly properties using getter **/

            /**
             * Get sub categories
             * @return null|SixPM_Category[] List of sub categories (null if not found)
             */
            public function get_categories() {
                // todo: check is URL is end with .ZSO (its some sort of dynamic filter) (case insensitive) then its has no sub cats, this is products list (parse products)
                if($this->_categories) // if cached then return cache field
                    return $this->_categories;
                if(!$this->_categoriesDocument)
                    $this->_categoriesDocument = new \DOMDocument();
                $this->_categoriesDocument->loadHTMLFile(SixPM::BASE_URL . $this->_url);
                $xpath = new \DOMXPath($this->_categoriesDocument);
                $nodes = $xpath->query('//*[@id="naviCenter"]/div[2]/a'); // div[2] - select only categories (second block of nav group)
                $this->_categories = array();
                foreach ($nodes as $i => $node)
                    $this->_categories[] = new SixPM_Category($node);

                return $this->_categories;
            }

            /**
             * @return string Name
             */
            public function get_name() {
                return $this->_name;
            }

            /**
             * @return int Category products count
             */
            public function get_count() {
                return $this->_size;
            }

            /**
             * @return string Category URL
             */
            public function get_url() {
                return $this->_url;
            }
        }

        /**
         * Class SixPM
         * @todo getCategories() of SixPM_Category[] -> getProducts() of SixPM_Product[]
         * @todo cache to file/db (for 6-12 hours) if needed
         * @property SixPM_Category[] types
         * @package Sunduk\API
         */
        class SixPM extends GetterSetter {
            const BASE_URL = 'http://www.6pm.com';
            const SEARCH_URL = 'http://localhost/Sunduk/cache/6pm.com_~1.html';

            protected $_types;
            protected $dom;

            public function __construct() {
                $this->dom = new \DOMDocument();
                /* tests */
                // @todo remove crud
                /*$dom = new \DOMDocument();
                $dom->loadHTMLFile('http://localhost/Sunduk/cache/6pm.com_googles~2.html');
                $xpath = new \DomXPath($dom);
                $nodes = $xpath->query("//*[@id='searchResults']/a");
                $stack = array();*/
                /* @var \DOMElement $node */
                /*foreach ($nodes as $i => $node) {
                    $attrs = array();
                    $attrs['url'] = $node->getAttribute('href');*/
                    /* @var \DOMElement $childNode */
                    /*foreach($node->childNodes as $childNode) {
                        if($childNode instanceof \DOMElement) {
                            $attrs[$childNode->getAttribute('class')] = $childNode->getAttribute('src') ? $childNode->getAttribute('src') : $childNode->nodeValue;
                        }
                    }
                    $stack[] = $attrs;
                }
                print_r($stack);*/
            }

            public function get_types() {
                if($this->_types)
                    return $this->_types;

                $this->dom->loadHTMLFile(self::SEARCH_URL);
                $xpath = new \DOMXPath($this->dom);
                $nodes = $xpath->query('//*[@id="naviCenter"]/div[1]/a'); // div[1] - select only types (first block of nav group)
                $this->_types = array();
                foreach ($nodes as $i => $node)
                    $this->_types[] = new SixPM_Category($node);

                return $this->_types;
            }
        }
    }