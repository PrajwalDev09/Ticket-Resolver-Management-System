<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Login Helper
 *
 * @author Shahzaib
 */


/**
 * Get Team Users
 *
 * @param  string $get_type
 * @return object
 */
if ( ! function_exists( 'get_team_users' ) )
{
    function get_team_users( $get_type = 'result' )
    {
        $ci =& get_instance();
        $ci->load->model( 'User_model' );
        
        return $ci->User_model->team_users( $get_type );
    }
}

/**
 * Is Notifications Enabled
 *
 * Use to check is the system and user email notifications
 * are enabled or not.
 *
 * @param  integer $id
 * @return boolean
 */
if ( ! function_exists( 'is_notifications_enabled' ) )
{
    function is_notifications_enabled( $id )
    {
        if ( db_config( 'sp_email_notifications' ) == 0 )
        {
            return false;
        }
        
        $ci =& get_instance();
        $ci->load->model( 'User_model' );
        
        $user = $ci->User_model->get_by_id( $id );
        
        if ( empty( $user ) ) return false;
        
        if ( $user->send_email_notifications )
        {
            return true;
        }
        
        return false;
    }
}

/**
 * Send Welcome Email
 *
 * @param  integer $id
 * @return string
 */
if ( ! function_exists( 'send_welcome_email' ) )
{
    function send_welcome_email( $id )
    {
        $ci =& get_instance();
        
        $template = $ci->Tool_model->email_template_by_hook_and_lang( 'welcome_user', get_language() );
        
        if ( ! empty( $template ) )
        {
            $ci->load->model( 'User_model' );
            
            $user = $ci->User_model->get_by_id( $id );
            
            if ( ! empty( $user ) )
            {
                if ( ! is_email_settings_filled() ) return 'missing_email_config_a';
                
                $message = replace_placeholders( $template->template, [
                    '{USER_NAME}' => $user->first_name . ' ' . $user->last_name,
                    '{LOGIN_USERNAME}' => $user->username,
                    '{EMAIL_LINK}' => env_url( 'login' ),
                    '{SITE_NAME}' => db_config( 'site_name' )
                ]);
                
                $ci->load->library( 'ZMailer' );
                
                if ( ! $ci->zmailer->send_email( $user->email_address, $template->subject, $message ) )
                {
                    return 'failed_email';
                }
                
                return true;
            }
            
            return 'invalid_req';
        }
        
        return 'missing_template';
    }
}

/**
 * Cleaned Email Username
 *
 * @param  string $email
 * @return string
 */
if ( ! function_exists( 'cleaned_email_username' ) )
{
    function cleaned_email_username( $email )
    {
        $email = explode( '@', $email );
        $username = preg_replace( '/[^A-Za-z0-9_-]/', '', $email[0] );
        $length = strlen( $username );
        
        if ( $length < 5 )
        {
            $chars = 'abcdefghijklmnopqrstuvwxyz';
            $string = substr( str_shuffle( $chars ), 0, ( 5 - $length ) );
            $username .= $string;
        }
        
        return $username;
    }
}

/**
 * Delete User Profile Picture
 *
 * @param  integer $user_id
 * @return boolean
 */
if ( ! function_exists( 'delete_profile_picture' ) )
{
    function delete_profile_picture( $user_id )
    {
        $ci =& get_instance();
        
        $ci->load->model( 'User_model' );
        $ci->load->library( 'ZFiles' );
        
        $user = $ci->User_model->get_by_id( $user_id );
        
        if ( empty( $user ) )
        {
            return 'invalid_req';
        }
        
        // Check if the image is hosted on a third-party server
        // ( e.g. Facebook ), don't perform the deletion:
        if ( ! filter_var( $user->picture, FILTER_VALIDATE_URL ) )
        {
            if ( $user->picture !== DEFAULT_USER_IMG )
            {
                $ci->zfiles->delete_image_file( 'users', $user->picture );
            }
        }
        
        $status = $ci->User_model->update_user(
        [
            'picture' => DEFAULT_USER_IMG
        ], $user_id );
        
        return $status;
    }
}

/**
 * User Locally Locked Check
 *
 * @param  string  $value
 * @param  string  $type
 * @param  boolean $return
 * @return boolean
 */
if ( ! function_exists( 'user_locally_locked_check' ) )
{
    function user_locally_locked_check( $value, $type = 'login', $return = false )
    {
        $ci =& get_instance();
        $ci->load->model( 'Login_model' );
        $li = $ci->Login_model->invalid_attempts( $value, $type );
        $status = false;
        
        if ( ! empty( $li ) )
        {
            $unlock_time = subtract_time( get_lockout_unlock_time() );
            $max_attempts = get_max_allowed_attempts();
            
            // Check invalid attempts that are performed under the fifteen minutes:
            if ( $li->attempted_at > subtract_time( '15 minutes' ) && $li->is_locked == 0 )
            {
                // If the count is crossed the maximum allowed attempts,
                // lock the account locally ( for a specific IP ):
                if ( $li->count >= $max_attempts )
                {
                    $ci->Login_model->lock_user_locally( $li->id, $type );
                    $status = true;
                }
            }
            
            // If a attempt is performed after fifteen minutes of the
            // last attemp, clear the recent count:
            else if ( $li->is_locked == 0 )
            {
                $ci->Login_model->clear_attempts_count( $value, $type );
            }
            
            if ( $li->attempted_at > $unlock_time && $li->is_locked == 1 )
            {
                if ( $li->count >= $max_attempts )
                {
                    $status = true;
                }
            }
            
            // If lockout time of a locked account is crossed the selected
            // time, delete the attempt record:
            else if ( $li->is_locked == 1 )
            {
                $ci->Login_model->delete_invalid_attempt( $value, $type );
            }
        }
        
        if ( $status )
        {
            // If the status is true, display the error message with the
            // waiting time for the next try:
            $time_limit = get_lockout_unlock_time( true );
            $sec = $time_limit - intval( time() - $li->attempted_at );
            $time_format = ( db_config( 'u_lockout_unlock_time' ) == 4 ) ? 'H:i:s' : 'i:s';
            $rem_time = gmdate( $time_format, $sec );
            $locked_message = sprintf( err_lang( 'too_many_attempts' ), $rem_time );
            
            if ( $return === false ) d_r_error_gr( $locked_message );
            else return $locked_message;
        }
    }
}

/**
 * Validate Password
 *
 * Use to validate the password based on setting.
 *
 * @param  string $pass
 * @return array
 */
if ( ! function_exists( 'validate_password' ) )
{
    function validate_password( $pass )
    {
        $req = db_config( 'u_password_requirement' );
        $status = false;
        $message = '';
        $regex = '';
        
        if ( $req === 'strong' )
        {
            $regex = '/^(?=.*[0-9])(?=.*[a-zA-z])(?=.*[.,:;?!~`\\\@#$%^&|[\[\](){}\/<>\"\'*_+=-]).{12,}$/';
            
            if ( ! preg_match( $regex, $pass ) )
            {
                $message = 'pwd_strong';
            }
            else
            {
                $status = true;
            }
        }
        else if ( $req === 'medium' )
        {
            $regex = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{8,}$/';
            
            if ( ! preg_match( $regex, $pass ) )
            {
                $message = 'pwd_medium';
            }
            else
            {
                $status = true;
            }
        }
        else if ( $req === 'normal' )
        {
            $regex = '/^(?=.*[0-9])(?=.*[a-zA-z]).{6,}$/';
            
            if ( ! preg_match( $regex, $pass ) )
            {
                $message = 'pwd_normal';
            }
            else
            {
                $status = true;
            }
        }
        else if ( $req === 'low' )
        {
            if ( strlen( $pass ) < 6 )
            {
                $message = 'pwd_low';
            }
            else
            {
                $status = true;
            }
        }
        
        return [
            'message' => $message,
            'status' => $status,
        ];
    }
}

/**
 * Set Login
 *
 * @param  string  $token
 * @param  integer $user_id
 * @param  boolean $social
 * @return boolean
 */
if ( ! function_exists( 'set_login' ) )
{
    function set_login( $token, $user_id, $social = false )
    {
        $ci =& get_instance();
        $ci->load->model( 'Login_model' );
        
        if ( $ci->Login_model->add_sess( $token, $user_id ) )
        {
            $ci->Login_model->set_as_online( $user_id );

            $ci->load->model( 'User_model' );
            
            if ( post( 'remember_me' ) || $social )
            {
                set_cookie( USER_TOKEN, $token, strtotime( '+1 year' ) );
            }
            else
            {
                delete_cookie( USER_TOKEN );
            }
            
            set_session( USER_TOKEN, $token );
            
            $ci->zuser->data = $ci->User_model->get_by_id( $user_id );

            // To fetch the permissions based on the
            // logged-in user role:
            $ci->zuser->is_logged_in = true;
            $ci->zuser->set_perm_keys();
            
            $ci->Login_model->save_last_login( $user_id );
            
            return true;
        }
        
        return false;
    }
}

/**
 * Get After Login Location
 *
 * @return string
 */
if ( ! function_exists( 'get_after_login_location' ) )
{
    function get_after_login_location()
    {
        $session = get_session( 'login_redirect' );
        
        unset_session( 'login_redirect' );
        
        if ( $session )
        {
            return get_session( 'login_redirect' );
        }
        else if ( $session === '' )
        {
            return $session;
        }
        
        return get_related_dashboard();
    }
}

/**
 * EVerification Setup
 *
 * @param  integer $id
 * @return mixed
 */
if ( ! function_exists( 'everification_setup' ) )
{
    function everification_setup( $id )
    {
        $ci =& get_instance();
        
        $template = $ci->Tool_model->email_template_by_hook_and_lang( 'email_verification', get_language() );
        $token = get_short_random_string();
        
        if ( empty( $template ) ) return 'missing_template';
        
        $ci->load->model( 'User_model' );
        
        $user = $ci->User_model->get_by_id( $id );
        
        if ( empty( $user ) ) return 'invalid_req';
        
        if ( $user->is_verified != 0 ) return 'already_verified';
        
        $message = replace_placeholders( $template->template, [
            '{USER_NAME}' => $user->first_name . ' ' . $user->last_name,
            '{EMAIL_LINK}' => env_url( "everify/{$id}/{$token}" ),
            '{SITE_NAME}' => db_config( 'site_name' )
        ]);
        
        if ( ! is_email_settings_filled() ) return 'missing_email_config_a';
        
        $ci->load->library( 'ZMailer' );
        
        if ( $ci->zmailer->send_email( $user->email_address, $template->subject, $message ) )
        {
            $ci->load->model( 'Email_token_model' );
             
            $status = $ci->Email_token_model->add_email_token( $token, $id, 'email_verification' );
            
            if ( empty( $status ) )
            {
                return 'ev_token_update_failed';
            }
            
            return true;
        }
        
        return 'failed_email';
    }
}

/**
 * Update Profile Settings
 *
 * @param  integer $user_id
 * @param  string  $area
 * @return mixed
 */
if ( ! function_exists( 'update_profile_settings' ) )
{
    function update_profile_settings( $user_id, $area = '' )
    {
        $ci =& get_instance();
        
        $ci->load->model( 'User_model' );
        
        $user = $ci->User_model->get_by_id( $user_id );
        
        if ( empty( $user ) ) return 'invalid_req';
        
        $data = [
            'first_name' => do_secure_u( post( 'first_name' ) ),
            'last_name' => do_secure_u( post( 'last_name' ) ),
            'email_address' => do_secure_l( post( 'email_address' ) ),
            'username' => do_secure_l( post( 'username' ) ),
            'send_email_notifications' => only_binary( post( 'email_notifications' ) ),
            'language' => do_secure_l( post( 'language' ) ),
            'time_format' => do_secure( post( 'time_format' ) ),
            'date_format' => do_secure( post( 'date_format' ) ),
            'timezone' => do_secure( post( 'timezone' ) )
        ];
        
        if ( ! empty( $data['language'] ) && empty( AVAILABLE_LANGUAGES[$data['language']] ) )
        {
            return 'invalid_language';
        }
        
        if ( $ci->User_model->is_email_address_exists( $data['email_address'], $user_id ) )
        {
            r_error( 'email_taken' );
        }
        
        if ( db_config( 'u_allow_username_change' ) == 0 && $ci->uri->segment( 2 ) === 'user' )
        {
            unset( $data['username'] );
        }
        else
        {
            if ( $ci->User_model->is_username_exists( $data['username'], $user_id ) )
            {
                r_error( 'username_taken' );
            }
        }
        
        if ( db_config( 'u_req_ev_onchange' ) == 1 && $area === 'user' )
        {
            if ( $data['email_address'] !== $user->email_address )
            {
                if ( $user->pending_email_address == $data['email_address'] ) return 'already_email_pending';
                
                $data['pending_email_address'] = $data['email_address'];
                
                $template = $ci->Tool_model->email_template_by_hook_and_lang( 'change_email', get_language() );
                $token = get_short_random_string();
                
                if ( empty( $template ) ) r_error( 'missing_template' );
                
                $message = replace_placeholders( $template->template, [
                    '{EMAIL_LINK}' => env_url( "change_email/{$token}" ),
                    '{SITE_NAME}' => db_config( 'site_name' )
                ]);
                
                if ( ! is_email_settings_filled() ) return 'missing_email_config_a';
                
                $ci->load->library( 'ZMailer' );
                
                if ( $ci->zmailer->send_email( $data['email_address'], $template->subject, $message ) )
                {
                    $ci->load->model( 'Email_token_model' );
                    
                    if ( ! $ci->Email_token_model->add_email_token( $token, $user->id, 'change_email' ) )
                    {
                        return 'went_wrong';
                    }
                }
                else
                {
                    return 'failed_email';
                }
                
                unset( $data['email_address'] );
            }
        }
        else if ( $ci->uri->segment( 2 ) === 'user' )
        {
            if ( $data['email_address'] !== $user->email_address ) $data['pending_email_address'] = '';
        }
        
        if ( ! empty( $_FILES['picture']['tmp_name'] ) )
        {
            $ci->load->library( 'ZFiles' );
            
            $old_file = $user->picture;
            $data['picture'] = $ci->zfiles->upload_user_avatar();
            
            if ( ! filter_var( $old_file, FILTER_VALIDATE_URL ) )
            {
                if ( $old_file !== DEFAULT_USER_IMG )
                {
                    $ci->zfiles->delete_image_file( 'users', $old_file );
                }
            }
        }
        
        if ( $area === 'admin' )
        {
            $data['is_verified'] = only_binary( post( 'email_verified' ) );
            
            if ( post( 'password' ) && ! post( 'retype_password' ) )
            {
                return 'missing_passwords';
            }
            
            if ( post( 'password' ) )
            {
                if ( post( 'password' ) == post( 'retype_password' ) )
                {
                    $status = validate_password( post( 'password' ) );
                
                    if ( $status['status'] === false ) return $status['message'];
                    
                    $data['password'] = password_hash( post( 'password' ), PASSWORD_DEFAULT );
                }
                else
                {
                    return 'passwords_match';
                }
            }
            
            $data['status'] = intval( post( 'status' ) );
            $data['role'] = intval( post( 'role' ) );
            
            // If password, role, or status is changed for the
            // default user, then don't allow the updation:
            if ( $user_id == 1 )
            {
                if ( $data['role'] != 1 ||
                     ( post( 'password' ) || post( 'retype_password' ) ) ||
                     $data['status'] != 1 )
                return 'u_change_not_allowed';
            }
        }
        
        $ci->User_model->update_user( $data, $user_id );
        
        return true;
    }
}

/**
 * Delete User
 *
 * @param  integer $id
 * @return boolean
 */
if ( ! function_exists( 'delete_user' ) )
{
    function delete_user( $id )
    {
        $ci =& get_instance();
        
        $ci->load->model( 'Email_token_model' );
        $ci->load->model( 'User_model' );
        
        $ci->Email_token_model->delete_user_tokens( $id );
        $ci->Notification_model->delete_notifications( $id );
        $ci->Tool_model->delete_user_sessions( $id );
        
        return $ci->User_model->delete_user( $id );
    }
}

/**
 * Get Lockout Unlock Time
 *
 * @param  boolean $math
 * @return mixed
 */
if ( ! function_exists( 'get_lockout_unlock_time' ) )
{
    function get_lockout_unlock_time( $math = false )
    {
        $key = db_config( 'u_lockout_unlock_time' );
        
        if ( $math )
        {
            $periods = [
                '1' => 15 * 60,
                '2' => 30 * 60,
                '3' => 60 * 60,
                '4' => 24 * 60 * 60
            ];
        }
        else
        {
            $periods = [
                '1' => '15 minutes',
                '2' => '30 minutes',
                '3' => '60 minutes',
                '4' => '24 hours'
            ];
        }
        
        if ( array_key_exists( $key, $periods ) )
        {
            return $periods[$key];
        }
    }
}

/**
 * Get Max Allowed Attempts
 *
 * @return integer
 */
if ( ! function_exists( 'get_max_allowed_attempts' ) )
{
    function get_max_allowed_attempts()
    {
        $key = db_config( 'u_temporary_lockout' );
        
        $allowed = [
            'strict' => 5,
            'medium' => 10,
            'normal' => 20
        ];
        
        if ( array_key_exists( $key, $allowed ) )
        {
            return $allowed[$key];
        }
    }
}
