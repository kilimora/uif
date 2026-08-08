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

## Verified figures to insert before submission

The "Impact & SDGs" section previously carried placeholder targets. Replace
those with the following figures, all drawn from the current UNICEF
Innovation Fund Expression of Interest and already reviewed against
eligibility criteria asking for "promising results from initial pilots":

- 1,547 farmers enrolled across 38 cooperative organisations, over 3,000
  total household beneficiaries, across 14 Kenyan counties.
- Murang'a County pilot, January 2023 to December 2025: 65 percent farmer
  income increase over 12 months, 70 percent reduction in environmental
  verification cost, 35 percent reduction in post harvest losses, 62 percent
  improvement in soil organic matter and water retention.
- First verified carbon credit batch, AK-2026-44, issued 820 tCO2 to Kisii
  County cooperative members.
- USD 162,800 revenue in FY2025, USD 367,400 cumulative revenue since
  inception.
- Over 60 percent of enrolled farmers are women, receiving individual mobile
  money payments directly.

These are documented pilot results, not projections, and should be presented
as such in the "Impact & SDGs" section.

Team section: use the founder bios and roles already on file. Godfrey Noel,
Founder and Strategy Lead. Matthew Muange, Technology Lead. Marylyn Mugure
Gathiru, Research and Development Lead. Hildah Gichuru, Learning and
Development Lead. Ezra Maruti, Finance Lead. Zuhra Nagib, Assurance Lead.
The 2 minute pitch video link and any adviser or media coverage sections
still need to be added once that material is ready, since none of that
exists yet to draw from.
