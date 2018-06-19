<?php

namespace App\Services;

use App\Entity\Constants\Constant;
use Psr\Container\ContainerInterface;

/**
 * Class PasswordEncoder
 * @package App\Services
 */
class PasswordEncoder
{
    const SERVICE_NAME = 'app.password';
    const BEGIN_SALT = 5;
    const SALT_LENGTH = 5;

    private $seedings = [];


    public function __construct()
    {
        $this->seedings = [
            'alpha'     =>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'alphanum'  => '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'num'       => '0123456789',
            'nozero'    => '123456789'
            ];
    }

    /**
     * @param string $type
     * @param int $length
     * @return string
     */
    public function random_str($type = 'alphanum', $length = 8)
    {
        switch($type)
        {
            case 'basic'    : return mt_rand();
                break;
            case 'alpha'    :
            case 'alphanum' :
            case 'num'      :
            case 'nozero'   :
                $str = '';
                for ($i=0; $i < $length; $i++) {
                    $str .= substr($this->seedings[$type], mt_rand(0, strlen($this->seedings[$type]) -1), 1);
                }
                return $str;
                break;
            case 'unique'   :
            case 'md5'      :
                return md5(uniqid(mt_rand()));
                break;
        }
    }

    /**
     * @param string $password
     * @param string $salt
     * @return string
     */
    public function encode($password = '', $salt = '')
    {
        $salt = substr($salt,self::BEGIN_SALT,self::SALT_LENGTH).$salt;
        $aSalt = str_split($salt, 1);
        $aPwd = str_split($password, 1);
        $pwdFinal = [];
        foreach ($aSalt as $key => $value) {
            array_push($pwdFinal,$value);
            if (isset($aPwd[$key])) {
                array_push($pwdFinal, $aPwd[$key]);
            }
        }
        return base64_encode(implode('',$pwdFinal));
    }

    /**
     * @param string $encoded
     * @param string $salt
     * @return string
     */
    public function decode($encoded = '')
    {
        $aEncoded = str_split(base64_decode($encoded), 1);
        $pwd = [];
        foreach($aEncoded as $key => $value ) {
            if(($key % 2 == 1) && ($key < Constant::PASSWORD_LENGTH * 2)) {
                $pwd[]= $value;
            }
        }
        return implode($pwd);
    }

}
