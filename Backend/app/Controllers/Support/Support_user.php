<?php

namespace App\Controllers\Support;

use App\Controllers\BaseController;
use App\Models\Support\Support_model;

#[\AllowDynamicProperties]
class Support_user extends BaseController
{
    private $db;
    public function __construct()
    {
        $this->support_model = new Support_model();
    }

    public function index()
    {
        $data = [];
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data['form_title'] = 'Support Ticket';
        $data['adminView'] = 2;
        $id_user = session()->get('id_user');
        $data['myTickets'] = $this->support_model->getMyTickets($id_user);
        $data['total_tickets'] = $this->support_model->getTicketCount($id_user, 1);
        $data['replied'] = $this->support_model->getTicketCount($id_user, 2);
        $data['reOpened'] = $this->support_model->getTicketCount($id_user, 4);
        $data['closed'] = $this->support_model->getTicketCount($id_user, 5);

        echo view('templates/header_view', $data);
        echo view('support/support_user_view');
        echo view('templates/footer_view');
    }
    public function viewCreateTicket()
    {
        $data = [];
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        helper(['form']);
        $data['header'] = 'Create Support Ticket';
        $data['header2'] = 'My Support Tickets';
        $data['form_title'] = 'Support Ticket';
        echo view('templates/header_view', $data);
        echo view('support/support_add_ticket');
        echo view('templates/footer_view');
    }

    public function createNewTicket()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if ($this->request->getPost()) {
            $newdata = [
                'description' => $this->request->getVar('description'),
                'status' => '1',
                'createdby' => session()->get('id_user'),
                'createdon' => time(),
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $result = $this->support_model->createTicket($newdata);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0046'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }

            return redirect()->to(base_url() . '/Support/Support_user');
        }
    }

    // public function viewTicketDetails()
    // {
    //     if ($response =  $this->requireRole(['3'])) {
    //         return $response;
    //     }
    //     $data = [];
    //     helper(['form']);
    //     if ($this->request->getPost()) {
    //         $data['header'] = 'My Support Tickets';
    //         $data['header_link'] = 'Support/Support_user';
    //         $data['sub_header_1'] = 'Ticket Details';
    //         $ticketID = $this->request->getVar('ticketID');
    //         $data['replytype'] = $this->request->getVar('type_of_access');
    //         $data['ticketDescription'] = $this->support_model->getTicketsDetails($ticketID);
    //         $data['getTicketReplies'] = $this->support_model->getTicketReplies($ticketID);
    //         echo view('templates/header_view', $data);
    //         echo view('support/support_ticket_view');
    //         echo view('templates/footer_view');
    //     } else {
    //         return redirect()->to(base_url() . '/Support/support');
    //     }
    // }
    public function viewTicketDetails()
    {
        if ($response = $this->requireRole(['3'])) {
            return $response;
        }

        helper(['form']);

        if ($this->request->getPost()) {
            $data['header'] = 'My Support Tickets';
            $data['header_link'] = 'Support/Support_user';
            $data['sub_header_1'] = 'Ticket Details';

            $ticketID = $this->request->getVar('ticketID');
            $data['replytype'] = $this->request->getVar('type_of_access');

            $userID = session()->get('id_user');

            $data['ticketDescription'] = $this->support_model->getTicketsDetails($ticketID, $userID);
            $data['getTicketReplies'] = $this->support_model->getTicketReplies($ticketID, $userID);

            if (empty($data['ticketDescription'])) {
                return redirect()->to(base_url() . '/Support/support')
                    ->with('error', 'Unauthorized access');
            }

            $data['ticketImages'] = $this->listTicketImages((int) session()->get('client'), (int) $ticketID);

            echo view('templates/header_view', $data);
            echo view('support/support_ticket_view');
            echo view('templates/footer_view');
        } else {
            return redirect()->to(base_url() . '/Support/support');
        }
    }


    // public function replyTicket()
    // {
    //     if ($response =  $this->requireRole(['3'])) {
    //         return $response;
    //     }
    //     $data = [];
    //     helper(['form']);
    //     if ($this->request->getPost()) {

    //         $ticketID = $this->request->getVar('ticketID');
    //         $replytype = $this->request->getVar('replytype');
    //         // print_r($replytype);
    //         // exit();
    //         $newdata = [
    //             'status' => $replytype,
    //         ];
    //         $result = $this->support_model->updateTicketStatus($newdata, $ticketID);
    //         $replies = [
    //             'ticket_id' => $ticketID,
    //             'replies' => $this->request->getVar('replies'),
    //             'status' => '1',
    //             'createdby' => session()->get('id_user'),
    //             'createdon' => time(),
    //             'last_updated_by' => session()->get('id_user'),
    //             'last_updated_on' => time(),
    //         ];

    //         $result = $this->support_model->replyTicketData($replies);
    //         // print_r($replytype);
    //         // exit();
    //         if ($result) {
    //             session()->setFlashdata('success', lang('Messages.Success_0008'));
    //         } else {
    //             session()->setFlashdata('error', lang('Messages.Error_0001'));
    //             session()->setFlashdata('alert-class', 'alert-danger');
    //         }

    //         if ($replytype == 2) {
    //             return redirect()->to(base_url() . 'Support/Support/admin_support');
    //         }
    //         if ($replytype == 3) {
    //             return redirect()->to(base_url() . 'Support/Support_user');
    //         } else {
    //             return redirect()->to(base_url() . 'Support/Support_user');
    //         }
    //     }
    // }
    public function replyTicket()
    {
        if ($response = $this->requireRole(['3'])) {
            return $response;
        }

        helper(['form']);

        if ($this->request->getPost()) {

            $ticketID = $this->request->getVar('ticketID');
            $replytype = $this->request->getVar('replytype');
            $userID = session()->get('id_user');

            //  Validate ownership BEFORE doing anything
            // $ticket = $this->support_model->getUserTicket($ticketID, $userID);

            // if (empty($ticket)) {
            //     return redirect()->to(base_url() . 'Support/Support_user')
            //         ->with('error', 'Unauthorized access');
            // }

            //  Safe update
            $newdata = [
                'status' => $replytype,
            ];

            $this->support_model->updateTicketStatus($newdata, $ticketID, $userID);

            //  Safe reply insert
            $replies = [
                'ticket_id' => $ticketID,
                'replies' =>  htmlspecialchars($this->request->getVar('replies'), ENT_QUOTES, 'UTF-8'),
                'status' => '1',
                'createdby' => $userID,
                'createdon' => time(),
                'last_updated_by' => $userID,
                'last_updated_on' => time(),
            ];

            $result = $this->support_model->replyTicketData($replies);

            if ($replytype == 2) {
                return redirect()->to(base_url() . 'Support/Support/admin_support');
            }
            if ($replytype == 3) {
                return redirect()->to(base_url() . 'Support/Support_user');
            } else {
                return redirect()->to(base_url() . 'Support/Support_user');
            }
        }
    }



    public function notificatoins()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        $userlevel = session('userlevel');
        $arrayuserlevel = array_map('intval', explode(',', $userlevel) ?? '');
        if (in_array('6', $arrayuserlevel)) {
            $data = [
                'link1' => '',
                'link1_name' => '',
                'link2' => '',
                'link2_name' => '',
                'link3_name' => 'Notifications'
            ];
            $data['header'] = 'Notifications';
            $data['adminView'] = session()->get('client');
            if ($data['adminView'] == 1) {
                $data['latest_notifications'] = $this->support_model->getAllNotifications();
                echo view('templates/header_view', $data);
                //   echo view('settings/admin_left_menu', $data);
                echo view('support/notifications');
                echo view('templates/footer_view');
            }
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url() . '/my_training');
        }
    }
    public function view_notificatoins()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];

        $data['header'] = 'Notifications';
        $data['adminView'] = session()->get('client');

        $data['latest_notifications'] = $this->support_model->getAllNotifications();
        echo view('templates/header_view', $data);
        echo view('support/notification_view');
        echo view('templates/footer_view');
    }


    public function view_detailed_notification()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        $notification_id = $this->request->getVar('notification_id');
        if (!isset($notification_id)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url() . '/Support/Support_user/view_notificatoins');
        } else {
            echo $this->request->getVar('notification_id');
            exit();
            $data['adminView'] = session()->get('client');
            $data['notification_details'] = $this->support_model->getNotificationDetails($notification_id);
            echo view('templates/header_view', $data);
            echo view('support/notification_details');
            echo view('templates/footer_view');
        }
    }

    public function add_notificatoins()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        if (in_array('6', $arrayuserlevel)) {
            $data = [];
            helper(['form']);
            $data['post'] = '';

            echo view('templates/header_view', $data);
            echo view('support/notification_add', $data);
            echo view('templates/footer_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url() . '/my_training');
        }
    }


    public function add_notificatoin_to_db()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if ($this->request->getPost()) {
            $notifications = [
                'short_name' => $this->request->getVar('short_desc'),
                'start_date' => $this->request->getVar('start_date'),
                'end_date' => $this->request->getVar('end_date'),
                'client_id' => $this->request->getVar('client_specific'),
                'detail_description' => $this->request->getVar('description'),
                'status' => '1',
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $result = $this->support_model->add_notifications_to_db($notifications);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }

            return redirect()->to(base_url() . '/Support/Support_user/notificatoins');
        }
    }
    public function delete_notificatoins()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if ($this->request->getPost()) {
            $notification_id = $this->request->getVar('notification_id');
            $notifications = [
                'status' => '0',
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $result = $this->support_model->delete_notifications_in_db($notification_id, $notifications);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0005'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            return redirect()->to(base_url() . '/Support/Support_user/notificatoins');
        }
        echo view('templates/header_view', $data);
        echo view('support/notification_edit', $data);
        echo view('templates/footer_view');
    }


    public function AdminviewTicketDetails()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if ($this->request->getPost()) {
            $data['header'] = 'Admin Support';
            $data['header_link'] = 'Support/Support/admin_support';
            $data['sub_header_1'] = 'Ticket Details';
            $ticketID = $this->request->getVar('ticketID');
            // replytype
            $data['replytype'] = $this->request->getVar('type_of_access');
            $id_user = session()->get('id_user');
            $data['ticketDescription'] = $this->support_model->getTicketsDetails($ticketID, $id_user);

            $data['getTicketReplies'] = $this->support_model->getTicketReplies($ticketID);
            $data['ticketImages'] = $this->listTicketImages((int) session()->get('client'), (int) $ticketID);
            echo view('templates/header_view', $data);
            echo view('support/support_ticket_view');
            echo view('templates/footer_view');
        } else {
            return redirect()->to(base_url() . '/Support/Support_user');
        }
    }

    // Handles the "attach an image" tile on the ticket details page (both the
    // user-facing viewTicketDetails and the admin AdminviewTicketDetails share
    // this same upload endpoint and the same support_ticket_view.php).
    public function uploadTicketImage()
    {
        if ($response = $this->requireRole(['3', '6'])) {
            return $response;
        }

        helper(['form']);

        if (!$this->request->getPost()) {
            return redirect()->to(base_url() . '/Support/support');
        }

        $ticketID = (int) $this->request->getPost('ticketID');
        [$hasAccess, $isAdmin, $userID] = $this->resolveTicketAccess($ticketID);

        if (!$hasAccess) {
            return redirect()->to(base_url() . '/Support/support')
                ->with('error', 'Unauthorized access');
        }

        $extraData = [];
        $rules = [
            'ticket_image' => [
                'label' => 'Image',
                'rules' => 'uploaded[ticket_image]|max_size[ticket_image,1024]|is_image[ticket_image]|mime_in[ticket_image,image/jpeg,image/png]|ext_in[ticket_image,jpg,jpeg,png]',
            ],
        ];

        if (!$this->validate($rules)) {
            $extraData['uploadValidation'] = $this->validator;
        } else {
            $file = $this->request->getFile('ticket_image');
            $clientId = (int) session()->get('client');

            // Belt-and-braces on top of is_image[]/mime_in[]: decode the file's
            // own bytes to confirm it is genuinely an image, not a renamed/
            // disguised payload that merely satisfies the MIME/extension checks.
            if ($file && $file->isValid() && !$file->hasMoved() && $clientId > 0 && @getimagesize($file->getTempName()) !== false) {
                $uploadDir = FCPATH . 'assets/assets/uploads/Support/' . $clientId . '/' . $ticketID;

                if (!is_dir($uploadDir) && !@mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                    session()->setFlashdata('error', 'Could not create the upload folder on the server (permission denied). Contact an administrator.');
                    $this->renderTicketDetails($ticketID, $isAdmin, $userID, $extraData);
                    return;
                }

                // getRandomName() sidesteps the original filename entirely, so there's
                // no path-traversal / overwrite / double-extension risk from user input.
                $file->move($uploadDir, $file->getRandomName());

                session()->setFlashdata('success', 'Image uploaded successfully.');
            } else {
                session()->setFlashdata('error', 'The uploaded file is not a valid image.');
            }
        }

        $this->renderTicketDetails($ticketID, $isAdmin, $userID, $extraData);
    }

    // Handles the delete ("x") control on each attachment thumbnail.
    public function deleteTicketImage()
    {
        if ($response = $this->requireRole(['3', '6'])) {
            return $response;
        }

        helper(['form']);

        if (!$this->request->getPost()) {
            return redirect()->to(base_url() . '/Support/support');
        }

        $ticketID = (int) $this->request->getPost('ticketID');
        [$hasAccess, $isAdmin, $userID] = $this->resolveTicketAccess($ticketID);

        if (!$hasAccess) {
            return redirect()->to(base_url() . '/Support/support')
                ->with('error', 'Unauthorized access');
        }

        $clientId = (int) session()->get('client');
        $uploadDir = FCPATH . 'assets/assets/uploads/Support/' . $clientId . '/' . $ticketID;

        // basename() strips any directory component (../, absolute paths, etc.) from the
        // submitted filename, and then we re-confirm the resolved real path still lives
        // inside this exact ticket's own folder before ever touching the filesystem -
        // belt-and-braces against path traversal even though basename() alone already
        // defeats it.
        $requestedName = basename((string) $this->request->getPost('filename'));
        $targetPath = realpath($uploadDir . '/' . $requestedName);
        $uploadDirReal = realpath($uploadDir);

        if (
            $requestedName !== ''
            && preg_match('/^[A-Za-z0-9_\-]+\.(jpg|jpeg|png)$/i', $requestedName)
            && $targetPath !== false
            && $uploadDirReal !== false
            && strpos($targetPath, $uploadDirReal . DIRECTORY_SEPARATOR) === 0
            && is_file($targetPath)
        ) {
            unlink($targetPath);
            session()->setFlashdata('success', 'Image deleted successfully.');
        } else {
            session()->setFlashdata('error', 'Image not found.');
        }

        $this->renderTicketDetails($ticketID, $isAdmin, $userID);
    }

    // Shared server-side access check for the ticket attachment actions above -
    // independent of whatever the page happened to display, so it can't be
    // fooled by tampering with the visible ticketID form field alone. Regular
    // users may only touch tickets they created; admins may touch any ticket.
    private function resolveTicketAccess(int $ticketID): array
    {
        $userID = session()->get('id_user');
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = array_map('intval', explode(',', (string) $userlevel));
        $isAdmin = in_array(6, $arrayuserlevel, true);

        if ($isAdmin) {
            $ticketRows = $this->support_model->getTicketsDetails($ticketID, $userID);
        } else {
            $ownedTicket = $this->support_model->getUserTicket($ticketID, $userID);
            $ticketRows = $ownedTicket ? [$ownedTicket] : [];
        }

        $hasAccess = $ticketID > 0 && !empty($ticketRows);

        return [$hasAccess, $isAdmin, $userID];
    }

    // This endpoint only exists as a POST target, so a redirect back to
    // viewTicketDetails()/AdminviewTicketDetails() would arrive as a GET with
    // no body and bounce straight back out to the ticket list. Re-render the
    // same ticket details page in place instead, same as those two actions do.
    private function renderTicketDetails(int $ticketID, bool $isAdmin, $userID, array $extraData = [])
    {
        $data = $extraData;
        $data['header'] = $isAdmin ? 'Admin Support' : 'My Support Tickets';
        $data['header_link'] = $isAdmin ? 'Support/Support/admin_support' : 'Support/Support_user';
        $data['sub_header_1'] = 'Ticket Details';
        $data['replytype'] = $this->request->getPost('type_of_access');
        $data['ticketDescription'] = $this->support_model->getTicketsDetails($ticketID, $userID);
        $data['getTicketReplies'] = $this->support_model->getTicketReplies($ticketID);
        $data['ticketImages'] = $this->listTicketImages((int) session()->get('client'), $ticketID);

        echo view('templates/header_view', $data);
        echo view('support/support_ticket_view');
        echo view('templates/footer_view');
    }

    // Lists previously uploaded images for a ticket by reading the upload folder
    // directly (no DB table backs these attachments) - only ever called with the
    // integer client id / ticket id already resolved server-side above, never with
    // raw request input, so this can't be used for path traversal.
    private function listTicketImages(int $clientId, int $ticketId): array
    {
        if ($clientId <= 0 || $ticketId <= 0) {
            return [];
        }

        $relativeDir = 'assets/assets/uploads/Support/' . $clientId . '/' . $ticketId;
        $dir = FCPATH . $relativeDir;

        if (!is_dir($dir)) {
            return [];
        }

        $images = [];
        foreach (['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'] as $ext) {
            foreach (glob($dir . '/*.' . $ext) ?: [] as $filePath) {
                $images[] = [
                    'name' => basename($filePath),
                    'url'  => base_url($relativeDir . '/' . basename($filePath)),
                ];
            }
        }

        return $images;
    }
}
