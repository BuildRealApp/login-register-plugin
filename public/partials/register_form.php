<div id="register-form" class="widecolumn">
    <?php if ( $attributes['show_title'] ) : ?>
        <h3><?php _e( 'Register', 'bra-login' ); ?></h3>
    <?php endif; ?>
    <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
        <?php foreach ( $attributes['errors'] as $error ) : ?>
            <p>
                <?php echo $error; ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>

    <form id="signupform" action="<?php echo wp_registration_url(); ?>" method="post">
        <p class="form-row">
            <label for="email"><?php _e( 'Email', 'bra-login' ); ?> <strong>*</strong></label>
            <input type="text" name="email" id="email">
        </p>

        <p class="form-row">
            <label for="email"><?php _e( 'Password', 'bra-login' ); ?> <strong>*</strong></label>
            <input type="text" name="user_pass" id="user_pass">
        </p>

        <p class="form-row">
            <label for="email"><?php _e( 'Confirm Password', 'bra-login' ); ?> <strong>*</strong></label>
            <input type="text" name="user_pass_confirm" id="user_pass_confirm">
        </p>

        <?php if ( $attributes['recaptcha_site_key'] ) : ?>
            <div class="recaptcha-container">
                <div class="g-recaptcha" data-sitekey="<?php echo $attributes['recaptcha_site_key']; ?>"></div>
            </div>
        <?php endif; ?>

        <p class="signup-submit">
            <input type="submit" name="submit" class="register-button"
                   value="<?php _e( 'Register', 'bra-login' ); ?>"/>
        </p>
    </form>
</div>