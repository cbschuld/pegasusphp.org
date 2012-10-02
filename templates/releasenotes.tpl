<h2>Release Notes</h2>
<table style="border-spacing:2px;">
{foreach from=$releaseNotes->release key=key item=release}
	<tr>
		<td style="vertical-align:top;background:#efefef;border:1px solid #e0e0e0;padding:5px;white-space:nowrap;">
			Version {$release->version}<br/>
			<span style="color:#aaaaaa;font-size:85%;">
				{$release->date|date_format:"%A, %B %e, %Y"}
			</span>
		</td>
		<td style="vertical-align:top;background:#fafafa;font-size:95%;width:100%;padding:5px;border:1px solid #e0e0e0;margin-bottom:2px;">{$release->notes|replace:"\n":'<br/>'}</td>
	</tr>
{/foreach}
</table>
