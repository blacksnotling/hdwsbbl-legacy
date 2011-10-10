<?php
//Register Sidebar as Dynamic
if ( function_exists('register_sidebar') )
	register_sidebar(array('name'=>'sidebar-posts',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));
	register_sidebar(array('name'=>'sidebar-common',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));

//Did You Know Function
function bblm_display_dyk() {
	global $wpdb;

	$dyksql = 'SELECT dyk_id, dyk_type, dyk_title, dyk_desc FROM '.$wpdb->prefix.'dyk WHERE dyk_show = 1 ORDER BY rand() LIMIT 1';
	$d = $wpdb->get_row($dyksql);
?>
		<div class="dykcontainer <?php if ($d->dyk_type) { print("dyktrivia"); } else { print("dykfact"); } ?>" id="dyk<?php print($d->dyk_id); ?>">
			<h3 class="dykheader">HDWSBBL - <?php if($d->dyk_type) { print("Did You Know"); } else { print("Fact"); } ?></h3>
<?php
				if ("none" !== $d->dyk_title) {
					print("			<h4>".$d->dyk_title."</h4>\n");
				}
?>
			<?php print(wpautop($d->dyk_desc)); ?>
			<p class="dykfooter"><a href="<?php echo home_url(); ?>/did-you-know" title="View More <?php if ($d->dyk_type) { print("Did You Knows"); } else { print("Facts"); } ?>">View More <?php if ($d->dyk_type) { print("Did You Knows"); } else { print("Facts"); } ?></a></p>
		</div>
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