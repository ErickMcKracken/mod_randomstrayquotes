<?php

//require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once('../../config.php');
//require_once ($CFG->dirroot.'/lib/formslib.php');
//require_once($CFG->dirroot . '/mod/randomstrayquotes/locallib.php');

global $CFG, $DB, $PAGE, $COURSE;

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Add Authors');
$PAGE->set_heading('Form Add Authors');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/add_authors.php');
 
$form = new \mod_randomstrayquotes\forms\addAuthors();

echo $OUTPUT->header();
echo $OUTPUT->heading('Add an authors');

echo $form->display();

//Authors Listing
        
        $catquery = "Select * from {randomstrayquotes_authors}";
        $cat_arr = $DB->get_records_sql($catquery);

            if ($cat_arr ) {

                    $renderer = $PAGE->get_renderer('mod_randomstrayquotes');
                    $content = $renderer->display_authors($cat_arr);
                } else {
                    $content = 'Aucuns auteurs n\'ont été saisies pour le moment';
                }
                
                echo ($content);


echo $OUTPUT->footer();

?>
