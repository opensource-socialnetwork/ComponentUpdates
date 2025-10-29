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
		if(ossn_isAdminLoggedin()) {
				ossn_extend_view('pages/administrator/contents/components', 'componentupdates/js');
				ossn_extend_view('pages/administrator/contents/themes', 'componentupdates/js');
				
				ossn_register_page('component_updates_check', function(){
							header('Content-Type: application/json; charset=utf-8');	
							echo json_encode(com_updates_api_all());
				});
		}
});
function com_updates_api_all() {
		$sources = array(
				array(
						'name'     => 'Ossn Community',
						'endpoint' => 'https://www.opensource-socialnetwork.org/api/v1.0/components_store_updates',
				),
				array(
						'name'     => 'Comz Za-mans',
						'endpoint' => 'https://comz.z-mans.net/groups/api/comz_updates',
				),
				array(
						'name'     => 'OpenTeknik',
						'endpoint' => 'https://www.openteknik.com/api/v1.0/components_store_updates',
				),				
		);
		$sources_count = count($sources);

		$requests = array();
		$curl     = curl_multi_init();

		for($i = 0; $i < $sources_count; $i++) {
				$requests[$i] = curl_init($sources[$i]['endpoint']);
				curl_setopt($requests[$i], CURLOPT_HEADER, false);
				curl_setopt($requests[$i], CURLOPT_RETURNTRANSFER, true);
				curl_setopt($requests[$i], CURLOPT_URL, $sources[$i]['endpoint']);
				curl_setopt($requests[$i], CURLOPT_SSL_VERIFYPEER, false);
				curl_multi_add_handle($curl, $requests[$i]);
		}

		do {
				curl_multi_exec($curl, $running);
		} while($running > 0);

		$errors = false;
		for($i = 0; $i < $sources_count; $i++) {
				$result = curl_multi_getcontent($requests[$i]);
				if(empty($result)) {
						$errors[] = "Error loading updates from {$sources[$i]['name']}";
				} else {
						$results[] = $result;
				}
				curl_multi_remove_handle($curl, $requests[$i]);
				curl_multi_close($curl);
		}
		$list = false;
		if(!empty($results)) {
				$list = array();
				foreach($results as $json) {
						$json = json_decode($json, true);
						if(isset($json['payload'])) {
								foreach($json['payload'] as $component) {
										array_push($list, $component);
								}
						}
				}
				$store_list = array(
						'result' => $list,
				);
				if(isset($errors) && !empty($errors)) {
						$store_list['error'] = implode(',', $errors);
				}
				return $store_list;
		}
		return array(
				'error' => ossn_print('component:update:removeserver:error'),
		);
}