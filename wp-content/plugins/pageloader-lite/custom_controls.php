<?php
    /* custom notice custom control */
    class Info_Custom_control extends WP_Customize_Control{
        public $type = 'info';
        public function enqueue(){
            wp_enqueue_style( 'custom_controls_css', plugin_dir_url( __FILE__ ) . 'custom_controls.css');
        }
        public function render_content(){
            ?>
            <a href="https://codecanyon.net/item/pageloader-loading-screen-and-progress-bar-for-wordpress/6594364?ref=BonfireThemes" target="_blank">
                <?php echo '<img src="' . plugins_url( 'images/pageloader-lite-gopro.jpg', __FILE__ ) . '" > '; ?>
            </a>
            <?php
        }
    }

?>