<?php
/*
Plugin Name: ReviewPilot Slider
Description: Få dine kundeudtalelser fra ReviewPilot ind som en flot slider.
Version: 1.0.0
Author: ReviewPilot
Author URI: https://www.kundeudtalelser.dk/
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

// Plugin code goes here

function review_slider_menu() {
	add_menu_page (
		'ReviewPilot Slider indstillinger',
		'ReviewPilot Slider',
		'manage_options',
		'review-slider-settings',
		'review_slider_settings_page',
		'dashicons-editor-quote'
	);
}
add_action('admin_menu', 'review_slider_menu');

// Create the content for the options page
function review_slider_settings_page() {
    ?>
    <style>
    <?php require_once('style.css'); ?>
    </style>
    <div class="wrap">
        <h1>ReviewPilot Slider indstillinger</h1>
        <form method="post" action="options.php">
            <?php
            // Add your options fields here using the Settings API
            settings_fields('review-slider-settings');
            do_settings_sections('review-slider-settings');
            submit_button();
            ?>
        </form>
    </div>
    <script>
    <?php require_once('script.js'); ?>
    </script>
  <?php
}

function review_slider_init() {
  // Register a new settings section
 
 add_settings_section(
  'review-slider-api-section',
  'API Indstillinger',
  'api_section_callback',
  'review-slider-settings'
);
 
 add_settings_section(
  'review-slider-shortcode-section',
  'Shortcode indstillinger',
  'shortcode_section_callback',
  'review-slider-settings'
);

  // Register the settings
  register_setting('review-slider-settings', 'review-slider-header-display');
  register_setting('review-slider-settings', 'review-slider-alternative-headline');
  register_setting('review-slider-settings', 'review-slider-star-rating-display');
  register_setting('review-slider-settings', 'review-slider-autoplay');
  register_setting('review-slider-settings', 'review-slider-looping');
  register_setting('review-slider-settings', 'review-slider-schema');
  register_setting('review-slider-settings', 'review-slider-interval');
  register_setting('review-slider-settings', 'review-slider-tags');
  register_setting('review-slider-settings', 'review-slider-api-key');
}
add_action('admin_init', 'review_slider_init');

function api_section_callback(){
  
    $value = get_option('review-slider-api-key'); ?>
    <div class="api-settings panel">
        <p>Indtast din unikke api nøgle fra kundeudtalelser.dk</p>
        <div class="rs-input-group">
            <label class="rs-label">API Nøgle</label>
            <input type="text" name="review-slider-api-key" value="<?php echo esc_attr($value) ?>" class="rs-input" placeholder="API Nøgle"/>
        </div>
    </div>

<?php }

function shortcode_section_callback(){ 
    $display_header_option = get_option('review-slider-header-display');
    $autoplay_option = get_option('review-slider-autoplay');
    $interval_option = get_option('review-slider-interval');
    $alternative_headline_option = get_option('review-slider-alternative-headline');
    $loop_option = get_option('review-slider-looping');
    $star_display_option = get_option('review-slider-star-rating-display');
    $schema_option = get_option('review-slider-schema');
    $tags_option = get_option('review-slider-tags');
    
    ?>
    <div class="shortcode-settings panel">

		<div class="shortcode-settings-side">

        <p>Brug nedenstående instillinger for at genererer en shortcode til brug på hjemmesiden.</p>
        
        <div class="rs-group-wrapper">
          <div class="rs-checkbox-group">
              <div class="rs-checkbox">
                  <input type="checkbox" data-linkedup name="review-slider-header-display" id="review-slider-header-display" value="1" <?php echo checked(1, $display_header_option, true);?>/>
                  <label class="rs-label checkbox-label" for="review-slider-header-display">Vis header på slideren</label>
              </div>
          </div>
        </div>

          <div class="rs-input-group" data-linked="review-slider-header-display" hidden>
              <label class="rs-label">Alternativ overskrift</label>
              <input type="text" name="review-slider-alternative-headline" value="<?php echo esc_attr($alternative_headline_option) ?>" class="rs-input" placeholder="Alternativ overskrift"/>
          </div>

        <div class="rs-group-wrapper">
          <div class="rs-checkbox-group">
              <div class="rs-checkbox">
                  <input type="checkbox" data-linkedup name="review-slider-autoplay" id="review-slider-autoplay" value="1" <?php echo checked(1, $autoplay_option, false);?>/>
                  <label class="rs-label checkbox-label" for="review-slider-autoplay">Afspil slideren automatisk</label>
              </div>
          </div>

          <div class="rs-checkbox-group">
              <div class="rs-checkbox">
                  <input type="checkbox" name="review-slider-looping" id="review-slider-looping" value="1" <?php echo checked(1, $loop_option, false);?>/>
                  <label class="rs-label checkbox-label" for="review-slider-looping">Kør slideren i ring</label>
              </div>
          </div>
        </div>

        <div class="rs-input-group" data-linked="review-slider-autoplay" hidden>
            <label class="rs-label">Sekunder mellem slider skift</label>
            <input type="number" name="review-slider-interval" value="<?php echo esc_attr($interval_option) ?>" class="rs-input" placeholder="Sekunder mellem slider skift"/>
        </div>

        <div class="rs-group-wrapper">
          <div class="rs-input-group">
              <label class="rs-label select-label">Visning af stjerner</label>

              <select name="review-slider-star-rating-display" class="rs-input rs-select">
              <?php

              $options = array(
                'both' => 'Vis stjerner i både header, og i slides',
                'header' => 'Vis kun stjerner i header',
                'slide' => 'Vis kun stjerner på individuelle slides',
                'none' => 'Vis IKKE stjerner'
              );
              
              foreach ($options as $key => $label) {
                echo '<option value="' . esc_attr($key) . '" ' . selected($key, $star_display_option, false) . '>' . esc_html($label) . '</option>';
              } ?>
              </select>
          </div>
        </div>

        
        <div class="rs-group-wrapper">
          <div class="rs-checkbox-group">
              <div class="rs-checkbox">
                  <input type="checkbox" name="review-slider-schema" id="review-slider-schema" value="1" <?php echo checked(1, $schema_option, true);?>/>
                  <label class="rs-label checkbox-label" for="review-slider-schema">Generer schema data automatisk</label>
              </div>
          </div>
        </div>


        <div class="rs-group-wrapper">
          <div class="rs-input-group">
              <label class="rs-label">Udtalelseskategorier</label>
              <input type="text" name="review-slider-tags" value="<?php echo esc_attr($tags_option) ?>" class="rs-input" placeholder="Udtalelseskategorier"/>
          </div>
        </div>

    	</div>

		<div class="shortcode-render-side">

			<div class="copy-icon">
				<p class="copied">Kopieret!</p>
				<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>
			</div>

			<div class="shortcode-render-element">[review-slider]</div>

		</div>

    </div>
<?php }

/*Shortcode registrering*/

function enqueue_review_slider_script() {   
    wp_enqueue_script( 'review_slider_script', plugin_dir_url( __FILE__ ) . 'kundeudtalelser.min.js' );
}

add_action('wp_enqueue_scripts', 'enqueue_review_slider_script');

function review_slider_shortcode($atts) {
	
	$apiKey = get_option('review-slider-api-key');


	$options = [];

	foreach($atts as $key => $value){

		$options[] = 'data-' . $key . '="' . $value . '"';
	}


	ob_start();
	?>

	<rp-kundeudtalelser data-apikey="<?php echo $apiKey; ?>" <?php echo implode(" ", $options); ?>></rp-kundeudtalelser>

	<?php
	return ob_get_clean();



}

add_shortcode('review-slider', 'review_slider_shortcode');
