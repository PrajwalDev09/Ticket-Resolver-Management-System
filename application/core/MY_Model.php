<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * MY Model ( The Base Model ).
 *
 * A class that is having some common database queries.
 *
 * @author Shahzaib
 */
class MY_Model extends CI_Model {
    
    /**
     * Database Table Name
     *
     * @var string
     */
    protected $table;
    
    
    /**
     * Add Where IN Clause(s)
     *
     * Use to append the where_in AND clauses with query builder methods.
     *
     * @param   array $options
     * @return  void
     * @version 1.4
     */
    private function add_wheres_in( array $options )
    {
        if ( ! empty( $options['where_in'] ) )
        {
            if ( ! is_array( @$options['where_in'] ) )
            {
                foreach ( $options['where_in'] as $condition )
                {
                    $this->db->where_in( $condition['column'], $condition['values'] );
                }
            }
            else
            {
                $condition = $options['where_in'];
                $this->db->where_in( $condition['column'], $condition['values'] );
            }
        }
    }
    
    /**
     * Add LIKE Operators
     *
     * @param   array $options
     * @return  void
     * @version 1.3
     */
    private function add_like_operators( $options )
    {
        if ( empty( $options['like_column_value'] ) )
        {
            if ( ! empty( $options['like'] ) && is_array( @$options['like'] ) )
            {
                $this->db->group_start();
                $this->db->or_like( $options['like'] );
                $this->db->group_end();
            }
        }
        else if ( ! empty( $options['like_column'] ) && ! empty( $options['like_column_value'] ) )
        {
            $this->db->like( $options['like_column'], $options['like_column_value'] );
        }
    }
    
    /**
     * Manage Table ( Database Table Name ).
     *
     * If the property called "$table" used by the child class, its
     * value will be treated as default until passed the table name
     * to the calling method of model.
     *
     * @param  string $table
     * @return string
     */
    private function manage_table( $table )
    {
        if ( ! empty( $this->table ) && empty( $table ) )
        {
            return $this->table;
        }
        
        if ( empty( $table ) ) exit( 'Missing Table Name' );
        
        return $table;
    }
    
    /**
     * Manage Column Name
     *
     * If the variable with empty value is passed as argument,
     * "id" will be returned.
     *
     * @param  string $column
     * @return string
     */
    private function manage_column( $column )
    {
        if ( ! empty( $column ) )
        {
            return $column;
        }
        
        return DEFAULT_DB_COLUMN;
    }
    
    /**
     * Add Where Clause(s)
     *
     * Use to append the where AND clauses with query builder methods.
     *
     * @param  array $options
     * @return void
     */
    private function add_wheres( array $options )
    {
        $column = $this->manage_column( @$options['column'] );
        
        if ( empty( $options['column_value'] ) )
        {
            if ( ! empty( $options['where'] ) && is_array( @$options['where'] ) )
            {
                $this->db->where( $options['where'] );
            }
        }
        else
        {
            $this->db->where( $column, $options['column_value'] );
        }
    }
    
    /**
     * Add Record
     *
     *
     * @param  array  $data
     * @param  string $table
     * @return mixed
     */
    public function add( $data, $table = '' )
    {
        if ( is_array( $data ) )
        {
            $this->db->insert( $this->manage_table( $table ), $data );
            
            if ( $this->db->affected_rows() )
            {
                return $this->db->insert_id();
            }
        }
        
        return false;
    }
    
    /**
     * Manage Join Type
     *
     * @param  string $type
     * @return string
     */
    private function manage_join_type( $type )
    {
        if ( ! empty( $type ) )
        {
            return $type;
        }
        
        return 'LEFT';
    }
    
    /**
     * Get Single or Multiple Records.
     *
     * Use to get the records from database. Supports common selecting
     * options ( conditioning, joining, limiting, orders ).
     *
     * @param  array   $options
     * @param  boolean $is_single
     * @return object
     */
    public function get( array $options = [], $is_single = false )
    {
        $table = $this->manage_table( @$options['table'] );
        $order = 'DESC';
        
        if ( ! empty( $options['select'] ) )
        {
            $this->db->select( $options['select'] );
        }
        
        if ( ! empty( $options['join'] ) && is_array( @$options['join'] ) )
        {
            $this->db->from( $table );
            
            if ( is_having_array( $options['join'] ) )
            {
                foreach ( $options['join'] as $join )
                {
                    $this->db->join( $join['table'], $join['on'], $this->manage_join_type( @$join['type'] ) );
                }
            }
            else
            {
                $join = $options['join'];
                $this->db->join( $join['table'], $join['on'], $this->manage_join_type( @$join['type'] ) );
            }
        }
        
        $this->add_like_operators( $options );
        
        $this->add_wheres( $options );
        
        $this->add_wheres_in( $options );
        
        if ( ! empty( $options['or_where'] ) && is_array( $options['or_where'] ) )
        {
            $this->db->group_start();
            $this->db->or_where( $options['or_where'] );
            $this->db->group_end();
        }
        
        if ( ! empty( $options['limit'] ) )
        {
            if ( ! empty( $options['offset'] ) )
            {
                $this->db->limit( $options['limit'], $options['offset'] );
            }
            else
            {
                $this->db->limit( $options['limit'] );
            }
        }
        
        if ( ! empty( $options['order'] ) )
        {
            $order = $options['order'];
        }
        
        if ( empty( $options['orderby_column'] ) )
        {
            $options['orderby_column'] = 'id';
        }
        
        $this->db->order_by( $options['orderby_column'], $order );
        
        if ( empty( $options['join'] ) )
        {
            $data = $this->db->get( $table );
        }
        else
        {
            $data = $this->db->get();
        }
        
        if ( $data->num_rows() > 0 )
        {
            if ( $is_single === false )
            {
                return $data->result();
            }
            
            return $data->row();
        }
    }
    
    /**
     * Get Record
     *
     * @param  array $options
     * @return object
     */
    public function get_one( array $options )
    {
        if ( ! empty( $options['column_value'] ) || ! empty( $options['where'] ) )
        {
            return $this->get( $options, true );
        }
    }
    
    /**
     * Get Count
     *
     * Use to get the records count of a table.
     *
     * @param  array $options
     * @return integer
     */
    public function get_count( array $options = [] )
    {
        $this->add_like_operators( $options );
        
        $this->add_wheres( $options );
        
        return $this->db->count_all_results( $this->manage_table( @$options['table'] ) );
    }
    
    /**
     * Get Managed Options
     *
     * Use to combine the multiple database records into a single array.
     * Required DB columns are "access_key" and "value".
     *
     * @param  string $table
     * @return array
     */
    public function get_managed_options( $table = '' )
    {
        $settings = $this->db->get( $this->manage_table( $table ) );
        $options = [];
        
        if ( $settings->num_rows() > 0 )
        {
            $settings = $settings->result();
            
            foreach ( $settings as $setting )
            {
                $options[$setting->access_key] = $setting->value;
            }
            
            return $options;
        }
    }
    
    /**
     * Update Options
     *
     * Use to update the multiple records in a single call.
     * Required DB columns are "access_key" and "value".
     *
     * @param  array  $data
     * @param  string $table
     * @return void
     */
    public function update_options( array $data, $table = '' )
    {
        foreach ( $data as $key => $value )
        {
            $this->db->where( 'access_key', $key );
            $this->db->update( $this->manage_table( $table ), ['value' => $value] );
        }
    }
    
    /**
     * Update Record
     *
     * Use to update a single or multiple records of a database table. This
     * method will automatically update the value of "updated_at" column if
     * the table is having it.
     *
     * @param  array $options
     * @return boolean
     */
    public function update( array $options )
    {
        $this->add_wheres( $options );
        
        if ( ! isset( $options['update_time'] ) )
        {
            $options['update_time'] = true;
        }
        
        if ( ! empty( $options['data'] ) || ! empty( $options['set'] ) )
        {
            $column = $this->manage_column( @$options['column'] );
            $table = $this->manage_table( @$options['table'] );
            
            if ( ! empty( $options['set'] ) )
            {
                if ( is_array( $options['set'] ) )
                {
                    foreach ( $options['set'] as $key => $value )
                    {
                        $this->db->set( $key, $value, false );
                    }
                }
                
                $this->db->update( $table );
            }
            else
            {
                $this->db->update( $table, $options['data'] );
            }
            
            if ( $this->db->affected_rows() )
            {
                if ( ! empty( $options['column_value'] ) && $options['update_time'] === true )
                {
                    if ( $this->db->field_exists( 'updated_at', $table ) )
                    {
                        $this->db->where( $column, $options['column_value'] );
                        $this->db->update( $table, ['updated_at' => time()] );
                    }
                }
                
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Delete All Records
     *
     * @param  string $table
     * @return boolean
     */
    public function delete_all( $table = '' )
    {
        $this->db->empty_table( $this->manage_table( $table ) );
        
        if ( $this->db->affected_rows() )
        {
            return true;
        }
        
        return false;
    }
    
    /**
     * Delete Record
     *
     * @param  array $options
     * @return boolean
     */
    public function delete( array $options )
    {
        if ( ! empty( $options['column_value'] ) || ! empty( $options['where'] ) )
        {
            $this->add_wheres( $options );
            
            $this->db->delete( $this->manage_table( @$options['table'] ) );
           
            if ( $this->db->affected_rows() )
            {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Generate Slug
     *
     * @param  string  $table
     * @param  string  $name Name/Label/Title
     * @param  integer $id
     * @param  boolean $has_parent
     * @return string
     */
    public function generate_slug( $table, $name, $id = 0, $has_parent = '' )
    {
        $slug = url_slug( $name );
        $result = 0;
        
        $data['like_column'] = 'slug';
        $data['like_column_value'] = $slug;
        $data['table'] = $table;
        
        if ( ! empty( $id ) ) $data['where']['id !='] = $id;
        
        if ( $has_parent === true ) $data['where']['parent_id !='] = null;
        else if ( $has_parent === false ) $data['where']['parent_id'] = null;
        
        $result = $this->get( $data );
        
        if ( ! empty( $result ) )
        {
            $count = count( $result );
            
            if ( $count > 0 )
            {
                $slug .= '-' . $count;
            }
        }
        
        return $slug;
    }
    
    /**
     * Update Views
     *
     * @param  integer $id
     * @param  string  $table
     * @return void
     */
    public function update_views( $id, $table )
    {
        $data['where'] = ['id' => $id];
        $data['table'] = $table;
        $data['set'] = ['views' => 'views+1'];
        
        $this->update( $data );
    }
}
