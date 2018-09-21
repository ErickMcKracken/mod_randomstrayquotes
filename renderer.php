<?php
defined('MOODLE_INTERNAL') || die();
require_login();
class mod_randomstrayquotes_renderer extends plugin_renderer_base {

    function display_categories($arr_categories){

         $content = html_writer::start_tag('table', array('class' => 'table table-striped'));
         $content .= html_writer::start_tag('th', array('class' => 'category_list_header_cell'));
         $content .= html_writer::start_span('author-display') .  '&nbsp;' . html_writer::end_span();
         $content .= html_writer::end_tag('th');
         $content .= html_writer::start_tag('th', array('class' => 'category_list_header_cell'));
         $content .= html_writer::start_span('author-display') .  'Category' . html_writer::end_span();
         $content .= html_writer::end_tag('th');
         $content .= html_writer::start_tag('th', array('class' => 'category_list_header_cell'));
         $content .= html_writer::start_span('author-display') .  'Time added' . html_writer::end_span();
         $content .= html_writer::end_tag('th');
         $content .= html_writer::start_tag('th', array('class' => 'category_list_header_cell'));
         $content .= html_writer::start_span('author-display') .  'Added by' . html_writer::end_span();
         $content .= html_writer::end_tag('th');
         $content .= html_writer::start_tag('th', array('class' => 'category_list_header_cell'));
         $content .= html_writer::start_span('author-display') .  '&nbsp;' . html_writer::end_span();
         $content .= html_writer::end_tag('th');
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
                $content .= html_writer::start_span('mod_randomstrayquotes_category') .  $this->format_date_time($category->time_added) . html_writer::end_span();
                $content .= html_writer::end_tag('td');
                $content .= html_writer::start_tag('td', array('class' => 'category_list'));
                $content .= html_writer::start_span('mod_randomstrayquotes_category') .  $this->get_user_name($category->user_id) . html_writer::end_span();
                $content .= html_writer::end_tag('td');
                $content .= html_writer::start_tag('td', array('class' => 'category_list'));
                $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/edit_categories.php', array('catid' => $catid, 'courseid' => $courseid, 'userid' => $userid)), get_string('Edit', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
                $content .= html_writer::end_tag('tr');
         }
         $content .= html_writer::end_tag('table');
         return $content;
    }

    function get_user_name($userid){
        global $DB;
        $queryusername = "Select * from {user} where id= $userid";
        $username = $DB->get_record_sql($queryusername);
        if($username == false){
          $username = 'User Destroyed';
        }else{
          $firstname = $username->firstname;
          $lastname = $username->lastname;
          $username = $firstname . ' ' . $lastname;
        }
      return $username;
    }

    function get_category_name($category_id){
      global $DB;
      $querycat = "Select * from {randomstrayquotes_categories} where id= $category_id";
      $category = $DB->get_record_sql($querycat);
        if($category == false){
          $categoryname = 'category destroyed';
        }else{
          $categoryname = $category->category_name;
        }
      return $categoryname;
    }

    function get_author_name($author_id){
     global $DB;
       $queryauthor = "Select * from {randomstrayquotes_authors} where id= $author_id";
       $author = $DB->get_record_sql($queryauthor);

       if ($author == false){
         $authorname = "Author destroyed";
       }else{
          $authorname = $author->author_name;
       }
     return $authorname;
    }

    function format_date_time($datetime_to_format){

        $date = substr($datetime_to_format, 0, 10);
        $time = substr($datetime_to_format, 11, 4);
        $hours = substr($time, 0,2);
        $minutes = substr($time, 2,4);
        $formateddatetime = $date . ' ' . $hours . ':' . $minutes;

      return $formateddatetime;
    }

    function number_of_contributions($userid, $courseid){
      global $DB;

        $contributions_query = "Select count(*) from {randomstrayquotes_quotes} where user_id = $userid and course_id = $courseid";
        $nbr_contributions = $DB->get_records_sql($contributions_query);
        //settype($nbr_contributions, 'integer');
        $values = array_keys($nbr_contributions);
      return $values[0];

    }

    function display_list_of_quotes($arr_quotes, $userid, $courseid){

    // Navigation buttons
    $content = html_writer::start_tag('table', array('class' => 'table table-striped'));
    $content .= html_writer::start_tag('tr', array('class' => 'author_list_header_row'));
    $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
    $content .= html_writer::end_tag('th');
    $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
    $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/add_quotes.php', array('courseid' => $courseid, 'userid' => $userid)), get_string('Addquotes', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
    $content .= html_writer::end_tag('th');
    $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
    $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/add_authors.php', array('courseid' => $courseid, 'userid' => $userid)), get_string('Addauthors', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
    $content .= html_writer::end_tag('th');
    $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
    $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/add_categories.php', array('courseid' => $courseid, 'userid' => $userid)), get_string('Addcategories', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
    $content .= html_writer::end_tag('th');
    $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
    $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/contributors_list.php', array('courseid' => $courseid, 'userid' => $userid)), get_string('Contributors', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
    $content .= html_writer::end_tag('th');
    $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
    $content .= html_writer::link(new moodle_url('/course/view.php', array('id' => $courseid, 'userid' => $userid)), get_string('Cancel', 'randomstrayquotes'), array('class'=> 'btn btn-danger', 'role'=> 'button', 'aria-pressed'=>'true'));
    $content .= html_writer::end_tag('th');
    $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
    $content .= html_writer::end_tag('th');
    $content .= html_writer::end_tag('tr');
    $content .= html_writer::end_tag('table');

    // Table with list of quotes
    $content .= html_writer::start_tag('table', array('class' => 'table table-striped'));
    $content .= html_writer::start_tag('tr', array('class' => 'author_list_header_row'));
    $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
    $content .= html_writer::start_span('author-display') .  'Author' . html_writer::end_span();
    $content .= html_writer::end_tag('th');
    $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
    $content .= html_writer::start_span('author-display') .  'Quote' . html_writer::end_span();
    $content .= html_writer::end_tag('th');
    $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
    $content .= html_writer::start_span('author-display') .  'Category' . html_writer::end_span();
    $content .= html_writer::end_tag('th');
    $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
    $content .= html_writer::start_span('author-display') .  'Added' . html_writer::end_span();
    $content .= html_writer::end_tag('th');
    $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
    $content .= html_writer::start_span('author-display') .  'Added by' . html_writer::end_span();
    $content .= html_writer::end_tag('th');
    $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
    $content .= html_writer::start_span('author-display') .  'Updated' . html_writer::end_span();
    $content .= html_writer::end_tag('th');
    $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
    $content .= html_writer::start_span('author-display') .  '&nbsp;' . html_writer::end_span();
    $content .= html_writer::end_tag('th');
    $content .= html_writer::end_tag('td');

      foreach ($arr_quotes as $quote){
        $quoteid = $quote->id;
        $courseid = $quote->course_id;
        $userid = $quote->user_id;
        $categoryid = $quote->category_id;
        $categoryname = $this->get_category_name($categoryid);
        $authorpix =  $this->get_image($quote->author_id, $courseid);
        $authorname = $this->get_author_name($quote->author_id);
        $timeadded = $this->format_date_time($quote->time_added);
        $timeupdated = $this->format_date_time($quote->time_updated);
        $content .= html_writer::start_tag('tr', array('class' => 'quote_list'));
        $content .= html_writer::start_tag('td', array('class' => 'author_list'));
        $content .= html_writer::empty_tag('img', array('src'=> $authorpix, 'alt'=>'', 'class'=>'headingimage'));
        $content .= html_writer::empty_tag('br');
        $content .= html_writer::start_span('quote-display') .  $authorname . html_writer::end_span();
        $content .= html_writer::end_tag('td');
        $content .= html_writer::start_tag('td', array('class' => 'quote_list'));
        $content .= html_writer::start_span('quote-display') .  $quote->quote . html_writer::end_span();
        $content .= html_writer::end_tag('td');
        $content .= html_writer::start_tag('td', array('class' => 'quote_list'));
        $content .= html_writer::start_span('quote-display') .  $categoryname . html_writer::end_span();
        $content .= html_writer::end_tag('td');
        $content .= html_writer::start_tag('td', array('class' => 'quote_list'));
        if (isset ($quote->time_added)){
            $content .= html_writer::start_span('quote-display') .  $timeadded . html_writer::end_span();
        }
        $content .= html_writer::end_tag('td');
        $content .= html_writer::start_tag('td', array('class' => 'quote_list'));
        $content .= html_writer::start_span('quote-display') .  $this->get_user_name($quote->user_id) . html_writer::end_span();
        $content .= html_writer::end_tag('td');
        $content .= html_writer::start_tag('td', array('class' => 'quote_list'));
        if (isset ($quote->time_updated)){
            $content .= html_writer::start_span('quote-display') .  $timeupdated . html_writer::end_span();
        }
        $content .= html_writer::end_tag('td');
        $content .= html_writer::start_tag('td', array('class' => 'author_list'));
        $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/edit_quote.php', array('quoteid' => $quote->id, 'courseid' => $courseid, 'userid' => $userid)), get_string('Edit', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
        $content .= html_writer::end_tag('td');
        $content .= html_writer::end_tag('tr');
      }
      $content .= html_writer::end_tag('table');
      return $content;
    }

    function display_list_of_contributors($arr_contributors, $courseid, $userid){
      // Table with list of quotes
      $content = html_writer::start_tag('table', array('class' => 'table table-striped'));
      $content .= html_writer::start_tag('tr', array('class' => 'contributor_list_header_row'));
      $content .= html_writer::start_tag('th', array('class' => 'contributor_list_header_cell'));
      $content .= html_writer::start_span('author-display') .  'Contributor' . html_writer::end_span();
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'contributor_list_header_cell'));
      $content .= html_writer::start_span('author-display') .  'Number of Quotes' . html_writer::end_span();
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'contributor_list_header_cell'));
      $content .= html_writer::start_span('author-display') .  'List of Contributions' . html_writer::end_span();
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'contributor_list_header_cell'));
      $content .= html_writer::start_span('author-display') .  'Grade' . html_writer::end_span();
      $content .= html_writer::end_tag('th');
      $content .= html_writer::end_tag('td');
      // Table with list of contributors
      foreach ($arr_contributors as $contributor){
        //var_dump($contributor->user_id);die;
        $userpix =  $this->get_user_image($contributor->user_id, $courseid);
        $content .= html_writer::start_tag('tr', array('class' => 'contributors_list'));
        $content .= html_writer::start_tag('td', array('class' => 'contributors_list'));
        $content .= html_writer::empty_tag('img', array('src'=> $userpix, 'alt'=>'', 'class'=>'headingimage'));
        $content .= html_writer::empty_tag('br');
        $content .= html_writer::start_span('contributor-display') .  $this->get_user_name($contributor->user_id) . html_writer::end_span();
        $content .= html_writer::end_tag('td');
        $content .= html_writer::start_tag('td', array('class' => 'contributor_list'));
        $content .= html_writer::start_span('contributor-display') .  $this->number_of_contributions($contributor->user_id, $courseid) . html_writer::end_span();
        $content .= html_writer::end_tag('td');
        $content .= html_writer::start_tag('td', array('class' => 'contributor_list'));
        $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/contributions_list.php', array('courseid' => $courseid, 'userid' => $userid)), get_string('Contributions', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
        $content .= html_writer::end_tag('td');
        $content .= html_writer::end_tag('td');
        $content .= html_writer::start_tag('td', array('class' => 'contributor_list'));
        $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/grade.php', array('id' => $courseid, 'userid' => $userid)), get_string('Grade', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
        $content .= html_writer::end_tag('td');
        $content .= html_writer::end_tag('td');
      }
      $content .= html_writer::end_tag('table');
      return $content;
    }

    function display_list_of_authors($arr_authors, $courseid, $userid){
      // Navigation buttons
      $content = html_writer::start_tag('table', array('class' => 'table table-striped'));
      $content .= html_writer::start_tag('tr', array('class' => 'author_list_header_row'));
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/add_quotes.php', array('courseid' => $courseid, 'userid' => $userid)), get_string('Addquotes', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/add_authors.php', array('courseid' => $courseid, 'userid' => $userid)), get_string('Addauthors', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/add_categories.php', array('courseid' => $courseid, 'userid' => $userid)), get_string('Addcategories', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/list_quotes.php', array('courseid' => $courseid, 'userid' => $userid)), get_string('Cancel', 'randomstrayquotes'), array('class'=> 'btn btn-danger', 'role'=> 'button', 'aria-pressed'=>'true'));
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::end_tag('th');
      $content .= html_writer::end_tag('tr');
      $content .= html_writer::end_tag('table');

      // Author's list
      $content .= html_writer::start_tag('table', array('class' => 'table table-striped'));
      $content .= html_writer::start_tag('tr', array('class' => 'author_list_header_row'));
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::start_span('author-display') .  'Author Name' . html_writer::end_span();
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::start_span('author-display') .  'Time Added' . html_writer::end_span();
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::start_span('author-display') .  'Added by' . html_writer::end_span();
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::start_span('author-display') .  'Time Updated' . html_writer::end_span();
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::start_span('author-display') .  '&nbsp;' . html_writer::end_span();
      $content .= html_writer::end_tag('th');
      $content .= html_writer::end_tag('td');

      foreach ($arr_authors as $author){
        $authorid = $author->id;
        $courseid = $author->course_id;
        $userid = $author->user_id;
        $authorpix =  $this->get_image($author->id, $courseid);
        $authorname = $this->get_author_name($author->id);
        $timeadded = $this->format_date_time($author->time_added);
        $timeupdated = $this->format_date_time($author->time_updated);

        $content .= html_writer::start_tag('tr', array('class' => 'author_list'));
        $content .= html_writer::start_tag('td', array('class' => 'author_list'));
        $content .= html_writer::empty_tag('img', array('src'=> $authorpix, 'alt'=>'', 'class'=>'headingimage'));
        $content .= html_writer::empty_tag('br');
        $content .= html_writer::start_span('author-display') .  $authorname . html_writer::end_span();
        $content .= html_writer::end_tag('td');
        $content .= html_writer::start_tag('td', array('class' => 'quote_list'));
        if(isset($author->time_added)){
            $content .= html_writer::start_span('author-display') .  $timeadded . html_writer::end_span();
        }
        $content .= html_writer::end_tag('td');

        $content .= html_writer::start_tag('td', array('class' => 'quote_list'));
        $content .= html_writer::start_span('author-display') . $this->get_user_name($author->user_id) . html_writer::end_span();
        $content .= html_writer::end_tag('td');

        $content .= html_writer::start_tag('td', array('class' => 'quote_list'));
        if(isset($author->time_updated)){
            $content .= html_writer::start_span('author-display') .  $timeupdated . html_writer::end_span();
        }
        $content .= html_writer::end_tag('td');
        $content .= html_writer::start_tag('td', array('class' => 'author_list'));
        $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/edit_author.php', array('authorid' => $author->id, 'courseid' => $courseid, 'userid' => $userid)), get_string('Edit', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
        $content .= html_writer::end_tag('td');
        $content .= html_writer::end_tag('tr');
      }
      $content .= html_writer::end_tag('table');
      return $content;
    }

    function display_list_of_categories($arr_categories, $courseid, $userid){

      // Navigation buttons
      $content = html_writer::start_tag('table', array('class' => 'table table-striped'));
      $content .= html_writer::start_tag('tr', array('class' => 'author_list_header_row'));
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/add_quotes.php', array('courseid' => $courseid, 'userid' => $userid)), get_string('Addquotes', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/add_authors.php', array('courseid' => $courseid, 'userid' => $userid)), get_string('Addauthors', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/add_categories.php', array('courseid' => $courseid, 'userid' => $userid)), get_string('Addcategories', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/list_quotes.php', array('courseid' => $courseid, 'userid' => $userid)), get_string('Cancel', 'randomstrayquotes'), array('class'=> 'btn btn-danger', 'role'=> 'button', 'aria-pressed'=>'true'));
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::end_tag('th');
      $content .= html_writer::end_tag('tr');
      $content .= html_writer::end_tag('table');

      // List of categories
      $content .= html_writer::start_tag('table', array('class' => 'table table-striped'));
      $content .= html_writer::start_tag('tr', array('class' => 'author_list_header_row'));
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::start_span('author-display') .  'Category Name' . html_writer::end_span();
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::start_span('author-display') .  'Time Added' . html_writer::end_span();
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::start_span('author-display') .  'Added by' . html_writer::end_span();
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::start_span('author-display') .  'Time Updated' . html_writer::end_span();
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::start_span('author-display') .  '&nbsp;' . html_writer::end_span();
      $content .= html_writer::end_tag('th');
      $content .= html_writer::end_tag('td');

      foreach ($arr_categories as $category){
        $categoryid = $category->id;
        $courseid = $category->course_id;
        $userid = $category->user_id;
        $categoryname = $category->category_name;
        $timeadded = $this->format_date_time($category->time_added);
        $timeupdated = $this->format_date_time($category->time_updated);

        $content .= html_writer::start_tag('tr', array('class' => 'category_list'));
        $content .= html_writer::start_tag('td', array('class' => 'category_list'));
        $content .= html_writer::start_span('category-display') .  $categoryname . html_writer::end_span();
        $content .= html_writer::end_tag('td');
        $content .= html_writer::start_tag('td', array('class' => 'category_list'));
        if (isset($category->time_added)){
            $content .= html_writer::start_span('category-display') .  $timeadded . html_writer::end_span();
        }
        $content .= html_writer::end_tag('td');
        $content .= html_writer::start_tag('td', array('class' => 'category_list'));
        $content .= html_writer::start_span('category-display') .  $this->get_user_name($category->user_id) . html_writer::end_span();
        $content .= html_writer::end_tag('td');
        $content .= html_writer::start_tag('td', array('class' => 'category_list'));
        if (isset($category->time_updated)){
            $content .= html_writer::start_span('category-display') .  $timeupdated . html_writer::end_span();
        }
        $content .= html_writer::end_tag('td');
        $content .= html_writer::start_tag('td', array('class' => 'category_list'));
        $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/edit_categories.php', array('catid' => $category->id, 'courseid' => $courseid, 'userid' => $userid)), get_string('Edit', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
        $content .= html_writer::end_tag('td');
        $content .= html_writer::end_tag('tr');
      }
      $content .= html_writer::end_tag('table');
      return $content;
    }

    function get_image($authorid, $courseid){
        global $DB, $COURSE;

        // We define the context
        $ctx = context_course::instance($courseid);

        // We setup the file storage area
        $imageid = $DB->get_record('randomstrayquotes_authors', ['id' => $authorid], 'author_picture');
        $fs = get_file_storage();

        // We obtain the filePathHash for the file storage area
        $imagePathHash = $fs->get_area_files($ctx->id, 'mod_randomstrayquotes', 'content', $imageid->author_picture, "itemid, filepath, filename", false);

        // We obtain the id of the picture file already present for the author using the imagePathHash
        $files = array_values($imagePathHash);

        // We store the context and the id of the file in an array of parameters that we will pass at the form instanciation
        $file = $files[0];

        if($file){
            $filename = $file->get_filename();
            $url = moodle_url::make_pluginfile_url($file->get_contextid(), 'mod_randomstrayquotes','content', $file->get_itemid(),'/' ,$filename);
        }else{
            echo("Error");
        }
        return $url;
    }

    function get_user_image($userid, $courseid){
        global $DB, $COURSE;

        // We define the context
        //return $userid;
        try {
          $ctx = context_user::instance($userid);
        } catch (dml_missing_record_exception $e) {
            $url = "/mod/randomstrayquotes/pix/xx.jpg";
          return $url;
        }

        //var_dump($ctx->id);
        // We setup the file storage area
      //  $imageid = $DB->get_record('user', ['id' => 2], 'picture');
        $imageid = $DB->get_record('user', ['id' => $userid], 'picture');
        //var_dump($imageid);die;
        $fs = get_file_storage();
        //var_dump($fs);
        // We obtain the filePathHash for the file storage area
        $imagePathHash = $fs->get_area_files($ctx->id, 'user', 'icon', 0, "itemid, filepath, filename", false);
        //echo "<pre>";var_dump($imagePathHash);echo "</pre>";die;

        // We obtain the id of the picture file already present for the author using the imagePathHash
        $files = array_values($imagePathHash);
        //echo "<pre>";var_dump($files);echo "</pre>";die;
        // We store the context and the id of the file in an array of parameters that we will pass at the form instanciation
        //$file = $files[0];

        foreach ($files as $file){
                 if ($file) {
                  $filename = $file->get_filename();
                  if ($filename){
                      $url = moodle_url::make_pluginfile_url($file->get_contextid(), 'user','icon', $file->get_itemid(),'/' ,$filename);
                 }else{
                      $url = "/mod/randomstrayquotes/pix/xx.jpg";
                 }
           }
               return $url;
        }
/*
        if($file){
            $filename = $file->get_filename();
            $url = moodle_url::make_pluginfile_url($file->get_contextid(), 'user','icon', $file->get_itemid(),'/' ,$filename);
        }else{
            $url ="";
        }
*/

    }


    function display_authors($arr_authors){
           global $COURSE;

         $content = html_writer::start_tag('table', array('class' => 'table table-striped'));
         $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
         $content .= html_writer::start_span('author-display') .  '&nbsp;' . html_writer::end_span();
         $content .= html_writer::end_tag('th');
         $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
         $content .= html_writer::start_span('author-display') .  'Author' . html_writer::end_span();
         $content .= html_writer::end_tag('th');
         $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
         $content .= html_writer::start_span('author-display') .  '&nbsp;' . html_writer::end_span();
         $content .= html_writer::end_tag('th');
         $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
         $content .= html_writer::start_span('author-display') .  'Added by' . html_writer::end_span();
         $content .= html_writer::end_tag('th');
         $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
         $content .= html_writer::start_span('author-display') .  '&nbsp;' . html_writer::end_span();
         $content .= html_writer::end_tag('th');
         $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
         $content .= html_writer::start_span('author-display') .  '&nbsp;' . html_writer::end_span();
         $content .= html_writer::end_tag('th');
         foreach ($arr_authors as $author){
                $courseid = $author->course_id;
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
                $content .= html_writer::start_span('author-display') .  $this->get_user_name($author->user_id) . html_writer::end_span();
                $content .= html_writer::end_tag('td');
                $content .= html_writer::start_tag('td', array('class' => 'author_list'));
                $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/edit_author.php', array('authorid' => $author->id, 'courseid' => $courseid, 'userid' => $userid)), get_string('Edit', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
                $content .= html_writer::end_tag('td');
                $content .= html_writer::end_tag('tr');
         }
         $content .= html_writer::end_tag('table');
         return $content;
    }

    // Display alerts
     function display_alerts($errormessage) {
        $content = html_writer::start_tag('div', array('class' => 'alert alert-danger', 'role'=> 'alert'));
        $content .= $errormessage;
        $content .= html_writer::end_tag('div');
    }

    //  Retrieve the author of the associated quote and his picture
     function get_author($DB, $quoteid) {
        $author = $DB->get_record('randomstrayquotes_authors', array('id' => $quoteid), '*', MUST_EXIST);
     return $author;
    }

    //   Retrieve the author of the associated quote and his picture
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
