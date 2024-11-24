<?php
use Grafema\RouteNew;
use Grafema\Debug;
use Grafema\Post;

try {
	RouteNew::get( '/', function() {
		echo '545345';

		for ( $i = 1; $i <= 10; $i++ ) {
			Post::add( 'pages', [
				'author'  => 1,
				'title'   => "Random title with number #{$i}",
				'content' => "Random content with title of number #{$i}",
			] );
		}
	} );

	RouteNew::middleware('/movies', function() {
		// will result in '/movies/'
		RouteNew::get('/', function() {
			echo 'movies overview';
		});

		// will result in '/movies/id'
		RouteNew::get('/(\d+)', function($id) {
			echo 'movie id ' . htmlentities($id);
		});
	});

	RouteNew::set404( function() {
		header( 'HTTP/1.1 404 Not Found' );

		echo 'Page not found 404';
	} );

	RouteNew::run();
} catch ( Error $e ) {
	echo Debug::print( $e );
}
exit;