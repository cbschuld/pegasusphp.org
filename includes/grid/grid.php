<?php
	require_once( 'propel/util/PropelPager.php' );

	/**
	 * @package PegasusPHP
	 */
	interface iGrid	{
		public function initialize();
		public function getData();
	}

	/**
	 * @package PegasusPHP
	 */
	class GridFilterCriterion {
		public $column;
		public $value;
		public $comparison;
	}
	/**
	 * @package PegasusPHP
	 */
	class GridFilter {
		private $_strTitle = '';
		private $_aCriterion = array();
		private $_index = -1;
		private $_bSelected = false;
		private $_bHotFilter = false;

		public function getCriterionCount()	           { return count( $this->_aCriterion ); }
		public function getTitle()                     { return $this->_strTitle; }
		public function getCriterion($index)           { assert('$index < $this->getCriterionCount()'); return $this->_aCriterion[$index]; }
		public function getCriterionColumn($index)     { assert('$index < $this->getCriterionCount()'); return $this->_aCriterion[$index]->column; }
		public function getCriterionValue($index)      { assert('$index < $this->getCriterionCount()'); return $this->_aCriterion[$index]->value; }
		public function getCriterionComparison($index) { assert('$index < $this->getCriterionCount()'); return $this->_aCriterion[$index]->comparison; }
		public function getIndex()                     { return $this->_index; }
		public function getSelected()                  { return $this->_bSelected; }
		public function getHotFilter()                  { return $this->_bHotFilter; }

		public function isSelected() { return $this->_bSelected; }
		public function isHotFilter() { return $this->_bHotFilter; }

		public function &setTitle($str)        { $this->_strTitle = $str; return $this; }
		public function &setIndex($i)          { $this->_index = $i; return $this; }
		public function &setSelected($b=true)  { $this->_bSelected = $b; return $this; }
		public function &setHotFilter($b=true) { $this->_bHotFilter = $b; return $this; }

		public function &setCriterion($c,$v,$s=null) {
			$criterion = new GridFilterCriterion();
			$criterion->column = $c;
			$criterion->value = $v;
			$criterion->comparison = $s;
			array_push($this->_aCriterion,$criterion);
			return $this;
		}

		public function __construct( $strTitle ) {
			$this->_strTitle = $strTitle;
		}

	}

	/**
	 * @package PegasusPHP
	 */
	class GridIcon {
		private $_bShow = true;
		private $_title;
		private $_url;
		private $_onclick;
		private $_confirmMessage = '';
		private $_icon;
		private $_id = '';
		private $_target = '';
		private $_iconWidth = 24;
		private $_iconHeight = 24;
		private $_linkCssClass = '';
		private $_iconCssClass = '';
		private $_enabled = true;

		private $_disabled_title = "";
		private $_disabled_url = "";
		private $_disabled_icon = "";
		private $_disabled_onclick = "";

		public function GridIcon($title,$url,$icon,$width=0,$height=0) {
			$this->setTitle($title);
			$this->setUrl($url);
			$this->setIcon($icon);
			if( $width != 0 ) { $this->setIconWidth($width); }
			if( $height == 0 && $width != 0 ) { $height = $width; }
			if( $height != 0 ) { $this->setIconHeight($height); }
			return $this;
		}

		public function setIconHeight($iVal) { $this->_iconHeight = $iVal; }
		public function setIconWidth($iVal) { $this->_iconWidth = $iVal; }
		public function setShow($bVal) { $this->_bShow = $bVal; }
		public function setTitle($str) {
			$this->_title = $str;
			if( $this->_disabled_title == "" ) {
				$this->_disabled_title = $str;
			}
		}
		public function setUrl($str) {
			$this->_url = $str;
			if( $this->_disabled_url == "" ) {
				$this->_disabled_url = $str;
			}
		}
		public function setIcon($str) {
			$this->_icon = $str;
			if( $this->_disabled_icon == "" ) {
				$this->_disabled_icon = $str;
			}
		}
		public function setOnclick($str) {
			$this->_onclick = $str;
			if( $this->_disabled_onclick == "" ) {
				$this->_disabled_onclick = $str;
			}
		}

		public function setId($id) { $this->_id = $id; }
		public function setConfirmMessage($msg) { $this->_confirmMessage = str_replace("'",'', str_replace('"','', $msg)); }
		public function setTarget($target) { $this->_target = $target; }
		public function setLinkCssClass($class) { $this->_linkCssClass = $class; }
		public function setIconCssClass($class) { $this->_iconCssClass = $class; }
		public function setEnabled($b) { $this->_enabled = $b; }
		public function setDisabledTitle($str) { $this->_disabled_title = $str; }
		public function setDisabledUrl($str) { $this->_disabled_url = $str; }
		public function setDisabledIcon($str) { $this->_disabled_icon = $str; }
		public function setDisabledOnclick($str) { $this->_disabled_onclick = $str; }

		public function getTitle() { return ( $this->isEnabled() ? $this->_title : $this->_disabled_title ); }
		public function getUrl() {
			if (is_array($this->_id)) {
				$patt = array();
				$repl = array();
				foreach ($this->_id as $k => $v) {
					$patt[] = '/\{' . $k . '\}/';
					$repl[] = $v;
				}
				return preg_replace($patt, $repl, ( $this->isEnabled() ? $this->_url : $this->_disabled_url ) );
			} else {
				return str_replace( '{ID}', $this->_id, ( $this->isEnabled() ? $this->_url : $this->_disabled_url ) );
			}
		}
		public function getOnclick() {
			return str_replace( '{ID}', $this->_id, ( $this->isEnabled() ? $this->_onclick : $this->_disabled_onclick ) );
		}
		public function getIcon() { return ( $this->isEnabled() ? $this->_icon : $this->_disabled_icon ); }

		public function getIconWidth() { return $this->_iconWidth; }
		public function getIconHeight() { return $this->_iconHeight; }
		public function getId() { return $this->_id; }
		public function getShow() { return $this->_bShow; }
		public function getConfirmMessage() { return $this->_confirmMessage; }
		public function getTarget() { return $this->_target; }
		public function getLinkCssClass() { return $this->_linkCssClass; }
		public function getIconCssClass() { return $this->_iconCssClass; }
		public function getEnabled() { return $this->_enabled; }
		public function isEnabled() { return $this->_enabled; }



		public function getLink() {
			$xhtml = '';
			if( $this->getShow() ) {
				if( $this->isEnabled() ) {
					$xhtml .= '<a title="'.$this->getTitle().'" ';
					if( $this->getConfirmMessage() != '' ) {
						$xhtml .= 'onclick="if( confirm(\''.$this->getConfirmMessage().'\') ) window.location=\''.$this->getUrl().'\';return false;" ';
					}
					else {
						if( $this->getOnclick() != '' ) { $xhtml .= 'onclick="'.$this->getOnclick().'" '; }
					}
					if( $this->getUrl() != '' && $this->getConfirmMessage() == '' ) { $xhtml .= 'href="' . $this->getUrl() . '" '; } else { $xhtml .= 'href="javascript:;" '; }
					if ($this->getTarget() != '') { $xhtml .= 'target="' . $this->getTarget() . '" '; }
					if ($this->getLinkCssClass() != '') { $xhtml .= ' class="' . $this->getLinkCssClass() . '" '; }
					$xhtml .= '>';
				}
				$xhtml .= '<img width="'.$this->getIconWidth().'" height="'.$this->getIconHeight().'" src="'.$this->getIcon().'" alt="'.$this->getTitle().'"';
				if ($this->getIconCssClass() != '') {
					$xhtml .= ' class="' . $this->getIconCssClass() . '"';
				}
				$xhtml .= '/>';
				if( $this->isEnabled() ) {
					$xhtml .= '</a>';
				}
			}
			return $xhtml;
		}
	}

	/**
	 * @package PegasusPHP
	 */
	class GridColumn {
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
		public function &setAlignCenter()       { $this->_strTextAlign = "center"; return $this; }
		public function &setAlignLeft()         { $this->_strTextAlign = "left"; return $this; }
		public function &setAlignRight()        { $this->_strTextAlign = "right"; return $this; }

	}

	/**
	 * @package PegasusPHP
	 */
	class Grid implements iGrid {

		private $_strId = 'grid';

		private $_iPageNumber = 1;
		private $_iTotalPages = 1;
		private $_iTotalRows = 0;
		private $_iRowsPerPage = 10;
		private $_cookie_set = false;
		
		private $_strProcessingUrl = '?';

		private $_strThemePath = '';
		private $_strThemeName = 'Grey';

		private $_column = array();
		private $_filter = array();

		private $_persistenceObject;

		private $_bIsAjax = false;
		private $_bIsStatic = false;
		private $_bIsReadOnly = false;
		private $_strSearchString = '';
		private $_bSearchable = true;
		private $_bLoadedFromRequest = false;
		private $_strTotalDescription = 'Total:';
		private $_bColorBySort = false;
		private $_strNoRecordsFoundMessage = 'no records found';

		public function getId()                    { return $this->_strId; }
		public function getTotalPages()            { return (int)$this->_iTotalPages; }
		public function getTotalRows()             { return (int)$this->_iTotalRows; }
		public function getRowsPerPage()           { return (int)$this->_iRowsPerPage; }
		public function getPageNumber()            { return (int)$this->_iPageNumber; }
		public function getProcessingUrl()         { return $this->_strProcessingUrl; }
		public function getSearchString()          { return $this->_strSearchString; }
		public function getTotalDescription()      { return $this->_strTotalDescription; }
		public function getThemePath()             { return $this->_strThemePath; }
		public function getThemeName()             { return $this->_strThemeName; }
		public function getColorBySort()           { return $this->_bColorBySort; }
		public function getNoRecordsFoundMessage() { return $this->_strNoRecordsFoundMessage; }
		public function getSearchable()            { return $this->_bSearchable; }
		public function getStaticData()            { return $this->_bIsStatic; }
		public function getReadOnly()              { return $this->_bIsReadOnly; }
		public function getPersistenceObject()     { $this->loadFromRequest(); return $this->_persistenceObject; }

		public function isColorBySort()            { return $this->_bColorBySort; }
		public function isSearchable()             { return $this->_bSearchable; }
		public function isStaticData()             { return $this->_bIsStatic; }
		public function isReadOnly()               { return $this->_bIsReadOnly; }

		public function setId($str)                    { $this->_strId = $str; }
		public function setTotalPages($i)              { $this->_iTotalPages = $i; }
		public function setTotalRows($i)               { $this->_iTotalRows = $i; }
		public function setRowsPerPage($i)             { $this->_iRowsPerPage = $i; }
		public function setPageNumber($i)              { $this->_iPageNumber = $i; }
		public function setProcessingUrl($str)         { $this->_strProcessingUrl = $str; }
		public function setSearchString($str)          { $this->_strSearchString = $str; }
		public function setTotalDescription($str)      { $this->_strTotalDescription = $str; }
		public function setThemePath($str)             { $this->_strThemePath = $str; }
		public function setThemeName($str)             { $this->_strThemeName = $str; }
		public function setNoRecordsFoundMessage($str) { $this->_strNoRecordsFoundMessage = $str; }
		public function setColorBySort($b=true)        { $this->_bColorBySort = $b; }
		public function setSearchable($b=true)         { $this->_bSearchable = $b; }
		public function setStaticData($b=true)         { $this->_bIsStatic = $b; }
		public function setReadOnly($b=true)           { $this->_bIsReadOnly = $b; }
		public function setPersistenceObject($obj=true){ $this->_persistenceObject = $obj; }



		public function __construct($callInitialize=true) {
			$this->setThemePath( dirname(__FILE__) . '/themes/' );
			if( $callInitialize ) {
				$this->initialize();
			}
		}

		public function isSortable() {
			$column = null;
			for($i = 0; $column == null &&  $i < count( $this->_column ); $i++ ) {
				if( $this->_column[$i]->isSortable() ) {
					$column = $this->_column[$i];
				}
			}
			return (bool)($column != null);
		}

		public function getRefreshJS() {
			return "$('#grid{$this->getId()}').load('{$this->getProcessingUrl()}?gridid={$this->getId()}&amp;gajax=1&amp;gridpage={$this->getPageNumber()}&amp;gridpersist={$this->getPersistenceObject()}');";
		}

		public function getReloadUrl() {
			return "{$this->getProcessingUrl()}?gridid={$this->getId()}&amp;gajax=1&amp;gridpage={$this->getPageNumber()}&amp;gridpersist={$this->getPersistenceObject()}";
		}


		public function isAjax() {
			$this->loadFromRequest();
			return $this->_bIsAjax;
		}

		private function getFullThemePathname($strFilename='') {
			return( $this->getThemePath() . '/' . $this->getThemeName() . '/' . $strFilename );
		}

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
			return $this->getSortedColumn()->getIndex();
		}
		public function getSortedUp() {
			return $this->getSortedColumn()->isSortedUp();
		}

		public function clearColumns() {
			$this->_column = array();
		}

		public function addColumn($column) {
			$column->setIndex( count($this->_column) );
			$this->_column[] = $column;
		}
		public function &createColumn($strTitle,$strColumn='') {
			$column = new GridColumn($strTitle,$strColumn);
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

		public function getSelectedFilter() {
			$filter = null;
			for($i = 0; $filter == null &&  $i < count( $this->_filter ); $i++ ) {
				if( $this->_filter[$i]->isSelected() ) {
					$filter = $this->_filter[$i];
				}
			}
			return $filter;
		}
		public function addFilter($filter) {
			$filter->setIndex( count($this->_filter) );
			$this->_filter[] = $filter;
		}

		public function selectFilter($filter) {
			for($i = 0; $i < count( $this->_filter ); $i++ ) {
				$this->_filter[$i]->setSelected( $this->_filter[$i] == $filter );
			}
		}

		public function &createFilter($strTitle,$criteria=null) {
			$filter = new GridFilter($strTitle,$criteria);
			$this->addFilter($filter);
			return $filter;
		}
		public function getFilter($index) {
			return $this->_filter[$index];
		}
		public function getFilters() {
			return $this->_filter;
		}
		public function getFilterCount() {
			return count($this->_filter);
		}

		public function initialize() {}

		private function initializeSorting() {

			assert( '$this->getColumnCount() > 0' );

			$column = $this->getSortedColumn();

			if( $column == null ) {
				if( isset( $_COOKIE['Grid'.$this->getId().'SortColumnIndex'] ) ) {
					$columnIndex = (int)$_COOKIE['Grid'.$this->getId().'SortColumnIndex'];
					if( $columnIndex < $this->getColumnCount() ) {
						$column = $this->getColumn( $columnIndex );
						$column->setSorted(true);
						if( isset( $_COOKIE['Grid'.$this->getId().'SortColumnUp'] ) ) {
							$column->setSortedUp((bool)$_COOKIE['Grid'.$this->getId().'SortColumnUp']);
						}
						else {
							$column->setSortedUp(false);
						}
					}
				}
			}

			for($i = 0; $column == null &&  $i < count( $this->_column ); $i++ ) {
				if( $this->_column[$i]->isSortable() ) {
					$column = $this->_column[$i];
					$this->_column[$i]->setSorted(true);
					$this->_column[$i]->setSortedUp(true);
				}
			}

			if( $column == null ) {
				$this->getColumn(0)->setSorted(true);
			}

		}

		private function initializeFiltering() {

			if( $this->getFilterCount() > 0 ) {

				$filter = $this->getSelectedFilter();

				if( $filter == null ) {
					if( isset( $_COOKIE['Grid'.$this->getId().'FilterIndex'] ) ) {
						$filterIndex = (int)$_COOKIE['Grid'.$this->getId().'FilterIndex'];
						if( $filterIndex < $this->getFilterCount() ) {
							$filter = $this->getFilter( $filterIndex );
							$filter->setSelected(true);
						}
					}
				}

				for($i = 0; $filter == null &&  $i < count( $this->_filter ); $i++ ) {
					if( $this->_filter[$i]->isSelected() ) {
						$filter = $this->_filter[$i];
						$this->_filter[$i]->setSelected(true);
					}
				}

				if( $filter == null ) {
					$this->getFilter(0)->setSelected(true);
				}
			}
		}

		private function loadFromRequest() {

			if( ! Request::exists('grpp') && isset( $_COOKIE['Grid'.$this->getId().'RowsPerPage'] ) ) {
				$this->setRowsPerPage( (int)$_COOKIE['Grid'.$this->getId().'RowsPerPage'] );
			}

			if( ! $this->_bLoadedFromRequest && Request::get('gridid') == $this->getId() ) {
				$this->_bIsAjax = (bool)Request::get('gajax');

				if( Request::exists('grpp') ) {
					$this->setRowsPerPage((int)Request::get('grpp'));
				}

				if( Request::exists('gridfilter') ) {
					foreach( $this->_filter as $filter ) {
						$filter->setSelected( Request::get('gridfilter') == $filter->getIndex() );
					}
				}

				if( Request::exists('gridsortcolumn') ) {

					$iSortedColumn = (int)Request::get('gridsortcolumn');
					$bSortedUp = (bool)Request::get('gridsortup');

					foreach( $this->_column as $column ) {
						$column->setSorted( $iSortedColumn == $column->getIndex() );
						$column->setSortedUp( $iSortedColumn == $column->getIndex() && $bSortedUp );
					}
				}

				$this->setPersistenceObject(Request::get('gridpersist'));
				$this->setSearchString( Request::get('gridsearch') );

				$this->_bLoadedFromRequest = true;
			}

			if( Request::exists('gridpage') ) {
				$this->setPageNumber( (int)Request::get('gridpage') );
				if( ! $this->_cookie_set ) {
					setcookie('Grid'.$this->getId().'gridpage',(int)Request::get('gridpage'));
					$this->_cookie_set = true;
				}
			}
			else if( isset( $_COOKIE['Grid'.$this->getId().'gridpage'] ) ) {
				$this->setPageNumber( (int)$_COOKIE['Grid'.$this->getId().'gridpage'] );
			}

			if( $this->getSearchString() == '' && isset( $_COOKIE['Grid'.$this->getId().'SearchString'] ) ) {
				$this->setSearchString( $_COOKIE['Grid'.$this->getId().'SearchString'] );
			}

		}

		public function isGridStretched() {
			$bRetVal = false;
			for($i = 0; !$bRetVal &&  $i < count( $this->_column ); $i++ ) {
				$bRetVal = $this->_column[$i]->isStretch();
			}
			return $bRetVal;
		}

		public function getContent() {

			$this->loadFromRequest();
			$this->initializeSorting();
			$this->initializeFiltering();

			$strTemplate = $this->getFullThemePathname('grid.tpl');
			$strRetVal = '';

			if( View::template_exists( $strTemplate ) ) {

				$gridData = $this->getData();

				//assert( 'count($gridData) == count($this->getColumns())' );

				View::assign('grid', $this );
				View::assign('gridData', $gridData );
				View::assign('gridSearch', $this->getSearchString() );
				View::assign('gridProcessingUrl', $this->getProcessingUrl() . (!strstr($this->getProcessingUrl(),'?') ? '?' : '' ) );

				$strRetVal = View::fetch( $strTemplate );
			}
			else {
				Pegasus::error('No Template Found','Error, unable to locate grid template for theme: ' . $this->getThemeName() );
			}
			return str_replace("\t",'',str_replace("\r\n",'',$strRetVal));
		}

		public function applyPropelCriteria(&$criteria) {
			$this->addPropelFiltering($criteria);
			$this->addPropelSearchCriteria($criteria);
			$this->addPropelSorting($criteria);
		}

		public function addPropelFiltering(&$criteria) {
			$filter = $this->getSelectedFilter();
			if( $filter ) {
				for( $i = 0; $i < $filter->getCriterionCount(); $i++ ) {
					$criterion = $filter->getCriterion($i);
					$criteria->addAnd( $criteria->getNewCriterion( $criterion->column, $criterion->value, $criterion->comparison ) );
				}
			}
		}

		public function addPropelSearchCriteria(&$criteria) {
			$bAdded = false;
			if( $this->getSearchString() != '' ) {
				$searchCriteria = new Criteria();
				foreach( $this->_column as $column ) {
					if( $column->isSearchable() ) {

						$likeString = str_replace('%^','',str_replace('$%','','%'.$this->getSearchString().'%'));

						if( ! $bAdded ) {
							$searchCriteria = $criteria->getNewCriterion( $column->getColumnName(), $likeString, Criteria::LIKE);
							$searchCriteria->addOr( $criteria->getNewCriterion( $column->getColumnName(), $this->getSearchString(), Criteria::EQUAL ) );
							$bAdded = true;
						}
						else {
							$searchCriteria->addOr( $criteria->getNewCriterion( $column->getColumnName(), $likeString, Criteria::LIKE) );
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
					$criteria->addAscendingOrderByColumn($sortColumn->getColumnName());
				} else {
					$criteria->addDescendingOrderByColumn($sortColumn->getColumnName());
				}
			}
		}

		public function endswith( $haystack, $needle) {
			return ( strncmp(substr($haystack,strlen($haystack)-strlen($needle)), $needle,strlen($needle)) == 0 );
		}
		public function beginswith( $haystack, $needle) {
			return ( strncmp($haystack,$needle,strlen($needle)) == 0 );
		}

		public function getData() {
			return array();
		}
	}

?>
