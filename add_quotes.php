<?php
require_once('../../config.php');
global $CFG, $DB, $PAGE, $COURSE;

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Ajout de catégories');
$PAGE->set_heading('Form Add Quote');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/add_category.php');
echo $OUTPUT->header();

defined('MOODLE_INTERNAL') || die;
class block_strayquotes_add_quote extends   block_edit_form {
    protected function add_quotes($mform) {
        global $PAGE, $DB;
       // Field for editing the module title
        $mform->addElement('header', 'configheader', 'strayquotes config');
        
        // Combobox with the authors
        $queryauth = "Select distinct * from {block_strayquotes_authors}";
        $authors_arr = $DB->get_records_sql($queryauth);
        $authors = ['Choose author'];
        foreach ($authors_arr as $author) {
            $key = str_replace(" ","_", $author->author);
            $authors[$key] = $author->author;
        }

        $mform->addElement('select', 'id', get_string('author_name'), $authors);
        $mform->setType('id', PARAM_INT);
        
         // Combobox with categories
        $selectArray = array();
        $selectArray[0] = "Toutes les cats...";
        $catquery = "Select * from {block_strayquotes_categories}";
        $category_arr = $DB->get_records_sql($catquery);
        
        $mform->addElement('select', 'id', get_string('category_name'), $selectArray);
        $mform->setType('id', PARAM_INT);
        
        foreach($category_arr as $category) {
            $key = $category->id;
            $value = $category->category_name;
            $selectArray[$key] = $value;
        }
        
         // Add the quote
        $mform->addElement('textarea', 'quote', get_string("quote", "survey"), 'wrap="virtual" rows="20" cols="40"');
        $mform->addRule('quote', null, 'required', null, 'client');
        $mform->setType('quote', PARAM_TEXT);
        
        // Indicate the source
        $mform->addElement('text', 'config_source', get_string('source', 'block_strayquotes'));
        $mform->setType('source', PARAM_TEXT);
        
        // Is the quote visible or not?
        $yesnooptions = array('yes'=>get_string('yes'), 'no'=>get_string('no'));
        $mform->addElement('select', 'visible', get_string('visible'), $yesnooptions);
        //    A REVOIR
        if (empty($this->block->config->ajax_enabled) || $this->block->config->ajax_enabled=='yes') {
            $mform->getElement('config_ajax_enabled')->setSelected('yes');
        } else {
            $mform->getElement('config_ajax_enabled')->setSelected('no');
        }
        
        // Indicate the date of the add
        $date = new DateTime("now", core_date::get_user_timezone_object());
        $date->setTime(0, 0, 0);
        $mform->addElement('hidden', 'time_added',  get_string('source', 'time_added'));
        $mform->setDefault('course_id', $date); 
        $mform->setType('time_added', PARAM_TEXT);

     }
}