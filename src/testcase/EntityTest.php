<?php 

use PHPUnit\Framework\TestCase;
use TAS\Entity;

/**
 *
 * @backupGlobals disabled
 */
class EntityTest extends TestCase {
    
    public function testEntity_LoadSuccess(){
        $entity = new Entity();
        
        $this->assertEquals(false, $entity->IsLoaded());
    } 
    
    public function testEntityValidate_ForStringSuccess() {
        $values = array('string' => 'Abhi');
        $tablename = 'validatecolumns';
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(false, ! is_bool($a));
        $this->assertEquals(true, $a);
    }
    
    public function testEntityValidate_ForStringFailure_SizeLimit() {
        $values = array('string' => 'Abhishek Chauhan');
        $tablename = 'validatecolumns';
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(false, $a);
    }
    
    public function testEntityValidate_IntAndNotNullSuccess () {
        $values = array('integer' => 123);
        $tablename = 'validatecolumns';
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(true, $a);
        
        $values = array('integer' => -12);
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(true, $a);
    }
    
    public function testEntityValidate_Int_Null_Success () {
        $values = array('int_null' => '');
        $tablename = 'validatecolumns';
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(true, $a);
        
        $values = array('int_null' => 0);
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(true, $a);
    }
    
    public function testEntityValidate_IntAndNotNullFailure () {
        $values = array('integer' => '');
        $tablename = 'validatecolumns';
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(false, $a);
        
        $values = array('integer' => 'sdasd');
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(false, $a);
    }
    
    public function testEntityValidate_Int_Null_Failure () {
        $values = array('int_null' => 'sdf');
        $tablename = 'validatecolumns';
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(false, $a);
    }
    
    public function testEntityValidate_FloatAndNotNullSuccess () {
        $values = array('float' => 10.99);
        $tablename = 'validatecolumns';
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(true, $a);
        
        $values = array('float' => -12.3);
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(true, $a);
    }
    
    public function testEntityValidate_Float_Null_Success () {
        $values = array('float_null' => '');
        $tablename = 'validatecolumns';
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(true, $a);
        
        $values = array('float_null' => 0.00);
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(true, $a);
    }
    
    public function testEntityValidate_FloatAndNotNullFailure () {
        $values = array('float' => '');
        $tablename = 'validatecolumns';
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(false, $a);
        
        $values = array('float' => 'sdasd');
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(false, $a);
    }
    
    public function testEntityValidate_Float_Null_Failure () {
        $values = array('float_null' => 'sdf');
        $tablename = 'validatecolumns';
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(false, $a);
    }
    
    public function testEntityValidate_DateAndNotNullSuccess () {
        $values = array('date' => date('Y-m-d'));
        $tablename = 'validatecolumns';
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(true, $a);
    }
    
    public function testEntityValidate_Date_Null_Success () {
        $values = array('date_null' => '');
        $tablename = 'validatecolumns';
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(true, $a);
        
        $values = array('date_null' => date('Y-m-d'));
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(true, $a);
    }
    
    public function testEntityValidate_DateAndNotNullFailure () {
        $values = array('date' => '');
        $tablename = 'validatecolumns';
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(false, $a);
        
        $values = array('date' => 'sdasd');
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(false, $a);
        
        $values = array('date' => 323);
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(false, $a);
    }
    
    public function testEntityValidate_Date_Null_Failure () {
        $values = array('date_null' => 'sdf');
        $tablename = 'validatecolumns';
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(false, $a);
        
        $values = array('date_null' => 21);
        $a = Entity::Validate($values, $tablename);
        $this->assertEquals(false, $a);
    }
    
    
}
