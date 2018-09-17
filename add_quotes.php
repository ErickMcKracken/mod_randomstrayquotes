<?php
require_once('../../config.php');
global $CFG, $DB, $PAGE, $COURSE, $USER;
defined('MOODLE_INTERNAL') || die();
require_login();
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Add Quotes');
$PAGE->set_heading('Form Add Quote');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/add_quotes.php');

// We catch the course id in the parameter in the adress bar and pass it to the form
if (isset($_GET['courseid'])){
  $course_id = required_param('courseid', PARAM_INT);
}else{
  $course_id = $_POST['course_id'];
}

if (isset($_GET['userid'])){
  $user_id = required_param('userid', PARAM_INT);
}else{
  $user_id = $_POST['user_id'];
}

// We define the context and pass it to the form
$ctx = context_course::instance($course_id);
$customdata['userid'] = $user_id;
$customdata['courseid'] = $course_id;
$customdata['ctx'] = $ctx;

// Instaciation of the form
$form = new \mod_randomstrayquotes\forms\addQuotes(null, $customdata);

if ($form->is_cancelled()) {
    redirect(new moodle_url('/mod/randomstrayquotes/list_quotes.php', ['courseid' => $course_id, 'userid' => $USER->id]));
}

if ($data = $form->get_data()) {

  try{
      $transaction = $DB->start_delegated_transaction();
      $table = 'randomstrayquotes_quotes';
      $quote = new stdClass();
      $quote->quote = $data->quote;
      $quote->author_id = $data->author;
      $quote->quote = $data->quote;
      $quote->category_id = $data->category;
      $quote->source = $data->source;
      $quote->time_added = $data->time_added;
      $quote->user_id = $data->user_id;
      $quote->course_id = $data->course_id;
      $quote->visible = $_POST['visible'];//$data->$visible;

      // We insert the quote
      $DB->insert_record('randomstrayquotes_quotes', $quote, $returnid = true, $bulk = false);
      $data = NULL;
      $url= new moodle_url('/mod/randomstrayquotes/add_quotes.php', array('courseid' => $course_id, 'userid' => $user_id));
      $transaction->allow_commit();
      }
       catch (\Exception $e) {
             $transaction->rollback($e);
             $url= new moodle_url('/mod/randomstrayquotes/add_quotes.php', array('id' => $course_id, 'userid' => $user_id, 'status' =>'error'));
             redirect($url, 'Some error have occured', 3);
      }
      redirect($url, 'Transaction successful', 3);

/*
  echo('<pre>');
  var_dump($data); die;
echo('<pre>');
*/

}
echo $OUTPUT->header();
echo $OUTPUT->heading('Add a Quote');
echo $form->display();
echo $OUTPUT->footer();
