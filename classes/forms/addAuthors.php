<?php
namespace mod_randomstrayquotes\forms;

require_once ($CFG->dirroot . '/lib/formslib.php');
require_once($CFG->dirroot . '/mod/randomstrayquotes/locallib.php');

class addAuthors extends \moodleform {

    protected function definition() {
        global $CFG, $DB, $COURSE, $USER, $CONTEXT;

        // Array of parameters passed through the instanciation of the form
        $customdata = $this->_customdata;

        // Create the form
        $mform = $this->_form;

        // Texbox to add an author
        $attributes = array('size' => '50', 'maxlength' => '100');
        $mform->addElement('text', 'author_name', get_string('author_name', 'mod_randomstrayquotes'), $attributes);
        $mform->addRule('author_name', null, 'required', null, 'client');
        $mform->setType('author_name', PARAM_TEXT);

        $entry = new \stdClass;
        $entry->id = null;
        $draftitemid = null;

        // Add picture
        $maxbytes = '50000';
        file_prepare_draft_area(
                $draftitemid, $customdata['ctx']->id, 'mod_randomstrayquotes', 'content', $entry->id, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 50)
        );
        $draftitemid = file_get_submitted_draft_itemid('userfile');
        $mform->addElement('filemanager', 'userfile', get_string('AuthorPicture', 'mod_randomstrayquotes'), null, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'areamaxbytes' => 10485760, 'maxfiles' => 1,
            'accepted_types' => array('image'), null));

        $entry->attachments = $draftitemid;

        // Textbox hidden to pass the course_id
        $mform->addElement('hidden', 'courseid', $customdata['courseid']);
        $mform->setType('courseid', PARAM_INT);

        // We format the date and time of the update
        $date = new \DateTime("now");
        $time = $date->format('Y-m-d_H.i');

        // Textbox hidden to pass  the date of the update
        $mform->addElement('hidden', 'time_added', "$time");
        $mform->setType('time_added', PARAM_ALPHANUMEXT);

        // Display an array of buttons on the form
        $buttonarray = array();
        $buttonarray[] = & $mform->createElement('submit', 'submitbutton', get_string('savechanges'));
        $buttonarray[] = & $mform->createElement('cancel');
        //$buttonarray[] = & $mform->createElement('submit', 'delete', get_string('delete'), array('class' => 'btn btn-danger'));
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $this->set_data($entry);
    }

}
