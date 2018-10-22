<?php
require_once('../../config.php');
require_once("$CFG->dirroot/mod/randomstrayquotes/locallib.php");
require_once($CFG->dirroot . '/mod/randomstrayquotes/db/access.php');
global $CFG, $PAGE;
defined('MOODLE_INTERNAL') || die();
require_login();
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('List of Contributions');
$PAGE->set_heading('List of Contributions');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/list_of_contributions.php');

$userid =  required_param('userid', PARAM_INT);
$courseid =  required_param('courseid', PARAM_INT);

echo $OUTPUT->header();
echo $OUTPUT->heading('List of Contributions');
echo("<table><tr><td>");
echo ("<img src=" . get_user_image($userid, $courseid) . ">" );
echo ("</td><td>");
echo ("<h2>" . get_user_name($userid) . "</h2>");
echo("</table></tr></td>");

$nbr_grading_instances_query = "Select count(*) from {randomstrayquotes} where course = $courseid and grade <> 0";
$nbr_grading_instances = $DB->get_records_sql($nbr_grading_instances_query);
$values = array_keys($nbr_grading_instances);

if ($values[0] > 1){
  $disable_grading_button = true;
  $cm = null;
}else{
  $disable_grading_button = false;
  $instancesquery = "select * from {randomstrayquotes} where course = $courseid and grade  <> 0";
  $instance = $DB->get_record_sql($instancesquery);
  $randomstrayquotes  = $DB->get_record('randomstrayquotes', array('id' =>  $instance->id), '*', MUST_EXIST);
  $course     = $DB->get_record('course', array('id' => $randomstrayquotes->course), '*', MUST_EXIST);
  $cm         = get_coursemodule_from_instance('randomstrayquotes', $randomstrayquotes->id, $course->id, false, MUST_EXIST);
}

$contributionsquery = "Select * from {randomstrayquotes_quotes} where user_id = $userid and course_id = $courseid and visible = 1";
$contributions_arr = $DB->get_records_sql($contributionsquery);

if ($contributions_arr) {
    $renderer = $PAGE->get_renderer('mod_randomstrayquotes');
    $content = $renderer->display_list_of_contributions($contributions_arr, $courseid, $userid, $cm, $disable_grading_button);
} else {
    $content = 'Aucunes contributions pour le moment';
}

echo ($content);
echo $OUTPUT->footer();
