<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Template Helper
 *
 * @author Shahzaib
 */


/**
 * Get Site Theme Name
 *
 * @param  boolean $append_slash
 * @return string
 */
if ( ! function_exists( 'get_theme_name' ) )
{
    function get_theme_name( $append_slash = true )
    {
        $theme_name = strtolower( html_escape( db_config( 'site_theme' ) ) );
        
        if ( $append_slash === true )
        {
            return append_slash( $theme_name );
        }
        
        return $theme_name;
    }
}

/**
 * Is View Exists
 *
 * @param  string $path File reference ( without ".php" extension ).
 * @return boolean
 */
if ( ! function_exists( 'is_view_exists' ) )
{
    function is_view_exists( $path )
    {
        return file_exists( APPPATH . 'views/' . get_theme_name() . $path. '.php' );
    }
}

/**
 * Load View
 *
 * Use to load a view with the existence verification.
 *
 * @param  string $path
 * @param  array  $data
 * @return void
 */
if ( ! function_exists( 'load_view' ) )
{
    function load_view( $path, $data = [] )
    {
        $ci =& get_instance();
        
        if ( is_view_exists( $path ) ) $ci->load->view( get_theme_name() . $path, $data );
    }
}

/**
 * Load Modal(s)
 *
 * @param  array|string $paths
 * @return void
 */
if ( ! function_exists( 'load_modals' ) )
{
    function load_modals( $paths )
    {
        $ci =& get_instance();
        
        if ( is_array( $paths ) )
        {
            foreach ( $paths as $path )
            {
                $path = "modals/panel/{$path}";
                load_view( $path );
            }
        }
        else
        {
            $path = "modals/panel/{$paths}";
            load_view( $path );
        }
    }
}

/**
 * Read View
 *
 * Use to read the HTML of a view.
 *
 * @param  string       $path
 * @param  array|object $data
 * @return string
 */
if ( ! function_exists( 'read_view' ) )
{
    function read_view( $path, $data = [] )
    {
        $ci =& get_instance();
        
        return $ci->load->view( get_theme_name() . $path, $data, true );
    }
}

/**
 * Display/Load View
 *
 * Use to read and display the HTML of a view with the option of data passing.
 *
 * @param  string       $path
 * @param  array|object $data
 * @param  boolean      $stop
 * @return void
 */
if ( ! function_exists( 'display_view' ) )
{
    function display_view( $path, $data = [], $stop = true )
    {
        $ci =& get_instance();
        echo $ci->load->view( get_theme_name() . $path, $data, true );
        
        if ( $stop === true )
        exit;
    }
}
