<?php
namespace DeepDiveDatingApp\DeepDiveDating\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\QueryDataSet;
use PHPUnit\DbUnit\Database\Connection;
use PHPUnit\DbUnit\Operation\{Composite, Factory, Operation};

// grab the encrypted properties file
require_once("/etc/apache2/capstone-mysql/Secrets.php");

// autoload Composer packages
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");

/**
 * Abstract class containing universal and project specific mySQL parameters
 *
 * This class is designed to lay the foundation of the unit tests per project. It loads the all the database
 * parameters about the project so that table specific tests can share the parameters in on place. To use it:
 *
 * 1. Rename the class from DataDesignTest to a project specific name (e.g., ProjectNameTest)
 * 2. Rename the namespace to be the same as in (1) (e.g., Edu\Cnm\ProjectName\Tests)
 * 3. Modify DataDesignTest::getDataSet() to include all the tables in your project.
 * 4. Modify DataDesignTest::getConnection() to include the correct mySQL properties file.
 * 5. Have all table specific tests include this class.
 *
 * *NOTE*: Tables must be added in the order they were created in step (2).
 *
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 * referenced from: https://bootcamp-coders.cnm.edu/class-materials/unit-testing/phpunit/
 **/
abstract class DeepDiveDatingAppTest extends TestCase {

	use TestCaseTrait;

	/**
	 * PHPUnit database connection interface
	 * @var Connection $connection
	 **/
	protected $connection = null;

	/**
	 * assembles the table from the schema and provides it to PHPUnit
	 *
	 * @return QueryDataSet assembled schema for PHPUnit
	 **/

	/**
	 * invalid id to use for an INT UNSIGNED field (maximum allowed INT UNSIGTNED in mySQL) + 1
	 * @see https://dev.mysql.com/doc/refman/5.6/en/integer-types.html mySQL Integer Types
	 * @var string INVALID_KEY
	 **/
	const INVALID_KEY = "0b402f14-8271-4ae2-8074-84c78799c70a";

	/**
	 * assembles the table from the schema and provides it to PHPUnit
	 *
	 * @return QueryDataSet assembled schema for PHPUnit
	 **/
	public final function getDataSet() : QueryDataSet {
		$dataset = new QueryDataSet($this->getConnection());

		// add all the tables for the project here
		//These TABLES *MUST* BE LISTED IN THE SAME ORDER THEY WERE CREATED!!!!!
		$dataset->addTable("user", "SELECT userId, userActivationToken, userAgent, userAvatarUrl, userBlocked, userEmail, userHandle, userHash, userIpAddress FROM `user`");
		$dataset->addTable("userDetail");
		$dataset->addTable("question");
		$dataset->addTable("answer");
		$dataset->addTable("match", "SELECT matchUserId, matchToUserId, matchApproved FROM `match`");
		$dataset->addTable("report");
		return ($dataset);
	}
		/**
		 * templates the setUp method that runs before each test; this method expunges the database before each run
		 *
		 * @see https://phpunit.de/manual/current/en/fixtures.html#fixtures.more-setup-than-teardown PHPUnit Fixtures: setUp and tearDown
		 * @see https://github.com/sebastianbergmann/dbunit/issues/37 TRUNCATE fails on tables which have foreign key constraints
		 * @return Composite array containing delete and insert commands
		 **/
		public final function getSetUpOperation(): Composite {
			return new Composite([
				Factory::DELETE_ALL(),
				Factory::INSERT()
			]);
}
	/**
	 * templates the tearDown method that runs after each test; this method expunges the database after each run
	 *
	 * @return Operation delete command for the database
	 **/
	public final function getTearDownOperation(): Operation {
			return (Factory::DELETE_ALL());
}
	/**
	 * sets up the database connection and provides it to PHPUnit
	 *
	 * @see <https://phpunit.de/manual/current/en/database.html#database.configuration-of-a-phpunit-database-testcase>
	 * @return Connection PHPUnit database connection interface
	 **/
	public final function getConnection(): Connection {
			// if the connection hasn't been established, create it
			if($this->connection === null) {
				// connect to mySQL and provide the interface to PHPUnit
				$secrets =  new \Secrets("/etc/apache2/capstone-mysql/cohort23/dateadan.ini");
				$pdo = $secrets->getPdoObject();
				$this->connection = $this->createDefaultDBConnection($pdo, $secrets->getDatabase());
			}
			return ($this->connection);
	}
	/**
	 * returns the actual PDO object; this is a convenience method
	 *
	 * @return \PDO active PDO object
	 **/
	public final function getPDO() {
			return ($this->getConnection()->getConnection());
		}
}
