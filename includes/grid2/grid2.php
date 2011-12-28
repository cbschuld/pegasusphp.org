<?php

	/**
	 * @package PegasusPHP
	 */
	interface iGrid2	{
		public function getData();
	}


	/**
	 * @package PegasusPHP
	 */
	class Grid2 {
		
		private $_id = 'G2';
		private $_base_css_class = 'humanity';
		private $_base_image_url = '/pegasus/includes/grid2/themes/humanity';
		private $_processing_url = '?';
		private $_title = '';
		private $_searchable = true;
		private $_show_footer = true;
		private $_column = array();
		private $_filter = array();
		private $_footer_message = 'Page [P] of [G] &middot; viewing [R] of [T] records';
		
		private $_strSearchString = '';
		private $_iPageNumber = 1;
		private $_iTotalPages = 1;
		private $_iTotalRows = 0;
		private $_iRowsPerPage = 5;

		private $_session_cache_propel_critiera = false;
		
		public function setSessionCachePropelCritiera($value) { $this->_session_cache_propel_critiera = $value; }
		public function getSessionCachePropelCritiera() { return $this->_session_cache_propel_critiera; }

		public function getTrailerMessage() {
			return str_replace('[T]','<span id="grid2-totalrows-'.$this->getId().'">'.$this->getTotalRows().'</span>',
						str_replace('[R]','<span id="grid2-rowsperpage-'.$this->getId().'">'.$this->getRowsPerPage().'</span>',
							str_replace('[P]','<span id="grid2-pagenumber-'.$this->getId().'">'.$this->getPageNumber().'</span>',
								str_replace('[G]','<span id="grid2-totalpages-'.$this->getId().'">'.$this->getTotalPages().'</span>', $this->_footer_message )
							)
						)
					);
		}
		
		public function setTrailerMessage($message) { $this->_footer_message = $message; }
		public function setShowTrailerMessage($b) { $this->_show_footer = $b; }
		public function getShowTrailerMessage() { return $this->_show_footer; }
		
		public function getBaseCssClass() { return $this->_base_css_class; }
		public function setBaseCssClass($classname) { $this->_base_css_class = $classname; }
		public function getBaseImageUrl() { return $this->_base_image_url; }
		public function setBaseImageUrl($baseurl) { $this->_base_image_url = $baseurl; }
		
		public function setTheme($themename) { $this->_base_css_class = $themename; $this->_base_image_url = '/pegasus/includes/grid2/themes/'.$themename; }
		
		public function getTitle() { return $this->_title; }
		public function setTitle($title) { $this->_title = $title; }
		public function getSearchable() { return $this->_searchable; }
		public function setSearchable($value) { $this->_searchable = $value; }
		
		public function getId()                    { return $this->_id; }
		public function getTotalPages()            { return (int)$this->_iTotalPages; }
		public function getTotalRows()             { return (int)$this->_iTotalRows; }
		public function getRowsPerPage()           { return (int)$this->_iRowsPerPage; }
		public function getPageNumber()            { return (int)$this->_iPageNumber; }
		public function getProcessingUrl()         { return $this->_processing_url; }
		public function getSearchString()          { return $this->_strSearchString; }
		
		public function setId($str)                    { $this->_id = preg_replace('[^a-zA-Z0-9]', '', $str); }
		public function setTotalPages($i)              { $this->_iTotalPages = $i; }
		public function setTotalRows($i)               { $this->_iTotalRows = $i; }
		public function setRowsPerPage($i)             { $this->_iRowsPerPage = $i; }
		public function setPageNumber($i)              { $this->_iPageNumber = $i; }
		public function setProcessingUrl($str)         { $this->_processing_url = $str; }
		public function setSearchString($str)          { $this->_strSearchString = $str; }
		
		public function getSortedColumn() {
			$column = null;
			for($i = 0; $column == null &&  $i < count( $this->_column ); $i++ ) {
				if( $this->_column[$i]->isSortable() && $this->_column[$i]->isSorted() ) {
					$column = $this->_column[$i];
				}
			}
			return $column;
		}
		public function getSortedIndex() {
			return ($this->getSortedColumn() ? $this->getSortedColumn()->getIndex() : -1 );
		}
		public function getSortedUp() {
			return ($this->getSortedColumn() ? $this->getSortedColumn()->isSortedUp() : false);
		}
		public function getSortedUpText() {
			return $this->getSortedUp() ? 'true':'false';
		}
		
		public function clearColumns() {
			$this->_column = array();
		}

		public function isGridStretched() {
			$bRetVal = false;
			for($i = 0; !$bRetVal &&  $i < count( $this->_column ); $i++ ) {
				$bRetVal = $this->_column[$i]->isStretch();
			}
			return $bRetVal;
		}
		
		public function addColumn($column) {
			$column->setIndex( count($this->_column) );
			$this->_column[] = $column;
		}
		public function &createColumn($strTitle,$strColumn='') {
			$column = new Grid2Column($strTitle,$strColumn);
			$this->addColumn($column);
			return $column;
		}
		public function getColumn($index) {
			return $this->_column[$index];
		}
		public function getColumns() {
			return $this->_column;
		}
		public function getColumnCount() {
			return count($this->_column);
		}

		
		public function process() {
			
			$gridId = isset($_POST['gid']) ? $_POST['gid'] : '';
			if( $gridId == $this->getId() ) {
				$this->processRequest();
				echo $this->getJsonOutput();
				exit; // AJAX RETURN
			}
			return false;
		}
		
		private function getJsonOutput() {
			$data = $this->getTranslatedData();
			$json = array(
					'data' => $this->getTranslatedData(),
					'rowcount' => count($data),
					'totalrows' => $this->getTotalRows(),
					'rowsperpage' => $this->getRowsPerPage(),
					'pagenumber' => $this->getPageNumber(),
					'totalpages' => $this->getTotalPages(),
					'filterindex' => $this->getSelectedFilterIndex(),
					'sortindex' => $this->getSortedIndex(),
					'sortedup' => $this->getSortedUp()
				);
			return json_encode($json);
		}
		
		private function getOutputTop() {
			$columnCount = $this->getColumnCount();
			$xhtml = '';
			$xhtml .= '<tr class="'.$this->_base_css_class.'-top-banner">';
			$xhtml .= '<td colspan="'.$this->getColumnCount().'" class="'.$this->_base_css_class.'-top-banner">';
			if( $this->_title != '' || $this->_searchable) {
				$xhtml .= '<table class="'.$this->_base_css_class.'-top-banner"><tr>';
				$xhtml .= '<th id="'.$this->getBaseCssClass().'-loader"></th>';
				if( $this->_title != '' ) { $xhtml .= '<th>'.$this->_title.'</th>'; $columnCount--; }
				if( $this->_searchable ) {
					$xhtml .= '<td id="grid2-search-statement-'.$this->getId().'" class="'.$this->getBaseCssClass().'-search-statement"></td>';
					$xhtml .= '<td>Search:&nbsp;<input type="text" id="grid2-search-'.$this->getId().'" class="'.$this->_base_css_class.'" value="'.$this->getSearchString().'"/><button onclick="searchGrid'.$this->getId().'(jQuery(\'#grid2-search-'.$this->getId().'\').val());return false;" class="'.$this->_base_css_class.'">Go</button></td>';
				}
				$xhtml .= '</tr></table>';
			}
			$xhtml .= '</td>';
			$xhtml .= '</tr>';
			
			return $xhtml;
		}
		
		private function getFilterTop() {
			$columnCount = $this->getColumnCount();
			$xhtml = '';
			$xhtml .= '<tr class="'.$this->_base_css_class.'-filtertop">';
			$xhtml .= '<td colspan="'.$this->getColumnCount().'">';
			if( $this->_title != '' || $this->_searchable) {
				for($i = count($this->_filter)-1; $i >= 0 ; $i-- ) {
					$xhtml .= '<div id="grid2-'.$this->getId().'-filter-'.$this->_filter[$i]->getIndex().'" class="'.$this->_base_css_class.'-filter'.($this->_filter[$i]->isSelected() ? ' '.$this->_base_css_class.'-filter-selected':'').'">';
					$xhtml .= '<a href="javascript:;" onclick="gridFilter'.$this->getId().'('.$this->_filter[$i]->getIndex().');return false;" class="'.$this->_base_css_class.'-filter">';
					$xhtml .= $this->_filter[$i]->getTitle();
					$xhtml .= '</a>';
					$xhtml .= '</div>';
				}
			}
			$xhtml .= '</td>';
			$xhtml .= '</tr>';
			
			return $xhtml;
		}
		
		private function getOutputHeader() {
			$xhtml = '<tr class="'.$this->_base_css_class.'-header">';
			foreach($this->getColumns() as $column ) {
				if( $column->getShow() ) {
					$xhtml .= '<th>';
				}
				else {
					$xhtml .= '<th class="'.$this->getBaseCssClass().'-noshow">';
				}
				if( $column->isSortable() ) {
					$xhtml .= '<a title="Sort by '.$column->getTitle().'" id="column-'.$column->getIndex().'-'.$this->getId().'" class="'.$this->getBaseCssClass().'-sortable '.$this->getBaseCssClass().'-sortable-column" href="javascript:;" onclick="gridSort'.$this->getId().'('.$column->getIndex().');return false;">';
				}
				$xhtml .= $column->getTitle();
				if( $column->isSortable() ) {
					$xhtml .= '</a>';
				}
				$xhtml .= '</th>';
			}
			$xhtml .= '</tr>';
			return $xhtml;			
		}
		
		private function getOutputTrailer() {
			$xhtml = '';
			if( $this->_show_footer ) {
				$xhtml .= '<tr class="'.$this->_base_css_class.'-footer">';
				$xhtml .= '<td colspan="'.$this->getColumnCount().'">';
				
				$xhtml .= '<table class="'.$this->_base_css_class.'-footer-banner"><tr><td style="white-space:nowrap;">';
				
				$xhtml .= '<a title="Go to the First Page" href="javascript:;" id="grid2-first-link-'.$this->getId().'" onclick="firstPage'.$this->getId().'();return false;">';
				$xhtml .= '<img alt="First Page" src="'.$this->_base_image_url.'/first.png" />';
				$xhtml .= '</a>';
				
				$xhtml .= '<a title="Go to the Previous Page" href="javascript:;" id="grid2-previous-link-'.$this->getId().'" onclick="previousPage'.$this->getId().'();return false;">';
				$xhtml .= '<img alt="Previous Page" src="'.$this->_base_image_url.'/previous.png" />';
				$xhtml .= '</a>';
				
				$xhtml .= '<a title="Go to a specific page" href="javascript:;" id="'.$this->getId().'-pageslink">';
				$xhtml .= '<img alt="Jump to a Page" src="'.$this->_base_image_url.'/up.png" />';
				$xhtml .= '</a>';
				
				$xhtml .= '<a title="Go to the Next Page" href="javascript:;" id="grid2-next-link-'.$this->getId().'" onclick="nextPage'.$this->getId().'();return false;">';
				$xhtml .= '<img alt="Next Page" src="'.$this->_base_image_url.'/next.png" />';
				$xhtml .= '</a>';
				
				$xhtml .= '<a title="Go to the Last Page" href="javascript:;" id="grid2-last-link-'.$this->getId().'" onclick="lastPage'.$this->getId().'();return false;">';
				$xhtml .= '<img alt="Last Page" src="'.$this->_base_image_url.'/last.png" />';
				$xhtml .= '</a>';

				$xhtml .= '</td>';
				$xhtml .= '<td class="'.$this->_base_css_class.'-expand">';
				$xhtml .= $this->getTrailerMessage();
				$xhtml .= '</td>';

				$xhtml .= '<td><a href="javascript:;" id="'.$this->getId().'-rowslink">viewing <span id="grid2-rowsperpage-link-'.$this->getId().'">'.$this->getRowsPerPage(). '</span> rows per page</a>';
				$xhtml .= '</td>';
				
				$xhtml .= '</tr>';
				$xhtml .= '</table>';
				
				$xhtml .= '</td>';
				$xhtml .= '</tr>';
			}
			return $xhtml;
		}
		
		private function getEmptyRow() {
			$xhtml = '';
			$xhtml .= '<tr class="'.$this->_base_css_class.'-row">';
			for($i = 0; $i < $this->getColumnCount(); $i++ ) {
				$xhtml .= $this->getColumnDataMarkup($i,'');
			}
			$xhtml .= '</tr>'; 
			return $xhtml;
		}
		private function getNoResultsRow() {
			$xhtml = '<tr class="'.$this->_base_css_class.'-no-results"><td colspan="'.$this->getColumnCount().'"><p><br/><br/><br/>Sorry, your search returned no results<br/><br/><br/></p></td></tr>';
			// Important to note that we must create a placeholder data row (with cells) so the resizer does not crash:
			$xhtml .= '<tr class="' . $this->_base_css_class . '-row" style="display:none;">';
			for( $i = 0; $i < $this->getColumnCount(); $i++ ) { $xhtml .= '<td class="' . $this->_base_css_class . '-nowrap"></td>'; }
			$xhtml .= '</tr>';
			return $xhtml;
		}
		private function getEmptyRowJs() {
			return str_replace('/','\/',$this->getEmptyRow());
		}
		
		private function getColumnDataMarkup($columnNumber,$internalMarkup) {
			$classes = array();
			$classMarkup = '';
			// TODO fix this to report error instead
			if( count($this->_column) > $columnNumber ) {
				if( $this->_column[$columnNumber]->isStretch() ) { $classes[] = $this->getBaseCssClass().'-column-stretch'; }
				if( ! $this->_column[$columnNumber]->isWrap() ) { $classes[] = $this->getBaseCssClass().'-nowrap'; }
				if( ! $this->_column[$columnNumber]->getShow() ) { $classes[] = $this->getBaseCssClass().'-noshow'; }
				if( $classes ) {
					$classMarkup = 'class="';
					foreach($classes as $class) {
						$classMarkup .= ' '.$class;
					}
					$classMarkup = ' '.trim($classMarkup.'"');
				}	
				return '<td'.$classMarkup.'>'.$internalMarkup.'</td>';
			}
			return "";
		}
		
		private function getOutputRows() {
			$xhtml = '';
			$data = $this->getTranslatedData();
			for($r = 0; $r < count($data); $r++ ) {
				$xhtml .= '<tr class="'.$this->_base_css_class.'-row">';
				for($c = 0; $c < count($data[$r]); $c++) {
					$xhtml .= $this->getColumnDataMarkup($c,$data[$r][$c]);
				}
				$xhtml .= '</tr>';
			}
			return $xhtml;
		}
		
		protected function getData() {
			return array();
		}
		
		protected function getTranslatedData() {
			$data = $this->getData();
			for($r = 0; $r < count($data); $r++ ) {
				for($c = 0; $c < count($data[$r]); $c++) {
					if( is_array($data[$r][$c]) ) {
						$translation = "";
						for($a = 0; $a < count($data[$r][$c]); $a++ ) {
							if( is_object($data[$r][$c][$a]) ) {
								 $translation .= $data[$r][$c][$a]->getMarkup();
							}
						}
						$data[$r][$c] = $translation;
					}
					else if( is_object($data[$r][$c]) ) {
						$data[$r][$c] = $data[$r][$c]->getMarkup();
					}
				}
			}
			return $data;
		}
		
		private function processRequest() {

			$pageNumber = (int)(isset($_POST['pagenumber']) ? $_POST['pagenumber'] : 1);
			$sortIndex = (int)(isset($_POST['sortindex']) ? $_POST['sortindex'] : (isset($_COOKIE['grid2-'.$this->getId().'-sort-index']) ? $_COOKIE['grid2-'.$this->getId().'-sort-index'] : 0));
			$sortedUp = (bool)(isset($_POST['sortedup']) ? ($_POST['sortedup'] == 'true' ? true : false ) : (isset($_COOKIE['grid2-'.$this->getId().'-sorted-up']) ? $_COOKIE['grid2-'.$this->getId().'-sorted-up'] : false));
			$rowsPerPage = (int)(isset($_POST['rowsperpage']) ? $_POST['rowsperpage'] : (isset($_COOKIE['grid2-'.$this->getId().'-rowsperpage']) ? $_COOKIE['grid2-'.$this->getId().'-rowsperpage'] : $this->getRowsPerPage()));
			$searchstring = isset($_POST['searchstring']) ? $_POST['searchstring'] : (isset($_COOKIE['grid2-'.$this->getId().'-searchstring']) ? $_COOKIE['grid2-'.$this->getId().'-searchstring'] : '');
			$filterIndex = (int)(isset($_POST['filterindex']) ? $_POST['filterindex'] : (isset($_COOKIE['grid2-'.$this->getId().'-filter-index']) ? $_COOKIE['grid2-'.$this->getId().'-filter-index'] : 0));
			
			$this->setSearchString($searchstring);
			$this->setPageNumber((int)$pageNumber);
			$this->setRowsPerPage($rowsPerPage);
			
			foreach( $this->_column as $column ) {
				$column->setSorted(false);
				if($column->getIndex() == $sortIndex ) {
					$column->setSorted(true);
					$column->setSortUp($sortedUp);
				}
			}
			foreach( $this->_filter as $filter ) {
				$filter->setSelected(false);
				if($filter->getIndex() == $filterIndex ) {
					$filter->setSelected(true);
				}
			}
		}
		
		public function getOutput() {
			
			$this->processRequest();
			
			$tableClasses = ( $this->isGridStretched() ? (' '.$this->getBaseCssClass().'-stretch') : '' );
			
			$xhtml = <<<EOT
			
				<!-- Begin Grid2 -->
				<div class="{$this->_base_css_class}"><table class="{$this->_base_css_class}{$tableClasses}" id="grid2-{$this->getId()}">
					<thead>
						{$this->getFilterTop()}
						{$this->getOutputTop()}
						{$this->getOutputHeader()}
					</thead>
					<tbody>
						{$this->getOutputRows()}
						{$this->getNoResultsRow()}
						{$this->getOutputTrailer()}
					</tbody>
				</table></div>
				
				<div id="{$this->getId()}-pages" class="{$this->getBaseCssClass()}-pages" style="position:absolute;display:none;z-index:1000;">
					<div id="{$this->getId()}-pages-internal-top" class="{$this->getBaseCssClass()}-pages-internal-top">Jump to Page Number: <span id="{$this->getId()}-jumpto-pagenumber"></span>
						<div id="{$this->getId()}-pages-slider" class="{$this->getBaseCssClass()}-page-slider"></div>
						<button onclick="jumpPage{$this->getId()}(jQuery('#{$this->getId()}-pages-slider').slider('option','value'));jQuery('#{$this->getId()}-pages').hide();return false;">Jump to Page</button>
					</div>
					<div class="{$this->getBaseCssClass()}-pages-internal-bottom">&nbsp;</div>
					<div style="clear:both;"></div>
				</div>

				<div id="{$this->getId()}-rows" class="{$this->getBaseCssClass()}-rows" style="position:absolute;display:none;z-index:1000;">
					<div id="{$this->getId()}-rows-internal-top" class="{$this->getBaseCssClass()}-rows-internal-top">
						<div style="text-align:center;padding:10px;">Number of Rows Per Page:
							<a href="javascript:;" onclick="jQuery('#{$this->getId()}-rows').hide();setRowsPerPage{$this->getId()}(5);return false;">5</a>
							<a href="javascript:;" onclick="jQuery('#{$this->getId()}-rows').hide();setRowsPerPage{$this->getId()}(10);return false;">10</a>
							<a href="javascript:;" onclick="jQuery('#{$this->getId()}-rows').hide();setRowsPerPage{$this->getId()}(15);return false;">15</a>
							<a href="javascript:;" onclick="jQuery('#{$this->getId()}-rows').hide();setRowsPerPage{$this->getId()}(20);return false;">20</a>
							<a href="javascript:;" onclick="jQuery('#{$this->getId()}-rows').hide();setRowsPerPage{$this->getId()}(25);return false;">25</a>
							<a href="javascript:;" onclick="jQuery('#{$this->getId()}-rows').hide();setRowsPerPage{$this->getId()}(50);return false;">50</a>
							<a href="javascript:;" onclick="jQuery('#{$this->getId()}-rows').hide();setRowsPerPage{$this->getId()}(75);return false;">75</a>
							<a href="javascript:;" onclick="jQuery('#{$this->getId()}-rows').hide();setRowsPerPage{$this->getId()}(100);return false;">100</a>
						</div>
					</div>
					<div class="{$this->getBaseCssClass()}-rows-internal-bottom">&nbsp;</div>
					<div style="clear:both;"></div>
				</div>

				<script type="text/javascript">
					/* <![CDATA[ */
					var grid2_{$this->getId()}_page_number = {$this->getPageNumber()};
					var grid2_{$this->getId()}_page_count = {$this->getTotalPages()};
					var grid2_{$this->getId()}_result_count = {$this->getTotalRows()};
					var grid2_{$this->getId()}_paused = false;
					var grid2_{$this->getId()}_base_css_class = '{$this->getBaseCssClass()}';
					var grid2_{$this->getId()}_searchstring = '{$this->getSearchString()}';
					var grid2_{$this->getId()}_rows_per_page = {$this->getRowsPerPage()};
					var grid2_{$this->getId()}_sort_index = {$this->getSortedIndex()};
					var grid2_{$this->getId()}_sorted_up = {$this->getSortedUpText()};
					var grid2_{$this->getId()}_filter_index = {$this->getSelectedFilterIndex()};
					
					jQuery.cookie{$this->getId()} = function(name, value, options) {if (typeof value != 'undefined') { options = options || {}; if (value === null) { value = ''; options.expires = -1; } var expires = ''; if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) { var date; if (typeof options.expires == 'number') { date = new Date(); date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000)); } else { date = options.expires; } expires = '; expires=' + date.toUTCString(); } var path = options.path ? '; path=' + (options.path) : '';var domain = options.domain ? '; domain=' + (options.domain) : '';var secure = options.secure ? '; secure' : ''; document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join(''); } else {var cookieValue = null; if (document.cookie && document.cookie != '') { var cookies = document.cookie.split(';'); for (var i = 0; i < cookies.length; i++) { var cookie = jQuery.trim(cookies[i]); if (cookie.substring(0, name.length + 1) == (name + '=')) { cookieValue = decodeURIComponent(cookie.substring(name.length + 1)); break; }}}return cookieValue;}};
					
					jQuery(function() {
						jQuery(document).ready( function() {
							jQuery('#{$this->getId()}-pages').mouseleave(function(e){ jQuery('#{$this->getId()}-pages').hide(); });
							jQuery('#{$this->getId()}-rows').mouseleave(function(e){ jQuery('#{$this->getId()}-rows').hide(); });
							jQuery('#{$this->getId()}-pages-slider').slider({animate:true,min:1,max:{$this->getTotalPages()},value:{$this->getPageNumber()},change:updateSlider{$this->getId()},slide:updateSlider{$this->getId()}});
							jQuery('#{$this->getId()}-jumpto-pagenumber').html(jQuery('#{$this->getId()}-pages-slider').slider('option','value')); 
							jQuery('#{$this->getId()}-pageslink').click(function(e) {
								pos = jQuery('#{$this->getId()}-rowslink').position();
								jQuery('#{$this->getId()}-pages').css({left:e.pageX-35,top:pos.top-jQuery('#{$this->getId()}-pages').height()+10}).show();
							});
							jQuery('#{$this->getId()}-rowslink').click(function(e) {
								pos = jQuery('#{$this->getId()}-rowslink').position();
								jQuery('#{$this->getId()}-rows').css({left:e.pageX-375,top:pos.top-jQuery('#{$this->getId()}-rows').height()+10}).show();
							});
							jQuery('#grid2-search-{$this->getId()}').keyup(function(e) {
								if(e.keyCode == 13) { searchGrid{$this->getId()}(jQuery('#grid2-search-{$this->getId()}').val()); }
							});
							zebraStripe{$this->getId()}()
							refreshNavigationButtons{$this->getId()}();
						});
					});
					
					function updateSlider{$this->getId()}(event, ui) {
						jQuery('#{$this->getId()}-jumpto-pagenumber').html(jQuery('#{$this->getId()}-pages-slider').slider('option','value'));
					}
					function zebraStripe{$this->getId()}() {
						var count = 0;
						jQuery('#grid2-{$this->getId()} > tbody > tr.{$this->getBaseCssClass()}-row').each( function() {
							jQuery(this).addClass('{$this->getBaseCssClass()}-row-'+(count%2==0?'even':'odd')).removeClass('{$this->getBaseCssClass()}-row-'+(count%2==1?'even':'odd'));
							count++;
						});
					}
					function clearGrid{$this->getId()}() {
						jQuery('#grid2-{$this->getId()} > tbody > tr > td').each( function() { jQuery(this).html('&nbsp;'); });
					}
					function pauseGrid{$this->getId()}(pause) {
						if( ( !pause && grid2_{$this->getId()}_paused ) || ( pause && !grid2_{$this->getId()}_paused ) ) {
							if( pause ) { grid2_{$this->getId()}_paused = pause; }
							jQuery('tr.{$this->getBaseCssClass()}-row:visible').fadeTo((pause?0:'fast'), pause ? 0.33 : 1,
									function(){
											if(pause){
												jQuery('#{$this->getBaseCssClass()}-loader').show();
											}
											else {
												grid2_{$this->getId()}_paused = pause;
												jQuery('#{$this->getBaseCssClass()}-loader').hide();
											}
									});
						}
					}
					function refreshNavigationButtons{$this->getId()}() {
						jQuery('#grid2-first-link-{$this->getId()}').show();
						jQuery('#grid2-previous-link-{$this->getId()}').show();
						jQuery('#grid2-next-link-{$this->getId()}').show();
						jQuery('#grid2-last-link-{$this->getId()}').show();
						jQuery('#{$this->getId()}-pageslink').show();
						if( grid2_{$this->getId()}_page_number == 1 ) {
							jQuery('#grid2-first-link-{$this->getId()}').hide();
							jQuery('#grid2-previous-link-{$this->getId()}').hide();
						}
						else if( grid2_{$this->getId()}_page_number == {$this->getTotalPages()} ) {
							jQuery('#grid2-next-link-{$this->getId()}').hide();
							jQuery('#grid2-last-link-{$this->getId()}').hide();
						}
						
						if( grid2_{$this->getId()}_searchstring != '' ) {
							jQuery('#grid2-search-statement-{$this->getId()}').show().html('Your Search for <span class="{$this->getBaseCssClass()}-search-word">'+grid2_{$this->getId()}_searchstring+'</span> found '+grid2_{$this->getId()}_result_count+' results - <a href="javascript:;" onclick="clearSearch{$this->getId()}();return false;">cancel your search<\/a>');
						} else {
							jQuery('#grid2-search-statement-{$this->getId()}').hide();
						}

						if( grid2_{$this->getId()}_page_count == 1 ) {
							jQuery('#grid2-next-link-{$this->getId()}').hide();
							jQuery('#grid2-last-link-{$this->getId()}').hide();
							jQuery('#{$this->getId()}-pageslink').hide();
						}
						
						if( grid2_{$this->getId()}_result_count == 0 ) {
							jQuery('.{$this->getBaseCssClass()}-no-results').show();
						}
						else {
							jQuery('.{$this->getBaseCssClass()}-no-results').hide();
						}
						showSorting{$this->getId()}();
					}
					function nextPage{$this->getId()}() {
						changePage{$this->getId()}(grid2_{$this->getId()}_page_number+1);
						reloadGrid{$this->getId()}();
					}
					function previousPage{$this->getId()}() {
						changePage{$this->getId()}(grid2_{$this->getId()}_page_number-1);
						reloadGrid{$this->getId()}();
					}
					function firstPage{$this->getId()}() {
						changePage{$this->getId()}(1);
						reloadGrid{$this->getId()}();
					}
					function lastPage{$this->getId()}() {
						changePage{$this->getId()}(grid2_{$this->getId()}_page_count);
						reloadGrid{$this->getId()}();
					}
					function jumpPage{$this->getId()}(pagenumber) {
						changePage{$this->getId()}(pagenumber);
						reloadGrid{$this->getId()}();
					}
					function changePage{$this->getId()}(pagenumber) {
						grid2_{$this->getId()}_page_number = pagenumber;
						jQuery('#{$this->getId()}-pages-slider').slider('option','value',pagenumber);
						updateSlider{$this->getId()}();
						if( grid2_{$this->getId()}_page_number <= 0 ) { grid2_{$this->getId()}_page_number = 1; }
						if( grid2_{$this->getId()}_page_number > grid2_{$this->getId()}_page_count ) { grid2_{$this->getId()}_page_number = {$this->getTotalPages()}; }
					}
					function clearSearch{$this->getId()}() {
						jQuery.cookie{$this->getId()}('grid2-{$this->getId()}-searchstring',null);
						grid2_{$this->getId()}_searchstring = "";
						grid2_{$this->getId()}_page_number = 1;
						jQuery('#grid2-search-{$this->getId()}').val("");
						reloadGrid{$this->getId()}();
					}
					function searchGrid{$this->getId()}(searchstring) {
						jQuery.cookie{$this->getId()}('grid2-{$this->getId()}-searchstring',searchstring);
						grid2_{$this->getId()}_searchstring = searchstring;
						grid2_{$this->getId()}_page_number = 1;
						jQuery('#grid2-search-{$this->getId()}').val(searchstring);
						reloadGrid{$this->getId()}();
					}
					function gridSort{$this->getId()}(index) {
						if( grid2_{$this->getId()}_sort_index == index ) { 
							grid2_{$this->getId()}_sorted_up = !grid2_{$this->getId()}_sorted_up;
						}
						grid2_{$this->getId()}_sort_index = index;
						jQuery.cookie{$this->getId()}('grid2-{$this->getId()}-sort-index',index);
						jQuery.cookie{$this->getId()}('grid2-{$this->getId()}-sort-up',grid2_{$this->getId()}_sorted_up);
						reloadGrid{$this->getId()}();
					}
					function gridFilter{$this->getId()}(index) {
						grid2_{$this->getId()}_filter_index = index;
						jQuery.cookie{$this->getId()}('grid2-{$this->getId()}-filter-index',index);
						jQuery('#grid2-{$this->getId()} > thead > tr > td > div.{$this->getBaseCssClass()}-filter').removeClass('{$this->getBaseCssClass()}-filter-selected');
						jQuery('#grid2-{$this->getId()}-filter-'+index).addClass('{$this->getBaseCssClass()}-filter-selected');
						reloadGrid{$this->getId()}();
					}
					function reloadGrid{$this->getId()}() {
						pauseGrid{$this->getId()}(true);
						jQuery.post('{$this->getProcessingUrl()}',{gid:'{$this->getId()}',pagenumber:grid2_{$this->getId()}_page_number,rowsperpage:grid2_{$this->getId()}_rows_per_page,searchstring:grid2_{$this->getId()}_searchstring,sortindex:grid2_{$this->getId()}_sort_index,sortedup:grid2_{$this->getId()}_sorted_up,filterindex:grid2_{$this->getId()}_filter_index},loadGrid{$this->getId()},'json');
					}
					function setRowsPerPage{$this->getId()}(rows) {
						jQuery.cookie{$this->getId()}('grid2-{$this->getId()}-rowsperpage',rows);
						grid2_{$this->getId()}_rows_per_page = rows;
						reloadGrid{$this->getId()}();
					}
					function resizeGrid{$this->getId()}(rows) {
						while( jQuery('#grid2-{$this->getId()} > tbody > tr.{$this->getBaseCssClass()}-row').length < rows ) {
							jQuery('#grid2-{$this->getId()} > tbody > tr.{$this->getBaseCssClass()}-row').clone().show().prependTo("#grid2-{$this->getId()} > tbody");
						}
						zebraStripe{$this->getId()}();
					}

					
					function showSorting{$this->getId()}() {
						jQuery('.{$this->getBaseCssClass()}-sortable-column').removeClass('{$this->getBaseCssClass()}-sortup').removeClass('{$this->getBaseCssClass()}-sortdown').addClass('{$this->getBaseCssClass()}-sortable');
						if( grid2_{$this->getId()}_sort_index != -1 ) {
							jQuery('#column-'+grid2_{$this->getId()}_sort_index+'-{$this->getId()}').removeClass('{$this->getBaseCssClass()}-sortable').addClass('{$this->getBaseCssClass()}-sort'+(grid2_{$this->getId()}_sorted_up ? 'up' : 'down'));
						}
					}
					
					function loadGrid{$this->getId()}(json) {
						row = 0;
						col = 0;
						colmax = {$this->getColumnCount()};

						grid2_{$this->getId()}_page_number = json.pagenumber;
						grid2_{$this->getId()}_result_count = json.totalrows;
						grid2_{$this->getId()}_page_count = json.totalpages;
						grid2_{$this->getId()}_rows_per_page= json.rowsperpage;
						grid2_{$this->getId()}_sort_index = json.sortindex;
						grid2_{$this->getId()}_sorted_up = json.sortedup;
						jQuery('#grid2-jumpto-pagenumber-{$this->getId()}').html(json.pagenumber);
						jQuery('#grid2-totalrows-{$this->getId()}').html(json.totalrows);
						jQuery('#grid2-rowsperpage-{$this->getId()}').html(json.rowsperpage);
						jQuery('#grid2-rowsperpage-link-{$this->getId()}').html(json.rowsperpage);
						jQuery('#grid2-pagenumber-{$this->getId()}').html(json.pagenumber);
						jQuery('#grid2-totalpages-{$this->getId()}').html(json.totalpages);
						
						jQuery('#{$this->getId()}-pages-slider').slider('option','max',json.totalpages);
						
						resizeGrid{$this->getId()}(json.rowcount);
						
						jQuery('#grid2-{$this->getId()} > tbody > tr.{$this->getBaseCssClass()}-row').each(
								function() {
									if( json.data && json.data[row] ) {
										jQuery('td',this).each( function() {
											jQuery(this).html(json.data[row][col]);
											col++;
											if(col>=colmax){
												col=0;
												row++;
											}
										});
										jQuery(this).show();
									}
									else {
										jQuery(this).hide();
									}
								}
							);
						pauseGrid{$this->getId()}(false);
						refreshNavigationButtons{$this->getId()}();
					}
					/* ]]> */
				</script>
				<!-- End Grid2 -->
EOT;
			

			
			
			return $xhtml;
		}

		public function applyPropelCriteria(&$criteria) {
			$this->addPropelFiltering($criteria);
			$this->addPropelSearchCriteria($criteria);
			$this->addPropelSorting($criteria);
			if( $this->getSessionCachePropelCritiera() ) {
				$_SESSION[$this->getSessionCachePropelCriteriaSlug()] = serialize($criteria);
			}
		}
		
		public function getSelectedFilter() {
			$filter = null;
			for($i = 0; $filter == null &&  $i < count( $this->_filter ); $i++ ) {
				if( $this->_filter[$i]->isSelected() ) {
					$filter = $this->_filter[$i];
				}
			}
			return $filter;
		}
		public function getSelectedFilterIndex() {
			$filter = $this->getSelectedFilter();
			if( $filter ) { return $filter->getIndex(); }
			return 0;
		}
		public function addFilter($filter) {
			$filter->setIndex( count($this->_filter) );
			if( $this->getSelectedFilter() == null ) {$filter->setSelected(true);}
			$this->_filter[] = $filter;
		}

		public function selectFilter($filter) {
			for($i = 0; $i < count( $this->_filter ); $i++ ) {
				$this->_filter[$i]->setSelected( $this->_filter[$i] == $filter );
			}
		}

		public function &createFilter($strTitle,$criteria=null) {
			$filter = new Grid2Filter($strTitle,$criteria);
			$this->addFilter($filter);
			return $filter;
		}

		public function getCachedPropelCritiera() {
			return unserialize($_SESSION[$this->getSessionCachePropelCriteriaSlug()]);
		}

		private function getSessionCachePropelCriteriaSlug() {
			return 'grid2-'.$this->getId().'-propel-critiera';
		}

		public function addPropelFiltering(&$criteria) {
			$filter = $this->getSelectedFilter();
			if( $filter ) {
				
				if( $filter->getCriterionCount() == 1 ) {
					$criterion = $filter->getCriterion(0);
					$criteria->addAnd( $criteria->getNewCriterion( $criterion->column, $criterion->value, $criterion->comparison ) );
				}
				else if( $filter->getCriterionCount() > 1 ) {

					$criterion = $filter->getCriterion(0);
					$c = $criteria->getNewCriterion( $criterion->column, $criterion->value, $criterion->comparison );
					
					for( $i = 1; $i < $filter->getCriterionCount(); $i++ ) {
						
						$criterion = $filter->getCriterion($i);
						
						if( $criterion->or ) {
							$c->addOr( $criteria->getNewCriterion( $criterion->column, $criterion->value, $criterion->comparison ) );
						}
						else {
							$c->addAnd( $criteria->getNewCriterion( $criterion->column, $criterion->value, $criterion->comparison ) );
						}
					}
					$criteria->add($c);
				}
			}
			
		}

		public function addPropelSearchCriteria(&$criteria) {
			$bAdded = false;
			if( $this->getSearchString() != '' ) {
				$searchCriteria = new Criteria();
				foreach( $this->_column as $column ) {
					if( $column->isSearchable() ) {
						if( ! $bAdded ) {
							$searchCriteria = $criteria->getNewCriterion( $column->getColumnName(), '%'.$this->getSearchString().'%', Criteria::LIKE);
							$searchCriteria->addOr( $criteria->getNewCriterion( $column->getColumnName(), $this->getSearchString(), Criteria::EQUAL ) );
							$bAdded = true;
						}
						else {
							$searchCriteria->addOr( $criteria->getNewCriterion( $column->getColumnName(), '%'.$this->getSearchString().'%', Criteria::LIKE) );
							$searchCriteria->addOr( $criteria->getNewCriterion( $column->getColumnName(), $this->getSearchString(), Criteria::EQUAL ) );
						}
					}
				}
				if( $bAdded ) {
					$criteria->addAnd($searchCriteria);
				}
			}
		}

		public function addPropelSorting(&$criteria) {
			$sortColumn = $this->getSortedColumn();
			if( $sortColumn != null ) {
				if( $sortColumn->isSortedUp() ) {
					$criteria->addDescendingOrderByColumn($sortColumn->getColumnName());
				} else {
					$criteria->addAscendingOrderByColumn($sortColumn->getColumnName());
				}
			}
		}
		
		
		
	}
	
	
	
	
	/**
	 * @package PegasusPHP
	 */
	class Grid2Column {
		private $_bStretch = false;
		private $_strTitle = 'n/a';
		private $_strColumnName = '';
		private $_bWrap = false;
		private $_iPadding = 1;
		private $_bSortable = false;
		private $_bSearchable = false;
		private $_bSorted = false;
		private $_bSortedUp = true;
		private $_bShow = true;
		private $_index = -1;
		private $_strTextAlign = 'left';

		public function __construct( $strTitle, $strColumnName='', $bSortable=false, $bWrap=false, $bStretch=false, $iPadding=2 ) {
			$this->_strTitle = $strTitle;
			$this->_strColumnName = $strColumnName;
			$this->_bSortable = $bSortable;
			$this->_bWrap = $bWrap;
			$this->_bStretch = $bStretch;
			$this->_iPadding = $iPadding;
		}
		public function getTitle()      { return $this->_strTitle; }
		public function getColumnName() { return $this->_strColumnName; }
		public function getPadding()    { return $this->_iPadding; }
		public function getIndex()      { return $this->_index; }
		public function getStretch()    { return $this->_bStretch; }
		public function getWrap()       { return $this->_bWrap; }
		public function getSortable()   { return $this->_bSortable; }
		public function getSorted()     { return $this->_bSorted; }
		public function getSortedUp()   { return $this->_bSortedUp; }
		public function getSearchable() { return $this->_bSearchable; }
		public function getShow()       { return $this->_bShow; }
		public function getTextAlign()  { return $this->_strTextAlign; }
		
		public function isStretch()    { return $this->_bStretch; }
		public function isWrap()       { return $this->_bWrap; }
		public function isSortable()   { return $this->_bSortable; }
		public function isSorted()     { return $this->_bSorted; }
		public function isSortedUp()   { return $this->_bSortedUp; }
		public function isSearchable() { return $this->_bSearchable; }

		public function &setTitle($str)         { $this->_strTitle = $str; return $this; }
		public function &setColumnName($str)    { $this->_strColumnName = $str; return $this; }
		public function &setPadding($i)         { $this->_iPadding = $i; return $this; }
		public function &setIndex($i)           { $this->_index = $i; return $this; }
		public function &setStretch($b=true)    { $this->_bStretch = $b; return $this; }
		public function &setWrap($b=true)       { $this->_bWrap = $b; return $this; }
		public function &setSortable($b=true)   { $this->_bSortable = $b; return $this; }
		public function &setSorted($b=true)     { $this->_bSorted = $b; return $this; }
		public function &setSortedUp($b=true)   { $this->_bSortedUp = $b; return $this; }
		public function &setSortedDown($b=true) { $this->_bSortedUp = ! $b; return $this; }
		public function &setSortUp($b=true)     { $this->_bSortedUp = $b; return $this; }
		public function &setSortDown($b=true)   { $this->_bSortedUp = ! $b; return $this; }
		public function &setSearchable($b=true) { $this->_bSearchable = $b; return $this; }
		public function &setShow($b=true)       { $this->_bShow = $b; return $this; }
		public function &setTextAlign($s)       { $this->_strTextAlign = $s; return $this; }
		
	}
	
	
	/**
	 * @package PegasusPHP
	 */
	class Grid2Icon {
		private $_bShow = true;
		private $_title;
		private $_url;
		private $_onclick;
		private $_confirmMessage = '';
		private $_icon;
		private $_id = '';
		private $_target = '';
		private $_linkCssClass = '';
		private $_iconCssClass = '';

		public function Grid2Icon($title,$icon,$url='',$onclick='') {
			$this->setTitle($title);
			$this->setIcon($icon);
			$this->setUrl($url);
			$this->setOnclick($onclick);
			return $this;
		}

		public function setShow($bVal) { $this->_bShow = $bVal; }
		public function setTitle($str) { $this->_title = $str; }
		public function setUrl($str) { $this->_url = $str; }
		public function setOnclick($str) { $this->_onclick = $str; }
		public function setIcon($str) { $this->_icon = $str; }
		public function setId($id) { $this->_id = $id; }
		public function setConfirmMessage($msg) { $this->_confirmMessage = str_replace("'",'', $msg); }
		public function setTarget($target) { $this->_target = $target; }
		public function setLinkCssClass($class) { $this->_linkCssClass = $class; }
		public function setIconCssClass($class) { $this->_iconCssClass = $class; }

		public function getTitle() { return $this->_title; }
		public function getUrl() {
			if(is_array($this->_id)) {
				$patt = array();
				$repl = array();
				foreach ($this->_id as $k => $v) {
					$patt[] = '/\{' . $k . '\}/';
					$repl[] = $v;
				}
				return preg_replace($patt, $repl, $this->_url);
			} else {
				$url = str_replace( '{ID}', $this->_id, $this->_url );
				if( preg_match( "/{request ([^}]+)}/", $url, $requestmatches) ) {
					if( preg_match_all('/ ?([^=]+)=([^ }]+)/', $requestmatches[1], $matches) ) {
						$requestItems = array();
						for( $matchCount = 0; $matchCount < count($matches[1]); $matchCount++ ) {
							$requestItems[ $matches[1][$matchCount] ] = $matches[2][$matchCount];
						}
						$request = Request::createRequest($requestItems);
					}
					return preg_replace("/{request ([^}]+)}/", $request, $url);
				}
				return $url;
			}
		}
		public function getOnclick() { return str_replace( '{ID}', $this->_id, $this->_onclick ); }
		public function getIcon() { return $this->_icon; }
		public function getId() { return $this->_id; }
		public function getShow() { return $this->_bShow; }
		public function getConfirmMessage() { return $this->_confirmMessage; }
		public function getTarget() { return $this->_target; }
		public function getLinkCssClass() { return $this->_linkCssClass; }
		public function getIconCssClass() { return $this->_iconCssClass; }

		public function getMarkup() {
			$xhtml = '';
			if( $this->getShow() ) {
				$xhtml .= '<a title="'.$this->getTitle().'" ';
				if( $this->_confirmMessage != '' ) {
					$xhtml .= 'onclick="if( confirm(\''.$this->getConfirmMessage().'\') ) window.location=\''.$this->getUrl().'\';return false;" ';
				}
				else {
					if( $this->_onclick != '' ) { $xhtml .= 'onclick="'.$this->getOnclick().'" '; }
				}
				if( $this->_url != '' ) { $xhtml .= 'href="' . $this->getUrl() . '" '; } else { $xhtml .= 'href="javascript:;" '; }
				if ($this->_target != '') { $xhtml .= 'target="' . $this->_target . '" '; }
				if ($this->_linkCssClass != '') { $xhtml .= ' class="' . $this->_linkCssClass . '" '; }
				$xhtml .= '>';
				$xhtml .= '<img src="'.$this->getIcon().'" alt="'.$this->getTitle().'"';
				$xhtml .= ' class="grid2icon' . ( $this->_iconCssClass != "" ? " {$this->_iconCssClass}" : "" ) . '"';
				$xhtml .= '/>';
				$xhtml .= '</a>';
			}
			return $xhtml;
		}
	}
	
	/**
	 * @package PegasusPHP
	 */
	class Grid2FilterCriterion {
		public $column = null;
		public $value = null;
		public $comparison = null;
		public $or = false;
	}
	
	/**
	 * @package PegasusPHP
	 */
	class Grid2Filter {
		private $_strTitle = '';
		private $_aCriterion = array();
		private $_index = -1;
		private $_bSelected = false;
		private $_bHotFilter = false;

		public function getCriterionCount()	           { return count( $this->_aCriterion ); }
		public function getTitle()                     { return $this->_strTitle; }
		public function getCriterion($index)           { return $this->_aCriterion[$index]; }
		public function getCriterionColumn($index)     { return $this->_aCriterion[$index]->column; }
		public function getCriterionValue($index)      { return $this->_aCriterion[$index]->value; }
		public function getCriterionComparison($index) { return $this->_aCriterion[$index]->comparison; }
		public function getIndex()                     { return $this->_index; }
		public function getSelected()                  { return $this->_bSelected; }
		public function getHotFilter()                  { return $this->_bHotFilter; }

		public function isSelected() { return $this->_bSelected; }
		public function isHotFilter() { return $this->_bHotFilter; }

		public function &setTitle($str)        { $this->_strTitle = $str; return $this; }
		public function &setIndex($i)          { $this->_index = $i; return $this; }
		public function &setSelected($b=true)  { $this->_bSelected = $b; return $this; }
		public function &setHotFilter($b=true) { $this->_bHotFilter = $b; return $this; }

		public function &setCriterion($c,$v,$s=null,$addAsOr=false) {
			$criterion = new Grid2FilterCriterion();
			$criterion->column = $c;
			$criterion->value = $v;
			$criterion->comparison = ( $s == null ? Criteria::EQUAL : $s );
			$criterion->or = $addAsOr;
			array_push($this->_aCriterion,$criterion);
			return $this;
		}
		public function &addOrCriterion($c,$v,$s=null) {
			return $this->setCriterion($c,$v,$s,true);
		}
		public function &addAndCriterion($c,$v,$s=null) {
			return $this->setCriterion($c,$v,$s,false);
		}
		
		public function __construct( $strTitle ) {
			$this->_strTitle = $strTitle;
		}

	}

	
?>