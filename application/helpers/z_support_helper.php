<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Support Helper
 *
 * @author Shahzaib
 */


/**
 * Get Ticket Attachments
 *
 * @param   integer $ticket_id
 * @return  object
 * @version 1.7
 */
if ( ! function_exists( 'get_ticket_attachments' ) )
{
    function get_ticket_attachments( $ticket_id )
    {
        return get_sm_object()->ticket_attachments( $ticket_id );
    }
}

/**
 * Get Ticket Reply Attachments
 *
 * @param   integer $reply_id
 * @return  object
 * @version 1.7
 */
if ( ! function_exists( 'get_ticket_reply_attachments' ) )
{
    function get_ticket_reply_attachments( $reply_id )
    {
        return get_sm_object()->ticket_reply_attachments( $reply_id );
    }
}

/**
 * Is Custom Fields Having Value
 *
 * @param   object $data
 * @return  boolean
 * @version 1.5
 */
if ( ! function_exists( 'is_custom_fields_having_value' ) )
{
    function is_custom_fields_having_value( $data )
    {
        if ( ! empty( $data ) )
        {
            foreach ( $data as $field )
            {
                if ( ! empty( $field->value ) ) return true;
            }
        }

        return false;
    }
}

/**
 * Set Custom Field Input
 *
 * @param   integer $id
 * @param   string  $value
 * @return  array
 * @version 1.5
 */
if ( ! function_exists( 'set_custom_field_input' ) )
{
    function set_custom_field_input( $id, $value )
    {
        return ['custom_field_id' => $id, 'value' => $value];
    }
}

/**
 * Manage Custom Fields Input
 *
 * @param   integer $ticket_id
 * @param   boolean $validate
 * @return  mixed
 * @version 1.5
 */
if ( ! function_exists( 'manage_custom_field_input' ) )
{
    function manage_custom_field_input( $ticket_id, $validate = false )
    {
        $ci =& get_instance();
        
        $ci->load->model( 'Custom_field_model' );
        
        $fields = $ci->Custom_field_model->custom_fields( 'ASC' );
        $input = [];
        
        if ( ! empty( $fields ) )
        {
            foreach ( $fields as $field )
            {
                $type = $field->type;
                $id = $field->id;
                $value = '';
                
                if ( $type === 'text' || $type === 'textarea' || $type === 'email' )
                {
                    $value = do_secure( post( "cf_{$id}" ) );
                    
                    if ( ! empty( $value ) )
                    {
                        if ( $type === 'email' && ! filter_var( $value, FILTER_VALIDATE_EMAIL ) )
                        {
                             return 'invalid_input';
                        }
                        
                        if ( ! $validate ) $input[] = set_custom_field_input( $id, $value );
                    }
                }
                else if (  $type === 'checkbox' || $type === 'radio' || $type === 'select' )
                {
                    $options = explode( ',', $field->options );
                    
                    if ( $type === 'checkbox' )
                    {
                        foreach ( $options as $key => $option )
                        {
                            if ( do_secure( post( "cf_{$id}_{$key}" ) ) )
                            {
                                $option = trim( $option );
                                
                                if ( ! empty( $value ) ) $value .= ", {$option}";
                                else $value .= $option;
                            }
                        }
                    }
                    else
                    {
                        if ( post( "cf_{$id}" ) !== null && post( "cf_{$id}" ) !== '' )
                        {
                            $value = intval( post( "cf_{$id}" ) );
                            
                            foreach ( $options as $key => $option )
                            {
                                if ( $key === $value )
                                {
                                    $value = trim( $option );
                                }
                            }
                        }
                    }
                    
                    if ( ! $validate ) $input[] = set_custom_field_input( $id, $value );
                }
                
                if ( $field->is_required && empty( $value ) )
                {
                    return 'missing_input';
                }
            }
        }
        
        if ( ! empty( $input ) && ! $validate )
        {
            foreach ( $input as $data )
            {
                $add = [
                    'custom_field_id' => $data['custom_field_id'],
                    'ticket_id' => $ticket_id,
                    'value' => $data['value']
                ];
                
                $ci->Custom_field_model->manage_custom_field_input_data( $add );
            }
        }
        
        return true;
    }
}

/**
 * Select GET Visibility
 *
 * @param   string $value
 * @return  string
 * @version 1.5
 */
if ( ! function_exists( 'select_get_visibility' ) )
{
    function select_get_visibility( $value )
    {
        return select_of_get( 'visibility', $value );
    }
}

/**
 * Get Chat Tracking Details ( Cookie )
 *
 * @return  string
 * @version 1.4
 */
if ( ! function_exists( 'get_chat_tracking' ) )
{
    function get_chat_tracking()
    {
        $tracking = get_cookie( CHAT_COOKIE );
        
        if ( ! empty( $tracking ) ) return $tracking;
        
        return '';
    }
}

/**
 * Is Logged-in User is a Chat Person
 *
 * @return  boolean
 * @version 1.4
 */
if ( ! function_exists( 'is_logged_in_user_chat_person' ) )
{
    function is_logged_in_user_chat_person()
    {
        $ci =& get_instance();
        $person = get_chat_data_from_json( 'person' );
        $status = false;
        
        if ( ! empty( $person ) )
        {
            $logged_in_user = md5( $ci->zuser->get( 'id' ) );
            
            return ( $logged_in_user == $person );
        }
        
        return $status;
    }
}

/**
 * Send Guest Ticket Notification
 *
 * Use to send the ticket creation notification
 * to the customer's email address ( that's not
 * registerd in the system ) after some agent
 * created on his/her behalf.
 *
 * @param   string  $email_address
 * @param   string  $url
 * @param   string  $custom_hook
 * @param   integer $id
 * @return  boolean
 * @version 1.4
 */
if ( ! function_exists( 'send_guest_ticket_notification' ) )
{
    function send_guest_ticket_notification( $email_address, $url, $custom_hook = '', $id = null )
    {
        $status = true;
        
        if ( is_email_settings_filled() )
        {
            $ci =& get_instance();
            
            $hook = ( $custom_hook == '' ) ? 'ticket_created_unregistered_user' : $custom_hook;
            $template = $ci->Tool_model->email_template_by_hook_and_lang( $hook, get_language() );

            if ( empty( $template ) ) return false;
            
            $subject = $template->subject;
            
            $message_data = [
                '{TICKET_URL}' => env_url( $url ),
                '{SITE_NAME}' => db_config( 'site_name' )
            ];
            
            if ( $hook == 'ticket_created_guest' )
            {
                $ci->load->model( 'Email_token_model' );
                
                $token = get_short_random_string();
                
                $message_data['{EMAIL_LINK}'] = env_url( "tverify/{$id}/{$token}" );
            
                $token_status = $ci->Email_token_model->add_email_token( $token, $id, 'ticket_verification' );
            
                if ( empty( $token_status ) ) return false;
            }
            
            $message = replace_placeholders( $template->template, $message_data );
            
            $ci->load->library( 'ZMailer' );

            if ( ! $ci->zmailer->send_email( $email_address, $subject, $message ) )
            {
                $status = false;
            }
        }

        return $status;
    }
}

/**
 * Send Reply Notification to Guest
 *
 * Use to send the ticket replied notification
 * to the unregistered customer's email address.
 *
 * @param   integer $ticket_id
 * @param   integer $reply_id
 * @return  boolean
 * @version 1.4
 */
if ( ! function_exists( 'send_guest_reply_notification' ) )
{
    function send_guest_reply_notification( $ticket_id, $reply_id )
    {
        $status = true;
        
        if ( is_email_settings_filled() && db_config( 'sp_email_notifications' ) == 1 )
        {
            $ci =& get_instance();
            $ticket = get_sm_object()->ticket( $ticket_id );
            
            if ( empty( $ticket ) ) return false;
            
            $url = 'ticket/guest/' . $ticket->security_key . "/{$ticket_id}?to_move_box=";
            $url .= md5( $reply_id );
        
            $email_address = $ticket->email_address;
            $hook = 'ticket_replied_agent';
            $template = $ci->Tool_model->email_template_by_hook_and_lang( $hook, 'english' );

            if ( empty( $template ) ) return false;
            
            $subject = $template->subject;
            
            $message = replace_placeholders( $template->template, [
                '{USER_NAME}' => $email_address,
                '{TICKET_URL}' => env_url( $url ),
                '{SITE_NAME}' => db_config( 'site_name' )
            ]);
            
            $ci->load->library( 'ZMailer' );

            if ( ! $ci->zmailer->send_email( $email_address, $subject, $message ) )
            {
                $status = false;
            }
        }

        return $status;
    }
}

/**
 * Is Active Chat
 *
 * Use to check the current logged-in user chat
 * status that the chat is active or not ( if any ).
 *
 * @return  boolean
 * @version 1.4
 */
if ( ! function_exists( 'is_active_chat' ) )
{
    function is_active_chat()
    {
        $tracking = get_chat_tracking();
        $status = false;
        
        if ( ! empty( $tracking ) )
        {
            $chat_id = get_chat_data_from_json();
            $status = get_sm_object()->is_active_chat( $chat_id );
            
            if ( $status === false && is_logged_in_user_chat_person() )
            {
                delete_cookie( CHAT_COOKIE );
            }
        }
        
        return $status;
    }
}

/**
 * Is Chat Available
 *
 * @return  boolean
 * @version 1.4
 */
if ( ! function_exists( 'is_chat_available' ) )
{
    function is_chat_available()
    {
        $status = true;
        
        if ( ! is_active_chat() )
        {
            $status = get_sm_object()->is_chat_available();
        }
        
        return $status;
    }
}

/**
 * Get Public ( Visibility ) Departments
 *
 * @return  object
 * @version 1.4
 */
if ( ! function_exists( 'get_public_departments' ) )
{
    function get_public_departments()
    {
        return get_sm_object()->departments( 1 );
    }
}

/**
 * Get Chat Data From JSON
 *
 * @param   string $key
 * @param   string $json
 * @return  integer
 * @version 1.4
 */
if ( ! function_exists( 'get_chat_data_from_json' ) )
{
    function get_chat_data_from_json( $key = 'chat_id', $json = '' )
    {
        $json = ( $json === '' ) ? get_chat_tracking() : $json;
        
        $data = json_decode( stripslashes( $json ), true );
        
        if ( isset( $data[$key] ) )
        {
            return $data[$key];
        }
        
        return 0;
    }
}

/**
 * Get User ( Logged-in ) Active Chat by ID
 *
 * @param   integer $chat_id
 * @param   boolean $get_replies
 * @return  object
 * @version 1.4
 */
if ( ! function_exists( 'get_user_active_chat_by_id' ) )
{
    function get_user_active_chat_by_id( $chat_id = 0, $get_replies = true )
    {
        $ci =& get_instance();
        
        $user_id = $ci->zuser->get( 'id' );
        
        if ( empty( $chat_id ) )
        {
            $chat_id = get_chat_data_from_json();
        }
        
        $chat = get_sm_object()->chat( $chat_id, $user_id );
        
        if ( ! empty( $chat ) )
        {
            $data = ['chat' => $chat];
            
            if ( $get_replies )
            {
                $replies = get_sm_object()->chat_replies( $chat_id );
                
                if ( ! empty( $replies ) ) $data['replies'] = $replies;
            }
            
            return $data;
        }
        
        return null;
    }
}

/**
 * Manage Chat Sub Status
 *
 * @param   integer $status
 * @return  string
 * @version 1.4
 */
if ( ! function_exists( 'manage_chat_status' ) )
{
    function manage_chat_status( $status )
    {
        $string = '';
        
        if ( $status == 1 )
        {
            $string = lang( 'active' );
        }
        else
        {
            $string = lang( 'ended' );
        }
        
        return $string;
    }
}

/**
 * Chat Status Color ( Label )
 *
 * @param   integer $status
 * @return  string
 * @version 1.4
 */
if ( ! function_exists( 'chat_status_color' ) )
{
    function chat_status_color( $status )
    {
        $string = '';
        
        if ( $status == 1 )
        {
            $string = 'badge-primary';
        }
        else
        {
            $string = 'badge-success';
        }
        
        return $string;
    }
}

/**
 * Manage Chat Sub Status
 *
 * @param   integer $status
 * @return  string
 * @version 1.4
 */
if ( ! function_exists( 'manage_chat_sub_status' ) )
{
    function manage_chat_sub_status( $status )
    {
        $string = '';
        
        if ( $status == 1 )
        {
            $string = lang( 'unanswered' );
        }
        else if ( $status == 2 )
        {
            $string = lang( 'replied' );
        }
        
        return $string;
    }
}

/**
 * Send Notifications to Department Users
 *
 * @param   array $options
 * @return  mixed
 * @version 1.4
 */
if ( ! function_exists( 'send_notifications_to_department_users' ) )
{
    function send_notifications_to_department_users( array $options )
    {
        $ci =& get_instance();
        $location = $options['location'];
        $data = $options['data'];
        $status = true;
        
        if ( is_email_settings_filled() )
        {
            $hook = $options['hook'];
            $template = $ci->Tool_model->email_template_by_hook_and_lang( $hook, config_item( 'language' ) );
            
            if ( empty( $template ) ) return 'missing_template';
            
            $subject = replace_placeholders( $template->subject, ['{DEPARTMENT_NAME}' => $data->name] );
            
            $for_message = [
                '{DEPARTMENT_NAME}' => $data->name,
                '{' . $options['location_placeholder'] . '}' => env_url( $location ),
                '{SITE_NAME}' => db_config( 'site_name' )
            ];
            
            $ci->load->library( 'ZMailer' );
        }
        
        $team = $data->team;
        $names = [];
        $emails = [];
        $ids = [];
        
        if ( $team !== 'all_users' )
        {
            $ci->load->model( 'User_model' );
            
            $users = json_decode( $team )->users;
            
            foreach ( $users as $user_id )
            {
                $user = $ci->User_model->get_by_id( $user_id );
                $names[] = $user->first_name . ' ' . $user->last_name;
                $emails[] = $user->email_address;
                $ids[] = $user->id;
            }
        }
        else
        {
            foreach ( get_team_users() as $user )
            {
                $names[] = $user->first_name . ' ' . $user->last_name;
                $emails[] = $user->email_address;
                $ids[] = $user->id;
            }
        }
        
        for ( $i = 0; $i < count( $emails ); $i++ )
        {
            send_notification( $options['notification_key'], $location, $ids[$i], 1 );
            
            if ( is_email_settings_filled() && is_notifications_enabled( $ids[$i] ) )
            {
                $placeholders = $for_message;
                $placeholders['{USER_NAME}'] = $names[$i];
            
                $message = replace_placeholders( $template->template, $placeholders );
                $status = $ci->zmailer->send_email( $emails[$i], $subject, $message );
            }
        }
        
        return $status;
    }
}

/**
 * Select GET Reply Status
 *
 * @param   string $value
 * @return  string
 * @version 1.3
 */
if ( ! function_exists( 'select_get_reply_status' ) )
{
    function select_get_reply_status( $value )
    {
        return select_of_get( 'reply_status', $value );
    }
}

/**
 * Select GET Priority
 *
 * @param   string $value
 * @return  string
 * @version 1.3
 */
if ( ! function_exists( 'select_get_priority' ) )
{
    function select_get_priority( $value )
    {
        return select_of_get( 'priority', $value );
    }
}

/**
 * Get Support Model Object
 *
 * @return object
 */
if ( ! function_exists( 'get_sm_object' ) )
{
    function get_sm_object()
    {
        $ci =& get_instance();
        $ci->load->model( 'Support_model' );
        
        return $ci->Support_model;
    }
}

/**
 * Inform Department Users
 *
 * NOTE: This function is updated for the chats
 * notifications also in the version 1.4 update.
 *
 * @param   object  $data
 * @param   integer $id
 * @param   string  $type
 * @return  mixed
 * @version 1.1
 */
if ( ! function_exists( 'inform_department_users' ) )
{
    function inform_department_users( $data, $id, $type = 'ticket' )
    {
        if ( $type === 'ticket' )
        {
            $options = [
                'location' => "admin/tickets/ticket/{$id}",
                'hook' => 'department_ticket',
                'notification_key' => 'ticket_assigned_department',
                'location_placeholder' => 'TICKET_URL',
                'data' => $data
            ];
        }
        else if ( $type === 'chat' )
        {
            $options = [
                'location' => "admin/chats/chat/{$id}",
                'hook' => 'department_chat',
                'notification_key' => 'chat_assigned_department',
                'location_placeholder' => 'CHAT_URL',
                'data' => $data
            ];
        }
        
        return send_notifications_to_department_users( $options );
    }
}

/**
 * Send Reply Notification
 *
 * Use to send the ticket replied notification.
 *
 * @param  integer $user_id
 * @param  integer $ticket_id
 * @param  integer $reply_id
 * @param  string  $postfix
 * @return boolean
 */
if ( ! function_exists( 'send_reply_notification' ) )
{
    function send_reply_notification( $user_id, $ticket_id, $reply_id, $postfix = 'agent' )
    {
        $ci =& get_instance();
        $status = true;
        
        if ( ! in_array( $postfix, ['agent', 'user'] ) )
        {
            return false;
        }

        $ci->load->model( 'User_model' );

        $user = $ci->User_model->get_by_id( $user_id );

        if ( empty( $user ) ) return 'invalid_req';

        $area = ( $postfix === 'agent' ) ? 'user' : 'admin';
        $for_team_member = ( $postfix !== 'agent' ) ? 1 : 0;
        $location = "{$area}/support/ticket/{$ticket_id}?to_move_box=";
        $location .= md5( $reply_id );
        
        send_notification( "{$postfix}_replied_ticket", $location, $user_id, $for_team_member );
        
        if ( is_email_settings_filled() && is_notifications_enabled( $user_id ) )
        {
            $email_address = $user->email_address;
            $language = get_user_closer_language( $user->language );
            $hook = "ticket_replied_{$postfix}";
            $template = $ci->Tool_model->email_template_by_hook_and_lang( $hook, $language );

            if ( empty( $template ) ) return false;
            
            $subject = $template->subject;
            
            $message = replace_placeholders( $template->template, [
                '{USER_NAME}' => $user->first_name . ' ' . $user->last_name,
                '{TICKET_URL}' => env_url( $location ),
                '{SITE_NAME}' => db_config( 'site_name' )
            ]);
            
            $ci->load->library( 'ZMailer' );

            if ( ! $ci->zmailer->send_email( $email_address, $subject, $message ) )
            {
                $status = false;
            }
        }

        return $status;
    }
}

/**
 * Get FAQs Categories
 *
 * @param  string $order
 * @return object
 */
if ( ! function_exists( 'get_faqs_categories' ) )
{
    function get_faqs_categories( $order = 'DESC' )
    {
        return get_sm_object()->faqs_categories( $order );
    }
}

/**
 * Get FAQs Category Name
 *
 * @param  integer $id
 * @return string
 */
if ( ! function_exists( 'get_faqs_category_name' ) )
{
    function get_faqs_category_name( $id )
    {
        $category = get_sm_object()->faqs_category( $id );
        
        if ( empty( $category->name ) )
        {
            return '';
        }
        
        return $category->name;
    }
}

/**
 * Get FAQs by Category
 *
 * @param  integer $cat_id
 * @return object
 */
if ( ! function_exists( 'get_faqs_by_category' ) )
{
    function get_faqs_by_category( $cat_id )
    {
        return get_sm_object()->faqs_by_category( $cat_id );
    }
}

/**
 * Get Articles Categories
 *
 * @param  string $search
 * @param  string $order
 * @return object
 */
if ( ! function_exists( 'get_articles_categories' ) )
{
    function get_articles_categories( $search = 'main', $order = 'ASC' )
    {
        return get_sm_object()->articles_categories( $search, $order );
    }
}

/**
 * Get Articles Categories
 *
 * @param  integer $parent_id
 * @return object
 */
if ( ! function_exists( 'get_articles_subcategories' ) )
{
    function get_articles_subcategories( $parent_id )
    {
        return get_sm_object()->articles_subcategories( $parent_id );
    }
}

/**
 * Get Articles Category Data
 *
 * @param  integer $id
 * @param  string  $prop
 * @return mixed
 */
if ( ! function_exists( 'get_articles_category_data' ) )
{
    function get_articles_category_data( $id, $prop = '' )
    {
        $category = get_sm_object()->articles_category( $id );
        
        if ( empty( $prop ) ) return $category;
        
        return $category->{$prop};
    }
}

/**
 * Get Articles by Category
 *
 * @param  integer $id
 * @param  boolean $count
 * @return object
 */
if ( ! function_exists( 'get_articles_by_category' ) )
{
    function get_articles_by_category( $id, $count = false )
    {
        return get_sm_object()->articles_by_category( $id, $count );
    }
}

/**
 * Log Ticket Activity
 *
 * @param  string  $key Language key without "av_" prefix.
 * @param  integer $id
 * @param  integer $user_id Agent user ID
 * @return void
 */
if ( ! function_exists( 'log_ticket_activity' ) )
{
    function log_ticket_activity( $key, $id, $user_id = 0 )
    {
        $data = [
            'message_key' => 'av_' . $key,
            'ticket_id' => $id,
            'created_at' => time()
        ];
        
        if ( ! empty( $user_id ) )
        {
            $data['user_id'] = $user_id;
        }
        
        get_sm_object()->log_ticket_activity( $data );
    }
}

/**
 * Manage Ticket Sub Status
 *
 * @param  integer $status
 * @param  string  $area
 * @return string
 */
if ( ! function_exists( 'manage_ticket_sub_status' ) )
{
    function manage_ticket_sub_status( $status, $area = 'user' )
    {
        $string = '';
        
        if ( $status == 1 )
        {
            if ( $area === 'user' ) $string = lang( 'awaiting_reply' );
            else $string = lang( 'unanswered' );
        }
        else if ( $status == 2 )
        {
            if ( $area === 'user' ) $string = lang( 'support_replied' );
            else $string = lang( 'replied' );
        }
        else if ( $status == 3 )
        {
            $string = lang( 'solved' );
        }
        else if ( $status == 4 )
        {
            if ( $area === 'user' ) $string = lang( 'awaiting_your_reply' );
            else $string = lang( 'awaiting_user_reply' );
        }
        
        return $string;
    }
}

/**
 * Ticket Sub Status Color ( Label )
 *
 * @param  integer $status
 * @param  string  $area
 * @return string
 */
if ( ! function_exists( 'ticket_sub_status_color' ) )
{
    function ticket_sub_status_color( $status, $area = 'user' )
    {
        $string = '';
        
        if ( $status == 1 )
        {
            if ( $area === 'user' ) $string = 'bg-secondary';
            else $string = 'badge-danger';
        }
        else if ( $status == 2 )
        {
            if ( $area === 'user' ) $string = 'bg-info';
            else $string = 'badge-info';
        }
        else if ( $status == 3 )
        {
            if ( $area === 'user' ) $string = 'bg-success';
            else $string = 'badge-success';
        }
        else if ( $status == 4 )
        {
            if ( $area === 'user' ) $string = 'bg-danger';
            else $string = 'badge-info';
        }
        
        return $string;
    }
}

/**
 * Manage Ticket Sub Status
 *
 * @param  integer $status
 * @return string
 */
if ( ! function_exists( 'manage_ticket_status' ) )
{
    function manage_ticket_status( $status )
    {
        $string = '';
        
        if ( $status == 1 )
        {
            $string = lang( 'opened' );
        }
        else
        {
            $string = lang( 'closed' );
        }
        
        return $string;
    }
}

/**
 * Ticket Status Color ( Label )
 *
 * @param  integer $status
 * @param  string  $area
 * @return string
 */
if ( ! function_exists( 'ticket_status_color' ) )
{
    function ticket_status_color( $status, $area = 'user' )
    {
        $string = '';
        
        if ( $status == 1 )
        {
            if ( $area === 'user' ) $string = 'bg-primary';
            else $string = 'badge-primary';
        }
        else
        {
            if ( $area === 'user' ) $string = 'bg-danger';
            else $string = 'badge-danger';
        }
        
        return $string;
    }
}

/**
 * Ticket Priority Color ( Label )
 *
 * @param  string $priority
 * @param  string $area
 * @return string
 */
if ( ! function_exists( 'ticket_priority_color' ) )
{
    function ticket_priority_color( $priority, $area = 'user' )
    {
        $string = '';
        
        if ( $priority == 'low' )
        {
            if ( $area === 'user' ) $string = 'bg-primary';
            else $string = 'badge-primary';
        }
        else if ( $priority == 'medium' )
        {
            if ( $area === 'user' ) $string = 'bg-warning';
            else $string = 'badge-warning';
        }
        else
        {
            if ( $area === 'user' ) $string = 'bg-danger';
            else $string = 'badge-danger';
        }
        
        return $string;
    }
}

/**
 * Get Articles Category Data
 *
 * @param  integer $ticket_id
 * @return integer
 */
if ( ! function_exists( 'ticket_replies_count' ) )
{
    function ticket_replies_count( $ticket_id )
    {
        return get_sm_object()->ticket_replies_count( $ticket_id );
    }
}
