<?php
namespace mod_randomstrayquotes\forms;

defined('MOODLE_INTERNAL') || die();

global $CFG, $DB;

#require_once('../../config.php');
require_once ($CFG->dirroot.'/lib/formslib.php');
require_once($CFG->dirroot . '/mod/randomstrayquotes/locallib.php');

class editCategory extends \moodleform {
    
    protected function definition() {
        global $PAGE, $DB;
        $mform = $this->_form;
        
 //Query to fill the fields
$catid = required_param('catid', PARAM_INT);
$querycat = "Select distinct * from {randomstrayquotes_categories} where id = $catid";
$category = $DB->get_record_sql($querycat);          
        
// Texbox to edit a category
$attributes = array('size' => '50', 'maxlength' => '100');
$mform->addElement('text', 'category_name', get_string('category_name', 'mod_randomstrayquotes'), $attributes);
$mform->addRule('category_name', null, 'required', null, 'client');
$mform->setDefault('category_name', $category->category_name);
$mform->setType('category_name', PARAM_TEXT);

// Indicate the user_id
$mform->addElement('hidden', 'user_id');
$mform->setDefault('user_id', $category->user_id);
$mform->setType('user_id', PARAM_INT);
  
// Indicate the courseid
$mform->addElement('hidden', 'course_id');
$mform->setDefault('course_id', $category->course_id);
$mform->setType('course_id', PARAM_INT);

/*
// Indicate the date of the add
$date = new DateTime("now", core_date::get_user_timezone_object());
$date->setTime(0, 0, 0);
$mform->addElement('hidden', 'time_added');
$mform->setDefault('time_added', $date); 
$mform->setType('time_added', PARAM_TEXT);
*/

// Put an array of buttons on the form
        $buttonarray=array();
        $buttonarray[] =& $mform->createElement('button', 'submitbutton', get_string('savechanges'));
        $buttonarray[] =& $mform->createElement('submit', 'cancel', get_string('cancel'));
        $buttonarray[] =& $mform->createElement('submit', 'delete', get_string('delete'), array('class'=> 'btn btn-danger', 'value'=> 'deleterecord'));
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        
     }
     
     public function delete_category($DB, $catid){
         null;
     }
}