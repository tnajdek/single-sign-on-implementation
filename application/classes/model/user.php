<?php defined('SYSPATH') or die('No direct script access.');

class Model_User extends ORM {
    protected $_has_many = array('openidassociations'=>array());
    
    public function getDisplayName() {
	if(isset($this->name)) return $this->name;
	else return 'User'.$this->id;
    }
}

?>