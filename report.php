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
$r  = optional_param('r', 0, PARAM_INT);  // ... newmodule instance ID - it should be named as the first character of the module.

global $USER;

if ($id) {
    $cm         = get_coursemodule_from_id('randomstrayquotes', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $randomstrayquotes  = $DB->get_record('randomstrayquotes', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($r) {
    $randomstrayquotes  = $DB->get_record('randomstrayquotes', array('id' => $r), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $randomstrayquotes->course), '*', MUST_EXIST);
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

$PAGE->set_url('/mod/randomstrayquotes/report.php', array('id' => $cm->id));
$PAGE->set_title(format_string($randomstrayquotes->name));
$PAGE->set_heading(format_string($course->fullname));

// Output starts here.
echo $OUTPUT->header();
echo $OUTPUT->footer();
