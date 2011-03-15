<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo $title ?></title>
<style>
/* Makeshift CSS Reset */
{ margin: 0; padding: 0; }
/* Render HTML5 elements as blocks */
header, footer, aside, nav, article { display: block; }
body {
	margin: 0 auto;
	width: 80em;
	font: 80% Helvetica, Arial, sans-serif;
	color: #111;
	background: #fff;
}
form dl { margin: 1em 0; }
form dl dd { margin: 0 0 0.2em; }
form dl dd.submit { margin-top: 1em; }
nav ul { padding: 0 1em 1em; }
nav ul li { list-style: square; margin-left: 1.6em; padding: 0 0 0.2em; }
header,
aside { color: #444; }
section { padding-bottom: 1em; margin-bottom: 1em; border-bottom: solid 1px #ccc; }
section:last-child { padding-bottom: 0; border-bottom: 0; }
footer { color: #888; text-align: center; }
.large { font-size: 1.2em; }
.warn { display: block; color: #911; background: #efefef; padding: 0.6em; margin: 1em 0; }
#body { display: table; margin: 1em 0; width: 100%; border: solid 1px #ccc; border-width: 1px 0; }
#body #menu { display: table-cell; padding-right: 1em; border-right: solid 1px #ccc; }
#body #content { display: table-cell; width: 90%; padding: 0 0 1em; }
#body #content section { padding: 0 1em 1em; }
#body #content pre { white-space: pre-wrap; padding: 1em; border: dotted 1px #ddd; background: #f3f3f3; }
</style>
</head>
<body>
<header>
	<h1><?php echo $title ?></h1>
</header>
<div id="body">
	<nav id="menu">
		<h4>Demos</h4>
		<?php if ( ! $demos): ?>
		<p>No demos available. Try enabling a provider API.</p>
		<?php else: ?>
		<ul>
		<?php foreach ($demos as $demo => $link): ?>
		<li><?php echo HTML::anchor($link, $demo) ?></li>
		<?php endforeach ?>
		</ul>
		<?php endif ?>
	</nav>
	<div id="content">
		<section class="large">
			<?php echo $content ?>
		</section>
		<?php if ($code): ?>
		<section>
			<h3>Source Code</h3>
			<?php echo $code ?>
		</section>
		<?php endif ?>
	</div>
</div>
<footer>Created by the <?php echo HTML::anchor('http://kohanaframework.org/team', 'Kohana Team') ?>.</footer>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script charset="utf-8">
	$('<link>').attr({
		'rel':  'stylesheet',
		'href': 'http://google-code-prettify.googlecode.com/svn/trunk/src/prettify.css'
	}).appendTo('head');

	$('#content pre').addClass('prettyprint');

	$.getScript('http://google-code-prettify.googlecode.com/svn/trunk/src/prettify.js', function() {
		window.prettyPrint();
	});
</script>
</body>
</html>