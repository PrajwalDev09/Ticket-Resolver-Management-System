<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Custom Field Model
 *
 * @author  Shahzaib
 * @version 1.5
 */
class Custom_field_model extends MY_Model {
    
    /**
     * Class Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->table = 'custom_fields';
    }
    
    /**
     * Custom Fields
     *
     * @param  string  $order
     * @param  integer $visibility
     * @return object
     */
    public function custom_fields( $order = 'DESC', $visibility = null )
    {
        $data['order'] = $order;
        
        if ( $visibility !== null )
        {
            $data['where']['visibility'] = $visibility;
        }
        
        return $this->get( $data );
    }
    
    /**
     * Custom Field
     *
     * @param  integer $id
     * @return object
     */
    public function custom_field( $id )
    {
        $data['column_value'] = $id;
        
        return $this->get_one( $data );
    }
    
    /**
     * Custom Fields Data
     *
     * @param  integer $ticket_id
     * @return object
     */
    public function custom_fields_data( $ticket_id )
    {
        $data['select'] = 'cf.*, tcf.value';
        $data['table'] = 'custom_fields cf';
        
        $data['join'] = [
            'table' => 'tickets_custom_fields tcf',
            'on' => "tcf.custom_field_id = cf.id AND tcf.ticket_id = {$ticket_id}"
        ];
        
        $data['orderby_column'] = 'cf.id';
        $data['order'] = 'ASC';
        
        return $this->get( $data );
    }
    
    /**
     * Add Custom Field
     *
     * @param  array $data
     * @return mixed
     */
    public function add_custom_field( $data )
    {
        return $this->add( $data );
    }
    
    /**
     * Update Custom Field
     *
     * @param  array   $to_update
     * @param  integer $id
     * @return boolean
     */
    public function update_custom_field( $to_update, $id )
    {
        $data['column_value'] = $id;
        $data['data'] = $to_update;

        return $this->update( $data );
    }
    
    /**
     * Manage Custom Field Input Data
     *
     * @param  array $data
     * @return void
     */
    public function manage_custom_field_input_data( $data )
    {
        $this->add( $data, 'tickets_custom_fields' );
    }
    
    /**
     * Delete Ticket Custom Fields
     *
     * @param  integer $id
     * @return void
     */
    public function delete_ticket_custom_fields( $id )
    {
        $data['where']['ticket_id'] = $id;
        $data['table'] = 'tickets_custom_fields';
        
        $this->delete( $data );
    }
    
    /**
     * Delete Tickets Custom Fields
     *
     * @param  integer $id Custom Field ID
     * @return void
     */
    public function delete_tickets_custom_fields( $id )
    {
        $data['where']['custom_field_id'] = $id;
        $data['table'] = 'tickets_custom_fields';
        
        $this->delete( $data );
    }
    
    /**
     * Delete Custom Field
     *
     * @param  integer $id
     * @return boolean
     */
    public function delete_custom_field( $id )
    {
        $data['column_value'] = $id;
        
        return $this->delete( $data );
    }
}
