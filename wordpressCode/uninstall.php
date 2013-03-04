<?php

//Check that code was called from Wordpress with uninstallation constant declared
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
{
	exit;
}

//Check if options exist and delete them if present
if ( get_option( 'delta_stock_ticker_stocks' ) != false )
{
	delete_option( 'delta_stock_ticker_stocks' );
}

?>