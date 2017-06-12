<?php

namespace Vector88\Navigation\Models;

class NavigationItem {

    public $key;
    public $label;
    public $href;
    public $sortIndex;
    public $right;
    public $data;

    public function __construct( $key, $label = "", $href = null, $sortIndex = 0, $right = false ) {
        $this->key = $key;
        $this->label = $label;
        $this->href = $href;
        $this->sortIndex = $sortIndex;
        $this->right = $right;

        $this->data = new \stdClass();
    }

}
