<?php

namespace App\Controllers\Social;

use App\Controllers\BaseController;
use App\Models\Social\Post_model;

#[\AllowDynamicProperties]
class Post extends BaseController
{
    public function __construct()
    {
        $this->is_session_available();
        $this->Post_model = new Post_model();
    }

    private function is_session_available()
    {
    }

    public function index()
    {
        helper(['form', 'time']); // load both form and your new helper

        $client = session()->get('client');
        $user_id = session()->get('id_user');
        $data['user_id'] = $user_id;
        if (isset($_POST['course_id'])) {
            $data['course_id'] = $_POST['course_id'];
            $_SESSION['course_id'] = $data['course_id'];
        } else if (isset($_SESSION['course_id'])) {
            $data['course_id'] = $_SESSION['course_id'];
        } else {
            $data['course_id'] = 0;
        }
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 2;
        }
        // print($data['course_id']);
        // exit();
        $data['my_all_post'] = $this->Post_model->getPostCount($user_id, $data['course_id']);
        $active_posts = $this->Post_model->get_active_posts($client, $user_id, $data['course_id']);
        $replies = [];

        foreach ($active_posts as &$post) {
            $post['time_ago'] = time_elapsed_string('@' . $post['last_updated_on']);
            $post_replies = $this->Post_model->getpost_replies($post['social_id']);

            foreach ($post_replies as &$reply) {
                $reply['time_ago'] = time_elapsed_string('@' . $reply['last_updated_on']);
            }

            $replies[$post['social_id']] = $post_replies;
        }

        $data['active_posts'] = $active_posts;
        $data['replies'] = $replies;
        $this->Post_model->updatePostCount($user_id);
        echo view('templates/header_view', $data);
        echo view('social/posts', $data);
        echo view('templates/footer_view');
    }
    function posts_list()
    {
        $data = [];
        if (isset($_POST['course_id'])) {
            $data['course_id'] = $_POST['course_id'];
            $_SESSION['course_id'] = $data['course_id'];
        } else if (isset($_SESSION['course_id'])) {
            $data['course_id'] = $_SESSION['course_id'];
        } else {
            $data['course_id'] = 0;
        }
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 2;
        }
        helper(['form', 'time']); // load both form and your new helper

        $client = session()->get('client');
        $user_id = session()->get('id_user');
        $data['user_id'] = $user_id;

        $active_posts = $this->Post_model->get_active_full_posts($client, $user_id, $data['course_id']);
        $replies = [];

        foreach ($active_posts as &$post) {
            $post['time_ago'] = time_elapsed_string('@' . $post['last_updated_on']);
            $post_replies = $this->Post_model->getAllpost_replies($post['social_id']);

            foreach ($post_replies as &$reply) {
                $reply['time_ago'] = time_elapsed_string('@' . $reply['last_updated_on']);
            }

            $replies[$post['social_id']] = $post_replies;
        }

        $data['active_posts'] = $active_posts;
        $data['replies'] = $replies;
        echo view('templates/header_view', $data);
        echo view('social/posts_list', $data);
        echo view('templates/footer_view');

    }

    function time_elapsed_string($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7); // weeks
        $diff->d -= $diff->w * 7;

        $string = [
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        ];
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full)
            $string = array_slice($string, 0, 1);

        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }


    // public function add_post()
    // {
    //     $client = session()->get('client');
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $new_post = $this->request->getPost('new_post');

    //         if ($new_post) {
    //             $data = [
    //                 'client_id' => $client,
    //                 'post_data' => $new_post,
    //                 'post_image' => '', // Assuming no image upload for simplicity
    //                 'post_video' => '', // Assuming no video upload for simplicity
    //                 'status' => '1',
    //                 'created_by' => session()->get('id_user'),
    //                 'last_updated_by' => session()->get('id_user'),
    //                 'last_updated_on' => time(),
    //             ];
    //             $this->Post_model->insert_post($data);
    //             session()->setFlashdata('success', 'New Post created.');
    //             return redirect()->to(base_url('social/post'));
    //         } else {
    //             return redirect()->back()->withInput()->with('error', 'Post content cannot be empty.');
    //         }
    //     } else {
    //         session()->setFlashdata('error', 'Post content cannot be empty.');
    //         return redirect()->to(base_url('social/post'));

    //     }
    // }
    public function add_post()
    {
        $client = session()->get('client');

        if ($this->request->getPost()) {
            $new_post = $this->request->getPost('new_post');
            if (isset($_POST['course_id'])) {
                $data['course_id'] = $_POST['course_id'];
                $_SESSION['course_id'] = $data['course_id'];
            } else if (isset($_SESSION['course_id'])) {
                $data['course_id'] = $_SESSION['course_id'];
            } else {
                $data['course_id'] = 0;
            }
            if (isset($_POST['tab'])) {
                $data['tab'] = $_POST['tab'];
                $_SESSION['tab'] = $data['tab'];
            } else if (isset($_SESSION['tab'])) {
                $data['tab'] = $_SESSION['tab'];
            } else {
                $data['tab'] = 2;
            }
            $imageFiles = $this->request->getFiles()['image_files'] ?? null;
            $videoFiles = $this->request->getFiles()['video_files'] ?? null;

            $uploadedImages = [];
            $uploadedVideos = [];

            // === Handle image uploads ===
            if ($imageFiles && is_array($imageFiles)) {
                foreach ($imageFiles as $image) {
                    $imageUploadPath = FCPATH . 'assets/assets/uploads/post_images/';
                    // Create folders if they don't exist
                    if (!is_dir($imageUploadPath)) {
                        mkdir($imageUploadPath, 0755, true);
                    }
                    if ($image->isValid() && !$image->hasMoved()) {
                        $newName = $image->getRandomName();
                        $image->move($imageUploadPath, $newName);
                        $uploadedImages[] = $newName;
                    }
                }
            }

            // === Handle video uploads ===
            if ($videoFiles && is_array($videoFiles)) {
                foreach ($videoFiles as $video) {
                    if ($video->isValid() && !$video->hasMoved()) {
                        // Define paths
                        $videoUploadPath = FCPATH . 'assets/assets/uploads/post_videos/';


                        if (!is_dir($videoUploadPath)) {
                            mkdir($videoUploadPath, 0755, true);
                        }

                        $newName = $video->getRandomName();
                        $video->move($videoUploadPath, $newName);
                        $uploadedVideos[] = $newName;
                    }
                }
            }

            // === Prevent empty posts ===
            if (empty($new_post) && empty($uploadedImages) && empty($uploadedVideos)) {
                return redirect()->back()->withInput()->with('error', 'Please write something or attach a file.');
            }

            $postdata = [
                'client_id' => $client,
                'course_id' => $data['course_id'],
                'post_data' => $new_post ?? '',
                'post_image' => implode(',', $uploadedImages),
                'post_video' => implode(',', $uploadedVideos),
                'status' => '1',
                'created_by' => session()->get('id_user'),
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];

            $this->Post_model->insert_post($postdata);
            if ($data['course_id'] != 0) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url('my_training/read_more'));
            } else {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url('social/post'));
            }
        }

        // GET or other methods
        session()->setFlashdata('error', 'Invalid request.');
        return redirect()->to(base_url('social/post'));
    }


    public function like_post()
    {
        $post_id = $this->request->getPost('post_id');
        if (isset($_POST['course_id'])) {
            $data['course_id'] = $_POST['course_id'];
            $_SESSION['course_id'] = $data['course_id'];
        } else if (isset($_SESSION['course_id'])) {
            $data['course_id'] = $_SESSION['course_id'];
        } else {
            $data['course_id'] = 0;
        }
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 2;
        }
        if ($post_id) {
            $postdata = [
                'social_id' => $post_id,
                'client_id' => session()->get('client'),
                'liked_by' => session()->get('id_user'),
            ];
            $this->Post_model->insert_like($postdata);
            if ($data['course_id'] != 0) {
                return redirect()->to(base_url('my_training/read_more'));
            } else {
                return redirect()->to(base_url('social/post'));
            }

        } else {
            return redirect()->back()->withInput()->with('error', 'Invalid post ID.');
        }
    }

    public function reply_post()
    {
        $post_id = $this->request->getPost('post_id');
        $reply_comment = $this->request->getPost('reply_comment');
        // print_r($_POST['course_id']);
        // exit();
        if (isset($_POST['course_id'])) {
            $data['course_id'] = $_POST['course_id'];
            $_SESSION['course_id'] = $data['course_id'];
        } elseif (isset($_SESSION['course_id'])) {
            $data['course_id'] = $_SESSION['course_id'];
        } else {
            $data['course_id'] = 0;
        }
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 2;
        }
        if ($post_id && $reply_comment) {
            $postdata = [
                'social_id' => $post_id,
                'client_id' => session()->get('client'),
                'reply_content' => $reply_comment,
                'status' => '1',
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $this->Post_model->insert_reply($postdata);
            // print_r($data['course_id'] );
            // exit();
            if ($data['course_id'] != 0) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url('my_training/read_more'));
            } else {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url('social/post'));
            }
        } else {
            return redirect()->back()->withInput()->with('error', 'Invalid post ID or empty comment.');
        }
    }
    public function delete_post()
    {
        $post_id = $this->request->getPost('post_id');
        if (isset($_POST['course_id'])) {
            $data['course_id'] = $_POST['course_id'];
            $_SESSION['course_id'] = $data['course_id'];
        } else if (isset($_SESSION['course_id'])) {
            $data['course_id'] = $_SESSION['course_id'];
        } else {
            $data['course_id'] = 0;
        }
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 2;
        }
        if ($post_id) {
            $postdata = [
                'status' => 0,
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $this->Post_model->update_post($post_id, $postdata);
            if ($data['course_id'] != 0) {
                session()->setFlashdata('success', lang('Messages.Success_0005'));
                return redirect()->to(base_url('my_training/read_more'));
            } else {
                session()->setFlashdata('success', lang('Messages.Success_0005'));
                return redirect()->to(base_url('social/post'));
            }

        } else {
            return redirect()->back()->withInput()->with('error', 'Invalid post ID.');
        }
    }
    public function delete_reply()
    {
        $social_reply_id = $this->request->getPost('social_reply_id');
        if (isset($_POST['course_id'])) {
            $data['course_id'] = $_POST['course_id'];
            $_SESSION['course_id'] = $data['course_id'];
        } else if (isset($_SESSION['course_id'])) {
            $data['course_id'] = $_SESSION['course_id'];
        } else {
            $data['course_id'] = 0;
        }
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 2;
        }
        if ($social_reply_id) {
            $postdata = [
                'status' => 0,
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $this->Post_model->update_reply($social_reply_id, $postdata);
            if ($data['course_id'] != 0) {
                session()->setFlashdata('success', lang('Messages.Success_0005'));
                return redirect()->to(base_url('my_training/read_more'));
            } else {
                session()->setFlashdata('success', lang('Messages.Success_0005'));
                return redirect()->to(base_url('social/post'));
            }

        } else {
            return redirect()->back()->withInput()->with('error', 'Invalid post ID.');
        }
    }
    public function edit_post()
    {
        $postId = $this->request->getPost('post_id');
        $post_data = $this->request->getPost('post_data');
        if (isset($_POST['course_id'])) {
            $data['course_id'] = $_POST['course_id'];
            $_SESSION['course_id'] = $data['course_id'];
        } else if (isset($_SESSION['course_id'])) {
            $data['course_id'] = $_SESSION['course_id'];
        } else {
            $data['course_id'] = 0;
        }
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 2;
        }
        $postdata = [
            'post_data' => $post_data,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        // Validate and update
        $this->Post_model->update_post($postId, $postdata);
        if ($data['course_id'] != 0) {
            session()->setFlashdata('success', lang('Messages.Success_0008'));
            return redirect()->to(base_url('my_training/read_more'));
        } else {
            session()->setFlashdata('success', lang('Messages.Success_0008'));
            return redirect()->to(base_url('social/post'));
        }
    }

    public function edit_reply()
    {
        $replyId = $this->request->getPost('social_reply_id');
        $reply_content = $this->request->getPost('reply_content');
        if (isset($_POST['course_id'])) {
            $data['course_id'] = $_POST['course_id'];
            $_SESSION['course_id'] = $data['course_id'];
        } else if (isset($_SESSION['course_id'])) {
            $data['course_id'] = $_SESSION['course_id'];
        } else {
            $data['course_id'] = 0;
        }
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 2;
        }
        $postdata = [
            'reply_content' => $reply_content,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $this->Post_model->update_reply($replyId, $postdata);
        if ($data['course_id'] != 0) {
            session()->setFlashdata('success', lang('Messages.Success_0008'));
            return redirect()->to(base_url('my_training/read_more'));
        } else {


            session()->setFlashdata('success', lang('Messages.Success_0008'));
            return redirect()->to(base_url('social/post'));
        }
    }

}
