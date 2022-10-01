<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       buildrealapp.co.uk
 * @since      1.0.0
 *
 * @package    Bra_Custom_Register_Login
 * @subpackage Bra_Custom_Register_Login/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Bra_Custom_Register_Login
 * @subpackage Bra_Custom_Register_Login/public
 * @author     Husam Abuhajjaj <buildrealapp.co.uk>
 */
class Bra_Custom_Register_Login_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bra-custom-register-login-public.css', array(), $this->version, 'all' );

        wp_enqueue_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bra-custom-register-login-public.js', array( 'jquery' ), $this->version, false );
	}

    /**
     * A shortcode for rendering the login form.
     *
     * @param  array   $attributes  Shortcode attributes.
     * @param  string  $content     The text content for shortcode. Not used.
     *
     * @return string  The shortcode output
     */
    public function render_login_form( $attributes, $content = null ): string
    {
        // Parse shortcode attributes
        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );

        $attributes['registered'] = isset( $_REQUEST['registered'] );

        if ( is_user_logged_in() ) {
            return __( 'You are already signed in.', 'bra-login' );
        }

        // If there is redirect_to, we need to pass it to wordpress login functionality
        $attributes['redirect'] = '';
        if ( isset( $_REQUEST['redirect_to'] ) ) {
            $attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'], $attributes['redirect'] );
        }

        // Error messages
        $errors = array();
        if ( isset( $_REQUEST['login'] ) ) {
            $error_codes = explode( ',', $_REQUEST['login'] );

            foreach ( $error_codes as $code ) {
                $errors []= $this->get_error_message( $code );
            }
        }
        $attributes['errors'] = $errors;
        // Check if user just logged out
        $attributes['logged_out'] = isset( $_REQUEST['logged_out'] ) && $_REQUEST['logged_out'] == true;
        // Render the login form using an external view
        return $this->get_view( 'login_form', $attributes );
    }

    /**
     * Renders the contents of the given template to a string and returns it.
     *
     * @param string $template_name The name of the template to render (without .php)
     * @param array  $attributes    The PHP variables for the template
     *
     * @return string               The contents of the template.
     */
    private function get_view( $template_name, $attributes = null ) {
        if ( ! $attributes ) {
            $attributes = array();
        }

        ob_start();

        require( 'partials/' . $template_name . '.php');

        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    /**
     * Redirect the user to the our custom login page instead of wp-login.php.
     */
    function redirect_to_custom_login() {
        if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
            $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : null;

            if ( is_user_logged_in() ) {
                $this->redirect_logged_in_user( $redirect_to );
                exit;
            }

            // The rest are redirected to the login page
            $login_url = home_url( 'member-login' );
            if ( ! empty( $redirect_to ) ) {
                $login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );
            }

            wp_redirect( $login_url );
            exit;
        }
    }

    /**
     * Redirects the user to the correct page depending on whether he / she
     * is an admin or not.
     *
     * @param string $redirect_to   An optional redirect_to URL for admin users
     */
    private function redirect_logged_in_user( $redirect_to = null ) {
        $user = wp_get_current_user();
        if ( user_can( $user, 'manage_options' ) ) {
            if ( $redirect_to ) {
                wp_safe_redirect( $redirect_to );
            } else {
                wp_redirect( admin_url() );
            }
        } else {
            wp_redirect( home_url( 'member-account' ) );
        }
    }

    /**
     * If authentication has error return user to our custom login page
     *
     * @param Wp_User|Wp_Error  $user       User object, or the errors that have occurred during login.
     * @param string            $username   The user name used to log in.
     * @param string            $password   The password used to log in.
     *
     * @return Wp_User|Wp_Error The logged in user, or error information if there were errors.
     */
    function redirect_at_authenticate( $user, $username, $password ) {
        // Check if the earlier authenticate filter
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            if ( is_wp_error( $user ) ) {
                $error_codes = join( ',', $user->get_error_codes() );

                $login_url = home_url( 'member-login' );
                $login_url = add_query_arg( 'login', $error_codes, $login_url );

                wp_redirect( $login_url );
                exit;
            }
        }

        return $user;
    }

    /**
     * Finds and returns a matching error message for the given error code.
     *
     * @param string $error_code    The error code to look up.
     *
     * @return string               An error message.
     */
    private function get_error_message( $error_code ) {
        switch ( $error_code ) {
            case 'empty_username':
                return __( 'You do have an email address, right?', 'bra-login' );

            case 'empty_password':
                return __( 'You need to enter a password to login.', 'bra-login' );

            case 'invalid_email':
                return __(
                    "We don't have any users with that email address. Maybe you used a different one when signing up?",
                    'bra-login'
                );

            case 'email':
                return __( 'The email address you entered is not valid.', 'bra-login' );

            case 'email_exists':
                return __( 'An account exists with this email address.', 'bra-login' );

            case 'closed':
                return __( 'Registering new users is currently not allowed.', 'bra-login' );

            case 'incorrect_password':
                $err = __(
                    "The password you entered wasn't quite right. <a href='%s'>Did you forget your password</a>?",
                    'bra-login'
                );
                return sprintf( $err, wp_lostpassword_url() );
            case 'captcha':
                return __( 'The Google reCAPTCHA check failed. Are you a robot?', 'bra-login' );
            default:
                break;
        }

        return __( 'An unknown error occurred. Please try again later.', 'bra-login' );
    }

    /**
     * Redirect to custom login page after the user has been logged out.
     */
    public function redirect_after_logout() {
        $redirect_url = home_url( 'member-login?logged_out=true' );
        wp_safe_redirect( $redirect_url );
        exit;
    }

    /**
     * Returns the URL to which the user should be redirected after the (successful) login.
     *
     * @param string           $redirect_to           The redirect destination URL.
     * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
     * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
     *
     * @return string Redirect URL
     */
    public function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {
        $redirect_url = home_url();

        if ( ! isset( $user->ID ) ) {
            return $redirect_url;
        }

        if ( user_can( $user, 'manage_options' ) ) {
            // Use the redirect_to parameter if one is set, otherwise redirect to admin dashboard.
            if ( $requested_redirect_to == '' ) {
                $redirect_url = admin_url();
            } else {
                $redirect_url = $requested_redirect_to;
            }
        } else {
            // Non admin users always redirect tp member-account page
            $redirect_url = home_url( 'member-account' );
        }

        return wp_validate_redirect( $redirect_url, home_url() );
    }

    /**
     * A shortcode for rendering the new user registration form.
     *
     * @param  array   $attributes  Shortcode attributes.
     * @param  string  $content     The text content for shortcode. Not used.
     *
     * @return string  The shortcode output
     */
    public function render_register_form( $attributes, $content = null ) {
        // Parse shortcode attributes
        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );

        if ( is_user_logged_in() ) {
            return __( 'You are already signed in.', 'bra-login' );
        } elseif ( ! get_option( 'users_can_register' ) ) {
            return __( 'Registering new users is currently not allowed.', 'bra-login' );
        } else {
            // Retrieve possible errors from request parameters
            $attributes['errors'] = array();
            if ( isset( $_REQUEST['register-errors'] ) ) {
                $error_codes = explode( ',', $_REQUEST['register-errors'] );

                foreach ( $error_codes as $error_code ) {
                    $attributes['errors'] []= $this->get_error_message( $error_code );
                }
            }
            $attributes['recaptcha_site_key'] = get_option( 'bra-login-recaptcha-site-key', null );
            return $this->get_view( 'register_form', $attributes );
        }
    }

    /** * Redirects the user to the custom registration page instead * of wp-login.php?action=register. */
    public function redirect_to_custom_register()
    {
        if ('GET' == $_SERVER['REQUEST_METHOD']) {
            if (is_user_logged_in()) {
                $this->redirect_logged_in_user();
            } else {
                wp_redirect(home_url('member-register'));
            }
            exit;
        }
    }

    /**
     * Validates and then completes the new user signup process if all went well.
     *
     * @param string $email         The new user's email address
     * @param string $first_name    The new user's first name
     * @param string $last_name     The new user's last name
     *
     * @return int|WP_Error         The id of the user that was created, or error if failed.
     */
    private function register_user( $email, $first_name, $last_name ) {
        $errors = new WP_Error();

        // Email address is used as both username and email. It is also the only
        if ( ! is_email( $email ) ) {
            $errors->add( 'email', $this->get_error_message( 'email' ) );
            return $errors;
        }

        if ( username_exists( $email ) || email_exists( $email ) ) {
            $errors->add( 'email_exists', $this->get_error_message( 'email_exists') );
            return $errors;
        }

        // Generate the password so that the subscriber will have to check email...
        $password = wp_generate_password( 12, false );

        $user_data = array(
            'user_login'    => $email,
            'user_email'    => $email,
            'user_pass'     => $password,
            'first_name'    => $first_name,
            'last_name'     => $last_name,
            'nickname'      => $first_name,
        );

        $user_id = wp_insert_user( $user_data );
        wp_new_user_notification( $user_id, $password );

        return $user_id;
    }

    /**
     * An action function used to include the reCAPTCHA JavaScript file
     * at the end of the page.
     */
    public function add_captcha_js_to_footer() {
        echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
    }

    /**
     * Checks that the reCAPTCHA parameter sent with the registration
     * request is valid.
     *
     * @return bool True if the CAPTCHA is OK, otherwise false.
     */
    private function verify_recaptcha() {
        // This field is set by the recaptcha widget if check is successful
        if ( isset ( $_POST['g-recaptcha-response'] ) ) {
            $captcha_response = $_POST['g-recaptcha-response'];
        } else {
            return false;
        }

        // Verify the captcha response from Google
        $response = wp_remote_post(
            'https://www.google.com/recaptcha/api/siteverify',
            array(
                'body' => array(
                    'secret' => get_option( 'bra-login-recaptcha-secret-key' ),
                    'response' => $captcha_response
                )
            )
        );

        $success = false;
        if ( $response && is_array( $response ) ) {
            $decoded_response = json_decode( $response['body'] );
            $success = $decoded_response->success;
        }

        return $success;
    }

    /**
     * Handles the registration of a new user.
     *
     * Used through the action hook "login_form_register" activated on wp-login.php
     * when accessed through the registration action.
     */
    public function do_register_user() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
            $redirect_url = home_url( 'member-register' );

            if ( ! get_option( 'users_can_register' ) ) {
                // Registration closed, display error
                $redirect_url = add_query_arg( 'register-errors', 'closed', $redirect_url );
            } elseif ( ! $this->verify_recaptcha() ) {
                // Recaptcha check failed, display error
                $redirect_url = add_query_arg( 'register-errors', 'captcha', $redirect_url );
            } else {
                $email = $_POST['email'];
                $first_name = sanitize_text_field( $_POST['first_name'] );
                $last_name = sanitize_text_field( $_POST['last_name'] );

                $result = $this->register_user( $email, $first_name, $last_name );

                if ( is_wp_error( $result ) ) {
                    // Parse errors into a string and append as parameter to redirect
                    $errors = join( ',', $result->get_error_codes() );
                    $redirect_url = add_query_arg( 'register-errors', $errors, $redirect_url );
                } else {
                    // Success, redirect to login page.
                    $redirect_url = home_url( 'member-login' );
                    $redirect_url = add_query_arg( 'registered', $email, $redirect_url );
                }
            }

            wp_redirect( $redirect_url );
            exit;
        }
    }

}
