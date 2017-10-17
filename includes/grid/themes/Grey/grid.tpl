{assign var=theme value="Grey"}
{assign var=themepath value="/pegasus/includes/grid/themes/`$theme`"}
{assign var=gridId value=$grid->getId()}
{assign var=gridColumns value=$grid->getColumns()}
{assign var=gridFilters value=$grid->getFilters()}
{assign var=gridPageNumber value=$grid->getPageNumber()}
{assign var=gridTotalPages value=$grid->getTotalPages()}
{include_css file="/pegasus/includes/grid/themes/`$theme`/grid.css"}
{include_javascript file="/pegasus/scripts/cookies.js"}
<script type="text/javascript">
{literal}
	/*<![CDATA[*/
	var grid_{/literal}{$grid->getId()}{literal}_page_number = {/literal}{$gridPageNumber}{literal};
	/*]]>*/
{/literal}
</script>
<!-- Grid -->
<div id="grid{$gridId}" style="overflow:auto;">
	<div style="margin:1px;">
		<table class="grid{$theme}" {if $grid->isGridStretched()} style="width:100%;"{/if}>
			{if $grid->getFilterCount() > 0}
			<tr>
				<td colspan="{$grid->getColumnCount()}">
					<div style="float:right;">
						<ul id="grid{$theme}FilterNav">
							{foreach name=filterNames from=$gridFilters item=filter}
							<li><a href="#" class="{if $filter->isSelected() && $filter->isHotFilter()}hothere{elseif $filter->isHotFilter()}hot{elseif $filter->isSelected()}here{/if}" onclick="setOneYearCookie('Grid{$gridId}FilterIndex',{$filter->getIndex()});$('#grid{$gridId}').load('{$gridProcessingUrl}{request gridid=$gridId gridpage=1 gajax=1 gridpersist=$grid->getPersistenceObject() gridfilter=$filter->getIndex()}');return false;">{$filter->getTitle()}</a></li>
							{/foreach}
						</ul>
					</div>
					<div style="float:right;"><em class="grid{$theme}">available filters:</em></div>
				</td>
			</tr>
			{/if}
			{if $grid->isSearchable()}
			<tr>
				<td colspan="{$grid->getColumnCount()}" class="grid{$theme}Utilities">
					<div id="gridSearchOff{$gridId}" style="display:block;">
						<div style="float:left;"><em class="grid{$theme}">Viewing Page {$gridPageNumber} of {$gridTotalPages}</em></div>
						<div style="float:right;">
							<a class="grid{$theme}NavLink" href="#" onclick="$('#gridSearchOn{$gridId}').fadeIn('fast',function(){ldelim}$('#gridSearch{$gridId}').select();{rdelim});$('#gridSearchOff{$gridId}').hide();return false;">Search this Information &gt;&gt;</a>
						</div>
						{if $grid->getSearchString() != ''}
						<div id="grid{$theme}FilteredMessage" style="float:right;padding-left:10px;padding-right:10px;">
							<a href="#" onclick="setOneYearCookie('Grid{$gridId}SearchString','');$('#grid{$gridId}').load('{$gridProcessingUrl}{request gridid=$gridId gridpage=1 gajax=1 gridsearch='' gridpersist=$grid->getPersistenceObject()}');return false;">Your results are currently filtered by a search for '{$grid->getSearchString()}' - click here to clear this search</a>
						</div>
						{/if}
						
						<div style="clear:both;"></div>
					</div>
					<div id="gridSearchOn{$gridId}" style="display:none;">
						<div style="float:left;"><img class="grid{$theme}" alt="search" src="{$themepath}/images/search.medium.gif"/></div>
						<div style="float:right;padding-left:10px;">
							<a class="grid{$theme}NavLink" href="#" onclick="$('#gridSearchOff{$gridId}').fadeIn('normal');$('#gridSearchOn{$gridId}').hide();return false;">Turn Search Off &laquo;</a>
						</div>
						<div style="float:right;">
							<form action="/" method="get" style="padding:0px;margin:0px;" onsubmit="setOneYearCookie('Grid{$gridId}SearchString',$('#gridSearch{$gridId}').val());$('#grid{$gridId}').load('{$gridProcessingUrl}{request gridid=$gridId gridpage=1 gajax=1 gridpersist=$grid->getPersistenceObject()}&amp;gridsearch='+encodeURIComponent($('#gridSearch{$gridId}').val()));return false;">
								<p style="padding:0px;margin:0px;">
								Search: <input type="text" name="gridSearch{$gridId}" id="gridSearch{$gridId}" size="30" value="{$grid->getSearchString()}" />&nbsp;<input class="grid{$theme}Button" type="submit" name="sbtn" value="Search"/>
								</p>
							</form>
						</div>
						<div style="clear:both;"></div>
					</div>
				</td>
			</tr>
			{/if}
			<tr>
				{foreach name=columnNames from=$gridColumns item=column}
				{if $column->getShow()}
				<td class="gridHeader grid{$theme}HBR {if $smarty.foreach.columnNames.first} grid{$theme}HBL{/if} grid{$theme}Header" style="white-space:nowrap;{if $column->getStretch()}width:100%;{/if}">
					{$column->getTitle()}
					{if $column->isSortable()}&nbsp;<a href="#" onclick="$('#grid{$gridId}').load('{$gridProcessingUrl}{request gridid=$gridId gridpage=$gridPageNumber gajax=1 gridsortcolumn=$column->getIndex() gridsortup=1 gridsearch=$grid->getSearchString() gridpersist=$grid->getPersistenceObject()}');setOneYearCookie('Grid{$gridId}SortColumnIndex',{$column->getIndex()});setOneYearCookie('Grid{$gridId}SortColumnUp',1);return false;"><img class="grid{$theme}" src="{$themepath}/images/arrow.{if $column->isSorted() && $column->isSortedUp()}active{else}inactive{/if}.up.gif" alt="u" /></a>{/if}
					{if $column->isSortable()}<a href="#" onclick="$('#grid{$gridId}').load('{$gridProcessingUrl}{request gridid=$gridId gridpage=$gridPageNumber gajax=1 gridsortcolumn=$column->getIndex() gridsortup=0 gridsearch=$grid->getSearchString() gridpersist=$grid->getPersistenceObject()}');setOneYearCookie('Grid{$gridId}SortColumnIndex',{$column->getIndex()});setOneYearCookie('Grid{$gridId}SortColumnUp',0);return false;"><img class="grid{$theme}" src="{$themepath}/images/arrow.{if $column->isSorted() && !$column->isSortedUp()}active{else}inactive{/if}.down.gif" alt="d" /></a>{/if}
				</td>
				{/if}
				{/foreach}
			</tr>

			{if $grid->getTotalRows() == 0}
			<tr>
				<td align="center" colspan="{$grid->getColumnCount()}" class="grid{$theme}DBR grid{$theme}RowEven grid{$theme}DBL grid{$theme}DBB" style="padding:10px;">
					<em>{$grid->getNoRecordsFoundMessage()}</em>
				</td>
			</tr>
			{else}

			{assign var=colorIndex value=0}
			{assign var=colorChanged value=false}
			{assign var=rowColorCount value=-1}
			{assign var=sortValue value=''}
			{assign var=sortColumn value=$grid->getSortedColumn()}

			{if $sortColumn}
				{assign var=sortColumnIndex value=$sortColumn->getIndex()}
				{assign var=sortColumnSortedUp value=$sortColumn->getSortedUp()}
			{else}
				{assign var=sortColumnIndex value=0}
				{assign var=sortColumnSortedUp value=false}
			{/if}

			{foreach name=rowData from=$gridData item=columnData}

				{if $grid->isColorBySort() && $sortColumn && ($sortValue != $columnData.$sortColumnIndex)}
					{assign var=rowColorCount value=$rowColorCount+1}
					{assign var=colorIndex value=$rowColorCount%9}
					{assign var=sortValue value=$columnData.$sortColumnIndex}
					{assign var=colorChanged value=true}
				{/if}

			<tr class="{if $grid->isColorBySort()}grid{$theme}RowSort{$colorIndex}{if $colorChanged} grid{$theme}RowSortChange{/if}{else}grid{$theme}Row{if $smarty.foreach.rowData.index % 2 == 0}Even{else}Odd{/if}{/if}">
				{foreach name=columnData from=$columnData item=data}
				{assign var=column value=$gridColumns[$smarty.foreach.columnData.index]}
				{if $column->getShow()}
				<td class="gridData grid{$theme}DBR {if $smarty.foreach.columnData.first} grid{$theme}DBL{/if}{if $smarty.foreach.rowData.last} grid{$theme}DBB{/if}" style="padding:{$gridColumns[$smarty.foreach.columnData.index]->getPadding()}px;{if !$column->isWrap()}white-space:nowrap;{/if}text-align:{$column->getTextAlign()};">
					{eval var=$data|default:"&nbsp;"}
				</td>
				{/if}
				{/foreach}
				{assign var=colorChanged value=false}
			</tr>
			{/foreach}
			{/if}
			
			{if ! $grid->isStaticData()}
			
			<tr>
				<td colspan="{$grid->getColumnCount()}" class="grid{$theme}Trailer">

					<div style="float:left;">
						{$grid->getTotalDescription()}: {$grid->getTotalRows()}
						&nbsp;&nbsp;
						Viewing Page {$gridPageNumber} of {$gridTotalPages}
					
						{if $gridTotalPages > 1}
						<br/>
					
						<div id="grid{$theme}Pages">
							{if $gridTotalPages > 1 && $gridPageNumber > 1}
							<a href="#" onclick="$('#grid{$gridId}').load('{$gridProcessingUrl}{request gridid=$gridId gridpage=1 gajax=1 gridsortcolumn=$sortColumnIndex gridsortup=$sortColumnSortedUp gridsearch=$grid->getSearchString() gridpersist=$grid->getPersistenceObject()}');return false;">&lt;&lt; First</a>
							<a href="#" onclick="$('#grid{$gridId}').load('{$gridProcessingUrl}{request gridid=$gridId gridpage=$gridPageNumber-1 gajax=1 gridsortcolumn=$sortColumnIndex gridsortup=$sortColumnSortedUp gridsearch=$grid->getSearchString() gridpersist=$grid->getPersistenceObject()}');return false;">&lt; 	Previous</a>
							{/if}
							
							{assign var=gridPageSeparator value="..."}
							{section name=pg start=1 loop=$gridTotalPages+1 step=1}
								{if $smarty.section.pg.index < 4 || $smarty.section.pg.index > $gridTotalPages-3 || $smarty.section.pg.index == $gridPageNumber || $smarty.section.pg.index == $gridPageNumber-1 || $smarty.section.pg.index == $gridPageNumber+1}
									<a href="#"{if $smarty.section.pg.index == $gridPageNumber} id="grid{$theme}CurrentPage"{/if} onclick="$('#grid{$gridId}').load('{$gridProcessingUrl}{request gridid=$gridId gridpage=$smarty.section.pg.index gajax=1 gridsortcolumn=$sortColumnIndex gridsortup=$sortColumnSortedUp gridsearch=$grid->getSearchString() gridpersist=$grid->getPersistenceObject()}');return false;">{$smarty.section.pg.index}</a>						
									{assign var=gridPageSeparator value="..."}
								{else}
									{$gridPageSeparator}
								{assign var=gridPageSeparator value=""}
								{/if}
							{/section}
							
							{if $gridTotalPages > $gridPageNumber}
							<a href="#" onclick="$('#grid{$gridId}').load('{$gridProcessingUrl}{request gridid=$gridId gridpage=$gridPageNumber+1 gajax=1 gridsortcolumn=$sortColumnIndex gridsortup=$sortColumnSortedUp gridsearch=$grid->getSearchString() gridpersist=$grid->getPersistenceObject()}');return false;">Next &gt;</a>
							<a href="#" onclick="$('#grid{$gridId}').load('{$gridProcessingUrl}{request gridid=$gridId gridpage=$gridTotalPages gajax=1 gridsortcolumn=$sortColumnIndex gridsortup=$sortColumnSortedUp gridsearch=$grid->getSearchString() gridpersist=$grid->getPersistenceObject()}');return false;">Last &gt;&gt;</a>
							{/if}
						</div>
						{/if}
					
					</div>
					<div style="float:right;" id="grid{$theme}ResultsPerPage">
						<div id="grid{$theme}ResultsPerPageLink">
							<a href="#" onclick="$('#grid{$theme}ResultsPerPageList').fadeIn();$('#grid{$theme}ResultsPerPageLink').hide();return false;">Rows Per Page &gt;&gt;</a><br/><div class="grid{$theme}FinePrint">Currently Viewing {$grid->getRowsPerPage()} Rows Per Page</div>
						</div>
						<div id="grid{$theme}ResultsPerPageList" style="display:none;">
							Number of Rows Per Page:
							{section name=rpp start=5 loop=55 step=5}
							<a href="#"{if $smarty.section.rpp.index == $grid->getRowsPerPage()} id="grid{$theme}CurrentRowPerPage"{/if} onclick="$('#grid{$gridId}').load('{$gridProcessingUrl}{request gridid=$gridId gridpage=1 gajax=1 gridsortcolumn=$sortColumnIndex gridsortup=$sortColumnSortedUp gridsearch=$grid->getSearchString() grpp=$smarty.section.rpp.index gridpersist=$grid->getPersistenceObject()}');setOneYearCookie('Grid{$gridId}RowsPerPage',{$smarty.section.rpp.index});return false;">{$smarty.section.rpp.index}</a>
							{/section}
							{section name=rpp start=50 loop=110 step=10}
							<a href="#"{if $smarty.section.rpp.index == $grid->getRowsPerPage()} id="grid{$theme}CurrentRowPerPage"{/if} onclick="$('#grid{$gridId}').load('{$gridProcessingUrl}{request gridid=$gridId gridpage=1 gajax=1 gridsortcolumn=$sortColumnIndex gridsortup=$sortColumnSortedUp gridsearch=$grid->getSearchString() grpp=$smarty.section.rpp.index gridpersist=$grid->getPersistenceObject()}');setOneYearCookie('Grid{$gridId}RowsPerPage',{$smarty.section.rpp.index});return false;">{$smarty.section.rpp.index}</a>
							{/section}
							<a href="#" onclick="$('#grid{$theme}ResultsPerPageLink').fadeIn();$('#grid{$theme}ResultsPerPageList').hide();return false;">&lt;&lt; Rows Per Page</a>
						</div>
					</div>
				</td>
			</tr>
			{/if}
		</table>
	</div>
</div>