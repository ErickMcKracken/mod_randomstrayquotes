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
        
        $customdata = $this->_customdata;
        
        //Query to fill the fields
        $authorid = $customdata['authorid'];//required_param('authorid', PARAM_INT);
        $queryauth = "Select distinct * from {randomstrayquotes_authors} where id = $authorid";
        $author = $DB->get_record_sql($queryauth);
        // Texbox to edit an author
        $attributes = array('size' => '50', 'maxlength' => '100');
        $mform->addElement('text', 'author_name', get_string('author_name', 'mod_randomstrayquotes'), $attributes);
        $mform->addRule('author_name', null, 'required', null, 'client');
        $mform->setDefault('author_name', $author->author_name);
        $mform->setType('author_name', PARAM_TEXT);

        $entry = new \stdClass;
        $entry->id = $customdata['photofile']->get_itemid();

        $draftitemid = file_get_submitted_draft_itemid('userfile');
        $maxbytes = '50000';
        file_prepare_draft_area(
                $draftitemid, 
                $customdata['ctx']->id, 
                'mod_randomstrayquotes', 
                'content', 
                $entry->id, 
                array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 50)
                );
        $entry->userfile = $draftitemid;

        $mform->addElement('filemanager', 'userfile', get_string('AuthorPicture', 'mod_randomstrayquotes'), null, [
            'subdirs' => 0,
            'maxbytes' => $maxbytes,
            'areamaxbytes' => 10485760,
            'maxfiles' => 1,
            'accepted_types' => ['image']
                ]
        );
        $entry->userfile = $draftitemid;

        //$mform->setDefault('userfile', $draftitemid);

        // Indicate the user_id
        $mform->addElement('hidden', 'user_id', "$author->user_id");
        $mform->setType('user_id', PARAM_INT);

        $mform->addElement('hidden', 'authorid', "$authorid");
        $mform->setType('authorid', PARAM_INT);

        // Indicate the courseid
        $mform->addElement('hidden', 'courseid', "$author->course_id");
        $mform->setType('courseid', PARAM_INT);
        
        // Indicate the date of the add
        $date = new \DateTime("now");
        $time = $date->format('Y-m-d_H.i');

        $mform->addElement('hidden', 'time_added', "$time");
        $mform->setType('time_added', PARAM_ALPHANUMEXT);
        // Put an array of buttons on the form
        $buttonarray = array();
        $buttonarray[] = & $mform->createElement('submit', 'submitbutton', get_string('savechanges'));
        $buttonarray[] = & $mform->createElement('cancel');
        $buttonarray[] = & $mform->createElement('submit', 'delete', get_string('delete'), array('class' => 'btn btn-danger'));
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        
        $this->set_data($entry);
    }

}
