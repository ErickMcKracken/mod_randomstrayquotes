<?php

namespace mod_randomstrayquotes\forms;

defined('MOODLE_INTERNAL') || die();

global $CFG, $DB;

#require_once('../../config.php');
require_once ($CFG->dirroot . '/lib/formslib.php');
require_once($CFG->dirroot . '/mod/randomstrayquotes/locallib.php');

class editAuthor extends \moodleform {

    protected function definition() {
        global $PAGE, $DB;
        $mform = $this->_form;

        // Array of parameters passed through the instanciation of the form
        $customdata = $this->_customdata;

        // Recuperate the author id via the Array of parameters passed through the instanciation of the form
        $authorid = $customdata['authorid'];

        //Query to fill the fields
        $queryauth = "Select distinct * from {randomstrayquotes_authors} where id = $authorid";
        $author = $DB->get_record_sql($queryauth);

        // Texbox to edit an author
        $attributes = array('size' => '50', 'maxlength' => '100');
        $mform->addElement('text', 'author_name', get_string('author_name', 'mod_randomstrayquotes'), $attributes);
        $mform->addRule('author_name', null, 'required', null, 'client');
        $mform->setDefault('author_name', $author->author_name);
        $mform->setType('author_name', PARAM_TEXT);

        // Instanciation of the entry object
        $entry = new \stdClass;
        // Recuperate the file id of the picture already present via the Array of parameters passed through the instanciation of the form
        $entry->id = $customdata['photofile']->get_itemid();

        // The user put a new file as replacement. Recuperate the id of the file uploaded by the user in the draft area
        $draftitemid = file_get_submitted_draft_itemid('userfile');
        $maxbytes = '50000';
        // Setup the draft area and make it ready for upload
        file_prepare_draft_area(
                $draftitemid,
                $customdata['ctx']->id,
                'mod_randomstrayquotes',
                'content',
                $entry->id,
                array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 50)
                );
        $entry->userfile = $draftitemid;
        // Add the file area to the form
        $mform->addElement('filemanager', 'userfile', get_string('AuthorPicture', 'mod_randomstrayquotes'), null, [
            'subdirs' => 0,
            'maxbytes' => $maxbytes,
            'areamaxbytes' => 10485760,
            'maxfiles' => 1,
            'accepted_types' => ['image']
                ]
        );
        // We recuperate the id of the item in the draft area
        $entry->userfile = $draftitemid;

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

        // Display an array of buttons on the form
        $buttonarray = array();
        $buttonarray[] = & $mform->createElement('submit', 'submitbutton', get_string('savechanges'));
        $buttonarray[] = & $mform->createElement('cancel');
        $buttonarray[] = & $mform->createElement('submit', 'delete', get_string('delete'), array('class' => 'btn btn-danger'));
        $buttonarray[] = & $mform->createElement('submit', 'backtolist', get_string('backtolist', 'mod_randomstrayquotes'), array('value'=> 'backtolist'));
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);

        // ??
        $this->set_data($entry);
    }

}
