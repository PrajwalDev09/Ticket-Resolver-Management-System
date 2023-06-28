<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['post_controller_constructor'] = function ()
{
    $ci =& get_instance();
    
    $ci->config->set_item( 'language', get_language() );
    
    $ci->lang->load( ['global', 'errors', 'success', 'notifications', 'activities'] );
    
    set_settings();
};

$hook['post_controller'] = function ()
{
    if ( ENVIRONMENT !== 'development' && is_dir( FCPATH . 'install' ) )
    {
        exit( '<h3>Delete the /install Directory.</h3>' );
    }
    
    mm_check();
};
