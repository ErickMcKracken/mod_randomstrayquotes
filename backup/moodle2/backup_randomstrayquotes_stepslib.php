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
 * Define all the backup steps that will be used by the backup_newmodule_activity_task
 *
 * @package   mod_newmodule
 * @category  backup
 * @copyright 2016 Your Name <your@email.address>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Define the complete newmodule structure for backup, with file and id annotations
 *
 * @package   mod_newmodule
 * @category  backup
 * @copyright 2016 Your Name <your@email.address>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_randomstrayquotes_activity_structure_step extends backup_activity_structure_step {

    /**
     * Defines the backup structure of the module
     *
     * @return backup_nested_element
     */
    protected function define_structure() {

        // Get know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');

        // Define the root element describing the newmodule instance.
        $randomstrayquotes = new backup_nested_element('randomstrayquotes', array('id'), array(
            'name', 'intro', 'introformat', 'grade'));

        // Adding the elements,

        $categories = new_nested_element('categories');

        $category = new_backup_nested_element('category', array('id', array('category_name', 'user_id', 'course_id', 'time_added', 'time_updated'));

        $authors = new_nested_element('authors');

        $author = new_backup_nested_element('author', array('id', array('author_name', 'author_picture', 'course_id', 'course_id', 'user_id', 'time_added', 'time_updated'));

        $randomstrayquotes_quotes = new_nested_element('quotes');

        $randomstrayquotes_quotes = new_backup_nested_element('quote', array('id'), array('quote', 'source', 'category_id', 'visible', 'user_id', 'author_id', 'course_id', 'time_added', 'time_updated'));


        // Building the tree

        $randomstrayquotes->add_child($categories);

        $categories->add_child($category);

        $category->add_child($quotes);

        $quotes->add_child ($authors);

        $authors->add_child ($author);

        // Define data sources.
        $randomstrayquotes->set_source_table('randomstrayquotes', array('id' => backup::VAR_ACTIVITYID));

       //If we are including user infos we define the source definitions

        if ($userinfo){
            $categories->set_source_sql('
                        SELECT *
                        FROM {randomstrayquotes_categories}
                        WHERE instance_id = ?'
                        array(backup::VAR_PARENTID));
                      }

           $quote->set_source_table('randomstrayquotes_quotes', array('categories' => backup::VAR_PARENTID), 'id ASC');
           $authors->set_source_table('randomstrayquotes_authors', array('quotes' => backup::VAR_PATRENTID));

        // If we were referring to other tables, we would annotate the relation
        // with the element's annotate_ids() method.

        $categories->annotate_ids('user', 'user_id');
        $authors->annotate_ids('user', 'user_id');
        $quotes->annotate_ids('user', 'user_id');

        // Define file annotations (we do not use itemid in this example).
        $randomstrayquotes->annotate_files('mod_randomstrayquotes', 'intro', null);
        $authors->annotate_files('mod_randomstrayquotes', 'author_picture');

        // Return the root element (newmodule), wrapped into standard activity structure.
        return $this->prepare_activity_structure($randomstrayquotes);
    }
}
