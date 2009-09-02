<?php
/*
Plugin Name: Kau-Boy's Backend Localization
Plugin URI: http://kau-boys.ramarka.de/blog/2009/09/01/kau-boys-backend-localization-plugin/
Description: Kau-Boy's Backend Localization
Version: 0.2
Author: Bernhard Kau
Author URI: http://kau-boys.ramarka.de/blog
*/


define('BACKEND_LOCALIZATION_URL', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)));

function init_backend_localization() {
	load_plugin_textdomain('backend_localization', false, dirname(plugin_basename(__FILE__)));
}

function backend_localization_admin_menu(){
	add_options_page("Kau-Boy's Backend Localization settings", 'Backend Language', 8, __FILE__, 'backend_localization_admin_settings');	
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
		update_option('kau-boys_backend_localization_language', $_POST['kau-boys_backend_localization_language']);
		$settings_saved = true;
	}
	$backend_locale = get_option('kau-boys_backend_localization_language');
	
	// set default if values haven't been recieved from the database
	if(empty($backend_locale)) $backend_locale = 'en_EN';
?>

<div class="wrap">
	<h2>Kau-Boy's Backend Localization</h2>
	<?php if($settings_saved) : ?>
	<div id="message" class="updated fade"><p><?php _e('Saved changes'); _e('Switch page or refresh to see the backend in the new language', 'backend_localization'); ?>.</p></div>
	<? endif ?>
	<p>
		<?php _e('Here you can customize the plugin for your needs.', 'backend_localization') ?>
	</p>
	<form method="post" action="">
		<p>
			<label for="kau-boys_backend_localization_language" style="width: 200px; display: inline-block;"><?php _e('Backend locale', 'backend_localization') ?></label>
			<input type="text" id="kau-boys_backend_localization_language" name="kau-boys_backend_localization_language" value="<?php echo $backend_locale ?>" />
			<span class="description"><?php _e('Here you can set the locale you want to use in the backend (default = en_EN).', 'backend_localization') ?></span>
		</p>
		<p class="submit">
			<input class="button-primary" name="save" type="submit" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
</div>

<?php
}

function localize_backend($locale) {
	if(defined('WP_ADMIN')) {
		$locale = get_option('kau-boys_backend_localization_language');
	}
	return $locale;
}

add_action('init', 'init_backend_localization');
add_action('admin_menu', 'backend_localization_admin_menu');
add_filter('plugin_action_links', 'backend_localization_filter_plugin_actions', 10, 2);
add_filter('locale', 'localize_backend', 1, 1);

?>