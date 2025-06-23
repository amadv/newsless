<?php
// send noindex headers if any url params
$any_params = parse_url("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
//if(strlen($any_params['query']) > 0) {
if(array_key_exists('query', $any_params)) {
    header("X-Robots-Tag: noindex, nofollow", true);
}

//require_once('php/autoloader.php');
//require_once('vendor/SimplePie.compiled.php');
require_once __DIR__ . '/vendor/autoload.php';

$section="";
$loc = "US";
$lang = "en";
$feed_url="";

if(isset( $_GET['section'])) {
    $section = $_GET["section"];
}
if(isset( $_GET['loc'])) {
    $loc = strtoupper($_GET["loc"]);
}
if(isset( $_GET['lang'])) {
    $lang = $_GET["lang"];
}

if($section) {
	$feed_url="https://news.google.com/news/rss/headlines/section/topic/".strtoupper($section)."?ned=".$loc."&hl=".$lang;
} else {
	$feed_url="https://news.google.com/rss?gl=".$loc."&hl=".$lang."-".$loc."&ceid=".$loc.":".$lang;
}

//https://news.google.com/news/rss/headlines/section/topic/CATEGORYNAME?ned=in&hl=en
$feed = new SimplePie();
 
// Set the feed to process.
$feed->set_feed_url($feed_url);
 
// Run SimplePie.
$feed->init();
 
// This makes sure that the content is sent to the browser as text/html and the UTF-8 character set (since we didn't change it).
$feed->handle_content_type();

//replace chars that old machines probably can't handle
function clean_str($str) {
    $str = str_replace( "‘", "'", $str );    
    $str = str_replace( "’", "'", $str );  
    $str = str_replace( "“", '"', $str ); 
    $str = str_replace( "”", '"', $str );
    $str = str_replace( "–", '-', $str );
	$str = str_replace( '&nbsp;', ' - ', $str );

    return $str;
}
 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 2.0//EN">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<html>
<head>
	<title>min9.news: Headlines for Minimalists</title>
<style type="text/css">
* {
	margin: 0;
	padding: 0;
	font: inherit;
	color: inherit;
	box-sizing: border-box;
}
::selection { background: #ddd; color: #000 }
:root { --lh: 1.5em }
html {
	margin: 0 0 0 calc(100vw - 100%);
	-webkit-text-size-adjust: 100%;
	height: 100%;
}
body {
	font: 1em/var(--lh) monospace;
	padding: 16vh 2em 0;
	background: #eee;
	display: grid;
	grid: 1fr / minmax(auto, 64ch);
	justify-content: center;
	height: 100%;
}
a {
	display: inline-block;
	text-decoration: none;
	padding: .16666em;
	margin-left: -.16666em;
	border-bottom: 1px dotted
}
a i { border-bottom: 1px dotted }
a:active i { border: none }
footer { padding: calc(var(--lh) * 2)  0 8vh }
time { color: #888 }
h1,p { margin-bottom: var(--lh); }
table { border: none; margin-top: -.16666em }
td:first-child { padding: 0 }
td { padding: 0 3em }
tr td:last-child { padding: 0}
@media (max-device-width: 600px) {
	body { padding-top: 2em; justify-content: start }
	footer { padding: 2em 0 }
	a i { border-color: #888 }
}
li::marker {
color: #888;
}
</style>
</head>
<body>
	<center><h1><b>min9.news:</b> <font color="#9400d3"><i>Headlines from Minimalists</i></font></h1></center>
	<hr>
	<center><small>
		Simple, fast, and distraction-free news. Minimalist Google News, lovingly built by <a href="https://github.com/amadv" target="_blank"><b>amadv</b></a>.
	</small></center>
	<?php
	if($section) {
		$section_title = explode(" - ", strtoupper($feed->get_title()));
		echo "<center><h2>" . $section_title[0]  . " NEWS</h2></center>";
	}
	?>
	<small>
	<p>
	<center><a href="index.php?loc=<?php echo $loc ?>">TOP</a> <a href="index.php?section=world&loc=<?php echo strtoupper($loc) ?>">WORLD</a> <a href="index.php?section=nation&loc=<?php echo strtoupper($loc) ?>">NATION</a> <a href="index.php?section=business&loc=<?php echo strtoupper($loc) ?>">BUSINESS</a> <a href="index.php?section=technology&loc=<?php echo strtoupper($loc) ?>">TECHNOLOGY</a> <a href="index.php?section=entertainment&loc=<?php echo strtoupper($loc) ?>">ENTERTAINMENT</a> <a href="index.php?section=sports&loc=<?php echo strtoupper($loc) ?>">SPORTS</a> <a href="index.php?section=science&loc=<?php echo strtoupper($loc) ?>">SCIENCE</a> <a href="index.php?section=health&loc=<?php echo strtoupper($loc) ?>">HEALTH</a><br>
	<font size="1">-=-=-=-=-=-=-=-=-=-=-=-=-=-</font>
	<br><?php echo strtoupper($loc) ?> Edition <a href="choose_edition.php">(Change)</a></center>
	</p>
	</small>
	<ol>
	<?php
	/*
	Here, we'll loop through all of the items in the feed, and $item represents the current item in the loop.
	*/
	foreach ($feed->get_items() as $item):
	?>
		<li>
		<a href="<?php 
			// echo 'article.php?loc=' . $loc . '&a=' . $item->get_permalink();
			echo $item->get_permalink();
		?>"><?php echo clean_str($item->get_title()); ?></a>
		<p><font size="4"><?php 
            $subheadlines = clean_str($item->get_description());
            $remove_google_link = explode("<li><strong>", $subheadlines);
            $no_blank = str_replace('target="_blank"', "", $remove_google_link[0]) . "</font></p>"; 
            // $cleaned_links = str_replace('<a href="', '<a href="article.php?loc=' . $loc . '&a=', $no_blank);
            $cleaned_links = str_replace('<a href="', '<a href=', $no_blank);
			$cleaned_links = strip_tags($cleaned_links, '<a><br><p><small><font><b><strong><i><em><blockquote><h1><h2><h3><h4><h5><h6>');
    		$cleaned_links = str_replace( 'strong>', 'b>', $cleaned_links); //change <strong> to <b>
    		$cleaned_links = str_replace( 'em>', 'i>', $cleaned_links); //change <em> to <i>
			$cleaned_links = str_replace( "View Full Coverage on Google News", "", $cleaned_links);
            echo $cleaned_links;
            ?></p>
			<p><small>Posted on <?php echo $item->get_date('j F Y | g:i a'); ?></small></p>
 
	</li>
	<?php endforeach; ?>
	</ol>
	<p><center><small>v1.0 Powered by SimplePie</small><center></p>
</body>
</html>
