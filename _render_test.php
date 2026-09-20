<?php

$users = \App\Models\User::with('roles')->get();
echo 'Users in DB: ' . $users->count() . PHP_EOL;

if ($users->count() > 0) {
    $user = $users->first();
    echo 'Testing user: ' . $user->fullname . ' <' . $user->email . '> roles=[' . $user->roles->pluck('name')->implode(',') . ']' . PHP_EOL;

    $html = view('dashboard', ['user' => $user])->render();
    echo 'View rendered OK (length ' . strlen($html) . ')' . PHP_EOL;

    echo str_contains($html, e($user->fullname)) ? 'PASS: fullname shown' . PHP_EOL : 'FAIL: fullname missing' . PHP_EOL;
    echo str_contains($html, e($user->email)) ? 'PASS: email shown' . PHP_EOL : 'FAIL: email missing' . PHP_EOL;
    echo str_contains($html, 'profile-dropdown-logout') && str_contains($html, '/logout') ? 'PASS: logout form present' . PHP_EOL : 'FAIL: logout form missing' . PHP_EOL;
    echo str_contains($html, '_token') ? 'PASS: CSRF token present' . PHP_EOL : 'FAIL: CSRF token missing' . PHP_EOL;
} else {
    echo 'No users in DB - skipping render test' . PHP_EOL;
}