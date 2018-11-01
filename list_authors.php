<?php
global $CFG, $PAGE;
require_once('../../config.php');
defined('MOODLE_INTERNAL') || die();
require_login();

// Page settings
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('List Authors');
$PAGE->set_heading('List Authors');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/list_authors.php');

// Header
echo $OUTPUT->header();

// We retrieve the parameters
$userid  =  required_param('userid', PARAM_INT);
$course_id =  required_param('courseid', PARAM_INT);

// We retrieve the authors submitted
$authorsquery = "Select * from {randomstrayquotes_authors} where course_id = $course_id";
$authors_arr = $DB->get_records_sql($authorsquery);

// We display the authors as a list
if ($authors_arr) {
    $renderer = $PAGE->get_renderer('mod_randomstrayquotes');
    $content = $renderer->display_list_of_authors($authors_arr, $course_id, $userid);
} else {
    $content = 'Aucuns auteurs n\'ont été saisies pour le moment';
}
echo ($content);
echo $OUTPUT->footer();
