<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Response Helper
 *
 * @author Shahzaib
 */


/**
 * Goto Referer
 *
 * Use to go back to the requester page if the request is
 * sent through a non-ajax source.
 *
 * @param  string  $value
 * @param  boolean $type
 * @param  boolean $direct
 * @return void
 */
if ( ! function_exists( 'r_go_to_referer' ) )
{
    function r_go_to_referer( $value, $type = true, $direct = false )
    {
        if ( ! empty( $_SERVER['HTTP_REFERER'] ) )
        {
            if ( $type === false )
            {
                if ( $direct === true )
                {
                    set_error_flash( $value, 'direct' );
                }
                else
                {
                    set_error_flash( $value );
                }
            }
            else
            {
                if ( $direct === true )
                {
                    set_success_flash( $value, 'direct' );
                }
                else
                {
                    set_success_flash( $value );
                }
            }
            
            redirect( $_SERVER['HTTP_REFERER'] );
            
            exit;
        }
    }
}

/**
 * Get JSON Response
 *
 * Use to send a response message for the ajax request.
 *
 * @param  string         $status
 * @param  string|integer $value  String to use as response
 * @param  array          $extras Append new indexes
 * @return string JSON
 */
if ( ! function_exists( 'get_json_response' ) )
{
    function get_json_response( $status, $value, array $extras = [] )
    {
        $data = ['value' => $value, 'status' => $status];
        
        if ( ! empty( $extras ) )
        {
            foreach ( $extras as $key => $extra )
            {
                $data[$key] = $extra;
            }
        }
        
        return json_encode( $data );
    }
}

/**
 * Close Modal
 *
 * Use to send a response that will close the modal.
 *
 * @param   string $class
 * @return  void
 * @version 1.4
 */
if ( ! function_exists( 'r_close_modal' ) )
{
    function r_close_modal( $class = '.modal' )
    {
        exit( get_json_response( 'close_modal', $class ) );
    }
}

/**
 * Reset Form
 *
 * Use to send a response that will reset the requesting form.
 *
 * @return  void
 * @version 1.4
 */
if ( ! function_exists( 'r_reset_form' ) )
{
    function r_reset_form()
    {
        exit( get_json_response( 'reset_form', '' ) );
    }
}

/**
 * User Chat Starting Response
 *
 * @param   array $data
 * @return  void
 * @version 1.4
 */
if ( ! function_exists( 'r_user_chat_starting' ) )
{
    function r_user_chat_starting( $data )
    {
        exit( get_json_response( 'user_chat_starting', $data ) );
    }
}

/**
 * Admin Chat Replies Response
 *
 * @param   array $data
 * @return  void
 * @version 1.4
 */
if ( ! function_exists( 'r_admin_chat_replies' ) )
{
    function r_admin_chat_replies( $data )
    {
        exit( get_json_response( 'admin_chat_replies', $data ) );
    }
}

/**
 * User Chat Replies Response
 *
 * @param   string $data
 * @return  void
 * @version 1.4
 */
if ( ! function_exists( 'r_user_chat_replies' ) )
{
    function r_user_chat_replies( $data )
    {
        exit( get_json_response( 'user_chat_replies', $data ) );
    }
}

/**
 * User Chat Ending Response
 *
 * @param   string $data
 * @return  void
 * @version 1.4
 */
if ( ! function_exists( 'r_user_chat_ending' ) )
{
    function r_user_chat_ending( $data )
    {
        exit( get_json_response( 'user_chat_ending', $data ) );
    }
}

/**
 * Response Refresh
 *
 * Use to refresh the page after successful response.
 *
 * @param  string $key Success messages language key without "suc_" prefix.
 * @return void
 */
if ( ! function_exists( 'r_s_refresh' ) )
{
    function r_s_refresh( $key = '' )
    {
        if ( ! empty( $key ) ) set_success_flash( $key );
        
        exit( get_json_response( 'refresh', '' ) );
    }
}

/**
 * Response Error
 *
 * Use to send a error response.
 *
 * @param  string $key Error messages language key without "err_" prefix.
 * @return void
 */
if ( ! function_exists( 'r_error' ) )
{
    function r_error( $key )
    {
        $ci =& get_instance();
        
        if ( $ci->input->is_ajax_request() )
        {
            exit( get_json_response( 'false', err_lang( $key ) ) );
        }
        
        r_go_to_referer( $key, false );
    }
}

/**
 * Error Response ( Google reCaptcha ).
 *
 * Use to send a error response, if the form element is having
 * the Google reCaptcha plugin applied.
 *
 * @param  string $key Error messages language key without "err_" prefix.
 * @return void
 */
if ( ! function_exists( 'r_error_gr' ) )
{
    function r_error_gr( $key )
    {
        $ci =& get_instance();
        
        if ( $ci->input->is_ajax_request() )
        {
            exit( get_json_response( 'false_gr', err_lang( $key ) ) );
        }
        
        r_go_to_referer( $key, false );
    }
}

/**
 * Direct Error Response
 *
 * Use to send a hard coded error response.
 *
 * @param  string $text
 * @return void
 */
if ( ! function_exists( 'd_r_error' ) )
{
    function d_r_error( $text )
    {
        $ci =& get_instance();
        
        if ( $ci->input->is_ajax_request() )
        {
            exit( get_json_response( 'false', $text ) );
        }
        
        r_go_to_referer( $text, false, true );
    }
}

/**
 * Direct Error Response ( Google reCaptcha ).
 *
 * Use to send a hard coded error response and the form element
 * that is having the Google reCaptcha plugin applied.
 *
 * @param  string $text
 * @return void
 */
if ( ! function_exists( 'd_r_error_gr' ) )
{
    function d_r_error_gr( $text )
    {
        $ci =& get_instance();
        
        if ( $ci->input->is_ajax_request() )
        {
            exit( get_json_response( 'false_gr', $text ) );
        }
        
        r_go_to_referer( $text, false, true );
    }
}

/**
 * Error Response ( No Permission ).
 *
 * Use to send a error response for the unauthorized request.
 *
 * @return void
 */
if ( ! function_exists( 'r_error_no_permission' ) )
{
    function r_error_no_permission()
    {
        d_r_error( NO_PERMISSION_MSG );
    }
}

/**
 * Success Response
 *
 * Use to send a success response.
 *
 * @param  string $key Success messages language key without "suc_" prefix.
 * @return void
 */
if ( ! function_exists( 'r_success' ) )
{
    function r_success( $key )
    {
        $ci =& get_instance();
        
        if ( $ci->input->is_ajax_request() )
        {
            exit( get_json_response( 'true', suc_lang( $key ) ) );
        }
        
        r_go_to_referer( $key );
    }
}

/**
 * Direct Success Response
 *
 * Use to send a hard coded success response.
 *
 * @param  string $text
 * @return void
 */
if ( ! function_exists( 'd_r_success' ) )
{
    function d_r_success( $text )
    {
        $ci =& get_instance();
        
        if ( $ci->input->is_ajax_request() )
        {
            exit( get_json_response( 'true', $text ) );
        }
        
        r_go_to_referer( $text, true, true );
    }
}

/**
 * Success Response ( Google reCaptcha ).
 *
 * Use to send a success response, if the form element
 * is having the Google reCaptcha plugin applied.
 *
 * @param  string $key Success messages language key without "suc_" prefix.
 * @return void
 */
if ( ! function_exists( 'r_success_gr' ) )
{
    function r_success_gr( $key )
    {
        $ci =& get_instance();
        
        if ( $ci->input->is_ajax_request() )
        {
            exit( get_json_response( 'true_gr', suc_lang( $key ) ) );
        }
        
        r_go_to_referer( $key );
    }
}

/**
 * Success Response ( Don't Reset ).
 *
 * Use to send a success response that will not reset the form data ( ajax ).
 *
 * @param  string $key Success messages language key without "suc_" prefix.
 * @return void
 */
if ( ! function_exists( 'r_success_dr' ) )
{
    function r_success_dr( $key )
    {
        $ci =& get_instance();
        
        if ( $ci->input->is_ajax_request() )
        {
            exit( get_json_response( 'true_dr', suc_lang( $key ) ) );
        }
        
        r_go_to_referer( $key );
    }
}

/**
 * Success Response ( Close Modal ).
 *
 * Use to send a success response that will close the requester modal too.
 *
 * @param  string $key Success messages language key without "suc_" prefix.
 * @return void
 */
if ( ! function_exists( 'r_success_cm' ) )
{
    function r_success_cm( $key )
    {
        exit( get_json_response( 'true_cm', suc_lang( $key ) ) );
    }
}

/**
 * Success Response ( Add ).
 *
 * Use to send a success response for the ajax request to append as HTML record.
 *
 * @param  string $html
 * @param  string $key Success messages language key without "suc_" prefix.
 * @return void
 */
if ( ! function_exists( 'r_success_add' ) )
{
    function r_success_add( $html, $key = 'added' )
    {
        exit( get_json_response( 'add', $html, ['message' => suc_lang( $key )] ) );
    }
}

/**
 * Success Response ( Replace ).
 *
 * Use to send a success response for the ajax request to replace with some HTML record.
 *
 * @param  integer $id Record ID
 * @param  string  $html
 * @return void
 */
if ( ! function_exists( 'r_success_replace' ) )
{
    function r_success_replace( $id, $html )
    {
        exit( get_json_response( 'replace', $html, ['id' => $id, 'message' => suc_lang( 'updated' )] ) ); 
    }
}

/**
 * Success Response ( Remove ).
 *
 * Use to send a success response for the ajax request to remove a HTML record.
 *
 * @param  integer $id  Record ID
 * @param  string  $key Success messages language key without "suc_" prefix.
 * @return void
 */
if ( ! function_exists( 'r_success_remove' ) )
{
    function r_success_remove( $id, $key = 'deleted' )
    {
        exit( get_json_response( 'remove', $id, ['message' => suc_lang( $key )] ) );
    }
}

/**
 * Close Window
 *
 * Use to close the requester tab.
 *
 * @return void
 */
if ( ! function_exists( 'close_window' ) )
{
    function close_window()
    {
        exit( '<script>window.close();</script>' );
    }
}

/**
 * Success Response ( Jump ).
 *
 * Use to redirect or to send a success response for the ajax request to move
 * to the target location.
 *
 * @param  string $target
 * @param  string $key Success messages language key without "suc_" prefix.
 * @param  string $type
 * @return void
 */
if ( ! function_exists( 'r_s_jump' ) )
{
    function r_s_jump( $target, $key = '', $type = 'lang' )
    {
        $ci =& get_instance();
        
        if ( ! empty( $key ) ) set_success_flash( $key, $type );
        
        $target = env_url( $target );
        
        if ( $ci->input->is_ajax_request() )
        {
            exit( get_json_response( 'jump', $target ) );
        }
        
        redirect( $target );
    }
}

/**
 * Success Response ( Jump, Settings ).
 *
 * Use to send a success response for the ajax request to move back to the
 * settings child page ( admin area ).
 *
 * @param  string $page
 * @return void
 */
if ( ! function_exists( 'r_settings' ) )
{
    function r_settings( $page )
    {
        r_s_jump( 'admin/settings/' . $page, 'updated' );
    }
}

/**
 * Success Response ( Voted ).
 *
 * @param  string $text
 * @return void
 */
if ( ! function_exists( 'r_success_voted' ) )
{
    function r_success_voted( $text )
    {
        exit( get_json_response( 'voted', $text ) );
    }
}

/**
 * Error Response ( Jump ).
 *
 * Use to redirect or to send a error response for the ajax request to move
 * to the target location.
 *
 * @param  string $target
 * @param  string $key Error messages language key without "err_" prefix.
 * @return void
 */
if ( ! function_exists( 'r_e_jump' ) )
{
    function r_e_jump( $target, $key = '' )
    {
        $ci =& get_instance();
        
        if ( ! empty( $key ) ) set_error_flash( $key );
        
        $target = env_url( $target );
        
        if ( $ci->input->is_ajax_request() )
        {
            exit( get_json_response( 'jump', $target ) );
        }
        
        redirect( $target );
    }
}
