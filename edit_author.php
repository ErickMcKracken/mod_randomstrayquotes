<?php
//require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once('../../config.php');
//require_once ($CFG->dirroot.'/lib/formslib.php');
//require_once($CFG->dirroot . '/mod/randomstrayquotes/locallib.php');

global $CFG, $DB, $PAGE, $COURSE, $USER;

// Configuration of the page
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Edit Authors');
$PAGE->set_heading('Form Edit Authors');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/edit_author.php');

// We catch the course id in the parameter in the adress bar
$course_id = required_param('courseid', PARAM_INT);
// We catch the authorid and put in an array the we will pass to the form at instanciation
$customdata['authorid'] = required_param('authorid', PARAM_INT);

// We define the context
$ctx = context_course::instance($course_id);

// We recuperate the picture already associated to the author
$imageid = $DB->get_record('randomstrayquotes_authors', ['id' => $customdata['authorid']], 'author_picture');

// We setup the file storage area
$fs = get_file_storage();
// We obtain the filePathHash for the file storage area
$imagePathHash = $fs->get_area_files($ctx->id, 'mod_randomstrayquotes', 'content', $imageid->author_picture, "itemid, filepath, filename", false);
// We obtain the id of the picture file already present for the author using the imagePathHash
$files = array_values($imagePathHash);
// We store the context and the id of the file in an array of parameters that we will pass at the form instanciation
$customdata['photofile'] = $files[0];
$customdata['ctx'] = $ctx;
// Instanciation of the form
$form = new \mod_randomstrayquotes\forms\editAuthor(null, $customdata);
// Define the maximum size of the file.  TODO: valeur répeptée à mettre dans une seule place
$maxbytes = '50000';
// if the form is canceled we retur to the addAuthor form
if ($form->is_cancelled()) {
    redirect(new moodle_url('/mod/randomstrayquotes/add_authors.php', ['courseid' => $course_id,  'userid' => $USER->id ]));
}

if (isset($_REQUEST['delete'])) {
  try{
      $transaction = $DB->start_delegated_transaction();
      $table = 'randomstrayquotes_authors';
      $DB->delete_records($table, array('id' => $authorid));
      $url= new moodle_url('/mod/randomstrayquotes/add_authors.php', array('id' => $courseid));
      $transaction->allow_commit();
      }
       catch (\Exception $e) {
             $transaction->rollback($e);
             $url= new moodle_url('/mod/randomstrayquotes/edit_author.php', array('id' => $courseid, 'status' =>'error'));
             redirect($url, 'Some error have occured', 3);
      }
      redirect($url, 'Transaction successful', 3);
}

// Creation of the page
echo $OUTPUT->header();
echo $OUTPUT->heading('Edit an author\'s informations');
// If the is data in the submitted form we update the record
if ($data = $form->get_data()) {
    // ... store or update $entry
    file_save_draft_area_files($data->userfile, $ctx->id, 'mod_randomstrayquotes', 'content',
                   $data->userfile, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 50));
    // We obtain de data about the author
    $author = $DB->get_record('randomstrayquotes_authors', ['id' => $data->authorid]);
    // We change the author's associated picture with the one we just upoalded in the file manager
    $author->author_picture = $data->userfile;

    // We update the author's informations with the new picture
    $DB->update_record('randomstrayquotes_authors', $author);
}
// Display the form
echo $form->display();
//Display the footer
echo $OUTPUT->footer();
