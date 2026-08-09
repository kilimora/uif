# AgriKonnekt · UNICEF Innovation Fund Microsite

Kilimora's Expression of Interest for the UNICEF Innovation Fund, and the
public home of AgriKonnekt's open systems: climate intelligence, verified
farmer identity, explainable credit scoring, market coordination, and the
Child Resilience Index.

**Live at:** `uif.kilimora.africa`
**Commercial platform:** `agrikonnekt.kilimora.africa`

## What This Is

`uif.kilimora.africa` documents the open, publicly fundable layer of
AgriKonnekt: the systems, data, and licensing that exist independently of
Kilimora as a company. `agrikonnekt.kilimora.africa` is the commercial
platform that funds and operates day to day for cooperatives, lenders, and
buyers. The two sites serve two audiences with one shared foundation, the
same verified household record.

The subdomain `uif` stands for UNICEF Innovation Fund, the opportunity this
site was built to support. Every link on the site stays within
`uif.kilimora.africa`, with one exception: the homepage links directly to
`agrikonnekt.kilimora.africa`, so a reviewer can see the commercial platform
running in production.

## Repository Contents

| File | Purpose |
|---|---|
| `index.php` | The full microsite. Home plus five initiative pages, served from a single router (`$routes`, `$meta`). |
| `legal-pages.php` | Terms of Use and Privacy Policy, rendered at `/terms-of-use` and `/privacy-policy`. |
| `LICENSE` | BSD 3-Clause License for the AgriKonnekt software (OSI-approved). |

## Site Map

- **Home** — the Systems Access Board links directly into every live
  system. Below it: the founding narrative, the product architecture, a
  Financial Enablement spotlight, first-year impact projections against the
  SDGs, household traceability, the nine-category partner ecosystem, Africa
  & Policy alignment, a six-point Eligibility checklist mapped against the
  Fund's own criteria, and the Open Core breakdown separating what is
  openly licensed from what powers the commercial platform.
- **`/financial-enablement`** — an explainable credit-scoring engine built
  on eight everyday signals, with a live interactive demo and a documented
  API.
- **`/child-aid-alignment`** — how the platform maps onto the priorities
  child-focused funders and implementers already score against.
- **`/sport-development`** — courts, coaches, mentors, arts, and vocational
  pathways, extending into a new Education & Agribusiness Pathway: an
  education hub built with cooperative partners, opening with Murang'a
  Farmers Cooperative Union, that trains high schoolers and post-secondary
  students in agribusiness, using sport, nutrition, and meals as the
  incentive that keeps them enrolled. AgriKonnekt leads as the technology
  partner behind the hub.
- **`/climate-resilience`** — hazard mapping and early warning, built on
  the same verified household record.
- **`/child-resilience-index`** — a composite score spanning food and
  nutrition security, school continuity, child-labour risk, and climate
  shock exposure, fed by a live public weather feed and explored through an
  interactive three-module demo.
- **`/terms-of-use`** and **`/privacy-policy`**.

Every initiative page shares one hero pattern: a background video, an
animated line overlay in that page's own accent colour, then badge, title,
lead text, calls to action, key stats, and SDG alignment, in that order.

## Where The Site Stands Today

AgriKonnekt runs as a scaled pilot in Kenya, built to scale directly across
Sub-Saharan Africa's smallholder economies. Two things on the site are
explicitly framed as targets rather than results: the first-year impact
figures in the Impact & SDGs section, and the Murang'a education hub, dated
as a Year Two milestone on the homepage roadmap. Both are stated as such
directly on the page.

The interactive scoring demos on `/financial-enablement` and
`/child-resilience-index` are illustrative models, built to show how the
scoring concept works. Both carry a visible banner directly above the
sliders saying so. The live weather feed powering the Child Resilience
Index climate module is real, drawn from Open-Meteo in real time.

## Design System

**EB Garamond** for display and editorial type, **Ubuntu** for interface
and body text. A dark void-and-lime register carries the technical,
subpage content; a cream, forest-green, and gold register carries the
household-facing homepage sections.

Cards use flat background tint with no borders. Buttons animate on hover
and press. Hero backgrounds carry a slow, continuously drifting gradient.
Every hero box grows to fit its content rather than clipping it, at any
zoom level or window size. A floating section counter tracks position on
the page as you scroll.

## Content Protection

Images, video, and the site logo are set to `draggable="false"`, with
right-click, text selection, and standard save or copy shortcuts (Ctrl/Cmd
+ S, U, C, X, P) disabled. This deters casual copying; it is not a
substitute for the licensing terms below, which govern actual reuse rights.

## Licensing

- **Software:** BSD 3-Clause License (`LICENSE`), OSI-approved.
- **Hardware and device designs:** CERN Open Hardware Licence.
- **Content, documentation, and design assets:** Creative Commons
  Attribution 4.0 International.

The homepage's Eligibility section links directly to
`opensource.org/licenses` for independent verification. The Open Core
section names the exact components released under open licence, the Child
Resilience Index methodology, the climate data schema and API, the sensor
hardware designs, and the identity and consent protocol, distinct from the
commercial platform's hosting, dashboards, integrations, and support.

## Deployment

1. Upload `index.php`, `legal-pages.php`, and `LICENSE` to the web root for
   `uif.kilimora.africa`.
2. Point the `uif` subdomain at that directory through your DNS or hosting
   panel.
3. Confirm HTTPS is active for the subdomain.
4. Update hero video, photo, email, and phone URLs as those assets move.
