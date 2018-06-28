<?php
//require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once('../../config.php');
//require_once ($CFG->dirroot.'/lib/formslib.php');
//require_once($CFG->dirroot . '/mod/randomstrayquotes/locallib.php');

global $CFG, $DB, $PAGE, $COURSE, $USER;

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Edit Authors');
$PAGE->set_heading('Form Edit Authors');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/edit_author.php');

$course_id = required_param('courseid', PARAM_INT);
$customdata['authorid'] = required_param('authorid', PARAM_INT);

$ctx = context_course::instance($course_id);
$imageid = $DB->get_record('randomstrayquotes_authors', ['id' => $customdata['authorid']], 'author_picture');

$fs = get_file_storage();
$imagePathHash = $fs->get_area_files($ctx->id, 'mod_randomstrayquotes', 'content', $imageid->author_picture, "itemid, filepath, filename", false);
$files = array_values($imagePathHash);

$customdata['photofile'] = $files[0]; 
$customdata['ctx'] = $ctx;

$form = new \mod_randomstrayquotes\forms\editAuthor(null, $customdata);


//TODO: valeur répeptée à mettre dans une seule place
$maxbytes = '50000';
if ($form->is_cancelled()) {
    redirect(new moodle_url('/mod/randomstrayquotes/add_authors.php', ['courseid' => $course_id,  'userid' => $USER->id ]));
}

echo $OUTPUT->header();
echo $OUTPUT->heading('Edit an author\'s informations');

if ($data = $form->get_data()) {
    // ... store or update $entry
    file_save_draft_area_files($data->userfile, $ctx->id, 'mod_randomstrayquotes', 'content',
                   $data->userfile, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 50));
    
    $author = $DB->get_record('randomstrayquotes_authors', ['id' => $data->authorid]);
    $author->author_picture = $data->userfile;
    $DB->update_record('randomstrayquotes_authors', $author);
}


echo $form->display();
echo $OUTPUT->footer();