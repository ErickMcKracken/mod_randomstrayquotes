<?php
require_once('../../config.php');
global $CFG, $PAGE;

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('List Categories');
$PAGE->set_heading('List Categories');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/list_categories.php');
echo $OUTPUT->header();
defined('MOODLE_INTERNAL') || die();

$course_id = 21155;
$catquery = "Select * from {randomstrayquotes_categories} where course_id = $course_id";
$cat_arr = $DB->get_records_sql($catquery);

if ($cat_arr) {
    $renderer = $PAGE->get_renderer('mod_randomstrayquotes');
    $content = $renderer->display_list_of_categories($cat_arr);
} else {
    $content = 'Aucunes catégories n\'ont été saisies pour le moment';
}
echo ($content);
echo $OUTPUT->footer();