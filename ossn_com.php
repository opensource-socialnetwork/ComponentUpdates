<?php
/**
 * Open Source Social Network
 *
 * @package   Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
define('__COMPONENT_UPDATES__', ossn_route()->com . 'ComponentUpdates/');
ossn_register_callback('ossn', 'init', function () {
		if(ossn_isAdminLoggedin()){
			ossn_extend_view('pages/administrator/contents/components', 'componentupdates/js');
		}
});
function com_updates_api_ossn_community() {
		$API_END_POINT = 'https://www.opensource-socialnetwork.org/api/v1.0/components_store_updates';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $API_END_POINT);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response  = curl_exec($ch);
		$error_msg = false;
		if(curl_errno($ch)) {
				$error_msg = curl_error($ch);
		}
		curl_close($ch);
		if($error_msg) {
				return array(
						'error' => $error_msg,
				);
		}
		$json = json_decode($response, true);
		if(isset($json['payload'])) {
				return $json['payload'];
		}
		return array(
				'error' => ossn_print('component:update:removeserver:error'),
		);
}