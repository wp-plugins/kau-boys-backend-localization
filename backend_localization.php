<?php
/*
Plugin Name: Kau-Boy's Backend Localization
Plugin URI: http://kau-boys.ramarka.de/blog/2009/09/01/kau-boys-backend-localization-plugin/
Description: This plugin enables you to run your blog in a different language than the backend of your blog. So you can serve your blog using e.g. German as the default language for the users, but keep English as the language for the administration.
Version: 0.4
Author: Bernhard Kau
Author URI: http://kau-boys.ramarka.de/blog
*/


define('BACKEND_LOCALIZATION_URL', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)));

$wp_locale_all = array(
	'af' => __('Afrikaans'),
	'ar' => __('Arabic'),
	'az' => __('Azerbaijani'),
	'bg_BG' => __('Bulgarian'),
	'bn_BD' => __('Bengali'),
	'bs_BS' => __('Bosnian'),
	'ca' => __('Catalan'),
	'ckb' => __('Kurdish'),
	'cs_CZ' => __('Czech'),
	'cy' => __('Cymraeg (Welsh)'),
	'da_DK' => __('Danish'),
	'de_DE' => __('German'),
	'el' => __('Greek'),
	'en_US' => __('English'),
	'eo' => __('Esperanto'),
	'es_ES' => __('Spanish'),
	'et' => __('Estonian'),
	'eu' => __('Basque'),
	'fa_IR' => __('Persian'),
	'fi' => __('Finnish'),
	'fo' => __('Faroese'),
	'fr_BE' => __('French (Belgium)'),
	'fr_FR' => __('French'),
	'gl_ES' => __('Galician '),
	'he_IL' => __('Hebrew'),
	'hi_IN' => __('Hindi'),
	'hr' => __('Croatian'),
	'hu_HU' => __('Hungarian'),
	'id_ID' => __('Indonesian'),
	'it_IT' => __('Italian'),
	'ja' => __('Japanese'),
	'ka_GE' => __('Georgian'),
	'ko_KR' => __('Korean'),
	'ky_KY' => __('Kyrgyz'),
	'lv' => __('Latvian'),
	'mk_MK' => __('Macedonian'),
	'ml_IN' => __('Malayalam'),
	'ms_MY' => __('Malay'),
	'my_MM' => __('Burmese (Myanmar)'),
	'nb_NO' => __('Norwegian (Bokmål)'),
	'nl' => __('Dutch'),
	'nn_NO' => __('Norwegian (Nynorsk)'),
	'pl_PL' => __('Polish'),
	'pt_BR' => __('Portuguese (Brazil)'),
	'pt_PT' => __('Portuguese'),
	'ro' => __('Romanian'),
	'ru_RU' => __('Russian'),
	'ru_UA' => __('Russian (Ukraine)'),
	'sd_PK' => __('Sindhi'),
	'si_LK' => __('Sinhalese'),
	'sk' => __('Slovak'),
	'sl_SI' => __('Slovenian'),
	'sq' => __('Albanian'),
	'sr_RS' => __('Serbian'),
	'su_ID' => __('Sundanese'),
	'sv_SE' => __('Swedish'),
	'sw' => __('Swahili'),
	'ta_IN' => __('Tamil'),
	'th' => __('Thai'),
	'tr' => __('Turkish'),
	'ug_CN' => __('Uighur'),
	'uk' => __('Ukrainian'),
	'ur' => __('Urdu'),
	'uz_UZ' => __('Uzbek'),
	'vi' => __('Vietnamese'),
	'zh_CN' => __('Chinese'),
	'zh_HK' => __('Chinese (Hong Kong)')
);

function init_backend_localization() {
	load_plugin_textdomain('backend-localization', false, dirname(plugin_basename(__FILE__)));
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
	
	if(isset($_POST['kau-boys_backend_localization_language'])){	
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
	update_option('kau-boys_backend_localization_language', $_POST['kau-boys_backend_localization_language']);
	
	return true;
}

function backend_localization_login_form(){
	global $wp_locale_all;
	
	$backend_locale_array = backend_localization_get_languages();
?>
<p>
	<label>
		<?php _e('Language', 'backend-localization') ?><br />
		<select name="kau-boys_backend_localization_language" id="user_email" style="width: 100%;">
		<?php foreach($backend_locale_array as $locale_value) : ?>
			<option value="<?php echo $locale_value ?>"><?php echo $wp_locale_all[$locale_value].' ('.$locale_value.')' ?></option>
		<?php endforeach ?>
		</select>
	</label>
</p>
<?php
}

function localize_backend($locale) {
	// save settings before getting the locale
	if(isset($_POST['kau-boys_backend_localization_language'])) backend_localization_save_setting();
	// set langauge if user is in admin area
	if(defined('WP_ADMIN')) {
		$locale = get_option('kau-boys_backend_localization_language');
	}
	return $locale;
}

add_action('init', 'init_backend_localization');
add_action('admin_menu', 'backend_localization_admin_menu');
add_action('login_form', 'backend_localization_login_form');
add_action('login_form_locale', 'localize_backend', 1, 1);
add_filter('plugin_action_links', 'backend_localization_filter_plugin_actions', 10, 2);
add_filter('locale', 'localize_backend', 1, 1);

?>