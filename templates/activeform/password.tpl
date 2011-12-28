{if $field->showlabel}
<div style="padding-top:4px;text-align:{$field->lalign};width:{$field->lwidth};float:left;"><label for="{$activeform->name}_{$field->name}">{$field->label}</label></div>
<div style="float:left;">
{/if}

{if $field->readonly}
<div class="afReadOnly" style="width:{$field->size*$field->pmux}px;">*************</div>

{elseif $field->verify}
<div style="float:left;"><input{if $field->required} class="required"{/if} name="{$activeform->name}_{$field->name}" id="{$activeform->name}_{$field->name}" onkeyup="validate_{$activeform->name}_{$field->name}();" type="password" size="{$field->size}" maxlength="{$field->length}" value="{$field->value}" /></div>
<div class="afExample" style="clear:both;"></div>
<div style="float:left;"><input{if $field->required} class="required"{/if} type="password" name="{$activeform->name}_{$field->name}2" id="{$activeform->name}_{$field->name}2" onkeyup="validate_{$activeform->name}_{$field->name}();" size="{$field->size}" maxlength="{$field->length}" value="{$field->value}" /></div>
<div style="float:left;display:block;" id="{$activeform->name}_{$field->name}_bad"><img alt="error" src="/pegasus/images/icon/16x16/delete.png"/></div>
<div style="float:left;display:none;" id="{$activeform->name}_{$field->name}_good"><img alt="ok" src="/pegasus/images/icon/16x16/check.png"/></div>
<div class="afExample" style="clear:both;">(Verify Password)</div>
<script type="text/javascript">
	//<![CDATA[
	function validate_{$activeform->name}_{$field->name}(){ldelim}if($('#{$activeform->name}_{$field->name}').val()!='' && $('#{$activeform->name}_{$field->name}').val()==$('#{$activeform->name}_{$field->name}2').val()){ldelim}{if $field->required}$('#{$activeform->name}_{$field->name}').removeClass('required');$('#{$activeform->name}_{$field->name}2').removeClass('required');{/if}$('#{$activeform->name}_{$field->name}_bad').hide();$('#{$activeform->name}_{$field->name}_good').show();validationArray{$activeform->name}['{$field->name}']=1{rdelim}else{ldelim}{if $field->required}$('#{$activeform->name}_{$field->name}').addClass('required');$('#{$activeform->name}_{$field->name}2').addClass('required');{/if}$('#{$activeform->name}_{$field->name}_bad').show();$('#{$activeform->name}_{$field->name}_good').hide();validationArray{$activeform->name}['{$field->name}']=0{rdelim};setSubmitStatus('{$activeform->name}',$('#{$activeform->submitname}'),validationArray{$activeform->name});{rdelim}
	$(document).ready(function(){ldelim}validate_{$activeform->name}_{$field->name}();{rdelim});
	//]]>
</script>
{else}
<div style="float:left;"><input{if $field->required} class="required"{/if} name="{$activeform->name}_{$field->name}" id="{$activeform->name}_{$field->name}" onkeyup="validate_{$activeform->name}_{$field->name}();" type="password" size="{$field->size}" maxlength="{$field->length}" value="{$field->value}" /></div>
<div class="afExample" style="clear:both;"></div>
{/if}





{if $field->showlabel}
</div>
{if $field->cleardiv}<div style="clear:both;"></div>{/if}
{/if}