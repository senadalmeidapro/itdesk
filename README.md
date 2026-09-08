<div align="center">

# 🛠️ TAKTIC

### itdesk — IT Service Management Platform

**A fullstack platform in a single Laravel application** for a French IT services company.

| 🌐 Public site | 🎯 Per-service lead capture | 🔁 Lead → ticket pipeline | 🖥️ Filament back office |
|:--:|:--:|:--:|:--:|

[Features](#-features) · [Stack](#-stack) · [Getting Started](#-getting-started) · [Workflow](#-lead--ticket-workflow) · [Domain](#-domain-model) · [Back office](#-back-office-filament) · [Testing](#-testing)

---

</div>

## ✨ Features

<div align="center">

| Route | Description |
|:--|:--|
| <code>GET /</code> | 🏠 Home |
| <code>GET /services</code> | 📦 Service catalog |
| <code>GET /services/{slug}</code> | 📄 Dedicated service page (404 on unknown slug) |
| <code>GET /a-propos</code> | ℹ️ "About us" page |
| <code>GET / POST /contact</code> | 📨 Smart contact form (see below) |

</div>

**📦 Service catalog** — fully driven by `config/public-services.php` (6 services).
Each entry contains `slug`, `name`, `short`, `headline`, `description`, `icon`,
`illustration` (a **dedicated SVG scene per service**), `tone`, `metrics` and `features`.
Every public page renders from this file: the imagery and accents automatically follow
each service.

---

## 🧰 Stack

| 🧱 Layer | Choice |
|:--|:--|
| **Framework** | Laravel — fullstack monolith, server-rendered (Blade/Livewire/Filament) |
| **Reactive UI (client portal)** | Livewire — full-page components under `app/Livewire/` |
| **UI components** | Flux UI, Tailwind CSS v4, Vite (`npm run build`) |
| **Public layouts** | `resources/views/layouts/public-web.blade.php` |
| **Back office** | Filament admin panel at `/admin` (`app/Filament/`) |
| **Authentication** | Laravel Fortify (session) |
| **RBAC** | Spatie laravel-permission |

> ⚠️ **Not a JSON API** — everything is server-rendered behind Fortify's session auth.
> No active `routes/api.php` surface.

---

## 🚀 Getting Started

```bash
# 1. Dependencies
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate

# 3. Database
php artisan migrate

# 4. Assets
npm run build            # (or: npm run dev)

# 5. Roles, permissions and their mapping
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=PermissionsSeeder
```

> ✅ `RolesAndPermissionsSeeder` creates the 4 roles; `PermissionsSeeder` grants every
> permission to `admin` and fine-grained sets to the other roles. **Both idempotent** — safe
> to re-run.

### 👥 Assigning a role to a user

```bash
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->assignRole('admin'); // or: agent, network_tech, requester
```

---

## 🔐 Roles & permissions

<div align="center">

| 👤 Role | 🛡️ Can do |
|:--|:--|
| `requester` | Create tickets, view/edit own tickets (while open), comment |
| `agent` | Manage all tickets, transition statuses, internal comments, view assets |
| `network_tech` | Everything `agent` can, plus create/update/assign assets |
| `admin` | Everything — deletions, ticket approval, back-office lead management |

</div>

**Policies** (`TicketPolicy`, `AssetPolicy`) check **permissions**
(`$user->can('tickets.transition')`), not raw role names — so access can be fine-tuned per user
via `givePermissionTo(...)` without touching policy code. Full list in
`database/seeders/PermissionsSeeder.php`.

---

## 🔁 Lead → ticket workflow

Public submissions (`contact_messages`) are **leads**, not accounts.
Simple state machine handled through the Filament back office:

```
 new ──────► contacted ──────► converted | rejected
```

*(see `App\Models\ContactMessage::STATUSES`)*

**1. 📥 Capture** — the visitor submits the form (`ContactController@store`). Fields: optional
`service_slug` (validated against `config/public-services.php`), per-service custom fields,
and `status` defaults to `new`. Answers outside the schema are **discarded server-side**.

**2. ⚙️ Processing** — admins see the list at `/admin/contact-messages`:

<div align="center">

| Action | Effect |
|:--|:--|
| **📞 Mark contacted** | Timestamps `contacted_at`; visible while `new` |
| **🎟️ Convert to ticket** | Modal (client, category, priority, agent; inline client creation). Runs `App\Services\LeadConverter`: creates a `Ticket` typed `service_request` with status `assigned`, notifies the agent, marks the lead `converted`. **Idempotent** (a second attempt returns the existing ticket) |
| **⛔ Reject** | Marks the lead `rejected` |

</div>

**3. 🔗 Link** — the `converted` relationship links the lead to its ticket
(`ContactMessage::convertedTicket()`). The `form_data` answers appear in the back-office edit
form (read-only section), the admin e-mail notification, and the generated ticket description.

---

## 📋 Service-specific contact form

Each service can carry **its own set of questions**, defined in
`config/service-form-fields.php` (keyed by service slug).

- 🧩 **Field types** — `text`, `number`, `select`, `textarea` (rendered in
  `resources/views/contact.blade.php`).
- ⚡ **Dynamic validation** — on submit, rules are built per service: selects are limited to
  their declared options, numbers are integers ≥ 1, and `required` fields must be filled (only
  for the selected service).
- 🔒 **Safe storage** — answers are filtered to the schema keys, so no unknown field can be
  persisted. Stored in the JSON column `contact_messages.form_data`.
- 📤 **Consumed everywhere** — `ContactMessage::formAnswers()` resolves select values to display
  labels, reused by the admin e-mail, the ticket description and Filament.

---

## 🧠 Domain model

### 🎫 Ticket types & approval

<div align="center">

| Type | Approval? | Notes |
|:--|:--:|:--|
| `incident` | ❌ No | Something broken; standard lifecycle |
| `service_request` | ✅ Yes | New equipment, access grants, etc. |
| `problem` | ❌ No | Root-cause investigation; links incidents via `problem_incidents` |
| `change` | ✅ Yes | Carries a `ChangeDetail` record (risk, schedule, rollback plan) |

</div>

### 🔄 Ticket status state machine

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

### 🖥️ Assets & software (CMDB)

- `assets` — physical hardware with location, warranty and serial tracking.
- `software_licenses` — seat-tracked licenses (separate lifecycle: no location/serial).
- `Asset::assignTo()` closes the previous `AssetAssignment` and opens a new one, preserving a
  full per-asset history.

---

## 🖥️ Back office (Filament)

Resources under `app/Filament/`:

- `ContactMessages` — lead list with status/service badges, filters and the convert workflow
  (see above). Uses the Filament v5 table API (`recordActions()`, `->schema()` action forms,
  inline client creation with `createOptionForm` / `createOptionUsing`).
- Tickets, users and other resources follow the resource-per-domain convention.

---

## 🧪 Testing

```bash
php artisan test
```

<div align="center">

| Suite | Coverage |
|:--|:--|
| `PublicSiteTest` | Public pages, catalog-driven service details, contact form + validation, `form_data` storage/validation, illustration guard |
| `LeadConversionTest` | Lead → `service_request` `assigned` ticket + agent notification, **idempotency**, contacted/rejected statuses |
| `Admin\ContactMessageAdminTest` | Lead table (admin only) + edit form incl. the answers section |
| `TicketTransitionTest` | Full state machine incl. the closed/resolved reopen rule |
| Others | Approval, asset assignment, policy/permission suites |

</div>

> 💾 Test database: SQLite in-memory (see `phpunit.xml`).

---

## 📁 Project structure

- 🎨 `resources/views/components/scene-*.blade.php` — one dedicated SVG illustration per service
  (`scene-maintenance`, `scene-reseaux`, `scene-helpdesk`, `scene-vente`, `scene-securite`,
  `scene-formation`), resolved at runtime from the catalog.
- 🖼️ `resources/views/components/visual-scene.blade.php` — generic decorative scene (used by
  `/a-propos`).
- ⚙️ `config/public-services.php` — service catalog.
- 📄 `config/service-form-fields.php` — per-service contact form schemas.
- 🔧 `app/Services/LeadConverter.php` — the lead → ticket service.
- 🎫 `app/Models/Ticket.php` — carries the transition guard on the model (no state machine
  package).
- 🛡️ `app/Policies/` — permission-based authorization registered via `Gate::policy()`.
- 🗄️ `database/seeders/` — roles and permission mapping (both idempotent).

---

<div align="center">

### 🚀 Built with

| <img src="https://laravel.com/img/logomark.min.svg" width="32" /> | <img src="https://livewire.laravel.com/img/livewire.min.svg" width="32" /> | <img src="https://filamentphp.com/images/favicon.png" width="32" /> | Tailwind |
|:--:|:--:|:--:|:--:|
| Laravel | Livewire | Filament | CSS v4 |

*© TAKTIC — Internal IT service management platform.*

</div>
