<?php

require_once __DIR__ . '/CWebTestCaseCustom.php';
/**
 * Change the following URL based on your server configuration
 * Make sure the URL ends with a slash so that we can use relative URLs in test cases
 */
define('TEST_BASE_URL','http://localhost');

/**
 * The base class for functional test cases.
 * In this class, we set the base URL for the test application.
 * We also provide some common methods to be used by concrete test classes.
 */
class WebTestCase extends CWebTestCaseCustom
{
	private $baseUrl = 'http://localhost';
	/**
	 * Sets up before each test method runs.
	 * This mainly sets the base URL for the test application.
	 */
	protected function setUp(): void
	{
		parent::setUp();
		$selenium_host = getenv("SELENIUM_HOST");
        if ($selenium_host === false) {
            $selenium_host = "localhost";
        }
        $this->setHost($selenium_host);
        $this->setBrowser("chrome");
        $this->setBrowserUrl($this->baseUrl);
        // https://github.com/giorgiosironi/phpunit-selenium/issues/439#issuecomment-561740660
		$this->setDesiredCapabilities([
            "chromeOptions" => [
                "args"  => [
                    "--disable-gpu",
                ],
                // disable loading images
                "prefs" => [
                    "profile" => [
                        "managed_default_content_settings" => [
                            "images" => 2,
                        ],
                    ],
                ],
            ],
        ]);	
    }
}
