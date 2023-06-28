<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Setting Model
 *
 * @author Shahzaib
 */
class Setting_model extends MY_Model {
    
    /**
     * Class Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->table = 'settings';
    }
    
    /**
     * Roles
     *
     * @return object
     */
    public function roles()
    {
        $data['table'] = 'roles';
        $data['order'] = 'ASC';
        
        return $this->get( $data );
    }
    
    /**
     * Roles Admin
     *
     * Use to get the roles IDs that are having the
     * tickets and chats modules permissions.
     *
     * @return object
     */
    public function roles_admin()
    {
        $data['select'] = 'r.id';
        $data['table'] = 'roles r';
        $data['join'] = ['table' => 'roles_permissions rp', 'on' => 'rp.role_id = r.id'];
        
        // Add more permissions IDs in the array if want need:
        $data['where_in'] = ['column' => 'rp.permission_id', 'values' => [2,15]];
        
        $ids = [];
        
        if ( ! empty( $roles = $this->get( $data ) ) )
        {
            foreach ( $roles as $role )
            {
                $ids[] = $role->id;
            }
        }
        
        $ids = array_unique( $ids );
        
        return $ids;
    }
    
    /**
     * Role
     *
     * @param  integer $id
     * @return object
     */
    public function role( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'roles';
        
        return $this->get_one( $data );
    }
    
    /**
     * Is Role Exists
     *
     * @param  string  $access_key
     * @param  integer $id
     * @return boolean
     */
    public function is_role_exists( $access_key, $id = null )
    {
        $data['where']['access_key'] = $access_key;
        $data['table'] = 'roles';
        
        if ( $id !== null )
        $data['where']['id !='] = $id;
        
        if ( $this->get_one( $data ) )
        {
            return true;
        }
        
        return false;
    }
    
    /**
     * Add Role
     *
     * @param  array $data
     * @return mixed
     */
    public function add_role( $data )
    {
        return $this->add( $data, 'roles' );
    }
    
    /**
     * Update Role
     *
     * @param  array   $to_update
     * @param  integer $id
     * @return boolean
     */
    public function update_role( $to_update, $id  )
    {
        $data['column_value'] = $id;
        $data['table'] = 'roles';
        $data['data'] = $to_update;

        return $this->update( $data );
    }
    
    /**
     * Role Permissions
     *
     * @param  integer $id
     * @return array   IDs
     */
    public function role_permissions( $id )
    {
        $data['column'] = 'role_id';
        $data['column_value'] = $id;
        $data['table'] = 'roles_permissions';
        
        $data = $this->get( $data );
        $arr = [];
        
        if ( ! empty( $data ) )
        {
            foreach ( $data as $d )
            {
                $arr[] = $d->permission_id;
            }
        }
        
        return $arr;
    }
    
    /**
     * Role Has Permission
     *
     * @param  integer $role_id
     * @param  integer $perm_id
     * @return boolean
     */
    public function role_has_permission( $role_id, $perm_id )
    {
        $data['where'] = ['role_id' => $role_id, 'permission_id' => $perm_id];
        $data['table'] = 'roles_permissions';
        
        if ( $this->get_one( $data ) )
        {
            return true;
        }
        
        return false;
    }
    
    /**
     * Assign Permission to Role
     *
     * @param  integer $role_id
     * @param  integer $perm_id
     * @return mixed
     */
    public function assign_permission( $role_id, $perm_id )
    {
        $data = ['role_id' => $role_id, 'permission_id' => $perm_id];
        
        return $this->add( $data, 'roles_permissions' );
    }
    
    /**
     * Delete Role Permissions ( NOT IN ).
     *
     * @param  integer $role_id
     * @param  array   $ids
     * @return boolean
     */
    public function delete_role_permissions_ni( $role_id, $ids )
    {
        $this->db->where( 'role_id', $role_id );
        
        // Delete all the permissions, whos IDs are not available
        // in the $ids variable.
        $this->db->where_not_in( 'permission_id', $ids );
        
        $this->db->delete( 'roles_permissions' );
           
        if ( $this->db->affected_rows() )
        {
            return true;
        }
        
        return false;
    }
    
    /**
     * Delete Role Permissions
     *
     * @param  integer $id
     * @return boolean
     */
    public function delete_role_permissions( $id )
    {
        $data['column'] = 'role_id';
        $data['column_value'] = $id;
        $data['table'] = 'roles_permissions';
        
        return $this->delete( $data );
    }
    
    /**
     * Delete Role
     *
     * @param  integer $id
     * @return boolean
     */
    public function delete_role( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'roles';
        
        return $this->delete( $data );
    }
    
    /**
     * Permissions
     *
     * @return object
     */
    public function permissions()
    {
        $data['table'] = 'permissions';
        $data['order'] = 'ASC';
        
        return $this->get( $data );
    }
    
    /**
     * Permission
     *
     * @param  integer $id
     * @return object
     */
    public function permission( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'permissions';
        
        return $this->get_one( $data );
    }
    
    /**
     * Update Permission
     *
     * @param  array   $to_update
     * @param  integer $id
     * @return boolean
     */
    public function update_permission( $to_update, $id  )
    {
        $data['column_value'] = $id;
        $data['table'] = 'permissions';
        $data['data'] = $to_update;

        return $this->update( $data );
    }
}
