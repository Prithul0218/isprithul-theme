<?php
/**
 * Opening of every page: <head>, the skip link and the site header.
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;
$isp_home = isp_home_view();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> data-available="<?php echo isp_available() ? 'true' : 'false'; ?>">
<?php wp_body_open(); ?>
<a class="skip" href="<?php echo $isp_home ? '#projects' : '#main'; ?>"><?php echo $isp_home ? 'Skip to projects' : 'Skip to content'; ?></a>
<?php get_template_part( 'parts/site-header' ); ?>
