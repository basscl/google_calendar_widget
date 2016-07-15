<?php
/*
Plugin Name: Test Plugin for lyingibex.com
Description: test widget
*/
/* Start Adding Functions Below this Line */

// Creating the widget 
class wpb_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'wpb_widget', 

// Widget name will appear in UI
__('WPBeginner Widget', 'wpb_widget_domain'), 

// Widget description
array( 'description' => __( 'Sample widget based on WPBeginner Tutorial', 'wpb_widget_domain' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output

$your_google_calendar='https://calendar.google.com/calendar/embed?showTitle=0&showNav=0&showDate=0&showPrint=0&showTabs=0&showCalendars=0&showTz=0&mode=AGENDA&height=200&wkst=1&bgcolor=%23ffffff&src=4pqirqdqr6tc3tj32uhl1i39a8@group.calendar.google.com&color=%23ffffff&ctz=America/New_York';
$url= parse_url($your_google_calendar);
//$google_domain = $url['scheme'].'://'.$url['host'].dirname($url['path']).'/';
$google_domain = 'https:';
// Load and parse Google's raw calendar
$dom = new DOMDocument;
$dom->loadHTMLfile($your_google_calendar);



// Change Google's CSS file to use absolute URLs (assumes there's only one element)
$css = $dom->getElementsByTagName('link')->item(0);
//echo $css->hasAttribute('href');
$css_href = $css->getAttribute('href');

$css->setAttribute('href', $google_domain . $css_href);

// Change Google's JS file to use absolute URLs
$scripts = $dom->getElementsByTagName('script')->item(0);
foreach ($scripts as $script) {
$js_src = $script->getAttribute('src');
if ($js_src) $script->setAttribute('src', $google_domain . $js_src);
}

// Create a link to a new CSS file called custom_calendar.css
$element = $dom->createElement('link');
$element->setAttribute('type', 'text/css');
$element->setAttribute('rel', 'stylesheet');
$element->setAttribute('href', 'http://lyingibex.com/wp-content/plugins/liplugin_custom_calendar.css');
// Append this link at the end of the element
$head = $dom->getElementsByTagName('head')->item(0);
$head->appendChild($element); 

// Export the HTML
echo $dom->saveHTML();



echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'wpb_widget_domain' );
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
} // Class wpb_widget ends here

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );

/* Stop Adding Functions Below this Line */
?>
