<?php 
/*Template Name:Login*/
global $user;
if ( $user->isLoggedIn() ) {
	header( 'Location: ' . get_permalink(13) );
	exit();
} 
get_header('default'); 
?>
<div class="container-fluid login-page">
  <div class="row no-gutter">
    <div class="d-none d-md-flex text-center flex-column justify-content-between col-md-4 col-lg-6 bg-image">
            <div class="btn btn-return text-left"><a href="<?php echo site_url(); ?>"><i class="fa fa-arrow-left" aria-hidden="true"></i></a></div>
            <div class="text">
              <?php $login_options = get_field( 'login_options' ); ?>
              <?php
                  if ($login_options['image']) 
                  {
                    //var_dump($login_options['image']);
                      printf('<img src="%s" alt="%s"/>', $login_options['image']['sizes']['thumbnail'],$login_options['image']['url']);
                  }
                  if ($login_options['title']) 
                  {
                      printf('<h2 class="title">%s</h2>', $login_options['title']);
                  }
                  else
                  {
                      printf('<h2 class="title">%s</h2>', get_the_title());
                  }

                  if ($login_options['description']) 
                  {
                      printf('%s', $login_options['description']);
                  }
              ?>
            </div>
            <div class="footer-note mb-4">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo bloginfo('name'); ?>" class="module-top-logo">
              <?php echo bloginfo('name'); ?>
            </a>
            </div>
    </div>
    <div class="col-md-8 col-lg-6">
      <div class="login-form d-flex align-items-center py-5">
        <div class="container">
          <div class="row">
            <div class="col-md-9 col-lg-8 mx-auto">
              <?php 
              if ($login_options['image']) 
                {
                  //var_dump($login_options['image']);
                    printf('<div class="d-md-none text-center"><a href="%s"><img src="%s" alt="%s"/></a></div>',esc_url( home_url( '/' ) ), $login_options['image']['sizes']['thumbnail'],$login_options['image']['url']);
                }
              ?>
              <h3 class="login-heading mb-4">Bienvenido!</h3>
              <form class="woocommerce-form woocommerce-form-login login" method="post">
                <?php do_action( 'woocommerce_login_form_start' ); ?>
                <div class="form-label-group">
                  <input type="email" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" class="form-control" placeholder="Email address" required autofocus>
                  <label for="username">Email address</label>
                </div>

                <div class="form-label-group">
                  <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                  <label for="password">Password</label>
                </div>
                <?php do_action( 'woocommerce_login_form' ); ?>
                <div class="custom-control custom-checkbox mb-3">
                  <input type="checkbox" class="custom-control-input" name="rememberme" id="rememberme" value="forever">
                  <label class="custom-control-label" for="rememberme">Remember password</label>
                </div>
                <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                <button name="login" id="login" class="btn btn-lg btn-primary btn-block btn-login text-uppercase font-weight-bold mb-2" type="submit">Ingresar</button>
                <a class="btn btn-lg btn-secondary btn-block btn-registro text-uppercase font-weight-bold mb-2" href="<?php echo get_permalink(41); ?>">Crear una Cuenta</a>
                <?php do_action( 'woocommerce_login_form_end' ); ?>
                <div class="text-center">
                  <a class="small text-dark" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot password?', 'woocommerce' ); ?></a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php do_action( 'woocommerce_after_customer_login_form' ); ?>

<?php get_footer('default'); ?>