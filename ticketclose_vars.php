<?php

add_hook('TicketClose', 1, function ($vars) {
    // Convert the $vars array to a string format
    $varsAsString = print_r($vars, true);

    // Log the output in WHMCS' module log
    logModuleCall('hook_output_vars', 'TicketClose Variables', $vars, $varsAsString);
});
