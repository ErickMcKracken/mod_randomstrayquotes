<?php
namespace mod_randomstrayquotes\forms;

defined('MOODLE_INTERNAL') || die();

global $CFG, $DB;

#require_once('../../config.php');
require_once ($CFG->dirroot.'/lib/formslib.php');
require_once($CFG->dirroot . '/mod/randomstrayquotes/locallib.php');

class editCategory extends formWithDelete {

    protected function definition() {
        global $PAGE, $DB;
        $mform = $this->_form;

        // Array of parameters passed through the instanciation of the form
        $customdata = $this->_customdata;

         //Query to fill the fields
        $catid =  $customdata['catid'];
        $querycat = "Select distinct * from {randomstrayquotes_categories} where id = $catid";
        $category = $DB->get_record_sql($querycat);

        // Texbox to edit a category
        $attributes = array('size' => '50', 'maxlength' => '100');
        $mform->addElement('text', 'category_name', get_string('category_name', 'mod_randomstrayquotes'), $attributes);
        $mform->addRule('category_name', null, 'required', null, 'client');
        $mform->setDefault('category_name', $category->category_name);
        $mform->setType('category_name', PARAM_TEXT);

        // Indicate the category_id
        $mform->addElement('hidden', 'category_id');
        $mform->setDefault('category_id', $category->id);
        $mform->setType('category_id', PARAM_INT);

        // Indicate the user_id
        $mform->addElement('hidden', 'user_id');
        $mform->setDefault('user_id', $category->user_id);
        $mform->setType('user_id', PARAM_INT);

        // Indicate the courseid
        $mform->addElement('hidden', 'course_id');
        $mform->setDefault('course_id', $category->course_id);
        $mform->setType('course_id', PARAM_INT);

        // Put an array of buttons on the form
        $buttonarray = array();
        $buttonarray[] = & $mform->createElement('submit', 'submitbutton', get_string('savechanges'));
        $buttonarray[] = & $mform->createElement('cancel');
        $buttonarray[] = & $mform->createElement('submit', 'delete', get_string('delete', 'mod_randomstrayquotes'), array('class'=> 'btn btn-danger', 'value'=> 'delete'));
        //$buttonarray[] = & $mform->createElement('submit', 'backtolist', get_string('backtolist', 'mod_randomstrayquotes'), array('value'=> 'backtolist'));
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
     }
}
