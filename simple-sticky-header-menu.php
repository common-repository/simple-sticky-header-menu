<?php
/*
Plugin Name: Simple Sticky Header Menu
Plugin URI: https://come2theweb.com/plugins/sshm/
Description: Make your website header sticky on scroll with this plugin, very simple way to use for any website, Make Header fix on scroll without any code confliction, this is free plugin to use.
Author: Come2theweb
Version: 1.0
Author URI: https://come2theweb.com
Text Domain: come2theweb
*/


function sshm_load_plugin_css() {
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'sshm_style', $plugin_url . 'assets/css/style_c2tw.css' );
	wp_enqueue_script( 'sshm_main', $plugin_url . 'assets/js/sshm_c2tw.js', array(), false, true );	
}
add_action( 'wp_enqueue_scripts', 'sshm_load_plugin_css', 99 );

function sshm_load_adminplugin_scripts() {
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'sshm_style', $plugin_url . 'assets/css/sshm_admin.css' );
}
add_action( 'admin_enqueue_scripts', 'sshm_load_adminplugin_scripts' );

/* ==== Load script and style here ======= */

function sshmMenu() {
	//create new top-level menu
	add_menu_page('Sticky Header Menu', 'Sticky Header Menu by C2tw', 'administrator', __FILE__, 'sshm_c2tw' , plugins_url('/assets/img/sshm.png', __FILE__) );
}

if ( is_admin() ){ // admin actions
	add_action('admin_menu', 'sshmMenu');
	add_action( 'admin_init', 'register_sshmsettings' );
} else{
	add_action('wp_head', 'sshmscript');
	add_action('wp_head', 'sshmcss');
}
// Script added inline to make padding top on body
function sshmscript(){ 
	$topspace =  get_option("topspace");
	if($topspace=='addtop'){
		echo '<script type="text/javascript">jQuery(document).ready(function(){
			var hdrhgt = jQuery("header, .header").height();
			jQuery("body").css({ "padding-top" : hdrhgt+"px" });
		 })</script>';
	  }
} 
// Script & css added inline to make header height on scroll  
function sshmcss(){ 
	$hsic =  get_option("header_selected_idcls");
	$stckyhgt =  get_option("sticky_hdrheight");
	if($hsic){
	?>
<style>html body <?php echo $hsic; ?>, html body.admin-bar <?php echo $hsic; ?>{position: fixed !important;top: 0 !important;left: 0 !important;width: 100% !important;	z-index: 9999999 !important;}</style>
<?php
	  } else{ ?>
      <style>html body header.sshm_header_sticky, html body header, html body header.header.sshm_header_sticky, html body.admin-bar header.sshm_header_sticky, html body.admin-bar header.header.sshm_header_sticky{
	position: fixed !important;
	top: 0 !important;
	left: 0 !important;
	width: 100% !important;
	z-index: 9999999 !important; transition:all ease-in-out 0.3s!important; -o-transition:all ease-in-out 0.3s!important; -webkit-transition:all ease-in-out 0.3s!important;
}</style>
      <?php
	  
	  }
	 if($stckyhgt){ 
		echo '<style>.heightchangeonscrl{height:'.$stckyhgt.'}</style>';
		?>
        <script>
        jQuery(window).scroll(function(){
		  var sticky = jQuery('html body header.sshm_header_sticky, html body header, html body header.header.sshm_header_sticky, html body.admin-bar header.sshm_header_sticky, html body.admin-bar header.header.sshm_header_sticky <?php if($hsic){ echo ', '.$hsic; } ?>'),
			  scroll = jQuery(window).scrollTop();
		  if (scroll >= 100) sticky.addClass('heightchangeonscrl');
		  else sticky.removeClass('heightchangeonscrl');
		});
        </script>
        <?php
	 }
} 
   
   
function register_sshmsettings() { // whitelist options
  register_setting( 'sshm-group', 'header_selected_idcls' );
  register_setting( 'sshm-group', 'sticky_hdrheight' );
  register_setting( 'sshm-group', 'topspace' );
}

function sshm_c2tw() {
?>
<div class="wrap">
<h1>Simple Sticky Header and Menu</h1>
<p>Make your website header sticky on scroll with this plugin, very simple way to use for any website, Make Header fix on scroll without any code confliction, this is free plugin to use.</p>
<div class="sshm_row">
	<div class="sshm_left">
    <form method="post" action="options.php">
    <?php settings_fields( 'sshm-group' ); ?>
    <?php do_settings_sections( 'sshm-group' ); ?>
    <div class="sshm_formrow">
        <label>Header ID or Class Name
        <span>(if your header is still not sticky. CLASS ex- .classname_without_space | ID ex- #idname_without_space)</span></label>
        <input type="text" name="header_selected_idcls" value="<?php echo esc_attr( get_option('header_selected_idcls') ); ?>" />
	</div>
    
    <div class="sshm_formrow">
        <label>Reduce Header Height 
        <span>(if you want to minimize sticky header height, write height in px, Ex- 100px,50px or leave it blank for default)</span></label>
        <input type="text" name="sticky_hdrheight" value="<?php echo esc_attr( get_option('sticky_hdrheight') ); ?>" />
	</div>
    
    <div class="sshm_formrow">
        <label>Top Space
        <span>(if website header is overlaping on content at the top possition, then choose "Yes")</span>
        </label>
        <label class="radiolabel"><input type="radio" name="topspace" value="notop" <?php if(get_option('topspace')=='notop'){ echo 'checked="checked"'; } ?> /> No</label>
        <label class="radiolabel"><input type="radio" name="topspace" value="addtop" <?php if(get_option('topspace')=='addtop'){ echo 'checked="checked"'; } ?> /> Yes</label>
	</div>
    <?php submit_button(); ?>
</form>
    </div>
    <div class="sshm_right">
    	<h3>How to get website header id or class name ?</h3>
    	<iframe src="https://www.youtube.com/embed/YeEhBp1yTR0" width="100%" height="330" frameborder="0"></iframe>
    </div>
</div>


<p>if you want to donate for this plugin : <a href="https://come2theweb.com" target="_blank">DONATE NOW</a></p>
</div>

<?php
}
// Register activation hook
register_activation_hook(__FILE__, 'sshm_activate_function');
function sshm_activate_function() {
    $siteurl = get_site_url();
	$sdate = date('d M Y');
	$autmail ='jitendra.wd@gmail.com';
	$authsub='A user activated plugin - Simple Sticky Header Menu';
	$autmsg='Dear Author, A user activate your plugin[Simple Sticky Header Menu] url is - '. $siteurl.' | Date - '.$sdate;
	wp_mail($autmail, $authsub, $autmsg);
}
?>