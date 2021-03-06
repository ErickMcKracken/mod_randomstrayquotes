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
 * Redirect the user to the appropriate submission related page
 *
 * @package   mod_newmodule
 * @category  grade
 * @copyright 2016 Your Name <your@email.address>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . "../../../config.php");

$id = required_param('id', PARAM_INT);// Course module ID.
// Item number may be != 0 for activities that allow more than one grade per user.
$itemnumber = optional_param('itemnumber', 0, PARAM_INT);
$userid = optional_param('userid', 0, PARAM_INT); // Graded user ID (optional).







//if (! $cm = get_coursemodule_from_id('randomstrayquotes', $id, 0, false, MUST_EXIST)){
if (! $cm = get_coursemodule_from_id('randomstrayquotes', $id)){
  print_error('invalidcoursemodule');
}

if(! $randomstrayquotes = $DB->get_record('randomstrayquotes', array('id' => $cm->instance))){
  print_error('invalidcoursemodule');
}

if (! $course = $DB->get_record('course', array('id' => $randomstrayquotes->course))){
  print_error('coursemisconf');
}

require_login($course, false, $cm);

// In the simplest case just redirect to the view page.
if(has_capability('mod/randomstrayquotes:viewreport', context_module::instance($cm->id))){
  redirect('report.php?id=' .$cm->id);
}else{
  redirect('view.php?id=' .$cm->id);
}
