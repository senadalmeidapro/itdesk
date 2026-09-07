<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use App\Models\ContactMessage;
use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $openTickets = Ticket::query()
            ->whereNotIn('status', ['resolved', 'closed', 'cancelled'])
            ->count();

        $breachedTickets = Ticket::query()
            ->whereNotIn('status', ['resolved', 'closed', 'cancelled'])
            ->where(function ($query) {
                $query->whereNotNull('sla_response_due_at')
                    ->where('sla_response_due_at', '<', now())
                    ->orWhere(fn ($q) => $q->whereNotNull('sla_resolution_due_at')->where('sla_resolution_due_at', '<', now()));
            })
            ->count();

        $assetsInUse = Asset::query()->where('status', 'in_use')->count();

        $unreadLeads = ContactMessage::query()->where('is_read', false)->count();

        return [
            Stat::make('Tickets ouverts', $openTickets)
                ->description('En attente de traitement')
                ->descriptionIcon('heroicon-o-lifebuoy')
                ->color('info'),
            Stat::make('SLA dépassés', $breachedTickets)
                ->description('Réponse ou résolution en retard')
                ->descriptionColor('danger')
                ->color('danger'),
            Stat::make('Équipements en usage', $assetsInUse)
                ->description('Parc actif')
                ->descriptionIcon('heroicon-o-computer-desktop')
                ->color('success'),
            Stat::make('Demandes non lues', $unreadLeads)
                ->description('Messages du site')
                ->descriptionIcon('heroicon-o-envelope')
                ->color('warning'),
        ];
    }
}
