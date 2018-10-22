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
 * Prints a particular instance of newmodule
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_newmodule
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Replace newmodule with the name of your module and remove this line.

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
#require_once($CFG->dirroot. '/mod/randomstrayquotes/lib.php');
#require_once($CFG->dirroot. '/mod/randomstrayquotes/locallib.php');
require_once(dirname(__FILE__).'/lib.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$r  = optional_param('r', 0, PARAM_INT);  // ... randomstrayquotes instance ID - it should be named as the first character of the module.

global $USER;

if ($id) {
    $cm         = get_coursemodule_from_id('randomstrayquotes', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $randomstrayquotes  = $DB->get_record('randomstrayquotes', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($r) {
    $randomstrayquotes  = $DB->get_record('randomstrayquotes', array('id' => $r), '*', MUST_EXIST);
    $course     = $DB->get_record('randomstrayquotes', array('id' => $randomstrayquotes->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('randomstrayquotes', $randomstrayquotes->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

if ($id) {
    $cm         = get_coursemodule_from_id('randomstrayquotes', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $randomstrayquotes  = $DB->get_record('randomstrayquotes', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($r) {
    $randomstrayquotes  = $DB->get_record('randomstrayquotes', array('id' => $r), '*', MUST_EXIST);
    $course     = $DB->get_record('randomstrayquotes', array('id' => $randomstrayquotes->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('randomstrayquotes', $randomstrayquotes->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/randomstrayquotes:view', $context);

$event = \mod_randomstrayquotes\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $randomstrayquotes);
$event->trigger();

// Print the page header.

$PAGE->set_url('/mod/randomstrayquotes/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($randomstrayquotes->name));
$PAGE->set_heading(format_string($course->fullname));

// Output starts here.
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.

if ($randomstrayquotes->intro) {
    echo $OUTPUT->box(format_module_intro('randomstrayquotes', $newmodule, $cm->id), 'generalbox mod_introbox', 'newmoduleintro');
}


if ($randomstrayquotes->intro) {
    echo $OUTPUT->box(format_module_intro('randomstrayquotes', $randomstrayquotes, $cm->id), 'generalbox mod_introbox', 'newmoduleintro');
}

// Replace the following lines with you own code.
echo $OUTPUT->heading('Manage Quotes');

// We check how many instance of this plugin have the grading option activated
$nbr_grading_instances_query = "Select count(*) from {randomstrayquotes} where course = $course->id and grade <> 0";
$nbr_grading_instances = $DB->get_records_sql($nbr_grading_instances_query);
$values = array_keys($nbr_grading_instances);

// If there is more than one instance of this plugin with grading option activated we display a waning that the grading will be deactivated
if ($values[0] > 1){
  $message = "There is currently more than one instance of this plugin with the grading option activated.  This plugin can have multiple instances in the same course but only one can be graded. The grading option will be deactivated until the situation is corrected.";
  $renderer = $PAGE->get_renderer('mod_randomstrayquotes');
  $content = $renderer->display_error_message($message);
  echo ($content);
}

// Display the complete Quotes Listing
        $selectArray = array();
        $quotesquery = "Select * from {randomstrayquotes_quotes} where course_id = $course->id and visible = 1";
        $quotes_arr = $DB->get_records_sql($quotesquery);

            if ($quotes_arr) {
                    $renderer = $PAGE->get_renderer('mod_randomstrayquotes');
                    $content = $renderer->display_list_of_quotes($quotes_arr, $USER->id,$course->id);
                } else {
                    $content = 'Aucunes citations n\'ont été saisies pour le moment';
                }

                echo ($content);

echo $OUTPUT->footer();
