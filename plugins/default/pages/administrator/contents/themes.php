<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
 ?>
<div class="panel-group ossn-admin-components-list" id="accordion">
   	<?php
	$themes = new OssnThemes;
	$list = $themes->getThemes();
	if($list){
		foreach ($list as $id) {
			$vars = array();
			$vars['OssnThemes'] = $themes;
			$vars['id'] = $id;
			$vars['theme'] = $themes->getTheme($id);;
			echo "<div class='ossn-admin-component-list-item' data-com-id='{$id}' data-com-version='{$vars['theme']->version}'>";
			echo ossn_plugin_view("admin/themes/list/item", $vars);
			echo "</div>";
		}
	}
	?>
</div> 