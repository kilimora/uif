# AgriKonnekt · UNICEF Innovation Fund — Expression of Interest Microsite

## Subdomain: `uif.kilimora.africa`

**Why `uif`:** it is the initialism of **U**NICEF **I**nnovation **F**und, the
exact opportunity this microsite exists to support. It reads clearly as
"this is our UNICEF Innovation Fund application" rather than a generic
marketing page, stays short enough to say over a call and type from memory,
and sits cleanly alongside the existing subdomain pattern:
`agrikonnekt.kilimora.africa` is the live commercial product, while
`uif.kilimora.africa` is its funding-application counterpart, a distinct
site with its own purpose rather than a competing product. It avoids any
collision with `www.kilimora.africa` or `agrikonnekt.kilimora.africa`.

The site only ever links to `https://uif.kilimora.africa`, with one
deliberate exception: the homepage and the Product section link out to
`https://agrikonnekt.kilimora.africa` directly, so a reviewer can verify the
commercial platform is real and already running.

## What is in this folder

| File | Purpose |
|---|---|
| `index.php` | The full microsite: homepage plus five linked initiative pages, all served from a single router at the top of the file (`$routes`, `$meta`). |
| `legal-pages.php` | Shared partial rendering both `/terms-of-use` and `/privacy-policy`, called from `index.php` and exiting before the main template loads. |
| `LICENSE` | BSD 3-Clause License text (OSI-approved) for the AgriKonnekt software, with notes on how to keep it current. |

## Page structure

`index.php` renders one of seven pages based on the `page` query parameter,
matched against `$routes`:

- **Home** (`/`) — hero, a Systems Access Board giving a direct link into
  every live system, the founding narrative, the product breakdown, the
  Financial Enablement spotlight and journey, the impact and SDG projections,
  the traceability and partner-ecosystem sections, Africa & Policy alignment,
  a six-point Eligibility checklist mapped directly against the fund's own
  mandatory criteria, and an Open Core section separating what is licensed
  openly from what stays part of the commercial platform.
- **`/financial-enablement`** — the explainable credit-scoring system, with a
  live interactive slider demo, a documented API, and a data-protection
  section.
- **`/child-aid-alignment`** — how the platform maps onto funder priorities
  for child-focused programming.
- **`/sport-development`** — the sister initiative covering a child's life
  outside the farm.
- **`/climate-resilience`** — hazard mapping and early warning built from the
  same verified household record.
- **`/child-resilience-index`** — the newest system: a composite score
  combining food and nutrition security, school-continuity and child-labour
  risk, and climate shock exposure, with a live public weather feed
  (Open-Meteo, no key required) feeding its climate module and an
  interactive three-module explorer.
- **`/terms-of-use`** and **`/privacy-policy`** — rendered by
  `legal-pages.php`.

## Demo-mode disclosure

The interactive sliders on `/financial-enablement` and
`/child-resilience-index` are illustrative only, built to show how the
scoring concept works rather than to reproduce the production model. Both
pages carry a visible banner directly above the sliders saying so, plainly
and before anyone touches a slider, along with a shorter note beneath the
composite score. The one genuinely live element on the Child Resilience
Index page is the weather feed itself.

## Design system

Matches the existing Kilimora brand system: **EB Garamond** for display and
editorial type, **Ubuntu** for interface and body text, a dark **void and
lime** register for the technical, subpage-driven content, and a **cream,
forest-green and gold** register for the household-facing homepage sections.

The UI favours flat, borderless surfaces over boxed containers: most cards
use a background tint or a single colored accent edge rather than a full
perimeter border, buttons carry a spring-eased hover and press animation,
and hero backgrounds use a slow, continuously drifting gradient rather than
a static one.

## Content protection

Images, video, and the site logo are set to `draggable="false"`, have
`-webkit-user-drag:none`, and the page disables right-click, text selection,
and common save/copy keyboard shortcuts (Ctrl/Cmd+S, U, C, X, P). This is a
deterrent rather than an absolute technical guarantee, a determined visitor
can still reach the page source, but it stops casual dragging, right-click
saving, and copy-pasting of media and text.

## Licensing shown on the homepage and in the footer

- **Site content (copy, photos, video):** Creative Commons Attribution 4.0
  International, linking to the archived license text specified for this
  project (`web.archive.org/.../creativecommons.org/licenses/by/4.0/`).
- **AgriKonnekt software:** BSD 3-Clause License (see `LICENSE`), an
  OSI-approved license requiring no application, fee, or registration
  number. Copyright exists automatically under Kenyan law from the moment
  the code is written, and this license simply grants reuse permission.
- **Hardware and device designs:** CERN Open Hardware Licence.
- **Design assets and documentation:** CC BY 4.0.
- The homepage's Eligibility section links out to
  `https://opensource.org/licenses` so a reviewer can independently confirm
  the licenses used are genuinely OSI-listed.
- The Open Core section states plainly which specific components carry
  these open licenses (the Child Resilience Index methodology, the climate
  data schema and API, the sensor hardware designs, the identity and consent
  protocol, and the documentation) versus what remains part of the
  commercial `agrikonnekt.kilimora.africa` platform (hosting, account
  dashboards, premium integrations, analytics, and support).

## Deploying

1. Upload `index.php`, `legal-pages.php`, and `LICENSE` to the web root
   `uif.kilimora.africa` will point at.
2. Create the `uif` subdomain in your DNS or hosting panel, pointed at that
   directory (standard cPanel "Subdomains" or a DNS CNAME plus vhost,
   depending on your host).
3. Confirm HTTPS is active for the subdomain, most hosts auto-issue
   Let's Encrypt certificates once the subdomain resolves.
4. Update the hero video, photo, email, and phone URLs if any of those
   assets move in your media library later.

## Before submission, still worth doing

- The 12-month impact numbers in the Impact & SDGs section are framed as
  **targets**, not verified pilot results. Replace or refine them once real
  baseline and pilot data are available, since the fund's eligibility
  criteria specifically ask for "promising results from initial pilots."
- Team, adviser, and media-coverage sections from the original EOI form
  (founder bios, milestones, budget, pitch video) are not fabricated here.
  Add the real team's details and the two-minute pitch video link once
  ready.
- The Open Core section commits, in writing, to open-licensing several
  specific components (the Child Resilience Index methodology and the
  sensor hardware designs among them). Confirm with whoever owns the actual
  licensing decision that Kilimora is prepared to release those components
  before this goes in front of a reviewer, since a reviewer could reasonably
  go looking for the repository.
- A tone and language pass is underway across the site, removing
  contractions, prose hyphens, and a repeated "X, not Y" phrasing pattern in
  favor of longer, plainly affirmative sentences. The homepage and the
  Financial Enablement page are furthest along; the remaining four
  initiative pages still carry the older phrasing throughout.
