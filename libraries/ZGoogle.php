<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

require_once APPPATH . 'third_party/hybridauth/autoload.php';
require_once APPPATH . 'libraries/ZOAuth.php';

use Hybridauth\Provider\Google;

/**
 * ZGoogle ( OAuth ) Library.
 *
 * Use to send the request to Google to get the user information for
 * the login purpose ( with the help of Hybridauth library ).
 *
 * @author Shahzaib
 */
class ZGoogle extends ZOAuth {
    
    /**
     * Class Constructor
     *
     * @param  array $keys
     * @return void
     */
    public function __construct( $keys )
    {
        if ( ! empty( $keys['public_id'] ) && ! empty( $keys['secret_id'] ) )
        {
            $config = [
                // Location where to redirect the user once he/she authenticated by the Google:
                'callback' => env_url( 'login/google' ),
                
                'keys' => [
                    'id' => $keys['public_id'],
                    'secret' => $keys['secret_id']
                ]
            ];
            
            try
            {
                $this->adapter = new Google( $config );    
            }
            catch ( Exception $e )
            {
                exit( $e->getMessage() );
            }
        }
        else
        {
            exit( 'Missing key(s)' );
        }
    }
}
