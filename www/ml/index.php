<?php
session_start();

require 'Meli/meli.php';
require 'configApp.php';
require '/home/fulmkodp/public_html/class/sincroml.class.php';

$Obj = new sincroML;
$meli = new Meli($appId, $secretKey);

if($_GET['code']) {

	// If the code was in get parameter we authorize
	$user = $meli->authorize($_GET['code'], $redirectURI);

	// Now we create the sessions with the authenticated user
	$_SESSION['access_token'] = $user['body']->access_token;
	$_SESSION['expires_in'] = $user['body']->expires_in;
	$_SESSION['refresh_token'] = $user['body']->refresh_token;

	// We can check if the access token in invalid checking the time
	if($_SESSION['expires_in'] + time() + 1 < time()) {
		try {
			$refresh_tokn = $meli->refreshAccessToken();

			if ($refresh_tokn['body']->status==400) {
				echo '<pre>Error</pre>';
			} else {
				$new_token = $refresh_tokn['body']->access_token;
				$new_refresh_token = $refresh_tokn['body']->refresh_token;
		
				if (!empty($new_token) and !empty($new_refresh_token)) {
					if ($Obj->ActualizaToken($new_token,$new_refresh_token)) {
						echo '<pre>Ok - Sincronización establecida</pre>';
					} else {
						echo '<pre>Error al actualizar token</pre>';
					}
				} else {
					echo '<pre>Error - No obtuvimos token</pre>';
				}
			}

		} catch (Exception $e) {
			echo "Exception: ",  $e->getMessage(), "\n";
		}

    } else {

		$new_token = $_SESSION['access_token'];
		$new_refresh_token = $_SESSION['refresh_token'];

		if (!empty($new_token) and !empty($new_refresh_token)) {
			if ($Obj->ActualizaToken($new_token,$new_refresh_token)) {
				echo '<pre>Ok - Sincronización establecida</pre>';
			} else {
				echo '<pre>Error al actualizar token</pre>';
			}
		} else {
			echo '<pre>Error - No obtuvimos token</pre>';
		}

	}

} else {
	header('Location: ' . $meli->getAuthUrl($redirectURI, Meli::$AUTH_URL[$siteId]));
}
?>