<?php
global $CFG, $DB, $PAGE, $COURSE, $USER;
require_once('../../config.php');
defined('MOODLE_INTERNAL') || die();
require_login();

// Page settings
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Edit Categories');
$PAGE->set_heading('Form Edit Categories');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/edit_category.php');

// We catch the parameters
if (isset($_GET['catid'])){
   $category_id = required_param('catid', PARAM_INT);
 }else{
   $category_id = $_POST['category_id'];
}

if (isset($_GET['courseid'])){
  $courseid = required_param('courseid', PARAM_INT);
}else{
  $courseid = $_POST['course_id'];
}

if (isset($_GET['userid'])){
  $userid = required_param('userid', PARAM_INT);
}else{
  $userid = $_POST['user_id'];
}

// We define the context
$ctx = context_course::instance($courseid);
// We pass custom data in parameter
$customdata['ctx'] = $ctx;
// We catch the category id in the adress bar
$customdata['catid'] = $category_id ;
// We catch the course id in the parameter in the adress bar
$customdata['courseid'] = $courseid;

// We load the form
$form = new \mod_randomstrayquotes\forms\editCategory(null, $customdata);

// Redirection for the cancel button
if ($form->is_cancelled()) {
    redirect(new moodle_url('/mod/randomstrayquotes/list_categories.php', ['courseid' => $courseid,  'userid' => $USER->id ]));
}

// Destruction of a category
if ($form->is_deleted()){
  try{
      $transaction = $DB->start_delegated_transaction();
      $table = 'randomstrayquotes_categories';
      $DB->delete_records($table, array('id' => $category_id));
      $url= new moodle_url('/mod/randomstrayquotes/add_categories.php', array('courseid' => $courseid, 'userid' => $userid));
      $transaction->allow_commit();
      }
       catch (\Exception $e) {
             $transaction->rollback($e);
             $url= new moodle_url('/mod/randomstrayquotes/edit_categories.php', array('id' => $courseid,  'userid' => $userid, 'status' =>'error', 'catid' => $category_id ));
             redirect($url, 'Some error have occured', 3);
      }
      redirect($url, 'Transaction successful', 3);
}

// Update of the informations of a category
if ($data = $form->get_data()) {
  try{
      $transaction = $DB->start_delegated_transaction();
      $category = new stdClass();
      $category->id = $data->category_id;
      $category->category_name = $data->category_name;
      $category->user_id = $data->user_id;
      $category->course_id = $data->course_id;
      // We update the category
      $DB->update_record('randomstrayquotes_categories', $category, $returnid = true, $bulk = false);
      $data = NULL;
      $url= new moodle_url('/mod/randomstrayquotes/edit_categories.php', ['courseid' => $courseid,  'userid' => $userid, 'catid' => $category_id ]);
      $transaction->allow_commit();
      }
       catch (\Exception $e) {
             $transaction->rollback($e);
             $url= new moodle_url('/mod/randomstrayquotes/list_categories.php', array('courseid' => $courseid, 'userid' => $userid,'status' =>'error'));
             redirect($url, 'Some error have occured', 3);
      }
      redirect($url, 'Transaction successful', 3);
}
echo $OUTPUT->header();
echo $OUTPUT->heading('Edit a category');
echo $form->display();
echo $OUTPUT->footer();
