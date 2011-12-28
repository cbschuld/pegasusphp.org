{if $field->showlabel}
<div style="padding-top:4px;text-align:{$field->lalign};width:{$field->lwidth};float:left;"><label for="{$activeform->name}_{$field->name}">{$field->label}</label></div>
<div style="float:left;">
{/if}

<div style="float:left;">
	<button name="{$activeform->name}_{$field->name}" id="{$activeform->name}_{$field->name}" class="{$field->class}">{$field->value}</button>
</div>
<div style="clear:both;"></div>

{if $field->showlabel}
</div>
{if $field->cleardiv}<div style="clear:both;"></div>{/if}
{/if}