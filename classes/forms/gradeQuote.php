<?php
namespace mod_randomstrayquotes\forms;

global $CFG, $DB;

class gradeQuote extends formWithDelete  {

    protected function definition() {
        global $PAGE, $DB, $CFG;
        $mform = $this->_form;
        // Array of parameters passed through the instanciation of the form
        $customdata = $this->_customdata;
        $courseid =  $customdata['courseid'];
        $userid = $customdata['userid'];
        //$ctx = $customdata['ctx'];

        // Texbox to add the grade
        $mform->addElement('text', 'grade', get_string('grade', 'mod_randomstrayquotes'));
        $mform->setType('grade', PARAM_INT);

        // Textbox hidden to pass the courseid
        $mform->addElement('hidden', 'courseid', "$courseid");
        $mform->setType('courseid', PARAM_INT);

        // We format the date and time of the update
        $date = new \DateTime("now");
        $time = $date->format('Y-m-d_H.i');

        // Textbox hidden to pass the user_id
        $mform->addElement('hidden', 'user_id', "$userid");
        $mform->setType('user_id', PARAM_INT);

        // Textbox hidden to pass the context
        //$mform->addElement('hidden', 'ctx', "$ctx");
        //$mform->setType('ctx', PARAM_INT);

        // Put an array of buttons on the form
        $buttonarray = array();
        $buttonarray[] = & $mform->createElement('submit', 'submitbutton', get_string('savechanges'));
        $buttonarray[] = & $mform->createElement('cancel');
      //  $buttonarray[] = & $mform->createElement('submit', 'addcategories', get_string('backtolist', 'mod_randomstrayquotes'), array('value'=> 'addcategories'));
      //  $buttonarray[] = & $mform->createElement('submit', 'addauthors', get_string('backtolist', 'mod_randomstrayquotes'), array('value'=> 'addauthors'));
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);

        //$this->set_data($entry);
    }

}
