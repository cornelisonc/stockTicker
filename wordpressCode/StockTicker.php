<?php

class Delta_stock_ticker {

	function load_scripts() {
		wp_enqueue_style( 'the_css', plugins_url('/delta-stock-ticker.css', __FILE__ ));
		wp_enqueue_script( 'jquery.jstockticker', plugins_url('/js/jquery.jstockticker-1.1.1.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'delta-stock-ticker', plugins_url('/js/delta-stock-ticker.js', __FILE__ ), array( 'jquery' ) );

	}

	function activate() {
		$data = array( 
			'option1' => 'GOOG',
			'option2' => 'XOM',
			'option3' => 'GE',
			'option4' => 'F',
			'option5' => 'AAPL'
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
			<p>Enter the symbol of the stocks you wish to track below. If you enter an invalid stock symbol, it will not be displayed.</p>
			<p><label>Stock 1<input name="delta_stock_ticker_option1" type="text" value="<?php echo $data['option1']; ?>" /></label></p>
			<p><label>Stock 2<input name="delta_stock_ticker_option2" type="text" value="<?php echo $data['option2']; ?>" /></label></p>
			<p><label>Stock 3<input name="delta_stock_ticker_option3" type="text" value="<?php echo $data['option3']; ?>" /></label></p>
			<p><label>Stock 4<input name="delta_stock_ticker_option4" type="text" value="<?php echo $data['option4']; ?>" /></label></p>
			<p><label>Stock 5<input name="delta_stock_ticker_option5" type="text" value="<?php echo $data['option5']; ?>" /></label></p>
		<?php
			if (isset($_POST['delta_stock_ticker_option1'])) {
				$data['option1'] = attribute_escape($_POST['delta_stock_ticker_option1']);
				$data['option2'] = attribute_escape($_POST['delta_stock_ticker_option2']);
				$data['option3'] = attribute_escape($_POST['delta_stock_ticker_option3']);
				$data['option4'] = attribute_escape($_POST['delta_stock_ticker_option4']);
				$data['option5'] = attribute_escape($_POST['delta_stock_ticker_option5']);
				update_option('delta_stock_ticker', $data);
			}	
	}

	function widget($args) {
		$options = get_option('delta_stock_ticker');

		echo $args['before_widget'];
		?>
			<script>
    			var arrayOfStocks = [ 
    				<?php
    				foreach ($options as $option) {
    					echo '"' . $option . '",';
    				}
    				?>
    			];
    		</script>
				<div id="ticker" class="stockTicker"></div>
			<script>
			    jQuery("#ticker").jStockTicker({});
			</script>
		<?php
		echo $args['after_widget'];
	}

	function register() {
		register_sidebar_widget('Delta Stock Ticker', array('Delta_stock_ticker', 'widget'));
		register_widget_control('Delta Stock Ticker', array('Delta_stock_ticker', 'control'));
	}
}
