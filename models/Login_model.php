<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Login Model
 *
 * @author Shahzaib
 */
class Login_model extends MY_Model {
    
    /**
     * Save Last Login
     *
     * @param  integer $user_id
     * @return void
     */
    public function save_last_login( $user_id )
    {
        $data['column_value'] = $user_id;
        $data['update_time'] = false;
        $data['data'] = ['last_login' => time()];
        $data['table'] = 'users';

        $this->update( $data );
    }
    
    /**
     * Social User Data
     *
     * @param  object  $user
     * @param  integer $provider
     * @return mixed
     */
    public function social_user_data( $user, $provider )
    {
        $this->load->model( 'User_model' );
        
        // Return back in case the user is not allowed
        // access to the email address:
        if ( empty( $user->email ) ) return 'invalid_req';
        
        $data = $this->User_model->get_by_email( $user->email );
        
        if ( empty( $data ) )
        {
             if ( ! db_config( 'u_enable_registration' ) ) return 'registration_disabled';
             
             if ( empty( $user->lastName ) )
             {
                 $name = explode( ' ', $user->firstName );
                 
                 if ( count( $name ) > 1 )
                 {
                     $user->lastName = array_pop( $name );
                     $user->firstName = implode( ' ', $name );
                 }
             }
             
             $data = [
                'first_name' => ucfirst( do_secure( $user->firstName ) ),
                'last_name' => ucfirst( do_secure( $user->lastName ) ),
                'email_address' => do_secure_l( $user->email ),
                'registration_source' => $provider,
                'oauth_identifier' => do_secure( $user->identifier ),
                'picture' => do_secure_url( $user->photoURL ),
                'role' => DEFAULT_USER_ROLE_ID,
                'registered_month_year' => get_site_date( 'n-Y' ),
                'registered_at' => time()
            ];
            
            $source = "{$data['first_name']}{$data['last_name']}";
            
            if ( ! is_alpha_numeric( $source ) || strlen( $source ) < 5 )
            {
                $source = cleaned_email_username( $data['email_address'] );
            }
            
            $data['username'] = $this->User_model->get_unique_username( $source );
            
            $id = $this->User_model->add( $data );
            
            if ( ! empty( $id ) )
            {
                set_flashdata( 'is_registered', true );
                set_success_flash( 'registered' );
                return intval( $id );
            }
            else
            {
                error_redirect( 'went_wrong' );
            }
        }
        else
        {
            if ( $data->registration_source == $provider )
            {
                if ( $data->status == 1 )
                {
                    return $data;
                }
                
                return 'user_banned';
            }
            
            return 'other_provider';
        }
    }
    
    /**
     * Invalid Attempts
     *
     * @param  string $value
     * @param  string $type
     * @return object
     */
    public function invalid_attempts( $value, $type )
    {
        $data['where'] = [
            'ip_address' => $this->input->ip_address(),
            'value' => $value,
            'type' => $type
        ];
        
        $data['table'] = 'attempts';
        
        return $this->get_one( $data );
    }
    
    /**
     * Log Invalid Attempt
     *
     * @param  string $value
     * @param  string $type
     * @return void
     */
    public function log_invalid_attempt( $value, $type = 'login' )
    {
        $ip_address = $this->input->ip_address();
        
        $data['where'] = [
            'ip_address' => $ip_address,
            'value' => $value,
            'type' => $type
        ];
        
        $data['table'] = 'attempts';
        
        if ( empty( $this->get_one( $data ) ) )
        {
            $to_add = [
                'ip_address' => $ip_address,
                'attempted_at' => time(),
                'value' => $value,
                'type' => $type
            ];
            
            $this->add( $to_add, 'attempts' );
        }
        else
        {
            $data['set'] = [
                'attempted_at' => time(),
                'count' => 'count+1'
            ];
            
            $this->update( $data );
        }
    }
    
    /**
     * Lock User Locally
     *
     * @param  integer $id
     * @param  string  $type
     * @return void
     */
    public function lock_user_locally( $id, $type )
    {
        $data['where'] = ['id' => $id, 'type' => $type];
        $data['data'] = ['is_locked' => 1];
        $data['table'] = 'attempts';

        $this->update( $data );
    }
    
    /**
     * Clear Attempts Count
     *
     * @param  string $value
     * @param  string $type
     * @return void
     */
    public function clear_attempts_count( $value, $type )
    {
        $data['where'] = [
            'ip_address' => $this->input->ip_address(),
            'value' => $value,
            'type' => $type
        ];
        
        $data['table'] = 'attempts';
        $data['data'] = ['count' => 0];
        
        $this->update( $data );
    }
    
    /**
     * Delete Invalid Attempt
     *
     * @param  string $value
     * @param  string $type
     * @return void
     */
    public function delete_invalid_attempt( $value, $type = 'login' )
    {
        $data['where'] = [
            'ip_address' => $this->input->ip_address(),
            'value' => $value,
            'type' => $type
        ];
        
        $data['table'] = 'attempts';
        
        $this->delete( $data );
    }
    
    /**
     * Set as Online
     *
     * @param  integer $user_id
     * @return void
     */
    public function set_as_online( $user_id )
    {
        $data['column_value'] = $user_id;
        $data['update_time'] = false;
        $data['data'] = ['is_online' => 1];
        $data['table'] = 'users';

        $this->update( $data );
    }
    
    /**
     * Password Reset Log
     *
     * @return object
     */
    public function password_reset_log()
    {
        $data['where'] = [
            'ip_address' => $this->input->ip_address(),
            'type' => 'password_reset'
        ];
        
        $data['table'] = 'email_tokens';
        
        return $this->get_one( $data );
    }
    
    /**
     * Add Login Session
     *
     * @param  string  $token
     * @param  integer $user_id
     * @return mixed
     */
    public function add_sess( $token, $user_id )
    {
        $this->load->library( 'user_agent' );
        
        $data = [
            'ip_address' => $this->input->ip_address(),
            'logged_in_at' => time(),
            'token' => $token,
            'user_id' => $user_id
        ];
        
        if ( ! empty( $this->agent->platform() ) )
        {
            $data['platform'] = $this->agent->platform();
        }
        
        if ( ! empty( $this->agent->browser() ) )
        {
            $data['browser'] = $this->agent->browser();
        }
        
        return $this->add( $data, 'users_sessions' );
    }
}