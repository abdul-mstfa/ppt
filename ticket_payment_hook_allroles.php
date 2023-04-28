<?php

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

use WHMCS\Database\Capsule;

add_hook('TicketClose', 1, function ($vars) {
    $ticketId = $vars['ticketid'];
    $adminId = $vars['adminid'];

    try {
        $insertedId = Capsule::table('tbl_ticket_payments')->insertGetId([
            'admin_id' => $adminId,
            'ticket_id' => $ticketId,
            'payment_amount' => 10,
            'created_at' => Capsule::raw('NOW()'),
            
        ]);

        logActivity("Ticket Payment hook executed: Inserted record with ID " . $insertedId);
    } catch (\Exception $e) {
        logActivity("Ticket Payment hook error: " . $e->getMessage());
    }
});
