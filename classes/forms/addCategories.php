<?php

namespace mod_randomstrayquotes\forms;

defined('MOODLE_INTERNAL') || die();

#require_once('../../config.php');
require_once ($CFG->dirroot.'/lib/formslib.php');
require_once($CFG->dirroot . '/mod/randomstrayquotes/locallib.php');

class addCategories extends \moodleform {
     protected function definition() {
         global $CFG, $DB, $COURSE;
$mform = $this->_form;

// Texbox to add a category
$attributes = array('size' => '50', 'maxlength' => '100');
$mform->addElement('text', 'category_name', get_string('category_name', 'mod_randomstrayquotes'), $attributes);
$mform->addRule('category_name', null, 'required', null, 'client');
$mform->setType('category_name', PARAM_TEXT);



/*
// textbox hidden containing the username
$mform->addElement('hidden', 'user_name', get_string('course_id'));
$mform->setDefault('user_name', $username);    
$mform->setType('user_name', PARAM_INT);
*/

/*
// textbox hidden containing the courseid
$courseid = $COURSE->id;
$mform->addElement('hidden', 'course_id', get_string('course_id'));
$mform->setDefault('couse_id', $courseid);    
$mform->setType('course_id', PARAM_INT);
*/



// Put an array of buttons on the form
        $buttonarray=array();
        $buttonarray[] =& $mform->createElement('button', 'submitbutton', get_string('savechanges'));
        $buttonarray[] =& $mform->createElement('submit', 'cancel', get_string('cancel'));
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        
       




     }  
}