<?php


//require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once('../../config.php');
//require_once ($CFG->dirroot.'/lib/formslib.php');
//require_once($CFG->dirroot . '/mod/randomstrayquotes/locallib.php');

global $CFG, $DB, $PAGE, $COURSE, $USER;

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Add Quotes');
$PAGE->set_heading('Form Add Quote');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/add_quotes.php');


//$form = new \mod_randomstrayquotes\forms\addQuotes();
// We catch the course id in the parameter in the adress bar and pass it to the form
//$course_id = required_param('courseid', PARAM_INT);
$course_id = 21155;
//var_dump($course_id); die;

// We define the context and pass it to the form
$ctx = context_course::instance($course_id);
$customdata['courseid'] = $course_id;
$customdata['ctx'] = $ctx;

// Instaciation of the form
$form = new \mod_randomstrayquotes\forms\addQuotes(null, $customdata);

if ($form->is_cancelled()) {
    redirect(new moodle_url('/course/view.php', ['id' => $course_id, 'userid' => $USER->id]));
}

echo $OUTPUT->header();
echo $OUTPUT->heading('Add a Quote');


if ($data = $form->get_data()) {

    $quote = new stdClass();
    $quote->author_id = $data->author_id;
    $quote->quote = $data->quote;
    $quote->category = $data->category;
    $quote->source = $data->source;
    $quote->time_added = $data->time_added;
    $quote->user_id = $USER->id;
    $quote->visible = $data->$visible;

    // We insert the quote
    $DB->insert_record('randomstrayquotes_quotes', $quote, $returnid = true, $bulk = false);
    $data = NULL;
    $form = NULL;
  //  redirect(new moodle_url('/mod/randomstrayquotes/add_quote.php', ['courseid' => $course_id, 'userid' => $USER->id]));
}
echo $form->display();
echo $OUTPUT->footer();
