{if $field->showlabel}
<div style="padding-top:4px;text-align:{$field->lalign};width:{$field->lwidth};float:left;">
	<label{if ! $field->readonly} for="{$activeform->name}_{$field->name}"{/if}>{$field->label}</label>
</div>
<div style="float:left;">
{/if}

{if $field->readonly}
<div class="afReadOnly" id="{$activeform->name}_{$field->name}" style="float:left;width:{$field->size*$field->pmux}px;">{$field->value|default:"&nbsp;"}</div>

{*  A J A X   V A L I D A T I O N  *}
{elseif $field->validation == 'ajax'}

{assign var=validationurl value=$field->validationurl}


{if $validationurl == ''}
<strong>Invalid Validation URL for AJAX</strong>
{else}

<!-- AJAX -->
<div style="float:left;"><input{if $field->required} class="required"{/if} type="text" name="{$activeform->name}_{$field->name}" id="{$activeform->name}_{$field->name}" {if $field->onchange != ''} onchange="{$field->onchange}"{/if} onblur="validate_{$activeform->name}_{$field->name}(this.value);{$field->onblur}" onkeyup="validate_{$activeform->name}_{$field->name}(this.value);" size="{$field->size}" maxlength="{$field->length}" value="{$field->value}" /></div>
<div style="float:left;display:block;" class="afError" id="{$activeform->name}_{$field->name}_bad"><img alt="err" src="/pegasus/images/icon/16x16/delete.png"/>{$field->vmessage}</div>
<div style="float:left;display:none;" id="{$activeform->name}_{$field->name}_good"><img alt="ok" src="/pegasus/images/icon/16x16/check.png"/></div>
<div style="clear:both;"></div>
<script type="text/javascript">
	/*<![CDATA[*/
	function validate_{$activeform->name}_{$field->name}(value){ldelim}if(value==undefined)value='';jQuery.get('{$validationurl|replace:'VALUE':"'+value+'"}',null,function(val){ldelim}if(val=='1'){ldelim}$('#{$activeform->name}_{$field->name}').removeClass('required');$('#{$activeform->name}_{$field->name}_bad').hide();$('#{$activeform->name}_{$field->name}_good').show();validationArray{$activeform->name}['{$field->name}']=1;{rdelim}else{ldelim}$('#{$activeform->name}_{$field->name}').addClass('required');$('#{$activeform->name}_{$field->name}_bad').show();$('#{$activeform->name}_{$field->name}_good').hide();validationArray{$activeform->name}['{$field->name}']=0;{rdelim};setSubmitStatus('{$activeform->name}',$('#{$activeform->submitname}'),validationArray{$activeform->name});{rdelim});{rdelim}
	//validate_{$activeform->name}_{$field->name}($('#{$activeform->name}_{$field->name}').val());
 	$(document).ready(function(){ldelim}validate_{$activeform->name}_{$field->name}($('#{$activeform->name}_{$field->name}').val());{rdelim});
	/*]]>*/
</script>
{/if}




{*  J A V A S C R I P T    V A L I D A T I O N  *}
{elseif $field->required && $field->validation == 'empty'}
<div style="float:left;"><input class="required" type="text" name="{$activeform->name}_{$field->name}" id="{$activeform->name}_{$field->name}"{if $field->onchange != ''} onchange="{$field->onchange}"{/if} onblur="validate_{$activeform->name}_{$field->name}(this.value);{$field->onblur}" onkeyup="validate_{$activeform->name}_{$field->name}(this.value);" size="{$field->size}" maxlength="{$field->length}" value="{$field->value}" /></div>
<div style="float:left;display:block;" class="afError" id="{$activeform->name}_{$field->name}_bad"><img alt="err" src="/pegasus/images/icon/16x16/delete.png"/>{$field->vmessage}</div>
<div style="float:left;display:none;" id="{$activeform->name}_{$field->name}_good"><img alt="ok" src="/pegasus/images/icon/16x16/check.png"/></div>
<div style="clear:both;"></div>
<script type="text/javascript">
	/*<![CDATA[*/
	function validate_{$activeform->name}_{$field->name}(value){ldelim}if(value==undefined)value='';if(value!=''){ldelim}$('#{$activeform->name}_{$field->name}').removeClass('required');$('#{$activeform->name}_{$field->name}_bad').hide();$('#{$activeform->name}_{$field->name}_good').show();validationArray{$activeform->name}['{$field->name}']=1;{rdelim}else{ldelim}$('#{$activeform->name}_{$field->name}').addClass('required');$('#{$activeform->name}_{$field->name}_bad').show();$('#{$activeform->name}_{$field->name}_good').hide();validationArray{$activeform->name}['{$field->name}']=0;{rdelim};setSubmitStatus('{$activeform->name}',$('#{$activeform->submitname}'),validationArray{$activeform->name});{rdelim}
 	$(document).ready(function(){ldelim}validate_{$activeform->name}_{$field->name}($('#{$activeform->name}_{$field->name}').val());{rdelim});
	/*]]>*/
</script>


{*  N O   V A L I D A T I O N  *}
{else}
<div style="float:left;"><input type="text" name="{$activeform->name}_{$field->name}" id="{$activeform->name}_{$field->name}" size="{$field->size}" maxlength="{$field->length}" value="{$field->value}" /></div>
{if $field->required}<div style="float:left;" class="afRequired">*</div>{/if}
<div style="clear:both;"></div>
{/if}

{if $field->showlabel}</div>{/if}
{if $field->cleardiv}<div style="clear:both;"></div>{/if}