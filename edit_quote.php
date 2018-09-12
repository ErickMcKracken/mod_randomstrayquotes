<?php

require_once('../../config.php');

global $CFG, $DB, $PAGE, $COURSE;

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Edit Quote');
$PAGE->set_heading('Form Edit Quote');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/edit_quote.php');

//We recuperate the parameters in the adress bar or the form submission according to the case
if (isset($_GET['quoteid'])){
   $quoteid = required_param('quoteid', PARAM_INT);
 }else{
   $quoteid = $_POST['quote_id'];
}

if (isset($_GET['courseid'])){
  $courseid = required_param('courseid', PARAM_INT);
}else{
  $courseid = $_POST['course_id'];
}

// We define the context
$ctx = context_course::instance($courseid);
// We pass custom data in parameter
$customdata['ctx'] = $ctx;
// We catch the quote id in the adress bar
$customdata['quoteid'] = $quoteid ;
// We catch the course id in the parameter in the adress bar
$customdata['courseid'] = $courseid;


// Instaciation of the form
$form = new \mod_randomstrayquotes\forms\editQuote(null, $customdata);

if ($form->is_cancelled()) {
    redirect(new moodle_url('/mod/randomstrayquotes/list_quotes.php', ['courseid' => $courseid,  'userid' => $USER->id ]));
}

if ($form->is_deleted()){
  try{
      $transaction = $DB->start_delegated_transaction();
      $table = 'randomstrayquotes_quotes';
      $DB->delete_records($table, array('id' => $_POST['quote_id']));
      $url= new moodle_url('/mod/randomstrayquotes/list_quotes.php', array('courseid' => $courseid, 'userid' => $USER->id));
      $transaction->allow_commit();
      }
       catch (\Exception $e) {
             $transaction->rollback($e);
             $url= new moodle_url('/mod/randomstrayquotes/edit_quote.php', array('courseid' => $courseid, 'userid' => $USER->id, 'status' =>'error'));
             redirect($url, 'Some error have occured', 3);
      }
      redirect($url, 'Transaction successful', 3);
}

if ($data = $form->get_data()) {

  try{
      $transaction = $DB->start_delegated_transaction();
      $table = 'randomstrayquotes_quotes';
      $quote = new stdClass();
      $quote->id = $data->quote_id;
      $quote->author_id = $data->author;
      $quote->quote = $data->quote;
      $quote->category_id = $data->category;
      $quote->source = $data->source;
      $quote->time_updated = $data->time_updated;
      $quote->user_id = $USER->id;
      $quote->visible = $data->visible;

      // We update the quote
      $DB->update_record('randomstrayquotes_quotes', $quote);
      $data = NULL;
      $url= new moodle_url('/mod/randomstrayquotes/edit_quote.php', array('courseid' => $courseid, 'quoteid' => $quoteid, 'userid' => $USER->id));
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
echo $form->display();
echo $OUTPUT->footer();
