<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * General Helper ( Generally Used Functions ).
 *
 * @author Shahzaib
 */


/**
 * Success Language Translation
 *
 * @param   string $key
 * @return  string
 * @version 1.6
 */
if ( ! function_exists( 'suc_lang' ) )
{
    function suc_lang( $key )
    {
        return lang( 'suc_' . $key );
    }
}

/**
 * Error Language Translation
 *
 * @param   string $key
 * @return  string
 * @version 1.5
 */
if ( ! function_exists( 'err_lang' ) )
{
    function err_lang( $key )
    {
        return lang( 'err_' . $key );
    }
}

/**
 * Get Time by Timezone
 *
 * @param   integer $stamp
 * @return  string
 * @version 1.4
 */
if ( ! function_exists( 'get_time_by_timezone' ) )
{
    function get_time_by_timezone( $stamp )
    {
        $ci =& get_instance();
        
        $timezone = db_config( 'site_timezone' );
        $format = 'H:i:s';
        
        if ( $ci->zuser->is_logged_in )
        {
            if ( ! empty( $ci->zuser->get( 'timezone' ) ) )
            {
                $timezone = $ci->zuser->get( 'timezone' );
            }
            
            $format = $ci->zuser->get( 'time_format' );
        }
        
        if ( ! empty( $timezone ) )
        {
            date_default_timezone_set( $timezone );
        }
        
        return date( $format, $stamp );
    }
}

/**
 * Replace Some with Actual Special Character(s)
 *
 * @param   string $text
 * @return  string
 * @version 1.3
 */
if ( ! function_exists( 'replace_some_with_actuals' ) )
{
    function replace_some_with_actuals( $text )
    {
        $string = str_replace( '&amp;lt;', '&lt;', $text );
        $string = str_replace( '&amp;gt;', '&gt;', $string );
        
        return $string;
    }
}

/**
 * Select of $_GET
 *
 * @param   string         $name
 * @param   string|integer $value
 * @return  string
 * @version 1.3
 */
if ( ! function_exists( 'select_by_get' ) )
{
    function select_of_get( $name, $value )
    {
        return select_single( do_secure( get( $name ) ), $value );
    }
}

/**
 * Long to Short File Name
 *
 * @param   string $name
 * @return  string
 * @version 1.3
 */
if ( ! function_exists( 'long_to_short_name' ) )
{
    function long_to_short_name( $name )
    {
        if ( mb_strlen( $name ) >= 25 )
        {
            return mb_substr( $name, 0, 10 ) . '...' . mb_substr( $name, -10 );
        }
        
        return $name;
    }
}

/**
 * Is Image File
 *
 * @param   string $file
 * @return  boolean
 * @version 1.1
 */
if ( ! function_exists( 'is_image_file' ) )
{
    function is_image_file( $file )
    {
        $status = false;
        
        if ( ! empty( $ext = pathinfo( $file, PATHINFO_EXTENSION ) ) )
        {
            $status = in_array( strtolower( $ext ), ALLOWED_IMG_EXT_ARRAY );
        }
        
        return $status;
    }
}

/**
 * Full URL
 *
 * Use to get the full URL with query strings.
 *
 * @return string
 */
if ( ! function_exists( 'full_url' ) )
{
    function full_url()
    {
        $ci =& get_instance();
        $url = uri_string();
        
        if ( ! empty( $_SERVER['QUERY_STRING'] ) )
        {
            return $url .= '?' . $_SERVER['QUERY_STRING'];
        }
        
        return $url;
    }
}

/**
 * Is Mod Rewrite Module Enabled
 *
 * @return boolean
 */
if ( ! function_exists( 'is_mod_rewrite_enabled' ) )
{
    function is_mod_rewrite_enabled()
    {
        $status = false;
        
        if ( function_exists( 'apache_get_modules' ) )
        {
            $status = in_array( 'mod_rewrite', apache_get_modules() );
        }
        
        return $status;
    }
}

/**
 * Environment URL
 *
 * @param  string $slug
 * @return string 
 */
if ( ! function_exists( 'env_url' ) )
{
    function env_url( $slug = '' )
    {
        $base_url = base_url();
        
        if ( ! is_mod_rewrite_enabled() )
        {
            $base_url .= 'index.php/';
        }
        
        $base_url .= $slug;
        
        return $base_url;
    }
}

/**
 * Redirect Based on Environment URL
 *
 * @param  string $slug
 * @return void
 */
if ( ! function_exists( 'env_redirect' ) )
{
    function env_redirect( $slug = '' )
    {
        $slug = ( ! empty( $slug ) ) ? env_url( $slug ) : '';
        redirect( $slug );
        exit;
    }
}

/**
 * Is Alpha Numeric
 *
 * Use to verify is the passed string is containing
 * only space(s), underscore and alpha numeric chars.
 *
 * @param  string $text
 * @return integer
 */
if ( ! function_exists( 'is_alpha_numeric' ) )
{
    function is_alpha_numeric( $text )
    {
        return preg_match( '/^[\w ]+$/i', $text );
    }
}

/**
 * Get Uploaded Path ( File ).
 *
 * Use to get the uploaded file path with base URL.
 *
 * @param  string $directory
 * @param  string $file
 * @return string
 */
if ( ! function_exists( 'get_uploaded_path' ) )
{
    function get_uploaded_path( $directory, $file )
    {
        return base_url( append_slash( IMG_UPLOADS_DIR ) . "{$directory}/{$file}" );
    }
}

/**
 * General Uploads ( Path ).
 *
 * Use to get the path of "general/" uploads directory.
 *
 * @param  string $file
 * @return string
 */
if ( ! function_exists( 'general_uploads' ) )
{
    function general_uploads( $file )
    {
        return get_uploaded_path( 'general', $file );
    }
}

/**
 * Users Uploads ( Path ).
 *
 * Use to get the path of "users/" uploads directory.
 *
 * @param  string $file
 * @return string
 */
if ( ! function_exists( 'users_uploads' ) )
{
    function users_uploads( $file )
    {
        return get_uploaded_path( 'users', $file );
    }
}

/**
 * Attachments Uploads ( Path ).
 *
 * Use to get the path of "attachments/" uploads directory.
 *
 * @param  string $file
 * @return string
 */
if ( ! function_exists( 'attachments_uploads' ) )
{
    function attachments_uploads( $file )
    {
        return get_uploaded_path( 'attachments', $file );
    }
}

/**
 * User Picture ( Path Management ).
 *
 * @param  string $ref
 * @return string
 */
if ( ! function_exists( 'user_picture' ) )
{
    function user_picture( $ref )
    {
        if ( ! filter_var( $ref, FILTER_VALIDATE_URL ) )
        {
            return users_uploads( $ref );
        }
        
        return $ref;
    }
}

/**
 * Admin Action ( Full Path ).
 *
 * Use to display the admin actions URL.
 *
 * @param  string $path
 * @return void
 */
if ( ! function_exists( 'admin_action' ) )
{
    function admin_action( $path )
    {
         echo env_url( append_slash( ADMIN_ACTIONS ) . $path );
    }
}

/**
 * User Action ( Full Path ).
 *
 * Use to display the user actions URL.
 *
 * @param  string $path
 * @return void
 */
if ( ! function_exists( 'user_action' ) )
{
    function user_action( $path )
    {
         echo env_url( append_slash( USER_ACTIONS ) . $path );
    }
}

/**
 * Dump and Die
 *
 * @param  mixed $value
 * @return void
 */
if ( ! function_exists( 'dd' ) )
{
    function dd( $value )
    {
        echo '<pre>';
        var_dump( $value );
        exit;
    }
}

/**
 * Append Slash
 *
 * Use with URL or path based string.
 *
 * @param  string $url
 * @return string
 */
if ( ! function_exists( 'append_slash' ) )
{
    function append_slash( $url )
    {
        if ( substr( $url, -1 ) !== '/' )
        {
            $url .= '/';
        }
        
        return $url;
    }
}

/**
 * Set Settings
 *
 * Set the database settings as CI configuration with the prefix "db_".
 *
 * @return void
 */
if ( ! function_exists( 'set_settings' ) )
{
    function set_settings()
    {
        $ci =& get_instance();
        $data = $ci->Setting_model->get_managed_options();
        
        foreach ( $data as $key => $value )
        {
            $ci->config->set_item( 'db_' . $key, $value );
        }
    }
}

/**
 * Get Settings ( From Database ).
 *
 * @return array
 */
if ( ! function_exists( 'get_settings' ) )
{
    function get_settings()
    {
        $ci =& get_instance();
        return $ci->Setting_model->get_managed_options();
    }
}

/**
 * DB Config
 *
 * Use to get the configuration value that is stored in database.
 *
 * @param  string $key
 * @return string
 */
if ( ! function_exists( 'db_config' ) )
{
    function db_config( $key )
    {
        return config_item( "db_{$key}" );
    }
}

/**
 * Get Language
 *
 * Use to get the selected language name ( key ).
 *
 * @return string
 */
if ( ! function_exists( 'get_language' ) )
{
    function get_language()
    {
        $lang_key = get_cookie( LANG_COOKIE );
        
        if ( ! empty( $lang_key ) )
        {
            if ( array_key_exists( $lang_key, AVAILABLE_LANGUAGES ) )
            {
                return $lang_key;
            }
        }
        
        return config_item( 'language' );
    }
}

/**
 * Get Language Label
 *
 * @param  string $lang_key
 * @return string
 */
if ( ! function_exists( 'get_language_label' ) )
{
    function get_language_label( $lang_key )
    {
        return AVAILABLE_LANGUAGES[$lang_key]['display_label'];
    }
}

/**
 * Set Session
 *
 *
 * @param  string $key
 * @param  mixed  $value
 * @return void
 */
if ( ! function_exists( 'set_session' ) )
{
    function set_session( $key, $value )
    {
        $ci =& get_instance();
        $ci->session->set_userdata( $key, $value );
    }
}

/**
 * Unset Session
 *
 * @param  string $key
 * @return void
 */
if ( ! function_exists( 'unset_session' ) )
{
    function unset_session( $key )
    {
        $ci =& get_instance();
        $ci->session->unset_userdata( $key );
    }
}

/**
 * Get Session
 *
 * Use to get the value stored in session.
 *
 * @param  string $key
 * @return string
 */
if ( ! function_exists( 'get_session' ) )
{
    function get_session( $key )
    {
        $ci =& get_instance();
        return $ci->session->userdata( $key );
    }
}

/**
 * Set Flash Data
 *
 * @param  string         $key
 * @param  string|integer $value
 * @return void
 */
if ( ! function_exists( 'set_flashdata' ) )
{
    function set_flashdata( $key, $value )
    {
        $ci =& get_instance();
        $ci->session->set_flashdata( $key, $value );
    }
}

/**
 * Get Flash Data
 *
 * Use to get the temporary stored session data.
 *
 * @param  string $key
 * @return string
 */
if ( ! function_exists( 'get_flashdata' ) )
{
    function get_flashdata( $key )
    {
        $ci =& get_instance();
        return $ci->session->flashdata( $key );
    }
}

/**
 * Set Success Flash
 *
 * Pass success messages language key without "suc_" prefix
 * if the $type value is "lang".
 *
 * @param  string|integer $value
 * @param  string         $type
 * @return void
 */
if ( ! function_exists( 'set_success_flash' ) )
{
    function set_success_flash( $value, $type = 'lang' )
    {
        if ( $type === 'lang' )
        {
            set_flashdata( 'success', suc_lang( $value ) );
        }
        else
        {
            set_flashdata( 'success', $value );
        }
    }
}

/**
 * Set Error Flash
 *
 * Pass error messages language key without "err_" prefix
 * if the $type value is "lang".
 *
 * @param  string|integer $value 
 * @param  string         $type
 * @return void
 */
if ( ! function_exists( 'set_error_flash' ) )
{
    function set_error_flash( $value, $type = 'lang' )
    {
        if ( $type === 'lang' )
        {
            set_flashdata( 'error', err_lang( $value ) );
        }
        else if ( $type === 'direct' )
        {
            set_flashdata( 'error', $value );
        }
    }
}

/**
 * Get Related Dashboard ( Path )
 * 
 * @return string
 */
if ( ! function_exists( 'get_related_dashboard' ) )
{
    function get_related_dashboard()
    {
        $ci =& get_instance();

        if ( $ci->zuser->is_team_member() )
        {
            return 'admin/dashboard';
        }

        return 'dashboard';
    }
}

/**
 * No Permission Redirect
 *
 * @return void
 */
if ( ! function_exists( 'no_permission_redirect' ) )
{
    function no_permission_redirect()
    {
        set_flashdata( 'error', NO_PERMISSION_MSG );
        env_redirect( get_related_dashboard() );
        exit;
    }
}

/**
 * Check Page Authorization
 *
 * @param  string $key
 * @return void
 */
if ( ! function_exists( 'check_page_authorization' ) )
{
    function check_page_authorization( $key )
    {
        $ci =& get_instance();
        if ( ! $ci->zuser->has_permission( $key ) ) no_permission_redirect();
    }
}

/**
 * Check Action Authorization
 *
 * @param  string $key
 * @return void
 */
if ( ! function_exists( 'check_action_authorization' ) )
{
    function check_action_authorization( $key )
    {
        $ci =& get_instance();
        if ( ! $ci->zuser->has_permission( $key ) ) r_error_no_permission();
    }
}

/**
 * Success Redirect
 *
 * @param  string $key
 * @param  string $path
 * @return void
 */
if ( ! function_exists( 'success_redirect' ) )
{
    function success_redirect( $key, $path = '' )
    {
        set_success_flash( $key );
        env_redirect( $path );
        exit;
    }
}

/**
 * Error Redirect
 *
 * @param  string $key
 * @param  string $path
 * @return void
 */
if ( ! function_exists( 'error_redirect' ) )
{
    function error_redirect( $key, $path = '' )
    {
        set_error_flash( $key );
        env_redirect( $path );
        exit;
    }
}

/**
 * Alert Message ( Temporary, Based on Session Type ).
 *
 * @param  string $suc_key
 * @param  string $err_key
 * @return string HTML
 */
if ( ! function_exists( 'alert_message' ) )
{
    function alert_message( $suc_key = 'alert-success', $err_key = 'alert-danger' )
    {
        $html = '';
        
        if ( get_flashdata( 'success' ) )
        {
            $html .= '<div class="alert ' . $suc_key . '" role="alert">';
            $html .=  get_flashdata( 'success' );
            $html .=  '</div>';
        }
        else if ( get_flashdata( 'error' ) )
        {
            $html .= '<div class="alert ' . $err_key . '" role="alert">';
            $html .=  get_flashdata( 'error' );
            $html .=  '</div>';
        }
        
        return $html;
    }
}

/**
 * Is Having Array
 *
 * Use to check if the array is having sub array.
 *
 * @param  array $arary
 * @return boolean
 */
if ( ! function_exists( 'is_having_array' ) )
{
    function is_having_array( $array )
    {
        foreach ( $array as $data )
        {
            if ( is_array( $data ) )
            {
                return true;
            }
        }
        
        return false;
    }
}

/**
 * Get Site Date
 *
 * Use to get the current date as 24 hours format using the site timezone.
 *
 * @param  string $format
 * @param  string $tz_support Pass timezone in case if it's calling where the db_config is not supported.
 * @return string
 */
if ( ! function_exists( 'get_site_date' ) )
{
    function get_site_date( $format = '', $tz_support = '' )
    {
        if ( empty( $tz_support ) )
        {
            $timezone = db_config( 'site_timezone' );
        }
        else
        {
            $timezone = $tz_support;
        }
        
        if ( ! empty( $timezone ) )
        {
            date_default_timezone_set( $timezone );
        }
        
        if ( empty( $format ) )
        {
            return date( STATIC_DATE_FORMAT );
        }
        
        return date( $format );
    }
}

/**
 * Get Custom Format Date by User Timezone.
 *
 * @param  string  $format
 * @param  integer $stamp
 * @return string
 */
if ( ! function_exists( 'get_cf_date_by_user_timezone' ) )
{
    function get_cf_date_by_user_timezone( $format, $stamp = 0 )
    {
        $ci =& get_instance();
        
        if ( ! empty( $ci->zuser->get( 'timezone' ) ) )
        {
            $timezone = $ci->zuser->get( 'timezone' );
        }
        
        if ( ! empty( $timezone ) )
        {
            date_default_timezone_set( $timezone );
        }
        
        if ( ! empty( $stamp ) )
        {
            return date( $format, $stamp );
        }
        
        return date( $format );
    }
}

/**
 * Get User Timezone
 *
 * @param  boolean $for_sql
 * @return string
 */
if ( ! function_exists( 'get_user_timezone_format' ) )
{
    function get_user_timezone_format( $for_sql )
    {
        $ci =& get_instance();
        
        $format = STATIC_DATE_FORMAT;
        
        if ( $ci->zuser->is_logged_in && $for_sql === false )
        {
            $format = $ci->zuser->get( 'date_format' );
        }
        
        return $format;
    }
}

/**
 * Get Date and Time by Timezone
 *
 * @param  integer $stamp
 * @param  boolean $date_only
 * @param  boolean $for_sql
 * @return string
 */
if ( ! function_exists( 'get_date_time_by_timezone' ) )
{
    function get_date_time_by_timezone( $stamp, $date_only = false, $for_sql = false )
    {
        $ci =& get_instance();
        
        $timezone = db_config( 'site_timezone' );
        $d_format = get_user_timezone_format( $for_sql );
        $t_format = 'H:i:s';
        
        if ( $ci->zuser->is_logged_in )
        {
            if ( ! empty( $ci->zuser->get( 'timezone' ) ) )
            {
                $timezone = $ci->zuser->get( 'timezone' );
            }
            
            $t_format = $ci->zuser->get( 'time_format' );
        }
        
        if ( $date_only === false )
        {
            $f_format = "{$d_format} {$t_format}";
        }
        else
        {
            $f_format = $d_format;
        }
        
        if ( ! empty( $timezone ) )
        {
            date_default_timezone_set( $timezone );
        }
        
        return date( $f_format, $stamp );
    }
}

/**
 * Subtract Time Period from Current Time.
 *
 * @param  string $period
 * @return integer
 */
if ( ! function_exists( 'subtract_time' ) )
{
    function subtract_time( $period )
    {
        return strtotime( '-' . $period, time() );
    }
}

/**
 * Get User Closer Language
 *
 * @param  string $language
 * @return string
 */
if ( ! function_exists( 'get_user_closer_language' ) )
{
    function get_user_closer_language( $language = '' )
    {
        if ( ! empty( $language ) )
        {
            return $language;
        }
        
        return config_item( 'language' );
    }
}

/**
 * Get Long Random String
 *
 * @return string
 */
if ( ! function_exists( 'get_long_random_string' ) )
{
    function get_long_random_string()
    {
        if ( ! version_compare( PHP_VERSION, '7.0.0', '>=' ) )
        {
            $key = random_bytes( 16 );
            $key = bin2hex( $key );
        }
        else
        {
            $key = md5( rand() );
        }

        $key .= sha1( time() * mt_rand() );
        $key = uniqid( "{$key}" );

        return $key;
    }
}

/**
 * Get Short Random String ( MD5 Based ).
 *
 * @param  string|integer $extra
 * @return string
 */
if ( ! function_exists( 'get_short_random_string' ) )
{
    function get_short_random_string( $extra = '' )
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $string = substr( str_shuffle( $chars ), 0, 10 );
        $string .= time() + rand();
        
        if ( ! empty( $extra ) )
        {
            $string .= $extra;
        }
        
        return md5( $string );
    }
}

/**
 * Is Increased Length
 *
 * Use to verify the length of given string.
 *
 * @param  string  $text
 * @param  integer $length
 * @return boolean
 */
if ( ! function_exists( 'is_increased_length' ) )
{
    function is_increased_length( $text, $length = 90 )
    {
        return mb_strlen( $text ) > $length;
    }
}

/**
 * Get Sized String
 *
 * Use to get the string characters base on the given length.
 *
 * @param  string  $text
 * @param  integer $length
 * @param  boolean $dots 
 * @return string
 */
if ( ! function_exists( 'get_sized_text' ) )
{
    function get_sized_text( $text, $length = 90, $dots = false )
    {
        $str = mb_substr( $text, 0, $length );
        
        if ( $dots && is_increased_length( $text, $length ) ) $str .= '...';
        
        return $str;
    }
}

/**
 * Is Increased Short Text
 *
 * Use to check the short text length, is increased or not.
 *
 * @param  string $text
 * @return boolean
 */
if ( ! function_exists( 'is_increased_short_text' ) )
{
    function is_increased_short_text( $text )
    {
        return is_increased_length( $text, 30 );
    }
}

/**
 * Short Text
 *
 * Use to make the longer text shorter.
 *
 * @param  string $text
 * @return string
 */
if ( ! function_exists( 'short_text' ) )
{
    function short_text( $text )
    {
        return get_sized_text( $text, 30 );
    }
}

/**
 * Replace Placeholders
 *
 * @param  string $template
 * @param  array  $placeholders
 * @return string
 */
if ( ! function_exists( 'replace_placeholders' ) )
{
    function replace_placeholders( $template, $placeholders )
    {
        foreach ( $placeholders as $placeholder => $value )
        {
            $template = str_replace( $placeholder, $value, $template );
        }
        
        return $template;
    }
}

/**
 * Is Email Settings Filled
 *
 * Use to check if the Email settings are filled or not.
 * 
 * @return boolean
 */
if ( ! function_exists( 'is_email_settings_filled' ) )
{
    function is_email_settings_filled()
    {
        if ( db_config( 'e_protocol' ) == 'smtp' )
        {
            if ( ! empty( db_config( 'e_sender' ) ) &&
                 ! empty( db_config( 'e_sender_name' ) ) &&
                 ! empty( db_config( 'e_host' ) ) &&
                 ! empty( db_config( 'e_username' ) ) &&
                 ! empty( db_config( 'e_password' ) ) &&
                 ! empty( db_config( 'e_port' ) ) )
                return true;
        }
        else
        {
            if ( ! empty( db_config( 'e_sender' ) ) &&
                 ! empty( db_config( 'e_sender_name' ) ) )
                return true;
        }
        
        return false;
    }
}

/**
 * Is Admin Panel Allowed
 *
 * @return boolean
 */
if ( ! function_exists( 'is_admin_panel_allowed' ) )
{
    function is_admin_panel_allowed()
    {
        $ci =& get_instance();
        
        if ( $ci->zuser->has_permission( 'canned_replies' ) ||
             $ci->zuser->has_permission( 'tickets' ) ||
             $ci->zuser->has_permission( 'chats' ) ||
             $ci->zuser->has_permission( 'departments' ) ||
             $ci->zuser->has_permission( 'knowledge_base' ) ||
             $ci->zuser->has_permission( 'faqs' ) ||
             $ci->zuser->has_permission( 'announcements' ) ||
             $ci->zuser->has_permission( 'backup' ) ||
             $ci->zuser->has_permission( 'email_templates' ) ||
             $ci->zuser->has_permission( 'pages' ) ||
             $ci->zuser->has_permission( 'impersonate' ) ||
             $ci->zuser->has_permission( 'users' ) ||
             $ci->zuser->has_permission( 'roles_and_permissions' ) ||
             $ci->zuser->has_permission( 'settings' ) )
            return true;
        
        return false;
    }
}

/**
 * Is Others Modules Allowed
 * 
 * Use to check the permissions of module(s) that
 * are listed under the "Others" in the sidebar.
 * 
 * @return boolean
 */
if ( ! function_exists( 'is_others_modules_allowed' ) )
{
    function is_others_modules_allowed()
    {
        $ci =& get_instance();
            
        if ( $ci->zuser->has_permission( 'knowledge_base' ) ||
             $ci->zuser->has_permission( 'faqs' ) ||
             $ci->zuser->has_permission( 'announcements' ) ||
             $ci->zuser->has_permission( 'backup' ) ||
             $ci->zuser->has_permission( 'email_templates' ) ||
             $ci->zuser->has_permission( 'pages' ) ||
             $ci->zuser->has_permission( 'impersonate' ) ||
             $ci->zuser->has_permission( 'users' ) ||
             $ci->zuser->has_permission( 'roles_and_permissions' ) ||
             $ci->zuser->has_permission( 'settings' ) )
            return true;
        
        return false;
    }
}

/**
 * Is Facebook Login Togo
 *
 * Use to check if Facebook login API keys are added
 * and enabled in the configuration.
 *
 * @return boolean
 */
if ( ! function_exists( 'is_fb_togo' ) )
{
    function is_fb_togo()
    {
        if ( ! empty( db_config( 'fb_app_id' ) ) &&
             ! empty( db_config( 'fb_app_secret' ) ) &&
               db_config( 'fb_enable_login' ) == 1 )
            return true;
        
        return false;
    }
}

/**
 * Is Twitter Login Togo
 *
 * Use to check if Twitter login API keys are added
 * and enabled in the configuration.
 *
 * @return boolean
 */
if ( ! function_exists( 'is_tw_togo' ) )
{
    function is_tw_togo()
    {
        if ( ! empty( db_config( 'tw_consumer_key' ) ) &&
             ! empty( db_config( 'tw_consumer_secret' ) ) &&
               db_config( 'tw_enable_login' ) == 1 )
            return true;
        
        return false;
    }
}

/**
 * Is Google Login Togo
 *
 * Use to check if Google login API keys are added
 * and enabled in the configuration.
 *
 * @return boolean
 */
if ( ! function_exists( 'is_gl_togo' ) )
{
    function is_gl_togo()
    {
        if ( ! empty( db_config( 'gl_client_key' ) ) &&
             ! empty( db_config( 'gl_secret_key' ) ) &&
               db_config( 'gl_enable' ) == 1 )
            return true;
        
        return false;
    }
}

/**
 * Is Google reCaptcha Togo
 *
 * Use to check if Google reCaptcha API keys are added
 * and enabled in the configuration.
 *
 * @return boolean
 */
if ( ! function_exists( 'is_gr_togo' ) )
{
    function is_gr_togo()
    {
        if ( ! empty( db_config( 'gr_public_key' ) ) &&
             ! empty( db_config( 'gr_secret_key' ) ) &&
               db_config( 'gr_enable' ) == 1 )
            return true;
        
        return false;
    }
}

/**
 * Submit Google reCaptcha
 *
 * Use to send the request to google and receive its response. Use with that
 * form that is having the google recaptcha plugin.
 *
 * @return boolean
 */
if ( ! function_exists( 'submit_gr' ) )
{
    function submit_gr()
    {
        $status = true;
        
        if ( is_gr_togo() )
        {
            if ( ! post( 'g-recaptcha-response' ) )
            {
                return false;
            }
        
            $handler = curl_init();
            curl_setopt( $handler, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $handler, CURLOPT_POST, true );
            curl_setopt( $handler, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify' );
            
            curl_setopt( $handler, CURLOPT_POSTFIELDS,
            [
                'response' => do_secure( post( 'g-recaptcha-response' ) ),
                'secret' => db_config( 'gr_secret_key' )
            ]);
            
            $response = curl_exec( $handler );
            $response = json_decode( $response );
            curl_close( $handler );
            
            if ( ! isset( $response->success ) ) $status = false;
        }
        
        return $status;
    }
}

/**
 * Send Notification
 *
 * Use to insert the notification details in database.
 *
 * @param  string  $key Language key without "notify_" prefix.
 * @param  string  $location
 * @param  integer $user_id
 * @param  integer $for_team_member Only 0, 1 
 * @return void
 */
if ( ! function_exists( 'send_notification' ) )
{
    function send_notification( $key, $location, $user_id, $for_team_member = 0 )
    {
        $ci =& get_instance();
        $ci->Notification_model->send_notification( 'notify_' . $key, $location, $user_id, $for_team_member );
    }
}

/**
 * Make Text Links
 *
 * @param  string $text
 * @return string
 */
if ( ! function_exists( 'make_text_links' ) )
{
    function make_text_links( $text )
    {
        return preg_replace( '"\b(https?:\/\/\S+)"', '<a href="$1" target="_blank">$1</a>', $text );
    }
}

/**
 * Check Single
 *
 * Use to add the "checked" attribute on the radio and checkbox inputs.
 *
 * @param  mixed $value1
 * @param  mixed $value2
 * @return string
 */
if ( ! function_exists( 'check_single' ) )
{
    function check_single( $value1, $value2 )
    {
        if ( $value1 == $value2 )
        {
            return 'checked="checked"';
        }
    }
}

/**
 * Check Single by Array
 *
 * @param  mixed $value
 * @param  array $values
 * @return string 
 */
if ( ! function_exists( 'check_single_by_array' ) )
{
    function check_single_by_array( $value, $values )
    {
        $values = array_map( 'trim', $values );
        
        if ( in_array( $value, $values ) )
        {
            return 'checked="checked"';
        }
    }
}

/**
 * Check Single Switch ( Toggle Button ).
 *
 * @param  string|integer $value
 * @return string
 */
if ( ! function_exists( 'check_single_switch' ) )
{
    function check_single_switch( $value )
    {
        return checked_single( 1, $value );
    }
}

/**
 * Select Single
 *
 * Use to add the "selected" attribute on "<option>" element.
 *
 * @param  mixed $value1
 * @param  mixed $value2
 * @return string
 */
if ( ! function_exists( 'select_single' ) )
{
    function select_single( $value1, $value2 )
    {
        if ( $value1 == $value2 )
        {
            return 'selected="selected"';
        }
    }
}

/**
 * Select User Filter
 *
 * @param  string $value
 * @return string
 */
if ( ! function_exists( 'select_user_filter' ) )
{
    function select_user_filter( $value )
    {
        return select_of_get( 'filter', $value );
    }
}

/**
 * Select User Role
 *
 * @param  string $value
 * @return string
 */
if ( ! function_exists( 'select_user_role' ) )
{
    function select_user_role( $value )
    {
        $role = do_secure( get( 'role' ) );
        
        return select_single( $role, $value );
    }
}

/**
 * Send GET cURL Request
 *
 * @param  string $url
 * @return mixed
 */
if ( ! function_exists( 'send_get_curl' ) )
{
    function send_get_curl( $url )
    {
        $ch = curl_init();
        
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        
        $response = curl_exec( $ch );
        
        if ( ! empty( $response ) )
        {
            return $response;
        }
        
        return '';
    }
}

/**
 * Is Internet Connected
 *
 * @return boolean
 */
if ( ! function_exists( 'is_internet_connected' ) )
{
    function is_internet_connected()
    {
        $is_opened = @fsockopen( 'www.google.com', 80 );
        
        if ( $is_opened )
        {
            @fclose( $is_opened );
            
            return true;
        }
        
        return false;
    }
}

/**
 * Is Localhost Server
 *
 * @return boolean
 */
if ( ! function_exists( 'is_localhost' ) )
{
    function is_localhost()
    {
        $whitelist = ['127.0.0.1', '::1'];
        
        if ( in_array( @$_SERVER['REMOTE_ADDR'], $whitelist ) )
        {
            return true;
        }
        
        return false;
    }
}
