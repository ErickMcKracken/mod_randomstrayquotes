<?php

namespace mod_randomstrayquotes\forms;

require_once($CFG->dirroot.'/lib/formslib.php');
require_once($CFG->dirroot . '/mod/randomstrayquotes/locallib.php');

class addCategories extends formWithDelete {
     protected function definition() {
         global $CFG, $DB, $COURSE, $USER;
          $mform = $this->_form;
          $customdata = $this->_customdata;

          //Query to fill the fields
          $courseid =  $customdata['courseid'];

          // Texbox to add a category
          $attributes = array('size' => '50', 'maxlength' => '100');
          $mform->addElement('text', 'category_name', get_string('category_name', 'mod_randomstrayquotes'), $attributes);
          $mform->addRule('category_name', null, 'required', null, 'client');
          $mform->setType('category_name', PARAM_TEXT);

          // textbox hidden containing the user id
          $mform->addElement('hidden', 'user_id', $USER->id);
          $mform->setType('user_id', PARAM_INT);

          // textbox hidden containing the courseid
          $mform->addElement('hidden', 'course_id');
          $mform->setDefault('course_id', $courseid);
          $mform->setType('course_id', PARAM_INT);

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
          //$buttonarray[] = & $mform->createElement('submit', 'delete', get_string('delete'), array('class' => 'btn btn-danger'));
          $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);

     }
}
