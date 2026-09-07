<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public function mount(): void
    {
        //
    }

    public function render(): View
    {
        $user = auth()->user();

        if ($user->can('tickets.view_all')) {
            return $this->renderStaff();
        }

        return $this->renderRequester();
    }

    /**
     * Personal dashboard for requesters: shows their own tickets,
     * assigned assets, and pending approvals they may need to review.
     */
    protected function renderRequester(): View
    {
        $myTickets = Ticket::query()
            ->where('requester_id', auth()->id())
            ->with(['category', 'assignedAgent'])
            ->latest()
            ->limit(10)
            ->get();

        $myPendingApprovals = Ticket::query()
            ->where('status', 'pending_approval')
            ->whereHas('approvals', fn ($q) => $q->where('decision', 'pending'))
            ->count();

        $openTickets = $myTickets->whereNotIn('status', ['resolved', 'closed'])->count();

        return view('livewire.dashboard-requester', [
            'myTickets' => $myTickets,
            'myAssets' => auth()->user()->assets()->latest()->limit(10)->get(),
            'openTicketsCount' => $openTickets,
            'pendingApprovalsCount' => $myPendingApprovals,
        ]);
    }

    protected function renderStaff(): View
    {
        $dailyVolume = $this->dailyVolume();
        $maxDaily = max(1, max($dailyVolume));

        return view('livewire.dashboard', [
            'statusCounts' => $this->statusCounts(),
            'priorityCounts' => $this->priorityCounts(),
            'sla' => $this->slaCompliance(),
            'avgResolutionHours' => $this->averageResolutionHours(),
            'dailyVolume' => $dailyVolume,
            'maxDaily' => $maxDaily,
            'topCategories' => $this->topCategories(),
        ]);
    }

    /**
     * Ticket counts grouped by status, in a fixed display order
     * (not alphabetical) so the state machine reads naturally.
     */
    protected function statusCounts(): array
    {
        $counts = Ticket::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $order = array_keys(Ticket::TRANSITIONS);

        return collect($order)
            ->mapWithKeys(fn ($status) => [$status => $counts[$status] ?? 0])
            ->toArray();
    }

    protected function priorityCounts(): array
    {
        $counts = Ticket::query()
            ->selectRaw('priority, count(*) as total')
            ->groupBy('priority')
            ->pluck('total', 'priority');

        return collect(['low', 'medium', 'high', 'critical'])
            ->mapWithKeys(fn ($priority) => [$priority => $counts[$priority] ?? 0])
            ->toArray();
    }

    /**
     * SLA compliance among tickets that had a resolution deadline and
     * have since been resolved: what fraction were resolved on time.
     * Computed in PHP rather than SQL date-diff to stay portable
     * across MySQL/SQLite/Postgres.
     */
    protected function slaCompliance(): array
    {
        $resolved = Ticket::query()
            ->whereNotNull('sla_resolution_due_at')
            ->whereNotNull('resolved_at')
            ->get(['resolved_at', 'sla_resolution_due_at']);

        $total = $resolved->count();
        $onTime = $resolved->filter(fn ($t) => $t->resolved_at->lte($t->sla_resolution_due_at))->count();

        $currentlyBreached = Ticket::query()
            ->whereNotNull('sla_resolution_due_at')
            ->where('sla_resolution_due_at', '<', now())
            ->whereNotIn('status', ['resolved', 'closed'])
            ->count();

        return [
            'total_with_sla' => $total,
            'on_time' => $onTime,
            'breached_historical' => $total - $onTime,
            'compliance_rate' => $total > 0 ? round(($onTime / $total) * 100, 1) : null,
            'currently_breached' => $currentlyBreached,
        ];
    }

    /**
     * Average time from creation to resolution, in hours, for
     * tickets that have been resolved. PHP-side average for the
     * same portability reason as slaCompliance().
     */
    protected function averageResolutionHours(): ?float
    {
        $tickets = Ticket::query()
            ->whereNotNull('resolved_at')
            ->get(['created_at', 'resolved_at']);

        if ($tickets->isEmpty()) {
            return null;
        }

        $totalHours = $tickets->sum(fn ($t) => $t->created_at->diffInMinutes($t->resolved_at) / 60);

        return round($totalHours / $tickets->count(), 1);
    }

    /**
     * Daily ticket creation volume for the last 30 days, including
     * zero-count days so the chart doesn't have gaps.
     */
    protected function dailyVolume(): array
    {
        $since = Carbon::now()->subDays(29)->startOfDay();

        $counts = Ticket::query()
            ->where('created_at', '>=', $since)
            ->selectRaw('DATE(created_at) as day, count(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $days = [];
        for ($i = 0; $i < 30; $i++) {
            $date = $since->copy()->addDays($i)->toDateString();
            $days[$date] = (int) ($counts[$date] ?? 0);
        }

        return $days;
    }

    protected function topCategories(): array
    {
        return Category::query()
            ->withCount('tickets')
            ->orderByDesc('tickets_count')
            ->limit(5)
            ->get()
            ->map(fn ($c) => ['name' => $c->name, 'count' => $c->tickets_count])
            ->toArray();
    }
}
