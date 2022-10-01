<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       buildrealapp.co.uk
 * @since      1.0.0
 *
 * @package    Bra_Custom_Register_Login
 * @subpackage Bra_Custom_Register_Login/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bra_Custom_Register_Login
 * @subpackage Bra_Custom_Register_Login/admin
 * @author     Husam Abuhajjaj <buildrealapp.co.uk>
 */
class Bra_Custom_Register_Login_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bra_Custom_Register_Login_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bra_Custom_Register_Login_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bra-custom-register-login-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bra_Custom_Register_Login_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bra_Custom_Register_Login_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bra-custom-register-login-admin.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * Registers the settings fields needed by the plugin.
     */
    public function register_settings_fields() {
        // For simplicity, create settings fields for the two keys used by reCAPTCHA
        register_setting( 'general', 'bra-login-recaptcha-site-key' );
        register_setting( 'general', 'bra-login-recaptcha-secret-key' );

        add_settings_field(
            'bra-login-recaptcha-site-key',
            '<label for="bra-login-recaptcha-site-key">' . __( 'reCAPTCHA site key' , 'bra-login' ) . '</label>',
            array( $this, 'render_recaptcha_site_key_field' ),
            'general'
        );

        add_settings_field(
            'bra-login-recaptcha-secret-key',
            '<label for="bra-login-recaptcha-secret-key">' . __( 'reCAPTCHA secret key' , 'bra-login' ) . '</label>',
            array( $this, 'render_recaptcha_secret_key_field' ),
            'general'
        );
    }

    public function render_recaptcha_site_key_field() {
        $value = get_option( 'bra-login-recaptcha-site-key', '' );
        echo '<input type="text" id="bra-login-recaptcha-site-key" name="bra-login-recaptcha-site-key" value="' . esc_attr( $value ) . '" />';
    }

    public function render_recaptcha_secret_key_field() {
        $value = get_option( 'bra-login-recaptcha-secret-key', '' );
        echo '<input type="text" id="bra-login-recaptcha-secret-key" name="bra-login-recaptcha-secret-key" value="' . esc_attr( $value ) . '" />';
    }

}
