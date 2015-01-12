<?php

    namespace Sunduk\Extensions {
        class GetterSetter {
            public function __get($key) {
                $callee = array($this, 'get_' . $key);
                if(is_callable($callee))
                    return call_user_func($callee);
                return null;
            }

            public function __set($key, $value) {
                $callee = array($this, 'set_' . $key);
                if(is_callable($callee))
                    return call_user_func($callee, $value);
                return null;
            }
        }
    }