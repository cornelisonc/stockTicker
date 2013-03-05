<?php
/*
Plugin Name: Delta Stock Ticker
Plugin URI: deltasys.com
Description: Fully functional stock ticker from Delta Systems Group
Version: 0.1
Author: Ceili Cornelison
Author URI: deltasys.com
*/

class Delta_Stock_Ticker {
	function __construct() {
		add_action( 'wp_enqueue_scripts', 	array( $this, 'delta_get_stock_ticker_css' ) );
		add_action( 'wp_head', 				array( $this, 'delta_get_stock_ticker_js' ) );
		add_action( 'admin_menu', 			array( $this, 'delta_stock_ticker_settings_menu' ), 1 );
		add_action( 'admin_init', 			array( $this, 'delta_stock_ticker_admin_init' ) );
		register_activation_hook( __FILE__, array( $this, 'delta_stock_ticker_default_stocks' ) );
	}

	function delta_get_stock_ticker_js()
	{
		$delta_stock_ticker_jquery		= plugins_url( 'js/jquery-1.4.2.min.js', __FILE__ );
		$delta_stock_ticker_js 			= plugins_url( 'js/jquery.jstockticker-1.1.1.js', __FILE__ );
		?>
		<script type="text/javascript" src="<?php echo $delta_stock_ticker_jquery; ?>"></script>
		<script type="text/javascript" src="<?php echo $delta_stock_ticker_js; ?>"></script>
		<?php
	}

	function delta_get_stock_ticker_css()
	{
		wp_enqueue_style( 'delta_stock_ticker_css', plugins_url( 'css/delta-stock-ticker.css', __FILE__ ) );
	}

	function delta_stock_ticker_settings_menu()
	{
		add_options_page( 'Delta Stock Ticker', 'Delta Stock Ticker', 'manage_options', 'delta_stock_ticker', array( $this, 'delta_stock_ticker_config_page' ) );
	}

	function delta_stock_ticker_default_stocks()
	{
		if ( get_option( 'delta_stock_ticker_stocks' ) === false )
		{
			$new_options['delta_stock_ticker_stock_0']		= "GOOG";
			$new_options['delta_stock_ticker_stock_1']		= "XOM";
			$new_options['delta_stock_ticker_stock_2']		= "F";
			$new_options['delta_stock_ticker_stock_3']		= "GE";
			$new_options['delta_stock_ticker_stock_4']		= "APPL";

			add_option( 'delta_stock_ticker_stocks', $new_options );
		}
	}
	
	function delta_stock_ticker_config_page()
	{
		//Retrieve plugin configuration options from database
		$options = get_option( 'delta_stock_ticker_stocks' );
		?>
			<div id="delta-stock-ticker-general" class="wrap">
				<h2>Delta Stock Ticker</h2>
				<form method="post" action="admin-post.php">
					<input type="hidden" name="action" value="save_delta_stock_ticker_options" />

					<?php 
						// Adding security through hidden referrer field
						wp_nonce_field( 'delta_stock_ticker' ); 
	
						// Cycle through each option to create input boxes
						foreach ( get_option('delta_stock_ticker_stocks') as $option_name => $option_value )
						{
							echo '<input type="text" name="'.$option_name.'" value="'.$option_value.'"/><br />';
						} 
					?>

					<input type="submit" value="Submit" class="button-primary"/>
				</form>
			</div>
		<?php


		foreach ( get_option('delta_stock_ticker_stocks') as $option_name => $option_value )
		{
			var_dump($option_name);
			var_dump($option_value);
		}

	}

	function delta_stock_ticker_admin_init() 
	{
		add_action( 'admin_post_save_delta_stock_ticker_options', array( $this, 'process_delta_stock_ticker_options') );
	}

	function process_delta_stock_ticker_options()
	{
		//Check that user has proper security level
		if( !current_user_can( 'manage_options' ) )
		{
			wp_die( 'Not allowed!' );
		}

		//Check that nonce field created in configuration form is present
		check_admin_referer( 'delta_stock_ticker' );

		//Retrieve original plugin options array
		$options = get_option( 'delta_stock_ticker_stocks' );

		foreach ( get_option('delta_stock_ticker_stocks') as $option_name => $option_value )
		{
			if ( isset( $_POST[$option_name] ) )
			{
				$options[$option_name] = sanitize_text_field( $_POST[$option_name]);
			}
		} 

		//Store updated options array to database
		update_option( 'delta_stock_ticker_stocks', $options );

		//Redirect the page to the configuration form that was processed
		wp_redirect( add_query_arg( 'page', 'delta_stock_ticker', admin_url( 'options-general.php' ) ) );
		exit;
	}
}

$my_delta_stock_ticker = new Delta_Stock_Ticker();


?>