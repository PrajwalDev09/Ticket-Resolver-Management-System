<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Pages Helper
 *
 * @author Shahzaib
 */



/**
 * Get Custom Pages
 *
 * @param   integer $visibility
 * @return  object
 * @version 1.8
 */
if ( ! function_exists( 'get_custom_pages' ) )
{
    function get_custom_pages( $visibility = 1 )
    {
        $ci =& get_instance();
        $ci->load->model( 'Page_model' );
        return $ci->Page_model->custom_pages( $visibility );
    }
}

/**
 * Get Page Name
 *
 * @param  integer $id
 * @return string
 */
if ( ! function_exists( 'get_page_name' ) )
{
    function get_page_name( $id )
    {
        if ( $id == 1 ) return lang( 'terms_of_use' );
        
        return lang( 'privacy_policy' );
    }
}

/**
 * Version to Combine
 *
 * Use to combind the version as hidden.
 *
 * @return string
 */
if ( ! function_exists( 'v_combine' ) )
{
    function v_combine()
    {
        return md5( Z_DESK_VERSION ); 
    }
}

/**
 * Admin LTE Asset
 *
 * Use to add the Admin LTE ( panel/admin_lte/ ) asset.
 *
 * @param  string  $path
 * @param  boolean $get
 * @return string
 */
if ( ! function_exists( 'admin_lte_asset' ) )
{
    function admin_lte_asset( $path, $get = false )
    {
        $admin_lte_asset = sprintf( ADMIN_LTE_ASSETS, get_theme_name( false ) );
        $url = base_url( append_slash( $admin_lte_asset ) . $path );
        
        if ( $get === false ) echo $url;
        
        return $url;
    }
}

/**
 * Get Assets ( Having Client Files ) Path.
 *
 * @param  string $path
 * @return string
 */
if ( ! function_exists( 'get_assets_path' ) )
{
    function get_assets_path( $path )
    {
        $assets_path = sprintf( ASSETS_PATH, get_theme_name( false ) );
        
        return base_url( append_slash( $assets_path ) . $path );
    }
}

/**
 * Assets ( Having Client Files ) Path.
 *
 * @param  string $path
 * @return void
 */
if ( ! function_exists( 'assets_path' ) )
{
    function assets_path( $path )
    {
        echo get_assets_path( $path );
    }
}

/**
 * Get Knowledge Base Category Slug
 *
 * @param  string $parent
 * @param  string $child
 * @return string
 */
if ( ! function_exists( 'get_kb_category_slug' ) )
{
    function get_kb_category_slug( $parent, $child = '' )
    {
        $url = "knowledge-base/{$parent}";
        
        if ( ! empty( $child ) )
        {
            $url .= "/{$child}";
        }
        
        return $url;
    }
}

/**
 * Get Knowledge Base Article Slug
 *
 * @param  string $slug
 * @return string
 */
if ( ! function_exists( 'get_kb_article_slug' ) )
{
    function get_kb_article_slug( $slug )
    {
        return "knowledge-base/article/{$slug}";
    }
}

/**
 * Illustration by Color ( Settings ).
 *
 * @param  string $name
 * @return void
 */
if ( ! function_exists( 'illustration_by_color' ) )
{
    function illustration_by_color( $name )
    {
        $color = html_escape( db_config( 'site_color' ) );
        echo get_assets_path( "images/color_{$color}/{$name}.svg" );
    }
}

/**
 * Manage Title ( Appending Site Name ).
 *
 * @param  string $title
 * @return string
 */
if ( ! function_exists( 'manage_title' ) )
{
    function manage_title( $title )
    {
        if ( empty( $title ) ) return '';
        
        $site_name = db_config( 'site_name' );
        
        if ( ! empty( $site_name ) )
        {
            $title .= ' - ' . $site_name;
        }
        
        return $title;
    }
}

/**
 * Sub Title
 *
 * @param  string $parent
 * @param  string $text
 * @return string
 */
if ( ! function_exists( 'sub_title' ) )
{
    function sub_title( $parent, $text )
    {
        return $parent . ' › ' . $text;
    }
}

/**
 * Create Title
 *
 * @param  string $source ( e.g Controller Name )
 * @return string
 */
if ( ! function_exists( 'create_title' ) )
{
    function create_title( $source )
    {
        return ucwords( str_replace( '_', ' ', $source ) );
    }
}

/**
 * Is Public Page
 *
 * Use to verify is the requested page is public.
 *
 * @return boolean
 */
if ( ! function_exists( 'is_public_page' ) )
{
    function is_public_page()
    {
        $ci =& get_instance();
        
        $page = $ci->uri->segment( 1 );
        
        if ( in_array( $page, USER_PUBLIC_PAGES ) )
        {
            return true;
        }
        
        return false;
    }
}

/**
 * Is Actions URL
 *
 * Use to check the requested URL is the core URL
 * that's designed for the form/input handlings.
 *
 * @return boolean
 */
if ( ! function_exists( 'is_actions_url' ) )
{
    function is_actions_url()
    {
        $ci =& get_instance();
        
        $page = $ci->uri->segment( 1 );
        
        // Check if the first segment is "action" or the request is sent by POST or GET methods:
        if ( $page === 'actions' || ! empty( $_POST ) || array_key_exists( 'get_request_form', $_GET ) )
        {
            return true;
        }
        
        return false;
    }
}

/**
 * Get Body "<body>" Class(s) ( Panel Area ).
 *
 * @return string
 */
if ( ! function_exists( 'get_body_classes' ) )
{
    function get_body_classes()
    {
        $classes = 'sidebar-mini layout-fixed layout-navbar-fixed';
            
        if ( get_cookie( SIDEBAR_COOKIE ) == 1 )
        {
            $classes .= ' sidebar-collapse';
        }
        
        return $classes;
    }
}

/**
 * Get Panel Slugs.
 *
 * Use to get the defined panel(s) slugs according to
 * the visited area.
 *
 * @param  string  $key
 * @param  string  $area
 * @return array
 */
if ( ! function_exists( 'get_panel_slugs' ) )
{
    function get_panel_slugs( $key, $area )
    {
        $ci =& get_instance();
        $seg_1 = $ci->uri->segment( 1 );
        $seg_2 = $ci->uri->segment( 2 );
        
        if ( array_key_exists( $seg_1, PANEL_SLUGS ) )
        {
            if ( array_key_exists( $key, PANEL_SLUGS[$seg_1] ) )
            {
                if ( $seg_1 === $area && $key === $seg_2 )
                {
                    return PANEL_SLUGS[$seg_1][$key];
                }
            }
        }
        
        return [];
    }
}

/**
 * Panel Activate Parent Menu.
 *
 * Use to display as activated the parent menu.
 *
 * @param  string $container_key
 * @param  string $area
 * @param  string $class
 * @return string
 */
if ( ! function_exists( 'panel_activate_parent_menu' ) )
{
    function panel_activate_parent_menu( $container_key, $area = 'admin', $class = 'active' )
    {
        $ci =& get_instance();
        $slug = $ci->uri->segment( 3 );
        
        if ( in_array( $slug, get_panel_slugs( $container_key, $area ) ) )
        {
            return $class;
        }
    }
}

/**
 * Panel Open Parent Menu.
 *
 * Use to open ( expand ) the parent menu.
 *
 * @param  string $container_key
 * @param  string $area
 * @return string
 */
if ( ! function_exists( 'panel_open_parent_menu' ) )
{
    function panel_open_parent_menu( $container_key, $area = 'admin' )
    {
        return panel_activate_parent_menu( $container_key, $area, 'menu-open' );
    }
}

/**
 * Activate Page ( Depending on Segment ).
 *
 * Use to display as activated a menu item.
 *
 * @param  string|array $name    Page slug
 * @param  string       $area    Page parent area
 * @param  integer      $segment URL position
 * @param  string       $mm      Main module
 * @return string
 */
if ( ! function_exists( 'activate_page' ) )
{
    function activate_page( $name, $area = '', $segment = 1, $mm = '' )
    {
        $ci =& get_instance();
        $class = 'active';
        $slug = $ci->uri->segment( $segment );
        $seg_1 = $ci->uri->segment( 1 );
        $seg_2 = $ci->uri->segment( 2 );
        
        // If needed, pass area for the child page.
        // If needed, pass main module for the sub child page.
        
        if ( is_array( $name ) )
        {
            if ( ! empty( $area ) )
            {
                if ( in_array( $slug, $name ) && $seg_1 === $area )
                {
                    if ( ! empty( $mm ) && $mm != $seg_2 ) return '';
                    
                    return $class;
                }
            }
            else if ( in_array( $slug, $name ) )
            {
                return $class;
            }
        }
        
        if ( ! empty( $area ) )
        {
            if ( $slug === $name && $seg_1 === $area )
            {
                if ( ! empty( $mm ) && $mm != $seg_2 ) return '';
                
                return $class;
            }
        }
        else if ( $slug === $name )
        {
            return $class;
        }
    }
}

/**
 * Panel Activate Child Page.
 *
 * @param  string $name Page slug
 * @param  string $area
 * @return string
 */
if ( ! function_exists( 'panel_activate_child_page' ) )
{
    function panel_activate_child_page( $name, $area = 'admin' )
    {
        return activate_page( $name, $area, 2 );
    }
}

/**
 * User Panel Activate Child Page.
 *
 * @param  string $name Page slug
 * @return string
 */
if ( ! function_exists( 'user_panel_activate_child_page' ) )
{
    function user_panel_activate_child_page( $name )
    {
        return panel_activate_child_page( $name, 'user' );
    }
}

/**
 * Panel Activate Sub Child Page.
 *
 * @param  string $name Page slug
 * @param  string $area
 * @param  string $mm   Main module
 * @return string
 */
if ( ! function_exists( 'panel_activate_sub_child_page' ) )
{
    function panel_activate_sub_child_page( $name, $area = 'admin', $mm = '' )
    {
        return activate_page( $name, $area, 3, $mm );
    }
}

/**
 * User Panel Activate Sub Child Page.
 *
 * @param  string $name Page slug
 * @param  string $mm   Main module
 * @return string
 */
if ( ! function_exists( 'user_panel_activate_sub_child_page' ) )
{
    function user_panel_activate_sub_child_page( $name, $mm = '' )
    {
        return panel_activate_sub_child_page( $name, 'user', $mm );
    }
}

/**
 * Get Invitation Status
 *
 * @param  object $invitation
 * @return string
 */
if ( ! function_exists( 'get_invitation_status' ) )
{
    function get_invitation_status( $invitation )
    {
        $status = '';
        
        if ( is_object( $invitation ) )
        {
            if ( $invitation->status == 0 ) $status = 'unused';
            else if ( $invitation->status == 1 ) $status = 'used';
            else if ( $invitation->status == 2 ) $status = 'expired';
            
            if ( ! empty( $invitation->expires_in ) && $invitation->status != 1 )
            {
                // Adding expiration time at the same time as it was invited:
                // Expiry time management: Number of allowed hours * hour
                $expiry = $invitation->invited_at + ( $invitation->expires_in * 60 * 60 );
                
                if ( time() > $expiry )
                {
                    $status = 'expired';
                }
            }
        }
        
        return $status;
    }
}

/**
 * Load JavaScript Files
 *
 * @param  arary $sources
 * @return void
 */
if ( ! function_exists( 'load_scripts' ) )
{
    function load_scripts( $sources )
    {
        if ( is_array( $sources ) && isset( $sources ) )
        {
            foreach ( $sources as $source )
            {
                echo '<script src="' . $source . '"></script>';
            }
        }
    }
}

/**
 * Manage Updated At
 *
 * @param  integer $stamp
 * @return void
 */
if ( ! function_exists( 'manage_updated_at' ) )
{
    function manage_updated_at( $stamp )
    {
        if ( ! empty( $stamp ) )
        {
            echo get_date_time_by_timezone( $stamp );
        }
        else
        {
            echo lang( 'never_updated' );
        }
    }
}

/**
 * Avator File Choose Guide
 *
 * @return string
 */
if ( ! function_exists( 'avator_tip' ) )
{
    function avator_tip()
    {
        $size = db_config( 'u_max_avator_size' );
        $formats = str_replace( '|', ', ', ALLOWED_IMG_EXT );
        
        if ( $size != '' )
        {
            $tip = sprintf( lang( 'avatar_with_dim_tip' ), strtoupper( $formats ), $size );
        }
        else
        {
            $tip = sprintf( lang( 'avatar_tip' ), $formats );
        }
        
        return $tip;
    }
}

/**
 * Get Allowed IPs for Maintenance Mode.
 *
 * @param  string $addresses
 * @return array
 */
if ( ! function_exists( 'get_mm_allowed_ips' ) )
{
    function get_mm_allowed_ips( $addresses = '' )
    {
        $allowed = db_config( 'mm_allowed_ips' );
        
        if ( ! empty( $addresses ) ) $allowed = $addresses;
        
        if ( ! empty( $ips = $allowed ) )
        {
            return explode( PHP_EOL, $ips );
        }
        
        return [];
    }
}

/**
 * Maintenance Mode Check
 *
 * @return void
 */
if ( ! function_exists( 'mm_check' ) )
{
    function mm_check()
    {
        $ci =& get_instance();
        
        // Helpful to allow access to the super admin users also in case if the
        // single IP address is added and that's changed for any reason.
        
        if ( db_config( 'maintenance_mode' ) && $ci->zuser->get( 'role' ) != 1 )
        {
            $ip_address = $ci->input->ip_address();
            $allowed_ips = get_mm_allowed_ips();
            $sanitized_ips = [];
            
            if ( ! empty( $allowed_ips ) )
            {
                foreach ( $allowed_ips as $allowed_ip )
                {
                    if ( ! empty( $allowed_ip ) )
                    {
                        $sanitized_ips[] = trim( str_replace( array( "\n", "\r" ), '', $allowed_ip ) );
                    }
                }
            }
            
            if ( ! in_array( $ip_address, $sanitized_ips ) )
            {
                display_view( 'common/maintenance' );
            }
        }
        else if ( db_config( 'i_pc_status' ) !== null )
        {
            if ( db_config( 'i_pc_status' ) == 3 )
            {
                display_view( 'common/maintenance' );
            }
        }
        
        if ( function_exists( 'setup_app' ) ) setup_app();
    }
}

/**
 * Is Valid Access Key ( Format ).
 *
 * @param  string $key
 * @return integer
 */
if ( ! function_exists( 'is_valid_access_key' ) )
{
    function is_valid_access_key( $key )
    {
        return preg_match( '/^[a-zA-Z_\.]+$/', $key );
    }
}

/**
 * Get Offset
 *
 * @param  integer $per_page
 * @param  integer $seg
 * @return integer
 */
if ( ! function_exists( 'get_offset' ) )
{
    function get_offset( $per_page, $seg = 4 )
    {
        $ci =& get_instance();
        
        if ( ! empty( $ci->uri->segment( $seg ) ) )
        {
            return ( $ci->uri->segment( $seg ) - 1 ) * $per_page;
        }
        
        return 0;
    }
}
