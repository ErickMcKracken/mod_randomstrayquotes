<?php
//require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once('../../config.php');
//require_once ($CFG->dirroot.'/lib/formslib.php');
//require_once($CFG->dirroot . '/mod/randomstrayquotes/locallib.php');

global $CFG, $DB, $PAGE, $COURSE;

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Edit Categories');
$PAGE->set_heading('Form Edit Categories');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/edit_category.php');
 
$form = new \mod_randomstrayquotes\forms\editCategory();

echo $OUTPUT->header();
echo $OUTPUT->heading('Edit a category');

echo $form->display();
echo $OUTPUT->footer();

?>