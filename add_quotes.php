<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
defined('MOODLE_INTERNAL') || die;
class block_strayquotes_add_quote extends   block_edit_form {
    protected function add_quotes($mform) {
        global $PAGE, $DB;
       // Field for editing Side Bar block title
        $mform->addElement('header', 'configheader', 'strayquotes config');
        
        // Combobox with the authors
        $queryauth = "Select distinct author from {block_strayquotes_authors}";
        $authors_arr = $DB->get_records_sql($queryauth);
        $authors = ['Choose author'];
        foreach ($authors_arr as $author) {
            $key = str_replace(" ","_", $author->author);
            $authors[$key] = $author->author;
        }

        $mform->addElement('select', 'id', 'name', $authors);
        $mform->setType('id', PARAM_TEXT);
        
         // Combobox with categories
        $selectArray = array();
        $selectArray[0] = "Toutes les cats...";
        $catquery = "Select * from {block_strayquotes_categories}";
        $category_arr = $DB->get_records_sql($catquery);

        foreach($category_arr as $category) {
            $key = $category->id;
            $value = $category->category_name;
            $selectArray[$key] = $value;
        }
        
        // Indicate the source
        $mform->addElement('text', 'config_source', get_string('source', 'block_strayquotes'));
        $mform->setType('source', PARAM_TEXT);
        
        // Is the quote visible or not?
        $yesnooptions = array('yes'=>get_string('yes'), 'no'=>get_string('no'));
        $mform->addElement('select', 'config_visible', get_string('visible', $this->block->block_strayquotes), $yesnooptions);
        if (empty($this->block->config->ajax_enabled) || $this->block->config->ajax_enabled=='yes') {
            $mform->getElement('config_ajax_enabled')->setSelected('yes');
        } else {
            $mform->getElement('config_eajax_enabled')->setSelected('no');
        }
        
        // Indicate the date of the add
        $date = new DateTime("now", core_date::get_user_timezone_object());
        $date->setTime(0, 0, 0);
        $mform->addElement('hidden', 'timeadded', $date);

     }
}