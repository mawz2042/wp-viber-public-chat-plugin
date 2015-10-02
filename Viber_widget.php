<?php
require_once 'functions.php';
/**

 * Plugin Name: Viber vidget
 * Plugin URI:
 * Description: This takes you to a viber public chat
 * Version: 1.0.0
 * Author:
 * Author URI:
 * License: GPL2
 */

add_action( 'widgets_init', function(){
    register_widget( 'Viber_widget' );
});
add_action( 'wp_enqueue_scripts', 'viber_my_scripts_method' );

class Viber_widget extends WP_Widget{

    function __construct() {
        parent::__construct('Viber_widget',__('Viber widget', 'Viber_widget_domain'),array( 'description' => __( 'Viber public chat widget', 'text_domain' ), ));
    }

    function Viber_widget(){
        $widget_ops = array( 'chat_owner' => null, 'description' => __('Viber public chat widget', 'Viber_widget_domain') );
        $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'Viber_widget' );
        $this->WP_Widget( 'Viber_widget', __('Viber_widget', 'Viber_widget_domain'), $widget_ops, $control_ops );

    }

    public function widget( $args, $instance ) {

        echo $args['before_widget'];
        if(! empty($instance['chat_owner'])) {
            $html = get_viber($instance['chat_owner']);
            echo $html;
        }
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'New title', 'text_domain' );
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php

        if ( isset( $instance[ 'chat_owner' ] ) ) {
            $chat_owner = $instance[ 'chat_owner' ];
        }
        else {
            $chat_owner = __( 'New owner', 'Viber_widget_domain' );
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'chat_owner' ); ?>"><?php _e( 'chat_owner:' ); ?></label>
            <input class="widefat"  id="<?php echo $this->get_field_id( 'chat_owner' ); ?>" name="<?php echo $this->get_field_name( 'chat_owner' ); ?>" type="text" value="<?php echo esc_attr( $chat_owner ); ?>" required>
        </p>
        <?php

    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['chat_owner'] = ( ! empty( $new_instance['chat_owner'] ) ) ? strip_tags( $new_instance['chat_owner'] ) : '';
        return $instance;
    }


}


?>