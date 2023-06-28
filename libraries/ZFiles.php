<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * ZFiles Library
 *
 * @author Shahzaib
 */
class ZFiles {
    
    /**
     * Upload Image File
     *
     * @param  string  $key The value of "name" attribute
     * @param  string  $dir Directory inside "uploads/"
     * @param  boolean $all
     * @param  string  $type
     * @return string|array
     */
    public function upload_image_file( $key, $dir, $all = false, $type = '' )
    {
        $ci =& get_instance();
        
        $config['upload_path'] = append_slash( IMG_UPLOADS_DIR ) . $dir;
        $config['allowed_types'] = ALLOWED_IMG_EXT;
        $config['max_size'] = MAX_ALLOWED_IMG_SIZE;
        $config['encrypt_name'] = true;
        
        if ( $type === 'avatar' && db_config( 'u_max_avator_size' ) != '' )
        {
            $avatar_size = explode( 'x', db_config( 'u_max_avator_size' ) );
            
            $config['max_width'] = intval( $avatar_size[0] );
            $config['max_height'] = intval( $avatar_size[1] );
        }
        
        $ci->load->library( 'upload' );
        $ci->upload->initialize( $config );
        
        if ( ! $ci->upload->do_upload( $key ) )
        {
            d_r_error( $ci->upload->display_errors() );
        }
        
        if ( ! $all )
        {
            return $ci->upload->data()['file_name'];
        }
        
        return $ci->upload->data();
    }
    
    /**
     * Upload Attachment
     *
     * @param  string $key The value of "name" attribute
     * @return array
     */
    public function upload_attachment( $key = 'attachment' )
    {
        $ci =& get_instance();
        
        $config['upload_path'] = append_slash( IMG_UPLOADS_DIR ) . 'attachments';
        $config['allowed_types'] = ALLOWED_ATTACHMENTS_EXT;
        $config['max_size'] = MAX_ALLOWED_ATTACHMENT_SIZE;
        $config['encrypt_name'] = true;
        
        $ci->load->library( 'upload' );
        $ci->upload->initialize( $config );
        
        if ( ! $ci->upload->do_upload( $key ) )
        {
            d_r_error( $ci->upload->display_errors() );
        }
        
        return $ci->upload->data();
    }
    
    /**
     * Upload User Avatar ( For Ajax ).
     *
     * @param  string $key The value of "name" attribute
     * @return string
     */
    public function upload_user_avatar( $key = 'picture' )
    {
        return $this->upload_image_file( $key, 'users', false, 'avatar' );
    }
    
    /**
     * Delete Image File ( Uploaded ).
     *
     * @param  string $dir Directory inside "uploads/"
     * @param  string $name
     * @return boolean
     */
    public function delete_image_file( $dir, $name )
    {
        $path = append_slash( IMG_UPLOADS_DIR ) . "{$dir}/{$name}";
        
        if ( file_exists( $path ) && ! empty( $name ) )
        {
            return unlink( $path );
        }
        
        return false;
    }
}
