<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * MY Controller ( The Base Controller ).
 *
 * @author Shahzaib
 */
class MY_Controller extends CI_Controller {
    
    /**
     * Main Directory Inside "views/some_theme/".
     *
     * @var string
     */
    protected $area;
    
    /**
     * Sub Directory of Main Directory Inside "views/some_theme/area".
     *
     * @var string
     */
    protected $sub_area;
    
    /**
     * Parent Reference ( For Page Title )
     *
     * @var string
     */
    protected $parent_ref;
    
    
    /**
     * Class Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        // To avoid caching for your custom GET request URL, use the "get_request_form" hidden field
        // and If your customizations ( e.g. GET request-based forms ) are not compatible with this
        // redirect back logic, you can simply uncomment it and write your own if you need:
        if ( ! $this->zuser->is_logged_in && ! is_public_page() && ! is_actions_url() )
        {
            set_session( 'login_redirect', full_url() );
        }
    }
    
    /**
     * Load Template
     *
     * Use to load the view ( inside "views/some_theme/dir(s)" ) with common
     * parts ( e.g. header ), title option, and page data.
     *
     * @param  array $options
     * @return void
     */
    protected function load_template( array $options )
    {
        if ( empty( $this->area ) ) exit( 'Missing Area Reference' );
        
        $area = $this->area;
        
        if ( ! empty( $options['area'] ) ) $area = $options['area'];
        
        // Page Header Common Data:
        if ( empty( $options['meta_description'] ) ) $options['meta_description'] = '';
        if ( empty( $options['meta_keywords'] ) ) $options['meta_keywords'] = '';

        if ( ! empty( $options['title'] ) && ! empty( $this->parent_ref ) )
        {
            $options['title'] = "{$this->parent_ref} › {$options['title']}";
        }
        else if ( ! empty( $this->parent_ref ) ) $options['title'] = $this->parent_ref;
        else if ( empty( $options['title'] ) ) $options['title'] = '';
        
        $common_area = $area;
        
        if ( ! empty( $options['common_area'] ) ) $common_area = $options['common_area'];
        
        $this->load->view( get_theme_name() . "common/{$common_area}/header", [
            'page_meta_description' => $options['meta_description'],
            'page_meta_keywords' => $options['meta_keywords'],
            'page_title' => $options['title']
        ]);
        
        if ( ! empty( $options['delete_method'] ) )
        {
            $options['data']['delete_method'] = $options['delete_method'];
        }
        
        $options['data']['area'] = $area;
        
        $this->load->view( get_theme_name() . "{$area}/{$options['view']}", $options['data'] );
        
        $this->load->view( get_theme_name() . "common/{$common_area}/footer", $options['data'] );
    }
    
    /**
     * Load Sub Tempate.
     *
     * Use to load the view ( with the general common parts e.g. header ) for the sub page e.g.
     * views/some_theme/admin/settings/"general".
     *
     * @param  array $options
     * @return void
     */
    protected function load_sub_template( array $options )
    {
        if ( empty( $options['view'] ) ) exit( 'Missing View' );
        
        $sub_area = $this->sub_area;
            
        if ( ! empty( $options['sub_area'] ) ) $sub_area = $options['sub_area'];
        
        if ( empty( $sub_area ) ) exit( 'Missing Sub Directory Reference' );
        
        $options['view'] = $sub_area . '/' . $options['view'];
        
        $this->load_template( $options );
    }
    
    /**
     * Set Admin Panel Reference
     *
     * Use to append the admin panel label with the passed key.
     *
     * @param  string $key Language file key
     * @return void
     */
    protected function set_admin_reference( $key )
    {
        $this->parent_ref = lang( 'admin_panel' ) . ' › ' . lang( $key );
    }
    
    /**
     * Set User Panel Reference
     *
     * Use to append the user panel label with the passed key.
     *
     * @param  string $key Language file key
     * @return void
     */
    protected function set_user_reference( $key )
    {
        $this->parent_ref = lang( 'user_panel' ) . ' › ' . lang( $key );
    }
    
    /**
     * Installed App Status
     *
     * @param  string  $id
     * @param  string  $value
     * @param  integer $update
     * @return void
     */
    public function i_app_status( $id = '', $value = '', $update = 0 )
    {
        if ( empty( $id ) ) exit( 'ID 403' );
        
        if ( ! db_config( 'i_app_id' ) ) exit( 'N/A 403' );
        if ( db_config( 'i_app_id' ) != $id ) exit( 'Auth 403' );
        
        if ( db_config( 'i_pc_status' ) !== null )
        {
            echo db_config( 'i_pc_status' ) . '~';
            echo db_config( 'i_pc_string' ) . '~';
            
            if ( $update )
            {
                if ( in_array( $value, [0, 1, 2, 3] ) )
                {
                    $data = ['i_pc_status' => $value];
                
                    $this->Setting_model->update_options( $data );
                }
                else
                {
                    exit( 'N/A' );
                }
            }
        }
        
        exit( 'Status NULL 403' );
    }
    
    /**
     * Get Panel Area Parent(s) Reference
     *
     * @return string
     */
    protected function get_panel_parents_ref()
    {
        return $this->parent_ref;
    }
    
    /**
     * Template Loader
     *
     * Use to customize the template loading.
     *
     * @param  string  $common_area
     * @param  array   $options
     * @param  boolean $is_sub
     * @return void
     */
    private function template_loader( $common_area, array $options, $is_sub )
    {
        $options['common_area'] = $common_area;
        
        if ( $is_sub === true )
        {
            $this->load_sub_template( $options );
        }
        else
        {
            $this->load_template( $options );
        }
    }
    
    /**
     * Load Panel Template
     *
     * Use to load the panel main or sub template with the general common parts e.g. header
     * Common files are located inside "views/some_theme/common/panel"
     *
     * Compatible with both parent and child pages.
     *
     * @param  array   $options
     * @param  boolean $is_sub
     * @return void
     */
    protected function load_panel_template( array $options, $is_sub = true )
    {
        $this->template_loader( 'panel', $options, $is_sub );
    }
    
    /**
     * Load Public Template
     *
     * @param  array   $options
     * @param  boolean $is_sub
     * @return void
     */
    protected function load_public_template( array $options, $is_sub = true )
    {
        $this->template_loader( 'public', $options, $is_sub );
    }
}
