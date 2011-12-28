<?php
	/**
	 * DynamicCalendarSettings
	 *
	 * @package PegasusPHP
	 */
	class DynamicCalendarSettings {
		private static $_startDay = 1;
		private static $_endDay = 7;
		private static $_startHour = 8;
		private static $_endHour = 17;
		private static $_minGranularity = 15;
		private static $_sortBySortId = true;
		private static $_pixelsPerMinuteGranularity = 3;
		private static $_displayHourMultiplier = 2;
		private static $_showTimeColumn = true;
		private static $_showDateColumn = true;
		private static $_showInlineTime = true;
		private static $_style = 'dcal';
		private static $_showTitle = true;
		private static $_dayFormat = 'l, F jS, Y';
		private static $_dayTitleFormat = 'l, F jS, Y';
		private static $_timeFormat = 'g:iA';
		private static $_blankCellOnClick = '';
		public static function getBlankCellOnClick() { return self::$_blankCellOnClick; }
		public static function setBlankCellOnClick($v)  { self::$_blankCellOnClick = $v; }
		public static function getStyle() { return self::$_style; }
		public static function setStyle($v)  { self::$_style = $v; }
		public static function getShowTitle() { return self::$_showTitle; }
		public static function setShowTitle($v)  { self::$_showTitle = $v; }
		public static function getDayFormat() { return self::$_dayFormat; }
		public static function setDayFormat($v)  { self::$_dayFormat = $v; }
		public static function getDayTitleFormat() { return self::$_dayTitleFormat; }
		public static function setDayTitleFormat($v)  { self::$_dayTitleFormat = $v; }
		public static function getTimeFormat() { return self::$_timeFormat; }
		public static function setTimeFormat($v)  { self::$_timeFormat = $v; }
		public static function getStartDay() { return self::$_startDay; }
		public static function setStartDay($v)  { self::$_startDay = $v; }
		public static function getEndDay() { return self::$_endDay; }
		public static function setEndDay($v)  { self::$_endDay = $v; }
		public static function getStartHour() { return self::$_startHour; }
		public static function setStartHour($v)  { self::$_startHour = $v; }
		public static function getEndHour() { return self::$_endHour; }
		public static function setEndHour($v)  { self::$_endHour = $v; }
		public static function getMinuteGranularity() { return self::$_minGranularity; }
		public static function setMinuteGranularity($v)  { self::$_minGranularity = $v; }
		public static function getSortBySortId() { return self::$_sortBySortId; }
		public static function setSortBySortId($v)  { self::$_sortBySortId = $v; }
		public static function getPixelsPerMinuteGranularity() { return self::$_pixelsPerMinuteGranularity; }
		public static function setPixelsPerMinuteGranularity($v)  { self::$_pixelsPerMinuteGranularity = $v; }
		public static function getDisplayHourMultiplier() { return self::$_displayHourMultiplier; }
		public static function setDisplayHourMultiplier($v)  { self::$_displayHourMultiplier = $v; }
		public static function showTimeColumn() { return self::$_showTimeColumn; }
		public static function getShowTimeColumn() { return self::$_showTimeColumn; }
		public static function setShowTimeColumn($v)  { self::$_showTimeColumn = $v; }
		public static function showDateColumn() { return self::$_showDateColumn; }
		public static function getShowDateColumn() { return self::$_showDateColumn; }
		public static function setShowDateColumn($v)  { self::$_showDateColumn = $v; }
		public static function showInlineTime() { return self::$_showInlineTime; }
		public static function getShowInlineTime() { return self::$_showInlineTime; }
		public static function setShowInlineTime($v)  { self::$_showInlineTime = $v; }
	}
	
	class DynamicCalendar {
		
		private $_smartyTemplatePath = '';
		private $_smarty;
		
		private $_events;
		
		private $_matrix = array();
		
		
		private $_leftHeader = '';
		private $_rightHeader = '';
		
		private $_weekUrl = '';
		private $_monthUrl = '';
		private $_dayUrl = '';
		
		
		public function getWeekUrl() { return $this->_weekUrl; }
		public function setWeekUrl($url)  { $this->_weekUrl = $url; }
		public function getMonthUrl() { return $this->_monthUrl; }
		public function setMonthUrl($url)  { $this->_monthUrl = $url; }
		public function getDayUrl() { return $this->_dayUrl; }
		public function setDayUrl($url)  { $this->_dayUrl = $url; }
		
		
		public function getEvents() { return $this->_events; }
		public function setEvents($v)  { $this->_events = $v; }
		public function addEvent($event) { $this->_events->addEvent( $event ); }
		
		public function setMondayStart() { DynamicCalendarSettings::setStartDay(1); DynamicCalendarSettings::setEndDay(7); }
		public function setSundayStart() { DynamicCalendarSettings::setStartDay(7); DynamicCalendarSettings::setEndDay(6); }
		
		public function setLeftHeader($v) { $this->_leftHeader = $v; }
		public function getLeftHeader() { return $this->_leftHeader; }
		
		public function setRightHeader($v) { $this->_rightHeader = $v; }
		public function getRightHeader() { return $this->_rightHeader; }
		
		public function __construct() {
			$this->_smartyTemplatePath = dirname(__FILE__) . '/../templates/DynamicCalendar/';
			$this->_smarty = new Smarty();
			$this->_events = new DynamicCalendarEvents();
		}
		
		private function getTemplate($s) {
			return $this->_smartyTemplatePath.'/'.$s;
		}
	
		private function createMonthSortMatrix($year,$month) {
			
			$dte = new DateTime("$year-$month-1");
			$dte->modify('+1 Month');
			$dte->modify('-1 Day');
			$startDay = 1;
			$endDay = (int)$dte->format('j');
			
			$this->_matrix = new DynamicCalendarEventMatrix();
			
			$this->_events->sort();
		
			$firstDate = $this->getFirstViewableDay($year,$month);
			$lastDate = $this->getLastViewableDay($year,$month);
			
			$events = $this->_events->getTimeSpanEvents($firstDate,$lastDate);
			foreach( $events as $event ) {
				///print 'adding: '.$event->getStartDateTime()->format('Y/m/d H:i')." - ".$event->getEndDateTime()->format('Y/m/d H:i');
				$this->_matrix->addEvent($event);
			}
		}
	
		private function createWeekSortMatrix($year,$month,$day) {
			
			$startDate = $this->getFirstDayOfWeek($year,$month,$day);
			$endDate = $this->getLastDayOfWeek($year,$month,$day);
			$this->_matrix = new DynamicCalendarEventMatrix();
			$this->_events->sort();
			$events = $this->_events->getTimeSpanEvents($startDate,$endDate);
			foreach( $events as $event ) {
				$this->_matrix->addEvent($event);
			}
		}
		
		private function createDaySortMatrix($year,$month,$day) {
			
			$startDte = new DateTime("$year-$month-$day");
			$startDte->setTime(00,00);
			
			$endDate = clone($startDte);
			$endDate->modify('+1 Day');
			$endDate->modify('-1 Minute');
			
			$this->_matrix = new DynamicCalendarEventMatrix();
			
			$this->_events->sort();
		
			$events = $this->_events->getTimeSpanEvents($startDte,$endDate);
			foreach( $events as $event ) {
				$this->_matrix->addEvent($event);
			}
		}
		
		public function getFirstViewableDay($year,$month) {
			$currentDate = new DateTime("$year-$month-1");
			while( $currentDate->format('N') != DynamicCalendarSettings::getStartDay() ) {
				$currentDate->modify('-1 Day');
			}
			return $currentDate;
		}
		
		public function getLastViewableDay($year,$month) {
			$currentDate = new DateTime("$year-$month-1");
			$currentDate->modify('+1 Month');
			$currentDate->modify('-1 Day');
			while( (int)$currentDate->format('m') == (int)$month || $currentDate->format('N') !=  DynamicCalendarSettings::getStartDay() ) {
				$currentDate->modify('+1 Day');
			}
			$currentDate->modify('-1 Day');
			$currentDate->setTime(23,59,59);

			return $currentDate;
		}
		
		public function getFirstDayOfWeek($year,$month,$day) {
			$currentDate = new DateTime("$year-$month-$day");
			while( $currentDate->format('N') != DynamicCalendarSettings::getStartDay() ) {
				$currentDate->modify('-1 Day');
			}
			return $currentDate;
		}
		
		public function getLastDayOfWeek($year,$month,$day) {
			$currentDate = new DateTime("$year-$month-$day");
			while( $currentDate->format('N') != DynamicCalendarSettings::getEndDay() ) {
				$currentDate->modify('+1 Day');
			}
			return $currentDate;
		}
	
		
		public function getDay($year,$month,$day) {
	
			$this->createDaySortMatrix($year,$month,$day);
			
			$currentDate = new DateTime(); $currentDate->setDate($year,$month,$day);
	
			$totalHours = DynamicCalendarSettings::getEndHour() - DynamicCalendarSettings::getStartHour() + 1;
			$hourStackHeight = (int)(60 / DynamicCalendarSettings::getMinuteGranularity());
			$stackHeight = $totalHours * $hourStackHeight;
			
			$xhtml  = '';
			$xhtml .= "<div class=\"cal\">";
			$xhtml .= "<table class=\"cal\" cellspacing=\"0\" cellpadding=\"0\">";
	
			$hour = DynamicCalendarSettings::getStartHour() - 1;
			$minute = 0;
			
			$columnCount = $this->_matrix->getLayerCount($currentDate) + 1;
			
			$xhtml .= '<tr>';
			$xhtml .= "<td class=\"_cal_header\" colspan=\"{$columnCount}\">";
			$xhtml .= "<div style=\"float:left;\">{$this->_leftHeader}</div>";
			$xhtml .= "<div style=\"float:right;\">{$this->_rightHeader}</div>";
			$xhtml .= "<div class=\"_cal_header_txt\">";
			$xhtml .= $currentDate->format('l, F jS, Y');
			$xhtml .= '</div></td>';
			$xhtml .= '</tr>';
			
			$xhtml .= "<tr>";
			
			$url = $this->getLink($this->getWeekUrl(),$currentDate->format('Y'),$currentDate->format('n'),$currentDate->format('d'));
			$class = '_cal_wmarker';
			$xhtml .= "<td class=\"{$class}\"><a class=\"_cal_wmarker\" href=\"{$url}\">Week {$currentDate->format('W')}</a></td>";
			
			//$xhtml .= "<td class=\"_cal_wmarker\">Week {$currentDate->format('W')}</td>";
			
			$layerCount = $this->_matrix->getLayerCount($currentDate);
			$xhtml .= "<td class=\"_cal_date\" colspan=\"{$layerCount}\">{$currentDate->format('l, F jS, Y')}</td>";
			
			$xhtml .= "</tr>";
			
			for( $s = 0; $s < $stackHeight; $s++ ) {
				$xhtml .= "<tr>";
				
				$hsh = $hourStackHeight * DynamicCalendarSettings::getDisplayHourMultiplier();
	
				if( $s % $hourStackHeight == 0 ) {
					$hour++;
					$minute = 0;
				}
				
				if( $s % $hsh == 0 ) {
					$tme = new DateTime(); $tme->setTime($hour,0,0);
					$xhtml .= "<td class=\"_cal_time\" rowspan=\"{$hsh}\">";
					$xhtml .= $tme->format('g:iA');
					$xhtml .= "</td>";
				}
				
	
				$mDay = $this->_matrix->getEventDay($currentDate);
				$layerCount = $this->_matrix->getLayerCount($currentDate);
				//$mDay->addLayer($layerCount);
				//$layerCount = $this->_matrix->getLayerCount($currentDate);
				
				$mDay->fillDayWithBlankInfoItems();
				
				for( $l = 0; $l < $layerCount; $l++ ) {
					
					$info = $mDay->getInfo($l,$hour,$minute);
				
					
					
					if($info->isBlank()) {
						$class = '';
						if( $minute == 0 ) {
							if( $hour % DynamicCalendarSettings::getDisplayHourMultiplier() == 0 ) {
								$class .= '_cal_bhr';
							}
							else {
								$class .= '_cal_bhrn';
							}
						}
						else {
							$class .= '_cal_bmin';
						}
						if( $l == 0 ) { $class .= ' _cal_bleft'; }
						$pixels = DynamicCalendarSettings::getPixelsPerMinuteGranularity();
						$xhtml .= "<td class=\"{$class}\"><div style=\"min-height:{$pixels}px;\"></div></td>";
					}
					else {
						if( ! $info->isUsed() ) {
							$length = $mDay->getInfoLengthFromPos($l,$hour,$minute);
							$pixels = DynamicCalendarSettings::getPixelsPerMinuteGranularity() * $length;
							$style = "color:#{$info->getEvent()->getForeground()};background:#{$info->getEvent()->getBackground()};opacity:{$info->getEvent()->getOpacity()};";
							$style .= 'width:'.( 100 / $layerCount ).'%;';
							$class = '_cal_e';
							if( $l == 0 ) { $class .= ' _cal_eleft'; }
							$xhtml .= "<td rowspan=\"{$length}\" class=\"{$class}\" style=\"{$style}\"><div style=\"min-height:{$pixels}px;\">{$info->getEvent()->getTitle()}</div></td>";
							$info->setUsed();
						}
					}
				}
	
				$xhtml .= "</tr>";
	
				$minute = $minute + DynamicCalendarSettings::getMinuteGranularity();
			}
			
			$xhtml .= "</table>";
			$xhtml .= "</div>";
			
			return $xhtml;
		}
		
		
		
		public function getWeek($year,$month,$day,$bShowTimes=true) {
	
			$this->createWeekSortMatrix($year,$month,$day);
	
			$startDate = $this->getFirstDayOfWeek($year,$month,$day);
			$endDate = $this->getLastDayOfWeek($year,$month,$day);
			
			$currentDate = clone $startDate;
			
			$totalHours = DynamicCalendarSettings::getEndHour() - DynamicCalendarSettings::getStartHour() + 1;
			$hourStackHeight = (int)(60 / DynamicCalendarSettings::getMinuteGranularity());
			$stackHeight = $totalHours * $hourStackHeight;
			
			$xhtml  = '';
			$xhtml .= "<div class=\"cal\">";
			$xhtml .= "<table class=\"cal\" cellspacing=\"0\" cellpadding=\"0\">";
	
			$hour = DynamicCalendarSettings::getStartHour() - 1;
			$minute = 0;
	
			$columnCount = 1;
			
			$currentDate = clone $startDate;
			$bFinalDateFound = false;
			while( ! $bFinalDateFound ) {
				$columnCount = $columnCount + $this->_matrix->getLayerCount($currentDate);
				$bFinalDateFound = ( $currentDate->format('N') == DynamicCalendarSettings::getEndDay() );
				$currentDate->modify('+1 Day');
			}
			
			$xhtml .= '<tr>';
			$xhtml .= "<td class=\"_cal_header\" colspan=\"{$columnCount}\">";
			$xhtml .= "<div style=\"float:left;\">{$this->_leftHeader}</div>";
			$xhtml .= "<div style=\"float:right;\">{$this->_rightHeader}</div>";
			$xhtml .= "<div class=\"_cal_header_txt\">";
			$xhtml .= $startDate->format('l, F jS, Y').' - '.$endDate->format('l, F jS, Y');
			$xhtml .= '</div></td>';
			$xhtml .= '</tr>';
			
			if( $bShowTimes ) {
				$xhtml .= "<tr>";
				//$xhtml .= "<td class=\"_cal_wmarker\">Week {$startDate->format('W')}</td>";
				
				$url = $this->getLink($this->getWeekUrl(),$startDate->format('Y'),$startDate->format('n'),$startDate->format('d'));
				$class = '_cal_wmarker';
				
				$xhtml .= "<td class=\"{$class}\"><a class=\"_cal_wmarker\" href=\"{$url}\">Week {$startDate->format('W')}</a></td>";
			
				$currentDate = clone $startDate;
				$bFinalDateFound = false;
				while( ! $bFinalDateFound ) {
		
					$layerCount = $this->_matrix->getLayerCount($currentDate);
					
					$url = $this->getLink($this->getDayUrl(),$currentDate->format('Y'),$currentDate->format('n'),$currentDate->format('d'));
					$xhtml .= "<td class=\"_cal_date\" colspan=\"{$layerCount}\"><a class=\"_cal_date\" href=\"{$url}\">{$currentDate->format('D, M jS, Y')}</a></td>";
					
					$bFinalDateFound = ( $currentDate->format('N') == DynamicCalendarSettings::getEndDay() );
					$currentDate->modify('+1 Day');
				}
				
				$xhtml .= "</tr>";		
			}
			
			for( $s = 0; $s < $stackHeight; $s++ ) {
				$xhtml .= "<tr>";
				
				$hsh = $hourStackHeight * DynamicCalendarSettings::getDisplayHourMultiplier();
	
				if( $s % $hourStackHeight == 0 ) {
					$hour++;
					$minute = 0;
				}
				
				if( $bShowTimes && $s % $hsh == 0 ) {
					
					$tme = new DateTime(); $tme->setTime($hour,0,0);
					
					$xhtml .= "<td class=\"_cal_time\" rowspan=\"{$hsh}\">";
					$xhtml .= $tme->format('g:iA');
					$xhtml .= "</td>";
				}
				
				$pixels = DynamicCalendarSettings::getPixelsPerMinuteGranularity();
				$currentDate = clone $startDate;
				$bFinalDateFound = false;
				while( ! $bFinalDateFound ) {
					
					
					$layerCount = $this->_matrix->getLayerCount($currentDate);
					
					$mDay = $this->_matrix->getEventDay($currentDate);
					$mDay->fillDayWithBlankInfoItems();
					
					for( $l = 0; $l < $layerCount; $l++ ) {
						
						$info = $mDay->getInfo($l,$hour,$minute);
	
						if($info->isBlank()) {
							$class = '';
							if( $minute == 0 ) {
								if( $hour % DynamicCalendarSettings::getDisplayHourMultiplier() == 0 ) {
									$class .= '_cal_bhr';
								}
								else {
									$class .= '_cal_bhrn';
								}
							}
							else {
								$class .= '_cal_bmin';
							}
							if( $l == 0 ) { $class .= ' _cal_bleft'; }
							$xhtml .= "<td class=\"{$class}\"><div style=\"min-height:{$pixels}px;\"></div></td>";
						}
						else {
							if( ! $info->isUsed() ) {
								$style = "color:#{$info->getEvent()->getForeground()};background:#{$info->getEvent()->getBackground()};opacity:{$info->getEvent()->getOpacity()};";
								$class = '_cal_e';
								$length = $mDay->getInfoLengthFromPos($l,$hour,$minute);
								if( $l == 0 ) { $class .= ' _cal_eleft'; }
								$xhtml .= "<td rowspan=\"{$length}\" class=\"{$class}\" style=\"{$style}\">{$info->getEvent()->getTitle()}</td>";
								$info->setUsed();
							}
						}
					}
	
					$bFinalDateFound = ( $currentDate->format('N') == DynamicCalendarSettings::getEndDay() );
					$currentDate->modify('+1 Day');
				}
				$xhtml .= "</tr>";
	
				$minute = $minute + DynamicCalendarSettings::getMinuteGranularity();
			}
			
			$xhtml .= "</table>";
			$xhtml .= "</div>";
			
			return $xhtml;
		}
		
		private function getLink($url,$year,$month,$day) {
			$url = str_replace('YEAR',$year,$url);
			$url = str_replace('MONTH',$month,$url);
			$url = str_replace('DAY',$day,$url);
			return $url;
		}
		
	
		public function getWeekEx($date) {
	
			$day = (int)$date->format('d');
			$month = (int)$date->format('m');
			$year = (int)$date->format('Y');
			
			$style = DynamicCalendarSettings::getStyle();
	
			$this->createWeekSortMatrix($year,$month,$day);
	
			$startDate = $this->getFirstDayOfWeek($year,$month,$day);
			$endDate = $this->getLastDayOfWeek($year,$month,$day);
			
			$currentDate = clone $startDate;
			
			$totalHours = DynamicCalendarSettings::getEndHour() - DynamicCalendarSettings::getStartHour() + 1;
			$hourStackHeight = (int)(60 / DynamicCalendarSettings::getMinuteGranularity());
			$stackHeight = $totalHours * $hourStackHeight;
			
			$xhtml  = '';
			$xhtml .= '<table class="'.$style.'">';
	
			$hour = DynamicCalendarSettings::getStartHour() - 1;
			$minute = 0;
	
			$columnCount = 1;
			
			$currentDate = clone $startDate;
			$bFinalDateFound = false;
			while( ! $bFinalDateFound ) {
				$columnCount = $columnCount + $this->_matrix->getLayerCount($currentDate);
				$bFinalDateFound = ( $currentDate->format('N') == DynamicCalendarSettings::getEndDay() );
				$currentDate->modify('+1 Day');
			}
			
			if( DynamicCalendarSettings::getShowTitle() ) {
				$xhtml .= '<tr><td class="'.$style.'_title" colspan="'.$columnCount.'">';
				$xhtml .= $startDate->format(DynamicCalendarSettings::getDayTitleFormat());
				$xhtml .= ' - ';
				$xhtml .= $endDate->format(DynamicCalendarSettings::getDayTitleFormat());
				$xhtml .= '</td></tr>';
			}
			
	
			if( DynamicCalendarSettings::getShowTimeColumn() || DynamicCalendarSettings::getShowDateColumn() ) {
				$xhtml .= '<tr>';
				if( DynamicCalendarSettings::getShowTimeColumn() ) {
					$xhtml .= '<th class="'.$style.'_week">Week '.$startDate->format('W').'</th>';
				}
			
				if( DynamicCalendarSettings::getShowDateColumn() ) {
					$currentDate = clone $startDate;
					$bFinalDateFound = false;
					while( ! $bFinalDateFound ) {
			
						$layerCount = $this->_matrix->getLayerCount($currentDate);
						
						//$url = $this->getLink($this->getDayUrl(),$currentDate->format('Y'),$currentDate->format('n'),$currentDate->format('d'));
						$xhtml .= '<th class="'.$style.'_day" colspan="'.$layerCount.'">';
						$xhtml .= $currentDate->format(DynamicCalendarSettings::getDayFormat());
						$xhtml .= '</th>';
						
						$bFinalDateFound = ( $currentDate->format('N') == DynamicCalendarSettings::getEndDay() );
						$currentDate->modify('+1 Day');
					}
				}
				
				$xhtml .= '</tr>';
			}
			
			for( $s = 0; $s < $stackHeight; $s++ ) {
				$xhtml .= "\n".'<tr>';
				
				$hsh = $hourStackHeight * DynamicCalendarSettings::getDisplayHourMultiplier();
	
				if( $s % $hourStackHeight == 0 ) {
					$hour++;
					$minute = 0;
				}
				
				if( DynamicCalendarSettings::getShowTimeColumn() && $s % $hsh == 0 ) {
					
					$tme = new DateTime(); $tme->setTime($hour,0,0);
					
					$xhtml .= '<td class="'.$style.'_time" rowspan="'.$hsh.'">';
					$xhtml .= $tme->format(DynamicCalendarSettings::getTimeFormat());
					$xhtml .= '</td>';
				}
				
				$pixels = DynamicCalendarSettings::getPixelsPerMinuteGranularity();
				$currentDate = clone $startDate;
				$bFinalDateFound = false;
				while( ! $bFinalDateFound ) {
					
					
					$layerCount = $this->_matrix->getLayerCount($currentDate);
					
					$mDay = $this->_matrix->getEventDay($currentDate);
					$mDay->fillDayWithBlankInfoItems();
					
					for( $l = 0; $l < $layerCount; $l++ ) {
						
						$info = $mDay->getInfo($l,$hour,$minute);
	
						if($info->isBlank()) {
							$class = '';
							if( $minute == 0 ) {
								$class .= $style.'_hour ';
								if( $hour % DynamicCalendarSettings::getDisplayHourMultiplier() == 0 ) {
									$class .= $style.'_marker ';
								}
							}
							else {
								$class .= $style.'_minute ';
							}
							if( $l == 0 ) { $class .= $style.'_left '; }
							//$xhtml .= "<td class=\"{$class}\"><div style=\"min-height:{$pixels}px;\"></div></td>";
							if( DynamicCalendarSettings::getBlankCellOnClick() == "" ) {
								$xhtml .= '<td class="'.trim($class).'" style="height:'.$pixels.'px;min-height:'.$pixels.'px;overflow:hidden;line-height:'.$pixels.'px;"></td>';
							}
							else {
								
								$link = DynamicCalendarSettings::getBlankCellOnClick();
								$link = str_replace("[H]",$hour,$link);
								$link = str_replace("[M]",$minute,$link);
								$link = str_replace("[YYYY]",$currentDate->format("Y"),$link);
								$link = str_replace("[YY]",$currentDate->format("y"),$link);
								$link = str_replace("[MM]",$currentDate->format("m"),$link);
								$link = str_replace("[DD]",$currentDate->format("d"),$link);
								
								$xhtml .= '<td class="'.trim($class).'" style="height:'.$pixels.'px;min-height:'.$pixels.'px;overflow:hidden;line-height:'.$pixels.'px;"><a href="javascript:;" onclick="' . $link . '" class="dynamic-calendar-link">&nbsp;</a></td>';
							}
							
						}
						else {
							if( ! $info->isUsed() ) {
								$inlinestyle = "color:#{$info->getEvent()->getForeground()};background:#{$info->getEvent()->getBackground()};opacity:{$info->getEvent()->getOpacity()};";
								$class = $style.'_event ';
								$length = $mDay->getInfoLengthFromPos($l,$hour,$minute);
								if( $l == 0 ) { $class .= $style.'_leftmarker'; }
								$xhtml .= '<td rowspan="'.$length.'" class="'.trim($class).'" style="'.$inlinestyle.'">';
								$xhtml .= $info->getEvent()->getTitle();
								$xhtml .= '</td>';
								$info->setUsed();
							}
						}
					}
	
					$bFinalDateFound = ( $currentDate->format('N') == DynamicCalendarSettings::getEndDay() );
					$currentDate->modify('+1 Day');
				}
				$xhtml .= '</tr>';
	
				$minute = $minute + DynamicCalendarSettings::getMinuteGranularity();
			}
			
			$xhtml .= "</table>";
			
			return $xhtml;
		}
		
		public function getMonth($year,$month) {
	
			$this->createMonthSortMatrix($year,$month);
	
			$firstDay = new DateTime(); $firstDay->setDate($year,$month,1);
			$startDate = $this->getFirstViewableDay($year,$month);
			$endDate = $this->getLastViewableDay($year,$month);
			
			$currentDate = clone $startDate;
			
			$totalHours = DynamicCalendarSettings::getEndHour() - DynamicCalendarSettings::getStartHour() + 1;
			$hourStackHeight = (int)(60 / DynamicCalendarSettings::getMinuteGranularity());
			$stackHeight = $totalHours * $hourStackHeight;
			
			$xhtml  = '';
			$xhtml .= "<div class=\"cal\">";
			$xhtml .= "<table class=\"cal\" cellspacing=\"0\" cellpadding=\"0\">";
	
			// $lcm = 1;

			$lcma = array();
			$lcma[0] = 1; // not in use but cleared and set
			$lcma[1] = 1; // Monday
			$lcma[2] = 1;
			$lcma[3] = 1;
			$lcma[4] = 1;
			$lcma[5] = 1;
			$lcma[6] = 1;
			$lcma[7] = 1;

			include_object('Math');
	
			$currentDate = clone $startDate;
			$bFinalDateFound = false;
			while( ! $bFinalDateFound ) {
	
				$layerCount = $this->_matrix->getLayerCountMonthMax($currentDate,$endDate);
				
				// $lcm = Math::lcm($lcm,$layerCount);

$lcma[ $currentDate->format("N") ] = Math::lcm($lcma[ $currentDate->format("N") ],$layerCount);
	
				$bFinalDateFound = ( $currentDate->format('N') == DynamicCalendarSettings::getEndDay() );
				$currentDate->modify('+1 Day');
			}

/*
echo "LCM: ". $lcm."\n";
echo "LCM0: ". $lcma[0]."\n";
echo "LCM1: ". $lcma[1]."\n";
echo "LCM2: ". $lcma[2]."\n";
echo "LCM3: ". $lcma[3]."\n";
echo "LCM4: ". $lcma[4]."\n";
echo "LCM5: ". $lcma[5]."\n";
echo "LCM6: ". $lcma[6]."\n";
*/
			
			//CBS $columnCount = $lcm * 7 + 1;
			$columnCount = 1 + $lcma[1] + $lcma[2] + $lcma[3] + $lcma[4] + $lcma[5] + $lcma[6] + $lcma[7];
			
			$xhtml .= '<tr>';
			$xhtml .= "<td class=\"_cal_header\" colspan=\"{$columnCount}\">";
			$xhtml .= "<div style=\"float:left;\">{$this->_leftHeader}</div>";
			$xhtml .= "<div style=\"float:right;\">{$this->_rightHeader}</div>";
			$xhtml .= "<div class=\"_cal_header_txt\">";
			$xhtml .= $firstDay->format('F Y');
			$xhtml .= '</div></td>';
			$xhtml .= '</tr>';
	
			$now = new DateTime("now");
			
			$weekStart = (int)$startDate->format('W');
			$endWeek = clone $endDate; $endWeek->modify('+1 Week');
			$weekEnd = (int)$endWeek->format('W');
			
			$w = $weekStart;
			
			while( $w != $weekEnd ) {
				
				$xhtml .= "<tr>";
				$url = $this->getLink($this->getWeekUrl(),$startDate->format('Y'),$startDate->format('n'),$startDate->format('d'));
				$class = '_cal_wmarker';
				if( $w != $weekStart ) { $class .= ' _cal_wmarker_top'; }
				$xhtml .= "<td class=\"{$class}\"><a class=\"_cal_wmarker\" href=\"{$url}\">Week {$startDate->format('W')}</a></td>";
				
				$currentDate = clone $startDate;
				$bFinalDateFound = false;
				while( ! $bFinalDateFound ) {
	
					$class = '_cal_date';
					if( $w != $weekStart ) { $class .= ' _cal_date_top'; }
					$layerCount = $this->_matrix->getLayerCountMonthMax($currentDate,$endDate);
					$url = $this->getLink($this->getDayUrl(),$currentDate->format('Y'),$currentDate->format('n'),$currentDate->format('d'));
					//CBS $xhtml .= "<td class=\"{$class}\" colspan=\"{$lcm}\"><a class=\"_cal_date\" href=\"{$url}\">{$currentDate->format('D, M jS, Y')}</a></td>";
					
					$isToday = ($now->format("Ymd") == $currentDate->format("Ymd"));
					
					$xhtml .= "<td class=\"{$class}\" colspan=\"".$lcma[$currentDate->format("N")]."\"><a ";
					if( $isToday ) {
						$xhtml .="id=\"today\" ";
					} 
					$xhtml .= "class=\"_cal_date\" href=\"{$url}\">{$currentDate->format('D, M jS, Y')}</a></td>";
					
					$bFinalDateFound = ( $currentDate->format('N') == DynamicCalendarSettings::getEndDay() );
					$currentDate->modify('+1 Day');
				}
				
				$xhtml .= "</tr>";
	
				$hour = DynamicCalendarSettings::getStartHour() - 1;
				$minute = 0;
							
				for( $s = 0; $s < $stackHeight; $s++ ) {
					$xhtml .= "<tr>";
		
					$hsh = $hourStackHeight * DynamicCalendarSettings::getDisplayHourMultiplier();
		
					if( $s % $hourStackHeight == 0 ) {
						$hour++;
						$minute = 0;
					}
					
					if( $s % $hsh == 0 ) {
					
						
						$tme = new DateTime();
						$tme->setTime($hour,0,0);
	
						// if the user selects a time increment which spans
						// the timeframe reset the row span incrementer to clean up past the selected hour
						if( $s + $hsh > $stackHeight ) {
							$hsh = $stackHeight - $s;
						}
						$xhtml .= "<td class=\"_cal_time\" rowspan=\"{$hsh}\">";
						$xhtml .= $tme->format('g:iA');
						$xhtml .= "</td>";
					}
					
					$pixels = DynamicCalendarSettings::getPixelsPerMinuteGranularity();
					$currentDate = clone $startDate;
					$bFinalDateFound = false;
					while( ! $bFinalDateFound ) {
						
						$layerCount = $this->_matrix->getLayerCount($currentDate);
						$maxLayerCount = $this->_matrix->getLayerCountMonthMax($currentDate,$endDate);
						
						$mDay = $this->_matrix->getEventDay($currentDate);
						$mDay->fillDayWithBlankInfoItems();
						
						//CBS $colspan = $lcm / $layerCount;
						$colspan = $lcma[$currentDate->format("N")] / $layerCount;
						
						for( $l = 0; $l < $layerCount; $l++ ) {
							
							$info = $mDay->getInfo($l,$hour,$minute);
		
							if($info->isBlank()) {
								$class = '';
								if( $minute == 0 ) {
									if( $hour % DynamicCalendarSettings::getDisplayHourMultiplier() == 0 ) {
										$class .= '_cal_bhr';
									}
									else {
										$class .= '_cal_bhrn';
									}
								}
								else {
									$class .= '_cal_bmin';
								}
								if( $l == 0 ) { $class .= ' _cal_bleft'; }
								$xhtml .= "<td class=\"{$class}\" colspan=\"{$colspan}\"><div style=\"min-height:{$pixels}px;\"></div></td>";
							}
							else {
								if( ! $info->isUsed() ) {
									$style = "color:#{$info->getEvent()->getForeground()};background:#{$info->getEvent()->getBackground()};opacity:{$info->getEvent()->getOpacity()};";
									$class = '_cal_e';
									if( $l == 0 ) { $class .= ' _cal_eleft'; }
									$length = $mDay->getInfoLengthFromPos($l,$hour,$minute);
									$xhtml .= "<td rowspan=\"{$length}\" colspan=\"{$colspan}\" class=\"{$class}\" style=\"{$style}\">{$info->getEvent()->getTitle()}</td>";
									$info->setUsed();
								}
							}
						}
		
						$bFinalDateFound = ( $currentDate->format('N') == DynamicCalendarSettings::getEndDay() );
						$currentDate->modify('+1 Day');
					}
					$xhtml .= "</tr>";
		
					$minute = $minute + DynamicCalendarSettings::getMinuteGranularity();
				}
				
				$startDate->modify('+1 Week');
				$w = (int)$startDate->format('W');
			}
			
			$xhtml .= "</table>";
			$xhtml .= "</div>";
			
			return $xhtml;
		}
			
	}
	
	class DynamicCalendarEvents {
		private $_events = array();
		public function getEvents() { return $this->_events; }
		public function setEvents($v)  { $this->_events = $v; }
		public function addEvent($event) { array_push( $this->_events, $event ); $event->setIndex(count($this->_events)); }
		public function getDayEvents($datetime) {
			$events = array();
			foreach( $this->_events as $event ) {
				if( $event->getStartDateTime()->format('Y') == $datetime->getStartDateTime()->format('Y') &&
					$event->getStartDateTime()->format('m') == $datetime->getStartDateTime()->format('m') && 
					$event->getStartDateTime()->format('d') == $datetime->getStartDateTime()->format('d') ) {
					array_push($events,$event);
				}
			}
			return $events;
		}
		public function getTimeSpanEvents($startDate,$endDate) {
			$events = array();
			foreach( $this->_events as $event ) {
				if( $event->getStartDateTime() >= $startDate && $event->getStartDateTime() <= $endDate ) { 
					array_push($events,$event);
				}
			}
			return $events;
		}
		public function __construct() {
			$this->_events = array();
		}
		public function sort() {
			usort($this->_events,array("DynamicCalendarEvents", "eventCompare"));
		}
		public static function eventCompare($e1,$e2) {
			if( $e1->getSortId() != $e2->getSortId() ) {
				return ($e1->getSortId() < $e2->getSortId()) ? -1 : 1;
			}
			else if( $e1->getSortId() == $e2->getSortId() ) {
				if( $e1->getStartDateTime() == $e2->getStartDateTime() ) { return 0; }
				return ($e1->getStartDateTime() < $e2->getStartDateTime()) ? -1 : 1;
			}
		}
		
	}
	
	class DynamicCalendarEventMatrix {
	
		private $_matrix = array();
	
		public function addEvent($event) {
			$year  = (int)$event->getStartDateTime()->format('Y');
			$month = (int)$event->getStartDateTime()->format('m');
			$day   = (int)$event->getStartDateTime()->format('d'); // Returns a leading Zero (must be casted to an int)
			if( ! isset( $this->_matrix[$year][$month][$day] ) ) {
				$this->_matrix[$year][$month][$day] = new DynamicCalendarEventMatrixDay($year,$month,$day);
			}
			$this->_matrix[$year][$month][$day]->addEvent($event);
		}
		
		public function getLayerCount($date) {
			$year  = (int)$date->format('Y');
			$month = (int)$date->format('m');
			$day   = (int)$date->format('d'); // Returns a leading Zero (must be casted to an int)
			if( ! isset( $this->_matrix[$year][$month][$day] ) ) {
				$this->_matrix[$year][$month][$day] = new DynamicCalendarEventMatrixDay($year,$month,$day);
			}
			return $this->_matrix[$year][$month][$day]->getLayerCount();
		}
		
		public function getLayerCountMonthMax($date,$endDate) {
			$maxLayerCount = 0;
			
			$currentDate = clone $date;
			while( $currentDate < $endDate ) {
				$year  = (int)$currentDate->format('Y');
				$month = (int)$currentDate->format('m');
				$day   = (int)$currentDate->format('d'); // Returns a leading Zero (must be casted to an int)
				if( ! isset( $this->_matrix[$year][$month][$day] ) ) {
					$this->_matrix[$year][$month][$day] = new DynamicCalendarEventMatrixDay($year,$month,$day);
				}
				//print 'MAX: '.$maxLayerCount.', '.$this->_matrix[$year][$month][$day]->getLayerCount();
				//print '<br/>'.$year.$month.$day.'<br/>';
				//$maxLayerCount = max( $maxLayerCount, $this->_matrix[$year][$month][$day]->getLayerCount() );
				
				$lc = $this->_matrix[$year][$month][$day]->getLayerCount();
				if( $maxLayerCount == 0 ) {
					$maxLayerCount = $lc;
				} else if( $lc != 0 ) {
					$maxLayerCount = Math::lcm($maxLayerCount,$lc);
				}
				
				$currentDate->modify('+1 Week');
			}
			//print 'MAX: '.$maxLayerCount.', '.$this->_matrix[$year][$month][$day]->getLayerCount();
			//print 'MAX: '.$maxLayerCount.' - '.$date->format('m/d/Y').'<br/>';
			return $maxLayerCount;
		}
		
		
		public function getEventDay($date) {
			$year  = (int)$date->format('Y');
			$month = (int)$date->format('m');
			$day   = (int)$date->format('d'); // Returns a leading Zero (must be casted to an int)
			if( ! isset( $this->_matrix[$year][$month][$day] ) ) {
				$this->_matrix[$year][$month][$day] = new DynamicCalendarEventMatrixDay($year,$month,$day);
			}
			return $this->_matrix[$year][$month][$day];
		}
		
	//	public function getDayMarkup($date,$bIncludeTimeLine=true,$bTimeListCompress=false) {
	//		
	//		$year  = (int)$date->format('Y');
	//		$month = (int)$date->format('m');
	//		$day   = (int)$date->format('d'); // Returns a leading Zero (must be casted to an int)
	//
	//		$retval = '';
	//		
	//		if( ! isset($this->_matrix[$year][$month][$day]) ) {
	//			$this->_matrix[$year][$month][$day] = new DynamicCalendarEventMatrixDay($year,$month,$day);
	//		}
	//		return $this->_matrix[$year][$month][$day]->getDayMarkup($bIncludeTimeLine,$bTimeListCompress);
	//	}
			
		public function printSortMatrix() {
			foreach( $this->_matrix as $year ) {
				foreach( $year as $month ) {
					foreach( $month as $day ) {
						$day->printSortMatrix();
					}
				}
			}
		}
		
	}
	
	class DynamicCalendarEventMatrixDay {
		
		// Two arrays exist to log information/events:
		//   +  Two Dimensional Array: matrix
		//   +  Single Dimensional Array: info
		//
		// A 'matrix' contains index pointers to items in 'info' based on the layer of existence in the calendar
		// 
		// A 'layer' is an column designed to stop events from stacking on top of each other.
		// If an event cannot fit into the first attempted layer a new layer is created and the
		// event is loaded into the new laer.  Thus, two layer exist for the following event insert. 
		//
		private $_matrix = array(); // [ LAYER ][ MINUTE_IN_DAY ]
		private $_layerowner = array();
		private $_info = array();
		private $_year = 0;
		private $_month = 0;
		private $_day = 0;
		
		public function __construct($year,$month,$day) {
			$this->_year = $year;
			$this->_month = $month;
			$this->_day = $day;
			$_matrix[0] = array();
			$this->addLayer(0);
		}
		
		public function &getInfo($layer,$hour,$minute) {
			return $this->_info[ $this->_matrix[$layer][( ( $hour * 60 ) + $minute )] ];
		}
		
		public function getInfoLengthFromPos($layer,$hour,$minute) {
			$len = 0;
			$infoId = $this->_matrix[$layer][( ( $hour * 60 ) + $minute )];
			while( $this->_matrix[$layer][( ( $hour * 60 ) + $minute )] == $infoId ) {
				$len++;
				$minute = $minute + DynamicCalendarSettings::getMinuteGranularity();
			}
			return $len;		
		}
		
		public function fillDayWithBlankInfoItems() {
			
			$activeInfoIndex = count($this->_info);
			
			for($l = 0; $l < $this->getLayerCount(); $l++ ) {
				for($h = DynamicCalendarSettings::getStartHour(); $h < DynamicCalendarSettings::getEndHour() + 1; $h++ ) {
					for( $m = 0; $m < 60; $m = $m + DynamicCalendarSettings::getMinuteGranularity() ) {
						
						$minute = ( ( $h * 60 ) + $m );
						$index = $this->_matrix[$l][$minute];
						
						// The location at index is empty (denoted -1)
						// (a) extend the active "blank" Info Object
						// OR
						// (b) create a new "blank" Info Object
						// 
						if( $index == -1 ) {
							if( isset( $this->_info[$activeInfoIndex] ) ) {
								$this->_info[$activeInfoIndex]->incrementLength();
							}
							else {
								$this->_info[$activeInfoIndex] = new DynamicCalendarEventMatrixInfo();
								$this->_info[$activeInfoIndex]->setBlank(true);
								$this->_info[$activeInfoIndex]->setLength(1);
							}
							$this->_matrix[$l][$minute] = $activeInfoIndex;
						}
						// Event Information is stored here - index the blank object if possible 
						else {
							if( isset( $this->_info[$activeInfoIndex] ) ) {
								$activeInfoIndex++;
							}
						}
					}
				}
				// Index the blank object on layer switch
				if( isset( $this->_info[$activeInfoIndex] ) ) {
					$activeInfoIndex++;
				}
			}
		}
		
		public function printSortMatrix() {
			print "Day: {$this->_year} / {$this->_month} / {$this->_day} :\n";
			print '<table><tr>';
			for( $l = 0; $l < count($this->_matrix); $l++ ) {
				print '<td><pre>';
				print "Layer: {$l}:\n";
				for( $h = DynamicCalendarSettings::getStartHour(); $h <= DynamicCalendarSettings::getEndHour(); $h++ ) {
					print 'H'.$h.': ';
					for( $m = 0; $m < 60; $m = $m + DynamicCalendarSettings::getMinuteGranularity() ) {
						$minute = ( ( $h * 60 ) + $m );
						print $this->_matrix[$l][$minute];
						if( $this->_matrix[$l][$minute] != -1 ) {
							print 'e'; //' ' . $this->_info[$this->_matrix[$l][$minute]]->getEvent()->getStartDateTime()->format('c');
						}
					}
					print "\n";
					}
				print '</pre></td>';
			}
			print '</tr></table>';
		}	
		
		public function getLayerCount() {
			return count($this->_matrix);
		}
		
		private function clearLayer($layer,$index) {
			for($h = DynamicCalendarSettings::getStartHour(); $h <= DynamicCalendarSettings::getEndHour(); $h++ ) {
				for( $m = 0; $m < 60; $m = $m + DynamicCalendarSettings::getMinuteGranularity() ) {
					$minute = $h * 60 + $m;
					if( $this->_matrix[$layer][$minute] == $index ) {
						$this->_matrix[$layer][$minute] = -1;
						///print 'cleared layer: '.$layer.' for index '.$index.'<br/>';
					}
				}
			}
		}
		
		public function addLayer($layer) {
			$l = $this->getLayerCount();
			while( $l <= $layer ) { 
				$this->_matrix[$l] = array();
				$this->_layerowner[$l] = 0;
				for($h = DynamicCalendarSettings::getStartHour(); $h <= DynamicCalendarSettings::getEndHour(); $h++ ) {
					for( $m = 0; $m < 60; $m = $m + DynamicCalendarSettings::getMinuteGranularity() ) {
						$minute = $h * 60 + $m;
						$this->_matrix[$l][$minute] = -1;
					}
				}
				$l++;
			}
		}
		
		public function addEvent($event) {
			$year  = (int)$event->getStartDateTime()->format('Y');
			$month = (int)$event->getStartDateTime()->format('m');
			$day   = (int)$event->getStartDateTime()->format('d'); // Returns a leading Zero (must be casted to an int)
	
			$startHour = (int)substr(DynamicCalendarSettings::getStartHour(),0,2);
			$endHour = (int)substr(DynamicCalendarSettings::getEndHour(),0,2);
			
			if( $startHour > $endHour ) {
				$tmp = $endHour;
				$endHour = $startHour;
				$startHour = $tmp;
			}
			if( $startHour < 0 ) { $startHour = 0; }
			if( $endHour > 23 ) { $endHour = 23; }
	
			
			$startDte = new DateTime("{$year}-{$month}-{$day} {$startHour}:00");
			$endDte   = new DateTime("{$year}-{$month}-{$day} {$endHour}:00");
			$refDte   = clone($startDte);
			
			$infoIndex = count($this->_info);
			
			$this->_info[$infoIndex] = new DynamicCalendarEventMatrixInfo();
			$this->_info[$infoIndex]->setEvent($event);
			$l = 0;
			
			$bIncrement = true;
			
			while( $refDte < $endDte ) {
				if( $refDte >= $event->getStartDateTime() && $refDte < $event->getEndDateTime() ) {
					// [day][layer][hour][minute] = index
					///print '<br/>checking: '.$event->getStartDateTime()->format('Y/m/d H:i')." ({$day},{$l},{$refDte->format('G')}{$refDte->format('i')} - {$event->getIndex()})\n";
					$minute = (int)(((int)$refDte->format('G')) * 60 ) + (int)$refDte->format('i');
					
					if( DynamicCalendarSettings::getSortBySortId() ) {
						
						if( $this->_matrix[$l][$minute] != -1 || ( $this->_layerowner[$l] != 0 && $this->_layerowner[$l] != $event->getSortId() ) ) {
							$this->clearLayer($l,$infoIndex);
							$l++;
							$this->addLayer($l);
							$refDte = clone($startDte);
							$bIncrement = false;
						}
						else {
							$this->_matrix[$l][$minute] = $infoIndex;
							$this->_layerowner[$l] = $event->getSortId();
							$this->_info[$infoIndex]->incrementLength();
							///print '<br/>added: '.$event->getStartDateTime()->format('Y/m/d H:i')." ({$day},{$l},{$refDte->format('G')}{$refDte->format('i')} - {$event->getIndex()})\n";
						}
					}
					else {
					
						if( $this->_matrix[$l][$minute] != -1 ) {
							$this->clearLayer($l,$infoIndex);
							$l++;
							$this->addLayer($l);
							$refDte = clone($startDte);
							$bIncrement = false;
						}
						else {
							$this->_matrix[$l][$minute] = $infoIndex;
							$this->_info[$infoIndex]->incrementLength();
							///print '<br/>added: '.$event->getStartDateTime()->format('Y/m/d H:i')." ({$day},{$l},{$refDte->format('G')}{$refDte->format('i')} - {$event->getIndex()})\n";
						}
					}
				}
				// Move the minute counter up the granularity counter or trigger to be incremented on the next loop
				if( $bIncrement ) {
					$refDte->modify('+'.DynamicCalendarSettings::getMinuteGranularity().' Minute');
				} else {
					$bIncrement = true;
				}
			}
		}
	}
	
	class DynamicCalendarEventMatrixInfo {
		private $_event = null;
		private $_length = 0;
		private $_used = false;
		private $_blank = false;
		public function getEvent() { return $this->_event; }
		public function setEvent(&$v)  { $this->_event = $v; }
		public function getLength() { return $this->_length; }
		public function setLength($v)  { $this->_length = $v; }
		public function incrementLength() { $this->_length++; }
		public function isUsed() { return $this->_used; }
		public function getUsed() { return $this->_used; }
		public function setUsed($v=true)  { $this->_used = $v; }
		public function isBlank() { return $this->_blank; }
		public function getBlank() { return $this->_blank; }
		public function setBlank($v=true)  { $this->_blank = $v; }
	}
	
	class DynamicCalendarEvent {
		private $_id = 0;
		private $_index = 0;
		private $_startDateTime;
		private $_endDateTime;
		private $_title;
		private $_description;
		private $_background = '';
		private $_foreground = '';
		private $_opacity = '1';
		private $_sortId = 0;
		public function getId() { return $this->_id; }
		public function setId($v)  { $this->_id = (int)$v; }
		public function getIndex() { return $this->_index; }
		public function setIndex($v)  { $this->_index = (int)$v; }
		public function getDescription() { return $this->_description; }
		public function setDescription($v)  { $this->_description = $v; }
		public function getTitle() { return $this->_title; }
		public function setTitle($v)  { $this->_title = $v; }
		public function getStartDateTime() { return $this->_startDateTime; }
		public function setStartDateTime($v)  { $this->_startDateTime = new DateTime($v); }
		public function getEndDateTime() { return $this->_endDateTime; }
		public function setEndDateTime($v)  { $this->_endDateTime = new DateTime($v); }
		public function getBackground() { return $this->_background; }
		public function setBackground($v)  { $this->_background = $v; }
		public function getForeground() { return $this->_foreground; }
		public function setForeground($v)  { $this->_foreground = $v; }
		public function getOpacity() { return $this->_opacity; }
		public function setOpacity($v)  { $this->_opacity = $v; }
		public function getSortId() { return $this->_sortId; }
		public function setSortId($v)  { $this->_sortId = $v; }
		
	}
	
	class DynamicCalendarDay {
		private $_dte;
		public function getDate() { return $this->_dte; }
		public function setDate($v) { $this->_dte = $v; }
		public function getWeek() { return $this->_dte->format('W'); }
	}
?>
