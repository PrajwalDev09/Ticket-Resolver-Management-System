<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * ZMailer Library
 *
 * @author Shahzaib
 */
class ZMailer {
  
    /**
     * Send Email Using Mail or SMTP Protocol.
     *
     * @param  string  $receiver
     * @param  string  $subject
     * @param  string  $message
     * @param  boolean $debug
     * @return mixed
     */
    public function send_email( $receiver, $subject, $message, $debug = false )
    {
        $ci =& get_instance();
        $ci->load->library( 'email' );
        
        $config['mailtype'] = 'html';
        
        if ( db_config( 'e_protocol' ) === 'smtp' )
        {
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = db_config( 'e_host' );
            $config['smtp_port'] = db_config( 'e_port' );
            $config['smtp_crypto'] = db_config( 'e_encryption' );
            $config['smtp_user'] = db_config( 'e_username' );
            $config['smtp_pass'] = db_config( 'e_password' );
        }
        
        $ci->email->initialize( $config );
        $ci->email->set_newline( "\r\n" );
        
        $ci->email->from( db_config( 'e_sender' ), db_config( 'e_sender_name' ) );
        $ci->email->to( $receiver );
        $ci->email->subject( '[' . html_escape( db_config( 'site_name' ) ) . '] - ' . $subject );
        $ci->email->message( $message );
        
        if ( @$ci->email->send() )
        {
            return true;
        }
        
        if ( $debug === true )
        {
            return $ci->email->print_debugger();
        }
        
        return false;
    }
}
