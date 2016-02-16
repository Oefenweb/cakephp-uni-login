<?php
/**
 * All UniLogin plugin tests.
 *
 */
class AllUniLoginTest extends CakeTestCase {

/**
 * Suite define the tests for this plugin.
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All UniLogin test');

		$path = CakePlugin::path('UniLogin') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}

}
