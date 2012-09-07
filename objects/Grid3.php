<?php

	/**
	 * @package PegasusPHP
	 */
	interface iGrid3	{
		public function getData();
	}


	/**
	 * @package PegasusPHP
	 */

	class Grid3 implements iGrid3 {

		private $_column = array();
		private $_id = "Grid3";
		private $_var_name = '';
		private $_processing_url = "";

		private $__sEcho = 0;
		private $__iTotalRecords = 0;
		private $__iTotalDisplayRecords = 0;

		private $__iDisplayStart = 0;
		private $__iDisplayLength = 0;
		private $__iColumns = 0;
		private $__sSearch = "";
		private $__iSortingCols = 0;

		private $__defaultSortColumn = 0;
		private $__defaultSortColumnOrder = '';

		private $_filterParameters = '';
		private $_drawCallback = '';

		public function Grid3() {
		}

		public function setDefaultSortColumn($iCol,$sOrder='desc') {
			if($this->getColumn($iCol)) {
				$this->__defaultSortColumn = $iCol;
				if($sOrder == 'asc') {
					$this->__defaultSortColumnOrder = 'asc';
				} else {
					$this->__defaultSortColumnOrder = 'desc';
				}
			}
		}

		public function getId() { return $this->_id; }

		public function setVarName($val='') { $this->_var_name = $val; }
		public function getVarName() { return $this->_var_name; }
		
		public function setRowStart($value){ $this->_row_start = $value; }

		public function setFilterParameters($value) { $this->_filterParameters = $value; }
		public function getFilterParameters() { return $this->_filterParameters; }

		public function setDrawCallback($value) {
			$this->_drawCallback = $value;
		}
		public function getDrawCallback() {
			return $this->_drawCallback;
		}

		public function setTotalRows($value){ $this->__iTotalRecords = $value; }
		public function setTotalDisplayRecords($value){ $this->__iTotalDisplayRecords = $value; }

		public function getRowsPerPage() { return $this->__iDisplayLength; }
		public function getPageNumber() { return $this->__iDisplayLength == 0 ? 1 : $this->__iDisplayStart / $this->__iDisplayLength +1; }

		public function getSearchString() { return $this->__sSearch; }

		public function loadFromRequest() {

			/*
			 * Type			Name				Info
			 * ---------------------------------------------------------------------------------------------------------------------
			 * int			iDisplayStart		Display start point
			 * int			iDisplayLength		Number of records to display
			 * int			iColumns			Number of columns being displayed (useful for getting individual column search info)
			 * string		sSearch				Global search field
			 * boolean		bEscapeRegex		Global search is regex or not
			 * boolean		bSortable_(int)		Indicator for if a column is flagged as sortable or not on the client-side
			 * boolean		bSearchable_(int)	Indicator for if a column is flagged as searchable or not on the client-side
			 * string		sSearch_(int)		Individual column filter
			 * boolean		bEscapeRegex_(int)	Individual column filter is regex or not
			 * int			iSortingCols		Number of columns to sort on
			 * int			iSortCol_(int)		Column being sorted on (you will need to decode this number for your database)
			 * string		sSortDir_(int)		Direction to be sorted - "desc" or "asc". Note that the prefix for this variable is wrong in 1.5.x where iSortDir_(int) was used)
			 * string		sEcho				Information for DataTables to use for rendering
			 */


			$this->__sEcho = (int)Request::get("sEcho");
			$this->__iDisplayStart = (int)Request::get("iDisplayStart");
			$this->__iDisplayLength = (int)Request::get("iDisplayLength");
			$this->__iColumns = (int)Request::get("iColumns");
			$this->__sSearch = Request::get("sSearch");
			$this->__iSortingCols = (int)Request::get("iSortingCols");

		}

		public function process() {

			/*
			 * Type			Name					Info
			 * ---------------------------------------------------------------------------------------------------------------------
			 * int			iTotalRecords			Total records, before filtering (i.e. the total number of records in the database)
			 * int			iTotalDisplayRecords	Total records, after filtering (i.e. the total number of records after filtering has been applied - not just the number of records being returned in this result set)
			 * string		sEcho					An unaltered copy of sEcho sent from the client side. This parameter will change with each draw (it is basically a draw count) - so it is important that this is implemented. Note that it strongly recommended for security reasons that you 'cast' this parameter to an integer in order to prevent Cross Site Scripting (XSS) attacks.
			 * string		sColumns				Optional - this is a string of column names, comma separated (used in combination with sName) which will allow DataTables to reorder data on the client-side if required for display
			 * array		aaData					The data in a 2D array
			 */

			$this->loadFromRequest();

			$resultData = $this->getData();

			return json_encode(
						array(
									"iTotalRecords" => (int)$this->__iTotalRecords,
									"iTotalDisplayRecords" => (int)$this->__iTotalDisplayRecords,
									"sEcho" => (int)$this->__sEcho,
									"aaData" => $resultData
							)
				);

		}


		public function addColumn($column) {
			$column->setIndex( count($this->_column) );
			$this->_column[] = $column;
		}
		public function &createColumn($strTitle,$strColumn='') {
			$column = new Grid3Column($strTitle,$strColumn);
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



		public function applyPropelCriteria(&$criteria) {
			$this->addPropelFiltering($criteria);
			$this->addPropelSearchCriteria($criteria);
			$this->addPropelSorting($criteria);
		}
		public function addPropelFiltering(&$criteria) {
//			$filter = $this->getSelectedFilter();
//			if( $filter ) {
//
//				if( $filter->getCriterionCount() == 1 ) {
//					$criterion = $filter->getCriterion(0);
//					$criteria->addAnd( $criteria->getNewCriterion( $criterion->column, $criterion->value, $criterion->comparison ) );
//				}
//				else if( $filter->getCriterionCount() > 1 ) {
//
//					$criterion = $filter->getCriterion(0);
//					$c = $criteria->getNewCriterion( $criterion->column, $criterion->value, $criterion->comparison );
//
//					for( $i = 1; $i < $filter->getCriterionCount(); $i++ ) {
//
//						$criterion = $filter->getCriterion($i);
//
//						if( $criterion->or ) {
//							$c->addOr( $criteria->getNewCriterion( $criterion->column, $criterion->value, $criterion->comparison ) );
//						}
//						else {
//							$c->addAnd( $criteria->getNewCriterion( $criterion->column, $criterion->value, $criterion->comparison ) );
//						}
//					}
//					$criteria->add($c);
//				}
//			}

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
			for( $c = 0; $c < (int)Request::get("iSortingCols"); $c++ ) {
				$index = (int)Request::get("iSortCol_{$c}");
				if( isset($this->_column[$index]) && $this->_column[$index]->isSortable() ) {
					if( Request::get("sSortDir_{$c}") == "asc" ) {
						$criteria->addAscendingOrderByColumn( $this->_column[$index]->getColumnName() );
					}
					else {
						$criteria->addDescendingOrderByColumn( $this->_column[$index]->getColumnName() );
					}
				}
			}
		}







		public function getColumnList() {
			return array();
		}

		public function getData() {
			return array();
		}

		public function setId($str) {
			$this->_id = preg_replace('[^a-zA-Z0-9]', '', $str);
		}
		public function getProcessingUrl()         { return $this->_processing_url; }
		public function setProcessingUrl($str)         { $this->_processing_url = $str; }





		public function getInitialMarkup() {
			$xhtmlHeader = "";
			$xhtmlFooter = "";
			$xhtmlColumns = "";
			for( $c = 0; $c < $this->getColumnCount(); $c++ ) {
				$xhtmlHeader .= "<th><div style=\"float: left;\">";
				$xhtmlHeader .= $this->getColumn($c)->getTitle();
				$xhtmlHeader .= "</div></th>";
				$xhtmlFooter .= "<th><div style=\"float: left;\">";
				$xhtmlFooter .= $this->getColumn($c)->getTitle();
				$xhtmlFooter .= "</div></th>";

				$xhtmlColumns .= "/* {$this->getColumn($c)->getTitle()} */ {bSearchable: ";
				$xhtmlColumns .= ( $this->getColumn($c)->isSearchable() ? "true" : "false" );
				$xhtmlColumns .= ",";
				$xhtmlColumns .= "bSortable: ";
				$xhtmlColumns .= ( $this->getColumn($c)->isSortable() ? "true" : "false" );
				$xhtmlColumns .= ",";
				$xhtmlColumns .= "bVisible: ";
				$xhtmlColumns .= ( $this->getColumn($c)->getShow() ? "true" : "false" );
				if( $this->getColumn($c)->getType() != "" ) {
					$xhtmlColumns .= ",";
					$xhtmlColumns .= "sType: \"{$this->getColumn($c)->getType()}\"";
				}
				if( ! $this->getColumn($c)->isWrap() ) {
					$xhtmlColumns .= ",";
					$xhtmlColumns .= "\"sClass\": \"";
					$xhtmlColumns .= "td-nowrap";
					$xhtmlColumns .= "\"";
				}
				$xhtmlColumns .= "},\n";
			}
			$xhtmlColumns = trim($xhtmlColumns,",\n");

			$xhtml = "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"display\" id=\"{$this->getId()}\">";
			$xhtml .= "<thead>";
			$xhtml .= "<tr>";
			$xhtml .= $xhtmlHeader;
			$xhtml .= "</tr>";
			$xhtml .= "</thead>";
			$xhtml .= "<tbody>";
			$xhtml .= "<tr>";
			$xhtml .= "<td colspan=\"{$this->getColumnCount()}\" class=\"dataTables_empty\">Loading Data...</td>";
			$xhtml .= "</tr>";
			$xhtml .= "</body>";
			$xhtml .= "<tfoot>";
			$xhtml .= "<tr>";
			$xhtml .= $xhtmlFooter;
			$xhtml .= "</tr>";
			$xhtml .= "</tfoot>";
			$xhtml .= "</table>";

			$xhtml .= "\n<script type=\"text/javascript\">\n";
			$xhtml .= "\t/*<![CDATA[ */\n";
			$xhtml .= "\t\t$(document).ready(function() {\n";
			if ( $this->_var_name != '' ) {
				$xhtml .= "\t\t	{$this->_var_name} = $('#{$this->getId()}').dataTable( {\n";
			}
			else {
				$xhtml .= "\t\t	$('#{$this->getId()}').dataTable( {\n";
			}
			$xhtml .= "\t\t		\"aaSorting\": [[{$this->__defaultSortColumn}, \"{$this->__defaultSortColumnOrder}\"]],\n";
			$xhtml .= "\t\t		\"bProcessing\": true,\n";
			$xhtml .= "\t\t		\"bServerSide\": true,\n";
			$xhtml .= "\t\t		\"sAjaxSource\": \"{$this->getProcessingUrl()}\",\n";
			$xhtml .= "\t\t		\"bJQueryUI\": true,\n";
			$xhtml .= "\t\t		\"bStateSave\": true,\n";
			$xhtml .= "\t\t		\"fnStateSave\": function (oSettings, oData) { localStorage.setItem( 'DataTables-{$this->getId()}', JSON.stringify(oData) ); },\n";
			$xhtml .= "\t\t		\"fnStateLoad\": function (oSettings) { return JSON.parse( localStorage.getItem('DataTables-{$this->getId()}') ); },";
			$xhtml .= "\t\t		\"sPaginationType\": \"full_numbers\",\n";
			if( $this->getFilterParameters() != '' ) {
				$xhtml .= "\t\t		\"fnServerParams\": function ( aoData ) {  aoData.push( {$this->getFilterParameters()} ); },\n";
			}
			if( $this->getDrawCallback() != '' ) {
				$xhtml .= "\t\t		\"fnDrawCallback\": function() { {$this->getDrawCallback()} },\n";
			}
			$xhtml .= "\t\t		\"aoColumns\": [\n";
			$xhtml .= $xhtmlColumns;
			$xhtml .= "\t\t		],\n";
			$xhtml .= "\t\t		\"fnServerData\": function ( sSource, aoData, fnCallback ) {\n";
			$xhtml .= "\t\t			aoData.push( { \"name\": \"action\", \"value\": \"refresh-grid\" } );\n";
			$xhtml .= "\t\t		$.ajax( {\n";
			$xhtml .= "\t\t				\"dataType\": 'json', \n";
			$xhtml .= "\t\t				\"type\": \"POST\", \n";
			$xhtml .= "\t\t				\"url\": sSource, \n";
			$xhtml .= "\t\t				\"data\": aoData, \n";
			$xhtml .= "\t\t				\"success\": function(json) { fnCallback(json); }\n";
			$xhtml .= "\t\t			} );\n";
			$xhtml .= "\t\t		}\n";
			$xhtml .= "\t\t	} ).fnSetFilteringDelay();\n";
			$xhtml .= "\t\t} );\n";
			$xhtml .= "\t/*]]>*/\n";
			$xhtml .= "\t</script>\n";

			return $xhtml;
		}
	}



	/**
	 * @package PegasusPHP
	 */
	class Grid3Column {
		private $_bStretch = false;
		private $_strTitle = 'n/a';
		private $_strColumnName = '';
		private $_bWrap = true;
		private $_iPadding = 1;
		private $_bSortable = false;
		private $_bSearchable = false;
		private $_bSorted = false;
		private $_bSortedUp = true;
		private $_bShow = true;
		private $_index = -1;
		private $_strTextAlign = 'left';
		private $_strType = "";

		public function __construct( $strTitle, $strColumnName='', $bSortable=false, $bWrap=true, $bStretch=false, $iPadding=2 ) {
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
		public function getType()       { return $this->_strType; }
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
		public function &setType($strType)      { $this->_strType = $strType; return $this; }
		public function &setTextAlign($s)       { $this->_strTextAlign = $s; return $this; }

	}




?>