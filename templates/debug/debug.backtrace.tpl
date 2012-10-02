<!-- Begin Debug Backtrace Object -->
<div style="font-family:Courier New,Courier;border:1px solid #cccccc;border-bottom:1px solid #eeeeee;background:#eeeeee;color:#555555;padding:2px;text-align:left;font-weight:bold;">
	function {$debugBacktrace.function}
	(
	{foreach name=arglist from=$debugBacktrace.args item=arg}
		'{$arg}'{if ! $smarty.foreach.arglist.last},{/if}
	{/foreach}
	)
</div>
<div style="font-family:Courier New,Courier;border:1px solid #cccccc;background:#f5f5f5;color:#888888;text-align:left;padding:2px;">
	<pre style="font-size:90%;">
	File:{$debugBacktrace.file}
	Line:{$debugBacktrace.line}
	</pre>
</div>
<!-- End Debug Backtrace Object -->