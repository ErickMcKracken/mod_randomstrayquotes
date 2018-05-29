<?php

defined('MOODLE_INTERNAL') || die();

/**
 * Inform admins about assignments that still need upgrading.
 */
function mod_randomstrayquotes_pending_upgrades_notification($count) {
    $admins = get_admins();

    if (empty($admins)) {
        return;
    }

    $a = new stdClass;
    $a->count = $count;
    $a->docsurl = get_docs_url('randomstrayquotes_upgrade_tool');
    foreach ($admins as $admin) {
        $message = new stdClass();
        $message->component         = 'moodle';
        $message->name              = 'notices';
        $message->userfrom          = \core_user::get_noreply_user();
        $message->userto            = $admin;
        $message->smallmessage      = get_string('pendingupgrades_message_small', 'mod_randomstrayquotes');
        $message->subject           = get_string('pendingupgrades_message_subject', 'mod_randomstrayquotes');
        $message->fullmessage       = get_string('pendingupgrades_message_content', 'mod_randomstrayquotes', $a);
        $message->fullmessagehtml   = get_string('pendingupgrades_message_content', 'mod_randomstrayquotes', $a);
        $message->fullmessageformat = FORMAT_PLAIN;
        $message->notification      = 1;
        message_send($message);
    }
}


