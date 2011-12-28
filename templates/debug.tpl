
<div id="debugoff" style="font-family:verdana,arial;display:block;text-align:right;">
	<a style="font-size:80%;border:1px solid #888888;padding:3px;color:#888888;text-decoration:none;" href="javascript://" onclick="document.getElementById('debugon').style.display=(document.getElementById('debugon').style.display=='block'?'none':'block');document.getElementById('debugoff').style.display=(document.getElementById('debugoff').style.display=='block'?'none':'block');">view debug information &raquo;</a>
	<br/>
	<br/>
</div>


<div id="debugon" style="font-family:verdana,arial;display:none;margin:10px;">

	<div style="color:#888888;border:1px solid #888888;background:#ffffff;padding:10px;">

		<div style="text-align:right;">
			<a style="font-size:80%;border:1px solid #888888;padding:3px;color:#888888;text-decoration:none;" href="javascript://" onclick="document.getElementById('debugon').style.display=(document.getElementById('debugon').style.display=='block'?'none':'block');document.getElementById('debugoff').style.display=(document.getElementById('debugoff').style.display=='block'?'none':'block');">&laquo; hide debug information</a>
		</div>
		
		<br/>

		<div style="border:1px solid #cccccc;border-bottom:1px solid #eeeeee;background:#eeeeee;color:#555555;padding:2px;">
			<div style="float:left;">
				<strong>{if $smarty.server.REQUEST_METHOD == 'POST'}POST{else}GET{/if} Information</strong>
			</div>
			<div style="float:right;">
				<small>The Server used a REQUEST METHOD == '{$smarty.server.REQUEST_METHOD}'</small>
			</div>
			<div style="clear:both;"></div>
		</div>
		<div style="border:1px solid #cccccc;background:#f5f5f5;color:#888888;text-align:left;padding:2px;">
			<pre style="font-size:80%;">{if $smarty.server.REQUEST_METHOD == 'POST'}{$pegasus->getDebugPost()}{else}{$pegasus->getDebugGet()}{/if}</pre>
		</div>

		<br/>
		
		<div style="border:1px solid #cccccc;border-bottom:1px solid #eeeeee;background:#eeeeee;color:#555555;padding:2px;">
			<strong>REQUEST Information</strong>
		</div>
		<div style="border:1px solid #cccccc;background:#f5f5f5;color:#888888;text-align:left;padding:2px;">
			<pre style="font-size:80%;">{$pegasus->getDebugRequest()}</pre>
		</div>

		<br/>
		
		<div style="border:1px solid #cccccc;border-bottom:1px solid #eeeeee;background:#eeeeee;color:#555555;padding:2px;">
			<strong>SESSION Information</strong>
		</div>
		<div style="border:1px solid #cccccc;background:#f5f5f5;color:#888888;text-align:left;padding:2px;">
			<pre style="font-size:80%;">{$pegasus->getDebugSession()}</pre>
		</div>
				
		<br/>
		
		{assign var=title value="PHP SERVER Information"}
		{assign var=description value=$pegasus->getDebugServer()}
		{include file="pegasus:debug/debug.object.tpl"}
		
		<div style="border:1px solid #cccccc;border-bottom:1px solid #eeeeee;background:#eeeeee;color:#555555;padding:2px;text-align:left;">
			<strong>PHP SERVER Information</strong>
		</div>
		<div style="border:1px solid #cccccc;background:#f5f5f5;color:#888888;text-align:left;padding:2px;">
			<pre style="font-size:80%;">{$pegasus->getDebugServer()}</pre>
		</div>

		<br/>
		
		<div style="border:1px solid #cccccc;border-bottom:1px solid #eeeeee;background:#eeeeee;color:#555555;padding:2px;">
			<strong>PREVIOUS REQUEST Information (Legacy Request)</strong>
		</div>
		<div style="border:1px solid #cccccc;background:#f5f5f5;color:#888888;text-align:left;padding:2px;">
			<pre style="font-size:80%;">{$pegasus->getDebugLegacyRequest()}</pre>
		</div>

		<br/>
		<br/>

		<div style="text-align:right;">
			<a style="font-size:80%;border:1px solid #888888;padding:3px;color:#888888;text-decoration:none;" href="javascript://" onclick="document.getElementById('debugon').style.display=(document.getElementById('debugon').style.display=='block'?'none':'block');document.getElementById('debugoff').style.display=(document.getElementById('debugoff').style.display=='block'?'none':'block');">&laquo; hide debug information</a>
		</div>
	</div>
</div>
