<div class="container">
    <div class="row">
        <div class="col-12">
            <!-- Show logged out message if user just logged out -->
            <?php if ( $attributes['logged_out'] ) : ?>
                <p class="login-info">
                    <?php _e( 'You have signed out. Would you like to sign in again?', 'bra-login' ); ?>
                </p>
            <?php endif; ?>
            <!-- If there is any errors, render them to view -->
            <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
                <div class="alert alert-danger">

                        <?php foreach ( $attributes['errors'] as $error ) : ?>
                            <p class="login-error">
                                <?php echo $error; ?>
                            </p>
                        <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if ( $attributes['show_title'] ) : ?>
                <h2><?php _e( 'Sign In', 'bra-login' ); ?></h2>
            <?php endif; ?>

            <div class="login-form-container">
                <form method="post" action="<?php echo wp_login_url(); ?>">
                    <p class="login-username">
                        <label for="user_login"><?php _e( 'Email', 'bra-login' ); ?></label>
                        <input type="text" name="log" id="user_login">
                    </p>
                    <p class="login-password">
                        <label for="user_pass"><?php _e( 'Password', 'bra-login' ); ?></label>
                        <input type="password" name="pwd" id="user_pass">
                    </p>
                    <p class="login-submit">
                        <input type="submit" value="<?php _e( 'Sign In', 'bra-login' ); ?>">
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>