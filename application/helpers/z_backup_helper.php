<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Backup Helper
 *
 * @author Shahzaib
 */


/**
 * Create Backup File Name
 *
 * Use to create a file name with full date ( Y-m-d ) and ".zip" extension.
 *
 * @param  string $type
 * @return string
 */
if ( ! function_exists( 'create_backup_file_name' ) )
{
    function create_backup_file_name( $type )
    {
        return "{$type}_" . get_site_date() . '_' . time() . ".zip";
    }
}

/**
 * Create Backup Path
 *
 * Use to prepend the path of backup keeper directory with the given file name.
 *
 * @param  string $file
 * @return string
 */
if ( ! function_exists( 'create_backup_path' ) )
{
    function create_backup_path( $file )
    {
        return append_slash( BACKUPS_DIRECTORY ) . $file;
    }
}

/**
 * Log a Backup
 *
 * Use to insert a backup log in database.
 *
 * @param  string  $backup_file
 * @param  integer $option
 * @param  integer $action
 * @return void
 */
if ( ! function_exists( 'log_a_backup' ) )
{
    function log_a_backup( $backup_file, $option, $action )
    {
        $ci =& get_instance();
        
        $data = [
          'backup_file' => $backup_file,
          'backup_option' => $option,
          'backup_action' => $action,
          'taken_at' => time()
        ];
        
        $ci->Tool_model->log_a_backup( $data );
    }
}

/**
 * Backup Database
 *
 * Use to take the backup of whole database whether to save on server
 * or to download.
 *
 * @param  integer $action
 * @param  boolean $all
 * @return void
 */
if ( ! function_exists( 'backup_database' ) )
{
    function backup_database( $action, $all = true )
    {
        $ci =& get_instance();
        
        $ci->load->dbutil();
        
        $file = create_backup_file_name( 'database' );
        
        $option = 3;
        
        $prefs = [
            'format' => 'zip',
            'filename' => $file
        ];
        
        if ( $all === false )
        {
            $prefs['tables'] = DB_BACKUP_TABLES;
            $option = 5;
        }
        
        @$backup =& $ci->dbutil->backup( $prefs );
        
        $ci->load->helper( 'download' );
        $ci->load->helper( 'file' );
        
        $to_call = 'write_file';
        $file_copy = $file;
        
        if ( $action === 1 )
        {
            $to_call = 'force_download';
        }
        else
        {
            $file = create_backup_path( $file );
        }
        
        log_a_backup( $file_copy, $option, $action );
        
        $to_call( $file, $backup );
    }
}

/**
 * Backup Files
 *
 * Use to take the backup of website files whether to save on
 * server or to download.
 *
 * @param  integer $action
 * @param  string  $directory
 * @param  integer $option
 * @return void
 */
if ( ! function_exists( 'backup_files' ) )
{
    function backup_files( $action, $directory = '', $option = 2 )
    {
        $ci =& get_instance();
        
        $ci->load->library( 'zip' );
        
        $file = create_backup_file_name( 'files' );
        $to_read = FCPATH;
        $path = create_backup_path( $file );
        
        if ( ! empty( $directory ) )
        {
            $to_read .= $directory;
        }
        
        $ci->zip->read_dir( $to_read, false );
        
        log_a_backup( $file, $option, $action );
        
        if ( $action === 1 )
        {
            $ci->zip->download( $file );
        }
        else
        {
            $ci->zip->archive( $path );
        }
        
        $ci->zip->clear_data();
    }
}

/**
 * Backup Options
 *
 * Supported options for the taking of backup.
 *
 * @param  string $key
 * @return mixed
 */
if ( ! function_exists( 'backup_options' ) )
{
    function backup_options( $key = '' )
    {
        $options = [
            1 => lang( 'only_languages_files' ),
            2 => lang( 'only_app_files' ),
            3 => lang( 'only_database' ),
            5 => lang( 'only_tickets_tables' ),
            4 => lang( 'only_uploads' )
        ];
        
        if ( array_key_exists( $key, $options ) )
        {
            return $options[$key];
        }
        
        return $options;
    }
}

/**
 * Backup Actions
 *
 * Supported actions for the taking of backup.
 *
 * @param  string $key
 * @return mixed
 */
if ( ! function_exists( 'backup_actions' ) )
{
    function backup_actions( $key = '' )
    {
        $actions = [
            1 => lang( 'download_backup_file' ),
            2 => lang( 'save_on_server_side' )
        ];
        
        if ( array_key_exists( $key, $actions ) )
        {
            return $actions[$key];
        }
        
        return $actions;
    }
}
