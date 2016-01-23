<?php
/**
 * Push mobile - iOS & Android
 *
 * This file is part of Push mobile.
 *
 * Push mobile is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Push mobile is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Push mobile. If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * (ES)
 * 
 * Archivo con la clase principal
 *
 * (EN)
 * 
 * File with main class
 * 
 */

class PushNotification
{
    protected $sandbox = false;

    protected $ios_pem_file_path = "";

    protected $google_api_key = "";

    /** Función para llamar al servidor Push de Apple (necesario enviar el certificado) **/
    public function ios($device_token, $message = null, array $args = array())
    {
        if(!$device_token)
        {
            return;
        }

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $this->ios_pem_file_path);
        stream_context_set_option($ctx, 'ssl', 'passphrase', 'pushchat');
        $sandbox = $this->sandbox ? 'sandbox.' : '';
        $url = "ssl://gateway.{$sandbox}push.apple.com:2195";
        $fp = stream_socket_client($url, $err, $errstr, 60, STREAM_CLIENT_ASYNC_CONNECT, $ctx);
        if (!$fp){
            return false;
        }

        $body['aps'] = $args+['sound' => 'default', 'alert' => $message];
        $payload = json_encode($body);
        $msg = chr( 0 ) . pack('n', 32) . pack('H*', $device_token) . pack('n', strlen($payload)) . $payload;
        fwrite($fp, $msg, strlen($msg));
        fclose($fp);
        return true;
    }

    /** Función para llamar al google cloud messaging de Google y enviar notificaciones push a dispositivos Android **/
    public function android($device_token, $message = null, array $args = array())
    {
        if(!$device_token)
        {
            return false;
        }

        /** Hacemos la petición mediante cURL **/

        $ch = curl_init('https://android.googleapis.com/gcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: key='.$this->google_api_key,
            'Content-Type: application/json'
        ));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
            'registration_ids' => array($device_token),
            'data' => $args+['sound' => 'default', 'alert' => $message],
        )));

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }


}
?>