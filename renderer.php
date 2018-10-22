<?php
require_once("$CFG->dirroot/mod/randomstrayquotes/locallib.php");
defined('MOODLE_INTERNAL') || die();
require_login();
require_once($CFG->dirroot . '/mod/randomstrayquotes/db/access.php');
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
              $userpix =  get_user_image($category->user_id, $courseid);
              $content .= html_writer::start_tag('tr', array('class' => 'category_list'));
              $content .= html_writer::start_tag('td', array('class' => 'category_list'));
              $content .= html_writer::start_span('mod_randomstrayquotes_category') .  $category->id . html_writer::end_span();
              $content .= html_writer::end_tag('td');
              $content .= html_writer::start_tag('td', array('class' => 'category_list'));
              $content .= html_writer::start_span('mod_randomstrayquotes_category') .  $category->category_name . html_writer::end_span();
              $content .= html_writer::end_tag('td');
              $content .= html_writer::start_tag('td', array('class' => 'category_list'));
              $content .= html_writer::start_span('mod_randomstrayquotes_category') .  format_date_time($category->time_added) . html_writer::end_span();
              $content .= html_writer::end_tag('td');
              $content .= html_writer::start_tag('td', array('class' => 'category_list'));
              $content .= html_writer::empty_tag('img', array('src'=> $userpix, 'alt'=>'', 'class'=>'author-picture-for-list'));
              $content .= html_writer::empty_tag('br');
              $content .= html_writer::start_span('mod_randomstrayquotes_category') .  get_user_name($category->user_id) . html_writer::end_span();
              $content .= html_writer::end_tag('td');
              $content .= html_writer::start_tag('td', array('class' => 'category_list'));
              $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/edit_categories.php', array('catid' => $catid, 'courseid' => $courseid, 'userid' => $userid)), get_string('Edit', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
              $content .= html_writer::end_tag('tr');
         }
         $content .= html_writer::end_tag('table');
         return $content;
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
          $categoryname = get_category_name($categoryid);
          $authorpix =  get_image($quote->author_id, $courseid);
          $authorname = get_author_name($quote->author_id);
          $timeadded = format_date_time($quote->time_added);
          $timeupdated = format_date_time($quote->time_updated);
          $userpix =  get_user_image($quote->user_id, $courseid);
          $content .= html_writer::start_tag('tr', array('class' => 'quote_list'));
          $content .= html_writer::start_tag('td', array('class' => 'author_list'));
          $content .= html_writer::empty_tag('img', array('src'=> $authorpix, 'alt'=>'', 'class'=>'author-picture-for-list'));
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
          $content .= html_writer::empty_tag('img', array('src'=> $userpix, 'alt'=>'', 'class'=>'profile-picture-author-list'));
          $content .= html_writer::empty_tag('br');
          $content .= html_writer::start_span('quote-display') .  get_user_name($quote->user_id) . html_writer::end_span();
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

    function display_list_of_contributions($arr_contributions, $courseid, $userid, $cm, $disable_grading_button){
      global $USER;

      $context = context_module::instance($cm->id);
      if ($disable_grading_button == true) {
          $content = html_writer::start_tag('div', array('class' => 'alert alert-danger', 'role' => 'alert')) .  ' <b>The Grading Button is Absent Because you Have More than One Instance of this Plugin with the Grading Option Activated in this Course. </b> If you want to use more than one instance of this plugin in the same course make sure only one of them is gradable.' . html_writer::end_tag('div');
          $content .= html_writer::start_tag('table', array('class' => 'table table-striped'));
      }else{
          $content = html_writer::start_tag('table', array('class' => 'table table-striped'));
      }

      // Navigation buttons
      $content .= html_writer::start_tag('tr', array('class' => 'author_list_header_row'));
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/list_quotes.php', array('courseid' => $courseid, 'userid' => $userid)), get_string('Listofquotes', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
      $content .= html_writer::end_tag('th');
      // If there is more than one instance of the pluging with the grading option activated, the grading button is hidden
      if ($disable_grading_button == false) {
        //Those who cannot attribute grades cannot see this button
        if (has_capability('mod/randomstrayquotes:grade', $context, $USER->id, $doanything = true, $errormessage='nopermissions')){
          $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
          $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/grade.php', array('id' => $cm->id, 'userid' => $userid)), get_string('Gradethiscontributor', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true', 'aria-disabled' => 'true'));
          $content .= html_writer::end_tag('th');
          }
      }
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/contributors_list.php', array('courseid' => $courseid, 'userid' => $userid)), get_string('Contributorslist', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
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
          $content .= html_writer::start_span('author-display') .  'Updated' . html_writer::end_span();
          $content .= html_writer::end_tag('th');
          $content .= html_writer::end_tag('td');

            foreach ($arr_contributions as $contribution){
                $quoteid = $contribution->id;
                $courseid = $contribution->course_id;
                $userid = $contribution->user_id;
                $categoryid = $contribution->category_id;
                $categoryname = get_category_name($categoryid);
                $authorpix =  get_image($contribution->author_id, $courseid);
                $authorname = get_author_name($contribution->author_id);
                $timeadded = format_date_time($contribution->time_added);
                $timeupdated = format_date_time($contribution->time_updated);
                $content .= html_writer::start_tag('tr', array('class' => 'quote_list'));
                $content .= html_writer::start_tag('td', array('class' => 'author_list'));
                $content .= html_writer::empty_tag('img', array('src'=> $authorpix, 'alt'=>'', 'class'=>'author-picture-for-list'));
                $content .= html_writer::empty_tag('br');
                $content .= html_writer::start_span('quote-display') .  $authorname . html_writer::end_span();
                $content .= html_writer::end_tag('td');
                $content .= html_writer::start_tag('td', array('class' => 'quote_list'));
                $content .= html_writer::start_span('quote-display') .  $contribution->quote . html_writer::end_span();
                $content .= html_writer::end_tag('td');
                $content .= html_writer::start_tag('td', array('class' => 'quote_list'));
                $content .= html_writer::start_span('quote-display') .  $categoryname . html_writer::end_span();
                $content .= html_writer::end_tag('td');
                $content .= html_writer::start_tag('td', array('class' => 'quote_list'));
                if (isset ($contribution->time_added)){
                    $content .= html_writer::start_span('quote-display') .  $timeadded . html_writer::end_span();
                }
                $content .= html_writer::end_tag('td');
                $content .= html_writer::start_tag('td', array('class' => 'quote_list'));
                if (isset ($contribution->time_updated)){
                    $content .= html_writer::start_span('quote-display') .  $timeupdated . html_writer::end_span();
                }
                $content .= html_writer::end_tag('td');
                $content .= html_writer::end_tag('tr');
              }
              $content .= html_writer::end_tag('table');
              return $content;
          }

    function display_list_of_contributors($arr_contributors, $courseid, $userid){

      // Navigation buttons
      $content = html_writer::start_tag('table', array('class' => 'table table-striped'));
      $content .= html_writer::start_tag('tr', array('class' => 'contributors_list_header_row'));
      $content .= html_writer::start_tag('th', array('class' => 'contributors_list_header_cell'));
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'contributors_list_header_cell'));
      $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/list_quotes.php', array('courseid' => $courseid, 'userid' => $userid)), get_string('Listofquotes', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'contributors_list_header_cell'));
      $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/list_categories.php', array('courseid' => $courseid, 'userid' => $userid)), get_string('categorieslist', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'contributors_list_header_cell'));
      $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/list_authors.php', array('courseid' => $courseid, 'userid' => $userid)), get_string('authorslist', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'contributors_list_header_cell'));
      $content .= html_writer::link(new moodle_url('/course/view.php', array('id' => $courseid, 'userid' => $userid)), get_string('Cancel', 'randomstrayquotes'), array('class'=> 'btn btn-danger', 'role'=> 'button', 'aria-pressed'=>'true'));
      $content .= html_writer::end_tag('th');

      $content .= html_writer::end_tag('tr');
      $content .= html_writer::end_tag('table');
      // Table with list of quotes
      $content .= html_writer::start_tag('table', array('class' => 'table table-striped'));
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
          $userpix =  get_user_image($contributor->user_id, $courseid);
          $content .= html_writer::start_tag('tr', array('class' => 'contributors_list'));
          $content .= html_writer::start_tag('td', array('class' => 'contributors_list'));
          $content .= html_writer::empty_tag('img', array('src'=> $userpix, 'alt'=>'', 'class'=>'profile-picture-author-list'));
          $content .= html_writer::empty_tag('br');
          $content .= html_writer::start_span('contributor-display') .  get_user_name($contributor->user_id) . html_writer::end_span();
          $content .= html_writer::end_tag('td');
          $content .= html_writer::start_tag('td', array('class' => 'contributor_list'));
          $content .= html_writer::start_span('contributor-display') .  number_of_contributions($contributor->user_id, $courseid) . html_writer::end_span();
          $content .= html_writer::end_tag('td');
          $content .= html_writer::start_tag('td', array('class' => 'contributor_list'));
          $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/list_of_contributions.php', array('courseid' => $courseid, 'userid' => $contributor->user_id)), get_string('Contributions', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
          $content .= html_writer::end_tag('td');
          $content .= html_writer::end_tag('td');
          $content .= html_writer::start_tag('td', array('class' => 'contributor_list'));
          $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/grade.php', array('id' => $courseid, 'userid' => $contributor->user_id)), get_string('Grade', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
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
      $content .= html_writer::start_span('author-list-header-label') .  'Author Name' . html_writer::end_span();
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::start_span('author-list-header-label') .  'Time Added' . html_writer::end_span();
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::start_span('author-list-header-label') .  'Added by' . html_writer::end_span();
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::start_span('author-list-header-label') .  'Time Updated' . html_writer::end_span();
      $content .= html_writer::end_tag('th');
      $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
      $content .= html_writer::start_span('author-list-header-label') .  '&nbsp;' . html_writer::end_span();
      $content .= html_writer::end_tag('th');
      $content .= html_writer::end_tag('td');

      foreach ($arr_authors as $author){
          $authorid = $author->id;
          $courseid = $author->course_id;
          $userid = $author->user_id;
          $authorpix =  get_image($author->id, $courseid);
          $authorname = get_author_name($author->id);
          $timeadded = format_date_time($author->time_added);
          $timeupdated = format_date_time($author->time_updated);
          $userpix =  get_user_image($author->user_id, $courseid);
          $content .= html_writer::start_tag('tr', array('class' => 'author_list_details_cell'));
          $content .= html_writer::start_tag('td', array('class' => 'author_list_details_cell'));
          $content .= html_writer::empty_tag('img', array('src'=> $authorpix, 'alt'=>'', 'class'=>'profile-picture-author-list'));
          $content .= html_writer::empty_tag('br');
          $content .= html_writer::start_span('author-details-label') .  $authorname . html_writer::end_span();
          $content .= html_writer::end_tag('td');
          $content .= html_writer::start_tag('td', array('class' => 'author_list_details_cell'));
          if(isset($author->time_added)){
              $content .= html_writer::start_span('author-details-label') .  $timeadded . html_writer::end_span();
          }
          $content .= html_writer::end_tag('td');

        $content .= html_writer::start_tag('td', array('class' => 'author_list_details_cell'));
        $content .= html_writer::empty_tag('img', array('src'=> $userpix, 'alt'=>'', 'class'=>'profile-picture-author-list'));
        $content .= html_writer::empty_tag('br');
        $content .= html_writer::start_span('author-details-label') . get_user_name($author->user_id) . html_writer::end_span();
        $content .= html_writer::end_tag('td');

        $content .= html_writer::start_tag('td', array('class' => 'quote_list'));
        if(isset($author->time_updated)){
            $content .= html_writer::start_span('author-details-label') .  $timeupdated . html_writer::end_span();
        }
        $content .= html_writer::end_tag('td');
        $content .= html_writer::start_tag('td', array('class' => 'author_list_details_cell'));
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
          $timeadded = format_date_time($category->time_added);
          $timeupdated = format_date_time($category->time_updated);
          $userpix =  get_user_image($category->user_id, $courseid);
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
          $content .= html_writer::empty_tag('img', array('src'=> $userpix, 'alt'=>'', 'class'=>'profile-picture-author-list'));
          $content .= html_writer::empty_tag('br');
          $content .= html_writer::start_span('category-display') .  get_user_name($category->user_id) . html_writer::end_span();
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

    function display_authors($arr_authors){
           global $COURSE;

         $content = html_writer::start_tag('table', array('class' => 'table table-striped'));
         $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
         $content .= html_writer::start_span('author-list-header-label') .  '&nbsp;' . html_writer::end_span();
         $content .= html_writer::end_tag('th');
         $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
         $content .= html_writer::start_span('author-list-header-label') .  'Author' . html_writer::end_span();
         $content .= html_writer::end_tag('th');
         $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
         $content .= html_writer::start_span('author-list-header-label') .  '&nbsp;' . html_writer::end_span();
         $content .= html_writer::end_tag('th');
         $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
         $content .= html_writer::start_span('author-list-header-label') .  'Added by' . html_writer::end_span();
         $content .= html_writer::end_tag('th');
         $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
         $content .= html_writer::start_span('author-list-header-label') .  '&nbsp;' . html_writer::end_span();
         $content .= html_writer::end_tag('th');
         $content .= html_writer::start_tag('th', array('class' => 'author_list_header_cell'));
         $content .= html_writer::start_span('author-list-header-label') .  '&nbsp;' . html_writer::end_span();
         $content .= html_writer::end_tag('th');
         foreach ($arr_authors as $author){
              $courseid = $author->course_id;
              $authorpix =  get_image($author->id, $courseid);
              $userid = $author->user_id;
              $userpix =  get_user_image($author->user_id, $courseid);
              $content .= html_writer::start_tag('tr', array('class' => 'author_list_details_cell'));
              $content .= html_writer::start_tag('td', array('class' => 'author_list_details_cell'));
              $content .= html_writer::start_span('author-details-label') .  $author->id . html_writer::end_span();
              $content .= html_writer::end_tag('td');
              $content .= html_writer::start_tag('td', array('class' => 'author_list_details_cell'));
              $content .= html_writer::empty_tag('img', array('src'=> $authorpix, 'alt'=>'', 'class'=>'profile-picture-author-list'));
              $content .= html_writer::end_tag('td');
              $content .= html_writer::start_tag('td', array('class' => 'author_list_details_cell'));
              $content .= html_writer::start_span('author-details-label') .  $author->author_name . html_writer::end_span();
              $content .= html_writer::end_tag('td');
              $content .= html_writer::start_tag('td', array('class' => 'author_list_details_cell'));
              $content .= html_writer::empty_tag('img', array('src'=> $userpix, 'alt'=>'', 'class'=>'profile-picture-author-list'));
              $content .= html_writer::empty_tag('br');
              $content .= html_writer::start_span('author-details-label') .  get_user_name($author->user_id) . html_writer::end_span();
              $content .= html_writer::end_tag('td');
              $content .= html_writer::start_tag('td', array('class' => 'author_list_details_cell'));
              $content .= html_writer::link(new moodle_url('/mod/randomstrayquotes/edit_author.php', array('authorid' => $author->id, 'courseid' => $courseid, 'userid' => $userid)), get_string('Edit', 'randomstrayquotes'), array('class'=> 'btn btn-secondary', 'role'=> 'button', 'aria-pressed'=>'true'));
              $content .= html_writer::end_tag('td');
              $content .= html_writer::end_tag('tr');
         }
         $content .= html_writer::end_tag('table');
         return $content;
    }

    function display_error_message($message){

      $content = html_writer::start_tag('div', array('class' => 'alert alert-danger', 'role' => 'alert')) .  $message . html_writer::end_tag('div');

      return $content;
    }

    function display_quotes($arr_quotes, $DB){
        global $COURSE, $USER;
         $content = html_writer::start_tag('table', array('class' => 'table table-striped'));
         foreach ($arr_quotes as $quote){
              $author = get_author($DB, $quote->author_id);
              $category = get_category($DB, $quote->category);
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
