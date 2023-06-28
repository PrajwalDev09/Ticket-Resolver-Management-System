<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * User Model
 *
 * @author Shahzaib
 */
class User_model extends MY_Model {
    
    /**
     * Class Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->table = 'users';
    }
    
    /**
     * Get Unique Username
     *
     * @param  string $source
     * @return string
     */
    public function get_unique_username( $source )
    {
        $username = str_replace( ' ', '', $source );
        $result = 0;
        
        $data['like_column'] = 'username';
        $data['like_column_value'] = $username;
        
        $result = $this->get( $data );
        
        if ( ! empty( $result ) )
        {
            $count = count( $result );
            
            if ( $count > 0 )
            {
                $username .= $count;
            }
        }
        
        return strtolower( $username );
    }
    
    /**
     * Get by Email Address
     *
     * @param  string $email_address
     * @return object
     */
    public function get_by_email( $email_address )
    {
        $data['where'] = ['email_address' => $email_address];
        
        return $this->get_one( $data );
    }
    
    /**
     * Get by Username
     *
     * @param  string $username
     * @return object
     */
    public function get_by_username( $username )
    {
        $data['where'] = ['username' => $username];
        
        return $this->get_one( $data );
    }
    
    /**
     * Get by ID
     *
     * @param  integer $user_id
     * @retrun object
     */
    public function get_by_id( $user_id )
    {
        $data['column_value'] = $user_id;
        
        return $this->get_one( $data );
    }
    
    /**
     * Get Users Count by Month and Year
     *
     * @param  string $month_year
     * @return integer
     */
    public function get_count_by_month_year( $month_year )
    {
        $data['where'] = ['registered_month_year' => $month_year];
        $data['table'] = 'users';

        return $this->get_count( $data );
    }
    
    /**
     * Get Social Users Count
     *
     * @return integer
     */
    public function get_social_count()
    {
        $data['where'] = ['registration_source !=' => 1];
        $data['table'] = 'users';

        return $this->get_count( $data );
    }
    
    /**
     * Get New Users Count
     *
     * Use to get the count of the users registered within 24 hrs.
     *
     * @return integer
     */
    public function get_of_new_count()
    {
        $data['where'] = ['registered_at >' => subtract_time( '24 hours' )];
        $data['table'] = 'users';

        return $this->get_count( $data );
    }
    
    /**
     * Get of Total Users Count
     *
     * @return integer
     */
    public function get_of_total_count()
    {
        return $this->get_count();
    }
    
    /**
     * Is Email Address Exists
     *
     * @param  string  $email_address
     * @param  integer $id
     * @return boolean
     */
    public function is_email_address_exists( $email_address, $id )
    {
        $data['where'] = ['email_address' => $email_address, 'id !=' => $id];
        
        return ! empty( $this->get_one( $data ) );
    }
    
    /**
     * Is Username Exists
     *
     * @param  string  $username
     * @param  integer $id
     * @return boolean
     */
    public function is_username_exists( $username, $id )
    {
        $data['where'] = ['username' => $username, 'id !=' => $id];
        
        return ! empty( $this->get_one( $data ) );
    }
    
    /**
     * Users
     *
     * @param  array $options
     * @return mixed
     */
    public function users( array $options = [] )
    {
        $data = [];
            
        $data['select'] = 'users.*, roles.name as role_name';
        $data['join'] = ['table' => 'roles', 'on' => 'users.role = roles.id'];
        
        if ( ! empty( $options['limit'] ) ) $data['limit'] = $options['limit'];
        
        if ( ! empty( $options['offset'] ) ) $data['offset'] = $options['offset'];
        
        if ( ! empty( $options['filter'] ) )
        {
            switch ( $options['filter'] )
            {
                case 'new_tfhrs':
                  $data['where'] = ['registered_at >' => subtract_time( '24 hours' )];
                  break;
                
                case 'online_today':
                  $data['where'] = ['online_date' => get_site_date()];
                  break;
                
                case 'social':
                  $data['where'] = ['registration_source !=' => 1];
                  break;
                 
                case 'online':
                  $data['where'] = ['is_online' => 1];
                  break;
                  
                case 'offline':
                  $data['where'] = ['is_online' => 0];
                  break;
                  
                case 'non_verified':
                  $data['where'] = ['is_verified' => 0];
                  break;
                  
                case 'active':
                  $data['where'] = ['status' => 1];
                  break;
                  
                case 'banned':
                  $data['where'] = ['status' => 0];
                  break;
            }
        }
        
        if ( ! empty( $options['role'] ) )
        {
            $data['where']['role'] = $options['role'];
        }
        
        if ( ! empty( $options['searched'] ) )
        {
            $holders = ['email_address', 'username', 'first_name', 'last_name'];
            
            foreach ( $holders as $holder )
            {
                $data['like'][$holder] = $options['searched'];
            }
        }
        
        if ( @$options['count'] === true )
        {
            return $this->get_count( $data );
        }
        
        return $this->get( $data );
    }
    
    /**
     * Active Users ( Non-Banned )
     *
     * @return  object
     * @version 1.1
     */
    public function active_users()
    {
        $data['where']['status'] = 1;
        $data['table'] = 'users';
        
        return $this->get( $data );
    }
    
    /**
     * Team Users
     *
     * @param  string $get_type
     * @return object
     */
    public function team_users( $get_type )
    {
        $ids = $this->Setting_model->roles_admin();
        
        $this->db->or_where_in( 'role', $ids );
        $this->db->where( 'status', 1 );
        $data = $this->db->get( 'users' );
        
        if ( $data->num_rows() > 0 ) return $data->{$get_type}();
    }
    
    /**
     * Limited Users
     *
     * @param  integer $limit
     * @return object
     */
    public function limited_users( $limit )
    {
        return $this->users( ['limit' => $limit] );
    }
    
    /**
     * Set Awayed Users as Offline
     *
     * @return void
     */
    public function set_awayed_offline()
    {
        $data['where'] = ['online_time <' => ( time() - 60 * 15 ), 'is_online' => 1];
        $data['update_time'] = false;
        $data['data'] = ['is_online' => 0];

        $this->update( $data );
    }
    
    /**
     * Update User
     *
     * @param  array   $to_update
     * @param  integer $id
     * @param  boolean $update_time
     * @return boolean
     */
    public function update_user( $to_update, $id, $update_time = true )
    {
        $data['column_value'] = $id;
        $data['update_time'] = $update_time;
        $data['data'] = $to_update;

        return $this->update( $data );
    }
    
    /**
     * Sent Email Records ( User )
     *
     * @param  array $options
     * @return mixed
     */
    public function sent_emails( array $options = [] )
    {
        $data['select'] = 'users_sent_emails.*, users.first_name, users.last_name';
        $data['table'] = 'users_sent_emails';
        $data['where'] = ['sent_to' => $options['user_id']];
        $data['join'] = ['table' => 'users', 'on' => 'users.id = users_sent_emails.sent_by'];
        
        if ( ! empty( $options['limit'] ) ) $data['limit'] = $options['limit'];
        
        if ( ! empty( $options['offset'] ) ) $data['offset'] = $options['offset'];
        
        if ( @$options['count'] === true )
        {
            return $this->get_count( $data );
        }
        
        return $this->get( $data );
    }
    
    /**
     * Sent Email Record ( User )
     *
     * @param  integer $id
     * @return object
     */
    public function sent_email( $id )
    {
        $data['where']['id'] = $id;
        $data['table'] = 'users_sent_emails';
        
        return $this->get_one( $data );
    }
    
    /**
     * Add Sent Email Record ( User ).
     *
     * @param  array $data
     * @return mixed
     */
    public function add_sent_email( $data )
    {
        return $this->add( $data, 'users_sent_emails' );
    }
    
    /**
     * Delete Sent Email Record ( User ).
     *
     * @param  integer $id
     * @return boolean
     */
    public function delete_sent_email( $id )
    {
        $data['table'] = 'users_sent_emails';
        $data['column_value'] = $id;
        
        return $this->delete( $data );
    }
    
    /**
     * Delete User
     *
     * @param  integer $id
     * @return boolean
     */
    public function delete_user( $id )
    {
        $data['column_value'] = $id;
        
        return $this->delete( $data );
    }
    
    /**
     * Mark Email Address as Verified.
     *
     * @param  integer $user_id
     * @return boolean
     */
    public function mark_as_everified( $user_id )
    {
        $data['data']['is_verified'] = 1;
        $data['column_value'] = $user_id;

        return $this->update( $data );
    }
    
    /**
     * Update Password
     *
     * @param  integer $id
     * @param  string  $password
     * @return boolean
     */
    public function update_password( $id, $password )
    {
        $data['column_value'] = $id;
        $password = password_hash( $password, PASSWORD_DEFAULT );
        $data['data'] = ['password' => $password];

        return $this->update( $data );
    }
    
    /**
     * Invites
     *
     * @param  array $options
     * @return mixed
     */
    public function invites( array $options = [] )
    {
        $data['select'] = 'ui.*, u.first_name, u.last_name';
        $data['join'] = ['table' => 'users u', 'on' => 'u.id = ui.user_id'];
        $data['table'] = 'users_invites ui';
        
        if ( ! empty( $options['id'] ) )
        {
            $data['where'] = ['ui.id' => $options['id']];
            return $this->get_one( $data ); 
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
     * Invitation by ID
     *
     * @param  string $code
     * @return object
     */
    public function invitation_by_code( $code )
    {
        $data['where'] = ['invitation_code' => $code];
        $data['table'] = 'users_invites';
        
        return $this->get_one( $data );
    }
    
    /**
     * Add Invitation
     *
     * @param  array $data
     * @return mixed
     */
    public function add_invitation( $data )
    {
        return $this->add( $data, 'users_invites' );
    }
    
    /**
     * Update Invitation
     *
     * @param  array   $to_update
     * @param  integer $id
     * @return boolean
     */
    public function update_invitation( $to_update, $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'users_invites';
        $data['data'] = $to_update;

        return $this->update( $data );
    }
    
    /**
     * Mark as Used the Invitation.
     *
     * @param  string  $code
     * @param  integer $user_id
     * @return boolean
     */
    public function invitation_mark_as_used( $code, $user_id )
    {
        $data['column'] = 'invitation_code';
        $data['column_value'] = $code;
        $data['table'] = 'users_invites';
        $data['update_time'] = false;
        $data['data'] = ['status' => 1, 'user_id' => $user_id];

        return $this->update( $data );
    }
    
    /**
     * Delete Invitation
     *
     * @param  integer $id
     * @return boolean
     */
    public function delete_invitation( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'users_invites';
        
        return $this->delete( $data );
    }
}
