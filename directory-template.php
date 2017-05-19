<?php

/*
 Template Name: Directory
 *
 * @package Minmod
 */

get_header(); ?>

	<div id="primary" class="content-area">

		<main id="main" class="site-main" role="main">

			<p> /* Insert any intro text here */ </p>


<?php

	global $wp_query;
	$args = array(
        	'status' => 'approve',
        	'order'   => 'DESC',
	);

	$count = 0;		//initialise counter
	$check = 'no';		//set author check
	$people = array();	//initalise author array to check against

	//add addresses to exclude from list (without http(s)://), include empty string
	$exclusions = array ( 'add.domain.here','' );

	$wp_query->comments = get_comments( $args );
    
	foreach ( $wp_query->comments as $comment ) {

		$wmreply = get_comment_meta( $comment->comment_ID, 'semantic_linkbacks_type', true );
		$author = get_comment_author( $comment->comment_ID );
		$author_url = get_comment_author_url();
		$parse = parse_url($author_url);
		$host = $parse['scheme'].'://'.$parse['host'];	//get just the domain portion of the URL

		//check if comment is either a webmention or webmention reply (via Semantic Linkbacks)
		if ( ($comment->comment_type == 'webmention' ) || ( $wmreply == 'reply' ) ) {

			//ensure author domain isn't excluded or name is not blank
			if (!in_array($parse['host'], $exclusions ) && ( '' != $author )) {

				//check if author has already been listed
				for ($x = 0; $x <= $count; $x++) {
    					if ( $author == $people[$x] ) {
						$check = 'yes';
					}
				} 
			
				//if author not yet listed add them to the array and display their link
				if ( $check != 'yes' ) {
					$people[$count] = $author;
					echo '<a class="directory-link" href="' . $host . '">'. $author . '</a><br/>';
				}
	
				$count += 1;
				$check = 'no';
			}

		}

	}

?>
			

		</main><!-- #main -->

	</div><!-- #primary -->

<?php get_footer(); ?>
