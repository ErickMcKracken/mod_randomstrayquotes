<?php

namespace mod_randomstrayquotes\forms;

defined('MOODLE_INTERNAL') || die();

#require_once('../../config.php');
require_once ($CFG->dirroot.'/lib/formslib.php');
require_once($CFG->dirroot . '/mod/randomstrayquotes/locallib.php');
/*
require_once($CFG->libdir . '/filelib.php');
require_once(dirname(__FILE__) . '/lib.php');
*/

class addAuthors extends \moodleform {
     protected function definition() {
         global $CFG, $DB, $COURSE;
$mform = $this->_form;
// Texbox to add an author
$attributes = array('size' => '50', 'maxlength' => '100');
$mform->addElement('text', 'author_name', get_string('author_name', 'mod_randomstrayquotes'), $attributes);
$mform->addRule('author_name', null, 'required', null, 'client');
$mform->setType('author_name', PARAM_TEXT);
 /*
// Add picture
$maxbytes = '50000';
$mform->addElement('filepicker', 'userfile', get_string('AuthorPicture', 'mod_randomstrayquotes'), null, array('maxbytes' => $maxbytes, 'accepted_types' => '.jpg', '.gif', '.png'));
$context = $this->page->context;
$contextid = $context->id;
$component= 'mod_randomstrayquotes';
$content = $mform->get_file_content('userfile');
$name = $mform->get_new_filename('userfile');
$fullpath = "";
$override = "";
$success = $mform->save_file('userfile', $fullpath, $override);
*/

        // Textbox hidden to pass the user_id
        $mform->addElement('hidden', 'user_id', "$author->user_id");
        $mform->setType('user_id', PARAM_INT);

         // Textbox hidden to pass the authorid
        $mform->addElement('hidden', 'authorid', "$authorid");
        $mform->setType('authorid', PARAM_INT);

        // Textbox hidden to pass the course_id
        $mform->addElement('hidden', 'courseid', "$author->course_id");
        $mform->setType('courseid', PARAM_INT);
        
        // We format the date and time of the update
        $date = new \DateTime("now");
        $time = $date->format('Y-m-d_H.i');
        
        // Textbox hidden to pass  the date of the update
        $mform->addElement('hidden', 'time_added', "$time");
        $mform->setType('time_added', PARAM_ALPHANUMEXT);


// Put an array of buttons on the form
        $buttonarray=array();
        $buttonarray[] =& $mform->createElement('button', 'submitbutton', get_string('savechanges'));
        $buttonarray[] =& $mform->createElement('submit', 'cancel', get_string('cancel'));
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
             
 }   
}
