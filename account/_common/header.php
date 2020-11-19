<header class="header">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-md navbar-dark navbar-custom fixed-top">
        <!-- Text Logo - Use this if you don't have a graphic logo -->
        <!-- Image Logo -->
        <a class="navbar-brand logo-image" href="<?php echo site_url(); ?>">
            <img src="<?php echo get_template_directory_uri(); ?>/images/logo.svg" alt="alternative">
        </a>
        
        <form action="<?php echo get_site_url(); ?>" method="get" class="navbar-form" id="search">
            <i class="fa fa-search fa-2x"></i>
            <input type="search" name="s" value="" class="search-input" placeholder="Search Here">
        </form> 
        <!-- Mobile Menu Toggle Button -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-awesome fas fa-bars"></span>
            <span class="navbar-toggler-awesome fas fa-times"></span>
        </button>
        <!-- end of mobile menu toggle button -->

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <!-- <div class="collapse navbar-collapse"> -->
            <ul class="navbar-nav mr-auto navbar-buttons">

                <li class="nav-item user-profile dropdown">
                    <?php if (is_user_logged_in() ): ?>
                        <a href="#" class="user-login dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-user fa-2x"></i>
                        </a>
                        
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>">
                                    <i class="icon-home"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo get_permalink(357);?>">
                                    <i class="icon-cog"></i>
                                    <span>Account Settings</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo get_permalink(46);?>">
                                    <i class="icon-briefcase"></i>
                                    <span>Company Profile</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo get_permalink(52);?>">
                                    <i class="icon-team"></i>
                                    <span>The Team</span>
                                </a>
                            </li>
                            <li class="logout">
                                <a href="<?php echo wp_logout_url(); ?>">
                                    <i class="icon-logout"></i>
                                    <span>Sign Out</span>
                                </a>
                            </li>
                        </ul>
                    <?php else: ?>
                       <a href="<?php echo get_permalink(71) ?>">
                            <i class="fa fa-user fa-2x"></i>
                       </a> 
                    <?php endif; ?>
                </li>

                <li class="store">
                    <a href="<?php echo get_template_link('t_marketplace.php') ?>">
                        <i class="fa fa-shopping-cart fa-2x"></i>
                    </a>
                </li>

                

                <?php //$notification = $user->getMessageFormated();?>
                <li class="notifications">
                    <a href="#collapseNotification" class="user-login" data-toggle="collapse">
                        <span class="num-wrap">
                            <span class="number notification-counter">3<?php //print $notification['total'];?></span>
                            <i class="fa fa-bell fa-2x"></i>
                        </span>
                    </a>

                    <ul id="collapseNotification" class="dropdown-menu collapse overflow-y notifications-tray">
                        <?php //print $notification['messages'];?>
                    </ul>
                </li>

            </ul>
        </div>
        <?php 
            
            // wp_nav_menu( array(
            //     'menu'               => 'Header menu',
            //     'theme_location'     => 'header-menu',
            //     'depth'              => 2,
            //     'container'          => 'false',
            //     'container_id'       => 'bs4navbar',
            //     'container_class'    => 'collapse navbar-collapse',
            //     'menu_class'         => 'navbar-nav ml-auto',
            //     'menu_id'            => '',
            //     'fallback_cb'        => 'bs4navwalker::fallback',
            //     //'walker'             => new bs4navwalker()
            // ));
        ?>
        
        <!-- </div> -->
    </nav>
    <!-- end of navbar -->
    <div class="header_gutter"></div>
    <!-- end of navigation -->

</header>