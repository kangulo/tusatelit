<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">

    <!-- SEO Meta Tags -->
    <meta name="description" content="Tusatelite te conecta con operarios activos, microempresas llamadas satélites, comerciantes y proveedores de materia prima. Encuentra todo lo relacionado para tu negocio en un solo sitio, de manera fácil y rápida e incrementa tus ganancias.">
    <meta name="author" content="KayakInnovations">

    <!-- OG Meta Tags to improve the way the post looks when you share the page on LinkedIn, Facebook, Google+ -->
    <meta property="og:site_name" content="" />
    <!-- website name -->
    <meta property="og:site" content="" />
    <!-- website link -->
    <meta property="og:title" content="" />
    <!-- title shown in the actual shared post -->
    <meta property="og:description" content="" />
    <!-- description shown in the actual shared post -->
    <meta property="og:image" content="" />
    <!-- image link, make sure it's jpg -->
    <meta property="og:url" content="" />
    <!-- where do you want your post to link to -->
    <meta property="og:type" content="article" />

    <!-- Website Title -->
    <title><?php if (is_front_page()) {
                echo ' Home ';
                echo ' | ';
                echo bloginfo('name');
            } else {
                wp_title('');
                echo ' | ';
                bloginfo('name');
            } ?></title>
    <?php $favicon = get_field('favicon', 'options');
    if ($favicon && isset($favicon)) : ?>
    <link rel="icon" type="image/png" href="<?php echo $favicon['url']; ?>" sizes="32x32">
    <?php else : ?>
    <link rel="icon" type="image/png" href="<?= get_theme_file_uri(); ?>/images/icon/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="<?= get_theme_file_uri(); ?>/images/icon/favicon-32x32.png" sizes="32x32">
    <link rel="icon" sizes="192x192" href="<?= get_theme_file_uri(); ?>/images/icon/touch-icon-192x192.png">
    <link rel="apple-touch-icon-precomposed" sizes="180x180" href="<?= get_theme_file_uri(); ?>/images/icon/apple-touch-icon-180x180-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?= get_theme_file_uri(); ?>/images/icon/apple-touch-icon-152x152-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?= get_theme_file_uri(); ?>/images/icon/apple-touch-icon-144x144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?= get_theme_file_uri(); ?>/images/icon/apple-touch-icon-120x120-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?= get_theme_file_uri(); ?>/images/icon/apple-touch-icon-114x114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="76x76" href="<?= get_theme_file_uri(); ?>/images/icon/apple-touch-icon-76x76-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?= get_theme_file_uri(); ?>/images/icon/apple-touch-icon-72x72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="<?= get_theme_file_uri(); ?>/images/icon/apple-touch-icon-precomposed.png">
    <?php endif; ?>
    

    <!-- Favicon  -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= get_theme_file_uri(); ?>/images/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= get_theme_file_uri(); ?>/images/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= get_theme_file_uri(); ?>/images/icons/favicon-16x16.png">
    <link rel="manifest" href="<?= get_theme_file_uri(); ?>/images/icons/site.webmanifest">
    <link rel="mask-icon" href="<?= get_theme_file_uri(); ?>/images/icons/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="<?= get_theme_file_uri(); ?>/images/icons/favicon.ico">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="msapplication-config" content="<?= get_theme_file_uri(); ?>/images/icons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <?php wp_head(); ?>
</head>

<body data-spy="scroll" data-target=".fixed-top" <?php body_class(); ?>>
    <?php wp_body_open(); ?>