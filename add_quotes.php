<?php


//require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once('../../config.php');
//require_once ($CFG->dirroot.'/lib/formslib.php');
//require_once($CFG->dirroot . '/mod/randomstrayquotes/locallib.php');

//global $CFG, $DB, $PAGE, $COURSE;

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Add Quotes');
$PAGE->set_heading('Form Add Quote');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/add_quotes.php');
 

$form = new \mod_randomstrayquotes\forms\addQuotes();

echo $OUTPUT->header();
echo $OUTPUT->heading('Add a Quote');

echo $form->display();

echo $OUTPUT->footer();