<?php if(!function_exists('add_action')) { exit; }


add_action( 'widgets_init', 'juicenet_gdpr_load_widget' );
// Register and load the widget
function juicenet_gdpr_load_widget() {
    register_widget( 'juicenet_gdpr_widget' );
}

 
// Creating the widget 
class juicenet_gdpr_widget extends WP_Widget {
 
	function __construct() {
		parent::__construct(
		 
			// Base ID of widget
			'juicenet_gdpr_widget', 
			 
			// Widget name will appear in UI
			__('Juicenet GDPR Widget', 'juicenet_gdpr_domain'), 
			 
			// Widget description
			array( 'description' => __( 'Widget show link to Privacy and cookie page', 'juicenet-gdpr' ), ) 
		
		);
	}
 
	// Creating widget front-end
	 
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		 
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];

		$html = "";
		if(get_option('juicenet_gdpr_privacy_page')!="")
			$html  .= '<a href="'.get_site_url()."/".get_option('juicenet_gdpr_privacy_page').'" class="juice-cs-cookie-policy-lnk">Privacy policy</a> - ';
		if(get_option('juicenet_gdpr_cookie_page')!="")
			$html .= '<a href="'.get_site_url()."/".get_option('juicenet_gdpr_cookie_page').'" class="juice-cs-cookie-policy-lnk">Cookie policy</a>';
		
		if($html == "")
			$html .= "Configure Link from JuiceNet GDPR Admin Page";
		
		// This is where you run the code and display the output
		echo __( $html );
		
		echo $args['after_widget'];
	}
         
	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = __( 'GDPR', 'juicenet-gdpr' );
		}
		// Widget admin form
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}
     
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
} // Class juicenet_gdpr_widget ends here



