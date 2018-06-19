<?php
require_once('../../config.php');
global $CFG, $DB, $PAGE, $COURSE;

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Form Add Category');
$PAGE->set_heading('Form Add Category');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/add_category.php');
echo $OUTPUT->header();
//$PAGE->requires->js(new moodle_url('/blocks/strayquotes/js/dyn.js'));
defined('MOODLE_INTERNAL') || die();

// Texbox to add a category
$courseid = $COURSE->id;
$attributes = array('size' => '50', 'maxlength' => '100');
$mform->addElement('text', 'category_name', get_string('category_name'), $attributes);
$mform->addRule('name', null, 'required', null, 'client');
$mform->setType('name', PARAM_TEXT);

// textbox hidden containing the courseid
$mform->addElement('hidden', 'course_id', get_string('course_id'));
$mform->setDefault('course_id', $courseid);    
$mform->setType('course_id', PARAM_INT);

// Display an array of buttons
$buttonarray=array();
$buttonarray[] =& $mform->createElement('submit', 'submitbutton', get_string('savechanges'));
$buttonarray[] =& $mform->createElement('submit', 'cancel', get_string('cancel'));
$mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);

// Validation         A REVOIR COMPLÈTEMENT
$errors = validate_form($mform);
if ($error == 1){
     $base_url = new moodle_url('/mod/mod_form.php', array('id' => $courseid));
     redirect($base_url);
} elseif ($error == 2){ 
        $DB->insert_record('mod_randomstrayquotes_categories', $datafromform);                 
} elseif ($error == 3){  
     $base_url = new moodle_url('/mod/add_category.php', array('id' => $courseid));
     redirect($base_url);
}
//Category Listing
$selectArray = array();
$catquery = "Select distinct * from {block_strayquotes_categories}";
$category_arr = $DB->get_records_sql($catquery);

foreach($category_arr as $category) {
    $key = $category->id;
    $value = $category->category_name;
    $selectArray[$key] = $value;
 if ($category_arr ) {
            $renderer = $this->page->get_renderer('mod_randomstrayquotes');
            $content = $renderer->display_categories($category_arr);
        } else {
            $content = 'Aucunes catégories n\'ont été saisie pour le moment';
        }
}
// Validate the form     A REVOIR COMPLÈTEMENT
 function validate_form($mform){  
           
                 $datafromform = $mform->get_data();
		//Form processing and displaying is done here
		if ($mform->is_cancelled()) {
			//Handle form cancel operation, if cancel button is present on form
                    $error = 1;  
		} else if ($datafromform) {
                    if (empty($datafromform['name'].lenght > 30)){
                        $error=2; 
                }		
		return $error;
       }            
}
?>
</body>
</html>