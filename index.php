<?php
// Send noindex header if query present
$any_params = parse_url(
    "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]
);
if (array_key_exists("query", $any_params)) {
    header("X-Robots-Tag: noindex, nofollow", true);
}

require_once __DIR__ . "/vendor/autoload.php";

// Utility functions
function clean_str($str)
{
    return str_replace(
        ["‘", "’", "“", "”", "–", "&nbsp;"],
        ["'", "'", '"', '"', "-", " - "],
        $str
    );
}

// Params
$section = $_GET["section"] ?? "";
$loc = strtoupper($_GET["loc"] ?? "US");
$lang = $_GET["lang"] ?? "en";

// Feed URL
if ($section) {
    $feed_url =
        "https://news.google.com/news/rss/headlines/section/topic/" .
        strtoupper($section) .
        "?ned=$loc&hl=$lang";
} else {
    $feed_url = "https://news.google.com/rss?gl=$loc&hl=$lang-$loc&ceid=$loc:$lang";
}

$feed = new SimplePie();
$feed->set_feed_url($feed_url);
$feed->init();
$feed->handle_content_type();

// Country options
$countries = [
    "US" => "🇺🇸 US",
    "GB" => "🇬🇧 UK",
    "CA" => "🇨🇦 Canada",
    "AU" => "🇦🇺 Australia",
    "IN" => "🇮🇳 India",
    "DE" => "🇩🇪 Germany",
    "FR" => "🇫🇷 France",
    "JP" => "🇯🇵 Japan",
    "BR" => "🇧🇷 Brazil",
    "ZA" => "🇿🇦 South Africa",
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>min9.news</title>
    <style>
body {
    font: 1em/1.5 monospace;
    padding: 16vh 2em 0;
    background: #eee;
    display: grid;
    grid: 1fr / minmax(auto, 64ch);
    justify-content: center;
    height: 100%;
}
a { text-decoration: none; border-bottom: 1px dotted }
pre {
    white-space: pre-wrap;
    background: #ddd;
    padding: 1em;
    margin-top: 2em;
}
select {
    font: inherit;
    margin: 1em auto;
    display: block;
    background: #fff;
    border: 1px solid #ccc;
}
    </style>
</head>
<body>
    <center>
        <h1><b>min9.news:</b> <font color="#9400d3"><i>Minimal Headlines</i></font></h1>
        <hr>
        <small>Built by <a href="https://github.com/amadv" target="_blank"><b>amadv</b></a></small>
    </center>

    <?php if ($section): ?>
        <center><h2><?php echo strtoupper($section); ?> NEWS</h2></center>
    <?php endif; ?>

    <p>
    <center>
        <a href="index.php?loc=<?php echo $loc; ?>">TOP</a>
        <a href="index.php?section=technology&loc=<?php echo $loc; ?>">TECH</a>
        <a href="index.php?section=world&loc=<?php echo $loc; ?>">WORLD</a>
        <a href="index.php?section=business&loc=<?php echo $loc; ?>">BUSINESS</a>
    </center>
    </p>

    <!-- Country Selector -->
    <form method="GET" onchange="this.submit()">
        <?php if ($section): ?>
            <input type="hidden" name="section" value="<?php echo htmlspecialchars(
                $section
            ); ?>">
        <?php endif; ?>
        <select name="loc">
            <?php foreach ($countries as $code => $label): ?>
                <option value="<?php echo $code; ?>" <?php echo $code === $loc
    ? "selected"
    : ""; ?>>
                    <?php echo $label; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <ol>
    <?php foreach ($feed->get_items() as $item):

        $description = $item->get_description();
        preg_match('/<a href="([^"]+)"/', $description, $matches);
        $real_link = $matches[1] ?? $item->get_permalink();
        ?>
        <li>
            <a href="<?php echo htmlspecialchars(
                $real_link
            ); ?>" target="_blank">
                <?php echo clean_str($item->get_title()); ?>
            </a>
            <p><small>Posted on <?php echo $item->get_date(
                "j F Y | g:i a"
            ); ?></small></p>
        </li>
    <?php
    endforeach; ?>
    </ol>

    <footer><center><small>Powered by SimplePie</small></center></footer>
</body>
</html>
