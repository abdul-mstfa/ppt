<?php

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

use WHMCS\Database\Capsule;

// pulling the reply admin from the ticket reply table to the ticket close table

add_hook('TicketAdminReply', 1, function ($vars) {
    $ticketId = $vars['ticketid'];
    $adminId = $vars['adminid'];

    try {
        // Update the custom field 'closing_admin_id' with the current admin ID
        Capsule::table('tbltickets')->where('id', $ticketId)->update([
            'closing_admin_id' => $adminId
        ]);
    } catch (\Exception $e) {
        logActivity("Ticket Reply hook error: " . $e->getMessage());
    }
});
