<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Tool Model
 *
 * @author Shahzaib
 */
class Tool_model extends MY_Model {
    
    /**
     * User Session by Token
     *
     * @param  string $token
     * @return object
     */
    public function user_session_by_token( $token )
    {
        $data['where']['token'] = $token;
        $data['table'] = 'users_sessions';
        
        return $this->get_one( $data );
    }
    
    /**
     * Delete User Session by Token
     *
     * @param  string $token
     * @return boolean
     */
    public function delete_user_session_by_token( $token )
    {
        $data['column'] = 'token';
        $data['column_value'] = $token;
        $data['table'] = 'users_sessions';
        
        return $this->delete( $data );
    }
    
    /**
     * User Sessions
     *
     * @param  array $options
     * @return mixed
     */
    public function user_sessions( array $options = [] )
    {
        $data['table'] = 'users_sessions';
        
        if ( empty( $options['user_id'] ) )
        {
            $data['select'] = 'users_sessions.*, users.first_name, users.last_name';
            $data['join'] = ['table' => 'users', 'on' => 'users.id = users_sessions.user_id'];
        }
        else
        {
            $data['where'] = ['user_id' => $options['user_id']];
        }
        
        if ( ! empty( $options['limit'] ) ) $data['limit'] = $options['limit'];
        
        if ( ! empty( $options['offset'] ) ) $data['offset'] = $options['offset'];
        
        if ( @$options['count'] === true )
        {
            return $this->get_count( $data );
        }
        
        return $this->get( $data );
    }
    
    /**
     * User ID by Session Token ( Logged in User ).
     *
     * @return integer
     */
    public function user_id_by_sess_token()
    {
        $data['where']['token'] = get_session( USER_TOKEN );
        $data['table'] = 'users_sessions';

        $row = $this->get_one( $data );
        
        if ( ! empty( $row->user_id ) )
        return $row->user_id;
    }
    
    /**
     * User Session
     *
     * @param  integer $id
     * @param  integer $user_id
     * @return object
     */
    public function user_session( $id, $user_id = 0 )
    {
        $data['where']['id'] = $id;
        $data['table'] = 'users_sessions';
        
        if ( ! empty( $user_id ) )
        {
            $data['where']['user_id'] = $user_id;
        }
        
        return $this->get_one( $data );
    }
    
    /**
     * Delete User Session
     *
     * @param  integer $id
     * @return boolean
     */
    public function delete_user_session( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'users_sessions';
        
        return $this->delete( $data );
    }
    
    /**
     * Logout My Other(s)
     *
     * @return boolean
     */
    public function logout_my_others()
    {
        $user_id = $this->zuser->get( 'id' );
        $current_token = get_session( USER_TOKEN );
        
        $data['where'] = ['user_id' => $user_id, 'token !=' => $current_token];
        $data['table'] = 'users_sessions';
        
        return $this->delete( $data );
    }
    
    /**
     * Delete User Sessions
     *
     * @param  integer $user_id
     * @return boolean
     */
    public function delete_user_sessions( $user_id )
    {
        $data['where']['user_id'] = $user_id;
        $data['table'] = 'users_sessions';
        
        return $this->delete( $data );
    }
    
    /**
     * Announcements
     *
     * @param  boolean $count
     * @param  integer $limit
     * @param  integer $offset
     * @return mixed
     */
    public function announcements( $count = false, $limit = 0, $offset = 0 )
    {
        $data['table'] = 'announcements';
        $data['limit'] = $limit;
        $data['offset'] = $offset;
        
        if ( $count === true )
        {
            return $this->get_count( $data );
        }
        
        return $this->get( $data );
    }
    
    /**
     * Announcement
     *
     * @param  integer $id
     * @return object
     */
    public function announcement( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'announcements';
        
        return $this->get_one( $data );
    }
    
    /**
     * Add Announcement
     *
     * @param  array $data
     * @return mixed
     */
    public function add_announcement( $data )
    {
        return $this->add( $data, 'announcements' );
    }
    
    /**
     * Update Announcement
     *
     * @param  array   $to_update
     * @param  integer $id
     * @return boolean
     */
    public function update_announcement( $to_update, $id )
    {
       $data['column_value'] = $id;
       $data['table'] = 'announcements';
       $data['data'] = $to_update;
       
       return $this->update( $data );
    }
    
    /**
     * Check for New Announcements
     *
     * @return integer
     */
    public function check_for_new_announcements()
    {
        if ( $this->zuser->is_logged_in )
        {
            $this->load->model( 'User_model' );
            
            // To avoid the dot after reading, get the updated
            // record of the logged in user:
            $user = $this->User_model->get_by_id( $this->zuser->get( 'id' ) );
            
            if ( ! empty( $user ) )
            {
                $user_last_read = $user->announcements_last_read_at;
                $data['where'] = ['created_at >=' => intval( $user_last_read )];
                $data['table'] = 'announcements';
                    
                return $this->get_count( $data );
            }
        }
        
        return 0;
    }
    
    /**
     * Mark Announcements as Read
     *
     * @return void
     */
    public function mark_announcements_as_read()
    {
       $data['column_value'] = $this->zuser->get( 'id' );
       $data['data'] = ['announcements_last_read_at' => time()];
       $data['table'] = 'users';
       
       $this->update( $data );
    }
    
    /**
     * Delete Announcement
     *
     * @param  integer $id
     * @return boolean
     */
    public function delete_announcement( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'announcements';
        
        return $this->delete( $data );
    }
    
    /**
     * Email Templates
     *
     * @return object
     */
    public function email_templates()
    {
        $data['table'] = 'email_templates';
        $data['order'] = 'ASC';
        
        return $this->get( $data );
    }
    
    /**
     * Email Template
     *
     * @param  integer $id
     * @return object
     */
    public function email_template( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'email_templates';
        
        return $this->get_one( $data );
    }
    
    /**
     * Email Template by Hook Hook and Language.
     *
     * @param  string $hook
     * @param  string $lang_key
     * @return object
     */
    public function email_template_by_hook_and_lang( $hook, $lang_key )
    {
        $data['where'] = ['hook' => $hook, 'language' => $lang_key];
        $data['table'] = 'email_templates';
        
        return $this->get_one( $data );
    }

    /**
     * Add Email Template
     *
     * @param  array $data
     * @return mixed
     */
    public function add_email_template( $data )
    {
        return $this->add( $data, 'email_templates' );
    }
    
    /**
     * Update Email Template
     *
     * @param  array   $to_update
     * @param  integer $id
     * @return boolean
     */
    public function update_email_template( $to_update, $id )
    {
       $data['column_value'] = $id;
       $data['table'] = 'email_templates';
       $data['data'] = $to_update;
       
       return $this->update( $data );
    }
    
    /**
     * Delete Email Template
     *
     * @param  integer $id
     * @return boolean
     */
    public function delete_email_template( $id )
    { 
        $data['column_value'] = $id;
        $data['table'] = 'email_templates';
        
        return $this->delete( $data );
    }
    
    /**
     * Backup Log
     *
     * @param  boolean $count
     * @param  integer $limit
     * @param  integer $offset
     * @return mixed
     */
    public function backup_log( $count = false, $limit = 0, $offset = 0 )
    {
        $data['table'] = 'backup_log';
        $data['limit'] = $limit;
        $data['offset'] = $offset;
        
        if ( $count === true )
        {
            return $this->get_count( $data );
        }
        
        return $this->get( $data );
    }
    
    /**
     * Log a Backup
     *
     * @param  array $data
     * @return mixed
     */
    public function log_a_backup( $data )
    {
        return $this->add( $data, 'backup_log' );
    }
    
    /**
     * Delete a Backup Log
     *
     * @param  integer $id
     * @return boolean
     */
    public function delete_a_backup_log( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'backup_log';
        
        return $this->delete( $data );
    }
}
