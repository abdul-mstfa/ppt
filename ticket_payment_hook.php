<?php
use WHMCS\Database\Capsule;

$allowed_admin_roles = [1, 2, 3, 4];

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function getAdminRoleId($admin_id) {
    try {
        $admin = Capsule::table('tbladmins')->where('id', $admin_id)->first();
        if ($admin) {
            return $admin->roleid;
        } else {
            return null;
        }
    } catch (Exception $e) {
        logSystem("Error fetching admin role: " . $e->getMessage());
        return null;
    }
}

add_hook('TicketClose', 0, function($vars) use ($allowed_admin_roles) {


    logSystem("TicketClose hook started"); //for debug in the system log

    $admin_id = $vars['adminid'];
    $ticket_id = $vars['ticketid'];

    logSystem("got the admin and ticket id's"); //for debug in the system log

    // Get the admin's role ID
    $admin_role_id = getAdminRoleId($admin_id);

    // Check if the admin's role is allowed
    if (!in_array($admin_role_id, $allowed_admin_roles)) {
        return;
    }

    // Set the payment amount per closed ticket
    $payment_per_ticket = 10;


    // Connect to WHMCS database
    try {
        $pdo = Capsule::connection()->getPdo();
    } catch (Exception $e) {
        logSystem("Error connecting to database: " . $e->getMessage());
        return;
    }

    logSystem("insertion into db"); //for debug in the system log

    // Insert closed ticket information into a custom table
    try {
        $sql = "INSERT INTO tbl_ticket_payments (admin_id, ticket_id, payment_amount, created_at) VALUES (?, ?, ?, NOW())";
        $statement = $pdo->prepare($sql);
        $statement->execute([$admin_id, $ticket_id, $payment_per_ticket]);
    } catch (Exception $e) {
        logSystem("Error inserting ticket payment data: " . $e->getMessage());
    }

    logSystem("TicketClose hook has run. Posted Vars: ". print_r($vars, true));
});
