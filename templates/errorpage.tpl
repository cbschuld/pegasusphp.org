<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Pegasus Error</title>
		<style type="text/css">
			body {ldelim}padding:20px;background:#ffffff;font-size:100%;font-family:verdana,arial,sans-serif;text-align:left;{rdelim}
			h1 {ldelim}text-align:left;color:#ac0000;font-size:120%;font-weight:bold;padding:1px;margin:0px;{rdelim}
			h3 {ldelim}text-align:left;color:#0000ac;font-size:100%;font-weight:bold;padding:1px;margin:0px;{rdelim}
			p#fineprint {ldelim}text-align:left;color:#888888;font-size:90%;padding:1px;margin:0px;{rdelim}
			div#debugoff {ldelim}text-align:left;color:#888888;padding:10px;margin:0px;{rdelim}
			div#debugon {ldelim}text-align:left;color:#888888;padding:10px;margin:0px;{rdelim}
			div#debugoff a,div#debugon a {ldelim}text-decoration:none;color:#888888;{rdelim}
			div#debugoff a:hover,div#debugon a:hover {ldelim}text-decoration:underline;color:#333333;{rdelim}
		</style>
		<script type="text/javascript" src="/pegasus/scripts/jquery-1.2.6.min.js"></script>
	</head>
	<body>
		<div id="error" class="error" style="background:#dddddd;">
			<div style="float:left;padding:4px;">
				<img src="/pegasus/images/SymbolError-48.gif" width="48" height="48" alt="Error" />
			</div>
			<div style="float:left;padding-left:5px;">
				{if $title != ''}<h1>{$title|htmlentities}</h1>{/if}
				{if $subtitle != ''}<h3>{$subtitle|htmlentities}</h3>{/if}
				<p>{$message|htmlentities|nl2br}</p>
				{if $fineprint !=''}<p id="fineprint">{$fineprint|htmlentities|nl2br}</p>{/if}
			</div>
			<div style="clear:both;"></div>
	
			<div id="debugoff">
				<div id="debugoffctrl">
					<a href="#" onmousedown="$('#debugon').slideDown('normal');$('#debugoffctrl').slideUp('normal');$('#debugonctrl').slideDown('normal');">View Debug Information</a>
				</div>
				<div id="debugonctrl" style="display:none;">
					<a href="#" onmousedown="$('#debugon').slideUp('normal');$('#debugoffctrl').slideDown('normal');$('#debugonctrl').slideUp('normal');">Hide Debug Information</a>
				</div>
			</div>
			<div id="debugon" style="display:none;">
				<h2>Debug Backtrace:</h2>
				{$debugBacktrace}
				<h2>Debug Information:</h2>
				{$debugInformation}
				<p>
				<a href="#" onmousedown="$('#debugon').slideUp('normal');$('#debugoffctrl').slideDown('normal');$('#debugonctrl').slideUp('normal');">Hide Debug Information</a>
				</p>
			</div>
			
		</div>
	</body>
</html>