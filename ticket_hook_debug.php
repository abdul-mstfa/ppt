<?php

use WHMCS\Database\Capsule;

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

add_hook('TicketClose', 1, function($vars) {
    logSystem("Simple TicketClose hook executed");
});
