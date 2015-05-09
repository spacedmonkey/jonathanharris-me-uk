<?php



class Spacedmonkey {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 *
	 * Unique identifier for your plugin.
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
	protected $plugin_slug = 'spacedmonkey';

	/**
	 *
	 * Post type
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $post_type = 'archive';


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


		/* Define custom functionality.
		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
		//add_action( 'wp_enqueue_scripts', array( $this,'current_jquery') );
		
		
		add_shortcode('workoutDate', array( $this,'workoutDate'));
		
		add_filter( "getarchives_where",array( $this,"node_custom_post_type_archive"),10,2);
		add_filter('the_generator', '__return_empty_string');
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
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
	
	
	function workoutDate ($atts,$content){
                extract(shortcode_atts(array(
                           'date' => '2000-01-01'                    
                ), $atts));
	    list($year,$month,$day) = explode("-",$date);
	    $year_diff  = date("Y") - $year;
	    $month_diff = date("m") - $month;
	    $day_diff   = date("d") - $day;
	    if ($day_diff < 0 || $month_diff < 0)
	      $year_diff--;
	    return $year_diff;
	}


	
	public function register_post_type() {

	    $labels = array( 
	        'name' => _x( 'Archive Posts', 'archive' ),
	        'singular_name' => _x( 'Archive', 'archive' ),
	        'add_new' => _x( 'Add New', 'archive' ),
	        'add_new_item' => _x( 'Add New archive post', 'archive' ),
	        'edit_item' => _x( 'Edit archive post', 'archive' ),
	        'new_item' => _x( 'New archive post', 'archive' ),
	        'view_item' => _x( 'View archive post', 'archive' ),
	        'search_items' => _x( 'Search archive posts', 'archive' ),
	        'not_found' => _x( 'No archive posts found', 'archive' ),
	        'not_found_in_trash' => _x( 'No archive posts found in Trash', 'archive' ),
	        'parent_item_colon' => _x( 'Parent archive:', 'archive' ),
	        'menu_name' => _x( 'Archive', 'archive' ),
	    );
	
	    $args = array( 
	        'labels' => $labels,
	        'hierarchical' => false,
	        
	        'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'post-formats'  ),
	        'taxonomies' => array( 'category', 'post_tag', 'page-category' , 'publicize', 'wpcom-markdown' ),
	        'public' => true,
	        'show_ui' => true,
	        'show_in_menu' => true,
	        'menu_position' => 5,
	        
	        'show_in_nav_menus' => true,
	        'publicly_queryable' => true,
	        'exclude_from_search' => false,
	        'has_archive' => true,
	        'query_var' => true,
	        'can_export' => true,
	        'rewrite' => true,
	        'menu_icon' => 'dashicons-portfolio',
	        'capability_type' => 'post'
	    );
	
	    register_post_type( $this->post_type, $args );
	}
	
	public function pre_get_posts( $query ) {
	
	    if ( !is_admin() && ($query->is_archive() || $query->is_home()) && $query->is_main_query() && !$query->is_post_type_archive() ) {
	        $query->set('post_type', array( 'post', $this->post_type ) );
	        
	    }
	}
	
	public function node_custom_post_type_archive($where, $args){
			$where = 'WHERE post_type IN("post","'.$this->post_type.'") AND post_status = "publish"';
			return $where;  
	}
	 


}



Spacedmonkey::get_instance();

