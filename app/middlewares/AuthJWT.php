<?php


use Firebase\JWT\JWT;

class JWTAuthenticator 
{

    private static $secretKey = 'Te$tJWT';
    private static $encryptionType = ['HS256'];

    public static function createToken($data) 
    {
        $time_now = time();
        $payload = array(
            'iat' => $time_now,
            'exp' => $time_now + (60000)*24*365,
            'aud' => self::Aud(),
            'data' => $data,
            'app' => "Test JWT"
        );
        return JWT::encode($payload, self::$secretKey);
    }

    public static function verifyToken($token) 
    {
        if (empty($token)) 
        {
            throw new Exception("The token is empty.");
        }
        try 
        {
            $decoded = JWT::decode(
                $token,
                self::$secretKey,
                self::$encryptionType
            );

        } 
        catch (Exception $e) 
        {
            throw $e;
        }
        if ($decoded->aud !== self::Aud()) 
        {
            throw new Exception("User wrong");
        }
    }

    public static function getPayload($token) 
    {
        if (empty($token)) 
        {
            throw new Exception("The token is empty.");
        }
        return JWT::decode(
            $token,
            self::$secretKey,
            self::$encryptionType
        );
    }

    public static function getTokenData($token) 
    {
        $array = JWT::decode(
            $token,
            self::$secretKey,
            self::$encryptionType
        )->data;
        return $array;
    }

    private static function Aud() 
    {
        $aud = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
        {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } 
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
        {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } 
        else 
        {
            $aud = $_SERVER['REMOTE_ADDR'];
        }

        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();

        return sha1($aud);
    }
}
?>