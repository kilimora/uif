# AgriKonnekt · UNICEF Innovation Fund — Expression of Interest Microsite

## Suggested subdomain: `uif.kilimora.africa`

**Why `uif`:** it's the initialism of **U**NICEF **I**nnovation **F**und — the
exact opportunity this microsite exists to support. It:

- reads clearly as "this is our UNICEF Innovation Fund application," not a
  generic marketing page;
- is short, easy to say over a call, and easy to type from memory;
- sits cleanly alongside your existing subdomain pattern
  (`agrikonnekt.kilimora.africa` is the live product — `uif.kilimora.africa`
  is clearly the funding-application counterpart, not a competing product);
- avoids any collision with `www.kilimora.africa` or `agrikonnekt.kilimora.africa`.

The site only ever links to `https://uif.kilimora.africa` — no alternate
subdomain suggestions are shown to visitors, per your request.

## What's in this folder

| File | Purpose |
|---|---|
| `index.php` | The full EOI microsite — hero video, challenge, AgriKonnekt solution, impact/SDG chart, AU Agenda 2063 / Malabo / Kenya Vision 2030 / EAC Vision 2050 policy alignment, 12-month roadmap, household targeting, open-source licensing section, contact, and footer. |
| `privacy-policy.php` | Full Privacy Policy, styled to match the main site. |
| `terms-of-use.php` | Full Terms of Use, including content-protection and open-source licensing clauses, styled to match the main site. |
| `LICENSE` | BSD 3-Clause License text (OSI-approved) for the AgriKonnekt software, with notes on how to keep it current. |

## Fonts & brand

Matches the existing Kilimora brand system: **EB Garamond** for display/
editorial type, **Ubuntu** for interface/body text, and the same cream /
forest-green / gold palette used on `kilimora-homepage.php`.

## Content protection

Images, video, and the site logo are set to `draggable="false"`, have
`-webkit-user-drag:none`, and the page disables right-click, text selection,
and common save/copy keyboard shortcuts (Ctrl/Cmd+S, U, C, X, P). This is a
deterrent, not an absolute technical guarantee — determined users can still
access page source — but it stops casual dragging, right-click-saving, and
copy-pasting of media and text.

## Licensing shown in the footer

- **Site content (copy, photos, video):** Creative Commons Attribution 4.0
  International, linking to the archived license text you specified
  (`web.archive.org/.../creativecommons.org/licenses/by/4.0/`).
- **AgriKonnekt software:** BSD 3-Clause License (see `LICENSE`), an
  OSI-approved license — no application, fee, or registration number needed;
  copyright exists automatically under Kenyan law the moment the code is
  written, and this license simply grants reuse permission.
- **Hardware / design assets:** referenced as CERN-OHL and CC BY 4.0
  respectively, per the eligibility text you provided.
- The "Open Source" section links out to `https://opensource.org/licenses`
  so reviewers can verify the license is genuinely OSI-listed.

## Deploying

1. Upload `index.php`, `privacy-policy.php`, `terms-of-use.php`, and `LICENSE`
   to the web root you'll point `uif.kilimora.africa` at.
2. Create the `uif` subdomain in your DNS/hosting panel, pointed at that
   directory (standard cPanel "Subdomains" or DNS CNAME + vhost, depending on
   your host).
3. Confirm HTTPS is active for the subdomain (most hosts auto-issue
   Let's Encrypt certs once the subdomain resolves).
4. Update the hero video, photo, email, and phone URLs if any of those
   assets move in your media library later.

## Things you'll want to fill in before submission

- The 12-month impact numbers in the "Impact & SDGs" section are framed as
  **targets**, not verified pilot results — replace/refine them once real
  baseline and pilot data are available, since the Fund's eligibility
  criteria ask specifically for "promising results from initial pilots."
- Team, adviser, and media-coverage sections from the original EOI form
  (founder bios, milestones, budget, pitch video) aren't fabricated here —
  add your real team's details and the 2-minute pitch video link once ready.
