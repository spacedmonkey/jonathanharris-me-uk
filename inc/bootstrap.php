<?php

function spacedmonkey_pages($old){
	$old['before'] = '<ul class="pagination"><li class="disabled"><span>Pages: </span></li>';
	$old['after'] = '</ul>';
	$old['link_before'] = '<span class="">';
	$old['link_after'] = '</span>';
	return $old;
}

add_filter( 'wp_link_pages_args', 'spacedmonkey_pages' );

function spacedmonkey_pages_link($old){
	$test = (strpos($old,'<a') !== false) ? '' : 'active';
	$new = "<li class='$test'>$old</li>";	
	return $new;
}

add_filter( 'wp_link_pages_link', 'spacedmonkey_pages_link' );

add_filter( 'the_password_form', 'custom_password_form' );
function custom_password_form() {
	global $post;
	$label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
	$o = '<form class="protected-post-form" action="' . home_url('/wp-login.php?action=postpass') . '" method="post" role="form">
	' . __( "This content is password protected. To view it please enter your password below:" ) . '
	<div class="form-group">
		<label class="pass-label" for="' . $label . '">' . __( "Password:" ) . ' </label>
		<input name="post_password" id="' . $label . '" class="form-control" type="password" size="20" />
	</div>
	<div class="btn-group">
		<input type="submit" name="Submit" class="button btn btn-primary" value="' . esc_attr__( "Submit" ) . '" />
	</div>
	</form>
	';
	return $o;
}

function add_some_classes($content){

	$content = preg_replace('/<table(.*?)>(.*?)<\/table>/is', "<table class=\"table table-bordered\">$2</table>", $content);
	//$content = preg_replace('/<img(.*?) class="(.*?)/is', "<img $1 class=\"img-thumbnail $2", $content);
	$content = str_replace("q>", "blockquote>", $content);
	$content = str_replace("b>", "strong>", $content);
	return $content;
}
add_filter( 'the_content', 'add_some_classes', 1, 100);

function spacedmonkey_comment_form(){
	ob_start();
	
	$current_user = wp_get_current_user();
	$user_identity = $current_user->display_name;
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " required='required' aria-required='true' " : '' );
	$required_text = '<span class="required">*</span>';
	$args = array(

  'comment_field' =>  '<div class="comment-form-comment form-group"><label for="comment">' . _x( 'Comment', 'noun' ) .
    '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" required="required" class="form-control">' .
    '</textarea></div>',

  'must_log_in' => '<p class="must-log-in text-primary">' .
    sprintf(
      __( 'You must be <a href="%s" class="btn btn-primary btn-sm">logged in</a> to post a comment.' ),
      wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
    ) . '</p>',

  'logged_in_as' => '<p class="logged-in-as">' .
    sprintf(
    __( 'Logged in as <a href="%1$s">%2$s</a>.</p> <a href="%3$s" title="Log out of this account" class="btn btn-danger btn-sm">Log out?</a>' ),
      admin_url( 'profile.php' ),
      $user_identity,
      wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
    ) . '',

  'comment_notes_before' => '<p class="comment-notes text-muted">' .
    __( 'Your email address will not be published.' ) . ( $req ? $required_text : '' ) .
    '</p>',

  'comment_notes_after' => '<p class="text-muted form-allowed-tags">' .
    sprintf(
      __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ),
      ' <code>' . allowed_tags() . '</code>'
    ) . '</p>',
'cancel_reply_link' => '<button class="btn btn-danger btn-sm">Cancel reply</button>',
  'fields' => apply_filters( 'comment_form_default_fields', array(

    'author' =>
      '<div class="comment-form-author form-group">' .
      '<label for="author">' . __( 'Name', 'domainreference' ) . '</label> ' .
      ( $req ? '<span class="required">*</span>' : '' ) .
      '<input id="author" name="author" type="text" class="form-control" value="' . esc_attr( $commenter['comment_author'] ) .
      '" size="30"' . $aria_req . ' /></div>',

    'email' =>
      '<div class="comment-form-email form-group"><label for="email">' . __( 'Email', 'domainreference' ) . '</label> ' .
      ( $req ? '<span class="required">*</span>' : '' ) .
      '<input id="email" name="email" type="email" class="form-control" value="' . esc_attr(  $commenter['comment_author_email'] ) .
      '" size="30"' . $aria_req . ' /></div>',

    'url' =>
      '<div class="comment-form-url form-group"><label for="url">' .
      __( 'Website', 'domainreference' ) . '</label>' .
      '<input id="url" name="url" type="url" class="form-control" value="' . esc_attr( $commenter['comment_author_url'] ) .
      '" size="30" /></div>'
    )
  ),
);
	
	

	comment_form( $args );
	$form = ob_get_clean(); 
	$form = str_replace('class="comment-form"','class="comment-form"', $form);
	$form = str_replace('novalidate','', $form);
	
	echo str_replace('id="submit"','data-loading-text="Loading..." id="submit" class="btn btn-primary"', $form);
}

function spacedmonkey_dynamic_sidebar($sidebar){
	ob_start();
	dynamic_sidebar( $sidebar );
	$sidebar_output = ob_get_clean(); 
	$sidebar_output = str_replace('type="submit"','type="submit" class="btn btn-primary"', $sidebar_output);
	$sidebar_output = str_replace('grofile-full-link','grofile-full-link btn btn-primary', $sidebar_output);
	$sidebar_output = str_replace('grofile-thumbnail','grofile-thumbnail img-circle center-block', $sidebar_output);
	
	$sidebar_output = str_replace('type="text"','type="text" class="form-control"', $sidebar_output);
	$sidebar_output = str_replace('<ul','<ul class="list-group"', $sidebar_output);
	$sidebar_output = str_replace('<li>','<li class="list-group-item">', $sidebar_output);
	$sidebar_output = preg_replace('/<li(.*?)>(.*?)<\/li>/is', "<li class=\"list-group-item\">$2</li>", $sidebar_output);
	$sidebar_output = preg_replace('/\((\d+)\)/is', "<span class='badge'>$1</span>", $sidebar_output);
	echo $sidebar_output;
}


add_filter('edit_comment_link', 'replace_reply_link_class');
add_filter('comment_reply_link', 'replace_reply_link_class');
add_filter('edit_post_link', 'replace_reply_link_class');


function replace_reply_link_class($class){
    $class = str_replace("class='", "class='btn btn-primary btn-sm ", $class);
    $class = str_replace('class="', 'class="btn btn-primary btn-sm ', $class);
     $class = str_replace('class', ' data-loading-text="Loading..." class', $class);
    
    return $class;
}
add_filter( 'wpcf7_form_novalidate',create_function('','return "";'));

function replace_contact_form_field_2($html){

	$html = str_replace("wpcf7-response-output", "wpcf7-response-output alert", $html);
	$html = str_replace("wpcf7-display-none", "hide", $html);
    $html = str_replace("wpcf7-validation-errors", "alert-danger", $html);
    $html = str_replace("wpcf7-spam-blocked", "alert-warning", $html);
    $html = str_replace(array('wpcf7-mail-sent-ok','wpcf7-mail-sent-ng'), "alert-success", $html);
    
    return $html;
}

add_filter( 'wpcf7_form_response_output', 'replace_contact_form_field_2', 20, 1);

function replace_contact_form_field_3($html){
	$html = str_replace("<span", "<div", $html);
	$html = str_replace("span>", "div>", $html);
    $html = str_replace("wpcf7-not-valid-tip", "wpcf7-not-valid-tip text-danger", $html);
    
    return $html;
}

add_filter( 'wpcf7_validation_error', 'replace_contact_form_field_3', 20, 1);



function change_form($content){
	$content = str_replace('wpcf7-form-control wpcf7-submit', 'wpcf7-submit btn btn-primary', $content);
	
	$content = str_replace('wpcf7-form-control-wrap', 'form-group', $content);
	$content = str_replace('wpcf7-form-control', 'wpcf7-form-control form-control', $content);
	$content = str_replace("<span", "<div", $content);
	$content = str_replace("span>", "div>", $content);
	return $content;
}
add_filter( 'wpcf7_form_elements', 'change_form', 1, 100);