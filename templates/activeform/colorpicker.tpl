{if $field->showlabel}
<div style="padding-top:4px;text-align:{$field->lalign};width:{$field->lwidth};float:left;">
	<label{if ! $field->readonly} for="{$activeform->name}_{$field->name}"{/if}>{$field->label}</label>
</div>
<div style="float:left;">
{/if}

{if $field->readonly}
<div class="afReadOnly" style="float:left;width:{$field->size*$field->pmux}px;">{$field->value|default:"&nbsp;"}</div>
{else}

{include_css file="/pegasus/includes/farbtastic/farbtastic.css"}
{include_javascript file="/pegasus/includes/farbtastic/farbtastic.js"}

<input type="text" id="{$activeform->name}_{$field->name}" name="{$activeform->name}_{$field->name}" value="{$field->value}" /><div id="{$activeform->name}_{$field->name}_picker"></div>
 <script type="text/javascript">
	/*<![CDATA[*/
  $(document).ready(function() {ldelim}
    $('#{$activeform->name}_{$field->name}_picker').farbtastic('#{$activeform->name}_{$field->name}');
    $.farbtastic('#{$activeform->name}_{$field->name}_picker').setColor('#{$field->value}');
  {rdelim});
	/*]]>*/
</script>
{/if}

{if $field->showlabel}</div>{/if}
{if $field->cleardiv}<div style="clear:both;"></div>{/if}