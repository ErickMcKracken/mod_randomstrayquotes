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
    
    function get_image($authorid, $courseid){
        global $DB;
        $courseid = 21155;
        // We define the context
        $ctx = context_course::instance($courseid);
        // We setup the file storage area
        $imageid = $DB->get_record('randomstrayquotes_authors', ['id' => $authorid], 'author_picture');
        $fs = get_file_storage();
        // We obtain the filePathHash for the file storage area
        $imagePathHash = $fs->get_area_files($ctx->id, 'mod_randomstrayquotes', 'content', $imageid->author_picture, "itemid, filepath, filename", false);
      //   var_dump($imagePathHash); die();
        // We obtain the id of the picture file already present for the author using the imagePathHash
        $files = array_values($imagePathHash);
       // var_dump($files ); Die();
        // We store the context and the id of the file in an array of parameters that we will pass at the form instanciation
        $file = $files[0]; 
       // echo $file;
       // var_dump($file); die();
        if($file){
        $filename = $file->get_filename();
        $url = moodle_url::make_pluginfile_url($file->get_contextid(), 'mod_randomstrayquotes','content', $file->get_itemid(),'/' ,$filename);
        }else{
            echo("shit");
        }
       //  var_dump($filename); die();
        //$url = moodle_url::make_file_url('/pluginfile.php', $file->get_contextid()."/mod_randomstrayquotes/content/".$file->get_itemid()."/".$filename);

        return $url;
    }

    function display_authors($arr_authors){
       // $arr_authors = [$arr_authors[1]];
         $content = html_writer::start_tag('table', array('class' => 'table table-striped'));
         foreach ($arr_authors as $author){      
                //$authorpix =  $author->author_picture;
                $courseid = 21155 ; //$author->course_id;
                $authorpix =  $this->get_image($author->id, $courseid);
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
