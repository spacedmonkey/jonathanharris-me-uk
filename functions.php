<?php
require get_stylesheet_directory() . '/inc/loader.php';
//require get_stylesheet_directory() . '/inc/twentythirteen.php';
require get_stylesheet_directory() . '/inc/class.php';
//require get_stylesheet_directory() . '/inc/bootstrap.php';
//require get_stylesheet_directory() . '/inc/category.php';
//require get_stylesheet_directory() . '/inc/nav.php';
//require get_stylesheet_directory() . '/inc/nav-social.php';
//require get_stylesheet_directory() . '/inc/figure.php';
//require get_stylesheet_directory() . '/inc/comments.php';
require get_stylesheet_directory() . '/inc/gallery.php';
require get_stylesheet_directory() . '/inc/sm_home.php';

if(is_admin()){
	require get_stylesheet_directory() . '/inc/header.php';
}


class Spacedmonkey_Theme {

	/**
	 * 
	 * @since   1.0
	 *
	 * @var     string
	 */
	const VERSION = '8';

	/**
	 *
	 * Unique identifier for your theme.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $theme_slug = 'spacedmonkey';


	protected $theme_data;
	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		add_filter( 'template_include', array( $this, 'template_include' ), 99 );
		add_filter( 'excerpt_length', array( $this, 'excerpt_length') );
		add_filter( 'excerpt_more', array( $this, 'excerpt_more'));
		add_filter( 'the_content', array( $this, 'the_content'));
		//add_filter( 'wp_get_attachment_image_attributes', array($this, 'attachment_image_attributes'));
		add_filter( 'widget_archives_args', array( $this, 'widget_archives_args'));
		
		add_filter( 'widget_categories_args', array( $this, 'change_cats'));
		
		add_action( 'wp_enqueue_scripts', array( $this , 'enqueue_scripts' ) , 1 );
		add_action( 'after_setup_theme', array( $this, 'infinite_scroll_init'), 25 );
		add_action( 'init', array( $this, 'init_functions' ) );
		add_action( 'wp_head', array( $this, 'header_style' ) , 50);
		add_action( 'twentythirteen_credits', array( $this, 'credits') );
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_theme_slug(){
		return $this->theme_slug;
	}


	public function load_theme_data(){
		$this->theme_data = wp_get_theme();
	}

	public function get_theme_data(){
		if(!$this->theme_data){
			$this->load_theme_data();
		}
		return $this->theme_data;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
		

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function template_include( $template ) {

		if ( is_archive() || is_home() || is_search()){
			
			$new_template = locate_template( array( 'archive.php' ) );
			if ( '' != $new_template ) {
				return $new_template ;
			}
		}
		
		
		
		if ( (is_page() || is_single() || is_404() || is_attachment()) && (!is_front_page())){
			$new_template = locate_template( array( 'single.php' ) );
			if ( '' != $new_template ) {
				return $new_template ;
			}
		}
		
		
		
		return $template;
	}
	
	public function widget_archives_args($attr){
		$attr['limit'] = 5;
		return $attr;
	}
	
	function change_cats($old){
		$old['number'] = 10;
		$old['orderby'] = 'count';
		$old['order'] = 'DESC';
		return $old;
	}


	public function the_content($content){
		global $post;
		if($post->post_type == 'archive' && is_singular() && !is_front_page()){
			
			$date = $this->date_difference($post->post_date, date("U"));
			$extra = sprintf(__('<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Warning!</strong> This post is %s old, so that the content might be out of date or incorrect and links broken.</div>'), $date);
			$content = $extra . $content;
		}
		return $content;
		
	}
	
	function date_difference($date1, $date2){
		
		$diff = abs($date2 - strtotime($date1));
		
		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		//$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
		
		return sprintf(__('%d years and %d months'), $years, $months);
	
	}
	
	/**
	 * Return the Google font stylesheet URL, if available.
	 *
	 * The use of Source Sans Pro and Bitter by default is localized. For languages
	 * that use characters not supported by the font, the font can be disabled.
	 *
	 * @since Twenty Thirteen 1.0
	 *
	 * @return string Font stylesheet or empty string if disabled.
	 */
	public function fonts_url() {
		$fonts_url = '';

		/* Translators: If there are characters in your language that are not
		 * supported by Source Sans Pro, translate this to 'off'. Do not translate
		 * into your own language.
		 */
		
		$font_families = array();

		
		$font_families[] = 'Source Sans Pro:300,400,700,300italic,400italic,700italic';
		$font_families[] = 'Open Sans:300,400,600,700,800,300italic,400italic,600italic,700italic,800italic';
		$font_families[] = 'Rancho';

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$fonts_url = add_query_arg( $query_args, "//fonts.googleapis.com/css" );

		return $fonts_url;
	}

	/**
	 * Enqueue scripts and styles for the front end.
	 *
	 * @since Twenty Thirteen 1.0
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		/*
		 * Adds JavaScript to pages with the comment form to support
		 * sites with threaded comments (when in use).
		 */
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ){
			wp_enqueue_script( 'comment-reply' );
		}
			

		wp_dequeue_style( 'infinity-twentythirteen');
		wp_dequeue_style( 'twentythirteen-fonts');
		wp_dequeue_style( 'twentythirteen-style');
		

		wp_register_script( 'fitvids', get_stylesheet_directory_uri() . '/js/jquery.fitvids.js', array( 'jquery' ), '3.0.3', true );

		//wp_register_script( 'twitter-bootscrap', get_stylesheet_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ), '1.0.3', true );
		
		// Loads JavaScript file with functionality specific to Twenty Thirteen.
		wp_enqueue_script( $this->get_theme_slug().'-script', get_stylesheet_directory_uri() . '/js/site.js', array( 'jquery','fitvids'), filemtime( get_stylesheet_directory() . '/js/site.js'), true );

		wp_register_style( $this->get_theme_slug().'-fonts', $this->fonts_url(), array(), null );

		// Add Genericons font, used in the main stylesheet.
		wp_register_style( 'twitter-bootstrap', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', array(), '3.0.3' );
		
		// Add Genericons font, used in the main stylesheet.
		wp_register_style( 'twitter-bootstrap-theme', get_stylesheet_directory_uri() . '/css/bootstrap-theme.min.css', array('twitter-bootstrap'), '3.0.3' );

		// Add Genericons font, used in the main stylesheet.
		//wp_register_style( 'genericons', get_template_directory_uri() . '/fonts/genericons.css', array(), '2.09' );

		// Loads our main stylesheet.
		wp_enqueue_style( $this->get_theme_slug().'-style', get_stylesheet_uri(), array($this->get_theme_slug().'-fonts','genericons'), filemtime(get_stylesheet_directory() . '/style.css') );

	}
	public function attachment_image_attributes($attr){
		$attr['class'] = "img-thumbnail ".$attr['class'];
		
		return $attr;
	}
	

	public function excerpt_length($length) {
		return 25;
	}

	function excerpt_more($more) {
        global $post;
        
        return apply_filters($this->get_theme_slug().'_excerpt', ' <a href="'. get_permalink($post->ID) . '">' . __('...', $this->get_theme_slug()) . '</a>');
    }

	public function infinite_scroll_init() {
		add_theme_support( 'infinite-scroll', array(
			'container' => 'content',
			'footer'    => false,
			'footer_widgets' => false,
			'posts_per_page' => get_option('posts_per_page'),
			'render'    =>  array( $this, 'infinite_scroll_render'),
		) );
	}

	public function infinite_scroll_render() {
		 get_template_part( 'loop', (is_search()) ? 'search' : '' ); 	 
	}

	public function init_functions() {
		register_nav_menu( 'social', __( 'Social', $this->get_theme_slug() ) );
		
		$args = array(
			'default-color' => 'FFFFFF'
		);
		add_theme_support( 'custom-background', $args );
		
	}

	/**
	 * Style the header text displayed on the blog.
	 *
	 * get_header_textcolor() options: Hide text (returns 'blank'), or any hex value.
	 *
	 * @since Twenty Thirteen 1.0
	 *
	 * @return void
	 */
	public function header_style() {
		$header_image = get_header_image();
		$text_color   = get_header_textcolor();

		?>
		<meta http-equiv="imagetoolbar" content="false">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<style type="text/css" id="<?php echo $this->get_theme_slug();?>-css">
		<?php
			if ( ! empty( $header_image ) ) :
		?>
			#masthead {
				background: url(<?php header_image(); ?>) no-repeat scroll top;
				background-size: 1600px auto;
			}
		<?php
			else:
			
			?>
			#masthead {
				background: <?php echo esc_attr( get_theme_mod( 'spacedmonkey_header_colour', '#0000000' ) ); ?>;
			}
			<?php
			endif;

			

			// If the user has set a custom color for the text, use that.
			if ( $text_color != get_theme_support( 'custom-header', 'default-text-color' ) ) :
		?>
			#masthead .site-title{
				color: #<?php echo esc_attr( $text_color ); ?> !important;
			}
		<?php endif; ?>
		</style>
		<?php
	}

	public function credits(){
		$my_theme = $this->get_theme_data();
		printf('<address class="h-card vcard">&copy; <span class="org nickname p-org">%s</span> %s  <span class="pull-right">Design by <a href="%s" class="fn n url u-url">%s</a></span></address>', get_bloginfo('name'), date("Y"), $my_theme->get('AuthorURI'), $my_theme->get('Author'));
	}

}

global $spacedmnkey_theme;
$spacedmnkey_theme = Spacedmonkey_Theme::get_instance();


function spacedmonkey_script_loader_src($src, $handle){
	if("spacedmonkey-script" === $handle){
		echo "<script async type='text/javascript' src='$src'></script>\n";
		return false;
	}
	return $src;
}
add_filter('script_loader_src', 'spacedmonkey_script_loader_src', 10, 2);

function spacedmonkey_template(){
	if(is_404()){
		return 'none';
	}

	if(is_search()){
		return 'search';
	}

	$format = get_post_format();
	if ( false === $format ){
		$template = get_post_type();
	}else{
		$template = $format;
	}
	return $template;	
}


function get_spacedmonkey_image($size = '', $attr = array(), $echo = true){
	global $post;
	$image = '';
	 
	if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
	  $image = get_the_post_thumbnail($post->ID,$size, $attr);
	}else{
		$images =& get_children( 'post_type=attachment&post_mime_type=image&numberposts=1&post_parent='.$post->ID );
		if(!empty($images)){
			foreach ( $images as $attachment_id => $attachment ) {
				$image = wp_get_attachment_image( $attachment_id, $size, false, $attr );
			}
		}else{
			$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
			$first_img = $matches[1][0];
			
			$width = get_option($size.'_size_w', 300);
			$height = get_option($size.'_size_h', 300);
			if(!empty($first_img)){
				$image = sprintf(__('<img src="%s" height="%s" width="%s" />'),$first_img,$height,$width);
			}
		}
		
		
	}
	if($echo){
		echo $image;
	}
	return $image;
}

function has_spacedmonkey_image($size = ''){
	$image = get_spacedmonkey_image($size, array(), false);
	$test = !empty($image);
	return $test;
}

function theme_page_title($echo = true){
		
	   $term_desc = '';
	   if(is_singular()){
		  $title = get_the_title();
	   }elseif (is_category() || is_tag() || is_tax()) { 
		  $title = single_term_title("", false);
		  if ( is_tax( 'post_format')){
			  if ( is_tax( 'post_format', 'post-format-gallery' ) ) :
                   $title = 'Galleries';
              elseif ( is_tax( 'post_format', 'post-format-status' ) ) :
                   $title = 'Statuses';
              else:
                   $title .= 's';
              endif;
		  }
		  $term_desc = term_description();
		  $term_desc = str_replace('<p>', '<p class="lead">', $term_desc);
		  
	   }  elseif (is_day()) {
		  $title = get_the_time('F jS Y');
	   } elseif (is_month()) { 
		  $title = get_the_time('F Y');
	   } elseif (is_year()) { 
		  $title = get_the_time('Y');
	   } elseif (is_author()) {
		  $title = get_the_author();
		  $desc = get_the_author_meta( 'description' );
		  if(!empty($desc)){
			  $term_desc = sprintf('<p class="lead">%s</p>',$desc);
		  }
		  
	   }  else if ( is_post_type_archive() ) {   
           $title = post_type_archive_title("",false);                
	   } else if ( is_search() ) {  
		  $title =  "Search results for ".get_search_query();
	   } else if(is_home()) { 
	   	    $page_id = get_option('page_for_posts');
	   	    $title = "Blog";
	   	    if($page_id){
		   	   $title = get_the_title($page_id);
	   	    }
	   	    	
			
	   }else if(is_404()) { 
			$title = "Page not found";
	   }

	$title = apply_filters('spacedmonkey_title', ucfirst($title));
	$theme_title = sprintf(__('<h1 class="archive-title page-title">%s</h1>', 'spacedmonkey'), $title);
	$theme_title .= $term_desc;
	
	if($echo){
		_e($theme_title, 'spacedmonkey');
		return;
	}

	return $theme_title;

}

function tweet_to_link_text($text = ''){
	// The Regular Expression filter
	$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
	
	$text = preg_replace('/([0-9|#][\x{20E3}])|[\x{00ae}|\x{00a9}|\x{203C}|\x{2047}|\x{2048}|\x{2049}|\x{3030}|\x{303D}|\x{2139}|\x{2122}|\x{3297}|\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', '', $text);
	
	// Check if there is a url in the text
	if(preg_match($reg_exUrl, $text, $url)) {
	
	       // make the urls hyper links
	       return preg_replace($reg_exUrl, "<a href='{$url[0]}'>{$url[0]}</a> ", $text);
	
	} else {
	
	       // if no urls in the text just return the text
	       return $text;
	
	}
}

function spacedmonkey_get_video($content){
	$matches = array('youtube.com','vimeo.com');
	preg_match_all('/\b(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i', $content, $results, PREG_PATTERN_ORDER);

	foreach($results[0] as $result){

		if(spacedmonkey_contains($result,$matches)){
			$video = wp_oembed_get($result);
			if( $video ){
				return $video;
			}
			
		}
	}
        return false;
}

function spacedmonkey_contains($str, array $arr)
{
    foreach($arr as $a) {
        if (stripos($str,$a) !== false) return true;
    }
    return false;
}
