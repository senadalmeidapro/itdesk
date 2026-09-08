# TAKTIC · itdesk

> **Fullstack IT service management platform** for a French IT services company.
> One Laravel application combining: a public marketing site, service-specific lead capture,
> a digital **lead → ticket** pipeline, a client portal, and a Filament back office.

---

## Table of contents

1. [Features](#features)
2. [Stack](#stack)
3. [Getting started](#getting-started)
4. [Roles & permissions](#roles--permissions)
5. [Lead → ticket workflow](#lead--ticket-workflow)
6. [Service-specific contact form](#service-specific-contact-form)
7. [Domain model](#domain-model)
8. [Back office (Filament)](#back-office-filament)
9. [Testing](#testing)
10. [Project structure](#project-structure)

---

## Features

**Public site** — `routes/web.php`, French, front-end only, no account required:

| Route | Description |
|---|---|
| `GET /` | Home |
| `GET /services` | Service catalog |
| `GET /services/{slug}` | Dedicated service page (404 on unknown slug) |
| `GET /a-propos` | About page |
| `GET / POST /contact` | Lead capture form (service-aware, see below) |

**Service catalog** — fully driven by `config/public-services.php` (6 services). Each entry
carries `slug`, `name`, `short`, `headline`, `description`, `icon` (`icon-*.blade.php`),
`illustration` (`scene-*.blade.php` — one *dedicated* SVG scene per service), `tone`
(`brand` / `flow` accent), `metrics` and `features`. Every public page renders from this file,
so the imagery and accents follow the service automatically.

---

## Stack

| Layer | Choice |
|---|---|
| Framework | Laravel — fullstack monolith, server-rendered Blade/Livewire/Filament (no separate frontend app, no JSON API) |
| Reactive UI (client portal) | Livewire full-page components under `app/Livewire/` |
| UI components | Flux UI, Tailwind CSS v4, Vite (`npm run build`) |
| Public layouts | `resources/views/layouts/public-web.blade.php` |
| Back office | Filament admin panel at `/admin` (`app/Filament/`) |
| Auth | Laravel Fortify (session-based) |
| RBAC | Spatie laravel-permission |

> Everything is authenticated behind Fortify's session auth for protected areas. There is no
> `routes/api.php` surface in active use.

---

## Getting started

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

> `RolesAndPermissionsSeeder` creates the 4 roles; `PermissionsSeeder` grants every permission
> to `admin` and fine-grained sets to the other roles. Both are idempotent — safe to re-run.

### Assigning a role to a user

```bash
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->assignRole('admin'); // or: agent, network_tech, requester
```

---

## Roles & permissions

| Role | Can do |
|---|---|
| `requester` | Create tickets, view/edit own tickets (while open), comment |
| `agent` | View/manage all tickets, transition status, internal comments, view assets |
| `network_tech` | Everything `agent` can, plus create/update/assign assets |
| `admin` | Everything, including delete, ticket approval, and back-office lead management |

Policies (`TicketPolicy`, `AssetPolicy`) check **permissions**
(`$user->can('tickets.transition')`), not raw role names — so access can be fine-tuned per user
via `givePermissionTo(...)` without touching policy code. The full permission list lives in
`database/seeders/PermissionsSeeder.php`.

---

## Lead → ticket workflow

Public contact submissions (`contact_messages`) are **leads**, not accounts. They follow a
simple state machine handled through the Filament back office:

```
new → contacted → converted | rejected
```

(see `App\Models\ContactMessage::STATUSES`)

1. **Capture** — a visitor submits the contact form (`ContactController@store`). Fields include
   an optional `service_slug` (validated against `config/public-services.php`), per-service
   custom fields, and `status` defaults to `new`. Answers not part of the selected service's
   schema are discarded server-side.
2. **Process** — admins see the list at `/admin/contact-messages` (`ContactMessagesResource`):

   | Action | Effect |
   |---|---|
   | **Marquer contacté** | Stamps `contacted_at`; visible while `new` |
   | **Convertir en ticket** | Modal (client, category, priority, assigned agent; inline client creation). Runs `App\Services\LeadConverter` — creates a `Ticket` typed `service_request` with status `assigned`, notifies the agent, marks the lead `converted`. **Idempotent** (a second attempt returns the existing ticket) |
   | **Rejeter** | Marks the lead `rejected` |

3. **Link** — the `converted` relationship links the lead to its ticket
   (`ContactMessage::convertedTicket()`). The service-specific answers (`form_data`) surface in
   the back-office edit form (read-only section), the admin e-mail notification, and the
   generated ticket description.

---

## Service-specific contact form

Each service can ship its **own set of question fields**, defined in
`config/service-form-fields.php` (keyed by service slug).

- **Field types** — `text`, `number`, `select`, `textarea` (rendered in
  `resources/views/contact.blade.php`).
- **Dynamic validation** — on submit, rules are built per service: selects are limited to their
  declared options, numbers are integers ≥ 1, and `required` fields must be filled (only for
  the selected service).
- **Safe storage** — answers are filtered to the schema keys, so stray/unknown fields cannot be
  persisted. Stored in the JSON column `contact_messages.form_data`.
- **Consumed everywhere** — `ContactMessage::formAnswers()` resolves select values to display
  labels, reused by the admin e-mail, the ticket description and Filament.

---

## Domain model

### Ticket types & approval

| Type | Requires approval? | Notes |
|---|---|---|
| `incident` | No | Something broken; standard lifecycle |
| `service_request` | Yes | New equipment, access grants, etc. |
| `problem` | No | Root-cause investigation; links incidents via `problem_incidents` |
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

**Key rule:** `closed` is terminal — only `resolved` can return to `assigned`. This is enforced
in `App\Models\Ticket::transitionTo()`, which throws a `DomainException` on any transition not
listed in `Ticket::TRANSITIONS`.

### Assets & software (CMDB)

- `assets` — physical hardware with location, warranty and serial tracking.
- `software_licenses` — seat-tracked licenses (separate lifecycle: no location/serial).
- `Asset::assignTo()` closes the previous `AssetAssignment` and opens a new one, preserving a
  full per-asset history.

---

## Back office (Filament)

Resources under `app/Filament/`:

- `ContactMessages` — lead list with status/service badges, filters and the convert workflow
  (see above). Uses the Filament v5 table API (`recordActions()`, `->schema()` action forms,
  inline client creation with `createOptionForm` / `createOptionUsing`).
- Tickets, users and other resources follow the resource-per-domain convention.

---

## Testing

```bash
php artisan test
```

Feature suites include:

| Suite | Coverage |
|---|---|
| `PublicSiteTest` | Public pages, catalog-driven service details, contact form + validation, service-specific `form_data` storage/validation, illustration guard (every service ships its own `scene-*.blade.php`) |
| `LeadConversionTest` | Lead → `assigned` `service_request` ticket with agent notification, conversion **idempotency**, contacted/rejected statuses |
| `Admin\ContactMessageAdminTest` | Back-office lead table (admin only) + edit form incl. the service-schema answers section |
| `TicketTransitionTest` | Full status state machine incl. the closed/resolved reopen rule |
| Others | Approval, AssetAssignment, policy/permission suites |

Quote database: SQLite in-memory (see `phpunit.xml`).

---

## Project structure

- `resources/views/components/scene-*.blade.php` — one hand-built SVG illustration per service
  (`scene-maintenance`, `scene-reseaux`, `scene-helpdesk`, `scene-vente`, `scene-securite`,
  `scene-formation`), resolved at runtime from the catalog.
- `resources/views/components/visual-scene.blade.php` — generic decorative scene (`/a-propos`).
- `config/public-services.php` — service catalog.
- `config/service-form-fields.php` — per-service contact form schemas.
- `app/Services/LeadConverter.php` — the lead-to-ticket service.
- `app/Models/Ticket.php` — carries the transition guard on the model (no state machine package).
- `app/Policies/` — permission-based authorization registered via `Gate::policy()`.
- `database/seeders/` — roles and permission mapping (both idempotent).
