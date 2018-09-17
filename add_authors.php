<?php

require_once('../../config.php');
defined('MOODLE_INTERNAL') || die();
require_login();

global $CFG, $DB, $PAGE, $COURSE, $CONTEXT, $USER;

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Add Authors');
$PAGE->set_heading('Form Add Authors');
$PAGE->set_url($CFG->wwwroot . '/mod/randomstrayquotes/add_authors.php');

// We catch the course id in the parameter in the adress bar and pass it to the form
$course_id = required_param('courseid', PARAM_INT);

// We define the context and pass it to the form
$ctx = context_course::instance($course_id);
$customdata['courseid'] = $course_id;
$customdata['ctx'] = $ctx;

// Instaciation of the form
$form = new \mod_randomstrayquotes\forms\addAuthors(null, $customdata);

if ($form->is_cancelled()) {
    redirect(new moodle_url('/mod/randomstrayquotes/list_authors.php', ['courseid' => $course_id, 'userid' => $USER->id]));
}

$maxbytes = 5000;
if ($data = $form->get_data()) {

  try{
      $transaction = $DB->start_delegated_transaction();
      // ... store or update $entry
      file_save_draft_area_files($data->userfile, $ctx->id, 'mod_randomstrayquotes', 'content', $data->userfile, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 50));

      $author = new stdClass();
      // We add the name of the author
      $author->author_name = $data->author_name;
      // We add the author's associated picture
      $author->author_picture = $data->userfile;
      $author->course_id = $data->courseid;
      // We add the time of the insert
      $author->time_added = $data->time_added;
      // We add the userid
      $author->user_id = $USER->id;
      // We update the author's informations with the new picture
      $DB->insert_record('randomstrayquotes_authors', $author, $returnid = true, $bulk = false);
      $data = NULL;
      $form = NULL;
      $url= new moodle_url('/mod/randomstrayquotes/add_authors.php', ['courseid' => $course_id, 'userid' => $USER->id]);

      $transaction->allow_commit();
      }
       catch (\Exception $e) {
             $transaction->rollback($e);
             $url= new moodle_url('/mod/randomstrayquotes/add_authors.php', array('courseid' => $course_id, 'userid' => $USER->id, 'status' =>'error'));
             redirect($url, 'Some error have occured', 3);
      }
      redirect($url, 'Transaction successful', 3);

}
echo $OUTPUT->header();
echo $OUTPUT->heading('Add an author');
echo $form->display();

$catquery = "Select * from {randomstrayquotes_authors} where course_id = $course_id";
$cat_arr = $DB->get_records_sql($catquery);

if ($cat_arr) {
    $renderer = $PAGE->get_renderer('mod_randomstrayquotes');
    $content = $renderer->display_authors($cat_arr);
} else {
    $content = 'Aucuns auteurs n\'ont été saisies pour le moment';
}
echo ($content);

echo $OUTPUT->footer();
