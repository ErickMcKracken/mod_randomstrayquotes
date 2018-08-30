<?php

namespace mod_randomstrayquotes\forms;

defined('MOODLE_INTERNAL') || die();

global $CFG, $DB;

#require_once('../../config.php');
require_once ($CFG->dirroot.'/lib/formslib.php');
require_once($CFG->dirroot . '/mod/randomstrayquotes/locallib.php');

class editQuote extends \moodleform {

    protected function definition() {
        global $PAGE, $DB, $CFG;
        $mform = $this->_form;

         // Array of parameters passed through the instanciation of the form
         $customdata = $this->_customdata;

         // Recuperate the author id via the Array of parameters passed through the instanciation of the form
         $quoteid = $customdata['quoteid'];
         $courseid = $customdata['courseid'];
         //var_dump("$courseid"); die;

        //$quoteid = 1;
        $queryquote = "Select * from {randomstrayquotes_quotes} where id= $quoteid";
        $quote = $DB->get_record_sql($queryquote);

        // Query to get the authors
        $selectAuth= array();
        $selectAuth[0] = "Tous les auteurs...";
        $queryauth = "Select * from {randomstrayquotes_authors}";
        $authors_arr = $DB->get_records_sql($queryauth);

        foreach ($authors_arr as $author) {
            $key = $author->id;
            $value = $author->author_name;
            $selectAuth[$key] = $value;
        }

         // Combobox with the authors
        $mform->addElement('select', 'author', get_string('author_name', 'mod_randomstrayquotes'), $selectAuth);
        $mform->setDefault('author', $quote->author_id);
        $mform->getElement('author')->setSelected($quote->author_id);
        $mform->setType('author', PARAM_INT);

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
        $mform->setDefault('category',  $quote->category_id);
        $mform->getElement('category')->setSelected($quote->category_id);
        $mform->setType('category', PARAM_INT);

          // Add the quote
        $mform->addElement('textarea', 'quote', get_string('quote', 'mod_randomstrayquotes'), 'wrap="virtual" rows="6" cols="85"');
        $mform->addRule('quote', null, 'required', null, 'client');
        $mform->setDefault('quote',  $quote->quote);
        $mform->setType('quote', PARAM_TEXT);

        // Indicate the source
        $mform->addElement('text', 'source', get_string('source', 'mod_randomstrayquotes'), array('width' => '70'));
        $mform->setDefault('source',  $quote->source);
        $mform->setType('source', PARAM_TEXT);

        // Is the quote visible or not?
        $attributes = array();
        $attributesbtn = array();
        $attributesbtn[1] = "class='radio-opt'";
        $attributes[1] = "class='radio-group'";
        $radioarray=array();
        $radioarray[] = $mform->createElement('radio', 'visible', '', get_string('yes', 'mod_randomstrayquotes'), 1, $attributesbtn);
        $radioarray[] = $mform->createElement('radio', 'visible', '', get_string('no', 'mod_randomstrayquotes'), 0, $attributesbtn);
        $mform->setType('visible', PARAM_INT);
        $mform->setDefault('visible', $quote->visible);
        $mform->addGroup($radioarray, 'radioar', get_string('visible', 'mod_randomstrayquotes'), array(' '), false);


        // Indicate the user_id
        $mform->addElement('hidden', 'user_id');
        $mform->setDefault('user_id', $quote->user_id);
        $mform->setType('user_id', PARAM_INT);

        // Indicate the quote id
        $mform->addElement('hidden', 'quote_id', "$quoteid");
        $mform->setDefault('quote_id', $quote->id);
        $mform->setType('quote_id', PARAM_INT);

        // Indicate the courseid
        $mform->addElement('hidden', 'course_id');
        $mform->setDefault('course_id', $quote->course_id);
        $mform->setType('course_id', PARAM_INT);

        // We format the date and time of the update
        $date = new \DateTime("now");
        $time = $date->format('Y-m-d_H.i');

        // Textbox hidden to pass  the date of the update
        $mform->addElement('hidden', 'time_updated', "$time");
        $mform->setType('time_updated', PARAM_ALPHANUMEXT);

        // Put an array of buttons on the form
        $buttonarray = array();
        $buttonarray[] = & $mform->createElement('submit', 'submitbutton', get_string('savechanges'));
        $buttonarray[] = & $mform->createElement('cancel');
        $buttonarray[] = & $mform->createElement('submit', 'delete', get_string('delete'), array('class'=> 'btn btn-danger', 'value'=> 'delete'));
        $buttonarray[] = & $mform->createElement('submit', 'backtolist', get_string('backtolist', 'mod_randomstrayquotes'), array('value'=> 'backtolist'));
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);

     }
}
