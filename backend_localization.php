<?php
/*
Plugin Name: Kau-Boy's Backend Localization
Plugin URI: http://kau-boys.ramarka.de/blog/2009/09/01/kau-boys-backend-localization-plugin/
Description: This plugin enables you to run your blog in a different language than the backend of your blog. So you can serve your blog using e.g. German as the default language for the users, but keep English as the language for the administration.
Version: 0.6
Author: Bernhard Kau
Author URI: http://kau-boys.ramarka.de/blog
*/


define('BACKEND_LOCALIZATION_URL', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)));

$wp_locale_all = array();

function init_backend_localization(){
	global $wp_locale_all;
	
	load_plugin_textdomain('backend-localization', false, dirname(plugin_basename(__FILE__)));
	
	$wp_locale_all = array(
		'af' => __('Afrikaans', 'backend-localization'),
		'ar' => __('Arabic', 'backend-localization'),
		'az' => __('Azerbaijani', 'backend-localization'),
		'bg_BG' => __('Bulgarian', 'backend-localization'),
		'bn_BD' => __('Bengali', 'backend-localization'),
		'bs_BS' => __('Bosnian', 'backend-localization'),
		'ca' => __('Catalan', 'backend-localization'),
		'ckb' => __('Kurdish', 'backend-localization'),
		'cs_CZ' => __('Czech', 'backend-localization'),
		'cy' => __('Cymraeg (Welsh)', 'backend-localization'),
		'da_DK' => __('Danish', 'backend-localization'),
		'de_DE' => __('German', 'backend-localization'),
		'el' => __('Greek', 'backend-localization'),
		'en_US' => __('English', 'backend-localization'),
		'eo' => __('Esperanto', 'backend-localization'),
		'es_ES' => __('Spanish', 'backend-localization'),
		'et' => __('Estonian', 'backend-localization'),
		'eu' => __('Basque', 'backend-localization'),
		'fa_IR' => __('Persian', 'backend-localization'),
		'fi' => __('Finnish', 'backend-localization'),
		'fo' => __('Faroese', 'backend-localization'),
		'fr_BE' => __('French (Belgium)', 'backend-localization'),
		'fr_FR' => __('French', 'backend-localization'),
		'gl_ES' => __('Galician ', 'backend-localization'),
		'he_IL' => __('Hebrew', 'backend-localization'),
		'hi_IN' => __('Hindi', 'backend-localization'),
		'hr' => __('Croatian', 'backend-localization'),
		'hu_HU' => __('Hungarian', 'backend-localization'),
		'id_ID' => __('Indonesian', 'backend-localization'),
		'it_IT' => __('Italian', 'backend-localization'),
		'ja' => __('Japanese', 'backend-localization'),
		'ka_GE' => __('Georgian', 'backend-localization'),
		'ko_KR' => __('Korean', 'backend-localization'),
		'ky_KY' => __('Kyrgyz', 'backend-localization'),
		'lv' => __('Latvian', 'backend-localization'),
		'mk_MK' => __('Macedonian', 'backend-localization'),
		'ml_IN' => __('Malayalam', 'backend-localization'),
		'ms_MY' => __('Malay', 'backend-localization'),
		'my_MM' => __('Burmese (Myanmar)', 'backend-localization'),
		'nb_NO' => __('Norwegian (Bokmål)', 'backend-localization'),
		'nl' => __('Dutch', 'backend-localization'),
		'nn_NO' => __('Norwegian (Nynorsk)', 'backend-localization'),
		'pl_PL' => __('Polish', 'backend-localization'),
		'pt_BR' => __('Portuguese (Brazil)', 'backend-localization'),
		'pt_PT' => __('Portuguese', 'backend-localization'),
		'ro' => __('Romanian', 'backend-localization'),
		'ru_RU' => __('Russian', 'backend-localization'),
		'ru_UA' => __('Russian (Ukraine)', 'backend-localization'),
		'sd_PK' => __('Sindhi', 'backend-localization'),
		'si_LK' => __('Sinhalese', 'backend-localization'),
		'sk' => __('Slovak', 'backend-localization'),
		'sl_SI' => __('Slovenian', 'backend-localization'),
		'sq' => __('Albanian', 'backend-localization'),
		'sr_RS' => __('Serbian', 'backend-localization'),
		'su_ID' => __('Sundanese', 'backend-localization'),
		'sv_SE' => __('Swedish', 'backend-localization'),
		'sw' => __('Swahili', 'backend-localization'),
		'ta_IN' => __('Tamil', 'backend-localization'),
		'th' => __('Thai', 'backend-localization'),
		'tr' => __('Turkish', 'backend-localization'),
		'ug_CN' => __('Uighur', 'backend-localization'),
		'uk' => __('Ukrainian', 'backend-localization'),
		'ur' => __('Urdu', 'backend-localization'),
		'uz_UZ' => __('Uzbek', 'backend-localization'),
		'vi' => __('Vietnamese', 'backend-localization'),
		'zh_CN' => __('Chinese', 'backend-localization'),
		'zh_HK' => __('Chinese (Hong Kong)', 'backend-localization')
	);
}

function backend_localization_admin_menu(){
	add_options_page("Kau-Boy's Backend Localization settings", __('Backend Language', 'backend-localization'), 8, __FILE__, 'backend_localization_admin_settings');	
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
	global $wp_locale_all;
	
	$backend_locale = backend_localization_get_locale();
	
	// set default if values haven't been recieved from the database
	if(empty($backend_locale)) $backend_locale = 'en_US';
?>

<div class="wrap">
	<?php screen_icon(); ?>
	<h2>Kau-Boy's Backend Localization</h2>
	<?php if($settings_saved) : ?>
	<div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
	<? endif ?>
	<p>
		<?php _e('Here you can customize the plugin for your needs.', 'backend-localization') ?>
	</p>
	<form method="post" action="">
		<p>
			<h2><?php _e('Available languages', 'backend-localization') ?></h2>
			<?php $backend_locale_array = backend_localization_get_languages() ?>
			<?php foreach($backend_locale_array as $locale_value) : ?>
			<input type="radio" value="<?php echo $locale_value ?>" id="kau-boys_backend_localization_language_<?php echo $locale_value ?>" name="kau-boys_backend_localization_language"<?php echo ($backend_locale == $locale_value)? ' checked="checked"' : '' ?> />
			<label for="kau-boys_backend_localization_language_<?php echo $locale_value ?>" style="width: 200px; display: inline-block;">
				<img src="<?php echo BACKEND_LOCALIZATION_URL.'flag_icons/'.strtolower(substr($locale_value, (strpos($locale_value, '_') * -1))).'.png' ?>" alt="<?php echo $locale_value ?>" />
				<?php echo $wp_locale_all[$locale_value].' ('.$locale_value.')' ?>
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
			if($fileParts['extension'] == 'mo' && (strlen($fileParts['filename']) <= 5)){
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
	setcookie('kau-boys_backend_localization_language', $_POST['kau-boys_backend_localization_language'], time()+60*60*24*30);
	
	return true;
}

function backend_localization_login_form(){
	global $wp_locale_all;
	
	$backend_locale_array = backend_localization_get_languages();
	$backend_locale = backend_localization_get_locale();
?>
<p>
	<label>
		<?php _e('Language', 'backend-localization') ?><br />
		<select name="kau-boys_backend_localization_language" id="user_email" style="width: 100%; color: #555;">
		<?php foreach($backend_locale_array as $locale_value) : ?>
			<option value="<?php echo $locale_value ?>"<?php echo ($backend_locale == $locale_value)? ' selected="selected"' : '' ?>>
				<?php echo $wp_locale_all[$locale_value].' ('.$locale_value.')' ?>
			</option>
		<?php endforeach ?>
		</select>
	</label>
</p>
<?php
}

function backend_localization_get_locale(){
	return 	isset($_POST['kau-boys_backend_localization_language'])
			? $_POST['kau-boys_backend_localization_language']
			: (	isset($_COOKIE['kau-boys_backend_localization_language'])
				? $_COOKIE['kau-boys_backend_localization_language']
				: get_option('kau-boys_backend_localization_language'));
}

function localize_backend($locale){
	// set langauge if user is in admin area
	if(defined('WP_ADMIN') || (isset($_POST['pwd']) && isset($_POST['kau-boys_backend_localization_language']))) {
		$locale = backend_localization_get_locale();
	}
	return $locale;
}

add_action('init', 'init_backend_localization');
add_action('admin_menu', 'backend_localization_admin_menu');
add_action('login_form_locale', 'localize_backend', 1, 1);
add_action('login_head', 'localize_backend', 1, 1);
add_action('login_form', 'backend_localization_login_form');
add_action('plugins_loaded', 'backend_localization_save_setting'); // TODO: recognize if settings should be saved or have already been saved using another function to reenable sucess message 
add_filter('plugin_action_links', 'backend_localization_filter_plugin_actions', 10, 2);
add_filter('locale', 'localize_backend', 1, 1);

?>