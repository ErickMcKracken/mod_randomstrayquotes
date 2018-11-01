<?php
global $CFG, $PAGE;
require_once('../../config.php');
defined('MOODLE_INTERNAL') || die();
require_login();

// Page settings
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('List Categories');
$PAGE->set_heading('List Categories');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/list_categories.php');

//Header
echo $OUTPUT->header();

// We catch the parameters
$user_id =  required_param('userid', PARAM_INT);
$course_id =  required_param('courseid', PARAM_INT);

// We retrieve the categories
$catquery = "Select * from {randomstrayquotes_categories} where course_id = $course_id";
$cat_arr = $DB->get_records_sql($catquery);

// We display the categories submitted as a list
if ($cat_arr) {
    $renderer = $PAGE->get_renderer('mod_randomstrayquotes');
    $content = $renderer->display_list_of_categories($cat_arr, $course_id, $user_id);
} else {
    $content = 'Aucunes catégories n\'ont été saisies pour le moment';
}
echo ($content);
echo $OUTPUT->footer();
