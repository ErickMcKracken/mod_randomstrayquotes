<?php


defined('MOODLE_INTERNAL') || die;
class block_strayquotes_add_quote extends   block_edit_form {
    protected function specific_definition($mform) {
        global $PAGE, $DB;
        
       // Field for editing Side Bar block title
        $mform->addElement('header', 'configheader', 'strayquotes config');
        
       // Add the quote
        $mform->addElement('textarea', 'quote', get_string("quote", "survey"), 'wrap="virtual" rows="20" cols="40"');
        $mform->setDefault('config_quote', get_string('quote', 'block_strayquotes'));
        $mform->setType('config_quote', PARAM_TEXT);
        
        // Select the author
        $query = "Select distinct author from {block_strayquotes}";
        $quote_arr = $DB->get_records_sql($query);
        $authors = ['Choose author'];
        foreach ($quote_arr as $author) {
            $key = str_replace(" ","_", $author->author);
            $authors[$key] = $author->author;
        }

        $mform->addElement('select', 'config_author', 'config author', $authors);
        $mform->setType('config_author', PARAM_TEXT);
        
        // Select the category
        $catquery = "Select distinct author from {block_strayquotes}";
        $category_arr = $DB->get_records_sql($catquery);

        $category = ['Choose category'];
        foreach ($category_arr as $category) {
            $catkey = str_replace(" ","_", $category->category);
            $category[$catkey] = $category->category;
        }

        $mform->addElement('select', 'config_category', 'config category', $category);
        $mform->setType('config_category', PARAM_TEXT); 
        
        // Indicate the source
        $mform->addElement('text', 'config_source', get_string('source', 'block_strayquotes'));
        $mform->setDefault('config_source', get_string('source', 'block_strayquotes'));
        $mform->setType('config_source', PARAM_NOTAGS);
        
        // Is the quote visible or not?
        $yesnooptions = array('yes'=>get_string('yes'), 'no'=>get_string('no'));
        $mform->addElement('select', 'config_visible', get_string('visible', $this->block->block_strayquotes), $yesnooptions);
        if (empty($this->block->config->ajax_enabled) || $this->block->config->ajax_enabled=='yes') {
            $mform->getElement('config_ajax_enabled')->setSelected('yes');
        } else {
            $mform->getElement('config_eajax_enabled')->setSelected('no');
        }
   
     }
}