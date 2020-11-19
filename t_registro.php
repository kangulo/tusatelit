<?php 
/* Template Name: Registro */
get_header();
?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <section class="registro ">
                <div class="container">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <?php $registro_page = get_field( 'registro_page' ); ?>
                            <?php
                                if ($registro_page['title']) 
                                {
                                    printf('<h2 class="title">%s</h2>', $registro_page['title']);
                                }
                                else
                                {
                                    printf('<h2 class="title">%s</h2>', get_the_title());
                                }

                                if ($registro_page['description']) 
                                {
                                    printf('%s', $registro_page['description']);
                                }
                            ?>

                            <?php if ($registro_page['register_form']): ?>
                                <?php echo do_shortcode('[gravityform id="'. $registro_page['register_form']['id']. '" title="false" description="false" ajax="false"]'); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section><!-- /popular-listings -->
        </main><!-- #main -->
    </div><!-- #primary -->
<?php get_footer(); ?>