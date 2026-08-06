<?php

namespace App\Models\Social;

use CodeIgniter\Model;

class Post_model extends Model
{

    function get_active_posts($client, $user_id, $course_id)
    {
        $builder = $this->db->table('social as so');
        $builder->select('so.*, u.name as posted_by, count(distinct like.so_like_id) as likes, count(melike.social_id) as melikes, u.last_name as posted_by_last_name, u.profile_image, u.profile_foldername, u.id_user');
        $builder->join('users as u', 'u.id_user = so.created_by', 'left');
        $builder->join('social_likes as like', 'like.social_id = so.social_id AND like.client_id =' . $client, 'left');
        $builder->join('social_likes as melike', 'melike.social_id = so.social_id AND melike.liked_by = ' . $user_id . ' AND melike.client_id = ' . $client, 'left');
        //  $builder->join('profile as pro', 'pro.id_user = u.id_user', 'left');
        $builder->where('so.client_id', $client);
        $builder->where('so.status !=', 0);
        $builder->where('so.course_id', $course_id);
        $builder->orderBy('so.last_updated_on', 'DESC');
        $builder->limit(5);
        $builder->groupBy('so.social_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function get_active_full_posts($client, $user_id,$course_id)
    {
        $builder = $this->db->table('social as so');
        $builder->select('so.*, u.name as posted_by, count(distinct like.so_like_id) as likes, count(melike.social_id) as melikes, u.last_name as posted_by_last_name, u.profile_image as profile_image, u.profile_foldername as foldername, u.username as username');
        $builder->join('users as u', 'u.id_user = so.created_by', 'left');
        $builder->join('social_likes as like', 'like.social_id = so.social_id AND like.client_id =' . $client, 'left');
        $builder->join('social_likes as melike', 'melike.social_id = so.social_id AND melike.liked_by = ' . $user_id . ' AND melike.client_id = ' . $client, 'left');
        //  $builder->join('profile as pro', 'pro.id_user = u.id_user', 'left');
        $builder->where('so.client_id', $client);
        $builder->where('so.status !=', 0);
        $builder->where('so.course_id', $course_id);
        $builder->orderBy('so.last_updated_on', 'DESC');
        // $builder->limit(5);
        $builder->groupBy('so.social_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getpost_replies($post_id)
    {
        $builder = $this->db->table('social_reply');
        $builder->select('social_reply.*, u.name as replied_by, u.last_name as replied_by_last_name, u.profile_image as profile_image, u.profile_foldername, u.id_user');
        $builder->join('users as u', 'u.id_user = social_reply.last_updated_by', 'left');
        $builder->where('social_reply.social_id', $post_id);
        $builder->where('social_reply.status !=', 0);
        // $builder->limit(2);
        $data = $builder->get()->getResultArray();

        return $data;
    }

    function getpost_replies_for_posts(array $post_ids)
    {
        if (empty($post_ids)) {
            return [];
        }
        $builder = $this->db->table('social_reply');
        $builder->select('social_reply.*, u.name as replied_by, u.last_name as replied_by_last_name, u.profile_image as profile_image, u.profile_foldername, u.id_user');
        $builder->join('users as u', 'u.id_user = social_reply.last_updated_by', 'left');
        $builder->whereIn('social_reply.social_id', $post_ids);
        $builder->where('social_reply.status !=', 0);
        $data = $builder->get()->getResultArray();

        $repliesByPost = [];
        foreach ($data as $reply) {
            $repliesByPost[$reply['social_id']][] = $reply;
        }
        return $repliesByPost;
    }

    function getAllpost_replies($post_id)
    {
        $builder = $this->db->table('social_reply');
        $builder->select('social_reply.*, u.name as replied_by, u.last_name as replied_by_last_name, u.profile_image, u.profile_foldername, u.id_user');
        $builder->join('users as u', 'u.id_user = social_reply.last_updated_by', 'left');
        $builder->where('social_reply.social_id', $post_id);
        $builder->where('social_reply.status !=', 0);
        // $builder->limit(2);
        $data = $builder->get()->getResultArray();

        return $data;
    }

    function getPostCount($user_id, $course_id)
    {
        $builder = $this->db->table('social');
        $builder->where('created_by', $user_id);
        $builder->where('course_id', $course_id);
        $builder->where('status !=', 0);
        return $builder->countAllResults();
    }
    function insert_post($data)
    {
        $builder = $this->db->table('social');
        $builder->insert($data);
        if ($this->db->affectedRows() > 0) {
            $builder = $this->db->table('users');
            $builder->select('id_user');
            $builder->where('valid', 1);
            $builder->where('name !=', 'Demo User');
            $builder->where('client_id', session()->get('client'));
            $builder->notLike('username ', 'user');
            $data = $builder->get()->getResultArray();
            foreach ($data as $row) {
                $builder = $this->db->table('social_notification');
                $builder->where('user_id', $row['id_user']);
                $statuscount = $builder->get()->getResultArray();
                if (!empty($statuscount)) {
                    $builder = $this->db->table('social_notification');
                    $builder->where('user_id', $row['id_user']);
                    $updatenotification = array(
                        'status' => $statuscount[0]['status'] + 1,
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time(),

                    );
                    $builder->update($updatenotification);

                } else {
                    $insertdata = array(
                        'user_id' => $row['id_user'],
                        'status' => 1,
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time(),

                    );
                    $builder = $this->db->table('social_notification');
                    $builder->insert($insertdata);
                }
            }

            return $this->db->insertID();
        } else {
            return false;
        }

    }
    function getUserPostCount($id_user)
    {
        $builder = $this->db->table('social_notification');
        $builder->select('status');
        $builder->where('user_id', $id_user);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function updatePostCount($user_id)
    {
        $builder = $this->db->table('social_notification');
        $builder->where('user_id', $user_id);
        $statuscount = $builder->get()->getResultArray();
        if (!empty($statuscount)) {
            $builder = $this->db->table('social_notification');
            $builder->where('user_id', $user_id);
            $updatenotification = array(
                'status' => 0,
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),

            );
            $_SESSION['userpostCount'] = 0;
            return $builder->update($updatenotification);
            
        } else {
            return false;
        }
    }
    function insert_reply($data)
    {
        $builder = $this->db->table('social_reply');
        $builder->insert($data);
        return true;
    }
    function insert_like($data)
    {
        $builder = $this->db->table('social_likes');
        $builder->insert($data);
        return true;
    }
    function update_post($post_id, $data)
    {
        $builder = $this->db->table('social');
        $builder->where('social_id', $post_id);
        return $builder->update($data);
    }
    function update_reply($reply_id, $data)
    {
        $builder = $this->db->table('social_reply');
        $builder->where('social_reply_id', $reply_id);
        return $builder->update($data);
    }
}
