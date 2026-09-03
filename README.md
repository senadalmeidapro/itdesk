# itdesk

Fullstack IT Service Management (ITSM) platform for managing technical support, IT, and network activities — ticketing, asset/CMDB tracking, SLA policies, and role-based access control.

## Stack

- **Laravel** (fullstack monolith — Blade views, no separate frontend build)
- **Livewire** for reactive UI (ticket list/create/show, asset list/create/show)
- **Laravel Fortify** for authentication (session-based, not token/API auth)
- **Spatie laravel-permission** for roles and granular permissions

This is **not** a JSON API. There is no `routes/api.php` surface in active use — all interaction happens through server-rendered Livewire components under `routes/web.php`, protected by Fortify's session `auth` middleware.

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate

# Roles, permissions, and their mapping
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=PermissionsSeeder

php artisan serve
```

### Assigning a role to a user

```bash
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->assignRole('admin'); // or: agent, network_tech, requester
```

## Domain model

### Ticket types

| Type | Requires approval? | Notes |
|---|---|---|
| `incident` | No | Something broken; standard lifecycle |
| `service_request` | Yes | New equipment, access grants, etc. |
| `problem` | No | Root-cause investigation; links to one or more `incident` tickets via `problem_incidents` |
| `change` | Yes | Carries a `ChangeDetail` record (risk level, scheduled date, rollback plan) |

### Ticket status state machine

```
open ──┬──────────────────────────────────► assigned
       │                                        │
       └──► pending_approval ──► (approved) ────┤
                  │                              ▼
                  └──► (rejected) ──► closed  in_progress
                                                  │
                                          ┌───────┴───────┐
                                          ▼               ▼
                                       pending ◄──────► resolved
                                                            │
                                                            ▼
                                                         closed
                                                            ▲
                                             (reopen) ───────┘
                                          resolved ──► assigned
```

**Key rule:** `closed` is terminal. A closed ticket can never be reopened — only a `resolved` ticket can go back to `assigned`. This is enforced in `App\Models\Ticket::transitionTo()`, which throws a `DomainException` on any transition not listed in `Ticket::TRANSITIONS`. See `tests/Feature/TicketTransitionTest.php` for the full contract.

### Roles & permissions

Four roles, each mapped to a fixed permission set in `PermissionsSeeder`:

| Role | Can do |
|---|---|
| `requester` | Create tickets, view/edit own tickets (while open), comment |
| `agent` | View/manage all tickets, transition status, internal comments, view assets |
| `network_tech` | Everything `agent` can, plus create/update/assign assets |
| `admin` | Everything, including delete and ticket approval |

Policies (`TicketPolicy`, `AssetPolicy`) check permissions (`$user->can('tickets.transition')`), not raw role names — so access can be fine-tuned per user later (`$user->givePermissionTo(...)`) without touching policy code. Full permission list in `database/seeders/PermissionsSeeder.php`.

### Assets (CMDB)

Physical hardware only (`assets` table) — software licenses live in a separate `software_licenses` table with seat tracking, since they don't share the same lifecycle (no location, no warranty, no serial number in the hardware sense).

Reassigning an asset (`Asset::assignTo()`) automatically closes out the previous `AssetAssignment` record and opens a new one, giving a full assignment history per asset.

## Testing

```bash
php artisan test
```

Feature tests cover:
- The full ticket status state machine, including the closed/resolved reopen rule
- Approval-driven transitions (`Approval::approve()` / `reject()`)
- Asset assignment/reassignment history logging
- Permission-based policy enforcement per role

Requires a test database configured in `phpunit.xml` (SQLite in-memory recommended: `DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`).

## Project structure notes

- `app/Livewire/Tickets/` and `app/Livewire/Assets/` — full-page Livewire components (index/create/show), each backed by a Blade view under `resources/views/livewire/`
- `app/Models/Ticket.php` — carries the status transition guard (`TRANSITIONS` const, `canTransitionTo()`, `transitionTo()`) directly on the model, by design (no separate state machine package)
- `app/Policies/` — permission-based authorization, registered via `Gate::policy()` in `AppServiceProvider::boot()`
- `database/seeders/RolesAndPermissionsSeeder.php` — creates the 4 roles
- `database/seeders/PermissionsSeeder.php` — creates permissions and assigns them to roles (idempotent — safe to re-run)
