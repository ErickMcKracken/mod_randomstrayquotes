<?php
defined('MOODLE_INTERNAL') || die;
class mod_randomstrayquotes_renderer extends plugin_renderer_base {
    
    function display_categories($arr_categories){
    

          $content = html_writer::start_tag('table', array('class' => 'table table-striped'));
         foreach ($arr_categories as $category){
                $catid = $category->id;
                $courseid = $category->course_id;
                $userid = $category->user_id;
                $content .= html_writer::start_tag('tr', array('class' => 'category_list'));
                $content .= html_writer::start_tag('td', array('class' => 'category_list'));
                $content .= html_writer::start_span('mod_randomstrayquotes_category') .  $category->id . html_writer::end_span();
                $content .= html_writer::end_tag('td');
                $content .= html_writer::start_tag('td', array('class' => 'category_list'));
                $content .= html_writer::start_span('mod_randomstrayquotes_category') .  $category->category_name . html_writer::end_span();
                $content .= html_writer::end_tag('td');
                 $content .= html_writer::start_tag('td', array('class' => 'category_list'));
                $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/edit_categories.php', array('catid' => $catid, 'courseid' => $courseid, 'userid' => $userid)), get_string('Edit', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
                $content .= html_writer::end_tag('td');
                $content .= html_writer::end_tag('tr');
         }
         $content .= html_writer::end_tag('table');
         return $content;
    }
    
    function display_authors($arr_authors){
    
         $content = html_writer::start_tag('table', array('class' => 'table table-striped'));
         foreach ($arr_authors as $author){
                $authorpix =  $author->author_picture;
                $courseid = $author->course_id;
                $userid = $author->user_id;
                $content .= html_writer::start_tag('tr', array('class' => 'author_list'));
                $content .= html_writer::start_tag('td', array('class' => 'author_list'));
                $content .= html_writer::start_span('author-display') .  $author->id . html_writer::end_span();
                $content .= html_writer::end_tag('td');
                $content .= html_writer::start_tag('td', array('class' => 'author_list'));
                $content .= html_writer::empty_tag('img', array('src'=> $authorpix, 'alt'=>'', 'class'=>'headingimage'));
                $content .= html_writer::end_tag('td');
                $content .= html_writer::start_tag('td', array('class' => 'author_list'));
                $content .= html_writer::start_span('author-display') .  $author->author_name . html_writer::end_span();
                $content .= html_writer::end_tag('td');
                $content .= html_writer::start_tag('td', array('class' => 'author_list'));
                $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/edit_author.php', array('authorid' => $author->id, 'courseid' => $courseid, 'userid' => $userid)), get_string('Edit', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
                $content .= html_writer::end_tag('td');
                $content .= html_writer::end_tag('tr');
         }
         $content .= html_writer::end_tag('table');
         return $content;
    }
    
    /********************************************************************/
    /*   Display alerts                                                  */
    /********************************************************************/

     function display_alerts($errormessage) {
                $content = html_writer::start_tag('div', array('class' => 'alert alert-danger', 'role'=> 'alert'));
                $content .= $errormessage;
                $content .= html_writer::end_tag('div');
    }

    /********************************************************************/
    /*   Retrieve the author of the associated quote and his picture    */
    /********************************************************************/

     function get_author($DB, $quoteid) {
        $author = $DB->get_record('randomstrayquotes_authors', array('id' => $quoteid), '*', MUST_EXIST);
        return $author;
    }
    
    /********************************************************************/
    /*   Retrieve the author of the associated quote and his picture    */
    /********************************************************************/

     function get_category($DB, $categoryid) {
        $category = $DB->get_record('randomstrayquotes_categories', array('id' => $categoryid), '*', MUST_EXIST);
        return $category;
    }

    function display_quotes($arr_quotes, $DB){
        global $COURSE, $USER;
         $content = html_writer::start_tag('table', array('class' => 'table table-striped'));
         foreach ($arr_quotes as $quote){
                $author = $this->get_author($DB, $quote->author_id);
                $category = $this->get_category($DB, $quote->category);
                $quoteid =  $quote->id ;
                $content .= html_writer::start_tag('tr', array('class' => 'quotes_list'));
                $content .= html_writer::start_tag('td', array('class' => 'quotes_list'));
                $content .= html_writer::start_span('quote-display') .  $quote->id . html_writer::end_span();
                $content .= html_writer::end_tag('td');
                $content .= html_writer::start_tag('td', array('class' => 'quote_list'));
                $content .= html_writer::start_span('quote-display') .  $quote->quote . html_writer::end_span();
                $content .= html_writer::end_tag('td');
                $content .= html_writer::start_tag('td', array('class' => 'quote_list'));
                $content .= html_writer::start_span('quote-display') . $author->author_name   . html_writer::end_span();
                $content .= html_writer::end_tag('td');
                $content .= html_writer::start_tag('td', array('class' => 'quote_list'));
                $content .= html_writer::start_span('quote-display') . $category->category_name   . html_writer::end_span();
                $content .= html_writer::end_tag('td');
                $content .= html_writer::start_tag('td', array('class' => 'quote_list'));
                $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/edit_/quotes.php', array('quoteid' => $quoteid, 'courseid' => $COURSE->id, 'userid' => $USER->id)), get_string('Edit', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
                $content .= html_writer::end_tag('td');
                $content .= html_writer::end_tag('tr');
               
         }
         $content .= html_writer::end_tag('table');
         return $content;
    } 
    
}
