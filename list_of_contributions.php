<?php
global $CFG, $PAGE, $USER;
require_once('../../config.php');
require_once("$CFG->dirroot/mod/randomstrayquotes/locallib.php");
require_once($CFG->dirroot . '/mod/randomstrayquotes/db/access.php');
defined('MOODLE_INTERNAL') || die();
require_login();

//Page settings
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('List of Contributions');
$PAGE->set_heading('List of Contributions');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/list_of_contributions.php');

// Parameters needed
$userid =  required_param('userid', PARAM_INT);
$courseid =  required_param('courseid', PARAM_INT);
$grading = optional_param('grading', array(), PARAM_BOOL);

// We define the context
$ctx = context_course::instance($courseid);

// Header
echo $OUTPUT->header();
echo $OUTPUT->heading('List of Contributions');
echo("<table><tr><td>");
echo ("<img src=" . get_user_image($userid, $courseid) . ">" );
echo ("</td>");
echo ("<td><h2>" . get_user_name($userid) . "</h2></td>");
echo("</tr></table>");

// We check if there is more than one grading instance of the plugin
$nbr_grading_instances_query = "Select count(*) from {randomstrayquotes} where course = $courseid and grade <> 0";
$nbr_grading_instances = $DB->get_records_sql($nbr_grading_instances_query);
$values = array_keys($nbr_grading_instances);

// We check if there is more than one grading instance of the plugin. If it,s the case we disable the grading button
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

// We retrive the contributions
$contributionsquery = "Select * from {randomstrayquotes_quotes} where user_id = $userid and course_id = $courseid and visible = 1";
$contributions_arr = $DB->get_records_sql($contributionsquery);

require_once($CFG->libdir. '/gradelib.php');
$grade_item = grade_item::fetch(array('itemtype'=>'mod', 'itemmodule'=>'randomstrayquotes', 'iteminstance'=>1));
# var_dump($grade_item);
$array_keys = array();
$arraykey[1] = $userid;
$grading_info = grade_get_grades($courseid, 'mod', 'randomstrayquotes', 1, $array_keys);
var_dump($grading_info);
// We display the grading form
//Those who cannot attribute grades cannot see this form
if (has_capability('mod/randomstrayquotes:grade', $ctx, $USER->id, $doanything = true, $errormessage='nopermissions')){
  // Instaciation of the form
  $customdata['courseid'] = $courseid;
  $customdata['userid'] = $userid;
  //$customdata['ctx'] = $ctx;
  $form = new \mod_randomstrayquotes\forms\gradeQuote(null, $customdata);
  echo $form->display();
}

// We display the user's contributions
if ($contributions_arr) {
    $renderer = $PAGE->get_renderer('mod_randomstrayquotes');
    $content = $renderer->display_list_of_contributions($contributions_arr, $courseid, $userid, $cm, $disable_grading_button);
} else {
    $content = 'Aucunes contributions pour le moment';
}

echo ($content);
echo $OUTPUT->footer();
