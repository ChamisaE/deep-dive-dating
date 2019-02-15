<?php

namespace DeepDiveDatingApp\DeepDiveDating\Test;

use DeepDiveDatingApp\DeepDiveDating\{Answer};

require_once(dirname(__DIR__) . "autoload.php");
require_once(dirname(__DIR__,2)."/lib/uuid.php");


/**
* unit test for the Answer class
* PDO methods are located in the Answer class
* @ see php/Classes/Answer.php
* @author Natalie Woodard
*/
class AnswerTest extends DeepDiveDatingAppTest {
/**
* Answer from users
* @var Answer answer
**/
protected $answer = null;

/**
* valid id to create the answer object to own the test
*@var uuid $VALID_ANSWER_QUESTION_ID
**/
protected $VALID_ANSWER_QUESTION_ID;

/**
* valid id to create the object to own the test
* @var string $VALID_ANSWER_USER_ID
**/
protected $VALID_ANSWER_USER_ID = "PHPUnit test passing";

/**
* Result of the answer input from user
* @var string $VALID_ANSWER_RESULT
**/
protected $VALID_ANSWER_RESULT;

/**
 *Score of the answers compared to Dan's preferred answers
 *@var int $VALID_ANSWER_SCORE
 */
protected $VALID_ANSWER_SCORE;
/**
* create all dependent objects so that the test can run properly
*/
/**
* perform the actual insert method and enforce that is meets expectations i.e, corrupted data is worth nothing
**/

public function testValidAnswerInsert(){
$numRows = $this->getConnection()->getRowCount("answer");

//create the answer object
$answer = new Answer(generateUuidV4(), $this->VALID_ANSWER_QUESTION_ID, $this->VALID_ANSWER_USER_ID, $this->VALID_ANSWER_RESULT, $this->VALID_ANSWER_SCORE);
//insert the answer object
$answer->insert($this->getPDO());

//grab the data from MySQL and enforce that it meets expectations
$pdoAnswer = Answer::getAnswerByAnsweranswerId($this->getPDO(), $answer->getAnswerQuestionId());
$this->assertEquals($numRows +1, $this->getConnection()->getRowCount("answer"));
$this->assertEquals($pdoAnswer->getAnswerQuestionId(), $answer->getAnswerQuestionId());
$this->assertEquals($pdoAnswer->getAnswerUserId(), $answer->getAnswerUserId());
$this->assertEquals($pdoAnswer->getAnswerResult(), $answer->getAnswerResult());
$this->assertEquals($pdoAnswer->getAnswerScore(), $answer->getAnswerScore());
}

/**
* create a answer object, update it in the database, and then enforce that it meets expectations
**/
public function testValidAnswerDelete() {
//grab the number of answers and save it for the test
$numRows = $this->getConnection()->getRowCount("answer");

//create the answer object
$answer = new Answer(generateUuidV4(), $this->VALID_ANSWER_QUESTION_ID, $this->VALID_ANSWER_USER_ID, $this->VALID_ANSWER_RESULT, $this->VALID_ANSWER_SCORE);

//insert the answer object
$answer->insert($this->getPDO());

//delete the answer from the database
$this->assertSame($numRows +1, $this->getConnection()->getRowCount("answer"));
$answer->delete($this->getPDO);

//enforce that the deletion was successful
$pdoAnswer = Answer::getAnswerByAnswerQuestionId($this->getPDO(), $answer->getAnswerQuestionId());
$this->assertNull($pdoAnswer);
$this->assertEquals($numRows, $this->getConnection()->getRowCount("answer"));
}

/**
* try and grab an answer by a primary that does not exist
*/

public function testInvalidGetByAnswerQuestionId(){
//grab the answer by an invalid key
$answer = Answer::getAnswerByAnswerQuestionId($this->getPDO(), DeepDiveDatingAppTest::INVALID_KEY);
$this->assertEmpty($answer);
}

/**
* insert an answer object, grab it by the content, and enforce that it meets expectations
*/
public function testValidGetAnswerByAnswerUserId() {
$numRows = $this->getConnection()->getRowCount("answer");

//create a answer object and insert it into the database
$answer = new Answer(generateUuidV4(), $this->VALID_ANSWER_QUESTION_ID, $this->VALID_ANSWER_USER_ID, $this->VALID_ANSWER_RESULT, $this->VALID_ANSWER_SCORE);

//insert the answer into the database
$answer->insert($this->getPDO());

//grab the answer from the database
$results = Answer::getAnswerByAnswerUserId($this->getPDO(), $answer->getAnswerUserId());
$this->assertEquals($numRows +1, $this->getConnection()->getRowCount("answer"));

$pdoAnswer = $results[1];

	$this->assertEquals($pdoAnswer->getAnswerQuestionId(), $answer->getAnswerQuestionId());
	$this->assertEquals($pdoAnswer->getAnswerUserId(), $answer->getAnswerUserId());
	$this->assertEquals($pdoAnswer->getAnswerResult(), $answer->getAnswerResult());
	$this->assertEquals($pdoAnswer->getAnswerScore(), $answer->getAnswerScore());
}
/**
* try and grab the answer by an answer that does not exist
*/
public function testInvalidGetByAnswerUserId(){
$answer = Answer::getAnswerByAnswerUserId($this->getPDO());
$this->assertEmpty($answer);
}

/**
* insert an answer use getAll method, then enforce it meets expectation
*/
public function testGetAllAnswers() {
	$numRows = $this->getConnection()->getRowCount("answer");

//insert the answer into the database
	$answer = new Answer(generateUuidV4(), $this->VALID_ANSWER_QUESTION_ID, $this->VALID_ANSWER_USER_ID, $this->VALID_ANSWER_RESULT, $this->VALID_ANSWER_SCORE);

//insert the answer into the database
	$answer->insert($this->getPDO());

//grab the results from mySQL and enforce it meets expectations
	$results = Answer::getAllAnswers($this->getPDO());
	$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("answer"));
	$this->assertCount(1, $results);
//$this->assertContainsOnlyInstancesOf()

//grab the results from the array and make sure it meets expectations
	$pdoAnswer = $results[0];
//$this->assertEquals($pdoAnswer->getAnswerQuestionId(), $answer->getAnswerQuestionId());
	$this->assertEquals($pdoAnswer->getAnswerQuestionId(), $answer->getAnswerQuestionId());
	$this->assertEquals($pdoAnswer->getAnswerUserId(), $answer->getAnswerUserId());
	$this->assertEquals($pdoAnswer->getAnswerResult(), $answer->getAnswerResult());
	$this->assertEquals($pdoAnswer->getAnswerScore(), $answer->getAnswerScore());
}
}
