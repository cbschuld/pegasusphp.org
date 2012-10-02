<?php
	/**
	 * GoogleChart - an OO PHP approach and interface to the Google Chart API
	 * + http://code.google.com/apis/chart/
	 * @package PegasusPHP
	 */
	class GoogleChart {
		
		const ENCODING_TEXT = 1;
		const ENCODING_SIMPLE = 2;
		const ENCODING_EXTENDED = 3;
		const ENCODING_TEXT_WITH_SCALING = 4;

		const AXIS_X  = 1; // bottom x-axis
		const AXIS_T  = 2; // top x-axis
		const AXIS_Y  = 3; // left y-axis
		const AXIS_R  = 4; // right y-axis
		const AXIS_XX = 5; // lower x-axis
		
		protected $_encodingType = self::ENCODING_SIMPLE;
		
		private $_cachePath = '';
		
		private $_title = ''; 			// Specify a chart title with chtt=<chart title>
		private $_legend = array();  	// Specify a legend for a chart with chdl=<first data set label>|<n data set label>
		private $_color = array(); 
		
		private $_titleSize = 14;
		private $_titleColor = '';
		
		private $_width = 200;
		private $_height = 200;
		
		protected $_dataSet = array();
		
		private $_bg = null;
		private $_bgGradient1 = null;
		private $_bgGradient2 = null;
		private $_bgGradientAngle = 90;
		private $_bgGradientOffset1 = 1;
		private $_bgGradientOffset2 = 0;
		
		private $_bgc = null;
		private $_bgcGradient1 = null;
		private $_bgcGradient2 = null;
		private $_bgcGradientAngle = 90;
		private $_bgcGradientOffset1 = 1;
		private $_bgcGradientOffset2 = 0;

		private $_axis_x_labels;
		private $_axis_t_labels;
		private $_axis_y_labels;
		private $_axis_r_labels;
		private $_axis_xx_labels;
		
		private $_gridline_x_step = 0;
		private $_gridline_y_step = 0;
		private $_gridline_line_segment_size = 2;
		private $_gridline_blank_segment_size = 5;
		
		const CHARTURL = 'http://chart.apis.google.com/chart?';
		
		/**
		 * Default Constructor
		 */
		public function __construct() {
			$this->_axis_x_labels = new GoogleAxisLabels('x');
			$this->_axis_t_labels = new GoogleAxisLabels('t');
			$this->_axis_y_labels = new GoogleAxisLabels('y');
			$this->_axis_r_labels = new GoogleAxisLabels('r');
			$this->_axis_xx_labels = new GoogleAxisLabels('x');
		}
		/**
		 * Default Destructor
		 */
		public function __destruct() {}
		
		public function setGridLineXStep($i) { $this->_gridline_x_step = $i; }
		public function setGridLineYStep($i) { $this->_gridline_y_step = $i; }
		public function setGridLineSegmentSize($i) { $this->_gridline_line_segment_size = $i; }
		public function setGridLineBlankSegmentSize($i) { $this->_gridline_blank_segment_size = $i; }
		
		public function getAxisLabels($axis) {
			switch($axis) {
				case(self::AXIS_X):  return $this->_axis_x_labels; break;
				case(self::AXIS_T):  return $this->_axis_t_labels; break;
				case(self::AXIS_Y):  return $this->_axis_y_labels; break;
				case(self::AXIS_R):  return $this->_axis_r_labels; break;
				case(self::AXIS_XX): return $this->_axis_xx_labels; break;
			}
			return null;
		}

		public function setAxisLabel($axis,$arrayOfLabels,$position=50) {
			$this->getAxisLabels($axis)->addLabel($arrayOfLabels,$position);
		}
		
		public function setAxisLabels($axis,$arrayOfLabels) {
			$this->getAxisLabels($axis)->clear();
			if( is_array($arrayOfLabels) ) {
				foreach( $arrayOfLabels as $label ) {
					$this->getAxisLabels($axis)->addLabel($label);
				}
			}
			else {
				$this->getAxisLabels($axis)->addLabel($arrayOfLabels);
			}
		}
		
		protected function markupAxisLabels() {
			$axislist = '';
			$index = 0;
			$retval = '';
			$axisposition = '';
			
			if( $this->getAxisLabels(self::AXIS_X)->count() > 0 ) {
				$this->getAxisLabels(self::AXIS_X)->setIndex($index);
				$labels = $this->getAxisLabels(self::AXIS_X)->getLabels();
				$axislist .= ( $axislist == '' ? '' : ',' ).$this->getAxisLabels(self::AXIS_X)->getMarkupLabel();
				$bCalculatePositionData = $this->getAxisLabels(self::AXIS_X)->hasPositionInfo();
				if( $bCalculatePositionData ) {
					$axisposition .= $index;
				}
				$retval .= $retval == '' ? '' : '|';
				$retval .= $index++.':';
				foreach( $labels as $label ) {
					$retval .= '|'.$label->getLabelMarkup();
					if( $bCalculatePositionData ) {
						$axisposition .= ','.$label->getPosition();
					}
				}
			}
			
			if( $this->getAxisLabels(self::AXIS_T)->count() > 0 ) {
				$this->getAxisLabels(self::AXIS_T)->setIndex($index);
				$labels = $this->getAxisLabels(self::AXIS_T)->getLabels();
				$axislist .= ( $axislist == '' ? '' : ',' ).$this->getAxisLabels(self::AXIS_T)->getMarkupLabel();
				$bCalculatePositionData = $this->getAxisLabels(self::AXIS_T)->hasPositionInfo();
				if( $bCalculatePositionData ) {
					$axisposition .= $index;
				}
				$retval .= $retval == '' ? '' : '|';
				$retval .= $index++.':';
				foreach( $labels as $label ) {
					$retval .= '|'.$label->getLabelMarkup();
					if( $bCalculatePositionData ) {
						$axisposition .= ','.$label->getPosition();
					}
				}
			}
			
			if( $this->getAxisLabels(self::AXIS_Y)->count() > 0 ) {
				$this->getAxisLabels(self::AXIS_Y)->setIndex($index);
				$labels = $this->getAxisLabels(self::AXIS_Y)->getLabels();
				$axislist .= ( $axislist == '' ? '' : ',' ).$this->getAxisLabels(self::AXIS_Y)->getMarkupLabel();
				$bCalculatePositionData = $this->getAxisLabels(self::AXIS_Y)->hasPositionInfo();
				if( $bCalculatePositionData ) {
					$axisposition .= $index;
				}
				$retval .= $retval == '' ? '' : '|';
				$retval .= $index++.':';
				foreach( $labels as $label ) {
					$retval .= '|'.$label->getLabelMarkup();
					if( $bCalculatePositionData ) {
						$axisposition .= ','.$label->getPosition();
					}
				}
			}
			
			if( $this->getAxisLabels(self::AXIS_R)->count() > 0 ) {
				$this->getAxisLabels(self::AXIS_R)->setIndex($index);
				$labels = $this->getAxisLabels(self::AXIS_R)->getLabels();
				$axislist .= ( $axislist == '' ? '' : ',' ).$this->getAxisLabels(self::AXIS_R)->getMarkupLabel();
				$bCalculatePositionData = $this->getAxisLabels(self::AXIS_R)->hasPositionInfo();
				if( $bCalculatePositionData ) {
					$axisposition .= $index;
				}
				$retval .= $retval == '' ? '' : '|';
				$retval .= $index++.':';
				foreach( $labels as $label ) {
					$retval .= '|'.$label->getLabelMarkup();
					if( $bCalculatePositionData ) {
						$axisposition .= ','.$label->getPosition();
					}
				}
			}
			
			if( $this->getAxisLabels(self::AXIS_XX)->count() > 0 ) {
				$this->getAxisLabels(self::AXIS_XX)->setIndex($index);
				$labels = $this->getAxisLabels(self::AXIS_XX)->getLabels();
				$axislist .= ( $axislist == '' ? '' : ',' ).$this->getAxisLabels(self::AXIS_XX)->getMarkupLabel();
				$bCalculatePositionData = $this->getAxisLabels(self::AXIS_XX)->hasPositionInfo();
				if( $bCalculatePositionData ) {
					$axisposition .= $index;
				}
				$retval .= $retval == '' ? '' : '|';
				$retval .= $index++.':';
				foreach( $labels as $label ) {
					$retval .= '|'.$label->getLabelMarkup();
					if( $bCalculatePositionData ) {
						$axisposition .= ','.$label->getPosition();
					}
				}
			}
					
			return $retval == '' ? '' : '&amp;chxt='.$axislist.'&amp;chxl='.$retval.($axisposition == '' ? '' : '&amp;chxp='.$axisposition);
		}
		
		public function addAxixLabels($axis,$min,$max,$count=10) {
			$this->getAxisLabels($axis)->clear();
			$span = (int)($max - $min);
			$interval = (int)($span / $count);
			if( $interval == 0 ) $interval = 1; 
			for( $i = 0; $min <= $max && $i <= $count; $i++ ) {
				$this->getAxisLabels($axis)->addLabel($min);
				$min += $interval;
			}
		}
				
		public function setEncodingType($encodingType) {
			switch($encodingType) {
				case(self::ENCODING_EXTENDED): $this->_encodingType = self::ENCODING_EXTENDED; break;
				case(self::ENCODING_TEXT): $this->_encodingType = self::ENCODING_TEXT; break;
				default: case(self::ENCODING_SIMPLE): $this->_encodingType = self::ENCODING_SIMPLE; break;
			}
		}
		public function getEncodingType() { return $this->_encodingType; }
		
		public function getCachePath() { return $this->_cachePath; }
		public function setCachePath($v) { $this->_cachePath = $v; }
		
		public function getWidth() { return $this->_width; }
		public function getHeight() { return $this->_height; }
		public function setWidth($v) { $this->_width = $v; }
		public function setHeight($v) { $this->_height = $v; }
		
		public function setBackground($v) { 
			$this->_bg = $v;
			$this->_bgGradient1 = null;
			$this->_bgGradient2 = null;
			$this->_bgGradientAngle = 0;
			$this->_bgGradientOffset1 = 1;
			$this->_bgGradientOffset2 = 0;
		}
		public function setBackgroundGradient($color1,$color2,$angle=90,$offset1=1,$offset2=0) { 
			$this->_bg = null;
			$this->_bgGradient1 = $color1;
			$this->_bgGradient2 = $color2;
			$this->_bgGradientAngle = $angle;
			$this->_bgGradientOffset1 = $offset1;
			$this->_bgGradientOffset2 = $offset2;
		}
		public function setChartBackground($v) { 
			$this->_bgc = $v;
			$this->_bgcGradient1 = null;
			$this->_bgcGradient2 = null;
			$this->_bgcGradientAngle = 0;
			$this->_bgcGradientOffset1 = 1;
			$this->_bgcGradientOffset2 = 0;
		}
		public function setChartBackgroundGradient($color1,$color2,$angle=90,$offset1=1,$offset2=0) { 
			$this->_bgc = null;
			$this->_bgcGradient1 = $color1;
			$this->_bgcGradient2 = $color2;
			$this->_bgcGradientAngle = $angle;
			$this->_bgcGradientOffset1 = $offset1;
			$this->_bgcGradientOffset2 = $offset2;
		}
		public function setTitle($v,$colorRgb='',$size=14) {
			$this->_title = str_replace("\n",'|',$v);
			$this->_titleColor = $colorRgb;
			$this->_titleSize = $size;
		}
		public function getTitle() { return $this->_title; }
		protected function markupChartTitle() {
			// chtt=<chart title>
			// chts=<color>,<fontsize> *** OPTIONAL
			return '&amp;chtt='.urlencode($this->_title) . ( $this->_titleColor != '' ? '&amp;chts='.$this->_titleColor.','.$this->_titleSize : '' );
		}
		protected function markupChartSize() {
			return '&amp;chs='.$this->_width.'x'.$this->_height;
		}
		protected function markupGridlines() {
			return '&amp;chg='.$this->_gridline_x_step.','.$this->_gridline_y_step.','.$this->_gridline_line_segment_size.','.$this->_gridline_blank_segment_size;
		}
		protected function markupBackground() {
			$retVal = '';
			$nextCmd = false;
			
			if( $this->_bg != null || $this->_bgc != null || $this->_bgGradient1 != null || $this->_bgcGradient1 != null ) {

				$retVal .= '&amp;chf=';
				
				//	Solid fill
				//	
				//	Specify solid fill with
				//	
				//	chf=
				//	<bg or c>,s,<color>|<bg or c>,s,<color>

				if( $this->_bg != null ) {
					$retVal .= 'bg,s,'.$this->_bg;
					$nextCmd = true;
				}
				
				
				if( $this->_bgc != null ) {
					if( $nextCmd ) { $retVal .= '|'; }
					$retVal .= 'c,s,'.$this->_bgc;
					$nextCmd = true;
				}
				
				//	Specify linear gradient with
				//	
				//	chf=<bg or c>,lg,<angle>,<color 1>,<offset 1>,<color n>,<offset n>
				//	
				//	Where:
				//	
				//	    * <bg or c> is bg for background fill or c for chart area fill.
				//	    * lg specifies linear gradient.
				//	    * <angle> specifies the angle of the gradient between 0 (horizontal) and 90 (vertical).
				//	    * <color x> are RRGGBB format hexadecimal numbers.
				//	    * <offset x> specify at what point the color is pure where: 0 specifies the right-most chart position and 1 the left-most.
				if( $this->_bgGradient1 != null ) {
					if( $nextCmd ) { $retVal .= '|'; }
					$retVal .= 'bg,lg,'.$this->_bgGradientAngle.','.$this->_bgGradient1.','.$this->_bgGradientOffset1.','.$this->_bgGradient2.','.$this->_bgGradientOffset2;
					$nextCmd = true;
				}
			
				if( $this->_bgcGradient1 != null ) {
					if( $nextCmd ) { $retVal .= '|'; }
					$retVal .= 'c,lg,'.$this->_bgcGradientAngle.','.$this->_bgcGradient1.','.$this->_bgcGradientOffset1.','.$this->_bgcGradient2.','.$this->_bgcGradientOffset2;
					$nextCmd = true;
				}
			
			}
			return $retVal;
		}
		
		protected function markupChartType() {
			return '';
		}
		public function getUrl() {
			return '';
		}
		public function addDataSet($ds) {
			array_push( $this->_dataSet, $ds );
		}
		public function convertToPercentageData() {
			$total = 0;
			for( $d = 0; $d < count($this->_dataSet); $d++ ) {
				$values = $this->_dataSet[$d]->getData();
				for( $v = 0; $v < count( $values ); $v++ ) {
					$total += $values[$v];
				}
			}
			for( $d = 0; $d < count($this->_dataSet); $d++ ) {
				$values = $this->_dataSet[$d]->getData();
				for( $v = 0; $v < count( $values ); $v++ ) {
					if( $total == 0 ) {
						$values[$v] = 0;	
					}
					else {
						$values[$v] = ( $values[$v] / $total ) * 100;
					}
				}
				$this->_dataSet[$d]->setData($values);
			}
		}
		protected function markupDataSetColors() {
			$retval = '';
			for( $d = 0; $d < count($this->_dataSet); $d++ ) {
				if( $retval != '' || $this->_dataSet[$d]->getColor() != '' ) {
					if( $retval != '' ) { $retval .= ','; }
					$retval .= $this->_dataSet[$d]->getColor();
				}
			}
			// If trailing colors are missing disregarding any leading colors
			// collapse the list helpful for pie charts
			$retval = preg_replace('/,+$/','',$retval);
			return '&amp;chco='.$retval;
		}
		protected function markupChartLegend() {
			//	Legend
			//	Specify a legend for a chart with
			//	chdl=<first data set label>|<n data set label>

			$retval = '';
			$hasLegend = false;
			for( $d = 0; ! $hasLegend && $d < count($this->_dataSet); $d++ ) {
				$hasLegend = ( $this->_dataSet[$d]->getLegendLabel() != '' );
			}
			if( $hasLegend ) {
				for( $d = 0; $d < count($this->_dataSet); $d++ ) {
					if( $retval != '' ) { $retval .= '|'; }
					$retval .= urlencode($this->_dataSet[$d]->getLegendLabel());
				}
			}
			return ( $retval == '' ? '' : '&amp;chdl=' . $retval );
		}
		protected function markupChartLabels() {
			// Label
			//			chl=
			// <label 1 value>|
			// ...
			// <label n value>
			// Specify a missing value with two consecutive pipe characters (||). 
			// Note: To display labels:
			// - A two-dimensional chart needs to be roughly twice as wide as it is high.
			// - A three-dimensional chart needs to be roughly two and a half times wider than it is high. 

			$retval = '';
			$hasLabel = false;
			for( $d = 0; ! $hasLabel && $d < count($this->_dataSet); $d++ ) {
				$hasLabel = ( $this->_dataSet[$d]->getLabel() != '' );
			}
			if( $hasLabel ) {
				for( $d = 0; $d < count($this->_dataSet); $d++ ) {
					if( $retval != '' ) { $retval .= '|'; }
					$retval .= urlencode($this->_dataSet[$d]->getLabel());
				}
			}
			return ( $retval == '' ? '' : '&amp;chl=' . $retval );
		}
		protected function markupData() {
			return $this->getEncodedData();
		}
		public function getEncodedData() {
			$retval = '';
			switch($this->_encodingType) {
				case(self::ENCODING_TEXT):
					$txtEncode = '';
					for( $d = 0; $d < count($this->_dataSet); $d++ ) {
						$values = $this->_dataSet[$d]->getData();
						if( $txtEncode != '' ) { $txtEncode .= ','; }
						for( $v = 0; $v < count( $values ); $v++ ) {
							$txtEncode .= sprintf('%.1f',$values[$v]);
							if( ( $v + 1 ) < count( $values ) ) {
								$txtEncode .= ',';
							}
						}
					}
					$retval = '&amp;chd=t:'.$txtEncode;
					break;
				case(self::ENCODING_SIMPLE):
					$maxValue = 0;
					$minValue = 0;
					for( $d = 0; $d < count($this->_dataSet); $d++ ) {
						$values = $this->_dataSet[$d]->getData();
						for( $v = 0; $v < count( $values ); $v++ ) {
							$maxValue = max( $maxValue, $values[$v] );
							$minValue = min( $minValue, $values[$v] );
						}
					}
					
					$simpleEncoding = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';			
					$simpleEncode = '';
			
					for( $d = 0; $d < count($this->_dataSet); $d++ ) {
						$values = $this->_dataSet[$d]->getData();
						if( $simpleEncode != '' ) { $simpleEncode .= ','; }
						for( $v = 0; $v < count( $values ); $v++ ) {
							if( $maxValue == 0 ) {
								$simpleEncode .= substr( $simpleEncoding, round( (strlen($simpleEncoding) - 1) * $values[$v] ), 1 );
							}
							else {
								$simpleEncode .= substr( $simpleEncoding, round( (strlen($simpleEncoding) - 1) * $values[$v] / $maxValue  ), 1 );
							}
							//print 'adding: '.$values[$v]. ' --> '.substr( $simpleEncoding, round( (strlen($simpleEncoding) - 1) * $values[$v] / $maxValue  ), 1 ).'<br/>';
						}
					}
					$retval = '&amp;chd=s:'.$simpleEncode;
					break;					
			}
			return $retval; 
		}
	};
	class GooglePieChart extends GoogleChart {
		/**
		 * Default Constructor
		 */
		public function __construct() {
			parent::__construct();
			$this->setEncodingType(self::ENCODING_TEXT);
		}
		/**
		 * Default Destructor
		 */
		public function __destruct() {
			parent::__destruct();
		}
		
		private $_b3d = false;
		
		public function set3d($v=true) { $this->_b3d = $v; }
		public function get3d() { return $this->_b3d; }
		
		protected function markupChartType() {
			//	 cht=<bar chart type>
			//	
			//	cht=p - Two dimensional pie chart.
			//	cht=p3 - Three dimensional pie chart.

			// 3d
			if( $this->_b3d ) {
				$type = 'p3';
			}
			// 2d
			else {
				$type = 'p';
			}
			return '&amp;cht='.$type;
		}
		
		
		public function getUrl() {
			
			// Check for Legend Labels
			$hasLegend = false;
			for( $d = 0; ! $hasLegend && $d < count($this->_dataSet); $d++ ) {
				$hasLegend = ( $this->_dataSet[$d]->getLegendLabel() != '' );
			}
			if( $hasLegend ) {			
				die('Error: found a legend label on a Pie Chart!  Please call setLabel() for Pie Charts and not setLegendLabel()');
			}
			
			return	self::CHARTURL .
					$this->markupChartType() .
					$this->markupBackground() .
					$this->markupData() .
					$this->markupDataSetColors() .
					$this->markupChartSize() .
					$this->markupChartLabels() .
					$this->markupChartTitle();
		}
	}
	
	class GoogleAxisLabel {
		private $_label;
		private $_position = -1;
		public function setLabel($v) { $this->_label = $v; }
		public function setPosition($v) { $this->_position = $v; }
		public function getLabel() { return $this->_label; }
		public function getLabelMarkup() { return urlencode($this->_label); }
		public function getPosition() { return $this->_position; }
	}
	class GoogleAxisLabels {
		private $_labels;
		private $_markupLabel;
		private $_index = -1;
		public function GoogleAxisLabels($googleMarkupLabel) {
			$this->clear();
			$this->_markupLabel = $googleMarkupLabel;
		}
		public function getMarkupLabel() { return $this->_markupLabel; }
		public function clear() { $this->_labels = array(); $_index = -1; }
		public function count() { return count($this->_labels); }
		public function getLabels() { return $this->_labels; }
		public function getIndex() { return $this->_index; }
		public function setIndex($index) { $this->_index = $index; }
		public function balancePositions() {
			$interval = (int)(100 / count($this->_labels));
			$location = 0;
			for($i = 0; $i < count($this->_labels); $i++ ) {
				$this->_labels[$i]->setPosition($location);
				$location += $interval;
			}
		}
		public function hasPositionInfo() {
			$retval = false;
			for($i = 0; !$retval && $i < count($this->_labels); $i++ ) {
				$retval = $this->_labels[$i]->getPosition() != -1;
			}
			return $retval;
		}
		public function addLabel($label,$positionFrom0to100=-1) {
			$gal = new GoogleAxisLabel();
			$gal->setLabel($label);
			$gal->setPosition($positionFrom0to100);
			array_push($this->_labels,$gal);
		}
	}
	class GoogleLineChart extends GoogleChart {
		
		/**
		 * Default Constructor
		 */
		public function __construct() {
			parent::__construct();
			$this->_noaxis = false;
			$this->_fill = '';
		}
	
		/**
		 * Default Destructor
		 */
		public function __destruct() {
			parent::__destruct();
		}
		
		public function setNoAxis() {
			$this->_noaxis = true;
		}
		
		public function setFill($fill) {
			$this->_fill = $fill;
		}
		
		protected function markupFill() {
			// just a basic fill for line graphs for now
			if($this->_fill) {
				return '&amp;chm=B,'.$this->_fill;
			} else {
				return '';
			}
		}
		
		protected function markupChartType() {
			//	 cht=lc
			// A line chart, data points are spaced evenly along the x-axis. 
			//	
			$type = 'lc';
			if ($this->_noaxis) { $type.=':nda'; }
			return '&amp;cht='.$type;
		}
		
		public function getUrl() {
			return	self::CHARTURL .
					$this->markupChartType() .
					$this->markupBackground() .
					$this->markupData() .
					$this->markupDataSetColors() .
					$this->markupChartSize() .
					$this->markupChartLegend() .
					$this->markupChartTitle().
					$this->markupAxisLabels().
					$this->markupGridlines().
					$this->markupFill()
					;
		}		
	}
	class GoogleBarChart extends GoogleChart {
		
		private $_verticalChart = true;
		private $_groupedChart = true;
		private $_barWidth = null;
		private $_barSpacing = null;
		private $_barGroupSpacing = null;
		
		public function setVerticalChart($v=true) { $this->_verticalChart = $v; }
		public function setGroupedChart($v=true) { $this->_groupedChart = $v; }
		public function setHorizontalChart($v=true) { $this->_verticalChart = ! $v; }
		public function setStackedChart($v=true) { $this->_groupedChart = ! $v; }
		
		public function setBarWidth($barWidth,$barSpacing=null,$groupSpacing=null ) {
			$this->_barWidth        = $barWidth;
			$this->_barSpacing      = $barSpacing;
			$this->_barGroupSpacing = $groupSpacing;
		}
		
		/**
		 * Default Constructor
		 */
		public function __construct() {
			parent::__construct();
		}
	
		/**
		 * Default Destructor
		 */
		public function __destruct() {
			parent::__destruct();
		}
		
		protected function markupChartBarWidth() {
			//	chbh=
			//	<bar width in pixels>,
			//	<optional space between bars in a group>,
			//	<optional space between groups>
			$retVal = '';
			if( $this->_barWidth != null ) {
				$retVal .= '&amp;chbh='.$this->_barWidth;
				if( $this->_barSpacing != null ) {
					$retVal .= ','.$this->_barSpacing;
					if( $this->_barGroupSpacing != null ) {
						$retVal .= ','.$this->_barGroupSpacing;
					}
				}
			}
			return $retVal;
		}
		
		protected function markupChartType() {
			//	 cht=<bar chart type>
			//	
			//	Where <bar chart type> is bhs, bhg, bvs or bvg as described in the following table.
			//	
			//	    * Depending on the bar chart type, multiple data sets are drawn as stacked or grouped bars.
			//	    * For information on how to specify multiple data sets see Chart data.
			//	    * For information on available parameters see Optional parameters by chart type.
			
			// Vertical Grouped
			if( $this->_verticalChart && $this->_groupedChart ) {
				$type = 'bvg';
			}
			// Horizontal Grouped
			else if( ! $this->_verticalChart && $this->_groupedChart ) {
				$type = 'bhg';
			}
			// Vertical Stacked
			else if( $this->_verticalChart && ! $this->_groupedChart ) {
				$type = 'bvs';
			}
			// Horizontal Stacked
			else {
				$type = 'bhs';
			}
			return '&amp;cht='.$type;
		}
		
		public function getUrl() {
			return	self::CHARTURL .
					$this->markupChartType() .
					$this->markupChartBarWidth() .
					$this->markupBackground() .
					$this->markupData() .
					$this->markupDataSetColors() .
					$this->markupChartSize() .
					$this->markupChartLegend() .
					$this->markupChartTitle().
					$this->markupAxisLabels()
					;
		}		
	}
	class GoogleChartDataSet {
		private $_color = '';
		private $_data = array();
		private $_legendLabel = '';
		private $_label = '';
		private $_maxValue = null;
		
		public function setMaximumValue($v) { $this->_maxValue = $v; }
		
		public function setColor($c) { $this->_color = $c; }
		public function getColor() { return $this->_color; }
		public function setLegendLabel($l) { $this->_legendLabel = $l; }
		public function getLegendLabel() { return $this->_legendLabel; }
		public function setLabel($l) { $this->_label = $l; }
		public function getLabel() { return $this->_label; }
		public function setPercentageData($v) {
			if( is_array($v) ) {
				$this->_data = array();
				foreach( $v as $value ) {
					 array_push($this->_data, ( $value / $this->_maxValue ) * 100 );
				}
			}
			else {
				$this->_data = array();
				if( $this->_maxValue == 0 ) {
					array_push($this->_data, 0 );
				}
				else {
					array_push($this->_data, ( $v / $this->_maxValue ) * 100 );
				}
			}
		}
		public function setData($v) {
			if( is_array($v) ) {
				$this->_data = $v;
			}
			else {
				$this->_data = array($v);
			}
		}
		public function setValue($v) { $this->_data = array($v); }
		public function incrementValue($v=1) { if( count($this->_data) > 0 ) $this->_data[0] += $v; }
		public function getData() { return $this->_data; }
		
		public function getMaximumValue() {
			$retval = null;
			if( count($this->_data) ) {
				$retval = $this->_data[0];
				for($i = 1; $i < count($this->_data); $i++ ) {
					$retval = max( $this->_data[$i], $retval );
				}
			}
			return $retval;
		}
		public function getMinimumValue() {
			$retval = null;
			if( count($this->_data) ) {
				$retval = $this->_data[0];
				for($i = 1; $i < count($this->_data); $i++ ) {
					$retval = max( $this->_data[$i], $retval );
				}
			}
			return $retval;
		}
	}
	
?>