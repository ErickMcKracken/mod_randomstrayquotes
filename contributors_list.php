<?php
global $CFG, $PAGE;
require_once('../../config.php');
defined('MOODLE_INTERNAL') || die();
require_login();

// Page settings
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Contributors List');
$PAGE->set_heading('Contributors List');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/contributors_list.php');

// Header
echo $OUTPUT->header();
echo $OUTPUT->heading('Contributor\'s List');

// We retrieve the parameters
$userid =  required_param('userid', PARAM_INT);
$courseid =  required_param('courseid', PARAM_INT);

// We retrieve the contributors
$contributorsquery = "Select distinct user_id  from {randomstrayquotes_quotes} where course_id = $courseid and visible = 1";
$contributors_arr = $DB->get_records_sql($contributorsquery);

// We display the contributors as a list
if ($contributors_arr) {
    $renderer = $PAGE->get_renderer('mod_randomstrayquotes');
    $content = $renderer->display_list_of_contributors($contributors_arr, $courseid, $userid);
} else {
    $content = 'Aucuns contributeurs pour le moment';
}

// We display the page
echo ($content);
echo $OUTPUT->footer();
