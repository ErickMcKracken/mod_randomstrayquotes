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

$category_id = 1; //required_param('catid', PARAM_INT);
$courseid = 21155; //required_param('courseid', PARAM_INT);

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

echo $OUTPUT->header();
echo $OUTPUT->heading('Edit a category');


if ($data = $form->get_data()) {
    $category = new stdClass();
    $category->id = $data->category_id;
    $category->category_name = $data->category_name;
    $category->user_id = $data->user_id;
    $category->course_id = $data->course_id;

    // We insert the category
    $DB->update_record('randomstrayquotes_categories', $category, $returnid = true, $bulk = false);
    $data = NULL;
  // $form = NULL;
    //redirect(new moodle_url('/mod/randomstrayquotes/add_categories.php');
}



echo $form->display();
echo $OUTPUT->footer();

?>
