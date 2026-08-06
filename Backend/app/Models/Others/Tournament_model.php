<?php

namespace App\Models\others;

use CodeIgniter\Model;

class Tournament_model extends Model
{

    function get_active_tournaments()
    {
        $builder = $this->db->table('tournaments as tour');
        $builder->select('tour.*, count(DISTINCT team.tt_id) as total_teams, count(DISTINCT  matches.match_id) as total_matches');
        $builder->join('tournament_team as team', 'team.tour_id = tour.tour_id AND team.status != 0', 'left');
        $builder->join('tournament_matches as matches', 'matches.tour_id = tour.tour_id AND team.status != 0', 'left');
        $builder->where('tour.status !=', 0);
        $builder->groupBy('tour.tour_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function insert_tournament($data)
    {
        $builder = $this->db->table('tournaments');
        return $builder->insert($data);
    }

    function get_tournament_by_id($tour_id)
    {
        $builder = $this->db->table('tournaments');
        $builder->select('*');
        $builder->where('tour_id', $tour_id);
        $data = $builder->get()->getRowArray();
        return $data;
    }

    function update_tournament($tour_id, $data)
    {
        $builder = $this->db->table('tournaments');
        $builder->where('tour_id', $tour_id);
        return $builder->update($data);
    }

    function get_teams($tour_id)
    {
        $builder = $this->db->table('tournament_team as team');
        $builder->select('team.*, count(matches.match_id) as total_wins');
        $builder->join('tournament_matches as matches', 'matches.who_won = team.tt_id AND matches.status != 0', 'left');
        $builder->where('team.tour_id', $tour_id);
        $builder->groupBy('team.tt_id');
        $builder->where('team.status !=', 0);
        $builder->orderBy('total_wins', 'DESC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function Insert_New_Team_data($data)
    {
        $builder = $this->db->table('tournament_team');
        return $builder->insert($data);
    }

    function get_teams_by_id($tt_id)
    {

        $builder = $this->db->table('tournament_team');
        $builder->select('*');
        $builder->where('tt_id', $tt_id);
        $builder->where('status !=', 0);
        $data = $builder->get()->getRowArray();
        return $data;
    }

    function Update_Team_data($tt_id, $data)
    {
        $builder = $this->db->table('tournament_team');
        $builder->where('tt_id', $tt_id);
        return $builder->update($data);
    }

    function Add_Member_to_Team_data($data)
    {
        $builder = $this->db->table(' tournament_user');
        return $builder->insert($data);
    }

    function get_team_members_by_id($tt_id)
    {
        $builder = $this->db->table('tournament_user as tu');
        $builder->select('tu.*');
        $builder->where('tu.team_id', $tt_id);
        $builder->where('tu.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function Delete_member_from_team($data, $tu_id)
    {
        $builder = $this->db->table('tournament_user');
        $builder->where('tu_id', $tu_id);
        return $builder->update($data);
    }

    function get_all_matches($tour_id)
    {
        $builder = $this->db->table('tournament_matches as matches');
        $builder->select('matches.*, team1.team_name as team_1, team2.team_name as team_2, team3.team_name as who_won');
        $builder->join('tournament_team as team1', 'team1.tt_id = matches.team_1', 'left');
        $builder->join('tournament_team as team2', 'team2.tt_id = matches.team_2', 'left');
        $builder->join('tournament_team as team3', 'team3.tt_id = matches.who_won', 'left');
        $builder->where('matches.tour_id', $tour_id);
        $builder->orderBy('matches.match_date', 'DESC');
        $builder->where('matches.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_all_teams($tour_id)
    {
        // collect both team_1 and team_2 from pending matches (who_won IS NULL)
        $matchBuilder = $this->db->table('tournament_matches');
        $matchBuilder->select('team_1, team_2');
        $matchBuilder->where('tour_id', $tour_id);
        $matchBuilder->where('status !=', 0);
        $matchBuilder->where('who_won', 0);
        $matches = $matchBuilder->get()->getResultArray();

        // build an array of excluded team ids
        $excluded_values = [];
        foreach ($matches as $m) {
            if (!empty($m['team_1'])) {
             //   $excluded_values[] = $m['team_1'];
            }
            if (!empty($m['team_2'])) {
           //     $excluded_values[] = $m['team_2'];
            }
        }
        $excluded_values = array_values(array_unique($excluded_values));

        $builder = $this->db->table('tournament_team as team');
        $builder->select('team.*');
        if (!empty($excluded_values)) {
            // use the correct Query Builder method and the actual team id column
            $builder->whereNotIn('team.tt_id', $excluded_values);
        }
        $builder->where('team.tour_id', $tour_id);
        $builder->where('team.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function Insert_New_Match_data($data)
    {
        $builder = $this->db->table('tournament_matches');
        return $builder->insert($data);
    }

    function get_matches_by_id($match_id)
    {
        $builder = $this->db->table('tournament_matches as matches');
        $builder->select('matches.*, team1.team_name as team_1_name, team1.player1 as t1_player_1_name, team1.player2 as t1_player_2_name,
         team2.team_name as team_2_name, , team2.player1 as t2_player_1_name, team2.player2 as t2_player_2_name,
         team3.team_name as who_won_name');
        $builder->join('tournament_team as team1', 'team1.tt_id = matches.team_1', 'left');
        $builder->join('tournament_team as team2', 'team2.tt_id = matches.team_2', 'left');
        $builder->join('tournament_team as team3', 'team3.tt_id = matches.who_won', 'left');
        $builder->where('matches.match_id', $match_id);
        $data = $builder->get()->getRowArray();
        return $data;
    }

    function Update_Match_data($match_id, $data)
    {
        $builder = $this->db->table('tournament_matches');
        $builder->where('match_id', $match_id);
        return $builder->update($data);
    }

    function update_team_points($data2, $who_won)
    {
        $builder = $this->db->table('tournament_team');
        $builder->where('tt_id', $who_won);
        return $builder->update($data2);
    }
}
