<?php

// Roles
function Admin()
{
    return 'Admin';
}
function Manager()
{
    return 'Manager';
}
function Customer()
{
    return 'Customer';
}
function Store()
{
    return 'Store';
}
function Rider()
{
    return 'Rider';
}

// Status

function Published()
{
    return 'published';
}

function Draft()
{
    return 'draft';
}



// Order Status

function Completed()
{
    return 'completed';
}

function Canceled()
{
    return 'canceled';
}
function InProcess()
{
    return 'in-process';
}
function Refunded()
{
    return 'refunded';
}
function Pending()
{
    return 'pending';
}
function PendingPayment()
{
    return 'pending-payment';
}






// Settings
function getSetting($key)
{
    $setting = \App\Models\Setting::where('key', $key)->first();
    if (!empty($setting)) {
        if ($setting->value) {
            return $setting = unserialize($setting->value);
        }
    }
    return [];
}
