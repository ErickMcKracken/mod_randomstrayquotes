<?php

function xmldb_randomstrayquotes_upgrade($oldversion) {
    global $CFG, $DB, $OUTPUT;
    require_once(__DIR__.'/upgradelib.php');

    $dbman = $DB->get_manager();

    // Moodle v2.2.0 release upgrade line
    // Put any upgrade step following this

    // Moodle v2.3.0 release upgrade line
    // Put any upgrade step following this


    if ($oldversion < 2012061701) {
        // Fixed/updated numfiles field in assignment_submissions table to count the actual
        // number of files has been uploaded when sendformarking is disabled
        upgrade_set_timeout(600);  // increase excution time for in large sites
        $fs = get_file_storage();

        // Fetch the moduleid for use in the course_modules table
        $moduleid = $DB->get_field('modules', 'id', array('name' => 'randomstrayquotes'), MUST_EXIST);

        $selectcount = 'SELECT COUNT(s.id) ';
        $select      = 'SELECT s.id, cm.id AS cmid ';
        $query       = 'FROM {randomstrayquotes_submissions} s
                        JOIN {randomstrayquotes} a ON a.id = s.assignment
                        JOIN {course_modules} cm ON a.id = cm.instance AND cm.module = :moduleid
                        WHERE randomstrayquotestype = :randomstrayquotestype';

        $params = array('moduleid' => $moduleid, 'randomstrayquotestype' => 'upload');

        $countsubmissions = $DB->count_records_sql($selectcount.$query, $params);
        $submissions = $DB->get_recordset_sql($select.$query, $params);

        $pbar = new progress_bar('randomstrayquotesupgradenumfiles', 500, true);
        $i = 0;
        foreach ($submissions as $sub) {
            $i++;
            if ($context = context_module::instance($sub->cmid)) {
                $sub->numfiles = count($fs->get_area_files($context->id, 'mod_randomstrayquotes', 'submission', $sub->id, 'sortorder', false));
                $DB->update_record('randomstrayquotes_submissions', $sub);
            }
            $pbar->update($i, $countsubmissions, "Counting files of submissions ($i/$countsubmissions)");
        }
        $submissions->close();

        // assignment savepoint reached
        upgrade_mod_savepoint(true, 2012061701, 'randomstrayquotes');
    }

    // Moodle v2.4.0 release upgrade line
    // Put any upgrade step following this


    // Moodle v2.5.0 release upgrade line.
    // Put any upgrade step following this.


    // Moodle v2.6.0 release upgrade line.
    // Put any upgrade step following this.

    if ($oldversion < 2013121900) {
        // Define table assignment_upgrade to be created.
        $table = new xmldb_table('randomstrayquotes_upgrade');

        // Adding fields to table assignment_upgrade.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('oldcmid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('oldinstance', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('newcmid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('newinstance', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table assignment_upgrade.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Adding indexes to table assignment_upgrade.
        $table->add_index('oldcmid', XMLDB_INDEX_NOTUNIQUE, array('oldcmid'));
        $table->add_index('oldinstance', XMLDB_INDEX_NOTUNIQUE, array('oldinstance'));

        // Conditionally launch create table for assignment_upgrade.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        if ($module = $DB->get_record("modules", array("name" => "randomstrayquotes"))) {
            $DB->set_field("modules", "visible", "0", array("id" => $module->id)); // Hide module.
        }

        $count = $DB->count_records('randomstrayquotes');
        if ($count) {
            mod_randomstrayquotes_pending_upgrades_notification($count);
        }

        // Assignment savepoint reached.
        upgrade_mod_savepoint(true, 2013121900, 'randomstrayquotes');
    }

    // Moodle v2.7.0 release upgrade line.
    // Put any upgrade step following this.

    // Moodle v2.8.0 release upgrade line.
    // Put any upgrade step following this.

    // Moodle v2.9.0 release upgrade line.
    // Put any upgrade step following this.

    // Moodle v3.0.0 release upgrade line.
    // Put any upgrade step following this.

    return true;
}

