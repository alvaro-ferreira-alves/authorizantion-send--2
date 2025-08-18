<?php



namespace Rafa\Http\Controllers;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Headers: Content-Type");
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class AuthController
{
    private static $key = '123456'; //Application Key


    public function file_get_content_curl($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }


    public function login(){

        $url = 'https://app-bot-wpp-5.onrender.com/pdv.php?pdv=' . $_POST['pdv'];

        $dados = json_decode($this->file_get_content_curl($url));

        if ($dados) {
            if ($_POST['pdv'] == $dados->pdv) {
                /* creating access token */
                $issuedAt = time();
                // jwt valid for 60 days (60 seconds * 60 minutes * 24 hours * 60 days)
                $expirationTime = $issuedAt + 3;

                $payload = [
                    'iss' => 'http://example.org',
                    'aud' => 'http://example.com',
                    'iat' => $issuedAt,
                    'exp' => $expirationTime,
                ];

                /**
                 * IMPORTANT:
                 * You must specify supported algorithms for your application. See
                 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
                 * for a list of spec-compliant algorithms.
                 */
                $jwt = JWT::encode($payload, self::$key, 'HS256');
                $decoded = JWT::decode($jwt, new Key(self::$key, 'HS256'));


                /*
     NOTE: This will now be an object instead of an associative array. To get
     an associative array, you will need to cast it as such:
    */

                $decoded_array = (array)$decoded;

                /**
                 * You can add a leeway to account for when there is a clock skew times between
                 * the signing and verifying servers. It is recommended that this leeway should
                 * not be bigger than a few minutes.
                 *
                 * Source: http://self-issued.info/docs/draft-ietf-oauth-json-web-token.html#nbfDef
                 */
                JWT::$leeway = 3; // $leeway in seconds
                $decoded = JWT::decode($jwt, new Key(self::$key, 'HS256'));

                return $jwt;
            }
        }


        throw new \Exception('Não autenticado! Entre em contato com o suporte.');
    }

    public static function checkAuth()
    {
        $http_header = apache_request_headers();

        if (isset($http_header['Authorization']) && $http_header['Authorization'] != null) {
            $bearer = explode(' ', $http_header['Authorization']);
            //$bearer[0] = 'bearer';
            //$bearer[1] = 'token jwt';

            $token = explode('.', $bearer[1]);
            $header = $token[0];
            $payload = $token[1];
            $sign = $token[2];

            //Conferir Assinatura
            $valid = hash_hmac('sha256', $header . "." . $payload, self::$key, true);
            $valid = self::base64UrlEncode($valid);


            JWT::$leeway = 1; // $leeway in seconds
            $decoded = JWT::decode($bearer[1], new Key(self::$key, 'HS256'));

            $issuedAt = time();
            if ($valid == $sign && $issuedAt < $decoded->exp) {
                return true;
            }
        }

        return false;
    }

    /*Criei os dois métodos abaixo, pois o jwt.io agora recomenda o uso do 'base64url_encode' no lugar do 'base64_encode'*/
    private static function base64UrlEncode($data)
    {
        // First of all you should encode $data to Base64 string
        $b64 = base64_encode($data);

        // Make sure you get a valid result, otherwise, return FALSE, as the base64_encode() function do
        if ($b64 === false) {
            return false;
        }

        // Convert Base64 to Base64URL by replacing “+” with “-” and “/” with “_”
        $url = strtr($b64, '+/', '-_');

        // Remove padding character from the end of line and return the Base64URL result
        return rtrim($url, '=');
    }
}
