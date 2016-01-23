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
 * Archivo de ejemplo para mostrar como enviar notificaciones push a dispositivo iOS y Android
 *
 * (EN)
 * 
 * Sample file to show how send push notifications to iOS & Android devices.
 * 
 */

/**
* Requerimos el archivo de la clase y creamos el objeto PushNotification();
* Require file class.pushmobile.php and create new object PushNotification();
*/

require_once('../src/class.pushmobile.php');

$push = new PushNotification();

/**
* Enviar notificación push a dispositivo iOS, método:
* Send push notification to iOS device, method:
* 
* Set:
* $device_token (required)
* $message (required)
* $args (optional)
*/

$device_token = "123456abcd";
$message = "Hola, soy una alerta push a iOS";
$args = array();

$push->ios($device_token, $message, $args);

/**
* Enviar notificación push a dispositivo Android, mediante Google Cloud Messaging.
* Send push notification to Android device.
* Set:
* $device_token (required)
* $message (required)
* $args (optional)
*/

$device_token = "123456abcd";
$message = "Hola, soy una alerta push a Android";
$args = array();

$push->android($device_token, $message, $args);

?>