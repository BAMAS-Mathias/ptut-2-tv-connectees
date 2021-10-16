<?php

/**
 * Virtual Pages
 */

use EC\VirtualPages\PageControllerInterface;
use EC\VirtualPages\PageController;

use EC\VirtualPages\TemplateLoaderInterface;
use EC\VirtualPages\TemplateLoader;

use EC\VirtualPages\PageInterface;

$controller = new PageController ( new TemplateLoader );

add_action( 'init', array( $controller, 'init' ) );

add_filter( 'do_parse_request', array( $controller, 'dispatch' ), PHP_INT_MAX, 2 );

add_action( 'loop_end', function( \WP_Query $query ) {
    if ( isset( $query->virtual_page ) && ! empty( $query->virtual_page ) ) {
        $query->virtual_page = NULL;
    }
} );

add_filter( 'the_permalink', function( $plink ) {
    global $post, $wp_query;
    if (
        $wp_query->is_page && isset( $wp_query->virtual_page )
        && $wp_query->virtual_page instanceof Page
        && isset( $post->is_virtual ) && $post->is_virtual
    ) {
        $plink = home_url( $wp_query->virtual_page->getUrl() );
    }
    return $plink;
} );

add_action( 'ec_virtual_pages', function( $controller ) {
  $controller->addPage( new \EC\VirtualPages\Page( "/creation-des-comptes" ) )
    ->setTitle( 'My First Custom Page' )
    ->setContent( 'test' )
    ->setTemplate( 'page0.php' );
} );
