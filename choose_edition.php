<?php

$loc = "US";

if( isset( $_GET['loc'] ) ) {
    $loc = $_GET["loc"];
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 2.0//EN">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<html>
<head>
	<title>min8.news: Headlines for Minimalists</title>
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
</style>
</head>
<body>
    <center><h1><b>min9.news:</b> <font color="#9400d3"><i>Headlines from the Future</i></font></h1></center>
    <hr>
    <center>
    <small>Basic HTML Google News for vintage computers. Built by <a href="https://youtube.com/ActionRetro" target="_blank"><b>Action Retro</b></a> on YouTube. Tested on Netscape 1.1 through 4 on a Mac SE/30.</small>
    <p><h2>CHOOSE YOUR EDITION:</h2></p>
    <p><a href='/index.php?section=nation&loc=US'>United States</a></p>
    <p><a href='/index.php?section=nation&loc=JP'>Japan</a></p>
    <p><a href='/index.php?section=nation&loc=UK'>United Kingdom</a></p>
    <p>Spain (RIP)</p>
    <p><a href='/index.php?section=nation&loc=CA'>Canada</a></p>
    <p><a href='/index.php?section=nation&loc=DE'>Deutschland</a></p>
    <p><a href='/index.php?section=nation&loc=IT'>Italia</a></p>
    <p><a href='/index.php?section=nation&loc=FR'>France</a></p>
    <p><a href='/index.php?section=nation&loc=AU'>Australia</a></p>
    <p><a href='/index.php?section=nation&loc=TW'>Taiwan</a></p>
    <p><a href='/index.php?section=nation&loc=NL'>Nederland</a></p>
    <p><a href='/index.php?section=nation&loc=BR'>Brasil</a></p>
    <p><a href='/index.php?section=nation&loc=TR'>Turkey</a></p>
    <p><a href='/index.php?section=nation&loc=BE'>Belgium</a></p>
    <p><a href='/index.php?section=nation&loc=GR'>Greece</a></p>
    <p><a href='/index.php?section=nation&loc=IN'>India</a></p>
    <p><a href='/index.php?section=nation&loc=MX'>Mexico</a></p>
    <p><a href='/index.php?section=nation&loc=DK'>Denmark</a></p>
    <p><a href='/index.php?section=nation&loc=AR'>Argentina</a></p>
    <p><a href='/index.php?section=nation&loc=CH'>Switzerland</a></p>
    <p><a href='/index.php?section=nation&loc=CL'>Chile</a></p>
    <p><a href='/index.php?section=nation&loc=AT'>Austria</a></p>
    <p><a href='/index.php?section=nation&loc=KR'>Korea</a></p>
    <p><a href='/index.php?section=nation&loc=IE'>Ireland</a></p>
    <p><a href='/index.php?section=nation&loc=CO'>Colombia</a></p>
    <p><a href='/index.php?section=nation&loc=PL'>Poland</a></p>
    <p><a href='/index.php?section=nation&loc=PT'>Portugal</a></p>
    <p><a href='/index.php?section=nation&loc=PK'>Pakistan</a></p>
    </center>
    <small><a href="/index.php?loc=<?php echo $loc ?>">< Back to <font color="#9400d3">min9.news</font> <?php echo $loc ?>front page</a></small>
	<p><center><small>Powered by SimplePie</small></p>
</body>
</html>
