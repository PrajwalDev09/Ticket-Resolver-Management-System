<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Support Model
 *
 * @author Shahzaib
 */
class Support_model extends MY_Model {
    
    /**
     * Ticket Notes
     *
     * @param   array $options
     * @return  mixed
     * @version 1.8
     */
    public function ticket_notes( array $options )
    {
        $data['select'] = 'tn.*, u.username';
        $data['table'] = 'tickets_notes tn';
        $data['join'] = ['table' => 'users u', 'on' => 'u.id = tn.user_id'];
        $data['where'] = ['tn.ticket_id' => $options['ticket_id']];
        
        if ( ! empty( $options['limit'] ) ) $data['limit'] = $options['limit'];
        
        if ( ! empty( $options['offset'] ) ) $data['offset'] = $options['offset'];
        
        if ( @$options['count'] === true )
        {
            return $this->get_count( $data );
        }
        
        return $this->get( $data );
    }
    
    /**
     * Ticket Note
     *
     * @paramm  integer $id
     * @return  object
     * @version 1.8
     */
    public function ticket_note( $id )
    {
        $data['where'] = ['id' => $id];
        $data['table'] = 'tickets_notes';
        
        return $this->get_one( $data );
    }
    
    /**
     * Add Ticket Note
     *
     * @param   array $data
     * @return  mixed
     * @version 1.8
     */
    public function add_ticket_note( $data )
    {
        return $this->add( $data, 'tickets_notes' );
    }
    
    /**
     * Delete Ticket Note
     *
     * @param   integer $id
     * @return  void
     * @version 1.8
     */
    public function delete_ticket_note( $id )
    {
        $data['where']['id'] = $id;
        $data['table'] = 'tickets_notes';
        
        return $this->delete( $data );
    }
    
    /**
     * Delete Ticket Notes
     *
     * @param   integer $id Ticket ID
     * @return  void
     * @version 1.8
     */
    public function delete_ticket_notes( $id )
    {
        $data['where']['ticket_id'] = $id;
        $data['table'] = 'tickets_notes';
        
        $this->delete( $data );
    }
    
    /**
     * Save Ticket Attachment
     *
     * @param   array $data
     * @return  mixed
     * @version 1.7
     */
    public function save_ticket_attachment( $data )
    {
        return $this->add( $data, 'tickets_attachments' );
    }

    /**
     * Ticket Attachments
     *
     * @param   integer $id Ticket ID
     * @return  mixed
     * @version 1.7
     */
    public function ticket_attachments( $id )
    {
        $data['table'] = 'tickets_attachments';
        $data['where']['ticket_id'] = $id;
        
        return $this->get( $data );
    }

    /**
     * Delete Ticket Attachments
     *
     * @param   integer $id
     * @return  boolean
     * @version 1.7
     */
    public function delete_ticket_attachments( $id )
    {
        $data['table'] = 'tickets_attachments';
        $data['where']['ticket_id'] = $id;
        
        return $this->delete( $data );
    }
    
    /**
     * Save Ticket Reply Attachment
     *
     * @param   array $data
     * @return  mixed
     * @version 1.7
     */
    public function save_ticket_reply_attachment( $data )
    {
        return $this->add( $data, 'tickets_replies_attachments' );
    }

    /**
     * Ticket Reply Attachments
     *
     * @param   integer $id Ticket Reply ID
     * @return  mixed
     * @version 1.7
     */
    public function ticket_reply_attachments( $id )
    {
        $data['table'] = 'tickets_replies_attachments';
        $data['where']['ticket_reply_id'] = $id;
        
        return $this->get( $data );
    }

    /**
     * Delete Ticket Reply Attachments
     *
     * @param   integer $id
     * @return  boolean
     * @version 1.7
     */
    public function delete_ticket_reply_attachments( $id )
    {
        $data['table'] = 'tickets_replies_attachments';
        $data['where']['ticket_reply_id'] = $id;
        
        return $this->delete( $data );
    }

    /**
     * Mark Email Address as Verified.
     *
     * @param   integer $user_id
     * @return  boolean
     * @version 1.6
     */
    public function mark_as_tverified( $ticket_id )
    {
        $data['data']['is_verified'] = 1;
        $data['column_value'] = $ticket_id;
        $data['table'] = 'tickets';

        return $this->update( $data );
    }
    
    /**
     * Increment the Email Sending Attemp
     *
     * @param   integer $id
     * @return  boolean
     * @version 1.6
     */
    public function increment_email_sending_attempt( $id )
    {
        $data['where'] = ['id' => $id];
        $data['table'] = 'tickets';
        
        $data['set'] = [
            'email_attempts' => 'email_attempts+1',
            'last_email_attempt' => time()
        ];
        
        return $this->update( $data );
    }
    
    /**
     * Guest Ticket
     *
     * @param   string  $security_key
     * @param   integer $id
     * @return  object
     * @version 1.4
     */
    public function guest_ticket( $security_key, $id )
    {
        $select = 't.*, td.name as department, u1.first_name, u1.last_name,';
        $select .= 'u1.picture as user_picture, u2.first_name as au_first_name,';
        $select .= 'u2.last_name as au_last_name';
        
        $data['select'] = $select;
        $data['table'] = 'tickets t';
        $data['where'] = ['t.id' => $id, 't.security_key' => $security_key];
        
        $data['join'] = [
            ['table' => 'tickets_departments td', 'on' => 'td.id = t.department_id'],
            ['table' => 'users u1', 'on' => 'u1.id = t.user_id'],
            ['table' => 'users u2', 'on' => 'u2.id = t.assigned_to']
        ];
        
        return $this->get_one( $data );
    }
    
    /**
     * Global Departments
     *
     * @return  object
     * @version 1.4
     */
    public function global_departments()
    {
        $data['where'] = ['team' => 'all_users'];
        $data['table'] = 'tickets_departments';
        
        return $this->get( $data );
    }
    
    /**
     * Local Departments
     *
     * @param   boolean $ids
     * @return  mixed
     * @version 1.4
     */
    public function local_departments( $ids = true )
    {
        $data['where'] = ['team !=' => 'all_users'];
        $data['table'] = 'tickets_departments';
        
        $results = $this->get( $data );
        
        if ( ! empty( $results ) && $ids === true )
        {
            $ids = [];
            
            foreach ( $results as $result )
            {
                $team = json_decode( $result->team, true );
                $user_id = $this->zuser->get( 'id' );
                
                if ( in_array( $user_id, $team['users'] ) )
                {
                    $ids[] = $result->id;
                }
            }
            
            return $ids;
        }
        
        return $results;
    }
    
    /**
     * Departments IDs ( Logged-in User )
     *
     * @return  array
     * @version 1.4
     */
    public function departments_ids_mine()
    {
        $ids = [];
        $global = $this->global_departments();
        
        if ( ! empty( $global ) )
        {
            foreach ( $global as $g )
            {
                $ids[] = $g->id;
            }
        }
        
        $local = $this->local_departments();
        
        if ( ! empty( $local ) )
        {
            foreach ( $local as $id )
            {
                $ids[] = $id;
            }
        }
        
        return $ids;
    }
    
    /**
     * Chats
     *
     * @param   array $options
     * @return  mixed
     * @version 1.4
     */
    public function chats( array $options = [] )
    {
        $select = 'c.*, u.first_name, u.last_name,';
        $select .= 'u.picture as user_picture, r.first_name as r_first_name,';
        $select .= 'r.last_name as r_last_name';
        
        $data['select'] = $select;
        $data['table'] = 'chats c';
        
        $data['join'] = [
            ['table' => 'users u', 'on' => 'u.id = c.assigned_to'],
            ['table' => 'users r', 'on' => 'r.id = c.user_id']
        ];
        
        $assigned = ( isset( $options['assigned'] ) ) ? $options['assigned'] : null;
        
        if ( ! $this->zuser->has_permission( 'all_chats' ) && $assigned === false )
        {
            $department_ids = $this->departments_ids_mine();
            
            if ( ! empty( $department_ids ) )
            {
                $data['where_in'] = ['column' => 'department_id', 'values' => $department_ids];
            }
            else return;
        }
        
        if ( ! empty( $options['user_id'] ) )
        {
            $data['where'] = ['c.user_id' => $options['user_id']];
        }
        else if ( $assigned === true )
        {
            $data['where']['c.assigned_to'] = $this->zuser->get( 'id' );
        }
        
        if ( ! empty( $options['searched'] ) )
        {
            $holders = ['c.id', 'c.subject'];
            
            foreach ( $holders as $holder )
            {
                $data['like'][$holder] = $options['searched'];
            }
        }
        
        if ( ! empty( $options['reply_status'] ) )
        {
            $data['where']['c.sub_status'] = $options['reply_status'];
        }
        
        if ( ! empty( $options['department_id'] ) )
        {
            $data['where']['c.department_id'] = $options['department_id'];
        }
        
        if ( @$options['status'] !== null ) $data['where']['c.status'] = $options['status'];
        
        if ( ! empty( $options['limit'] ) ) $data['limit'] = $options['limit'];
        
        if ( ! empty( $options['offset'] ) ) $data['offset'] = $options['offset'];
        
        if ( @$options['count'] === true )
        {
            return $this->get_count( $data );
        }
        
        return $this->get( $data );
    }
    
    /**
     * Chat
     *
     * @param   integer $id
     * @param   integer $user_id
     * @return  object
     * @version 1.4
     */
    public function chat( $id, $user_id = 0 )
    {
        $select = 'c.*, td.name as department, u1.first_name, u1.last_name,';
        $select .= 'u1.picture as user_picture, u2.first_name as au_first_name,';
        $select .= 'u2.last_name as au_last_name, u3.first_name as eb_first_name,';
        $select .= 'u3.last_name as eb_last_name';
        
        $data['select'] = $select;
        $data['table'] = 'chats c';
        $data['where'] = ['c.id' => $id];
        
        $data['join'] = [
            ['table' => 'tickets_departments td', 'on' => 'td.id = c.department_id'],
            ['table' => 'users u1', 'on' => 'u1.id = c.user_id'],
            ['table' => 'users u2', 'on' => 'u2.id = c.assigned_to'],
            ['table' => 'users u3', 'on' => 'u3.id = c.ended_by']
        ];
        
        if ( ! empty( $user_id ) )
        {
            $data['where']['c.user_id'] = $user_id;
        }
        
        $result = $this->get_one( $data );
        
        // If the logged-in user is not having the access of all the chats, check for
        // the chat authorization with the help of department users and own assignment:
        if ( ! $this->zuser->has_permission( 'all_chats' ) && empty( $user_id ) )
        {
            if ( $result->assigned_to == $this->zuser->get( 'id' ) )
            {
                return $result;
            }
            
            $department = $this->department( $result->department_id );
            
            if ( ! empty( $department ) )
            {
                $team = $department->team;
                
                if ( $team == 'all_users' )
                {
                    return $result;
                }
                
                $ids = json_decode( $team, true )['users'];
                
                if ( in_array( $this->zuser->get( 'id' ), $ids ) )
                {
                    return $result;
                }
            }
            
            return null;
        }
        
        return $result;
    }
    
    /**
     * Get Agent Related Permissions IDs
     *
     * @return  array
     * @version 1.4
     */
    public function get_agent_related_perms_ids()
    {
        $data['table'] = 'permissions';
        $results = $this->get( $data );
        $ids = [];
        
        if ( ! empty( $results ) )
        {
            $perms = ['tickets', 'chats'];
            
            foreach ( $results as $result )
            {
                if ( in_array( $result->access_key, $perms ) )
                {
                    $ids[] = $result->id;
                }
            }
        }
        
        return $ids;
    }
    
    /**
     * Get Agent Roles IDs by Permissions IDs
     *
     * @return  object
     * @version 1.4
     */
    public function get_agent_roles_ids_by_perm_ids()
    {
        $ids = $this->get_agent_related_perms_ids();
        
        $this->db->where_in( 'permission_id', $ids );
        
        $data = $this->db->get( 'roles_permissions' );
        
        if ( $data->num_rows() > 0 ) return $data->result();
    }
    
    /**
     * Is Chat Available
     *
     * Use to check if the agent(s) are online
     * or not.
     *
     * @return  boolean
     * @version 1.4
     */
    public function is_chat_available()
    {
        $results = $this->get_agent_roles_ids_by_perm_ids();
        $status = false;
        $ids = [];
        
        if ( ! empty( $results ) )
        {
            foreach ( $results as $result )
            {
                if ( ! in_array( $result->role_id, $ids ) )
                {
                    $ids[] = $result->role_id;
                }
            }
        }
        
        $this->db->where_in( 'role', $ids );
        $this->db->where( 'is_online', 1 );
        
        $data = $this->db->count_all_results( 'users' );
        
        if ( $data > 0 ) $status = true;
        
        return $status;
    }
    
    /**
     * Is Active Chat
     *
     * @param   integer $id Chat ID
     * @param   integer $user_id
     * @return  boolean
     * @version 1.4
     */
    public function is_active_chat( $id, $user_id = null )
    {
        $data['where']['id'] = $id;
        
        if ( $user_id === null )
        {
            $data['where']['user_id'] = $this->zuser->get( 'id' );
        }
        else if ( $user_id > 0 )
        {
            $data['where']['user_id'] = $user_id;
        }
        
        $data['table'] = 'chats';
        
        $chat = $this->get_one( $data );
        
        if ( ! empty( $chat ) )
        {
            if ( $chat->status == 0 ) return false;
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Chat Replies
     *
     * @param   integer $chat_id
     * @param   integer $last_id
     * @return  object
     * @version 1.4
     */
    public function chat_replies( $chat_id, $last_id = 0 )
    {
        $select = 'cr.*, u.first_name, u.last_name,';
        $select .= 'u.picture as user_picture';
        
        $data['select'] = $select;
        $data['table'] = 'chats_replies cr';
        $data['join'] = ['table' => 'users u', 'on' => 'u.id = cr.user_id'];
        $data['where'] = ['cr.chat_id' => $chat_id];
        
        if ( ! empty( $last_id ) )
        {
            $data['where']['cr.id >'] = $last_id;
        }
        
        $data['order'] = 'ASC';
        
        return $this->get( $data );
    }
    
    /**
     * Add Chat Reply
     *
     * @param   array $data
     * @return  mixed
     * @version 1.4
     */
    public function add_chat_reply( $data )
    {
        return $this->add( $data, 'chats_replies' );
    }
    
    /**
     * Delete Chat Replies
     *
     * @parma   integer $id
     * @return  void
     * @version 1.4
     */
    public function delete_chat_replies( $id )
    {
        $data['where']['chat_id'] = $id;
        $data['table'] = 'chats_replies';
        
        $this->delete( $data );
    }
    
    /**
     * Add Chat
     *
     * @param   array $data
     * @return  mixed
     * @version 1.4
     */
    public function add_chat( $data )
    {
        return $this->add( $data, 'chats' );
    }
    
    /**
     * Update Chat
     *
     * @param   array   $to_update
     * @param   integer $id
     * @param   boolean $update_time
     * @return  boolean
     * @version 1.4
     */
    public function update_chat( $to_update, $id, $update_time = true )
    {
       $data['column_value'] = $id;
       $data['table'] = 'chats';
       $data['update_time'] = false;
       $data['data'] = $to_update;
       
       if ( $update_time === true )
       {
            $data['data']['updated_at'] = time();
       }
       
       return $this->update( $data );
    }
    
    /**
     * End Chat
     *
     * @param   integer $id
     * @return  boolean
     * @version 1.4
     */
    public function end_chat( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'chats';
        $data['data'] = ['status' => 0, 'ended_by' => $this->zuser->get( 'id' )];

        return $this->update( $data );
    }
    
    /**
     * Delete Chat
     *
     * @param   integer $id
     * @return  boolean
     * @version 1.4
     */
    public function delete_chat( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'chats';
        
        return $this->delete( $data );
    }
    
    /**
     * Has Tickets ( Department )
     *
     * @param   integer $department_id
     * @return  boolean
     * @version 1.3
     */
    public function has_department_tickets( $department_id )
    {
        $data['where'] = ['department_id' => $department_id];
        $data['table'] = 'tickets';
        
        return ! empty( $this->get_one( $data ) );
    }
    
    /**
     * Canned Replies
     *
     * @param  boolean $count
     * @param  integer $limit
     * @param  integer $offset
     * @return mixed
     */
    public function canned_replies( $count = false, $limit = 0, $offset = 0 )
    {
        $data['table'] = 'canned_replies';
        $data['limit'] = $limit;
        $data['offset'] = $offset;
        
        if ( $count === true )
        {
            return $this->get_count( $data );
        }
        
        return $this->get( $data );
    }
    
    /**
     * Canned Reply
     *
     * @param  integer $id
     * @return object
     */
    public function canned_reply( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'canned_replies';
        
        return $this->get_one( $data );
    }
    
    /**
     * Add Canned Reply
     *
     * @param  array $data
     * @return mixed
     */
    public function add_canned_reply( $data )
    {
        return $this->add( $data, 'canned_replies' );
    }
    
    /**
     * Update Canned Reply
     *
     * @param  array   $to_update
     * @param  integer $id
     * @return boolean
     */
    public function update_canned_reply( $to_update, $id )
    {
       $data['column_value'] = $id;
       $data['table'] = 'canned_replies';
       $data['data'] = $to_update;
       
       return $this->update( $data );
    }
    
    /**
     * Delete Canned Reply
     *
     * @param  integer $id
     * @return boolean
     */
    public function delete_canned_reply( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'canned_replies';
        
        return $this->delete( $data );
    }
    
    /**
     * FAQs
     *
     * @param  boolean $count
     * @param  integer $limit
     * @param  integer $offset
     * @return mixed
     */
    public function faqs( $count = false, $limit = 0, $offset = 0 )
    {
        $data['select'] = 'faqs.*, faqs_categories.name as category_name';
        $data['table'] = 'faqs';
        $data['join'] = ['table' => 'faqs_categories', 'on' => 'faqs_categories.id = faqs.category_id'];
        $data['limit'] = $limit;
        $data['offset'] = $offset;
        
        if ( $count === true )
        {
            return $this->get_count( $data );
        }
        
        return $this->get( $data );
    }
    
    /**
     * FAQs by Category
     *
     * @param  integer $cat_id
     * @return object
     */
    public function faqs_by_category( $cat_id )
    {
        $data['where'] = ['category_id' => $cat_id, 'visibility' => 1];
        
        if ( ! $this->zuser->is_logged_in )
        {
            $data['where']['logged_in_only'] = 0;
        }
        
        $data['table'] = 'faqs';
        $data['order'] = 'ASC';
        
        return $this->get( $data );
    }
    
    /**
     * FAQ
     *
     * @param  integer $id
     * @return object
     */
    public function faq( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'faqs';
        
        return $this->get_one( $data );
    }
    
    /**
     * Has FAQs
     *
     * Use to check the existence of faqs of a specific category.
     *
     * @param  integer $cat_id
     * @return boolean
     */
    public function has_faqs( $cat_id )
    {
        $data['where'] = ['category_id' => $cat_id];
        $data['table'] = 'faqs';
        
        return ! empty( $this->get_one( $data ) );
    }
    
    /**
     * Add FAQ
     *
     * @param  array $data
     * @return mixed
     */
    public function add_faq( $data )
    {
        return $this->add( $data, 'faqs' );
    }
    
    /**
     * Update FAQ
     *
     * @param  array   $to_update
     * @param  integer $id
     * @return boolean
     */
    public function update_faq( $to_update, $id )
    {
       $data['column_value'] = $id;
       $data['table'] = 'faqs';
       $data['data'] = $to_update;
       
       return $this->update( $data );
    }
    
    /**
     * Delete FAQ
     *
     * @param  integer $id
     * @return boolean
     */
    public function delete_faq( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'faqs';
        
        return $this->delete( $data );
    }
    
    /**
     * FAQs Categories
     *
     * @param  string $order
     * @return object
     */
    public function faqs_categories( $order = 'DESC' )
    {
        $data['table'] = 'faqs_categories';
        $data['order'] = $order;
        
        return $this->get( $data );
    }
    
    /**
     * FAQs Category
     *
     * @param  integer $id
     * @return object
     */
    public function faqs_category( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'faqs_categories';
        
        return $this->get_one( $data );
    }
    
    /**
     * Add FAQs Category
     *
     * @param  array $data
     * @return mixed
     */
    public function add_faqs_category( $data )
    {
        return $this->add( $data, 'faqs_categories' );
    }
    
    /**
     * Update FAQs Category
     *
     * @param  array   $to_update
     * @param  integer $id
     * @return boolean
     */
    public function update_faqs_category( $to_update, $id )
    {
       $data['column_value'] = $id;
       $data['table'] = 'faqs_categories';
       $data['data'] = $to_update;
       
       return $this->update( $data );
    }
    
    /**
     * Delete FAQs Category
     *
     * @param  integer $id
     * @return boolean
     */
    public function delete_faqs_category( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'faqs_categories';
        
        return $this->delete( $data );
    }
    
    /**
     * Articles Categories
     *
     * @param  string $search
     * @param  string $order
     * @return object
     */
    public function articles_categories( $search = 'main', $order = 'DESC' )
    {
        $data['table'] = 'articles_categories';
        
        if ( $search === 'sub' ) $data['where']['parent_id !='] = null;
        if ( $search === 'main' ) $data['where']['parent_id'] = null;
        
        $data['order'] = $order;
        
        return $this->get( $data );
    }
    
    /**
     * Articles Subcategories
     *
     * @param  integer $parent_id
     * @return object
     */
    public function articles_subcategories( $parent_id )
    {
        $data['table'] = 'articles_categories';
        $data['where']['parent_id'] = $parent_id;
        $data['order'] = 'ASC';
        
        return $this->get( $data );
    }
    
    /**
     * Popular Topics ( Parent )
     *
     * @param  integer $limit
     * @return object
     */
    public function popular_topics( $limit = 2 )
    {
        $data['table'] = 'articles_categories';
        $data['where']['parent_id'] = null;
        $data['where']['views !='] = 0;
        $data['orderby_column'] = 'views';
        $data['limit'] = $limit;
        
        return $this->get( $data );
    }
    
    /**
     * Articles Category
     *
     * @param  string  $value
     * @param  string  $column
     * @param  boolean $parent
     * @return object
     */
    public function articles_category( $value, $column = 'id', $parent = '' )
    {
        $data['table'] = 'articles_categories';
        $data['where'][$column] = $value;
        
        if ( $parent === true ) $data['where']['parent_id !='] = null;
        else if ( $parent === false ) $data['where']['parent_id'] = null;
        
        return $this->get_one( $data );
    }
    
    /**
     * Articles Category Slug
     *
     * @param  string  $name
     * @param  integer $id
     * @param  boolean $parent
     * @return string
     */
    public function articles_category_slug( $name, $id = 0, $parent = false )
    {
        return $this->generate_slug( 'articles_categories', $name, $id, $parent );
    }
    
    /**
     * Is Articles Category Slug Exists
     *
     * @param  string  $slug
     * @param  integer $id
     * @param  boolean $parent
     * @return boolean
     */
    public function is_ac_slug_exists( $slug, $id, $parent = false )
    {
        $data['where'] = ['slug' => $slug, 'id !=' => $id];
        
        if ( $parent === true ) $data['where']['parent_id !='] = null;
        else $data['where']['parent_id'] = null;
        
        $data['table'] = 'articles_categories';
        
        return ! empty( $this->get_one( $data ) );
    }
    
    /**
     * Has Articles Subcategories
     *
     * Use to check the existence of subcategories.
     *
     * @param  integer $id Parent ID
     * @return boolean
     */
    public function has_articles_subcategories( $id )
    {
        $data['where'] = ['parent_id' => $id];
        $data['table'] = 'articles_categories';
        
        return ! empty( $this->get_one( $data ) );
    }
    
    /**
     * Articles Category ID by Slug
     *
     * @param  string  $slug
     * @param  boolean $parent
     * @return integer
     */
    public function articles_category_id_by_slug( $slug, $parent = false )
    {
        $data['where'] = ['slug' => $slug];
        $data['table'] = 'articles_categories';
        
        if ( $parent === true ) $data['where']['parent_id !='] = null;
        else $data['where']['parent_id'] = null;
        
        $result = $this->get_one( $data );
        
        if ( empty( $result ) )
        {
            return 0;
        }
        
        return $result->id;
    }
    
    /**
     * Add Articles Category
     *
     * @param  array $data
     * @return mixed
     */
    public function add_articles_category( $data )
    {
        return $this->add( $data, 'articles_categories' );
    }
    
    /**
     * Update Articles Category
     *
     * @param  array   $to_update
     * @param  integer $id
     * @return boolean
     */
    public function update_articles_category( $to_update, $id )
    {
       $data['column_value'] = $id;
       $data['table'] = 'articles_categories';
       $data['data'] = $to_update;
       
       return $this->update( $data );
    }
    
    /**
     * Update Articles Category Views
     *
     * @param  integer $id
     * @return void
     */
    public function update_articles_category_views( $id )
    {
        $this->update_views( $id, 'articles_categories' );
    }
    
    /**
     * Delete Articles Category
     *
     * @param  integer $id
     * @return boolean
     */
    public function delete_articles_category( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'articles_categories';
        
        return $this->delete( $data );
    }
    
    /**
     * Articles
     *
     * @param  boolean $count
     * @param  integer $limit
     * @param  integer $offset
     * @param  array   $options
     * @return mixed
     */
    public function articles( $count = false, $limit = 0, $offset = 0, $options = [] )
    {
        $data['table'] = 'articles';
        $data['limit'] = $limit;
        $data['offset'] = $offset;
        
        if ( ! empty( $options['searched'] ) )
        {
            $holders = ['id', 'title'];
            
            foreach ( $holders as $holder )
            {
                $data['like'][$holder] = $options['searched'];
            }
        }
        
        if ( ! empty( $options['visibility'] ) )
        {
            $visibility = $options['visibility'];
            
            if ( in_array( $visibility, ['public', 'hidden'] ) )
            {
                $visibility = ( $visibility === 'public' ) ? 1 : 0;
                
                $data['where']['visibility'] = $visibility;
            }
        }
        
        if ( $count === true )
        {
            return $this->get_count( $data );
        }
        
        return $this->get( $data );
    }
    
    /**
     * Articles by Category
     *
     * @param  integer $id
     * @param  boolean $count
     * @return mixed
     */
    public function articles_by_category( $id, $count )
    {
        $data['table'] = 'articles';
        $data['where']['category_id'] = $id;
        $data['where']['visibility'] = 1;
        
        if ( ! $this->zuser->is_logged_in )
        {
            $data['where']['logged_in_only'] = 0;
        }
        
        $data['limit'] = 5;
        $data['order'] = 'ASC';
        
        if ( $count === true )
        {
            return $this->get_count( $data );
        }
        
        return $this->get( $data );
    }
    
    /**
     * Article
     *
     * @param  string|integer $value
     * @param  string         $column
     * @return object
     */
    public function article( $value, $column = 'id' )
    {
        $data['table'] = 'articles';
        $data['where'][$column] = $value;
        
        return $this->get_one( $data );
    }
    
    /**
     * Article Slug
     *
     * @param  string  $name
     * @param  integer $id
     * @return string
     */
    public function article_slug( $name, $id = 0 )
    {
        return $this->generate_slug( 'articles', $name, $id );
    }
    
    /**
     * Is Article Exists ( by Specific Column ).
     *
     * @param  mixed   $value
     * @param  integer $id
     * @param  string  $column
     * @return boolean
     */
    public function is_article_exists_by( $value, $id, $column = 'slug' )
    {
        $data['where'] = [$column => $value, 'id !=' => $id];
        $data['table'] = 'articles';
        
        return ! empty( $this->get_one( $data ) );
    }
    
    /**
     * Articles by Filter
     *
     * @param  array $options
     * @return mixed
     */
    public function articles_by_filter( array $options )
    {
        $select = 'a.*, ac.name as category_name, ac.slug as category_slug,';
        $select .= 'ac.parent_id as category_parent_id';
        
        $this->db->select( $select );
        $this->db->from( 'articles a' );
        $this->db->join( 'articles_categories ac', 'ac.id = a.category_id', 'LEFT' );
        
        if ( ! empty( $options['ids'] ) )
        {
            $this->db->or_where_in( 'a.category_id', $options['ids'] );
        }
        else if ( ! empty( $options['searched'] ) )
        {
            $holders = ['a.title', 'a.meta_description', 'a.content'];
            
            foreach ( $holders as $holder )
            {
                $data['like'][$holder] = $options['searched'];
            }
            
            $this->db->group_start();
            $this->db->or_like( $data['like'] );
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
        
        $this->db->where( 'visibility', 1 );
        
        if ( ! $this->zuser->is_logged_in )
        {
            $this->db->where( 'logged_in_only', 0 );
        }
        
        if ( @$options['count'] === true )
        {
            return $this->db->count_all_results();
        }
        
        $this->db->order_by( 'a.created_at', 'DESC' );
        
        $data = $this->db->get();
        
        if ( $data->num_rows() > 0 ) return $data->result();
    }
    
    /**
     * Articles by Slug
     *
     * @param  string       $slug
     * @param  integer|null $visibility
     * @return object
     */
    public function article_by_slug( $slug, $visibility )
    {
        $select = 'a.*, ac.name as category_name, ac.slug as category_slug,';
        $select .= 'ac.parent_id as category_parent_id';
        
        $data['select'] = $select;
        $data['where'] = ['a.slug' => $slug];
        
        if ( $visibility !== null )
        {
            $data['where']['a.visibility'] = $visibility;
        }
        
        if ( ! $this->zuser->is_logged_in )
        {
            $data['where']['a.logged_in_only'] = 0;
        }
        
        $data['join'] = ['table' => 'articles_categories ac', 'on' => 'a.category_id = ac.id'];
        $data['table'] = 'articles a';
        
        return $this->get_one( $data );
    }
    
    /**
     * Related Articles [ Through Category ID(s) ].
     *
     * @param  integer $article_id
     * @param  array   $ids
     * @return object
     */
    public function related_articles( $article_id, $ids )
    {
        $this->db->or_where_in( 'category_id', $ids );
        $this->db->where( 'visibility', 1 );
        $this->db->where( 'id !=', $article_id );
        
        if ( ! $this->zuser->is_logged_in )
        {
            $this->db->where( 'logged_in_only', 0 );
        }
        
        $this->db->limit( 5 );
        
        $data = $this->db->get( 'articles' );
        
        if ( $data->num_rows() > 0 ) return $data->result();
    }
    
    /**
     * Has Articles
     *
     * Use to check the existence of articles of a specific category.
     *
     * @param  integer $cat_id
     * @return boolean
     */
    public function has_articles( $cat_id )
    {
        $data['where'] = ['category_id' => $cat_id];
        $data['table'] = 'articles';
        
        return ! empty( $this->get_one( $data ) );
    }
    
    /**
     * Add Article
     *
     * @param  array $data
     * @return mixed
     */
    public function add_article( $data )
    {
        return $this->add( $data, 'articles' );
    }
    
    /**
     * Am I Voted for Article
     *
     * @param  integer $id
     * @return boolean
     */
    public function am_i_voted_article( $id )
    {
        $data['where'] = [
            'ip_address' => $this->input->ip_address(),
            'article_id' => $id
        ];
        
        $data['table'] = 'articles_votes';
        
        return ! empty( $this->get_one( $data ) );
    }
    
    /**
     * Log Article Vote
     *
     * @param  integer $id
     * @return void
     */
    public function log_article_vote( $id )
    {
        $data = [
            'ip_address' => $this->input->ip_address(),
            'article_id' => $id
        ];
        
        $this->add( $data, 'articles_votes' );
    }
    
    /**
     * Add Article Vote
     *
     * @param  integer $id
     * @param  string  $column
     * @return boolean
     */
    public function add_article_vote( $id, $column )
    {
        $this->log_article_vote( $id );
        
        $data['where'] = ['id' => $id];
        $data['table'] = 'articles';
        $data['set'] = [$column => "{$column}+1"];
        
        return $this->update( $data );
    }
    
    /**
     * Update Article
     *
     * @param  array   $to_update
     * @param  integer $id
     * @return boolean
     */
    public function update_article( $to_update, $id )
    {
       $data['column_value'] = $id;
       $data['table'] = 'articles';
       $data['data'] = $to_update;
       
       return $this->update( $data );
    }
    
    /**
     * Update Article Views
     *
     * @param  integer $id
     * @return void
     */
    public function update_article_views( $id )
    {
        $this->update_views( $id, 'articles' );
    }
    
    /**
     * Delete Article Votes
     *
     * @param  integer $id
     * @return void
     */
    public function delete_article_votes( $id )
    {
        $data['where']['article_id'] = $id;
        $data['table'] = 'articles_votes';
        
        $this->delete( $data );
    }
    
    /**
     * Delete Article
     *
     * @param  integer $id
     * @return boolean
     */
    public function delete_article( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'articles';
        
        return $this->delete( $data );
    }
    
    /**
     * Departments
     *
     * @param  integer $visibility
     * @return object
     */
    public function departments( $visibility = null )
    {
        $data['table'] = 'tickets_departments';

        if ( $visibility != null )
        {
            $data['where']['visibility'] = $visibility;
        }
        
        return $this->get( $data );
    }
    
    /**
     * Department
     *
     * @param  integer $id
     * @return object
     */
    public function department( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'tickets_departments';
        
        return $this->get_one( $data );
    }
    
    /**
     * Add Department
     *
     * @param  array $data
     * @return mixed
     */
    public function add_department( $data )
    {
        return $this->add( $data, 'tickets_departments' );
    }
    
    /**
     * Update Department
     *
     * @param  array   $to_update
     * @param  integer $id
     * @return boolean
     */
    public function update_department( $to_update, $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'tickets_departments';
        $data['data'] = $to_update;
       
        return $this->update( $data );
    }
    
    /**
     * Delete Department
     *
     * @param  integer $id
     * @return boolean
     */
    public function delete_department( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'tickets_departments';
        
        return $this->delete( $data );
    }
    
    /**
     * Tickets
     *
     * @param  array $options
     * @return mixed
     */
    public function tickets( array $options = [] )
    {
        $select = 't.*, u.first_name, u.last_name,';
        $select .= 'u.picture as user_picture, r.first_name as r_first_name,';
        $select .= 'r.last_name as r_last_name';
        
        $data['select'] = $select;
        $data['table'] = 'tickets t';
        
        $data['join'] = [
            ['table' => 'users u', 'on' => 'u.id = t.assigned_to'],
            ['table' => 'users r', 'on' => 'r.id = t.user_id']
        ];
        
        if ( ! empty( $options['user_id'] ) )
        {
            $data['where'] = ['t.user_id' => $options['user_id']];
        }
        else if ( ! $this->zuser->has_permission( 'all_tickets' ) || @$options['assigned'] === true )
        {
            $data['where']['t.assigned_to'] = $this->zuser->get( 'id' );
        }
        
        if ( ! empty( $options['searched'] ) )
        {
            $holders = ['t.id', 't.subject'];
            
            foreach ( $holders as $holder )
            {
                $data['like'][$holder] = $options['searched'];
            }
        }
        
        if ( ! empty( $options['reply_status'] ) )
        {
            $data['where']['t.sub_status'] = $options['reply_status'];
        }
        
        if ( ! empty( $options['priority'] ) )
        {
            $data['where']['t.priority'] = $options['priority'];
        }
        
        if ( ! empty( $options['department_id'] ) )
        {
            $data['where']['t.department_id'] = $options['department_id'];
        }
        
        if ( @$options['status'] !== null ) $data['where']['t.status'] = $options['status'];
        
        if ( ! empty( $options['limit'] ) ) $data['limit'] = $options['limit'];
        
        if ( ! empty( $options['offset'] ) ) $data['offset'] = $options['offset'];
        
        if ( @$options['count'] === true )
        {
            return $this->get_count( $data );
        }
        
        return $this->get( $data );
    }
    
    /**
     * Get Tickets Count by Month and Year
     *
     * @param  string $month_year
     * @return integer
     */
    public function get_tickets_count_by_month_year( $month_year )
    {
        $data['where'] = ['created_month_year' => $month_year];
        $data['table'] = 'tickets';
        
        if ( ! $this->zuser->has_permission( 'all_tickets' ) )
        {
            $data['where']['assigned_to'] = $this->zuser->get( 'id' );
        }

        return $this->get_count( $data );
    }
    
    /**
     * Ticket Replies
     *
     * @param  integer $ticket_id
     * @return object
     */
    public function tickets_replies( $ticket_id )
    {
        $select = 'tr.*, u.first_name, u.last_name,';
        $select .= 'u.picture as user_picture';
        
        $data['select'] = $select;
        $data['table'] = 'tickets_replies tr';
        $data['join'] = ['table' => 'users u', 'on' => 'u.id = tr.user_id'];
        $data['where'] = ['tr.ticket_id' => $ticket_id];
        $data['order'] = 'ASC';
        
        return $this->get( $data );
    }
    
    /**
     * Ticket Replies Count
     *
     * @param  integer $ticket_id
     * @return integer
     */
    public function ticket_replies_count( $ticket_id )
    {
        $data['table'] = 'tickets_replies';
        $data['where'] = ['ticket_id' => $ticket_id];
        
        return $this->get_count( $data );
    }
    
    /**
     * Ticket History
     *
     * @param  array $options
     * @return mixed
     */
    public function ticket_history( array $options )
    {
        $data['select'] = 'th.*, u.username';
        $data['table'] = 'tickets_history th';
        $data['join'] = ['table' => 'users u', 'on' => 'u.id = th.user_id'];
        $data['where'] = ['th.ticket_id' => $options['ticket_id']];
        
        if ( ! empty( $options['limit'] ) ) $data['limit'] = $options['limit'];
        
        if ( ! empty( $options['offset'] ) ) $data['offset'] = $options['offset'];
        
        if ( @$options['count'] === true )
        {
            return $this->get_count( $data );
        }
        
        return $this->get( $data );
    }
    
    /**
     * Delete Ticket History
     *
     * @param  integer $id
     * @return void
     */
    public function delete_ticket_history( $id )
    {
        $data['where']['ticket_id'] = $id;
        $data['table'] = 'tickets_history';
        
        $this->delete( $data );
    }
    
    /**
     * Ticket
     *
     * @param  integer $id
     * @param  integer $user_id
     * @return object
     */
    public function ticket( $id, $user_id = 0 )
    {
        $select = 't.*, td.name as department, u1.first_name, u1.last_name,';
        $select .= 'u1.picture as user_picture, u2.first_name as au_first_name,';
        $select .= 'u2.last_name as au_last_name, u3.first_name as cb_first_name,';
        $select .= 'u3.last_name as cb_last_name';
        
        $data['select'] = $select;
        $data['table'] = 'tickets t';
        $data['where'] = ['t.id' => $id];
        
        $data['join'] = [
            ['table' => 'tickets_departments td', 'on' => 'td.id = t.department_id'],
            ['table' => 'users u1', 'on' => 'u1.id = t.user_id'],
            ['table' => 'users u2', 'on' => 'u2.id = t.assigned_to'],
            ['table' => 'users u3', 'on' => 'u3.id = t.closed_by']
        ];
        
        if ( ! empty( $user_id ) )
        {
            $data['where']['t.user_id'] = $user_id;
        }
        
        $result = $this->get_one( $data );
        
        // If the logged-in user is not having the access of all the tickets, check for
        // the ticket authorization with the help of department users and own assignment:
        if ( ! $this->zuser->has_permission( 'all_tickets' ) && empty( $user_id ) )
        {
            if ( $result->assigned_to == $this->zuser->get( 'id' ) )
            {
                return $result;
            }
            
            $department = $this->department( $result->department_id );
            
            if ( ! empty( $department ) )
            {
                $team = $department->team;
                
                if ( $team == 'all_users' )
                {
                    return $result;
                }
                
                $ids = json_decode( $team, true )['users'];
                
                if ( in_array( $this->zuser->get( 'id' ), $ids ) )
                {
                    return $result;
                }
            }
            
            return null;
        }
        
        return $result;
    }
    
    /**
     * Assign Ticket to the User
     *
     * @param  integer $id
     * @param  integer $user_id
     * @param  string  $table
     * @return boolean
     */
    public function assign_user( $id, $user_id, $table = 'tickets' )
    {
        $data['column_value'] = $id;
        $data['table'] = $table;
        $data['data']['assigned_to'] = $user_id;
        
        $status = $this->update( $data );
        
        if ( $status )
        {
            unset( $data['data']['assigned_to'] );
            $data['data']['is_read_assigned'] = 0;
            
            $this->update( $data );
        }
        
        return $status;
    }
    
    /**
     * Add Ticket
     *
     * @param  array $data
     * @return mixed
     */
    public function add_ticket( $data )
    {
        return $this->add( $data, 'tickets' );
    }
    
    /**
     * Log Ticket Activity
     *
     * @param  array $data
     * @return void
     */
    public function log_ticket_activity( $data )
    {
        $this->add( $data, 'tickets_history' );
    }
    
    /**
     * Update Ticket
     *
     * @param  array   $to_update
     * @param  integer $id
     * @param  boolean $update_time
     * @return boolean
     */
    public function update_ticket( $to_update, $id, $update_time = true )
    {
       $data['column_value'] = $id;
       $data['table'] = 'tickets';
       $data['update_time'] = false;
       $data['data'] = $to_update;
       
       if ( $update_time === true )
       {
            $data['data']['updated_at'] = time();
       }
       
       return $this->update( $data );
    }
    
    /**
     * Add Reply
     *
     * @param  array $data
     * @return mixed
     */
    public function add_reply( $data )
    {
        return $this->add( $data, 'tickets_replies' );
    }
    
    /**
     * Update Reply
     *
     * @param   array   $to_update
     * @param   integer $id
     * @return  boolean
     * @version 1.1
     */
    public function update_reply( $to_update, $id )
    {
       $data['column_value'] = $id;
       $data['table'] = 'tickets_replies';
       $data['data'] = $to_update;
       
       return $this->update( $data );
    }
    
    /**
     * Update Ticket Status
     *
     * @param  integer $id
     * @param  integer $status
     * @param  integer $closed_by
     * @return boolean
     */
    private function update_ticket_status( $id, $status, $closed_by = '' )
    {
        $data['column_value'] = $id;
        $data['table'] = 'tickets';
        $data['data'] = ['status' => $status];
        $data['data']['closed_by'] = null;
        
        if ( $status === 0 )
        {
            if ( $closed_by === '' )
            {
                $data['data']['closed_by'] = $this->zuser->get( 'id' );
            }
            else
            {
                $data['data']['closed_by'] = $closed_by;
            }
            
            $data['data']['reopened_awaiting'] = 0;
        }
        else if ( $status === 1 )
        {
            $data['data']['reopened_awaiting'] = 1;
            $data['data']['sub_status'] = 4;
        }

        return $this->update( $data );
    }
    
    /**
     * Re-open Ticket
     *
     * @param  integer $id
     * @return boolean
     */
    public function reopen_ticket( $id )
    {
        return $this->update_ticket_status( $id, 1 );
    }
    
    /**
     * Ticket Reply
     *
     * @param  integer $id
     * @return object
     */
    public function ticket_reply( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'tickets_replies';
        
        return $this->get_one( $data );
    }
    
    /**
     * Close Ticket
     *
     * @param  integer      $id
     * @param  integer|null $closed_by
     * @return boolean
     */
    public function close_ticket( $id, $closed_by = '' )
    {
        return $this->update_ticket_status( $id, 0, $closed_by );
    }
    
    /**
     * To Close Tickets
     *
     * @param  string $time
     * @return object
     */
    public function to_close_tickets( $time )
    {
        $data['where'] = ['last_agent_replied_at <' => $time, 'status' => 1, 'reopened_awaiting' => 0];
        $data['table'] = 'tickets';
        
        return $this->get( $data );
    }
    
    /**
     * Close the Tickets Automatically
     *
     * @return boolean
     */
    public function auto_close_tickets()
    {
        if ( db_config( 'auto_close_tickets' ) == 4 ) return false;
        
        $after_time = db_config( 'auto_close_tickets' );
        
        if ( $after_time == 1 ) $time = subtract_time( '3 days' );
        else if ( $after_time == 2 ) $time = subtract_time( '7 days' );
        else if ( $after_time == 3 ) $time = subtract_time( '14 days' );
        
        $to_close = $this->to_close_tickets( $time );
        $data['data'] = ['status' => 0, 'updated_at' => time(), 'closed_by' => null];
        
        if ( ! empty( $to_close ) )
        {
            foreach ( $to_close as $ticket )
            {
                log_ticket_activity( 'ticket_closed_system', $ticket->id );
            }
        }
        
        $data['where'] = [
            'last_agent_replied_at <' => $time,
            'status' => 1,
            'reopened_awaiting' => 0
        ];
        
        $data['table'] = 'tickets';

        $this->update( $data );
    }
    
    /**
     * Delete Ticket Reply
     *
     * @param  integer $id
     * @return boolean
     */
    public function delete_ticket_reply( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'tickets_replies';
        
        return $this->delete( $data );
    }
    
    /**
     * Delete Ticket Replies
     *
     * @parma  integer $id
     * @return void
     */
    public function delete_ticket_replies( $id )
    {
        $data['where']['ticket_id'] = $id;
        $data['table'] = 'tickets_replies';
        
        $this->delete( $data );
    }
    
    /**
     * Delete Ticket
     *
     * @param  integer $id
     * @return boolean
     */
    public function delete_ticket( $id )
    {
        $data['column_value'] = $id;
        $data['table'] = 'tickets';
        
        return $this->delete( $data );
    }
}
