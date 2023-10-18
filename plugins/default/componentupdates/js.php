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
 $updates = com_updates_api_ossn_community();
?>
<script>
//https://locutus.io/php/info/version_compare/
function com_update_version_compare(v1, v2, operator) {
	let i
	let x
	let compare = 0

	const vm = {
		dev: -6,
		alpha: -5,
		a: -5,
		beta: -4,
		b: -4,
		RC: -3,
		rc: -3,
		'#': -2,
		p: 1,
		pl: 1
	}

	const _prepVersion = function (v) {
		v = ('' + v).replace(/[_\-+]/g, '.')
		v = v.replace(/([^.\d]+)/g, '.$1.').replace(/\.{2,}/g, '.')
		return (!v.length ? [-8] : v.split('.'))
	}

	const _numVersion = function (v) {
		return !v ? 0 : (isNaN(v) ? vm[v] || -7 : parseInt(v, 10))
	}
	v1 = _prepVersion(v1)
	v2 = _prepVersion(v2)
	x = Math.max(v1.length, v2.length)
	for (i = 0; i < x; i++) {
		if (v1[i] === v2[i]) {
			continue
		}
		v1[i] = _numVersion(v1[i])
		v2[i] = _numVersion(v2[i])
		if (v1[i] < v2[i]) {
			compare = -1
			break
		} else if (v1[i] > v2[i]) {
			compare = 1
			break
		}
	}
	if (!operator) {
		return compare
	}
	// Important: operator is CASE-SENSITIVE.
	// "No operator" seems to be treated as "<."
	// Any other values seem to make the function return null.
	switch (operator) {
		case '>':
		case 'gt':
			return (compare > 0)
		case '>=':
		case 'ge':
			return (compare >= 0)
		case '<=':
		case 'le':
			return (compare <= 0)
		case '===':
		case '=':
		case 'eq':
			return (compare === 0)
		case '<>':
		case '!==':
		case 'ne':
			return (compare !== 0)
		case '':
		case '<':
		case 'lt':
			return (compare < 0)
		default:
			return null
	}
}
	$(document).ready(function(){
			//console.log(com_update_version_compare("5.4", "5.3" , ">"));
			//console.log(com_update_version_compare("5.2", "5.3" , ">"));
			var com_update_json = <?php echo json_encode($updates); ?>;	
			
			if(com_update_json['error']){
				var error_template = '<div class="alert alert-danger" role="alert"><h4 class="alert-heading">'+Ossn.Print('component:update:update:error')+'</h4><div>'+com_update_json['error']+'</div></div>';
				$('.ossn-admin-components-list').prepend(error_template);
			} else {
				$(com_update_json).each(function(){
						com_id = this.com_id;
						com_version = this.latest_version;
						$item = $('.ossn-admin-component-list-item[data-com-id="'+com_id+'"]');
						if($item.length > 0){
								local_com_version = $item.attr('data-com-version');
								//check if remote version is larger then local then show update
								if(com_update_version_compare(com_version, local_com_version , ">") === true){
										$item.find('.card-header').addClass('bg-warning');
										template_download = '<tr class="bg-warning"><th scope="row">'+Ossn.Print('component:update:update_available')+'</th><td><span>'+com_version+'</span> <a href="'+this.url+'" target="_blank" class="badge bg-success text-white">'+Ossn.Print('component:update:download')+'</a></td></tr>';
										
										$item.find('.card-body > table > tbody').prepend(template_download);
								}

						}
				});
			}
			
	});
</script>
