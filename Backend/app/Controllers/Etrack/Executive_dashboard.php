<?php

namespace App\Controllers\Etrack;

use App\Controllers\BaseController;
use App\Libraries\DashboardMetrics;
use App\Models\Etrack\Finance_model;

#[\AllowDynamicProperties]
class Executive_dashboard extends BaseController
{
    private const ALLOWED_ROLES = ['3048', '2010', '3014', '69'];

    public function __construct()
    {
        $this->guardAccess();
        $this->Finance_model = new Finance_model();
        $this->metrics = new DashboardMetrics();
    }

    /**
     * Same session-based gate as the sibling Fin_admin controller (client
     * must be 1, userlevel must include one of the finance/admin role codes).
     */
    private function guardAccess(): void
    {
        $client = session()->get('client');
        if ($client != 1) {
            header('Location:' . base_url('my_training'));
            exit();
        }
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }
        // userlevel is stored as a comma-separated string that may contain
        // spaces after commas (e.g. "3014, 3048, 69"); trim each token so
        // the comparison against ALLOWED_ROLES isn't defeated by whitespace.
        $arrayuserlevel = array_map('trim', explode(',', $userlevel));
        if (empty(array_intersect($arrayuserlevel, self::ALLOWED_ROLES))) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    } 

    public function view()
    {
        if ($response = $this->requireRole(self::ALLOWED_ROLES)) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['year'] = (int) ($this->request->getGet('year') ?: date('Y'));
        $data['year_options'] = range($data['year'], $data['year'] - 4);
        echo view('templates/header_view', $data);
        echo view('etrack/finance/executive_dashboard', $data);
        echo view('templates/footer_view');
    }

    public function data()
    {
        if ($response = $this->requireRole(self::ALLOWED_ROLES)) {
            return $response;
        }

        $year = (int) ($this->request->getGet('year') ?: date('Y'));
        $client = session()->get('client');
        $currentMonthIndex = ($year === (int) date('Y')) ? ((int) date('n')) - 1 : 11;
        $monthsElapsed = $currentMonthIndex + 1;

        $totalUsers = (int) $this->Finance_model->get_active_users_dashboard($client);
        $maleUsers = (int) $this->Finance_model->get_active_male_users_dashboard($client);
        $permanentUsers = (int) $this->Finance_model->get_active_permanent_users_dashboard($client);
        $productionUsers = (int) $this->Finance_model->get_active_tasks_users_dashboard($client);
        $listTaskUsers = $this->Finance_model->list_task_users($client);

        $totalCapacityHours = 0.0;
        foreach ($listTaskUsers as $row) {
            $totalCapacityHours += ((float) $row['default_dashboard2']) * 160 / 100;
        }

        $bespokeRows = $this->Finance_model->get_bespoke_revenue($year);
        $salaryRows = $this->Finance_model->get_active_salary_users_dashboard($year);
        $operationCostRows = $this->Finance_model->get_operation_cost($year);
        $salesRows = $this->Finance_model->get_sales_data_dashboard();

        $series = $this->metrics->buildMonthlySeries($bespokeRows, $salaryRows, $operationCostRows, $salesRows);

        $ytdRevenue = $this->metrics->getYtdRevenue($series, $currentMonthIndex);
        $ytdCost = $this->metrics->getYtdCost($series, $currentMonthIndex);
        $cumulativePosition = $this->metrics->getCumulativePosition($series, $currentMonthIndex);

        $monthlyCapacityDollars = $this->metrics->getWorkforceCapacityDollars($totalCapacityHours);
        // Capacity $ is a monthly figure; scale by months elapsed so it's
        // comparable against YTD revenue for the utilization KPI.
        $ytdCapacityDollars = $monthlyCapacityDollars * $monthsElapsed;
        $utilizationRate = $this->metrics->getUtilizationRate($ytdRevenue, $ytdCapacityDollars);

        $remainingPipeline = array_sum(array_slice($series['pipeline'], $currentMonthIndex));
        $avgMonthlyCost = $monthsElapsed > 0 ? $ytdCost / $monthsElapsed : 0.0;
        $pipelineCoverageMonths = $this->metrics->getPipelineCoverage($remainingPipeline, $avgMonthlyCost);

        return $this->response->setJSON([
            'success' => true,
            'year' => $year,
            'generated_at' => date('Y-m-d H:i:s'),
            'months' => DashboardMetrics::MONTH_LABELS,
            'current_month_index' => $currentMonthIndex,
            'series' => $series,
            'kpis' => [
                'ytd_revenue' => $ytdRevenue,
                'ytd_cost' => $ytdCost,
                'ytd_net_profit' => $ytdRevenue - $ytdCost,
                'cumulative_position' => $cumulativePosition,
                'pipeline_coverage_months' => round($pipelineCoverageMonths, 1),
                'utilization_rate' => round($utilizationRate, 1),
                'monthly_capacity_dollars' => $monthlyCapacityDollars,
                'total_capacity_hours' => $totalCapacityHours,
            ],
            'workforce' => $this->metrics->getWorkforceSplits($totalUsers, $maleUsers, $permanentUsers, $productionUsers),
            'leaderboard' => $this->buildUtilizationLeaderboard($listTaskUsers),
            'insights' => $this->metrics->getInsights($series, $currentMonthIndex),
            'placeholders' => [
                'library_revenue' => true,
                'wip_revenue' => true,
            ],
        ]);
    }

    private function buildUtilizationLeaderboard(array $users): array
    {
        usort($users, static fn($a, $b) => ((float) ($b['default_dashboard2'] ?? 0)) <=> ((float) ($a['default_dashboard2'] ?? 0)));

        $format = static fn($row) => [
            'name' => trim(($row['name'] ?? '') . ' ' . ($row['last_name'] ?? '')),
            'utilization' => (float) ($row['default_dashboard2'] ?? 0),
        ];

        return [
            'top' => array_map($format, array_slice($users, 0, 5)),
            'bottom' => array_map($format, array_slice(array_reverse($users), 0, 5)),
        ];
    }
}
