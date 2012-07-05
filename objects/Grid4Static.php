<?php

	/**
	 * @package PegasusPHP
	 */

	class Grid4Static {
		
		private $_column = array();
		private $_data = array();
		private $_var_name = '';
		
		private $_id = "Grid4";

		public function Grid4() {
		}
		
		public function getId() { return $this->_id; }
		public function setId($str) {
			$this->_id = preg_replace('[^a-zA-Z0-9]', '', $str);
		}
		
		public function setVarName($val='') { $this->_var_name = $val; }
		public function getVarName() { return $this->_var_name; }		
		
		public function setData($data) { $this->_data = $data; }
		
		public function getProcessingUrl()         { return $this->_processing_url; }
		public function setProcessingUrl($str)         { $this->_processing_url = $str; }

		public function addColumn($column) {
			$column->setIndex( count($this->_column) );
			$this->_column[] = $column;
		}
		public function &createColumn($strTitle,$strColumn='') {
			$column = new Grid4StaticColumn($strTitle,$strColumn);
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
				
		public function getMarkup() {
			$xhtml = "";
			if( count($this->_data) >= 0 ) {
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
					
					$xhtmlColumns .= "/* {$this->getColumn($c)->getTitle()} */ {\"bSearchable\": ";
					$xhtmlColumns .= ( $this->getColumn($c)->isSearchable() ? "true" : "false" );
					$xhtmlColumns .= ",";
					if( ! $this->getColumn($c)->isWrap() ) {
						$xhtmlColumns .= "\"sClass\": \"";
						$xhtmlColumns .= "td-nowrap";
						$xhtmlColumns .= "\",";
					}
					$xhtmlColumns .= "\"bSortable\": ";
					$xhtmlColumns .= ( $this->getColumn($c)->isSortable() ? "true" : "false" );
					$xhtmlColumns .= ",";
					$xhtmlColumns .= "\"bVisible\": ";
					$xhtmlColumns .= ( $this->getColumn($c)->getShow() ? "true" : "false" );
					
					if( $this->getColumn($c)->getType() != "" ) {
						$xhtmlColumns .= ",";
						$xhtmlColumns .= "\"sType\": \"{$this->getColumn($c)->getType()}\"";
					}
					
					$xhtmlColumns .= "}" . ( ($c+1) < $this->getColumnCount() ? "," : "" ) . "\n";		
					
				}

				$xhtml = "<table class=\"table table-condensed table-bordered table-striped\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"display\" style=\"width:100%;\" id=\"{$this->getId()}\">";
				$xhtml .= "<thead>";
				$xhtml .= "<tr>";
				$xhtml .= $xhtmlHeader;
				$xhtml .= "</tr>";
				$xhtml .= "</thead>";
				$xhtml .= "<tbody>";
				
				for( $r = 0; $r < count($this->_data); $r++ ) {
					$xhtml .= "<tr>";	
					for( $c = 0; $c < count($this->_data[$r]); $c++ ) {
						$xhtml .= "<td>{$this->_data[$r][$c]}</td>";
					}
					$xhtml .= "</tr>";
				}
				
				$xhtml .= "</body>";
				$xhtml .= "<tfoot>";
				$xhtml .= "<tr>";
				$xhtml .= $xhtmlFooter;
				$xhtml .= "</tr>";
				$xhtml .= "</tfoot>";
				$xhtml .= "</table>";

				$xhtml .= "\n<style type=\"text/css\">\n";
				$xhtml .= "\t\ttable#{$this->getId()} tbody tr td.td-nowrap { white-space:nowrap; }\n";
				$xhtml .= "</style>\n";
				
				$xhtml .= "\n<script type=\"text/javascript\">\n";
				$xhtml .= "\t/*<![CDATA[ */\n";				
				$xhtml .= "\t\t$(document).ready(function() {\n";
				if ( $this->_var_name != '' ) {
					$xhtml .= "\t\t	{$this->_var_name} = $('#{$this->getId()}').dataTable( {\n";
				}
				else {
					$xhtml .= "\t\t	$('#{$this->getId()}').dataTable( {\n";
				}				
				$xhtml .= "\t\t		\"bStateSave\": true,\n";
				$xhtml .= "\t\t		\"fnStateSave\": function (oSettings, oData) { localStorage.setItem( 'DataTables-{$this->getId()}', JSON.stringify(oData) ); },\n";
				$xhtml .= "\t\t		\"fnStateLoad\": function (oSettings) { return JSON.parse( localStorage.getItem('DataTables-{$this->getId()}') ); },";				
				$xhtml .= "\t\t		\"bJQueryUI\": false,\n";
				$xhtml .= "\t\t		\"sDom\": \"<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>\",\n";
				$xhtml .= "\t\t		\"sPaginationType\": \"bootstrap\",\n";
				$xhtml .= "\t\t		\"oLanguage\": {\n";
				$xhtml .= "\t\t\t   	\"sLengthMenu\": \"_MENU_ records per page\"\n";
				$xhtml .= "\t\t		},\n"; 				
				$xhtml .= "\t\t		\"aoColumns\": [\n";
				$xhtml .= $xhtmlColumns;
				$xhtml .= "\t\t		],\n"; 
				$xhtml .= "\t\t		\"sPaginationType\": \"full_numbers\"\n";
				$xhtml .= "\t\t	});\n";
				$xhtml .= "\t\t});\n";
				$xhtml .= "\t/*]]>*/\n";
				$xhtml .= "\t</script>\n";
			}
			return $xhtml;
		}
	}
	
	/**
	 * @package PegasusPHP
	 */
	class Grid4StaticColumn {
		private $_bStretch = false;
		private $_strTitle = 'n/a';
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

		public function __construct( $strTitle, $type = "", $bSortable=false, $bWrap=true, $bStretch=false, $iPadding=2 ) {
			$this->_strTitle = $strTitle;
			$this->_bSortable = $bSortable;
			$this->_bWrap = $bWrap;
			$this->_bStretch = $bStretch;
			$this->_iPadding = $iPadding;
		}
		public function getTitle()      { return $this->_strTitle; }
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