<?php

/**
 * Fired during plugin activation
 *
 * @link       buildrealapp.co.uk
 * @since      1.0.0
 *
 * @package    Bra_Custom_Register_Login
 * @subpackage Bra_Custom_Register_Login/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Bra_Custom_Register_Login
 * @subpackage Bra_Custom_Register_Login/includes
 * @author     Husam Abuhajjaj <buildrealapp.co.uk>
 */
class Bra_Custom_Register_Login_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.Í
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        self::create_pages();
	}

    /**
     * Create login, register, forget-password and reset password pages
     */
    private static function create_pages() {
        // Pages information
        $page_definitions = array(
            'member-login' => array(
                'title' => __( 'Sign In', 'bra-login' ),
                'content' => '[bra-login-form]'
            ),
            'member-account' => array(
                'title' => __( 'Your Profile', 'bra-login' ),
                'content' => '[my-profile]'
            ),
            'member-register' => array(
                'title' => __( 'Register', 'bra-login' ),
                'content' => '[register-form]'
            ),
            'member-password-lost' => array(
                'title' => __( 'Forgot Your Password?', 'bra-login' ),
                'content' => '[custom-password-lost-form]'
            ),
            'member-password-reset' => array(
                'title' => __( 'Pick a New Password', 'bra-login' ),
                'content' => '[custom-password-reset-form]'
            )
        );

        foreach ( $page_definitions as $slug => $page ) {
            // Create page only if it is not exist
            $query = new WP_Query( 'pagename=' . $slug );
            if ( ! $query->have_posts() ) {
                // Add page information from array defined aboves
                wp_insert_post(
                    array(
                        'post_content'   => $page['content'],
                        'post_name'      => $slug,
                        'post_title'     => $page['title'],
                        'post_status'    => 'publish',
                        'post_type'      => 'page',
                        'ping_status'    => 'closed',
                        'comment_status' => 'closed',
                    )
                );
            }
        }
    }


}
