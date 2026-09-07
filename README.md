# TAKTIC (itdesk)

Fullstack **IT service management platform** for a French IT services company (TAKTIC).
It combines a public marketing site, a digital lead-to-ticket pipeline, a client portal,
and a Filament back office — all in a single Laravel application.

## What it does

- **Public site** (`routes/web.php`) — French, front-end only, no account required:
  - `GET /` — home
  - `GET /services` — service catalog
  - `GET /services/{slug}` — dedicated service page (404 on unknown slug)
  - `GET /a-propos` — about page
  - `GET|POST /contact` — lead capture form (optionally pre-framed by a service via `?service=<slug>`)
- **Service catalog** driven by `config/public-services.php` (6 services). Each entry has
  `slug`, `name`, `short`, `headline`, `description`, `icon` (`icon-*.blade.php`),
  `illustration` (`scene-*.blade.php` — one *dedicated* SVG scene per service), `tone`
  (`brand` / `flow` accent), `metrics` and `features`. All public pages render from this file —
  the `tone`/`icon` and illustrations follow the service.

## Stack

| Layer | Choice |
|---|---|
| Framework | Laravel (fullstack monolith — Blade views, no separate frontend app) |
| Reactive UI (client portal) | Livewire full-page components under `app/Livewire/` |
| UI components | Flux UI component library, Tailwind CSS v4, Vite build (`npm run build`) |
| Public site layouts | Blade layout `resources/views/layouts/public-web.blade.php` |
| Back office | Filament admin panel at `/admin` (`app/Filament/`) |
| Auth | Laravel Fortify (session-based) |
| RBAC | Spatie laravel-permission |

This is **not** a JSON API — there is no `routes/api.php` surface in active use. Everything
is server-rendered (Blade / Livewire / Filament) behind Fortify's session auth for protected
areas.

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate

# Assets
npm run build            # (or: npm run dev)

# Roles, permissions, and their mapping
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=PermissionsSeeder
```

> `RolesAndPermissionsSeeder` creates the 4 roles; `PermissionsSeeder` grants every
> permission to `admin` and fine-grained sets to the other roles. Both are idempotent.

### Assigning a role to a user

```bash
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->assignRole('admin'); // or: agent, network_tech, requester
```

## Lead → ticket workflow

Public contact submissions (`contact_messages`) are **leads**, not accounts. They follow a
simple state machine handled through the Filament back office:

`new → contacted → converted | rejected` (see `App\Models\ContactMessage::STATUSES`).

1. A visitor submits the contact form (`ContactController@store`). Fields include an optional
   `service_slug` (validated against `config/public-services.php`), and `status` defaults to `new`.
2. Admins see the list at `/admin/contact-messages` (`ContactMessagesResource`):
   - **Marquer contacté** — stamps `contacted_at`, visible while `new`.
   - **Convertir en ticket** — opens a modal (client, category, priority, assigned agent; can
     create the client account inline from name/email). Runs `App\Services\LeadConverter`:
     creates a `Ticket` typed `service_request` with status `assigned`, notifies the assigned
     agent, then marks the lead `converted` (with `converted_ticket_id`). Idempotent — a second
     attempt returns the existing ticket.
   - **Rejeter** — marks the lead `rejected`.
3. The `converted` relationship links the lead to its ticket (`ContactMessage::convertedTicket()`).

## Domain model

### Ticket types & approval

| Type | Requires approval? | Notes |
|---|---|---|
| `incident` | No | Something broken; standard lifecycle |
| `service_request` | Yes | New equipment, access grants, etc. |
| `problem` | No | Root-cause investigation; links to incidents via `problem_incidents` |
| `change` | Yes | Carries a `ChangeDetail` record (risk, schedule, rollback plan) |

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

**Key rule:** `closed` is terminal — only `resolved` can return to `assigned`. Enforced in
`App\Models\Ticket::transitionTo()`, which throws a `DomainException` on any transition not
listed in `Ticket::TRANSITIONS`.

### Roles & permissions

| Role | Can do |
|---|---|
| `requester` | Create tickets, view/edit own tickets (while open), comment |
| `agent` | View/manage all tickets, transition status, internal comments, view assets |
| `network_tech` | Everything `agent` can, plus create/update/assign assets |
| `admin` | Everything, including delete, ticket approval, and back-office lead management |

Policies (`TicketPolicy`, `AssetPolicy`) check **permissions**
(`$user->can('tickets.transition')`), not raw role names, so access can be fine-tuned per user
via `givePermissionTo(...)` without touching policy code. Full permission list in
`database/seeders/PermissionsSeeder.php`.

### Assets & software (CMDB)

- `assets` — physical hardware with location, warranty and serial tracking.
- `software_licenses` — seat-tracked licenses (separate lifecycle: no location/serial).
- `Asset::assignTo()` closes the previous `AssetAssignment` and opens a new one, preserving a
  full per-asset history.

## Back office (Filament)

Resources under `app/Filament/`:
- `ContactMessages` — lead list with status/service badges, filters and the convert workflow
  (see above). Uses the Filament v5 table API (`recordActions()`, `->schema()` action forms,
  inline client creation with `createOptionForm`/`createOptionUsing`).
- Tickets, users and other resources follow the resource-per-domain convention.

## Testing

```bash
php artisan test
```

Feature suites include:
- `PublicSiteTest` — public pages, catalog-driven service details, contact form + validation,
  and a guard that every service ships its own `scene-*.blade.php` illustration.
- `LeadConversionTest` — lead converted into an `assigned` `service_request` ticket with agent
  notification, conversion **idempotency**, and contacted/rejected statuses.
- `Admin\ContactMessageAdminTest` — back-office renders the lead table (admin only).
- `TicketTransitionTest` — full status state machine incl. the closed/resolved reopen rule.
- `Approval`, `AssetAssignment`, policy/permission suites.

Quote databases: SQLite in-memory (see `phpunit.xml`).

## Project structure notes

- `resources/views/components/scene-*.blade.php` — one hand-built SVG illustration per service
  (`scene-maintenance`, `scene-reseaux`, `scene-helpdesk`, `scene-vente`, `scene-securite`,
  `scene-formation`), resolved at runtime from the catalog.
- `resources/views/components/visual-scene.blade.php` — generic decorative scene (used by
  `/a-propos`).
- `app/Services/LeadConverter.php` — the lead-to-ticket service.
- `app/Models/Ticket.php` — carries the transition guard on the model (no state machine package).
- `app/Policies/` — permission-based authorization registered via `Gate::policy()`.
- `database/seeders/` — roles and permission mapping (both idempotent, safe to re-run).