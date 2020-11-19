<?php 
/* Template Name: Home */
get_header();
?>
 
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            
            <?php include( locate_template( "account/_common/header.php", false, false ) ); ?>

        <section class="weekly-offers slider-section">
            <div class="container">
                <?php $weekly_offers = get_field( 'weekly_offers' );?>
                <?php
                    if ($weekly_offers['title']) 
                    {
                        printf('<h2 class="title">%s</h2>', $weekly_offers['title']);
                    }
                    else
                    {
                        printf('<h2 class="title">%s</h2>', get_the_title());
                    }

                    if ($weekly_offers['description']) 
                    {
                        printf('<p class="mb-3">%s</p>', $weekly_offers['description']);
                    }
                ?>

                <?php

                    $weekly_offers_args = array(
                        'posts_per_page' => 12,
                        'post_type' => 'product',
                        'post_status' => 'publish',
                        'ignore_sticky_posts' => 1,
                        'meta_key' => 'total_sales',
                        'orderby' => 'meta_value_num',
                        'order' => 'DESC',
                    );

                    $weekly_offers_loop = new WP_Query( $weekly_offers_args );

                    if ( $weekly_offers_loop->have_posts() ) :
                ?>
                <div class="weekly-offers-slider">
                    <?php while( $weekly_offers_loop->have_posts() ): $weekly_offers_loop->the_post(); 
                        $product = wc_get_product( get_the_ID() );
                    ?>
                    <a href="<?php the_permalink(); ?>" class="item">
                        <div id="product-<?php the_ID(); ?>" <?php post_class('responder-favorite'); ?>>
                            <?php if (has_post_thumbnail()): ?>
                            <div class="media">
                                <?php the_post_thumbnail('product-thumbnail', array('class' => 'img-fluid')); ?>
                            </div>
                            <?php endif; ?>

                            <div class="text p-3">
                                <?php the_title('<h6 class="small">', '</h6>'); ?>

                                <div>
                                <?php echo $product->get_price_html();?>
                                </div>
                                
                            </div>
                        </div>
                    </a>
                    <?php endwhile; ?>
                </div>
                <?php endif; wp_reset_query(); ?>

                <?php if ( $weekly_offers['button'] ): ?>
                <div class="row lr-9">
                    <div class="col-12 text-center">
                        <?php Utils::acfButton($weekly_offers); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </section><!-- /popular-listings -->
        
        <section class="proveedores slider-section">
            <div class="container">
                <h2 class="title">Proveedores</h2>
                <p class="mb-3">Seccion de proveedores</p>
            
                <?php

                    $weekly_offers_args = array(
                        'posts_per_page' => 12,
                        'post_type' => 'product',
                        'post_status' => 'publish',
                        'ignore_sticky_posts' => 1,
                        'meta_key' => 'total_sales',
                        'orderby' => 'meta_value_num',
                        'order' => 'DESC',
                    );

                    $weekly_offers_loop = new WP_Query( $weekly_offers_args );

                    if ( $weekly_offers_loop->have_posts() ) :
                ?>
                <div class="weekly-offers-slider">
                    <?php while( $weekly_offers_loop->have_posts() ): $weekly_offers_loop->the_post(); 
                        $product = wc_get_product( get_the_ID() );
                    ?>
                    <a href="<?php the_permalink(); ?>" class="item">
                        <div id="product-<?php the_ID(); ?>" <?php post_class('responder-favorite'); ?>>
                            <?php if (has_post_thumbnail()): ?>
                            <div class="media">
                                <?php the_post_thumbnail('product-thumbnail', array('class' => 'img-fluid')); ?>
                            </div>
                            <?php endif; ?>

                            <div class="text p-3">
                                <?php the_title('<h6 class="small">', '</h6>'); ?>

                                <div>
                                <?php echo $product->get_price_html();?>
                                </div>
                                
                            </div>
                        </div>
                    </a>
                    <?php endwhile; ?>
                </div>
                <?php endif; wp_reset_query(); ?>

                <?php if ( $weekly_offers['button'] ): ?>
                <div class="row lr-9">
                    <div class="col-12 text-center">
                        <?php Utils::acfButton($weekly_offers); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </section><!-- /popular-listings -->

         <section class="satelites slider-section">
            <div class="container">
                <h2 class="title">Satelites</h2>
                <p class="mb-3">Seccion de satelites</p>
            
                <?php

                    $weekly_offers_args = array(
                        'posts_per_page' => 12,
                        'post_type' => 'product',
                        'post_status' => 'publish',
                        'ignore_sticky_posts' => 1,
                        'meta_key' => 'total_sales',
                        'orderby' => 'meta_value_num',
                        'order' => 'DESC',
                    );

                    $weekly_offers_loop = new WP_Query( $weekly_offers_args );

                    if ( $weekly_offers_loop->have_posts() ) :
                ?>
                <div class="weekly-offers-slider">
                    <?php while( $weekly_offers_loop->have_posts() ): $weekly_offers_loop->the_post(); 
                        $product = wc_get_product( get_the_ID() );
                    ?>
                    <a href="<?php the_permalink(); ?>" class="item">
                        <div id="product-<?php the_ID(); ?>" <?php post_class('responder-favorite'); ?>>
                            <?php if (has_post_thumbnail()): ?>
                            <div class="media">
                                <?php the_post_thumbnail('product-thumbnail', array('class' => 'img-fluid')); ?>
                            </div>
                            <?php endif; ?>

                            <div class="text p-3">
                                <?php the_title('<h6 class="small">', '</h6>'); ?>

                                <div>
                                <?php echo $product->get_price_html();?>
                                </div>
                                
                            </div>
                        </div>
                    </a>
                    <?php endwhile; ?>
                </div>
                <?php endif; wp_reset_query(); ?>

                <?php if ( $weekly_offers['button'] ): ?>
                <div class="row lr-9">
                    <div class="col-12 text-center">
                        <?php Utils::acfButton($weekly_offers); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </section><!-- /popular-listings -->

        <section class="slider-section">
            <div class="container">
                <h2 class="title">Las mejores tiendas te esperan</h2>
                <div class="row">
                    <div class="col-lg-6 col-md-6 mb-5">
                        <a class="card lift border-bottom-lg border-red" href="#!"><div class="card-body text-center">
                                <div class="icon-stack icon-stack-xl bg-red mb-2"><i class="fas fa-car"></i></div>
                                <div class="small text-gray-600">Cars</div>
                            </div></a>
                    </div>
                    <div class="col-lg-6 col-md-6 mb-5">
                        <a class="card lift border-bottom-lg border-orange" href="#!"><div class="card-body text-center">
                                <div class="icon-stack icon-stack-xl bg-orange mb-2"><i class="fas fa-home"></i></div>
                                <div class="small text-gray-600">Housing</div>
                            </div></a>
                    </div>
                </div>
            </div>
        </section>
        <section class="browse-by-categories slider-section mb-4">
            <div class="container">
                <h2 class="title">Por categorias</h2>
                <a href="#">ver mas</a>
            
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-5">
                        <a class="card lift border-bottom-lg border-red" href="#!"><div class="card-body text-center">
                                <div class="icon-stack icon-stack-xl bg-red mb-2"><i class="fas fa-car"></i></div>
                                <div class="small text-gray-600">Cars</div>
                            </div></a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-5">
                        <a class="card lift border-bottom-lg border-orange" href="#!"><div class="card-body text-center">
                                <div class="icon-stack icon-stack-xl bg-orange mb-2"><i class="fas fa-home"></i></div>
                                <div class="small text-gray-600">Housing</div>
                            </div></a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-5">
                        <a class="card lift border-bottom-lg border-yellow" href="#!"><div class="card-body text-center">
                                <div class="icon-stack icon-stack-xl bg-yellow mb-2"><i class="fas fa-gift"></i></div>
                                <div class="small text-gray-600">Free</div>
                            </div></a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-5">
                        <a class="card lift border-bottom-lg border-green" href="#!"><div class="card-body text-center">
                                <div class="icon-stack icon-stack-xl bg-green mb-2"><i class="fas fa-mobile-alt"></i></div>
                                <div class="small text-gray-600">Tech</div>
                            </div></a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                        <a class="card lift border-bottom-lg border-cyan" href="#!"><div class="card-body text-center">
                                <div class="icon-stack icon-stack-xl bg-cyan mb-2"><i class="fas fa-couch"></i></div>
                                <div class="small text-gray-600">Furniture</div>
                            </div></a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                        <a class="card lift border-bottom-lg border-blue" href="#!"><div class="card-body text-center">
                                <div class="icon-stack icon-stack-xl bg-blue mb-2"><i class="fas fa-briefcase"></i></div>
                                <div class="small text-gray-600">Jobs</div>
                            </div></a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                        <a class="card lift border-bottom-lg border-purple" href="#!"><div class="card-body text-center">
                                <div class="icon-stack icon-stack-xl bg-purple mb-2"><i class="fas fa-motorcycle"></i></div>
                                <div class="small text-gray-600">Leisure</div>
                            </div></a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a class="card lift border-bottom-lg border-pink" href="#!"><div class="card-body text-center">
                                <div class="icon-stack icon-stack-xl bg-pink mb-2"><i class="fas fa-shopping-cart"></i></div>
                                <div class="small text-gray-600">Other</div>
                            </div></a>
                    </div>
                </div>
            </div>
        </section><!-- /popular-listings -->

        <section class="browse-by-categories slider-section">
            <div class="container">
                <h2 class="title">Por ubicacion</h2>
                <a href="#">ver mas</a>
            
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-5">
                        <a class="card lift border-bottom-lg border-red" href="#!"><div class="card-body text-center">
                                <div class="icon-stack icon-stack-xl bg-red mb-2"><i class="fas fa-car"></i></div>
                                <div class="small text-gray-600">Cars</div>
                            </div></a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-5">
                        <a class="card lift border-bottom-lg border-orange" href="#!"><div class="card-body text-center">
                                <div class="icon-stack icon-stack-xl bg-orange mb-2"><i class="fas fa-home"></i></div>
                                <div class="small text-gray-600">Housing</div>
                            </div></a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-5">
                        <a class="card lift border-bottom-lg border-yellow" href="#!"><div class="card-body text-center">
                                <div class="icon-stack icon-stack-xl bg-yellow mb-2"><i class="fas fa-gift"></i></div>
                                <div class="small text-gray-600">Free</div>
                            </div></a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-5">
                        <a class="card lift border-bottom-lg border-green" href="#!"><div class="card-body text-center">
                                <div class="icon-stack icon-stack-xl bg-green mb-2"><i class="fas fa-mobile-alt"></i></div>
                                <div class="small text-gray-600">Tech</div>
                            </div></a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                        <a class="card lift border-bottom-lg border-cyan" href="#!"><div class="card-body text-center">
                                <div class="icon-stack icon-stack-xl bg-cyan mb-2"><i class="fas fa-couch"></i></div>
                                <div class="small text-gray-600">Furniture</div>
                            </div></a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                        <a class="card lift border-bottom-lg border-blue" href="#!"><div class="card-body text-center">
                                <div class="icon-stack icon-stack-xl bg-blue mb-2"><i class="fas fa-briefcase"></i></div>
                                <div class="small text-gray-600">Jobs</div>
                            </div></a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                        <a class="card lift border-bottom-lg border-purple" href="#!"><div class="card-body text-center">
                                <div class="icon-stack icon-stack-xl bg-purple mb-2"><i class="fas fa-motorcycle"></i></div>
                                <div class="small text-gray-600">Leisure</div>
                            </div></a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a class="card lift border-bottom-lg border-pink" href="#!"><div class="card-body text-center">
                                <div class="icon-stack icon-stack-xl bg-pink mb-2"><i class="fas fa-shopping-cart"></i></div>
                                <div class="small text-gray-600">Other</div>
                            </div></a>
                    </div>
                </div>
            </div>
        </section><!-- /popular-listings -->

        </main><!-- #main -->
    </div><!-- #primary -->
<?php get_footer(); ?>