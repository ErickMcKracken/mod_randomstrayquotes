<?php
require_once('../../config.php');
global $CFG, $DB, $PAGE, $COURSE;
defined('MOODLE_INTERNAL') || die();
require_login();
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Add Categories');
$PAGE->set_heading('Form Add Categories');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/add_categories.php');

if (isset($_GET['courseid'])){
  $courseid = required_param('courseid', PARAM_INT);
}else{
  $courseid = $_POST['course_id'];
}
$ctx = context_course::instance($courseid);
// We pass custom data in parameter
$customdata['ctx'] = $ctx;
// We catch the course id in the parameter in the adress bar
$customdata['courseid'] = $courseid;

$form = new \mod_randomstrayquotes\forms\addCategories(null, $customdata);

if ($form->is_cancelled()) {
    redirect(new moodle_url('/mod/randomstrayquotes/list_categories.php', ['courseid' => $courseid,  'userid' => $USER->id ]));
}

if ($data = $form->get_data()) {
  try{
      $transaction = $DB->start_delegated_transaction();
      $category = new stdClass();
      $category->category_name = $data->category_name;
      $category->user_id = $data->user_id;
      $category->course_id = $data->course_id;
      $category->time_added = $data->time_added;
      // We insert the category
      $DB->insert_record('randomstrayquotes_categories', $category, $returnid = true, $bulk = false);
      $data = NULL;
      $url= new moodle_url('/mod/randomstrayquotes/add_categories.php', ['courseid' => $courseid, 'userid' => $USER->id]);
      $transaction->allow_commit();
      }
       catch (\Exception $e) {
             $transaction->rollback($e);
             $url= new moodle_url('/mod/randomstrayquotes/add_categories.php', array('courseid' => $courseid, 'status' =>'error', 'userid' => $USER->id));
             redirect($url, 'Some error have occured', 3);
      }
      redirect($url, 'Transaction successful', 3);
}
echo $OUTPUT->header();
echo $OUTPUT->heading('Add a Category');
echo $form->display();

//Quotes Listing
        $catquery = "Select * from {randomstrayquotes_categories}  where course_id = $courseid";
        $cat_arr = $DB->get_records_sql($catquery);
            if ($cat_arr ) {

                    $renderer = $PAGE->get_renderer('mod_randomstrayquotes');
                    $content = $renderer->display_categories($cat_arr,$DB);
                } else {
                    $content = 'Aucunes catégories n\'ont été saisies pour le moment';
                }

                echo ($content);

echo $OUTPUT->footer();
