<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Report Model
 *
 * @author  Shahzaib
 * @version 1.4
 */
class Report_model extends MY_Model {
    
    /**
     * Class Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->table = 'reports';
    }
    
    /**
     * Reports
     *
     * @param  boolean $count
     * @param  integer $limit
     * @param  integer $offset
     * @return mixed
     */
    public function reports( $count = false, $limit = 0, $offset = 0 )
    {
        $data['limit'] = $limit;
        $data['offset'] = $offset;
        
        if ( $count === true )
        {
            return $this->get_count( $data );
        }
        
        return $this->get( $data );
    }
    
    /**
     * Report
     *
     * @param  integer $id
     * @return object
     */
    public function report( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'reports';
        
        return $this->get_one( $data );
    }
    
    /**
     * Users Count
     *
     * @param  integer $period
     * @return integer
     */
    public function users( $period )
    {
        $data['table'] = 'users';
        
        if ( ! empty( $period ) )
        {
            $data['where'] = ['registered_at >=' => $period];
        }
        
        return $this->get_count( $data );
    }
    
    /**
     * Opened Tickets Count
     *
     * @param  integer $period
     * @return integer
     */
    public function opened_tickets( $period )
    {
        $data['table'] = 'tickets';
        $data['where']['status'] = 1;
        
        if ( ! empty( $period ) )
        {
            $data['where']['created_at >='] = $period;
        }
        
        return $this->get_count( $data );
    }
    
    /**
     * Closed Tickets Count
     *
     * @param  integer $period
     * @return integer
     */
    public function closed_tickets( $period )
    {
        $data['table'] = 'tickets';
        $data['where']['status'] = 0;
        
        if ( ! empty( $period ) )
        {
            $data['where']['created_at >='] = $period;
        }
        
        return $this->get_count( $data );
    }
    
    /**
     * Solved Tickets Count
     *
     * @param  integer $period
     * @return integer
     */
    public function solved_tickets( $period )
    {
        $data['table'] = 'tickets';
        $data['where']['sub_status'] = 3;
        
        if ( ! empty( $period ) )
        {
            $data['where']['created_at >='] = $period;
        }
        
        return $this->get_count( $data );
    }
    
    /**
     * Total Tickets Count
     *
     * @param  integer $period
     * @return integer
     */
    public function total_tickets( $period )
    {
        $data['table'] = 'tickets';
        
        if ( ! empty( $period ) )
        {
            $data['where']['created_at >='] = $period;
        }
        
        return $this->get_count( $data );
    }
    
    /**
     * Active Chats Count
     *
     * @param  integer $period
     * @return integer
     */
    public function active_chats( $period )
    {
        $data['table'] = 'chats';
        $data['where']['status'] = 1;
        
        if ( ! empty( $period ) )
        {
            $data['where']['created_at >='] = $period;
        }
        
        return $this->get_count( $data );
    }
    
    /**
     * Ended Chats Count
     *
     * @param  integer $period
     * @return integer
     */
    public function ended_chats( $period )
    {
        $data['table'] = 'chats';
        $data['where']['status'] = 0;
        
        if ( ! empty( $period ) )
        {
            $data['where']['created_at >='] = $period;
        }
        
        return $this->get_count( $data );
    }
    
    /**
     * Total Chats Count
     *
     * @param  integer $period
     * @return integer
     */
    public function total_chats( $period )
    {
        $data['table'] = 'chats';
        
        if ( ! empty( $period ) )
        {
            $data['where']['created_at >='] = $period;
        }
        
        return $this->get_count( $data );
    }
    
    /**
     * Add Report
     *
     * @param  array $data
     * @return mixed
     */
    public function add_report( $data )
    {
        return $this->add( $data );
    }
    
    /**
     * Delete Report
     *
     * @param  integer $id
     * @return boolean
     */
    public function delete_report( $id )
    {
        $data['column_value'] = $id;
        
        return $this->delete( $data );
    }
}
