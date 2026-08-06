<?php

namespace App\Libraries;

/**
 * Pure calculation service for the CEO Executive Dashboard.
 *
 * Takes the same raw rows already produced by App\Models\Etrack\Finance_model
 * (no DB access here) and turns them into the aligned 12-month series and
 * KPI figures the dashboard displays. Kept framework-free so it can be
 * unit-tested without booting CodeIgniter.
 */
class DashboardMetrics
{
    public const MONTH_LABELS = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];

    // TODO: source from a real FX-rate table instead of a literal (tracked alongside
    // the placeholder Library/WIP revenue below).
    private const EXCHANGE_RATE_INR_USD = 94;

    // TODO: source from an HR rate config once one exists.
    private const HOURLY_RATE_USD = 23;
    private const TARGET_UTILIZATION = 0.70;

    /**
     * finance_dashboard.php currently hardcodes these two series instead of
     * querying them. Centralized here (rather than duplicated in a view) so
     * there is exactly one place to swap in a real query later.
     */
    private const PLACEHOLDER_LIBRARY_REVENUE = [32851, 37894, 34078, 35708, 45520, 39588, 42020, 43950, 35463, 27397, 37209, 32339];
    private const PLACEHOLDER_WIP_REVENUE = [32189, 53069, 66323, 80494, 108386, 75497, 0, 0, 0, 0, 0, 0];

    /**
     * Builds aligned 12-month series from raw model rows.
     *
     * @param array $bespokeRows  rows of ['total_amount', 'month'] from Finance_model::get_bespoke_revenue()
     * @param array $salaryRows   rows of ['total_salary', 'pay_month'] from Finance_model::get_active_salary_users_dashboard()
     * @param array $operationCostRows rows of ['usd_cost', 'month'] from Finance_model::get_operation_cost()
     * @param array $salesRows    rows of ['total_amount', 'month'] from Finance_model::get_sales_data_dashboard()
     */
    public function buildMonthlySeries(array $bespokeRows, array $salaryRows, array $operationCostRows, array $salesRows): array
    {
        $library = self::PLACEHOLDER_LIBRARY_REVENUE;
        $wip = self::PLACEHOLDER_WIP_REVENUE;
        $bespoke = $this->normalizeByMonth($bespokeRows, 'total_amount', 'month');
        $salary = $this->normalizeSalaryByMonth($salaryRows);
        $operational = $this->normalizeByMonth($operationCostRows, 'usd_cost', 'month');
        $pipeline = $this->normalizeByMonth($salesRows, 'total_amount', 'month');

        $revenue = [];
        $cost = [];
        $delta = [];
        $cumulative = [];
        $running = 0.0;

        for ($i = 0; $i < 12; $i++) {
            // Total Revenue = Library + Bespoke only, matching the existing
            // finance_dashboard.php view: WIP is unbilled/in-progress work and
            // is tracked separately, not folded into recognized revenue.
            $revenue[$i] = $library[$i] + $bespoke[$i];
            $cost[$i] = $salary[$i] + $operational[$i];
            $delta[$i] = $revenue[$i] - $cost[$i];
            $running += $delta[$i];
            $cumulative[$i] = $running;
        }

        return [
            'library' => $library,
            'bespoke' => $bespoke,
            'wip' => $wip,
            'salary' => $salary,
            'operational' => $operational,
            'pipeline' => $pipeline,
            'revenue' => $revenue,
            'cost' => $cost,
            'delta' => $delta,
            'cumulative' => $cumulative,
        ];
    }

    private function normalizeByMonth(array $rows, string $valueKey, string $monthKey): array
    {
        $values = array_fill(0, 12, 0.0);
        foreach ($rows as $row) {
            $monthIndex = ((int) ($row[$monthKey] ?? 0)) - 1;
            if ($monthIndex >= 0 && $monthIndex < 12) {
                $values[$monthIndex] += (float) ($row[$valueKey] ?? 0);
            }
        }
        return $values;
    }

    private function normalizeSalaryByMonth(array $rows): array
    {
        $values = array_fill(0, 12, 0.0);
        foreach ($rows as $row) {
            $monthIndex = ((int) ($row['pay_month'] ?? 0)) - 1;
            if ($monthIndex >= 0 && $monthIndex < 12) {
                $values[$monthIndex] += round(((float) ($row['total_salary'] ?? 0)) / self::EXCHANGE_RATE_INR_USD);
            }
        }
        return $values;
    }

    public function getYtdRevenue(array $series, int $throughMonthIndex): float
    {
        return array_sum(array_slice($series['revenue'], 0, $throughMonthIndex + 1));
    }

    public function getYtdCost(array $series, int $throughMonthIndex): float
    {
        return array_sum(array_slice($series['cost'], 0, $throughMonthIndex + 1));
    }

    public function getCumulativePosition(array $series, int $throughMonthIndex): float
    {
        return $series['cumulative'][$throughMonthIndex] ?? 0.0;
    }

    public function getPipelineCoverage(float $remainingPipeline, float $avgMonthlyCost): float
    {
        return $avgMonthlyCost > 0 ? $remainingPipeline / $avgMonthlyCost : 0.0;
    }

    public function getUtilizationRate(float $actualRevenue, float $capacityDollars): float
    {
        return $capacityDollars > 0 ? ($actualRevenue / $capacityDollars) * 100 : 0.0;
    }

    public function getWorkforceCapacityDollars(float $totalCapacityHours): float
    {
        return $totalCapacityHours * self::HOURLY_RATE_USD * self::TARGET_UTILIZATION;
    }

    public function getWorkforceSplits(int $totalUsers, int $maleUsers, int $permanentUsers, int $productionUsers): array
    {
        $pct = static fn(int $part, int $whole): float => $whole > 0 ? round(($part / $whole) * 100) : 0.0;

        $femaleUsers = max(0, $totalUsers - $maleUsers);
        $contractUsers = max(0, $totalUsers - $permanentUsers);
        $supportUsers = max(0, $totalUsers - $productionUsers);

        return [
            'total_users' => $totalUsers,
            'male' => ['count' => $maleUsers, 'pct' => $pct($maleUsers, $totalUsers)],
            'female' => ['count' => $femaleUsers, 'pct' => $pct($femaleUsers, $totalUsers)],
            'permanent' => ['count' => $permanentUsers, 'pct' => $pct($permanentUsers, $totalUsers)],
            'contract' => ['count' => $contractUsers, 'pct' => $pct($contractUsers, $totalUsers)],
            'production' => ['count' => $productionUsers, 'pct' => $pct($productionUsers, $totalUsers)],
            'support' => ['count' => $supportUsers, 'pct' => $pct($supportUsers, $totalUsers)],
        ];
    }

    /**
     * Small rule-based text generator for the Alerts/Insights panel.
     * Deliberately simple threshold checks against the already-computed
     * series, not a forecasting model.
     */
    public function getInsights(array $series, int $throughMonthIndex): array
    {
        $insights = [];
        $months = self::MONTH_LABELS;

        // Cumulative turning positive for the first time this year.
        $turnedPositiveAt = null;
        for ($i = 0; $i <= $throughMonthIndex; $i++) {
            if ($series['cumulative'][$i] >= 0) {
                $turnedPositiveAt = $i;
                break;
            }
        }
        if ($turnedPositiveAt !== null && $turnedPositiveAt > 0) {
            $insights[] = "Cumulative position turned positive in {$months[$turnedPositiveAt]}, after starting the year negative.";
        } elseif ($turnedPositiveAt === null) {
            $insights[] = 'Cumulative position is still negative year-to-date.';
        }

        // Net loss per quarter.
        $quarterNames = ['Q1', 'Q2', 'Q3', 'Q4'];
        foreach ($quarterNames as $q => $label) {
            $start = $q * 3;
            $end = min($start + 2, $throughMonthIndex);
            if ($start > $throughMonthIndex) {
                break;
            }
            $quarterDelta = array_sum(array_slice($series['delta'], $start, $end - $start + 1));
            if ($quarterDelta < 0) {
                $insights[] = "{$label} was a net loss of $" . number_format(abs($quarterDelta), 0, '.', ',') . '.';
            }
        }

        // Month-over-month revenue spikes (>50% growth).
        for ($i = 1; $i <= $throughMonthIndex; $i++) {
            $prev = $series['revenue'][$i - 1];
            $curr = $series['revenue'][$i];
            if ($prev > 0 && ($curr - $prev) / $prev >= 0.5) {
                $growthPct = round((($curr - $prev) / $prev) * 100);
                $insights[] = "Revenue grew {$growthPct}% month-over-month in {$months[$i]}.";
            }
        }

        $insights[] = 'Library and WIP Revenue figures shown are placeholder data pending integration with live tables.';

        return $insights;
    }
}
