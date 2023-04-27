<?php
use WHMCS\Database\Capsule;

$allowed_admin_roles = [4];

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
        logActivity("Error fetching admin role: " . $e->getMessage());
        return null;
    }
}

add_hook('TicketClose', 0, function($vars) use ($allowed_admin_roles) {


    logActivity("TicketClose hook started"); //for debug in the system log

    $admin_id = $vars['adminid'];
    $ticket_id = $vars['ticketid'];

    logActivity("got the admin and ticket id's. admin id:", print($admin_id)); //for debug in the system log; improper syntaxt -> not printing the variable

    // Get the admin's role ID
    $admin_role_id = getAdminRoleId($admin_id);

    logActivity("got the admin role id: ", print($admin_role_id)); //for debug in the system log; improper syntaxt -> not printing the variable

    // Check if the admin's role is allowed
    if (!in_array($admin_role_id, $allowed_admin_roles)) {
        return;
    }

    try {
        $insertedId = Capsule::table('tbl_ticket_payments')->insertGetId([
            'ticket_id' => $ticketId,
            'admin_id' => $adminId,
            'amount' => 10,
            'created_at' => Capsule::raw('NOW()'),
            'updated_at' => Capsule::raw('NOW()'),
        ]);

        logActivity("Ticket Payment hook executed: Inserted record with ID " . $insertedId);
    } catch (\Exception $e) {
        logActivity("Ticket Payment hook error: " . $e->getMessage());
    }
    
    // Set the payment amount per closed ticket
    $payment_per_ticket = 10;


    // Connect to WHMCS database
    try {
        $pdo = Capsule::connection()->getPdo();
    } catch (Exception $e) {
        logActivity("Error connecting to database: " . $e->getMessage());
        return;
    }

    logActivity("insertion into db"); //for debug in the system log

    // Insert closed ticket information into a custom table
    try {
        $sql = "INSERT INTO tbl_ticket_payments (admin_id, ticket_id, payment_amount, created_at) VALUES (?, ?, ?, NOW())";
        $statement = $pdo->prepare($sql);
        $statement->execute([$admin_id, $ticket_id, $payment_per_ticket]);
    } catch (Exception $e) {
        logActivity("Error inserting ticket payment data: " . $e->getMessage());
    }

    logActivity("TicketClose hook has run. Posted Vars: ". print_r($vars, true));
});
