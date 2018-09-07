<?php
namespace mod_randomstrayquotes\forms;

defined('MOODLE_INTERNAL') || die();

global $CFG, $DB;

#require_once('../../config.php');
require_once ($CFG->dirroot.'/lib/formslib.php');
require_once($CFG->dirroot . '/mod/randomstrayquotes/locallib.php');

class navigation extends \moodleform {

    protected function definition() {
        global $PAGE, $DB;
        $mform = $this->_form;

        // Array of parameters passed through the instanciation of the form
        $customdata = $this->_customdata;
        $courseid =  $customdata['courseid'];
        $userid = $customdata['userid'];
        $ctx = $customdata['ctx'];

        // Put an array of buttons on the form
        $buttonarray = array();
        $buttonarray[] = & $mform->createElement('submit', 'Addquotes', get_string('Addquotes', 'mod_randomstrayquotes'), array('value'=> 'Addquotes'));
        $buttonarray[] = & $mform->createElement('submit', 'Addauthors', get_string('Addauthors', 'mod_randomstrayquotes'), array('value'=> 'Addauthors'));
        $buttonarray[] = & $mform->createElement('submit', 'Addcategories', get_string('Addcategories', 'mod_randomstrayquotes'), array('value'=> 'Addcategories'));
        $buttonarray[] = & $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);

    }
}
