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
 * Library of interface functions and constants for module newmodule
 *
 * All the core Moodle functions, neeeded to allow the module to work
 * integrated in Moodle should be placed here.
 *
 * All the newmodule specific functions, needed to implement all the module
 * logic, should go to locallib.php. This will help to save some memory when
 * Moodle is performing actions across all modules.
 *
 * @package    mod_newmodule
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Example constant, you probably want to remove this :-)
 */
define('NEWMODULE_ULTIMATE_ANSWER', 42);

/* Moodle core API */

/**
 * Returns the information on whether the module supports a feature
 *
 * See {@link plugin_supports()} for more info.
 *
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed true if the feature is supported, null if unknown
 */
function randomstrayquotes_supports($feature) {

    switch($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return true;
        case FEATURE_GRADE_HAS_GRADE:
            return true;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        default:
            return null;
    }
}

/**
 * Saves a new instance of the newmodule into the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param stdClass $newmodule Submitted data from the form in mod_form.php
 * @param mod_newmodule_mod_form $mform The form instance itself (if needed)
 * @return int The id of the newly inserted newmodule record
 */
function randomstrayquotes_add_instance(stdclass $randomstrayquotes, $mform = null) {
    global $DB;

    $randomstrayquotes->timecreated = time();
    $randomstrayquotes->students_add_quotes = $randomstrayquotes->admin_setting_students_add_quotes;
    $randomstrayquotes->students_add_authors = $randomstrayquotes->admin_setting_students_add_authors;
    $randomstrayquotes->students_add_categories = $randomstrayquotes->admin_setting_students_add_categories;

    // You may have to add extra stuff in here.
    $randomstrayquotes->id = $DB->insert_record('randomstrayquotes', $randomstrayquotes);

    randomstrayquotes_grade_item_update($randomstrayquotes);

    return $randomstrayquotes->id;
}

/**
 * Updates an instance of the newmodule in the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param stdClass $newmodule An object from the form in mod_form.php
 * @param mod_newmodule_mod_form $mform The form instance itself (if needed)
 * @return boolean Success/Fail
 */
function randomstrayquotes_update_instance(stdclass $randomstrayquotes, $mform =null) {
    global $DB, $CFG;

    $randomstrayquotes->timemodified = time();
    $randomstrayquotes->id = $randomstrayquotes->instance;
    $randomstrayquotes->students_add_quotes = $randomstrayquotes->admin_setting_students_add_quotes;
    $randomstrayquotes->students_add_authors = $randomstrayquotes->admin_setting_students_add_authors;
    $randomstrayquotes->students_add_categories = $randomstrayquotes->admin_setting_students_add_categories;

    // You may have to add extra stuff in here.
    $result = $DB->update_record('randomstrayquotes', $randomstrayquotes);

    randomstrayquotes_grade_item_update($randomstrayquotes);

    return $result;
}

/**
 * This standard function will check all instances of this module
 * and make sure there are up-to-date events created for each of them.
 * If courseid = 0, then every newmodule event in the site is checked, else
 * only newmodule events belonging to the course specified are checked.
 * This is only required if the module is generating calendar events.
 *
 * @param int $courseid Course ID
 * @return bool
 */
function randomstrayquotes_refresh_events($courseid = 0) {
    global $DB;

    if ($courseid == 0) {
        if (!$randomstrayquotes = $DB->get_records('randomstrayquotes')) {
            return true;
        }
    } else {
        if (!$randomstrayquotes = $DB->get_records('randomstrayquotes', array('course' => $courseid))) {
            return true;
        }
    }

    foreach ($randomstrayquotes as $randomstrayquotes) {
        // Create a function such as the one below to deal with updating calendar events.
    #     randsomstrayquotes_update_events($randomstrayquotes);
    }

    return true;
}

/**
 * Removes an instance of the newmodule from the database
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function randomstrayquotes_delete_instance($id) {
    global $DB;

    if (! $randomstrayquotes = $DB->get_record('randomstrayquotes', array('id' => $id))) {
        return false;
        /*
      }else{

        $randomstrayquotes = $DB->get_record('randomstrayquotes', array('id' => $id), MUST_EXIST);
        delete_randomstrayquotes_quotes($randomstrayquotes);
        delete_randomstrayquotes_categories($randomstrayquotes);
        delete_randomstraquotes_authors($randomstrayquotes);
        */
    }

    // Delete any dependent records here.

    $DB->delete_records('randomstrayquotes', array('id' => $randomstrayquotes->id));

    randomstrayquotes_grade_item_delete($randomstrayquotes);

    return true;
}

/**
 * Returns a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 *
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @param stdClass $course The course record
 * @param stdClass $user The user record
 * @param cm_info|stdClass $mod The course module info object or record
 * @param stdClass $newmodule The newmodule instance record
 * @return stdClass|null
 */
function randomstrayquotes_user_outline($course, $user, $mod, $randomstrayquotes) {

    $return = new stdClass();
    $return->time = 0;
    $return->info = '';
    return $return;
}

/**
 * Prints a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * It is supposed to echo directly without returning a value.
 *
 * @param stdClass $course the current course record
 * @param stdClass $user the record of the user we are generating report for
 * @param cm_info $mod course module info
 * @param stdClass $newmodule the module instance record
 */
function randomstrayquotes_user_complete($course, $user, $mod, $randomstrayquotes) {
}

/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in newmodule activities and print it out.
 *
 * @param stdClass $course The course record
 * @param bool $viewfullnames Should we display full names
 * @param int $timestart Print activity since this timestamp
 * @return boolean True if anything was printed, otherwise false
 */
function randomstrayquotes_print_recent_activity($course, $viewfullnames, $timestart) {
    return false;
}

/**
 * Prepares the recent activity data
 *
 * This callback function is supposed to populate the passed array with
 * custom activity records. These records are then rendered into HTML via
 * {@link newmodule_print_recent_mod_activity()}.
 *
 * Returns void, it adds items into $activities and increases $index.
 *
 * @param array $activities sequentially indexed array of objects with added 'cmid' property
 * @param int $index the index in the $activities to use for the next record
 * @param int $timestart append activity since this time
 * @param int $courseid the id of the course we produce the report for
 * @param int $cmid course module id
 * @param int $userid check for a particular user's activity only, defaults to 0 (all users)
 * @param int $groupid check for a particular group's activity only, defaults to 0 (all groups)
 */
function randomstrayquotes_get_recent_mod_activity(&$activities, &$index, $timestart, $courseid, $cmid, $userid=0, $groupid=0) {
}

/**
 * Prints single activity item prepared by {@link newmodule_get_recent_mod_activity()}
 *
 * @param stdClass $activity activity record with added 'cmid' property
 * @param int $courseid the id of the course we produce the report for
 * @param bool $detail print detailed report
 * @param array $modnames as returned by {@link get_module_types_names()}
 * @param bool $viewfullnames display users' full names
 */
function randomstrayquotes_print_recent_mod_activity($activity, $courseid, $detail, $modnames, $viewfullnames) {
}

/**
 * Function to be run periodically according to the moodle cron
 *
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * Note that this has been deprecated in favour of scheduled task API.
 *
 * @return boolean
 */
function randomstrayquotes_cron () {
    return true;
}

/**
 * Returns all other caps used in the module
 *
 * For example, this could be array('moodle/site:accessallgroups') if the
 * module uses that capability.
 *
 * @return array
 */
function randomstrayquotes_get_extra_capabilities() {
    return array();
}

/* Gradebook API */

/**
 * Is a given scale used by the instance of newmodule?
 *
 * This function returns if a scale is being used by one newmodule
 * if it has support for grading and scales.
 *
 * @param int $newmoduleid ID of an instance of this module
 * @param int $scaleid ID of the scale
 * @return bool true if the scale is used by the given newmodule instance
 */
function randomstrayquotes_scale_used($randomstrayquotesid, $scaleid) {
    global $DB;

    if ($scaleid and $DB->record_exists('randomstrayquotes', array('id' => $randomstrayquotesid, 'grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Checks if scale is being used by any instance of newmodule.
 *
 * This is used to find out if scale used anywhere.
 *
 * @param int $scaleid ID of the scale
 * @return boolean true if the scale is used by any newmodule instance
 */
function randomstrayquotes_scale_used_anywhere($scaleid) {
    global $DB;

    if ($scaleid and $DB->record_exists('randomstrayquotes', array('grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Creates or updates grade item for the given newmodule instance
 *
 * Needed by {@link grade_update_mod_grades()}.
 *
 * @param stdClass $newmodule instance object with extra cmidnumber and modname property
 * @param bool $reset reset grades in the gradebook
 * @return void
 */
function randomstrayquotes_grade_item_update(stdClass $randomstrayquotes, $reset=false) {
    global $CFG;
    require_once($CFG->dirroot.'/mod/randomstrayquotes/locallib.php');

    if(!function_exists('grade_update')) {
      require_once($CFG->libdir.'/gradelib.php');
    }

    $item = array('itemname'=>$randomstrayquotes->name, 'idnumber'=>$randomstrayquotes->cmidnumber);
    //$item['itemname'] = clean_param($randomstrayquotes->name, PARAM_NOTAGS);
    //$item['gradetype'] = GRADE_TYPE_VALUE;

    if ($randomstrayquotes->grade > 0) {
        $item['gradetype'] = GRADE_TYPE_VALUE;
        $item['grademax']  = $randomstrayquotes->grade;
        $item['grademin']  = 0;
    } else if ($randomstrayquotes->grade < 0) {
        $item['gradetype'] = GRADE_TYPE_SCALE;
        $item['scaleid']   = -$randomstrayquotes->grade;
    } else {
        $item['gradetype'] = GRADE_TYPE_NONE;
    }

    if ($reset) {
        $item['reset'] = true;
    }

  return  grade_update('mod/randomstrayquotes', $randomstrayquotes->course, 'mod', 'randomstrayquotes',
            $randomstrayquotes->id, 0, null, $item);
}

/**
 * Delete grade item for given newmodule instance
 *
 * @param stdClass $newmodule instance object
 * @return grade_item
 */
function randomstrayquotes_grade_item_delete($randomstrayquotes) {
    global $CFG;
    require_once($CFG->libdir.'/gradelib.php');

    return grade_update('mod/randomstrayquotes', $randomstrayquotes->course, 'mod', 'randomstrayquotes',
            $randomstrayquotes->id, 0, null, array('deleted' => 1));
}

/**
 * Update newmodule grades in the gradebook
 *
 * Needed by {@link grade_update_mod_grades()}.
 *
 * @param stdClass $newmodule instance object with extra cmidnumber and modname property
 * @param int $userid update grade of specific user only, 0 means all participants
 */
function randomstrayquotes_update_grades($randomstrayquotes, $userid = 0, $nullifnone = true) {
    global $CFG, $DB;
    require_once($CFG->libdir.'/gradelib.php');
    $grades = array();

    // Populate array of grade objects indexed by userid.
    if($randomstrayquotes->grade == 0){
      randomstrayquotes_grade_item_update($randomstrayquotes);
    }else if ($grade = randomstrayquotes_get_user_grades($randomstrayquotes, $userid)){
      randomstrayquotes_grade_item_update($randomstrayquotes, $grades);
    }else if ($userid && $nullifnone){
      $grade = new stdClass();
      $grade->userid = $userid;
      $grade->rawgrade = null;
      randomstrayquotes_grade_item_update($randomstrayquotes, $grade);
    }else{
      randomstrayquotes_grade_item_update($randomstrayquotes);
    }

    grade_update('mod/randomstrayquotes', $randomstrayquotes->course, 'mod', 'randomstrayquotes', $randomstrayquotes->id, 0, $grades);
}

/* File API */

/**
 * Returns the lists of all browsable file areas within the given module context
 *
 * The file area 'intro' for the activity introduction field is added automatically
 * by {@link file_browser::get_file_info_context_module()}
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @return array of [(string)filearea] => (string)description
 */
function randomstrayquotes_get_file_areas($course, $cm, $context) {
    return array();
}

/**
 * File browsing support for newmodule file areas
 *
 * @package mod_newmodule
 * @category files
 *
 * @param file_browser $browser
 * @param array $areas
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @param string $filearea
 * @param int $itemid
 * @param string $filepath
 * @param string $filename
 * @return file_info instance or null if not found
 */
function randomstrayquotes_get_file_info($browser, $areas, $course, $cm, $context, $filearea, $itemid, $filepath, $filename) {
    return null;
}

/**
 * Serves the files from the newmodule file areas
 *
 * @package mod_newmodule
 * @category files
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the newmodule's context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 */
function randomstrayquotes_pluginfile($course, $cm, $context, $filearea, array $args, $forcedownload, array $options=array()) {
    global $DB, $CFG;

    //echo "<pre>";
    //var_dump(func_get_args());echo "</pre>"; die();
    if ($context->contextlevel != CONTEXT_COURSE) {
        return false;
    }
    require_login($course, true, $cm);

    //$canmanageactivity = has_capability('moodle/course:manageactivities', $context);
    if ($filearea === 'content') {
        $relativepath = implode('/', $args);
        //$fullpath = "/$context->id/mod_randomstrayquotes/content/0/$relativepath";
        $fullpath = "/".$context->id."/mod_randomstrayquotes/content/".$relativepath;
        //$fullpath= "/1898224/mod_randomstrayquotes/content/136692634/toto-the-frog-1272162_640.jpg";
        // TODO: add any other access restrictions here if needed!

    }
    else {
        return false;
    }

    $fs = get_file_storage();
    $fs->get_file_by_id($fileid);
    if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory()) {
        if ($filearea === 'content') { // Return file not found straight away to improve performance.
            send_header_404();
            die;
        }
        return false;
    }

    // Finally send the file.
    send_stored_file($file, 0, 0, false, $options);

}

/* Navigation API */

/**
 * Extends the global navigation tree by adding newmodule nodes if there is a relevant content
 *
 * This can be called by an AJAX request so do not rely on $PAGE as it might not be set up properly.
 *
 * @param navigation_node $navref An object representing the navigation tree node of the newmodule module instance
 * @param stdClass $course current course record
 * @param stdClass $module current newmodule instance record
 * @param cm_info $cm course module information
 */
function randomstrayquotes_extend_navigation(navigation_node $navref, stdClass $course, stdClass $module, cm_info $cm) {
    // TODO Delete this function and its docblock, or implement it.
}

/**
 * Extends the settings navigation with the newmodule settings
 *
 * This function is called when the context for the page is a newmodule module. This is not called by AJAX
 * so it is safe to rely on the $PAGE.
 *
 * @param settings_navigation $settingsnav complete settings navigation tree
 * @param navigation_node $newmodulenode newmodule administration node
 */
function randomstrayquotes_extend_settings_navigation(settings_navigation $settingsnav, navigation_node $newmodulenode=null) {
    // TODO Delete this function and its docblock, or implement it.
}

function randomstrayquotes_reset_course_form_definition(&$mform){

  $mform->addElement('header', 'forumheader', get_string('resetrandomstrayquotes', 'randomstrayquotes'));

  $mform->addElements('checkbox', 'reset_randomstrayquotes_all', get_string('resetrandomstrayquotesall', 'randomstrayquotes'));

  $mform->addElement('checkbox', 'reset_randomsrayquotes_authors', get_string('resetrandomstrayquotesauthors', 'randomstrayquotes'));
  $mform->setAdvanced('reset_randomstrayquotes_authors');
  $mform->disabledif('reset_randomstrayquotes_quotes', 'reset_randomstrayquotes_all', 'checked');

  $mform->addElement('checkbox', 'reset_randomstrayquotes_categories', get_string('resetrandomstrayquotescategories', 'randomstrayquotes'));
  $mform->setAdvanced('reset_randomstrayquotes_categories');
  $mform->disabledif( 'reset_randomstrayquotes_all', 'checked');

  $mform->addElement('checkbox', 'reset_randomstrayquotes_quotes', get_string('resetrandomstrayquotesquotes', 'randomstrayquotes'));
  $mform->setAdvanced('reset_randomstrayquotes_quotes');
  $mform->disabledif('reset_randomstrayquotes_all', 'checked');

}

function randomstrayquotes_reset_userdata($data){
  if (!empty($data->reset_randomstrayquotes)){
    if (delete_records('randomstrayquotes_categories', 'courseid', $data->courseid)){
      notify(get_string('randomstrayquotes_categories_deleted', 'randomstrayquotes'), 'notifysuccess');
    }
  }
  if (!empty($data->reset_randomstrayquotes_authors)){
    if (delete_records('randomstrayquotes_authors', 'courseid', $data->courseid)){
      notify(get_string('randomstrayquotes_authors_deleted', 'randomstrayquotes'), 'notifysuccess');
    }
  }
  if (!empty($data->reset_randomstrayquotes_quotes)){
    if (delete_records('randomstrayquotes_quotes', 'courseid', $data->courseid)){
      notify(get_string('randomstrayquotes_quotes_deleted', 'randomstrayquotes'), 'notifysuccess');
    }
  }
}

function randomstrayquotes_reset_course_form_defaults($course){
  return array('reset_randomstrayquotes_all'=>1, 'reset_randomstrayquotes_all');
}

function randomstrayquotes_get_user_grades($randomstrayquotes, $userid, $courseid){
/*
  global $CFG, $DB;
  require_once($CFG->dirroot. '/mod/randomstrayquotes/locallib.php');
  $grade_item = new grade_item(array('id'=>0, 'courseid'=>$courseid));
  grade_item::set_propertires($grade_item, $grading);

  $grading = new stdClass;
   if (!empty($userid)){
     $grading[itemname] => 'Quotes Contributions';
     $grading[iteminfo] => '';
     $grading[itemnumber] => '';
     $grading[outcomeid] => 1;
     $grading[cmid] => 0;
     $grading[id] => 0;
     $grading[courseid] => 2;
     $grading[aggregationcoef] => 0;
   }
   $grade_item->itemnuber = $uniqueitemnumber;

   $outcome = grade_outcome::fetch(array('id' =>$outcomeid));
   $grade_item->gradetype = GRADE_TYPE_SCALE;
   $grade_item->scaleid = $outcome->scaleid;
   $grade_item->insert();

   if($item = grade_item::fetch(array('itemtype'=>'mod', 'itemmodule'=>$grade_item->itemmodule, 'iteminstance'=>$grade_item->iteminstance, 'itemnumber'=>0, 'courseid'=>$COURSE->id))){
     $grade_item->set_parent($iteminstance, 'itemnumber'=>0, 'courseid'=>$COURSE->id))){
     $grade_item->move_after_sortorder($item->sortorder);
     }

   return $grading;

  if(empty($CFG->enableoutcomes)){
    return;
  }

  require_once($CFG->libdir. '/gradelib.php');

  $data = array();
  $grading_info = grade_get_grades($courseid, 'mod' $modtype, $modinstance->id, $userid);

  if(!empty($grading_info->outcomes)){
     foreach($grading_info->outcomes as $n=>$old){
       $data[$n] = 2;
     }
  }

  if (count($data) > 0){
    grade_update_outcomes('mod/' .$modtype, $courseid, 'mod', $modtype, $modinstance->id, $userid, $data);
  }

   return $grading;
   */
}
