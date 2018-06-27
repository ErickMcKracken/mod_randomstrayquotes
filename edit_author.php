<?php
//require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once('../../config.php');
//require_once ($CFG->dirroot.'/lib/formslib.php');
//require_once($CFG->dirroot . '/mod/randomstrayquotes/locallib.php');

global $CFG, $DB, $PAGE, $COURSE;

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Edit Authors');
$PAGE->set_heading('Form Edit Authors');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/edit_author.php');

$course_id = required_param('courseid', PARAM_INT);
$customdata['authorid'] = required_param('authorid', PARAM_INT);

$ctx = context_course::instance($course_id);
$imageid = $DB->get_record('randomstrayquotes_authors', ['id' => $customdata['authorid']], 'author_picture');
$imagePathHash = $DB->get_records('files', ['itemid' => $imageid->author_picture, 'component' => 'mod_randomstrayquotes', 'filearea' => 'content', 'filesize' => '> 0'], 'pathnamehash');
$file_ids = array_keys($imagePathHash);
$file = $imagePathHash[$file_ids[0]];
$fs = get_file_storage();
$fichier = $fs->get_file_by_hash($file->pathnamehash);

$customdata['photofile'] = $fichier; 
$customdata['ctx'] = $ctx;

$form = new \mod_randomstrayquotes\forms\editAuthor(null, $customdata);

echo $OUTPUT->header();
echo $OUTPUT->heading('Edit an author\'s informations');
//TODO: valeur répeptée à mettre dans une seule place
$maxbytes = '50000';
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