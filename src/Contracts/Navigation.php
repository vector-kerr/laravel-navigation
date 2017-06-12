<?php

namespace Vector88\Navigation\Contracts;

interface Navigation {

    /**
     * Build a navigation tree using the given context.
     * @param  object $context  A contextual identifier of any type, which
     *                             is passed on to the navigation tree event
     *                             listeners.
     * @return array            An associative array containing the
     *                             generated navigation tree.
     */
    function build( $context = null );

}
