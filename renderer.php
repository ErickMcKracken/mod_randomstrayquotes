<?php
defined('MOODLE_INTERNAL') || die;
class mod_randomstrayquotes_renderer extends plugin_renderer_base {
    
    function display_categories($arr_categories){
    
         $content = html_writer::start_tag('table', array('class' => 'mod_random_strayquotes_quote'));
         foreach ($arr_categories as $category){
                $content = html_writer::start_tag('tr', array('class' => 'category_list'));
                $content .= html_writer::start_tag('td', array('class' => 'category_list'));
                $content .= html_writer::start_span('block_strayquotes_quote') .  $category->id . html_writer::end_span();
                $content .= html_writer::end_tag('td');
                $content .= html_writer::start_tag('td', array('class' => 'category_list'));
                $content .= html_writer::start_span('block_strayquotes_quote') .  $category->category_name . html_writer::end_span();
                $content .= html_writer::end_tag('td');
                $content .= html_writer::end_tag('tr');
         }
         $content .= html_writer::end_tag('table');
    }
    
    function display_authors($arr_authors){
    
         $content = html_writer::start_tag('table', array('class' => 'mod_random_strayquotes_quote'));
         foreach ($arr_authors as $author){
                $content = html_writer::start_tag('tr', array('class' => 'author_list'));
                $content .= html_writer::start_tag('td', array('class' => 'author_list'));
                $content .= html_writer::start_span('author-display') .  $author->id . html_writer::end_span();
                $content .= html_writer::end_tag('td');
                $content .= html_writer::start_tag('td', array('class' => 'category_list'));
                $content .= html_writer::start_span('author-display') .  $category->category_name . html_writer::end_span();
                $content .= html_writer::end_tag('td');
                $content .= html_writer::end_tag('tr');
         }
         $content .= html_writer::end_tag('table');
    }
}
