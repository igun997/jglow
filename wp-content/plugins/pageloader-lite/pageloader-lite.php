<?php
/*
Plugin Name: PageLoader Lite
Description: Loading Screen for WordPress. Customize under Appearance → Customize → PageLoader Lite
Version: 1.1
Author: Bonfire Themes
Author URI: http://bonfirethemes.com/
License: GPL2
*/

    //
	// WORDPRESS LIVE CUSTOMIZER
	//
    function pageloader_theme_customizer( $wp_customize ) {

        //
        // ADD "PAGELOADER" PANEL TO LIVE CUSTOMIZER 
        //
        $wp_customize->add_panel('pageloader_panel', array('title' => __('PageLoader Lite', 'pageloader'),'priority' => 10,));
        
        //
        // ADD "Loading Icon/Image" SECTION TO "PAGELOADER" PANEL 
        //
        $wp_customize->add_section('pageloader_main_section',array('title' => __( 'Loading Icon/Image', 'pageloader' ),'panel' => 'pageloader_panel','priority' => 10));

        /* icon selection */
        $wp_customize->add_setting('pageloader_icon_selection',array(
            'default' => 'icon1',
        ));
        $wp_customize->add_control('pageloader_icon_selection',array(
            'type' => 'select',
            'label' => 'Icon style',
            'section' => 'pageloader_main_section',
            'choices' => array(
                'icon1' => 'Icon 1',
                'icon2' => 'Icon 2',
                'icon3' => 'Icon 3',
                'icon4' => 'Icon 4',
                'icon5' => 'Icon 5',
                'icon6' => 'Icon 6',
                'icon7' => 'Icon 7',
                'icon8' => 'Icon 8',
                'icon9' => 'Icon 9',
                'icon10' => 'Icon 10',
        ),
        ));
        
        /* icon size */
        $wp_customize->add_setting('pageloader_icon_size',array(
            'default' => '100',
        ));
        $wp_customize->add_control('pageloader_icon_size',array(
            'type' => 'select',
            'label' => 'Icon size',
            'section' => 'pageloader_main_section',
            'choices' => array(
                '25' => '25%',
                '50' => '50%',
                '75' => '75%',
                '100' => '100%',
        ),
        ));
        
        /* icon color */
        $wp_customize->add_setting( 'bonfire_pageloader_icon_color', array( 'sanitize_callback' => 'sanitize_hex_color' ));
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bonfire_pageloader_icon_color',array(
            'label' => 'Icon color', 'settings' => 'bonfire_pageloader_icon_color', 'section' => 'pageloader_main_section' )
        ));
        
        /* custom loading image */
        $wp_customize->add_setting('pageloader_custom_loading_image');
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize,'pageloader_custom_loading_image',
            array(
                'label' => 'Custom loading image',
                'description' => 'Overrides icon selection above.',
                'section' => 'pageloader_main_section',
                'settings' => 'pageloader_custom_loading_image'
        )
        ));

        /* loading image size */
        $wp_customize->add_setting('pageloader_custom_loading_image_size',array('sanitize_callback' => 'sanitize_pageloader_custom_loading_image_size',));
        function sanitize_pageloader_custom_loading_image_size($input) {return wp_kses_post(force_balance_tags($input));}
        $wp_customize->add_control('pageloader_custom_loading_image_size',array(
            'type' => 'text',
            'label' => 'Image size (in pixels)',
            'description' => 'By default, the default size of the image is used (but gets sized down gradually depending on screen size to prevent it from going beyond screen bounds).',
            'section' => 'pageloader_main_section',
        ));
        
        /* animation speed */
        $wp_customize->add_setting('pageloader_animation_speed',array(
            'default' => 'medium',
        ));
        $wp_customize->add_control('pageloader_animation_speed',array(
            'type' => 'select',
            'label' => 'Animation speed',
            'section' => 'pageloader_main_section',
            'choices' => array(
                'slow' => 'Slow',
                'medium' => 'Medium',
                'fast' => 'Fast',
                'none' => 'Disable animation',
        ),
        ));
        
        /* fade animation */
        $wp_customize->add_setting('bonfire_pageloader_fade_animation',array('sanitize_callback' => 'sanitize_bonfire_pageloader_fade_animation',));
        function sanitize_bonfire_pageloader_fade_animation( $input ) { if ( $input == 1 ) { return 1; } else { return ''; } }
        $wp_customize->add_control('bonfire_pageloader_fade_animation',array('type' => 'checkbox','label' => 'Fade animation','description' => 'Animation speed setting applies. If unchecked, loading icon/image will rotate instead.','section' => 'pageloader_main_section',));
        
        //
        // ADD "Loading Text" SECTION TO "PAGELOADER" PANEL 
        //
        $wp_customize->add_section('pageloader_text_dots_section',array('title' => __( 'Loading Text', 'pageloader' ),'panel' => 'pageloader_panel','priority' => 10));
        
        /* loading text */
        $wp_customize->add_setting('bonfire_pageloader_custom_loading_text',array('sanitize_callback' => 'sanitize_bonfire_pageloader_custom_loading_text',));
        function sanitize_bonfire_pageloader_custom_loading_text($input) {return wp_kses_post(force_balance_tags($input));}
        $wp_customize->add_control('bonfire_pageloader_custom_loading_text',array(
            'type' => 'text',
            'label' => 'Loading text',
            'description' => 'A short sentence to display under the loading icon. If empty, no text will be shown.',
            'section' => 'pageloader_text_dots_section',
        ));
        
        /* loading text font size */
        $wp_customize->add_setting('bonfire_pageloader_loading_text_font_size',array('sanitize_callback' => 'sanitize_bonfire_pageloader_loading_text_font_size',));
        function sanitize_bonfire_pageloader_loading_text_font_size($input) {return wp_kses_post(force_balance_tags($input));}
        $wp_customize->add_control('bonfire_pageloader_loading_text_font_size',array(
            'type' => 'text',
            'label' => 'Loading text font size (in pixels)',
            'description' => 'Font size for the loading text. If empty, will default to 14',
            'section' => 'pageloader_text_dots_section',
        ));
        
        /* text color */
        $wp_customize->add_setting( 'bonfire_pageloader_text_color', array( 'sanitize_callback' => 'sanitize_hex_color' ));
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bonfire_pageloader_text_color',array(
            'label' => 'Loading text', 'settings' => 'bonfire_pageloader_text_color', 'section' => 'pageloader_text_dots_section' )
        ));
        
        //
        // ADD "Background" SECTION TO "PAGELOADER" PANEL 
        //
        $wp_customize->add_section('pageloader_background_section',array('title' => __( 'Background', 'pageloader' ),'panel' => 'pageloader_panel','priority' => 10));
        
        /* background animation */
        $wp_customize->add_setting('pageloader_background_animation',array(
            'default' => 'fade',
        ));
        $wp_customize->add_control('pageloader_background_animation',array(
            'type' => 'select',
            'label' => 'Background animation',
            'section' => 'pageloader_background_section',
            'choices' => array(
                'fade' => 'Fade away',
                'top' => 'Slide Top',
                'left' => 'Slide Left',
                'right' => 'Slide Right',
                'bottom' => 'Slide Bottom',
        ),
        ));
        
        /* background color */
        $wp_customize->add_setting( 'bonfire_pageloader_background_color', array( 'sanitize_callback' => 'sanitize_hex_color' ));
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bonfire_pageloader_background_color',array(
            'label' => 'Background color', 'settings' => 'bonfire_pageloader_background_color', 'section' => 'pageloader_background_section' )
        ));
        
        /* background opacity */
        $wp_customize->add_setting('bonfire_pageloader_background_opacity',array('sanitize_callback' => 'sanitize_bonfire_pageloader_background_opacity',));
        function sanitize_bonfire_pageloader_background_opacity($input) {return wp_kses_post(force_balance_tags($input));}
        $wp_customize->add_control('bonfire_pageloader_background_opacity',array(
            'type' => 'text',
            'label' => 'Background opacity (from 0-1)',
            'description' => 'Example: 0.25 or 0.5. If empty, defaults to 1.',
            'section' => 'pageloader_background_section',
        ));

        //
        // ADD "Misc." SECTION TO "PAGELOADER" PANEL 
        //
        $wp_customize->add_section('pageloader_misc_section',array('title' => __( 'Misc.', 'pageloader' ),'panel' => 'pageloader_panel','priority' => 10));
        
        /* show on front page only */
        $wp_customize->add_setting('bonfire_pageloader_front_page_only',array('sanitize_callback' => 'sanitize_bonfire_pageloader_front_page_only',));
        function sanitize_bonfire_pageloader_front_page_only( $input ) { if ( $input == 1 ) { return 1; } else { return ''; } }
        $wp_customize->add_control('bonfire_pageloader_front_page_only',array('type' => 'checkbox','label' => 'Show on front page only','description' => 'The loading screen will be shown on the front page only.','section' => 'pageloader_misc_section',));
        
        /* custom delay */
        $wp_customize->add_setting('bonfire_pageloader_custom_delay',array('sanitize_callback' => 'sanitize_bonfire_pageloader_custom_delay',));
        function sanitize_bonfire_pageloader_custom_delay($input) {return wp_kses_post(force_balance_tags($input));}
        $wp_customize->add_control('bonfire_pageloader_custom_delay',array(
            'type' => 'text',
            'label' => 'Custom delay (in milliseconds)',
            'description' => 'Upon load completion, the loading screen will remain visible for the specified amount of time. Example: 500 or 1500 (0.5 and 1.5 seconds respectively).',
            'section' => 'pageloader_misc_section',
        ));
        
        //
        // ADD "Go Pro!" SECTION TO "PAGELOADER" PANEL 
        //
        $wp_customize->add_section('pageloader_gopro_section',array('title' => __( 'View premium version', 'pageloader' ), 'panel' => 'pageloader_panel','priority' => 10));

        require_once 'custom_controls.php';
        /* custom notice custom contol */
        $wp_customize->add_setting('custom_info', array(
            'default'           => '',
            'sanitize_callback' => 'pageloader_text_sanitization',
        ));
        $wp_customize->add_control(new Info_Custom_control($wp_customize, 'custom_info', array(
            'settings'		=> 'custom_info',
            'section'  		=> 'pageloader_gopro_section',
        )));

    }
    add_action('customize_register', 'pageloader_theme_customizer');

	//
	// Insert PageLoader into the header
	//
	function bonfire_pageloader_header() {

        if( get_theme_mod('bonfire_pageloader_front_page_only') != '') {
            if( is_front_page() || is_home() ) {
                include( plugin_dir_path( __FILE__ ) . 'include.php');
            }
        } else {
            // BEGIN GET POST ID (FOR PER-POST/PAGE PageLoader HIDE)
            global $post;
            $bonfire_pageloader_display = get_post_meta($post->ID, 'bonfire_pageloader_display', true);
            //END GET POST ID (FOR PER-POST/PAGE PageLoader HIDE)

            include( plugin_dir_path( __FILE__ ) . 'include.php');
        }
	
	}
	add_action('wp_head','bonfire_pageloader_header');

    //
	// ENQUEUE Google WebFonts
	//
    function pageloader_fonts_url() {
        $font_url = '';

        if ( 'off' !== _x( 'on', 'Google font: on or off', 'pageloader' ) ) {
            $font_url = add_query_arg( 'family', urlencode( 'Roboto:300' ), "//fonts.googleapis.com/css" );
        }
        return $font_url;
    }
    function pageloader_scripts() {
        if (!isset($_SESSION['pageloader_session_gfont'])) { $_SESSION['pageloader_session_gfont'] = '0';
            wp_enqueue_style( 'pageloader-fonts', pageloader_fonts_url(), array(), '1.0.0' );
        } $_SESSION['pageloader_session_gfont']++;
    }
    add_action( 'wp_enqueue_scripts', 'pageloader_scripts' );


	//
	// ENQUEUE pageloader-lite.css (only when loading screen visible)
	//   
	function bonfire_pageloader_css() {
        if (!isset($_SESSION['pageloader_session_css'])) { $_SESSION['pageloader_session_css'] = '0';
            if( get_theme_mod('bonfire_pageloader_front_page_only') != '') {
                if( is_front_page() || is_home() ) {
                    wp_register_style( 'bonfire-pageloader-css', plugins_url( '/pageloader-lite.css', __FILE__ ) . '', array(), '1', 'all' );
                    wp_enqueue_style( 'bonfire-pageloader-css' );
                }
            } else {
                // BEGIN GET POST ID (FOR PER-POST/PAGE PageLoader HIDE)
                global $post;
                $bonfire_pageloader_display = get_post_meta($post->ID, 'bonfire_pageloader_display', true);
                //END GET POST ID (FOR PER-POST/PAGE PageLoader HIDE)
                
                wp_register_style( 'bonfire-pageloader-css', plugins_url( '/pageloader-lite.css', __FILE__ ) . '', array(), '1', 'all' );
                wp_enqueue_style( 'bonfire-pageloader-css' );
            }
        } $_SESSION['pageloader_session_css']++;
	}
	add_action( 'wp_enqueue_scripts', 'bonfire_pageloader_css' );
    
	//
	// Insert customizer options into the header
	//
	function bonfire_pageloader_header_customize() {
	?>
    
    <?php if (!isset($_SESSION['pageloader_session_customizer'])) { $_SESSION['pageloader_session_customizer'] = '0'; ?>

		<style>
		/* background and icon color + background opacity */
		.bonfire-pageloader-background { background-color:<?php echo get_theme_mod('bonfire_pageloader_background_color'); ?>; opacity:<?php echo get_theme_mod('bonfire_pageloader_background_opacity'); ?>; }
		.bonfire-pageloader-icon svg { fill:<?php echo get_theme_mod('bonfire_pageloader_icon_color'); ?>; }
		.bonfire-pageloader-sentence {
            font-size:<?php echo get_theme_mod('bonfire_pageloader_loading_text_font_size'); ?>px;
            color:<?php echo get_theme_mod('bonfire_pageloader_text_color'); ?>;
        }
		</style>
		<!-- END CUSTOM COLORS (WP THEME CUSTOMIZER) -->
	
    <?php } $_SESSION['pageloader_session_customizer']++; ?>
    
	<?php
	}
	add_action('wp_head','bonfire_pageloader_header_customize');
    
    //
	// Add 'Settings' link to plugin page
	//
    add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links' );
    function add_action_links ( $links ) {
        $mylinks = array(
        '<a href="' . admin_url( 'customize.php?autofocus[panel]=pageloader_panel' ) . '">Settings</a>',
        '<a href="https://codecanyon.net/item/pageloader-loading-screen-and-progress-bar-for-wordpress/6594364?ref=BonfireThemes" target="_blank" style="color:red;">Go Pro!</a>',
        );
    return array_merge( $links, $mylinks );
    }

	///////////////////////////////////////
	// Yes/No drop-down selector on 'write post/page' pages
	///////////////////////////////////////
	add_action( 'add_meta_boxes', 'bonfire_pageloader_custom_class_add' );
	function bonfire_pageloader_custom_class_add() {
		add_meta_box( 'bonfire-pageloader-meta-box-id', __( 'Show PageLoader loading screen on this post?', 'bonfire' ), 'bonfire_pageloader_custom_class', 'post', 'normal', 'high' );
		add_meta_box( 'bonfire-pageloader-meta-box-id', __( 'Show PageLoader loading screen on this page?', 'bonfire' ), 'bonfire_pageloader_custom_class', 'page', 'normal', 'high' );
	}

	function bonfire_pageloader_custom_class( $post ) {
		$values = get_post_custom( $post->ID );
		$bonfire_pageloader_selected_class = isset( $values['bonfire_pageloader_display'] ) ? sanitize_text_field( $values['bonfire_pageloader_display'][0] ) : '';
		?>		
		<p>
			<select name="bonfire_pageloader_display">
				<option value="" <?php selected( $bonfire_pageloader_selected_class, 'yes' ); ?>>Yes</option>
				<!-- You can add and remove options starting from here -->				
				<option value="pageloader-hide" <?php selected( $bonfire_pageloader_selected_class, 'pageloader-hide' ); ?>>No</option>
				<!-- Options end here -->	
			</select>		
		</p>
		<?php	
	}

	add_action( 'save_post', 'bonfire_pageloader_custom_class_save' );
	function bonfire_pageloader_custom_class_save( $post_id ) {
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if( !current_user_can( 'edit_post' ) ) {
			return;
		}
			
		if( isset( $_POST['bonfire_pageloader_display'] ) ) {
			update_post_meta( $post_id, 'bonfire_pageloader_display', sanitize_text_field( $_POST['bonfire_pageloader_display'] ) );
		}
	}

?>