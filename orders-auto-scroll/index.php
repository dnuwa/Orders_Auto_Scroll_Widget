<?php
/*
Plugin Name: Orders Auto Scroll Widget
Plugin URI: http://www.wpexplorer.com/create-widget-plugin-wordpress/
Description: This plugin adds a custom widget that displays orders in an auto scroll sidebar.
Version: 1.0
Author: Daniel Nuwagaba
Author URI: https://github.com/dnuwa
License: GPL2
*/

// The widget class
class Orders_Auto_Scroll_Widget extends WP_Widget {

	// Main constructor
	public function __construct() {
		parent::__construct(
			'orders_auto_scroll_widget',
			__( 'Orders Auto Scroll Plugin', 'text_domain' ),
			array(
				'customize_selective_refresh' => true,
			)
		);
	}

	// The widget form (for the backend )
	public function form( $instance ) {
		// if ( isset( $instance[ 'title' ] ) )
		// 	$title = $instance[ 'title' ];
			// $limit = $instance[ 'limit' ];
		// else
			// $title = __( 'some text', 'orders_auto_scroll_widget' );
			// $limit = __( 'Limit', 'orders_auto_scroll_widget' );
			?>			
			<p>
				<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Limit:*' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="number" value="<?php echo esc_attr( $limit ); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'date_from' ); ?>"><?php _e( 'Date From:' ); ?><small>&nbsp;(optional)</small></label>
				<input class="widefat" placeholder="2021-12-07" id="<?php echo $this->get_field_id( 'date_from' ); ?>" name="<?php echo $this->get_field_name( 'date_from' ); ?>" type="text" value="<?php echo esc_attr( $date_from ); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'date_to' ); ?>"><?php _e( 'Date To:' ); ?>&nbsp;<small>(optional)</small></label>
				<input class="widefat" placeholder="2021-12-08" id="<?php echo $this->get_field_id( 'date_to' ); ?>" name="<?php echo $this->get_field_name( 'date_to' ); ?>" type="text" value="<?php echo esc_attr( $date_to ); ?>" />
			</p>					
		<?php
		}

	// Update widget settings
	public function update( $new_instance, $old_instance ) {

		$instance = array();
		$instance['date_from'] = ( ! empty( $new_instance['date_from'] ) ) ? strip_tags( $new_instance['date_from'] ) : '';
		$instance['date_to'] = ( ! empty( $new_instance['date_to'] ) ) ? strip_tags( $new_instance['date_to'] ) : '';
		$instance['limit'] = ( ! empty( $new_instance['limit'] ) ) ? strip_tags( $new_instance['limit'] ) : 1;
		return $instance;
		}

	// Display the widget
	public function widget( $args, $instance ) {
		extract( $args );
		global $wpdb;

	$limit = apply_filters( 'widget_limit', $instance['limit'] );

	// $date_from = '2021-12-07';
    // $date_to = '2021-12-08';
	$date_from = apply_filters( 'widget_date_from', $instance['date_from'] );
	$date_to = apply_filters( 'widget_date_to', $instance['date_to'] );

	$i = 0;

	if($date_from && $date_to)
		$result = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE post_type = 'shop_order' AND post_date BETWEEN '{$date_from}  00:00:00' AND '{$date_to} 23:59:59' ORDER BY menu_order ASC LIMIT $limit");
	else
		$result = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE post_type = 'shop_order' ORDER BY menu_order ASC LIMIT $limit");

	echo '<div class="header">Latest Order</div>';
	echo '<div class="wrapper">';
	

    foreach($result as $value) {

        // Getting WC order object
        $order = wc_get_order( $value->ID );

		//get order data
		$orderdata = $order->get_data();

		$items = $order->get_items();

        // For example displaying order number and status
        // echo '<li>Order #'.$order->id. ' with status ' . $order->get_status() . '</li>';
		echo '<div class="card"><div><small>'.$orderdata['billing']['first_name']. '</small></div><div><small>' . $orderdata['billing']['city'].','.$orderdata['billing']['country'] . '</small></div><div><small>'. $orderdata['date_created'] -> date('Y-m-d H:i:s') .'</small></div><div>';
		echo '<div>';
		foreach ($items as $item){

			$item_data = $item->get_data();

			echo '<small>'. $item_data['name']. '</small> &nbsp; <small>'. $item_data['quantity'].'</small></br>';
		};
		echo'</div></div><div class="amount"><small><span class="dashicons dashicons-cart"></span>'.$orderdata['currency'].'&nbsp;'.$orderdata['total'].'</small></div></div>';
    }
    echo '</div>';

	}

}

// Register the widget
function my_register_custom_widget() {
	register_widget( 'Orders_Auto_Scroll_Widget' );
}
add_action( 'widgets_init', 'my_register_custom_widget' );
