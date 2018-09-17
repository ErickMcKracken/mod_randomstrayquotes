<?php
require_once('../../config.php');
global $CFG, $PAGE;
defined('MOODLE_INTERNAL') || die();
require_login();
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('List Categories');
$PAGE->set_heading('List Categories');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/list_categories.php');
echo $OUTPUT->header();
defined('MOODLE_INTERNAL') || die();

$user_id =  required_param('userid', PARAM_INT);
$course_id =  required_param('courseid', PARAM_INT);
$catquery = "Select * from {randomstrayquotes_categories} where course_id = $course_id";
$cat_arr = $DB->get_records_sql($catquery);

if ($cat_arr) {
    $renderer = $PAGE->get_renderer('mod_randomstrayquotes');
    $content = $renderer->display_list_of_categories($cat_arr, $course_id, $user_id);
} else {
    $content = 'Aucunes catégories n\'ont été saisies pour le moment';
}
echo ($content);
echo $OUTPUT->footer();
