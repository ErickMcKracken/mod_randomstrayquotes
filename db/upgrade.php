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
 * This file keeps track of upgrades to the newmodule module
 *
 * Sometimes, changes between versions involve alterations to database
 * structures and other major things that may break installations. The upgrade
 * function in this file will attempt to perform all the necessary actions to
 * upgrade your older installation to the current version. If there's something
 * it cannot do itself, it will tell you what you need to do.  The commands in
 * here will all be database-neutral, using the functions defined in DLL libraries.
 *
 * @package    mod_randomstrayquotes
 * @copyright  2018 Eric Frenette <frenette.eric@uqam.ca>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute newmodule upgrade from the given old version
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_randomstrayquotes_upgrade($oldversion) {
    global $DB;

 $dbman = $DB->get_manager();
  if ($oldversion < 2018083114) {
    //define the fields to be added to randomstrayquotes_authors
     $table = new xmldb_table('randomstrayquotes_authors');
     $fields = [
       new xmldb_field('time_added', XMLDB_TYPE_CHAR, 255, XMLDB_UNSIGNED, null, null, null, null),
       new xmldb_field('time_updated', XMLDB_TYPE_CHAR, 255, XMLDB_UNSIGNED, null, null, null, null),
       new xmldb_field('user_id', XMLDB_TYPE_INTEGER,20, XMLDB_UNSIGNED, null, null, null, null)
     ];

     foreach ($fields as $field) {
       if (!$dbman->field_exists($table, $field)){
         $dbman->add_field($table, $field);
       }
     }

     //define the fields to be added to randomstrayquotes_categories
      $table = new xmldb_table('randomstrayquotes_categories');
      $fields = [
        new xmldb_field('time_added', XMLDB_TYPE_CHAR, 255, XMLDB_UNSIGNED, null, null, null, null),
        new xmldb_field('time_updated', XMLDB_TYPE_CHAR, 255, XMLDB_UNSIGNED, null, null, null, null),
        new xmldb_field('user_id', XMLDB_TYPE_INTEGER,20, XMLDB_UNSIGNED, null, null, null, null)
      ];

      foreach ($fields as $field) {
        if (!$dbman->field_exists($table, $field)){
          $dbman->add_field($table, $field);
        }
      }
      upgrade_mod_savepoint(true, 2018083114, 'randomstrayquotes');
  }

    return true;
}
