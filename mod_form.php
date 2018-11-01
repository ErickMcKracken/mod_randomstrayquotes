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

        $id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
        $r  = optional_param('r', 0, PARAM_INT);  // ... newmodule instance ID - it should be named as the first character of the module.

        global $USER;

        if ($id) {
            $cm         = get_coursemodule_from_id('randomstrayquotes', $id, 0, false, MUST_EXIST);
            $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
            $randomstrayquotes  = $DB->get_record('randomstrayquotes', array('id' => $cm->instance), '*', MUST_EXIST);
        } else if ($r) {
            $randomstrayquotes  = $DB->get_record('randomstrayquotes', array('id' => $r), '*', MUST_EXIST);
            $course     = $DB->get_record('course', array('id' => $randomstrayquotes->course), '*', MUST_EXIST);
            $cm         = get_coursemodule_from_instance('randomstrayquotes', $randomstrayquotes->id, $course->id, false, MUST_EXIST);
        }


        //Students can add quotes?
        $attributes = array();
        $attributesbtn = array();
        $attributesbtn[1] = "class='radio-opt'";
        $attributes[1] = "class='radio-group'";
        $radioarray=array();
        $radioarray[] = $mform->createElement('radio', 'admin_setting_students_add_quotes', '', get_string('yes', 'mod_randomstrayquotes'), 1, $attributesbtn);
        $radioarray[] = $mform->createElement('radio', 'admin_setting_students_add_quotes', '', get_string('no', 'mod_randomstrayquotes'), 0, $attributesbtn);
        $mform->addGroup($radioarray, 'radioar', get_string('studentsaddquotes', 'mod_randomstrayquotes'), array(' '), false);

        if (empty($randosmstrayquotes) || $radomstrayquotes->students_add_quotes == 1){
            $mform->setDefault('admin_setting_students_add_quotes', 1);
        }else{
            $mform->setDefault('admin_setting_students_add_uotes', 0);
       }
        $mform->setType('admin_setting_students_add_quotes', PARAM_INT);

        //Students can add authors?
        $attributes = array();
        $attributesbtn = array();
        $attributesbtn[1] = "class='radio-opt'";
        $attributes[1] = "class='radio-group'";
        $radioarray=array();
        $radioarray[] = $mform->createElement('radio', 'admin_setting_students_add_authors', '', get_string('yes', 'mod_randomstrayquotes'), 1, $attributesbtn);
        $radioarray[] = $mform->createElement('radio', 'admin_setting_students_add_authors', '', get_string('no', 'mod_randomstrayquotes'), 0, $attributesbtn);
        $mform->addGroup($radioarray, 'radioar', get_string('studentsaddauthors', 'mod_randomstrayquotes'), array(' '), false);
        if (empty($randomstrayquotes) || $randomstrayquotes->students_add_authors == 1) {
            $mform->setDefault('admin_setting_students_add_authors', 1);
        }else{
            $mform->setDefault('admin_setting_students_add_authors', 0);
        }
        $mform->setType('admin_setting_students_add_authors', PARAM_INT);

        //Students can add categories?
        $attributes = array();
        $attributesbtn = array();
        $attributesbtn[1] = "class='radio-opt'";
        $attributes[1] = "class='radio-group'";
        $radioarray = [];
        $radioarray[] = $mform->createElement('radio', 'admin_setting_students_add_categories', '', get_string('yes', 'mod_randomstrayquotes'), 1, $attributesbtn);
        $radioarray[] = $mform->createElement('radio', 'admin_setting_students_add_categories', '', get_string('no', 'mod_randomstrayquotes'), 0, $attributesbtn);
        $mform->addGroup($radioarray, 'radioarray', get_string('studentsaddcategories', 'mod_randomstrayquotes'), array(' '), false);
        if (empty($randomstrayquotes) || $randomstrayquotes->students_add_categories==1) {
            $mform->setDefault('admin_setting_students_add_categories', 1);
        }else{
            $mform->setDefault('admin_setting_students_add_categories', 0);
        }
        $mform->setType('admin_setting_students_add_categories', PARAM_INT);

        // Add standard grading elements.
        $this->standard_grading_coursemodule_elements();

        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements();

        // Add standard buttons, common to all modules.
        $this->add_action_buttons();


    }
}
