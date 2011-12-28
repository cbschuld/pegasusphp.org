{if $field->showlabel}
<div style="padding-top:4px;text-align:{$field->lalign};width:{$field->lwidth};float:left;"><label for="{$activeform->name}_{$field->name}">{$field->label}</label></div>
<div style="float:left;">
{/if}

{if $field->readonly}
<div class="afReadOnly" style="width:{$field->cols*$field->pmux}px;">{$field->value|default:"&nbsp;"}</div>

{*  N O   V A L I D A T I O N  *}
{else}
<div style="float:left;">
	<textarea name="{$activeform->name}_{$field->name}" id="{$activeform->name}_{$field->name}" rows="{$field->rows}" cols="{$field->cols}">{$field->value}</textarea>
	{if $field->footnote != ''}
	<div class="afFootnote">{$field->footnote}</div>
	{/if}
</div>
<div style="clear:both;"></div>
{/if}

{if $field->showlabel}
</div>
{if $field->cleardiv}<div style="clear:both;"></div>{/if}
{/if}