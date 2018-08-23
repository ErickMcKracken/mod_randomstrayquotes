<?php

namespace mod_randomstrayquotes\forms;

defined('MOODLE_INTERNAL') || die();

#require_once('../../config.php');
require_once ($CFG->dirroot.'/lib/formslib.php');
require_once($CFG->dirroot . '/mod/randomstrayquotes/locallib.php');

class addQuotes extends \moodleform {

    protected function definition() {
        global $PAGE, $DB, $CFG;
        $mform = $this->_form;
        $courseid = 21155;

        // Array of parameters passed through the instanciation of the form
        $customdata = $this->_customdata;

        // Combobox with the authors
        $selectAuth= array();
        $selectAuth[0] = "Tous les auteurs...";
        $queryauth = "Select distinct * from {randomstrayquotes_authors}";
        $authors_arr = $DB->get_records_sql($queryauth);

        foreach ($authors_arr as $author) {
            $key = $author->id;
            $value = $author->author_name;
            $selectAuth[$key] = $value;
        }
        $mform->addElement('select', 'author', get_string('author_name', 'mod_randomstrayquotes'), $selectAuth);
        $mform->setType('id', PARAM_INT);

        // Combobox with categories
        $selectArray = array();
        $selectArray[0] = get_string('General', 'mod_randomstrayquotes');
        $query = "Select * from {randomstrayquotes_categories}";
        $category_arr = $DB->get_records_sql($query);

        foreach($category_arr as $category) {
            $key = $category->id;
            $value = $category->category_name;
            $selectArray[$key] = $value;
        }

        $mform->addElement('select', 'category', get_string('category', 'mod_randomstrayquotes'),$selectArray);
      //$mform->setDefault('config_category',  $category['selected']);
        $mform->setDefault('category', 0);
        $mform->setType('category', PARAM_INT);


          // Add the quote
        $mform->addElement('textarea', 'quote', get_string('quote', 'mod_randomstrayquotes'), 'wrap="virtual" rows="6" cols="85"');
        $mform->addRule('quote', null, 'required', null, 'client');
        $mform->setType('quote', PARAM_TEXT);

        // Indicate the source
        $mform->addElement('text', 'source', get_string('source', 'mod_randomstrayquotes'));
        $mform->setType('source', PARAM_TEXT);

        // Is the quote visible or not?
        $attributes = array();
        $attributesbtn = array();
        $attributesbtn[1] = "class='radio-opt'";
        $attributes[1] = "class='radio-group'";
        $radioarray=array();
        $radioarray[] = $mform->createElement('radio', 'visible', '', get_string('yes', 'mod_randomstrayquotes'), 1, $attributesbtn);
        $radioarray[] = $mform->createElement('radio', 'visible', '', get_string('no', 'mod_randomstrayquotes'), 0, $attributesbtn);
        $mform->addGroup($radioarray, 'radioar', get_string('visible', 'mod_randomstrayquotes'), array(' '), false);
        $mform->setDefault('visible', 1);
        $mform->setType('visible', PARAM_INT);

        // Indicate the username
        $mform->addElement('hidden', 'username');
        $mform->setType('username', PARAM_TEXT);

        // We format the date and time of the update
        $date = new \DateTime("now");
        $time = $date->format('Y-m-d_H.i');

        // Textbox hidden to pass  the date of the update
        $mform->addElement('hidden', 'time_added', "$time");
        $mform->setType('time_added', PARAM_ALPHANUMEXT);

        // Put an array of buttons on the form
        $buttonarray = array();
        $buttonarray[] = & $mform->createElement('submit', 'submitbutton', get_string('savechanges'));
        $buttonarray[] = & $mform->createElement('cancel');
        $buttonarray[] = & $mform->createElement('submit', 'delete', get_string('delete'), array('class' => 'btn btn-danger'));
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);


     }
}
