<?php

require_once('../../config.php');
global $CFG, $DB, $PAGE, $COURSE;

//class mod_newmodule_mod_form extends moodleform_mod {

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Form Add Author');
$PAGE->set_heading('Form Add Author');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/add_author.php');
echo $OUTPUT->header();
//$PAGE->requires->js(new moodle_url('/blocks/strayquotes/js/dyn.js'));
defined('MOODLE_INTERNAL') || die();

// Texbox to add an author
$attributes = array('size' => '50', 'maxlength' => '100');
$mform->addElement('text', 'author_name', get_string('author_name'), $attributes);
$mform->addRule('author_name', null, 'required', null, 'client');
$mform->setType('name', PARAM_TEXT);

//Hidden textbox containing the courseid
$courseid = $COURSE->id;
$mform->addElement('hidden', 'course_id', get_string('course_id'));
$mform->setDefault('course_id', $courseid);    
$mform->setType('course_id', PARAM_TEXT);

//Upload a picture of the author
$mform->addElement('filepicker', 'userfile', get_string('file'), null, array('maxbytes' => $maxbytes, 'accepted_types' => '*'));
$content = $mform->get_file_content('userfile');
$name = $mform->get_new_filename('userfile');
$success = $mform->save_file('userfile', $fullpath, $override);
$storedfile = $mform->save_stored_file('userfile');


// Put an array of buttons on the form
$buttonarray=array();
$buttonarray[] =& $mform->createElement('submit', 'submitbutton', get_string('savechanges'));
$buttonarray[] =& $mform->createElement('submit', 'cancel', get_string('cancel'));
$mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);

// Validate the form     A REVOIR COMPLÈTEMENT
$errors = validate_form($mform);
if ($error == 1){
     $base_url = new moodle_url('/mod/mod_form.php', array('id' => $courseid));
     redirect($base_url);
} elseif ($error == 2){ 
        $DB->insert_record('mod_randomstrayquotes_authors', $datafromform);                 
} elseif ($error == 3){  
     $base_url = new moodle_url('/mod/add_authors.php', array('id' => $courseid));
     redirect($base_url);
}

//Authors Listing
$selectArray = array();
$authquery = "Select distinct * from {block_strayquotes_authors}";
$category_arr = $DB->get_records_sql($authquery);

foreach($authors_arr as $author) {
    $key = $author->id;
    $value = $author->author_name;
    $selectArray[$key] = $value;
 if ($authors_arr ) {
            $renderer = $this->page->get_renderer('mod_randomstrayquotes');
            $content = $renderer->display_authors($authors_arr);
        } else {
            $content = 'Aucuns auteurs n\'ont été saisis pour le moment';
        }
}

//  A REVOIR COMPLÈTEMENT
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