<?php

/** Tell WordPress to run oberwald_theme_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'oberwald_theme_setup' );

function oberwald_theme_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	//add_editor_style();

	// This theme uses post thumbnails
	//add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'oberwald' ),
	) );
}

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 *
 */
function oberwald_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
};
add_filter( 'wp_page_menu_args', 'oberwald_page_menu_args' );

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @return string "Continue Reading" link
 */
function oberwald_continue_reading_link() {
	return '<p class="readmorelink">Continue reading <a href="'. get_permalink() . '">'.get_the_title().' &raquo;</a></p>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and twentyten_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @return string An ellipsis
 */
function oberwald_auto_excerpt_more( $more ) {
	return ' &hellip;' . oberwald_continue_reading_link();
}
add_filter( 'excerpt_more', 'oberwald_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function oberwald_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= oberwald_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'oberwald_custom_excerpt_more' );

/**
 * Register widgetized areas,
 *
 * To override oberwald_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @uses register_sidebar
 */
 function oberwald_widgets_init() {
	register_sidebar(array(
		'name'=> __( 'sidebar-posts', 'oberwald' ),
		'description' => __( 'Appears on most pages in the sidebar area', 'oberwald' ),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));
	register_sidebar(array(
		'name'=> __( 'sidebar-common', 'oberwald' ),
		'description' => __( 'Appears on ALL Pages in the sidebar area', 'oberwald' ),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));
	register_sidebar(array(
		'name'=> __( 'post-bottom', 'oberwald' ),
		'description' => __( 'Appears at the bottom of full posts', 'oberwald' ),
        'before_widget' => '<div id="%1$s" class="post-bottom-dynamic %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
	register_sidebar(array(
		'name'=> __( 'maincontent-bottom', 'oberwald' ),
		'description' => __( 'Appears at the bottom of the maincontent - above the footer', 'oberwald' ),
        'before_widget' => '<div id="%1$s" class="content-bottom-dynamic %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
}

/** Register sidebars by running bblm_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'oberwald_widgets_init' );

if ( ! function_exists( 'oberwald_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post date/time and author.
 *
 */
function oberwald_posted_on() {
	printf( __( 'Posted on %1$s', 'oberwald' ),
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		)
	);
}
endif;

if ( ! function_exists( 'bblm_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 */
function oberwald_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	$team_list = get_the_term_list( $post->ID, 'post_teams', '', ', ', '' );
	$comp_list = get_the_term_list( $post->ID, 'post_competitions', '', ', ', '' );
	if ( $tag_list && $team_list && $comp_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. It mentions %5$s in the %6$s. &lt;<a href="%3$s" title="Permalink to %4$s" rel="bookmark">Permalink</a>&gt;.', 'oberwald' );
	} else if ( $tag_list && $team_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. It mentions %5$s. &lt;<a href="%3$s" title="Permalink to %4$s" rel="bookmark">Permalink</a>&gt;.', 'oberwald' );
	} else if ( $tag_list && $comp_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. It discusses the %6$s. &lt;<a href="%3$s" title="Permalink to %4$s" rel="bookmark">Permalink</a>&gt;.', 'oberwald' );
	} else if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. &lt;<a href="%3$s" title="Permalink to %4$s" rel="bookmark">Permalink</a>&gt;.', 'oberwald' );
	} else if ( $comp_list && $team_list ) {
		$posted_in = __( 'This entry was posted in %1$s. It mentions %5$s in the %6$s. &lt;<a href="%3$s" title="Permalink to %4$s" rel="bookmark">Permalink</a>&gt;.', 'oberwald' );
	} else if ( $team_list ) {
		$posted_in = __( 'This entry was posted in %1$s. It mentions %5$s. &lt;<a href="%3$s" title="Permalink to %4$s" rel="bookmark">Permalink</a>&gt;.', 'oberwald' );
	} else if ( $comp_list ) {
		$posted_in = __( 'This entry was posted in %1$s. It discusses the %6$s. &lt;<a href="%3$s" title="Permalink to %4$s" rel="bookmark">Permalink</a>&gt;.', 'oberwald' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. &lt;<a href="%3$s" title="Permalink to %4$s" rel="bookmark">Permalink</a>&gt;.', 'oberwald' );
	} else {
		$posted_in = __( '&lt;<a href="%3$s" title="Permalink to %4$s" rel="bookmark">Permalink</a>&gt;.', 'oberwald' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' ),
		$team_list,
		$comp_list
	);
}
endif;

function oberwald_breadcrumb_root() {
	//displays the initial tezt for all breadcrumb links
?>
<a href="<?php echo home_url(); ?>" title="Back to the front of the HDWSBBL">HDWSBBL</a> &raquo;
<?php
}

function TimeAgoInWords($from_time, $include_seconds = false) {
//http://yoast.com/wordpress-functions-supercharge-theme/
  $to_time = time();
  $mindist = round(abs($to_time - $from_time) / 60);
  $secdist = round(abs($to_time - $from_time));

  if ($mindist >= 0 and $mindist <= 1) {
    if (!$include_seconds) {
      return ($mindist == 0) ? 'under a minute' : '1 minute';
	} else {
      if ($secdist >= 0 and $secdist <= 4) {
        return 'less than 5 seconds';
      } elseif ($secdist >= 5 and $secdist <= 9) {
        return 'less than 10 seconds';
      } elseif ($secdist >= 10 and $secdist <= 19) {
        return 'less than 20 seconds';
      } elseif ($secdist >= 20 and $secdist <= 39) {
        return 'half a minute';
      } elseif ($secdist >= 40 and $secdist <= 59) {
        return 'less than a minute';
      } else {
        return '1 minute';
      }
    }
  } elseif ($mindist >= 2 and $mindist <= 44) {
    return $mindist . ' minutes';
  } elseif ($mindist >= 45 and $mindist <= 89) {
    return '1 hour';
  } elseif ($mindist >= 90 and $mindist <= 1439) {
    return round(floatval($mindist) / 60.0) . ' hours';
  } elseif ($mindist >= 1440 and $mindist <= 2879) {
    return '1 day';
  } elseif ($mindist >= 2880 and $mindist <= 43199) {
    return round(floatval($mindist) / 1440) . ' days';
  } elseif ($mindist >= 43200 and $mindist <= 86399) {
    return '1 month';
  } elseif ($mindist >= 86400 and $mindist <= 525599) {
    return round(floatval($mindist) / 43200) . ' months';
  } elseif ($mindist >= 525600 and $mindist <= 1051199) {
    return '1 year ish';
  } else {
    return 'over ' . round(floatval($mindist) / 525600) . ' years';
  }
}

function in_array_recursive($needle, $haystack) {

    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($haystack));

    foreach($it AS $element) {
        if($element == $needle) {
            return true;
        }
    }

    return false;
}

// Custom callback to list comments in the your-theme style
// based off http://themeshaper.com/wordpress-theme-comments-template-tutorial/
function custom_comments($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment;
        $GLOBALS['comment_depth'] = $depth;
  ?>
        <li id="comment-<?php comment_ID() ?>" <?php comment_class() ?>>
                <div class="comment-author vcard"><?php commenter_link() ?></div>
                <div class="comment-meta"><?php printf(__('Posted %1$s at %2$s <span class="meta-sep">|</span> <a href="%3$s" title="Permalink to this comment">Permalink</a>', 'your-theme'),
                                        get_comment_date(),
                                        get_comment_time(),
                                        '#comment-' . get_comment_ID() );
                                        edit_comment_link(__('Edit', 'your-theme'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?></div>

	       		<div class="comment-content">
	                	<?php comment_text() ?>
	                </div>

                <?php // echo the comment reply link
                        if($args['type'] == 'all' || get_comment_type() == 'comment') :
                                comment_reply_link(array_merge($args, array(
                                        'reply_text' => __('Reply','your-theme'),
                                        'login_text' => __('Log in to reply.','your-theme'),
                                        'depth' => $depth,
                                        'before' => '<div class="comment-reply-link">',
                                        'after' => '</div>'
                                )));
                        endif;
?>

  				<?php if ($comment->comment_approved == '0') _e("\t\t\t\t\t<span class='info'>Your comment is awaiting moderation.</span>\n", 'your-theme') ?>



<?php } // end custom_comments

// Produces an avatar image with the hCard-compliant photo class
//http://themeshaper.com/wordpress-theme-comments-template-tutorial/
function commenter_link() {
        $commenter = get_comment_author_link();
        if ( ereg( '<a[^>]* class=[^>]+>', $commenter ) ) {
                $commenter = ereg_replace( '(<a[^>]* class=[\'"]?)', '\\1url ' , $commenter );
        } else {
                $commenter = ereg_replace( '(<a )/', '\\1class="url "' , $commenter );
        }
        $avatar_email = get_comment_author_email();
        $avatar = str_replace( "class='avatar", "class='photo avatar", get_avatar( $avatar_email, 38 ) );
        echo $avatar . ' <span class="fn n">' . $commenter . '</span>';
} // end commenter_link
?>