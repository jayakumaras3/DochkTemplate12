<?php
function requireAdmin()
{
    $session = session();
    $user = $session->get('userData');

    if (!$session->get('isLoggedIn') || !$user) {
        return service('response')
            ->setStatusCode(401)
            ->setJSON(['error' => 'Unauthorized'])
            ->send();
        exit;
    }

    if ($user['userlevel'] !== 'admin') {
        return service('response')
            ->setStatusCode(403)
            ->setJSON(['error' => 'Forbidden: Admin access required'])
            ->send();
        exit;
    }
}
