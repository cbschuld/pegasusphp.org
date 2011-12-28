
<!-- BEGIN PRIVILEGE DISPLAY ({$privilegename|upper}) -->

<table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding: 1px;">
	<tr>
		<td style="background: #{$color}; border: 1px solid #cccccc;" width="100%">

			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td>
						<input	id="{$privilegeid}"
								name="{$privilegeid}" 
								onclick="javascript:togglePrivileges(this,new Array({eval var=$privilege_parent_list}),new Array({eval var=$privilege_children_list}));"
								type="checkbox"{if $privilegevalue }
								checked="checked"{/if}{if $readonly}
								disabled="disabled"{/if}/>
					</td>
					<td width="100%" style="font-weight: normal; color: #000000;">
						{$privilegetitle}
					</td>
				</tr>
				<tr>
					<td></td>
					<td style="color: #666666;">
						<small>{$privilegedescription}</small>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<!-- END PRIVILEGE DISPLAY ({$privilegename|upper}) -->

