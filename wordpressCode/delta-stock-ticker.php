<?php
/*
Plugin Name: Delta Stock Ticker
Plugin URI:
Description: This stock ticker doesn't suck.
Author: Ceili Cornelison, Delta Systems
Version:
Author URI:
*/

error_reporting(E_ALL);

add_action("widgets_init", array( 'Delta_stock_ticker', 'register') );
add_action("widgets_init", array( 'Delta_stock_ticker', 'load_scripts') );
register_activation_hook( __FILE__, array('Delta_stock_ticker', 'activate'));
register_deactivation_hook( __FILE__, array('Delta_stock_ticker', 'deactivate'));

class Delta_stock_ticker {

	function load_scripts() {
		wp_enqueue_style( 'the_css', plugins_url('/delta-stock-ticker.css', __FILE__ ));
		wp_enqueue_script( 'jquery.jstockticker', plugins_url('/js/jquery.jstockticker-1.1.1.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'delta-stock-ticker', plugins_url('/js/delta-stock-ticker.js', __FILE__ ), array( 'jquery' ) );

	}

	function activate() {
		$data = array( 
			'option1' => 'Detault value',
			'option2' => 55
		);

		if ( ! get_option('delta_stock_ticker')) {
			add_option('delta_stock_ticker', $data);
		} else {
			update_option('delta_stock_ticker', $data);
		}
	}

	function deactivate() {
		delete_option('delta_stock_ticker');
	}

	function control() {
		$data = get_option('delta_stock_ticker');
		?>
			<p><label>Option 1<input name="delta_stock_ticker_option1" type="text" value="<?php echo $data['option1']; ?>" /></label></p>
			<p><label>Option 2<input name="delta_stock_ticker_option2" type="text" value="<?php echo $data['option2']; ?>" /></label></p>
		<?php
			if (isset($_POST['delta_stock_ticker_option1'])) {
				$data['option1'] = attribute_escape($_POST['delta_stock_ticker_option1']);
				$data['option2'] = attribute_escape($_POST['delta_stock_ticker_option2']);
				update_option('delta_stock_ticker', $data);
			}	
	}

	function widget($args) {
		$data = get_option('delta_stock_ticker');

		echo $args['before_widget'];
		echo $args['before_title'] . 'Delta Stock Ticker' . $args['after_title'];
		?>
			<script>
			    $("#ticker").jStockTicker({});
			</script>
			<div class="deltaWrap">
				<div id="ticker" class="stockTicker"></div>
			</div>
		<?php
		echo $args['after_widget'];
	}

	function register() {
		register_sidebar_widget('Delta Stock Ticker', array('Delta_stock_ticker', 'widget'));
		register_widget_control('Delta Stock Ticker', array('Delta_stock_ticker', 'control'));
	}
}