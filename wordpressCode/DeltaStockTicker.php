<?php

class DeltaStockTicker {

	function load_scripts() {
		wp_enqueue_style( 'css', plugins_url( '/delta-stock-ticker.css', __FILE__ ) );
		wp_enqueue_script( 'jquery.jstockticker', plugins_url( '/js/jquery.jstockticker-1.1.1.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'delta-stock-ticker', plugins_url( '/js/delta-stock-ticker.js', __FILE__ ), array( 'jquery' ) );

	}

	function activate() {
		$data = array(
			'option1' => 'GOOG',
			'option2' => 'XOM',
			'option3' => 'GE',
			'option4' => 'F',
			'option5' => 'AAPL'
		);

		if ( ! get_option( 'delta_stock_ticker' ) ) {
			add_option( 'delta_stock_ticker', $data );
		} else {
			update_option( 'delta_stock_ticker', $data );
		}
	}

	function deactivate() {
		delete_option( 'delta_stock_ticker' );
	}

	function control() {
	    if (isset( $_POST['stock_symbols'] ) && is_array( $_POST['stock_symbols'] ) ) {
	        $data = $_POST['stock_symbols'];
	    } else {
	        $data = get_option( 'delta_stock_ticker' );
	    }   
    	?>

		<p>Enter the symbol of the stocks you wish to track below. If you enter an invalid stock symbol, it will not be displayed.</p>
			
			<div class="stock_wrapper">

				<?php
				foreach ($data as $symbol) {
				    printf(
				        '<div class="stock_input"><input type="text" size="4" name="stock_symbols[]" value="%s" /></div>',
				        esc_attr($symbol)
				    );  
				}   
				?>

			</div>

		    <a class="button stock_add_button" href="#">Add Stock Symbol</a>


	    <?php
	    if ( isset( $_POST['stock_symbols'] ) && is_array( $_POST['stock_symbols'] ) ) {
	        update_option( 'delta_stock_ticker', $_POST['stock_symbols'] );
	    }   
	}   


	function widget( $args ) {
		$options = get_option( 'delta_stock_ticker' );

		echo $args['before_widget'];
		?>
			<script>
    			var arrayOfStocks = <?php echo json_encode( array_values( $options ) );?>;

    		</script>
				<div id="ticker" class="stockTicker"></div>
			<script>
			    jQuery("#ticker").jStockTicker({});
			</script>
		<?php
		echo $args['after_widget'];
	}

	function register() {
		register_sidebar_widget( 'Delta Stock Ticker', array( 'DeltaStockTicker', 'widget' ) );
		register_widget_control( 'Delta Stock Ticker', array( 'DeltaStockTicker', 'control' ) );
	}
}
