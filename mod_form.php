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
        /*
        $mform->addElement('header', 'add_authors', get_string('add_authors', 'form'));
        // Put an array of buttons on the form
        $buttonarray=array();
        $buttonarray[] =& $mform->createElement('button', 'submitbutton', get_string('savechanges'));
        $buttonarray[] =& $mform->createElement('submit', 'cancel', get_string('cancel'));
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
       
        global $USER;
        html_writer::link(new moodle_url('/mod/randomstrayquotes/add_authors.php', array('id' => $COURSE->id)), array('class' => 'btn btn-default'));
       // <a href="http://moodle.org" class="btn btn-default"> Moodle Home</a>
        
        /*if (has_capability('mod/randomstrayquotes:addinstance', $this->context)) {
            $parameters = array('randomstrayquotes' => $this->instance->id, 'courseid' => $COURSE->id);
            $url = new moodle_url('/mod/randomstrayquotes/add_authors.php', $parameters);
            $label = get_string('overview', 'mod_randomstrayquotes');
            $options = array('class' => 'overviewButton');
            $this->content->text .= html_writer::link($url, $label);
        */
        
        //echo $output->single_button(new moodle_url('/mod/randomstrayquotes/add_authors.php', array('id' => $id)), 'Link text', 'get');
        
     

        // Add standard grading elements.
        $this->standard_grading_coursemodule_elements();

        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements();

        // Add standard buttons, common to all modules.
        $this->add_action_buttons();
        
        
    }
}
