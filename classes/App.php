<?php

/**
 * Setup App Class
 */
class App
{
    public function __construct()
    {
        add_action('after_setup_theme', array($this, 'tusatelit_after_setup_theme'), 20);
    }

    public function tusatelit_after_setup_theme()
    {


    }
}
?>