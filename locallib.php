<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Internal library of functions for module newmodule
 *
 * All the newmodule specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package    mod_newmodule
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/*
 * Does something really useful with the passed things
 *
 * @param array $things
 * @return object
 *function newmodule_do_something_useful(array $things) {
 *    return new stdClass();
 *}
 */
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
     $values = array_keys($nbr_contributions);
   return $values[0];
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

  function get_user_image($userid, $courseid){
     global $DB, $COURSE;

     // We define the context  + return $userid;
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
  }
