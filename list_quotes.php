<?php
global $CFG, $PAGE;
require_once('../../config.php');
defined('MOODLE_INTERNAL') || die();
require_login();

// Page settings
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('List Quotes');
$PAGE->set_heading('List Quotes');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/list_quotes.php');

// We catch the parameters
$userid =  required_param('userid', PARAM_INT);
$course_id = required_param('courseid', PARAM_INT);

// We define the context
$ctx = context_course::instance($course_id);

// Redirections for the forms buttons
if (isset($_REQUEST['Addauthors'])) {
    redirect(new moodle_url('/mod/randomstrayquotes/add_authors.php', ['courseid' => $course_id,  'userid' => $USER->id ]));
}

if (isset($_REQUEST['Addcategories'])) {
    redirect(new moodle_url('/mod/randomstrayquotes/add_authors.php', ['courseid' => $course_id,  'userid' => $USER->id ]));
}

if (isset($_REQUEST['Addquotes'])) {
    redirect(new moodle_url('/mod/randomstrayquotes/add_quotes.php', ['courseid' => $course_id,  'userid' => $USER->id ]));
}

echo $OUTPUT->header();

//We retrieve the quotes submitted
$quotesquery = "Select * from {randomstrayquotes_quotes} where course_id = $course_id and visible = 1";
$quotes_arr = $DB->get_records_sql($quotesquery);

// We display the quotes as a list
if ($quotes_arr) {
    $renderer = $PAGE->get_renderer('mod_randomstrayquotes');
    $content = $renderer->display_list_of_quotes($quotes_arr, $userid, $course_id);
} else {
    $content = 'Aucunes citations n\'ont été saisies pour le moment';
}

echo ($content);
echo $OUTPUT->footer();
