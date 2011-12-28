{if $field->showlabel}
<div style="padding-top:4px;text-align:{$field->lalign};width:{$field->lwidth};float:left;"><label for="{$activeform->name}_{$field->name}">{$field->label}</label></div>
<div style="float:left;">
{/if}

{* R E A D O N L Y    D I S P L A Y *}
{if $field->readonly}
	<div class="readonly" style="width:{$field->size*$field->pmux}px;">{$field->value|default:"&nbsp;"}</div>

{* N O   V A L I D A T I O N *}
{else}
<div style="float:left;">
	<select name="{$activeform->name}_{$field->name}" id="{$activeform->name}_{$field->name}">
		{html_options options=$field->options selected=$field->value}
	</select>
</div>
{/if}
{if $field->showlabel}
</div>
{if $field->cleardiv}<div style="clear:both;"></div>{/if}
{/if}