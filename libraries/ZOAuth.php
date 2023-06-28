<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * ZOAuth Library
 *
 * @author Shahzaib
 */
class ZOAuth {
    
    /**
     * Hybridauth Supported Social Reference.
     *
     * @var object
     */
    protected $adapter;
    
    
    /**
     * Authenticate User
     *
     * @return mixed
     */
    public function authenticate()
    {
        try
        {
            $this->adapter->authenticate();
            
            if ( $this->adapter->isConnected() )
            {
                $user = $this->adapter->getUserProfile();
                
                $this->adapter->disconnect();
                
                return $user;
            }
            
            return false;
        }
        catch ( Exception $e )
        {
            exit( $e->getMessage() );
        }
    }
}
