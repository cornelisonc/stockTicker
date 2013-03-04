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
		add_action( 'wp_enqueue_scripts', array( $this, 'delta_get_stock_ticker_css' ) );
		add_action( 'wp_head', array( $this, 'delta_get_stock_ticker_js' ) );
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


}

$my_delta_stock_ticker = new Delta_Stock_Ticker();

function delta_stock_ticker_default_stocks()
{
	if ( get_option( 'delta_stock_ticker_stocks' ) === false )
	{
		$new_options['delta_stock_ticker_num_stocks'] 	= "5";
		$new_options['delta_stock_ticker_stock_0']		= "GOOG";
		$new_options['delta_stock_ticker_stock_1']		= "XOM";
		$new_options['delta_stock_ticker_stock_2']		= "F";
		$new_options['delta_stock_ticker_stock_3']		= "GE";
		$new_options['delta_stock_ticker_stock_4']		= "APPL";

		add_option( 'delta_stock_ticker_stocks', $new_options );
	}
}
register_activation_hook( __FILE__, 'delta_stock_ticker_default_stocks' );
?>