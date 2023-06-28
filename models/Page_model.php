<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Page Model
 *
 * @author Shahzaib
 */
class Page_model extends MY_Model {
    
    /**
     * Class Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->table = 'pages';
    }
    
    /**
     * Pages
     *
     * @return object
     */
    public function pages()
    {
        return $this->get();
    }
    
    /**
     * Page
     *
     * @param  integer $id
     * @return object
     */
    public function page( $id )
    {
        $data['column_value'] = $id;
        
        return $this->get_one( $data );
    }
    
    /**
     * Update Page
     *
     * @param  array   $to_update
     * @param  integer $id
     * @return boolean
     */
    public function update_page( $to_update, $id )
    {
       $data['column_value'] = $id;
       $data['data'] = $to_update;
       
       return $this->update( $data );
    }
    
    /**
     * Custom Pages
     *
     * @param   integer $visibility
     * @return  object
     * @version 1.8
     */
    public function custom_pages( $visibility = null )
    {
        $data['select'] = 'custom_pages.*, users.first_name, users.last_name';
        $data['join'] = ['table' => 'users', 'on' => 'users.id = custom_pages.created_by'];
        
        if ( $visibility !== null )
        {
            $data['where']['custom_pages.visibility'] = $visibility;
        }
        
        $data['table'] = 'custom_pages';
        
        return $this->get( $data );
    }
    
    /**
     * Custom Pages Slug
     *
     * @param   string  $name
     * @param   integer $id
     * @return  string
     * @version 1.8
     */
    public function custom_pages_slug( $name, $id = 0 )
    {
        $slug = url_slug( $name );
        $result = 0;
        
        $data['like_column'] = 'slug';
        $data['like_column_value'] = $slug;
        $data['table'] = 'custom_pages';
        
        if ( ! empty( $id ) ) $data['where']['id !='] = $id;
        
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
     * Is Custom Page Exists ( by Specific Column ).
     *
     * @param   mixed   $value
     * @param   integer $id
     * @param   string  $column
     * @return  boolean
     * @version 1.8
     */
    public function is_cp_exists_by( $value, $id, $column = 'slug' )
    {
        $data['where'] = [$column => $value, 'id !=' => $id];
        $data['table'] = 'custom_pages';
        
        return ! empty( $this->get_one( $data ) );
    }
    
    /**
     * Custom Page
     *
     * @param   mixed   $value
     * @param   string  $column
     * @param   integer $visibility
     * @return  object
     * @version 1.8
     */
    public function custom_page( $value, $column = 'id', $visibility = null )
    {
        $data['table'] = 'custom_pages';
        $data['where'][$column] = $value;
        
        if ( $visibility !== null )
        {
            $data['where']['visibility'] = $visibility;
        }
        
        return $this->get_one( $data );
    }
    
    /**
     * Add Custom Page
     *
     * @param   array $data
     * @return  mixed
     * @version 1.8
     */
    public function add_custom_page( $data )
    {
        return $this->add( $data, 'custom_pages' );
    }
    
    /**
     * Update Custom Page
     *
     * @param   array   $to_update
     * @param   integer $id
     * @return  boolean
     * @version 1.8
     */
    public function update_custom_page( $to_update, $id )
    {
       $data['column_value'] = $id;
       $data['table'] = 'custom_pages';
       $data['data'] = $to_update;
       
       return $this->update( $data );
    }
    
    /**
     * Delete Custom Page
     *
     * @param   integer $id
     * @return  boolean
     * @version 1.8
     */
    public function delete_custom_page( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'custom_pages';
        
        return $this->delete( $data );
    }
}
