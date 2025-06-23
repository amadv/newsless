<?php
error_reporting(E_ERROR | E_PARSE);
header("X-Robots-Tag: noindex, nofollow", true);
require_once('vendor/autoload.php');

use andreskrey\Readability\Readability;
use andreskrey\Readability\Configuration;
use andreskrey\Readability\ParseException;

$article_url = "";
$article_html = "";
$error_text = "";
$loc = "US";

if (isset($_GET['loc'])) {
    $loc = strtoupper($_GET["loc"]);
}

if (isset($_GET['a'])) {
    $article_url = $_GET["a"];
} else {
    echo "What do you think you're doing... >:(";
    exit();
}

if (substr($article_url, 0, 23) !== "https://news.google.com") {
    echo("That's not news :(");
    die();
}

// Resolve redirect to actual article URL
$article_url = resolve_redirect_url($article_url);

if (empty($article_url)) {
    $error_text .= "Couldn't resolve redirect from Google News.<br>No valid article URL to fetch.<br>";
}

// Initialize Readability
$configuration = new Configuration();
$configuration->setArticleByLine(false);
$readability = new Readability($configuration);

// Try to fetch and parse the article
// if ($article_url && $article_html = @file_get_contents($article_url)) {
if ($article_url && $article_html = fetch_html($article_url)) {
	file_put_contents('debug.html', $article_html);
	error_log("Fetched article URL: $article_url");
	error_log("Saved article body to debug.html");
    try {
        $readability->parse($article_html);
	try {
    		if (!$readability->parse($article_html)) {
        	throw new Exception("Invalid or incomplete HTML.");
    	}
    	// continue as before...
	} catch (Exception | ParseException $e) {
    		file_put_contents('debug_failed.html', $article_html); // for debug
    		$error_text .= 'Sorry - working on it! ' . $e->getMessage() . '<br>';
	}
	
	$readable_article = strip_tags(
            $readability->getContent(),
            '<ol><ul><li><br><p><small><font><b><strong><i><em><blockquote><h1><h2><h3><h4><h5><h6>'
        );

        // Normalize tags
        $readable_article = str_replace('strong>', 'b>', $readable_article);
        $readable_article = str_replace('em>', 'i>', $readable_article);
        $readable_article = clean_str($readable_article);

    } catch (ParseException $e) {
        $error_text .= 'Sorry - working on it! ' . $e->getMessage() . '<br>';
    }
} else {
    $error_text .= "Failed to get the article :( <br>";
}

function fetch_html($url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; 68k-newsbot/1.0)',
        CURLOPT_TIMEOUT => 10,
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ($http_code >= 200 && $http_code < 300) ? $response : '';
}

function clean_str($str) {
    $str = str_replace("‘", "'", $str);
    $str = str_replace("’", "'", $str);
    $str = str_replace("“", '"', $str);
    $str = str_replace("”", '"', $str);
    $str = str_replace("–", '-', $str);
    return $str;
}

function resolve_redirect_url($url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_NOBODY => true,
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_TIMEOUT => 5,
    ]);

    curl_exec($ch);
    $redirected_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);

    return $redirected_url ?: '';
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 2.0//EN">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<html>
<head>
    <title><?php echo $readability->getTitle() ?? "68k.news"; ?></title>
</head>
<body>
    <small><a href="/index.php?loc=<?php echo $loc ?>">&lt; Back to <font color="#9400d3">68k.news</font> <?php echo $loc ?> front page</a></small>

    <?php if (!empty($readable_article)): ?>
        <h1><?php echo clean_str($readability->getTitle()); ?></h1>
        <p><small>
            <a href="<?php echo $article_url ?>" target="_blank">Original source</a> (on modern site)
            <?php
            $img_num = 0;
            $imgline_html = "| Article images:";
            foreach ($readability->getImages() as $image_url):
                if (preg_match('/\.(jpg|jpeg|png)$/i', $image_url)) {
                    $img_num++;
                    $imgline_html .= " <a href='image.php?loc=" . $loc . "&i=" . $image_url . "'>[$img_num]</a> ";
                }
            endforeach;
            if ($img_num > 0) {
                echo $imgline_html;
            }
            ?>
        </small></p>
        <p><font size="4"><?php echo $readable_article; ?></font></p>
    <?php endif; ?>

    <?php if ($error_text): ?>
        <p><font color="red"><?php echo $error_text; ?></font></p>
    <?php endif; ?>

    <small><a href="/index.php?loc=<?php echo $loc ?>">&lt; Back to <font color="#9400d3">68k.news</font> <?php echo $loc ?> front page</a></small>
</body>
</html>

