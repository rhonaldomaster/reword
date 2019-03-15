<?php
// Autoload includes
foreach ( glob( __DIR__ . '/includes/*.php' ) as $module ) {
	if ( ! $modulepath = $module ) {
		trigger_error( sprintf( __( 'Error locating %s for inclusion', 'reword' ), $module ), E_USER_ERROR );
	}
	require_once( $modulepath );
}
unset( $module, $filepath );

function add_actions() {
	add_action( 'rest_api_init', 'register_theme_routes' );
	if ( ! is_admin() ) {
		add_action( 'wp_enqueue_scripts', 'load_assets' );
	}
}

function load_assets() {
	wp_enqueue_style( 'theme-style', asset_path( 'main.css' ), false, null );
	wp_enqueue_script( 'webpack', asset_path('runtime~main.js'), ['jquery'], null, true );
	wp_enqueue_script( 'chunk', asset_path('/static\/js\/\d\.(\w+)\.chunk\.js/'), ['webpack'], null, true );
	wp_enqueue_script( 'app-script', asset_path('main.js'), ['chunk'], null, true );
}

function asset_path( $filename ) {
	$dist_path = get_stylesheet_directory_uri() . '/build';
	static $manifest;
	if (empty($manifest)) {
		$manifest_path = get_stylesheet_directory() . '/build/' . 'asset-manifest.json';
		$manifest = new KoombeaAssets($manifest_path);
	}
	if (array_key_exists($filename, $manifest->get())) {
		return $dist_path . $manifest->get()[$filename];
	} else {
		foreach ( array_keys( $manifest->get() ) as $k ) {
			if ( preg_match( $filename , $k, $matches ) ) {
				return $dist_path . $manifest->get()[$matches[0]];
			}
		}
	}
	return $dist_path . $filename;
}


function register_theme_routes() {
	register_rest_route('api/v1', '/get-blog-posts/',
		array(
			'methods' => 'GET',
			'callback' => 'get_blog_posts',
		));
	register_rest_route('api/v1', '/get-site-info/',
		array(
			'methods' => 'GET',
			'callback' => 'get_site_info',
		));
}

function trunc( $phrase, $max_words ) {
	$phrase_array = explode( ' ', $phrase );
	if( count( $phrase_array ) > $max_words && $max_words > 0 ) {
		$phrase = implode( ' ' ,array_slice( $phrase_array, 0, $max_words ) ). '...';
	}
	return trim( $phrase );
}

function generate_post_excerpt( $post_content, $words = 50 ) {
	$text = strip_shortcodes( $post_content );
	$text = apply_filters( 'the_content', $text );
	$text = str_replace(']]>', ']]&gt;', $text);
	$excerpt_length = apply_filters( 'excerpt_length', $words );
	$excerpt_more = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
	$text = wp_trim_words( $text, $excerpt_length, $excerpt_more );
	return $text;
}

function get_posts_json( $posts ) {
	$json = '{"posts": [';
	$post_string = '';
	foreach ($posts as $key => $post) {
		if ( strcasecmp( $post_string, '' ) !== 0 ) {
			$post_string .= ',';
		}
		$bg_image = get_the_post_thumbnail_url( $post->ID );
		$post_tags = get_the_tags( $post->ID );
		$tag = $post_tags ? $post_tags[0]->name : '';
		$avatar = get_avatar( $post->post_author );
		$excerpt = ( strcasecmp( $post->post_excerpt, '' ) !== 0 ? $post->post_excerpt : generate_post_excerpt( $post->post_content, 20 ) );
		$post_string .= '{' .
			'"image": "' . $bg_image . '", "tag": "' . $tag . '", "authorAvatar": "' . $avatar . '", ' .
			'"title": "' . $post->post_title . '", "link": "' . get_permalink( $post->ID ) . '", "date": "' . $post->post_date . '", ' .
			'"author": "' . get_the_author_meta( 'display_name', $post->post_author ) . '", "shortTitle": "' . trunc( $post->post_title, 8 ) . '",' .
			'"excerpt": "' . $excerpt . '"' .
			'}';
	}
	$json .= $post_string . ']}';
	$headers['Cache-Control'] = 'public, max-age=3600';
	return $json;
}

function get_blog_posts() {
	$args = array(
		'post_type' => 'post',
		'posts_per_page' => -1,
		'numberposts' => get_option( 'posts_per_page' )
	);
	$posts = get_posts( $args );
	$json = get_posts_json( $posts );
	ob_start('ob_gzhandler');
	return rest_ensure_response( json_decode( $json ) );
}

function get_site_info() {
	$info = array (
		'url' => home_url(),
		'home_url' => home_url(),
		'site_url' => site_url(),
		'rdf' => get_bloginfo('rdf_url'),
		'rss' => get_bloginfo('rss_url'),
		'rss2' => get_bloginfo('rss2_url'),
		'atom' => get_bloginfo('atom_url'),
		'language' => get_bloginfo('language'),
		'charset' => get_bloginfo('charset'),
		'pingback' => get_bloginfo('pingback_url')
	);
	return rest_ensure_response( json_decode( json_encode( $info ) ) );
}

add_actions();
