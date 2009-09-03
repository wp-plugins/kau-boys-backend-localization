<?php
/*
Plugin Name: Kau-Boy's Backend Localization
Plugin URI: http://kau-boys.ramarka.de/blog/2009/09/01/kau-boys-backend-localization-plugin/
Description: Kau-Boy's Backend Localization
Version: 0.3
Author: Bernhard Kau
Author URI: http://kau-boys.ramarka.de/blog
*/


define('BACKEND_LOCALIZATION_URL', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)));

function init_backend_localization() {
	load_plugin_textdomain('backend-localization', false, dirname(plugin_basename(__FILE__)));
}

function backend_localization_admin_menu(){
	add_options_page("Kau-Boy's Backend Localization settings", __('Backend Language'), 8, __FILE__, 'backend_localization_admin_settings');	
}

function backend_localization_filter_plugin_actions($links, $file){
	static $this_plugin;
	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
	
	if ($file == $this_plugin){
		$settings_link = '<a href="options-general.php?page=kau-boys-backend-localization/backend_localization.php">'.__('Settings').'</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}

function backend_localization_admin_settings(){
	if(isset($_POST['save'])){	
		$settings_saved = backend_localization_save_setting();
	}
	$backend_locale = get_option('kau-boys_backend_localization_language');
	
	// set default if values haven't been recieved from the database
	if(empty($backend_locale)) $backend_locale = 'en_US';
?>

<div class="wrap">
	<h2>Kau-Boy's Backend Localization</h2>
	<?php if($settings_saved) : ?>
	<div id="message" class="updated fade"><p><?php _e('Saved changes') ?>.</p></div>
	<? endif ?>
	<p>
		<?php _e('Here you can customize the plugin for your needs.', 'backend-localization') ?>
	</p>
	<form method="post" action="">
		<p>
			<h2><?php _e('Available languages') ?></h2>
			<?php $backend_locale_array = backend_localization_get_languages() ?>
			<?php foreach($backend_locale_array as $locale_value) : ?>
			<input type="radio" value="<?php echo $locale_value ?>" id="kau-boys_backend_localization_language_<?php echo $locale_value ?>" name="kau-boys_backend_localization_language"<?php echo ($backend_locale == $locale_value)? ' checked="checked"' : '' ?> />
			<label for="kau-boys_backend_localization_language_<?php echo $locale_value ?>" style="width: 200px; display: inline-block;">
				<img src="<?php echo BACKEND_LOCALIZATION_URL.'flag_icons/'.strtolower(substr($locale_value, (strpos($locale_value, '_') * -1))).'.png' ?>" alt="<?php echo $locale_value ?>" />
				<?php echo $locale_value ?>
			</label>
			<br />
			<?php endforeach ?>
			<div class="description">
				<?php echo sprintf(__('If your language isn\'t listed, you have to download the right version from the WordPress repository: <a href="http://svn.automattic.com/wordpress-i18n">http://svn.automattic.com/wordpress-i18n</a>. Browser to the language folder of your choice and get the <b>all</b> .mo files for your WordPress Version from <i><b>tags/%1s/messages/</b></i> or from the <i><b>trunk/messages/</b></i> folder. Upload them to the langauge folder <i>%2s</i>. You should than be able to choose the new language (or after a refresh of this page).', 'backend-localization'), $GLOBALS['wp_version'], WP_LANG_DIR) ?>
			</div>
		</p>
		<p class="submit">
			<input class="button-primary" name="save" type="submit" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
</div>

<?php
}

function backend_localization_get_languages(){
	$backend_locale_array = array();
	
	if(is_dir(WP_LANG_DIR)){
		$files = scandir(WP_LANG_DIR);
		foreach($files as $file){
			$fileParts = pathinfo($file);
			if($fileParts['extension'] == 'mo' && (strlen($fileParts['filename']) == 2 || strlen($fileParts['filename']) == 5)){
				$backend_locale_array[] = $fileParts['filename'];
			}
		}
	}
	
	if(!in_array('en_US', $backend_locale_array)){
		$backend_locale_array[] = 'en_US';
	}
	sort($backend_locale_array);
	
	return $backend_locale_array;
}

function backend_localization_save_setting(){
	update_option('kau-boys_backend_localization_language', $_POST['kau-boys_backend_localization_language']);
	
	return true;
}

function localize_backend($locale) {
	if(defined('WP_ADMIN')) {
		// save settings before getting the locale
		if(isset($_POST['save'])) backend_localization_save_setting(); 
		$locale = get_option('kau-boys_backend_localization_language');
	}
	return $locale;
}

add_action('init', 'init_backend_localization');
add_action('admin_menu', 'backend_localization_admin_menu');
add_filter('plugin_action_links', 'backend_localization_filter_plugin_actions', 10, 2);
add_filter('locale', 'localize_backend', 1, 1);

?>