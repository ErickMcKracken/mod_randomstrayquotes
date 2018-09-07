<?php
//require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once('../../config.php');
//require_once ($CFG->dirroot.'/lib/formslib.php');
//require_once($CFG->dirroot . '/mod/randomstrayquotes/locallib.php');

global $CFG, $DB, $PAGE, $COURSE;

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Edit Categories');
$PAGE->set_heading('Form Edit Categories');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/edit_category.php');

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

// We define the context
$ctx = context_course::instance($courseid);
// We pass custom data in parameter
$customdata['ctx'] = $ctx;
$customdata['catid'] = $category_id ;
// We catch the course id in the parameter in the adress bar
$customdata['courseid'] = $courseid;

$form = new \mod_randomstrayquotes\forms\editCategory(null, $customdata);

if ($form->is_cancelled()) {
    redirect(new moodle_url('/mod/randomstrayquotes/add_categories.php', ['courseid' => $courseid,  'userid' => $USER->id ]));
}

if (isset($_POST['backtolist'])) {
    redirect(new moodle_url('/mod/randomstrayquotes/list_categories.php', ['courseid' => $courseid,  'userid' => $USER->id ]));
}

if (isset($_REQUEST['delete'])) {
  try{
      $transaction = $DB->start_delegated_transaction();
      $table = 'randomstrayquotes_categories';
      $DB->delete_records($table, array('id' => $category_id));
      $url= new moodle_url('/mod/randomstrayquotes/add_categories.php', array('id' => $courseid));
      #$url=  $CFG->wwwroot.'/course/view.php', array('id'=>$courseid);
      $transaction->allow_commit();
      }
       catch (\Exception $e) {
             $transaction->rollback($e);
             $url= new moodle_url('/mod/randomstrayquotes/edit_categories.php', array('id' => $courseid, 'status' =>'error', 'catid' => $category_id ));
             redirect($url, 'Some error have occured', 3);
      }
      redirect($url, 'Transaction successful', 3);
}

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

      $url= new moodle_url('/mod/randomstrayquotes/edit_categories.php', ['courseid' => $courseid,  'userid' => $USER->id, 'catid' => $category_id ]);
      $transaction->allow_commit();
      }
       catch (\Exception $e) {
             $transaction->rollback($e);
             $url= new moodle_url('/mod/randomstrayquotes/list_categories.php', array('id' => $courseid, 'status' =>'error'));
             redirect($url, 'Some error have occured', 3);
      }
      redirect($url, 'Transaction successful', 3);

}
echo $OUTPUT->header();
echo $OUTPUT->heading('Edit a category');
echo $form->display();
echo $OUTPUT->footer();
