<?php

global $sm_data;

class SM_HOME{
	
	protected $posts = array();
	
	protected $post = null;

	protected $posts_per_page = 12;

	protected $template = '';

	protected $html = '';

	// Twitter
	protected $twitter_id = 'thespacedmonkey';

	protected $token = '21873665-DcdtUqH8I0xP565rY2MXroQMeirsZjQcylY8wKona';
	protected $token_secret = 'QJi8kT5W9IDXigSBIr2mlPKIwNlnP1X4A4uknbo26zVts';
	protected $consumer_key = 'TBWJ1kAa7sS8impKDLD9nQ';
	protected $consumer_secret = '88P5fu1pk3UoI7ay46xJDjw06ABudrcGVoW8o3KWG4';
	
	// Instragam 

	protected $access_token = '7620992.dd12277.de8bfc0fedf7439890cc922da8170966';
	protected $instagram = '7620992';

	// Youtube 

	protected $youtube_id = 'spacedmonkey1';
	protected $youtube_feed = "http://gdata.youtube.com/feeds/api/users/%s/favorites?orderby=updated&v=2&max-results=%s";

	// RSS

 	protected $feed_url = '';
	
	protected static $instance = null;
	
	protected $last_date;

     /**
      * Initialize the plugin by setting localization and loading public scripts
      * and styles.
      *
      * @since     1.0.0
      */
    private function __construct() {
    	//$this->posts_per_page = get_option('posts_per_page');
    	
		$this->get_all_feeds();
		
		$this->display_html();
		
		

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
	
	public function display_html(){
		
		echo $this->html;
	}
	
	protected function generate_html(){
		global $sm_data;
		$i = 1;
		foreach($this->posts as $each_post){
			
			$each_post_new = (array)$each_post;
			$sm_data = $each_post_new['data'];
			$this->html .= $this->load_template_part('templates/item',$each_post_new['template']);
			if(($i % 2) == 0){
				$this->html .= '<div class="visible-sm clear"></div>';
			}
			if(($i % 3) == 0){
				$this->html .= '<div class="visible-md clear"></div>';
			}
			if(($i % 4) == 0){
				$this->html .= '<div class="visible-lg clear"></div>';
			}
			$i++;
		}
	}
	
	protected function load_template_part($template_name, $part_name=null) {
			
	
	    ob_start();
	    get_template_part($template_name, $part_name);
	    $var = ob_get_contents();
	    ob_end_clean();
	    return $var;
	}

	protected function set_posts($key, $data){
		$this->posts[$key] = array('template' => $this->template, 'data' => $data);
	}
	
	protected function get_youtube_feed(){
		return sprintf($this->youtube_feed,$this->youtube_id,$this->posts_per_page);
	}

	protected function sort_posts(){
		krsort($this->posts);
	}
	
		
	public function get_all_feeds(){
	
		$cache_data = get_transient( 'sm_home' );
	
		if ( false === $cache_data ) {
	
		    $this->feed_url = $this->get_youtube_feed();
			$this->template = 'youtube';
	
			//$this->get_rss();
			
			$this->feed_url = 'http://www.jonathandavidharris.co.uk/feed';
			$this->template = 'rss';
	
			$this->get_rss();
			
			$this->get_goodreads();
			$this->get_instagram();
			$this->get_twitter();
			$this->get_posts();
			
			$this->sort_posts();
			
			$this->generate_html();
			
			$this->set_cache();
			
		}else{
			$this->html = $cache_data;
			
		}
	}
	
	public function set_cache(){
		set_transient( 'sm_home', $this->html, HOUR_IN_SECONDS / 4 );
	}

	protected function get_posts(){
		$args = array( 'posts_per_page' => $this->posts_per_page, 'post_type' => array('post','archive'));
	
		query_posts( $args );

		// The Loop
		while ( have_posts() ) : the_post();
		   $mypost = get_post(get_the_ID());
		   $this->template = get_post_format($mypost);
		   $this->set_posts(get_the_date("U"),$mypost);
		   $this->last_date = get_the_date("U");
		endwhile;

		// Reset Query
		wp_reset_query();

	}

	protected function get_rss(){
		// Get RSS Feed(s)
		include_once( ABSPATH . WPINC . '/feed.php' );
		
		// Get a SimplePie feed ob
	 $rss = fetch_feed( $this->feed_url );
		
		if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly
		
		    // Figure out how many total items there are, but limit it to 5. 
		    $maxitems = $rss->get_item_quantity( $this->posts_per_page ); 
		
		    // Build an array of all the items, starting with element 0 (first element).
		    $rss_items = $rss->get_items( 0, $maxitems );
		
		endif;
		if ( $maxitems == 0 ) : 
		else :
		// Loop through each feed item and display each item as a hyperlink. 
        	  foreach ( $rss_items as $item ) : 
        			$this->set_posts($item->get_date('U'),$item);
        	  endforeach; 
        endif; 
	}
	
	function fetchData($url){
	  $result = wp_remote_get( $url, array( 'timeout' => 120, 'httpversion' => '1.1' ) );
	  if ( is_wp_error($result) || ! isset($result['body']) )
	  	return '';

	  return $result['body'];
  	}
	
	private function get( $Url ) {
		
		try{
			$response = wp_remote_get($Url, array( 'timeout' => '20' ));
	        $content_body = wp_remote_retrieve_body($response);
	        $body = json_encode( simplexml_load_string( $content_body ) );
	        $body = json_decode( $body, true );
	        return $body;
		}catch (Exception $e) {
			return '';
		}
        
    }
	
	protected function get_goodreads(){
	  $this->template = 'goodreads';

	  $result = $this->get("https://www.goodreads.com/review/list/11831025.xml?key=ZYTkEx6vIydGFWMTldLYEQ&v=2&per_page=".$this->posts_per_page);
	
	  foreach($result['reviews']['review'] as $book){
			$this->set_posts(strtotime($book['date_added']),$book);
		 }
	}
	
	protected function get_instagram(){
	  $this->template = 'instagram';

	  $result = $this->fetchData("https://api.instagram.com/v1/users/".$this->instagram."/media/recent/?access_token=".$this->access_token);
	  $result = json_decode($result);
	  $i = 0;
	  foreach ($result->data as $post_data) {
	  	$this->set_posts($post_data->created_time,$post_data);
	  	$i++;
	  	if($i > $this->posts_per_page)
	  		break;
	  }
	}

	private function get_twitter(){
	
		$this->template = 'twitter';
		
		$token = $this->token;
		$token_secret = $this->token_secret;
		$consumer_key = $this->consumer_key;
		$consumer_secret = $this->consumer_secret;
		
		$host = 'api.twitter.com';
		$method = 'GET';
		$path = '/1.1/statuses/user_timeline.json'; // api call path
		
		$query = array( // query parameters
		    'screen_name' => $this->twitter_id,
		    'count' => $this->posts_per_page
		);
		
		$oauth = array(
		    'oauth_consumer_key' => $consumer_key,
		    'oauth_token' => $token,
		    'oauth_nonce' => (string)mt_rand(), // a stronger nonce is recommended
		    'oauth_timestamp' => time(),
		    'oauth_signature_method' => 'HMAC-SHA1',
		    'oauth_version' => '1.0'
		);
		
		$oauth = array_map("rawurlencode", $oauth); // must be encoded before sorting
		$query = array_map("rawurlencode", $query);
		
		$arr = array_merge($oauth, $query); // combine the values THEN sort
		
		asort($arr); // secondary sort (value)
		ksort($arr); // primary sort (key)
		
		// http_build_query automatically encodes, but our parameters
		// are already encoded, and must be by this point, so we undo
		// the encoding step
		$querystring = urldecode(http_build_query($arr, '', '&'));
		
		$url = "https://$host$path";
		
		// mash everything together for the text to hash
		$base_string = $method."&".rawurlencode($url)."&".rawurlencode($querystring);
		
		// same with the key
		$key = rawurlencode($consumer_secret)."&".rawurlencode($token_secret);
		
		// generate the hash
		$signature = rawurlencode(base64_encode(hash_hmac('sha1', $base_string, $key, true)));
		
		// this time we're using a normal GET query, and we're only encoding the query params
		// (without the oauth params)
		$url .= "?".http_build_query($query);
		
		$oauth['oauth_signature'] = $signature; // don't want to abandon all that work!
		ksort($oauth); // probably not necessary, but twitter's demo does it
		
		// also not necessary, but twitter's demo does this too
		function add_quotes($str) { return '"'.$str.'"'; }
		$oauth = array_map("add_quotes", $oauth);
		
		// this is the full value of the Authorization line
		$auth = "OAuth " . urldecode(http_build_query($oauth, '', ', '));
		
		// if you're doing post, you need to skip the GET building above
		// and instead supply query parameters to CURLOPT_POSTFIELDS
		$options = array( CURLOPT_HTTPHEADER => array("Authorization: $auth"),
		                  //CURLOPT_POSTFIELDS => $postfields,
		                  CURLOPT_HEADER => false,
		                  CURLOPT_URL => $url,
		                  CURLOPT_RETURNTRANSFER => true,
		                  CURLOPT_SSL_VERIFYPEER => false);
		
		// do our business
		$feed = curl_init();
		curl_setopt_array($feed, $options);
		$json = curl_exec($feed);
		curl_close($feed);
		
		$twitter_data = json_decode($json);
		
		$tweets = array();
		foreach($twitter_data as $data){
			$this->set_posts(strtotime($data->created_at),$data);
		}
	}
	
	public function remove_emoji($text){
	  return preg_replace('/([0-9|#][\x{20E3}])|[\x{00ae}|\x{00a9}|\x{203C}|\x{2047}|\x{2048}|\x{2049}|\x{3030}|\x{303D}|\x{2139}|\x{2122}|\x{3297}|\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', '', $text);
	}
	
}


add_action('spacedmonkey_home', array('SM_HOME','get_instance'));
?>