<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Email Token Model
 *
 * @author Shahzaib
 */
class Email_token_model extends MY_Model {
    
    /**
     * Email Token
     *
     * @param  string  $token
     * @param  string  $type
     * @param  integer $user_id
     * @return object
     */
    public function email_token( $token, $type, $user_id = 0 )
    {
        $data['where'] = ['token' => $token, 'type' => $type];
        $data['table'] = 'email_tokens';
        
        if ( ! empty( $user_id ) ) $data['where']['user_id'] = $user_id;
        
        return $this->get_one( $data );
    }
    
    /**
     * Add Email Token
     *
     * @param  string  $token
     * @param  integer $user_id
     * @param  string  $type
     * @return mixed
     */
    public function add_email_token( $token, $user_id, $type )
    {
        $data = [
            'requested_at' => time(),
            'type' => $type,
            'ip_address' => $this->input->ip_address(),
            'token' => $token,
            'user_id' => $user_id
        ];
        
        return $this->add( $data, 'email_tokens' );
    }
    
    /**
     * Delete User Tokens
     *
     * @param  integer $id
     * @return void
     */
    public function delete_user_tokens( $id )
    {
        $data['where']['user_id'] = $id;
        $data['table'] = 'email_tokens';
        
        $this->delete( $data );
    }
    
    /**
     * Delete Email Token
     *
     * @param  integer $user_id
     * @param  string  $type
     * @return boolean
     */
    public function delete_email_token( $user_id, $type )
    {
        $data['where'] = [
            'user_id' => $user_id,
            'ip_address' => $this->input->ip_address(),
            'type' => $type
        ];
        
        $data['table'] = 'email_tokens';
        
        return $this->delete( $data );
    }
}
