<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * The main newmodule configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod_newmodule
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Module instance settings form
 *
 * @package    mod_newmodule
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_randomstrayquotes_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {
        global $CFG, $COURSE, $DB;

        $mform = $this->_form;

        // Adding the "general" fieldset, where all the common settings are showed.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field.
        $mform->addElement('text', 'name', get_string('newmodulename', 'randomstrayquotes'), array('size' => '64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'newmodulename', 'randomstrayquotes');

        // Adding the standard "intro" and "introformat" fields.
        if ($CFG->branch >= 29) {
            $this->standard_intro_elements();
        } else {
            $this->add_intro_editor();
        }

        // Adding authors
        
        $mform->addElement('header', 'addauthors', get_string('addauthors', 'form'));
        // Texbox to add an author
        $attributes = array('size' => '50', 'maxlength' => '100');
        $mform->addElement('text', 'author_name', get_string('author_name'), $attributes);
        $mform->addRule('author_name', null, 'required', null, 'client');
        $mform->setType('name', PARAM_TEXT);
        
        //Hidden textbox containing the courseid
        $courseid = $COURSE->id;
        $mform->addElement('hidden', 'course_id', get_string('course_id'));
        $mform->setDefault('course_id', $courseid);    
        $mform->setType('course_id', PARAM_TEXT);
        
        //Upload a picture of the author
       /*
        $mform->addElement('filepicker', 'userfile', get_string('authorpix'), null, array('maxbytes' => $maxbytes, 'accepted_types' => '*'));
        $content = $mform->get_file_content('userfile');
        $name = $mform->get_new_filename('userfile');
        $success = $mform->save_file('userfile', $fullpath, $override);
        $storedfile = $mform->save_stored_file('userfile');
        */
        
        //Authors Listing
        $selectArray = array();
        $authors_arr = array();
        $authquery = "Select distinct * from {block_strayquotes_authors}";
        $category_arr = $DB->get_records_sql($authquery);

        foreach($authors_arr as $author) {
            $key = $author->id;
            $value = $author->author_name;
            $selectArray[$key] = $value;
         if ($authors_arr ) {
                    $renderer = $this->page->get_renderer('mod_randomstrayquotes');
                    $content = $renderer->display_authors($authors_arr);
                } else {
                    $content = 'Aucuns auteurs n\'ont été saisis pour le moment';
                }
        }
        
        
        
        
        
        
        
        

        // Add standard grading elements.
        $this->standard_grading_coursemodule_elements();

        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements();

        // Add standard buttons, common to all modules.
        $this->add_action_buttons();
        
        
    }
}
