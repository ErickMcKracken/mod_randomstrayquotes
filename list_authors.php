<?php
require_once('../../config.php');
global $CFG, $PAGE;

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('List Authors');
$PAGE->set_heading('List Authors');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/list_authors.php');
echo $OUTPUT->header();
defined('MOODLE_INTERNAL') || die();

$course_id =  required_param('courseid', PARAM_INT);
$authorsquery = "Select * from {randomstrayquotes_authors} where course_id = $course_id";
$authors_arr = $DB->get_records_sql($authorsquery);

if ($authors_arr) {
    $renderer = $PAGE->get_renderer('mod_randomstrayquotes');
    $content = $renderer->display_list_of_authors($authors_arr);
} else {
    $content = 'Aucuns auteurs n\'ont été saisies pour le moment';
}
echo ($content);
echo $OUTPUT->footer();
