<?php

namespace Vector88\Navigation\Services;
use Vector88\Navigation\Events\BuildNavigation;

class Navigation {

    public function build( $context = null ) {
        $e = new BuildNavigation( $context );
        event( $e );
        return $e->getTree();
    }

}
