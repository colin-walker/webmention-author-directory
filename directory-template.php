<?php

/*
 Template Name: Directory
 */

get_header(); ?>

	<div id="primary" class="content-area">

		<main id="main" class="site-main" role="main">

			<p> /* Insert introduction text of your choice here */ </p>


<?php

	global $wp_query;
	$args = array(
        	'status' => 'approve',
        	'order'   => 'DESC',
		'type' 	=> 'webmention'
	);

	$i = 0;			//initialise counter
	$check = 'no';		//set author check
	$people = array();	//initalise author array to check against

	//add addresses to exclude from list host portion of URL only, include empty string
	$exclusions = array ( 'add.domain.here', '' );		

	$wp_query->comments = get_comments( $args );
    
	foreach ( $wp_query->comments as $comment ) {
		$comment_id = get_comment_ID();
		$author = get_comment_author( $comment->comment_ID );
		$author_url = get_comment_author_url();
		$parse = parse_url($author_url);
		$host = $parse['scheme'].'://'.$parse['host'];	//get just the domain portion of the URL

		//ensure author domain isn't excluded or name is not blank
		if (!in_array($parse['host'], $exclusions ) && ( '' != $author )) {

			//check if author has already been listed
			for ($x = 0; $x <= $i; $x++) {
    				if ( $author == $people[$x] ) {
					$check = 'yes';
				}
			} 
			
			//if author not yet listed add them to the array and display their link
			if ( $check != 'yes' ) {
				$people[$i] = $author;
				echo '<a href="' . $host . '">'. $author . '</a><br/>';
			}
		$i += 1;
		$check = 'no';
		}
	}

?>
			

		</main><!-- #main -->

	</div><!-- #primary -->

<?php get_footer(); ?>
