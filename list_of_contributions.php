<?php
require_once('../../config.php');
require_once("$CFG->dirroot/mod/randomstrayquotes/locallib.php");

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
/*
$xx = get_coursemodule_from_id('randomstrayquotes',32);
echo('<pre>');
var_dump($xx);
echo('</pre>');


$cm         = get_coursemodule_from_id('randomstrayquotes', $id, 0, false, MUST_EXIST);
*/

$contributionsquery = "Select * from {randomstrayquotes_quotes} where user_id = $userid and visible = 1";
$contributions_arr = $DB->get_records_sql($contributionsquery);

if ($contributions_arr) {
    $renderer = $PAGE->get_renderer('mod_randomstrayquotes');
    $content = $renderer->display_list_of_contributions($contributions_arr, $courseid, $userid);
} else {
    $content = 'Aucunes contributions pour le moment';
}


echo ($content);
echo $OUTPUT->footer();
