<?php

	class BrowserTest extends PHPUnit_Framework_TestCase {

		public function testCount() {
			$conn = new PDO("sqlite:".dirname(__FILE__).'/useragent.db');
			$sql = 'SELECT DISTINCT browser,platform,version,mobile,robot,aol,aolversion FROM agent';
			$count = 0;
			foreach ($conn->query($sql) as $row) {
				$count++;
			}
			echo "Number of Distinct Browsers to be tested: {$count}\n";
		}

		public function testGetterSetters() {
			$b = new Browser();

			$b->setBrowser(Browser::BROWSER_BLACKBERRY);
			$b->setPlatform(Browser::PLATFORM_BLACKBERRY);
			$b->setVersion("4.2.1");

			$this->assertEquals(Browser::BROWSER_BLACKBERRY,$b->getBrowser());
			$this->assertEquals(Browser::PLATFORM_BLACKBERRY,$b->getPlatform());
			$this->assertEquals("4.2.1",$b->getVersion());
		}

		public function testBrowser() {
			$conn = new PDO("sqlite:".dirname(__FILE__).'/useragent.db');
			$sql = 'SELECT user_agent,browser,platform,version,mobile,robot,aol,aolversion FROM agent';
			foreach ($conn->query($sql) as $row) {
				$b = new Browser($row["user_agent"]);
				$this->assertEquals($row["browser"], $b->getBrowser(), $row["user_agent"]);
			}
		}

		public function testPlatform() {
			$conn = new PDO("sqlite:".dirname(__FILE__).'/useragent.db');
			$sql = 'SELECT user_agent,browser,platform,version,mobile,robot,aol,aolversion FROM agent';
			foreach ($conn->query($sql) as $row) {
				$b = new Browser($row["user_agent"]);
				$this->assertEquals($row["platform"], $b->getPlatform(), $row["user_agent"]);
			}
		}

		public function testVersion() {
			$conn = new PDO("sqlite:".dirname(__FILE__).'/useragent.db');
			$sql = 'SELECT user_agent,browser,platform,version,mobile,robot,aol,aolversion FROM agent';
			foreach ($conn->query($sql) as $row) {
				$b = new Browser($row["user_agent"]);
				$this->assertEquals($row["version"], $b->getVersion(), $row["user_agent"]);
			}
		}

		public function testMobileDevices() {
			$conn = new PDO("sqlite:".dirname(__FILE__).'/useragent.db');
			$sql = 'SELECT user_agent,browser,platform,version,mobile,robot,aol,aolversion FROM agent';
			foreach ($conn->query($sql) as $row) {
				$b = new Browser($row["user_agent"]);
				$this->assertEquals(((int)$row["mobile"] > 0 ), $b->isMobile(), $row["user_agent"]);
			}
		}

		public function testIsChromeFrame() {
			$userAgents = array();
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; chromeframe; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.3)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; chromeframe; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; MS-RTC LM 8)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; chromeframe; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; Tablet PC 2.0)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; chromeframe; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET CLR 1.1.4322; OfficeLiveConnector.1.4; OfficeLivePatch.1.3; InfoPath.1)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; chromeframe; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; chromeframe; SLCC1; .NET CLR 2.0.50727; .NET CLR 1.1.4322; .NET CLR 3.5.30729; .NET CLR 3.0.30729)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; chromeframe; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; chromeframe)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; GTB6; chromeframe; .NET CLR 3.0.04506.30; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; GTB6; chromeframe; .NET CLR 2.0.50727; OfficeLiveConnector.1.3; OfficeLivePatch.0.0; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; msn OptimizedIE8;DEAT)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; GTB6; chromeframe; .NET CLR 2.0.50727; InfoPath.1)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; GTB6; chromeframe; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; chromeframe; .NET CLR 3.5.30729; FDM)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; chromeframe; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; InfoPath.2; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; chromeframe; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; InfoPath.2)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; chromeframe; .NET CLR 1.1.4322)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; chromeframe; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; OfficeLiveConnector.1.4; OfficeLivePatch.1.3)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; chromeframe)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/4.0; chromeframe; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.3; Zune 3.0)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; GTB6; chromeframe; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; chromeframe)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; chromeframe; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; chromeframe; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; chromeframe; .NET CLR 2.0.50727)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; chromeframe; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.2; .NET CLR 3.0.04506.30)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; chromeframe; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.3)";
			$userAgents[] = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; chromeframe; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; MS-RTC LM 8)";

			foreach($userAgents as $userAgent) {
				$b = new Browser($userAgent);
				$this->assertEquals(Browser::BROWSER_IE, $b->getBrowser());
				$this->assertTrue($b->isChromeFrame());
			}
		}
	}

?>