<?php
require_once dirname(__FILE__) . '/../../lib/XS.class.php';
require_once dirname(__FILE__) . '/../../lib/XSFieldScheme.class.php';

/**
 * Test class for XSFieldScheme.
 * Generated by PHPUnit on 2011-09-15 at 17:19:35.
 */
class XSFieldSchemeTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var XS
	 */
	protected $xs;
	/**
	 * @var XSFieldScheme
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->xs = new XS(end($GLOBALS['fixIniData']));
		$this->object = $this->xs->scheme;
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
		$this->object = null;
		$this->xs = null;
	}
	
	/**
	 * @expectedException XSException
	 * @expectedExceptionMessage Duplicated field name: `pid'
	 */
	public function testAddField1()
	{
		$this->object->addField(new XSFieldMeta('pid'));
	}
	
	/**
	 * @expectedException XSException
	 * @expectedExceptionMessage Duplicated TITLE field: `subject2' and `subject'
	 */
	public function testAddField2()
	{
		$this->object->addField('subject2', array('type' => 'title'));
	}
	
	public function testAddField3()
	{
		$fields = $this->object->getAllFields();
		$this->object->addField('date2', array('index' => 'both', 'type' => 'date'));
		$field = $this->object->getField('date2');
		$this->assertEquals(XSFieldMeta::TYPE_DATE, $field->type);
		$this->assertTrue($field->hasIndexMixed());
		$this->assertTrue($field->hasIndexSelf());
		$this->assertEquals(count($fields), $field->vno);
	}			

	public function testCheckValid()
	{
		$this->assertTrue($this->object->checkValid());
		
		$object = new XSFieldScheme;
		$this->assertFalse($object->checkValid());
	}

	public function testGetIterator()
	{
		$fields = $this->object->getAllFields();
		foreach ($this->object as $key => $value)
		{
			$this->assertArrayHasKey($key, $fields);
			$this->assertEquals($fields[$key], $value);
		}
	}

	public function testLogger()
	{
		$log = XSFieldScheme::logger();
		$this->assertInstanceOf('XSFieldScheme', $log);
		$this->assertEquals('id', strval($log->getFieldId()));
		$this->assertFalse($log->getFieldTitle());
		$this->assertNotEquals(false, $log->getField('pinyin', false));
		$this->assertNotEquals(false, $log->getField('partial', false));
		$this->assertTrue($log->getField('total')->isNumeric());
		$this->assertTrue($log->getField('lastnum')->isNumeric());
		$this->assertTrue($log->getField('currnum')->isNumeric());
		$this->assertFalse($log->getField('currtag')->isNumeric());
		$this->assertTrue($log->getField('body')->isSpeical());
		$this->assertEquals(XSFieldScheme::MIXED_VNO, $log->getFieldBody()->vno);
	}
}

