<?php
class JwtHandler {
    private static $secret = 'tu_clave_secreta';

    /**
     * La función codifica una carga útil en un token web JSON (JWT) utilizando el algoritmo HS256.
     * 
     * @param payload La carga útil es una estructura de datos que contiene la información que desea
     * incluir en el JWT (JSON Web Token). Puede ser cualquier objeto JSON válido que desee codificar e
     * incluir en el token.
     * 
     * @return una cadena JSON Web Token (JWT). La cadena JWT consta de tres partes separadas por
     * puntos: el encabezado codificado en base64Url, la carga útil codificada en base64Url y la firma
     * codificada en base64Url.
     */
    public static function encode($payload) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$secret, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
    }

    /**
     * La función decodifica un token web JSON (JWT) dividiéndolo en sus partes de encabezado, carga
     * útil y firma, y luego verifica la firma para garantizar que el token sea válido.
     * 
     * @param jwt El parámetro `jwt` es una cadena JSON Web Token (JWT) que consta de tres partes
     * separadas por puntos. Las tres partes son el encabezado, la carga útil y la firma del JWT.
     * 
     * @return la carga útil decodificada como un objeto JSON si la firma proporcionada coincide con la
     * firma calculada. De lo contrario, devuelve nulo.
     */
    public static function decode($jwt) {
        $tokenParts = explode('.', $jwt);
        $header = base64_decode(str_replace(['-', '_'], ['+', '/'], $tokenParts[0]));
        $payload = base64_decode(str_replace(['-', '_'], ['+', '/'], $tokenParts[1]));
        $signatureProvided = str_replace(['-', '_'], ['+', '/'], $tokenParts[2]);
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$secret, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        if (($signature === base64_decode($signatureProvided))) {
            return json_decode($payload);
        } else {
            return null;
        }
    }
}
