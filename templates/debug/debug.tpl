{if $dynamicDebug}
<div id="debugoff" style="font-family:verdana,arial;display:block;text-align:right;">
	<a style="font-size:90%;border:1px solid #888888;padding:3px;color:#888888;text-decoration:none;" href="#" onclick="document.getElementById('debugon').style.display=(document.getElementById('debugon').style.display=='block'?'none':'block');document.getElementById('debugoff').style.display=(document.getElementById('debugoff').style.display=='block'?'none':'block');return false;">view debug information &raquo;</a>
	<br/>
	<br/>
</div>
{/if}
<div id="debugon" style="font-family:verdana,arial;display:{if $dynamicDebug}none{else}block{/if};margin:10px;">
	<div style="color:#888888;border:1px solid #888888;background:#ffffff;padding:10px;">
		{if $dynamicDebug}
		<div style="text-align:right;">
			<a style="font-size:90%;border:1px solid #888888;padding:3px;color:#888888;text-decoration:none;" href="#" onclick="document.getElementById('debugon').style.display=(document.getElementById('debugon').style.display=='block'?'none':'block');document.getElementById('debugoff').style.display=(document.getElementById('debugoff').style.display=='block'?'none':'block');return false;">&laquo; hide debug information</a>
		</div>
		<br/>
		{/if}
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
			<pre style="font-size:90%;">{if $smarty.server.REQUEST_METHOD == 'POST'}{$debugPost->getFormattedObject()}{else}{$debugGet->getFormattedObject()}{/if}</pre>
		</div>

		{foreach from=$debugObjects item=debugObject}
		<br/>
		<div>
			{assign var=title value=$debugObject->getTitle()}
			{assign var=description value=$debugObject->getFormattedObject()}
			<!-- Begin Debug Object -->
			<div style="border:1px solid #cccccc;border-bottom:1px solid #eeeeee;background:#eeeeee;color:#555555;padding:2px;text-align:left;font-weight:bold;">{$title}</div>
			<div style="border:1px solid #cccccc;background:#f5f5f5;color:#888888;text-align:left;padding:2px;">
				<pre style="font-size:90%;">{$description}</pre>
			</div>
			<!-- End Debug Object -->			
		</div>
		{/foreach}

		<br/><br/>
		Note: &diams; - Unprintable Characters
		{if $dynamicDebug}
		<div style="text-align:right;">
			<a style="font-size:90%;border:1px solid #888888;padding:3px;color:#888888;text-decoration:none;" href="#" onclick="document.getElementById('debugon').style.display=(document.getElementById('debugon').style.display=='block'?'none':'block');document.getElementById('debugoff').style.display=(document.getElementById('debugoff').style.display=='block'?'none':'block');return false;">&laquo; hide debug information</a>
		</div>
		{/if}
	</div>
</div>
