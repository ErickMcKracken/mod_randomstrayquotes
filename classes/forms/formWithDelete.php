<?php

namespace mod_randomstrayquotes\forms;

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/lib/formslib.php');
require_once($CFG->dirroot . '/mod/randomstrayquotes/locallib.php');

abstract class formWithDelete extends \moodleform {

    public function is_deleted(){
        $mform = &$this->_form;
        // Utilisation de http://php.net/manual/en/function.get-class-methods.php
        // pour connaître les méthodes de la classe ou d'un object
        // nous avons fait
        // get_class_methods($mform)
        if ($mform->isSubmitted()){
          $isDeleted = $mform->getSubmitValue('delete');
          if ($isDeleted){
              return true;
          }
        }
        return false;
    }
}
