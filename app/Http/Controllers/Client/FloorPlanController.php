<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\models\website\FloorPlan;

class FloorPlanController extends Controller
{
    public function show($slug)
    {
        $plan = $this->findPlan($slug);
        $journal = $this->journalFromPlan($plan, 3);

        return view('floor-plan.show', $this->journalViewData($plan, $journal));
    }

    public function journal($slug)
    {
        $plan = $this->findPlan($slug);
        $journal = $this->journalFromPlan($plan, null);

        return view('floor-plan.journal', $this->journalViewData($plan, $journal));
    }

    private function findPlan($slug)
    {
        $plan = FloorPlan::with('category:id,name')
            ->where('slug', $slug)
            ->where('status', 1)
            ->first();
        if (!$plan) {
            abort(404);
        }

        return $plan;
    }

    private function journalViewData(FloorPlan $plan, array $journal)
    {
        return [
            'plan' => $plan,
            'journalDays' => $journal['days'],
            'journalTotal' => $journal['total'],
            'journalBuilding' => trim((string) preg_replace('/^layout\s+/iu', '', $plan->name)),
        ];
    }

    private function journalFromPlan(FloorPlan $plan, $limit)
    {
        $raw = $plan->journal;
        if (is_string($raw)) {
            $raw = json_decode($raw, true);
        }
        if (!is_array($raw)) {
            return ['days' => [], 'total' => 0];
        }

        $building = trim((string) preg_replace('/^layout\s+/iu', '', $plan->name));
        $days = [];
        foreach ($raw as $day) {
            if (!is_array($day)) {
                continue;
            }
            $groups = [];
            $sale = $this->journalItems($day['sale'] ?? []);
            $rent = $this->journalItems($day['rent'] ?? []);
            if (count($sale)) {
                $groups[] = ['name' => 'Bán', 'items' => $sale];
            }
            if (count($rent)) {
                $groups[] = ['name' => 'Cho thuê', 'items' => $rent];
            }
            if (!count($groups)) {
                continue;
            }
            $row = [
                'dow' => trim($day['dow'] ?? ''),
                'date' => trim($day['date'] ?? ''),
                'time' => trim($day['time'] ?? ''),
                'groups' => $groups,
            ];
            $row['title'] = $this->journalTitle($building, $row);
            $days[] = $row;
        }

        usort($days, function ($a, $b) {
            return $this->journalTime($b['date'] ?? '') <=> $this->journalTime($a['date'] ?? '');
        });

        return [
            'days' => $limit ? array_slice($days, 0, $limit) : $days,
            'total' => count($days),
        ];
    }

    private function journalItems($value)
    {
        if (is_string($value)) {
            $value = preg_split('/\r\n|\r|\n/', $value);
        }
        if (!is_array($value)) {
            return [];
        }

        $items = [];
        foreach ($value as $line) {
            $line = trim((string) $line);
            if ($line === '') {
                continue;
            }
            if (preg_match('/^(.+?)\s+[–—-]\s+(.+)$/u', $line, $match)) {
                $items[] = ['label' => trim($match[1]), 'text' => trim($match[2])];
            } else {
                $items[] = ['label' => '', 'text' => $line];
            }
        }

        return $items;
    }

    private function journalTitle($building, array $day)
    {
        $dow = trim($day['dow'] ?? '');
        $date = trim($day['date'] ?? '');
        $short = $date;
        if (preg_match('/^(\d{1,2}\/\d{1,2})\//', $date, $match)) {
            $short = $match[1];
        }
        if ($building !== '' && $dow !== '' && $short !== '') {
            return 'Giỏ hàng '.$building.' – '.$dow.' ngày '.$short;
        }

        return trim($day['title'] ?? '');
    }

    private function journalTime($date)
    {
        $parsed = \DateTime::createFromFormat('d/m/Y', trim((string) $date));

        return $parsed ? $parsed->getTimestamp() : 0;
    }
}
