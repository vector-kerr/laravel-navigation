<?php

namespace Vector88\Navigation\Events;
use Vector88\Navigation\Models\NavigationItem;
use stdClass;

class BuildNavigation {

    protected $_items = [];
    public $context;

    public function __construct( $context = null ) {
        $this->context = $context;
    }

    public function add( NavigationItem $item ) {
        $this->_items[] = $item;
    }

    public function getTree() {

        $root = new stdClass();
        $root->items = [];

        foreach( $this->_items as $item ) {

            // Ensure the tree structure exists for this item
            $keyParts = explode( ".", $item->key );
            $target = &$root;

            $currentKey = null;
            foreach( $keyParts as $currentKey ) {
                if( !isset( $target->items[ $currentKey ] ) ) {
                    $target->items[ $currentKey ] = new stdClass();
                    $target->items[ $currentKey ]->items = [];
                }
                $target = &$target->items[ $currentKey ];
            }

            // Set attributes
            $target->label = $item->label ? $item->label : $currentKey;
            $target->sortIndex = $item->sortIndex;
            $target->href = $item->href ? $item->href : "#";
            $target->right = $item->right;
            $target->data = $item->data;

        }

        return $root->items;
    }

}
