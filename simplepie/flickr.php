<?php
require_once('simplepie.inc');
require_once('flickr-lightbox.inc');
 
/**
 * Set up SimplePie with all default values using shorthand syntax.
 */
$feed = new SimplePie('http://api.flickr.com/services/feeds/groups_pool.gne?id=1574695@N22&lang=en-us&format=rss');
$feed->handle_content_type();
 
/**
 * What sizes should we use?
 * Choices: square, thumb, small, medium, large.
 */
$thumb = 'square';
$full = 'medium';
 
?>
 
	<!-- Format the photos in a way that the Lightbox-clone scripts prefer. -->
	<?php
	foreach ($feed->get_items() as $item):
 
		// Set some variables to keep the rest of the code cleaner.
		$url = lightbox::find_photo($item->get_description());
		$title = lightbox::cleanup($item->get_title());
		$full_url = lightbox::photo($url, $full);
		$thumb_url = lightbox::photo($url, $thumb);
	?>
 
	<a href="<?php echo $full_url; ?>" title="<?php echo $title; ?>" rel="shadowbox">
		<img src="<?php echo $thumb_url; ?>" alt="<?php echo $title; ?>" border="0" />
	</a>
 
	<?php endforeach; ?>