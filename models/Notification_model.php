<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Notification Model
 *
 * @author Shahzaib
 */
class Notification_model extends MY_Model {
    
    /**
     * Notifications
     *
     * @param  array $options
     * @return mixed
     */
    public function notifications( array $options )
    {
        $data['table'] = 'notifications';
        $data['where'] = ['user_id' => $this->zuser->get('id')];
        
        if ( array_key_exists( 'for_team_member', $options ) )
        {
            $data['where']['for_team_member'] = $options['for_team_member'];
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
     * Notification
     *
     * @param  integer $id
     * @return object
     */
    public function notification( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'notifications';
        
        return $this->get_one( $data );
    }
    
    /**
     * Check for New Notifications
     *
     * @param  boolean $user_area
     * @return integer
     */
    public function check_for_new_notifications( $user_area = false )
    {
        $data['where']['user_id'] = $this->zuser->get( 'id' );
        $data['where']['is_read'] = 0;
        $data['table'] = 'notifications';
        
        if ( $user_area === true && ! $this->zuser->is_team_member() )
        {
            $data['where']['for_team_member'] = 0;
        }
            
        return $this->get_count( $data );
    }
    
    /**
     * Mark as Read the Notification
     *
     * @param  integer $id
     * @return boolean
     */
    public function mark_as_read( $id = null )
    {
        if ( $id !== null ) $data['where']['id'] = $id;
        
        $data['where']['user_id'] = $this->zuser->get( 'id' );
        $data['where']['is_read'] = 0;
        $data['table'] = 'notifications';
        $data['data'] = ['is_read' => 1];
       
        return $this->update( $data );
    }
    
    /**
     * Send Notification
     *
     * @param  string  $key Language key without "notify_" prefix.
     * @param  string  $location
     * @param  integer $user_id
     * @param  integer $for_team_member Only 0, 1 
     * @return void
     */
    public function send_notification( $key, $location, $user_id, $for_team_member = 0 )
    {
        $this->add(
        [
            'message_key' => $key,
            'location' => $location,
            'user_id' => $user_id,
            'for_team_member' => $for_team_member,
            'created_at' => time()
        ], 'notifications' );
    }
    
    /**
     * Delete Notifications
     *
     * @param  integer $user_id
     * @return boolean
     */
    public function delete_notifications( $user_id )
    {
        $data['where']['user_id'] = $user_id;
        $data['table'] = 'notifications';
        
        return $this->delete( $data );
    }
}
