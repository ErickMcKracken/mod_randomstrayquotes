<?php
require_once('../../config.php');
global $CFG, $PAGE;

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('List Quotes');
$PAGE->set_heading('List Quotes');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/list_quotes.php');
echo $OUTPUT->header();
defined('MOODLE_INTERNAL') || die();

$course_id =  required_param('courseid', PARAM_INT);
$quotesquery = "Select * from {randomstrayquotes_quotes} where course_id = $course_id";
$quotes_arr = $DB->get_records_sql($quotesquery);

if ($quotes_arr) {
    $renderer = $PAGE->get_renderer('mod_randomstrayquotes');
    $content = $renderer->display_list_of_quotes($quotes_arr);
} else {
    $content = 'Aucunes citations n\'ont été saisies pour le moment';
}
echo ($content);
echo $OUTPUT->footer();
