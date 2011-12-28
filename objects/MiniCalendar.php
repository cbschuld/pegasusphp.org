<?php

	/**
	 * MiniCalendar
	 *
	 * @package PegasusPHP
	 */
	class MiniCalendar {
		
		private $_startDay = 1;
		private $_today = null;
		private $_selected = null;
		private $_showAllDays = false;
		private $_showYearInMonth = false;
		
		public function setShowAllDays($v=true) { $this->_showAllDays = $v; }
		public function setShowYearInMonth($v=true) { $this->_showYearInMonth = $v; }
		
		public function getMonth($year,$month) {
			$date = new DateTime('now');
			if( $this->_today == null ) { $this->_today = clone $date; }
			$date->setDate($year,$month,1);
			$cDte = $this->getFirstViewableDay($year,$month);
			$eDte = $this->getLastViewableDay($year,$month);
			
			$xhtml  = '<table class="minical"><tr><td class="month" colspan="7">';
			$xhtml .= $date->format('F');
			if( $this->_showYearInMonth ) {
				 $xhtml .= ' ' . $date->format('Y');
			}
			$xhtml .= '</td></tr>';
			
			$cDte->modify('-7 Day');
			for( $d = 0; $d < 7; $d++ ) {
				$xhtml .= '<td class="dayofweek">'.$cDte->format('D').'</td>';
				$cDte->modify('+1 Day');
			}
			
			while( $cDte < $eDte ) {
				if( $cDte->format('N') == $this->_startDay ) {
					$xhtml .= '<tr>';
				}
				$class = 'day';
				if( $cDte->format('N') >= 6 ) {
					$class .= ' weekend';
				}
				else {
					$class .= ' weekday';
				}
				if( $cDte < $this->_today ) {
					$class .= ' past';
				}
				if( $cDte->format('Y-m-d') == $this->_today->format('Y-m-d') && ! ( $this->_showAllDays || $cDte->format('m') != $month ) ) { $class .= ' today'; }
				if( $cDte == $this->_selected ) { $class .= ' selected'; }
				if( $this->_showAllDays && $cDte->format('m') != $month ) {
					$class .= ' notdayofmonth';
				}
				$xhtml .= "<td class=\"{$class}\">";
				if( $cDte->format('m') == $month || $this->_showAllDays ) {
					$xhtml .= $this->getDay($cDte->format('Y'),$cDte->format('m'),$cDte->format('d'));
				}
				else {
					$xhtml .= '&nbsp;';
				}
				$xhtml .= '</td>';

				$cDte->modify('+1 Day');
				if( $cDte->format('') == $this->_startDay ) {
					$xhtml .= '</tr>';
				}
			}
			
			$xhtml .= '</table>';
			return $xhtml;
		}
		
		public function getDay($year,$month,$day) {
			return '<a class="day" href="/calendar/day/'.$year.'/'.$month.'/'.$day.'/">'.$day.'</a>';
		}

		public function getFirstViewableDay($year,$month) {
			$currentDate = new DateTime("$year-$month-1");
			while( $currentDate->format('N') != $this->_startDay ) {
				$currentDate->modify('-1 Day');
			}
			return $currentDate;
		}
		
		public function getLastViewableDay($year,$month) {
			$currentDate = new DateTime("$year-$month-1");
			$currentDate->modify('+1 Month');
			$currentDate->modify('-1 Day');
			while( (int)$currentDate->format('m') == (int)$month || $currentDate->format('N') != $this->_startDay ) {
				$currentDate->modify('+1 Day');
			}
			return $currentDate;
		}
		
	}

?>