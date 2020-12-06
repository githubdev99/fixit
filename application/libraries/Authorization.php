<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Authorization
 * ----------------------------------------------------------
 * API Token Generate/Validation
 * 
 * @author: Jeevan Lal
 * @version: 0.0.1
 */

require_once APPPATH . 'third_party/php-jwt/JWT.php';
require_once APPPATH . 'third_party/php-jwt/BeforeValidException.php';
require_once APPPATH . 'third_party/php-jwt/ExpiredException.php';
require_once APPPATH . 'third_party/php-jwt/SignatureInvalidException.php';

use \Firebase\JWT\JWT;

class Authorization
{
    /**
     * Token Key
     */
    protected $token_key;

    /**
     * Token algorithm
     */
    protected $token_algorithm;

    /**
     * Token Request Header Name
     */
    protected $token_header;

    /**
     * Token Expire Time
     */
    protected $token_expire;
    protected $token_expire_time;


    public function __construct()
    {
        $this->CI = &get_instance();

        /** 
         * jwt config file load
         */
        $this->CI->load->config('jwt');

        /**
         * Load Config Items Values 
         */
        $this->token_key        = $this->CI->config->item('jwt_key');
        $this->token_algorithm  = $this->CI->config->item('jwt_algorithm');
        $this->token_header  = $this->CI->config->item('token_header');
        $this->token_expire  = $this->CI->config->item('token_expire');
        $this->token_expire_time  = $this->CI->config->item('token_expire_time');
    }

    /**
     * Generate Token
     * @param: {array} data
     */
    public function generate_token($data = null)
    {
        if ($data and is_array($data)) {
            $data['token_time'] = time();

            try {
                return [
                    'error' => FALSE,
                    'data' => JWT::encode($data, $this->token_key, $this->token_algorithm)
                ];
            } catch (Exception $e) {
                return [
                    'error' => TRUE,
                    'data' => $e->getMessage()
                ];
            }
        } else {
            return [
                'error' => TRUE,
                'data' => 'token data undefined!'
            ];
        }
    }

    /**
     * Validate Token with Header
     * @return : user informations
     */
    public function validate_token()
    {
        /**
         * Request All Headers
         */
        $headers = $this->CI->input->request_headers();

        /**
         * Authorization Header Exists
         */
        $token_data = $this->token_is_exist($headers);
        if ($token_data['error'] === FALSE) {
            try {
                /**
                 * Token Decode
                 */
                try {
                    $token_decode = JWT::decode($token_data['data'], $this->token_key, array($this->token_algorithm));
                } catch (Exception $e) {
                    return [
                        'error' => TRUE,
                        'data' => $e->getMessage()
                    ];
                }

                if (!empty($token_decode) and is_object($token_decode)) {
                    // Check Token API Time [token_time]
                    if (empty($token_decode->token_time or !is_numeric($token_decode->token_time))) {
                        return [
                            'error' => TRUE,
                            'data' => 'token time not define!'
                        ];
                    } else {
                        if ($this->token_expire == false) {
                            /**
                             * All Validation False Return Data
                             */
                            return [
                                'error' => FALSE,
                                'data' => $token_decode
                            ];
                        } else {
                            /**
                             * Check Token Time Valid 
                             */
                            $time_difference = strtotime('now') - $token_decode->token_time;
                            if ($time_difference >= $this->token_expire_time) {
                                return [
                                    'error' => TRUE,
                                    'data' => 'token time expire.'
                                ];
                            } else {
                                /**
                                 * All Validation False Return Data
                                 */
                                return [
                                    'error' => FALSE,
                                    'data' => $token_decode
                                ];
                            }
                        }
                    }
                } else {
                    return [
                        'error' => TRUE,
                        'data' => 'forbidden!'
                    ];
                }
            } catch (Exception $e) {
                return [
                    'error' => TRUE,
                    'data' => $e->getMessage()
                ];
            }
        } else {
            // Authorization Header Not Found!
            return [
                'error' => TRUE,
                'data' => $token_data['data']
            ];
        }
    }

    /**
     * Token Header Check
     * @param: request headers
     */
    private function token_is_exist($headers)
    {
        if (!empty($headers) and is_array($headers)) {
            foreach ($headers as $header_name => $header_value) {
                if (strtolower(trim($header_name)) == strtolower(trim($this->token_header)))
                    return [
                        'error' => FALSE,
                        'data' => $header_value
                    ];
            }
        }

        return [
            'error' => TRUE,
            'data' => 'token is not defined.'
        ];
    }
}
