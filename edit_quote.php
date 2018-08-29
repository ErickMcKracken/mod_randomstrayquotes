<?php
//require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once('../../config.php');
//require_once ($CFG->dirroot.'/lib/formslib.php');
//require_once($CFG->dirroot . '/mod/randomstrayquotes/locallib.php');

global $CFG, $DB, $PAGE, $COURSE;

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Edit Quote');
$PAGE->set_heading('Form Edit Quote');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/edit_quote.php');

//We recuperae the parameters in the adress bar
$courseid  = required_param('courseid', PARAM_INT);
$quoteid = required_param('quoteid', PARAM_INT);

$courseid = 21155;
$quoteid = 6;
//var_dump("$courseid"); die;

// We define the context
$ctx = context_course::instance($courseid);
// We pass custom data in parameter
$customdata['ctx'] = $ctx;
$customdata['quoteid'] = $quoteid ;
// We catch the course id in the parameter in the adress bar
$customdata['courseid'] = $courseid;
//var_dump($customdata); die;

// Instaciation of the form
$form = new \mod_randomstrayquotes\forms\editQuote(null, $customdata);

if ($form->is_cancelled()) {
    redirect(new moodle_url('/mod/randomstrayquotes/list_quotes.php', ['courseid' => $courseid,  'userid' => $USER->id ]));
}

if (isset($_REQUEST['delete'])) {
  try{
      $transaction = $DB->start_delegated_transaction();
      $table = 'randomstrayquotes_quotes';
      $DB->delete_records($table, array('id' => $quote_id));
      $url= new moodle_url('/mod/randomstrayquotes/list_quotes.php', array('id' => $courseid));
      $transaction->allow_commit();
      }
       catch (\Exception $e) {
             $transaction->rollback($e);
             $url= new moodle_url('/mod/randomstrayquotes/edit_quote.php', array('id' => $courseid, 'status' =>'error'));
             redirect($url, 'Some error have occured', 3);
      }
      redirect($url, 'Transaction successful', 3);
}

echo $OUTPUT->header();
echo $OUTPUT->heading('Edit a quote');


if ($data = $form->get_data()) {
    $quote = new stdClass();
    $quote->id = $data->quote_id;
    $quote->author_id = $data->author;
    $quote->quote = $data->quote;
    $quote->category_id = $data->category;
    $quote->source = $data->source;
    $quote->time_updated = $data->time_updated;
    $quote->user_id = $USER->id;
    $quote->visible = 1 ; //$data->$visible;

    // We insert the quote
    $DB->update_record('randomstrayquotes_quotes', $quote);
    $data = NULL;
    //$form = NULL;
    redirect(new moodle_url('/mod/randomstrayquotes/edit_quote.php', ['courseid' => $courseid, 'quoteid' => $quoteid, 'userid' => $USER->id]));
}

echo $form->display();
echo $OUTPUT->footer();
