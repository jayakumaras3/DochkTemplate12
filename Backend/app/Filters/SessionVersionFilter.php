<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class SessionVersionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        $id_user = $session->get('id_user');

        // Not logged in
        if (!$id_user) {

            $session->destroy();

            if ($request->isAJAX()) {

                return service('response')
                    ->setStatusCode(401)
                    ->setJSON([
                        'expired' => true,
                        'redirect' => base_url('ang/login'),
                        'message'  => 'Session expired'
                    ]);
            }

            return redirect()->to(
                base_url('ang/login?success=Session expired. Please login again.')
            );
        }

        $usersModel = model('App\Models\User_login\Users_model');

        $dbUser = $usersModel->usersedit_view($id_user);

        // User deleted
        if (!$dbUser || empty($dbUser[0])) {

            $session->destroy();

            if ($request->isAJAX()) {
                return service('response')
                    ->setStatusCode(401)
                    ->setJSON([
                        'expired' => true,
                        'redirect' => base_url('ang/login'),
                        'message' => 'Session expired'
                    ]);
            }

            return redirect()->to(base_url('ang/login'));
        }
        if ($session->get('is_impersonating')) {

            $admin = $usersModel->usersedit_view(
                $session->get('org_id_user')
            );

            $adminVersion = (int)($admin[0]['session_version'] ?? 1);

            if ($adminVersion !== (int)$session->get('impersonator_version')) {

                $session->destroy();

                if ($request->isAJAX()) {
                    return service('response')
                        ->setStatusCode(401)
                        ->setJSON([
                            'expired' => true,
                            'redirect' => base_url('ang/login'),
                            'message' => 'Session expired'
                        ]);
                }

                return redirect()->to(base_url('ang/login'));
            }
        }

        $dbVersion = (int)($dbUser[0]['session_version'] ?? 1);

        $sessionVersion = (int)$session->get('session_version');

        // Session invalidated
        if ($dbVersion !== $sessionVersion) {

            $session->destroy();

            if ($request->isAJAX()) {
                return service('response')
                    ->setStatusCode(401)
                    ->setJSON([
                        'expired' => true,
                        'redirect' => base_url('ang/login'),
                        'message' => 'Session expired'
                    ]);
            }

            return redirect()->to(base_url('ang/login'));
        }
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {}
}
