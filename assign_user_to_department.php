<?php

use WHMCS\Database\Capsule;

add_hook('ClientAdd', 1, function ($vars) {
    $id = $vars['id'];
    $roled = 4; // Replace with the desired support department ID

    try {
        // Check if the client registered using the custom form
        $client_notes = Capsule::table('tbladmins')
            ->where('id', $id)
            ->value('notes');

        $custom_signup = strpos($client_notes, 'Custom Signup: true') !== false;

        if ($custom_signup) {
            // Check if the client is already assigned to the department
            $exists = Capsule::table('tbladmins')
                ->where('clientid', $id)
                ->where('roleid', $roleid)
                ->count();

            // If not, insert a new record
            if (!$exists) {
                Capsule::table('tbladmins')
                    ->insert([
                        'id' => $id,
                        'roleid' => $roleid,
                    ]);
            }
        }
    } catch (Exception $e) {
        // Log the error
        logActivity('Error assigning client to department: ' . $e->getMessage());
    }
});
