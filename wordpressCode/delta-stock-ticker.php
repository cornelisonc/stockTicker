<?php
/*
Plugin Name: Delta Stock Ticker
Plugin URI: deltasys.com
Description: Fully functional stock ticker from Delta Systems Group
Version: 0.1
Author: Ceili Cornelison
Author URI: deltasys.com
*/

global $options_page;

class Delta_Stock_Ticker
{
	function __construct()
	{
		add_action( 'admin_enqueue_scripts', 	array( $this, 'delta_stock_ticker_load_admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', 		array( $this, 'delta_get_stock_ticker_css' ) );
		add_action( 'wp_enqueue_scripts',		array( $this, 'delta_stock_ticker_front_facing_pages' ) );
		add_action( 'wp_head', 					array( $this, 'delta_get_stock_ticker_js' ) );
		add_action( 'admin_menu', 				array( $this, 'delta_stock_ticker_settings_menu' ), 1 );
		add_action( 'admin_init', 				array( $this, 'delta_stock_ticker_admin_init' ) );
		register_activation_hook( __FILE__, 	array( $this, 'delta_stock_ticker_default_stocks' ) );
	}

	function delta_get_stock_ticker_js()
	{
		$delta_stock_ticker_js 			= plugins_url( 'js/jquery.jstockticker-1.1.1.js', __FILE__ );
		?>
			<script type="text/javascript" src="<?php echo $delta_stock_ticker_js; ?>"></script>
		<?php
	}

	function delta_get_stock_ticker_css()
	{
		wp_enqueue_style( 'delta_stock_ticker_css', plugins_url( 'css/delta-stock-ticker.css', __FILE__ ) );
	}

	function delta_stock_ticker_settings_menu()
	{
		global $options_page;

		$options_page = add_options_page( 'Delta Stock Ticker', 'Delta Stock Ticker', 'manage_options', 'delta_stock_ticker', array( $this, 'delta_stock_ticker_config_page' ) );
		
		if ( $options_page ) {
			add_action( 'load-'.$options_page, array( $this, 'delta_stock_ticker_help_tabs' ) );
		}
	}

	function delta_stock_ticker_front_facing_pages()
	{
		wp_enqueue_script( 'jquery' );
	}

	function delta_stock_ticker_help_tabs()
	{		
		$screen = get_current_screen();
		$screen->add_help_tab( array(
			'id'		=>	'delta-stock-ticker-help-instructions',
			'title' 	=>	'Instructions',
			'callback'	=>	array( $this, 'delta_stock_ticker_help_instructions' )
			) );

		$screen->add_help_tab( array(
			'id'		=>	'delta-stock-ticker-help-faq',
			'title'		=>	'FAQ',
			'callback'	=>	array( $this, 'delta_stock_ticker_help_faq' )
			) );

		$screen->set_help_sidebar( '<p>This is the sidebar content.</p>' );

		global $options_page;
		add_meta_box( 
			'delta_stock_ticker_general_meta_box', 
			'General Settings',
			array( $this, 'delta_stock_ticker_meta_box'),
			$options_page,
			'normal',
			'core' );
		add_meta_box(
			'delta_stock_ticker_second_meta_box',
			'Second Settings Section',
			array( $this, 'delta_stock_ticker_second_meta_box'),
			$options_page,
			'normal',
			'core' );
	}

	function delta_stock_ticker_load_admin_scripts()
	{
		global $current_screen;
		global $options_page;

		if( $current_screen->id == $options_page )
		{
			wp_enqueue_script( 'common' );
			wp_enqueue_script( 'wp-lists' );
			wp_enqueue_script( 'postbox' );
		}
	}

	function delta_stock_ticker_meta_box( $options )
	{
		?>
			<h2>Stocks</h2>
		<?php

		foreach ( get_option('delta_stock_ticker_stocks') as $option_name => $option_value )
		{
			echo '<input type="text" name="'.$option_name.'" value="'.$option_value.'"/><br />';
		} 
	}

	function delta_stock_ticker_second_meta_box( $options )
	{
		?>
			<p>This is the content of the second metabox.</p>
		<?php
	}

	function delta_stock_ticker_help_instructions()
	{
		?>
			<p>These are instructions explaining how to use this plugin.</p>
		<?php
	}

	function delta_stock_ticker_help_faq()
	{
		?>
			<p>These are the most frequently asked questions on the use of this plugin.</p>
		<?php
	}

	function delta_stock_ticker_admin_init() 
	{
		add_action( 'admin_post_save_delta_stock_ticker_options', array( $this, 'process_delta_stock_ticker_options') );
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
		global $options_page;
		?>
			<div id="delta-stock-ticker-general" class="wrap">
				<h2>Delta Stock Ticker</h2>
				<?php
					if ( isset( $_GET['message'] ) && $_GET['message'] == '1' ) {
						echo "<div id='message' class='updated fade'><p><strong>Settings Saved</strong></p></div>";
					}
				?>
				<form method="post" action="admin-post.php">
					<input type="hidden" name="action" value="save_delta_stock_ticker_options" />

					<?php 
						// Adding security through hidden referrer field
						wp_nonce_field( 'delta_stock_ticker' ); 
	
						//Security fields for meta box save processing
						wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
						wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
					?>

					<div id="poststuff" class="metabox-holder">
						<dig id="post-body">
							<div id="post-body-content">
								<?php do_meta_boxes( $options_page, 'normal', $options ); ?>

								<input type="submit" value="Submit" class="button-primary"/>
							</div>
						</div>
						<br class="clear"/>
					</div>
				</form>
			</div>

			<script type="text/javascript">
				//<![CDATA[
					jQuery(document).ready(function($) {
						//close postboxes that should be closed
						$('.if-js-closed').removeClass('if-js-closed').addClass('closed');

						//postboxes setup
						postboxes.add_postbox_toggles('<?php echo $options_page; ?>');
					});
				//]]>
			</script>
		<?php
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

		//Redirect the page to the configuration form that was processed with confirmation message
		wp_redirect( add_query_arg(
			array( 
				'page' => 'delta_stock_ticker',
				'message' => '1' ),
			admin_url( 'options-general.php' ) ) );
		exit;
	}
}

$my_delta_stock_ticker = new Delta_Stock_Ticker();

?>
