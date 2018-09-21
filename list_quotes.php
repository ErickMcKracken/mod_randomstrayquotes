<?php
require_once('../../config.php');
global $CFG, $PAGE;
defined('MOODLE_INTERNAL') || die();
require_login();
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('List Quotes');
$PAGE->set_heading('List Quotes');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/list_quotes.php');

// We catch the parameters
$userid =  required_param('userid', PARAM_INT);
$course_id = required_param('courseid', PARAM_INT);

// We define the context and pass it to the form
$ctx = context_course::instance($course_id);

$form = new \mod_randomstrayquotes\forms\navigation(null, null);

if ($form->is_cancelled()) {
    redirect(new moodle_url('/course/view.php', ['id' => $course_id]));
}

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
echo $form->display();

$quotesquery = "Select * from {randomstrayquotes_quotes} where course_id = $course_id and visible = 1";
$quotes_arr = $DB->get_records_sql($quotesquery);

if ($quotes_arr) {
    $renderer = $PAGE->get_renderer('mod_randomstrayquotes');
    $content = $renderer->display_list_of_quotes($quotes_arr, $userid, $course_id);
} else {
    $content = 'Aucunes citations n\'ont été saisies pour le moment';
}

echo ($content);
echo $OUTPUT->footer();
