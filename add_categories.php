<?php

//require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once('../../config.php');
//require_once ($CFG->dirroot.'/lib/formslib.php');
//require_once($CFG->dirroot . '/mod/randomstrayquotes/locallib.php');

global $CFG, $DB, $PAGE, $COURSE;

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Add Categories');
$PAGE->set_heading('Form Add Categories');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/add_categories.php');


$form = new \mod_randomstrayquotes\forms\addCategories();

echo $OUTPUT->header();
echo $OUTPUT->heading('Add a Category');

if ($data = $form->get_data()) {
    $category = new stdClass();
    $category->category_name = $data->category_name;
    $category->user_id = $data->user_id;
    $category->course_id = $data->course_id;

    // We insert the category
    $DB->insert_record('randomstrayquotes_categories', $category, $returnid = true, $bulk = false);
    $data = NULL;
  // $form = NULL;
    //redirect(new moodle_url('/mod/randomstrayquotes/add_categories.php');
}
    echo $form->display();
//Quotes Listing

        $catquery = "Select * from {randomstrayquotes_categories}";
        $cat_arr = $DB->get_records_sql($catquery);

            if ($cat_arr ) {

                    $renderer = $PAGE->get_renderer('mod_randomstrayquotes');
                    $content = $renderer->display_categories($cat_arr,$DB);
                } else {
                    $content = 'Aucunes catégories n\'ont été saisies pour le moment';
                }

                echo ($content);


echo $OUTPUT->footer();
