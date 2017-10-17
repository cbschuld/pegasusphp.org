<div id="{$activeform->name}_{$field->name}_object">
{if $field->showlabel}
<div style="padding-top:4px;text-align:{$field->lalign};{if $field->lwidth != ''}width:{$field->lwidth};{/if}float:left;"></div>
{/if}

{* R E A D O N L Y    D I S P L A Y *}
{if $field->readonly}
<div style="float:left;"><input type="checkbox" disabled="disabled" name="{$activeform->name}_{$field->name}" id="{$activeform->name}_{$field->name}"{if $field->value} checked="checked"{/if} /><label id="{$activeform->name}_{$field->name}_label" for="{$activeform->name}_{$field->name}">{$field->label}</label></div>

{*  N O   V A L I D A T I O N  *}
{else}
<div style="float:left;"><input type="checkbox" name="{$activeform->name}_{$field->name}" id="{$activeform->name}_{$field->name}"{if $field->value} checked="checked"{/if} /><label id="{$activeform->name}_{$field->name}_label" for="{$activeform->name}_{$field->name}">{$field->label}</label></div>
{/if}

</div>
{if $field->cleardiv}<div style="clear:both;"></div>{/if}
