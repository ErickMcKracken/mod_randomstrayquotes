<?php
require_once('../../config.php');
global $CFG, $PAGE;
defined('MOODLE_INTERNAL') || die();
require_login();
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Contributors List');
$PAGE->set_heading('Contributors List');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/contributors_list.php');
echo $OUTPUT->header();
echo $OUTPUT->heading('Contributor\'s List');
$userid =  required_param('userid', PARAM_INT);
$courseid =  required_param('courseid', PARAM_INT);
$contributorsquery = "Select distinct user_id  from {randomstrayquotes_quotes} where course_id = $courseid and visible = 1";
$contributors_arr = $DB->get_records_sql($contributorsquery);

if ($contributors_arr) {
    $renderer = $PAGE->get_renderer('mod_randomstrayquotes');
    $content = $renderer->display_list_of_contributors($contributors_arr, $courseid, $userid);
} else {
    $content = 'Aucuns contributeurs pour le moment';
}
echo ($content);
echo $OUTPUT->footer();
