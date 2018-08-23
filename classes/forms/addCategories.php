<?php

namespace mod_randomstrayquotes\forms;

defined('MOODLE_INTERNAL') || die();

#require_once('../../config.php');
require_once ($CFG->dirroot.'/lib/formslib.php');
require_once($CFG->dirroot . '/mod/randomstrayquotes/locallib.php');

class addCategories extends \moodleform {
     protected function definition() {
         global $CFG, $DB, $COURSE, $USER;
$mform = $this->_form;

// Texbox to add a category
$attributes = array('size' => '50', 'maxlength' => '100');
$mform->addElement('text', 'category_name', get_string('category_name', 'mod_randomstrayquotes'), $attributes);
$mform->addRule('category_name', null, 'required', null, 'client');
$mform->setType('category_name', PARAM_TEXT);

// textbox hidden containing the user id
$mform->addElement('hidden', 'user_id', $USER->id);
$mform->setType('user_id', PARAM_INT);

// textbox hidden containing the courseid
$courseid = $COURSE->id;
$mform->addElement('hidden', 'course_id');
$mform->setDefault('course_id', $courseid);
$mform->setType('course_id', PARAM_INT);

// Put an array of buttons on the form
$buttonarray = array();
$buttonarray[] = & $mform->createElement('submit', 'submitbutton', get_string('savechanges'));
$buttonarray[] = & $mform->createElement('cancel');
$buttonarray[] = & $mform->createElement('submit', 'delete', get_string('delete'), array('class' => 'btn btn-danger'));
$mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);

     }
}
