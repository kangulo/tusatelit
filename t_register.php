<?php 
/*Template Name: Register*/
global $user;
if ( $user->isLoggedIn() ) {
	header( 'Location: ' . get_permalink(13) );
	exit();
} 
get_header('default'); 
?>
<div class="container-fluid register-page">
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
      <div class="register-form-wrapper d-flex align-items-center py-5">
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
              <h3 class="login-heading mb-4">Formulario de Registro</h3>
              <div class="register-form">
              <?php echo do_shortcode('[gravityform id="1" title="false" description="false" ajax="false"]'); ?>
                
              </div>
              <div class="text-center mt-3">
                  <a class="small text-dark" href="<?php echo get_permalink(71) ?>"><?php esc_html_e( 'Already have an account', 'tusatelit' ); ?></a>
                </div> 
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php do_action( 'woocommerce_after_customer_login_form' ); ?>

<?php get_footer('default'); ?>