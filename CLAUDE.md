# CLAUDE.md

# DOCHEK PHP SCORM Preview — Theme Parity & UI Fidelity Rules

## Project Context

You are working on a production PHP SCORM Player integrated with the DOCHEK LMS.

This is:

- NOT an Angular migration.
- NOT a SCORM rewrite.
- NOT a UI redesign.
- NOT a new theme implementation.

The SCORM functionality is already stable and MUST NOT be modified.

The primary objective is:

> Make PHP Preview/Launch render the EXACT SAME exported theme selected in Course Settings.

The Exported Theme is the visual and structural source of truth.

Preview must reuse the existing exported theme rather than recreating or approximating it.

---

# 1. Existing Architecture

## Angular

Angular is only used for the LMS template.

Angular is NOT launched inside the SCORM package.

DO NOT modify:

- Angular routing
- Angular components
- Angular services
- Angular CSS
- Angular templates
- Angular build configuration

---

## PHP

PHP is responsible for:

- Course Launch
- Preview
- Export
- JSON generation
- SCORM APIs
- Course Builder
- Tracking
- Assessment
- Theme loading

PHP Preview currently has theme/layout inconsistencies that must be corrected by reusing the exported theme implementation.

---

# 2. Theme Location

Themes are stored here:

/var/www/DOCHEK/assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/

Examples:

Default/
ModernTheme/
Vertical_ContentforU/
Knowledge_Works/
...

Each theme may contain:

content.html
css/
fonts/
images/
scripts/
json/

The exact directory contents may differ between themes.

DO NOT assume that every theme has identical files.

---

# 3. Single Source of Truth

The exported theme is the SOURCE OF TRUTH.

If Course Settings selects:

ModernTheme

then both:

Preview
AND
Export

must use:

export_themes/ModernTheme/

If Course Settings selects:

Vertical_ContentforU

then both:

Preview
AND
Export

must use:

export_themes/Vertical_ContentforU/

There must NOT be:

- a separate Preview theme
- a copied Preview CSS
- duplicated theme HTML
- duplicated theme JavaScript
- manually recreated styles
- hardcoded theme selection
- legacy fallback UI overriding the selected theme

Reuse the existing Export theme-loading mechanism wherever possible.

---

# 4. Current Core Problem

The system has historically had this behavior:

Course Settings
    ↓
Selected Theme
    ↓
Export → Correct Theme
    ↓
Preview → Old/Default/Partially Recreated Theme

This is incorrect.

Required behavior:

Course Settings
    ↓
Selected Theme
    ↓
Same Theme Resolver
    ├── Preview
    └── Export

Both must use the same:

- theme directory
- content.html
- CSS
- fonts
- images
- JavaScript
- theme assets
- layout
- UI components

---

# 5. Preview Must Match Export EXACTLY

Preview and Export must be visually indistinguishable when:

- same course
- same theme
- same viewport size
- same browser zoom
- same page
- same content/state

The user should NOT feel that Preview and Export are different players.

Do not accept:

"approximately similar"

"same style"

"close enough"

"same colors"

The requirement is:

> EXACT THEME PARITY.

---

# 6. Theme Loading

Before changing any code, trace:

Launch Button
    ↓
PHP Controller
    ↓
Preview Loader
    ↓
Course Settings
    ↓
Theme Resolver
    ↓
Selected Theme Directory
    ↓
content.html
    ↓
Theme CSS
    ↓
Theme JS
    ↓
Theme Assets

Find the existing Export implementation first.

Then reuse that implementation for Preview.

Do NOT create a second theme resolver if one already exists.

---

# 7. Analysis First

Before editing any file, identify:

1. Launch entry file
2. Controller
3. Preview loader
4. Theme selection source
5. Theme resolver
6. Export theme-loading logic
7. Preview theme-loading logic
8. content.html loading logic
9. CSS loading logic
10. JS loading logic
11. Theme asset path resolution
12. Current Preview DOM generation

Then identify:

13. SCQ rendering
14. MCQ rendering
15. Quiz rendering
16. Menu rendering
17. Transcript rendering
18. Completion indicator rendering
19. Header rendering
20. Footer rendering

Do not make CSS changes before understanding which theme actually owns the UI.

---

# 8. IMPORTANT — Menu Initial State

The Menu MUST NOT automatically appear merely because the course/page loads.

Required behavior:

Page Load
    ↓
Main content/page displayed
    ↓
Menu remains closed/hidden
    ↓
User clicks Menu
    ↓
Menu opens

The menu must only be displayed when the user explicitly clicks the Menu control.

Do NOT:

- automatically open the menu on page load
- automatically trigger the menu click during initialization
- display the menu overlay before page content finishes loading
- cause menu flash during initial rendering

Check for:

- automatic `.click()`
- initialization functions opening the menu
- default `display:block`
- incorrect `visibility`
- incorrect transform state
- CSS animation initial state
- page-load event handlers
- DOM insertion behavior

The initial state must match the Exported Theme.

---

# 9. Menu and Transcript

Menu and Transcript must use the EXACT exported theme styling.

Verify:

- Menu button width
- Menu button height
- Transcript button width
- Transcript button height
- Menu/Transcript spacing
- typography
- font family
- font size
- font weight
- text color
- background color
- active color
- hover color
- border/divider
- padding
- margin
- alignment
- icon size
- icon spacing

Menu and Transcript must have the same dimensions and visual hierarchy as Export.

If Menu and Transcript are equal-width tabs in the reference, Preview must use equal-width tabs.

Do not independently style Preview to "look similar."

Reuse the theme CSS.

---

# 10. Menu Width

The complete menu/sidebar width must match the Exported Theme.

Do not independently calculate a new width.

Verify:

- total sidebar width
- inner content width
- logo area width
- Menu/Transcript tab width
- menu item width
- footer width

The menu content must not be narrower or wider than the exported reference.

Avoid:

- fixed widths that conflict with the theme
- inline width styles
- legacy table widths
- Bootstrap column widths
- duplicate sidebar CSS

---

# 11. Menu Item Styling

Menu items must match Export exactly.

Verify:

- icon position
- icon size
- text position
- text font
- text size
- text weight
- line height
- item height
- horizontal padding
- vertical padding
- active background
- active text color
- completion icon
- completion icon color
- spacing between items

Do not approximate these values.

Use the actual theme CSS whenever possible.

---

# 12. Completion / Page Tracking

Page completion tracking is FUNCTIONAL behavior and must remain intact.

The modern Preview theme must display completion state exactly as the Exported Theme does.

For completed pages, verify:

- completion icon is displayed
- completion color matches Export
- icon position matches Export
- icon size matches Export
- completion state updates immediately when required
- state persists after navigation
- state persists after reload/resume

Do NOT change the underlying SCORM tracking logic unless a real bug is proven.

The UI must correctly consume the existing completion/tracking state.

Required flow:

Page completed
    ↓
Existing tracking logic records completion
    ↓
Preview menu receives completion state
    ↓
Completion indicator displays exactly like Export

---

# 13. Header

Preview header must match Export.

Verify:

- menu icon
- title
- divider
- title font
- title font size
- title font weight
- title color
- page counter
- power/exit icon
- spacing
- height
- padding
- alignment

Do not recreate header CSS if the exported theme already provides it.

---

# 14. Page Counter

The page counter must use the same theme styling.

Example:

5 / 8

Verify:

- position
- font
- font size
- font weight
- color
- right padding
- vertical alignment

Do not alter page-count logic.

Only correct visual rendering if required.

---

# 15. SCQ and MCQ

SCQ and MCQ must use the exact question/assessment design from the selected exported theme.

This includes:

- question card
- question text
- instruction text
- option rows
- radio/checkbox controls
- Submit button
- result state
- spacing
- padding
- margins
- typography
- colors
- borders
- shadows
- widths
- responsive behavior

SCQ:

- single-choice/radio behavior must remain unchanged.

MCQ:

- multiple-choice/checkbox behavior must remain unchanged.

---

# 16. CRITICAL — Unified Question Card

The question and answers MUST NOT appear as separate visual panels.

WRONG:

Question Panel
    ↓
Answer Panel

CORRECT:

ONE QUESTION CARD

    ┌───────────────────────────────────┐
    │ Question                          │
    │                                   │
    │ Instruction                       │
    │                                   │
    │ Option 1                          │
    │ Option 2                          │
    │ Option 3                          │
    │                                   │
    │ Submit                            │
    └───────────────────────────────────┘

The following must belong to the SAME outer visual container:

- question
- instruction
- options
- Submit button

There must NOT be:

- separate answer card
- separate question card
- duplicate background
- duplicate border
- duplicate shadow
- unwanted dividing line
- separate outer padding containers

Individual option rows may have their own border if that is part of the exported theme.

---

# 17. SCQ Structure

Required visual structure:

SCQ Question Card
    ↓
Question
    ↓
Instruction
    ↓
Radio Option 1
    ↓
Radio Option 2
    ↓
Radio Option 3
    ↓
Submit

The exact HTML/classes must come from the existing theme wherever possible.

Do not create a new SCQ visual system.

---

# 18. MCQ Structure

Required visual structure:

MCQ Question Card
    ↓
Question
    ↓
Instruction
    ↓
Checkbox Option 1
    ↓
Checkbox Option 2
    ↓
Checkbox Option 3
    ↓
Submit

The exact HTML/classes must come from the existing theme wherever possible.

Do not create a separate custom MCQ design.

---

# 19. Question / Option Width

Question and answer options must use the same parent content width.

Do NOT allow:

Question:
    50% width

Options:
    100% width

or:

Question:
    one container

Options:
    unrelated container

Required:

Question Card
    ↓
Common Content Container
    ├── Question
    ├── Instruction
    ├── Options
    └── Submit

Option rows should use the full available content width defined by the exported theme.

---

# 20. SCQ / MCQ Padding

Padding is part of the theme and must match Export.

Verify:

Outer card:

- top padding
- right padding
- bottom padding
- left padding

Question:

- position
- margin
- line height

Instruction:

- margin-top
- margin-bottom

Options:

- option-to-option spacing
- option height
- internal horizontal padding
- internal vertical padding

Submit:

- margin-top
- button height
- button width
- button padding

Do not compensate for incorrect parent structure by adding random margins.

Fix the correct parent container.

---

# 21. Typography

Preview must reuse the exported theme's typography.

Verify for SCQ, MCQ, Menu, Transcript, Header and other theme components:

- font family
- font size
- font weight
- line height
- letter spacing
- text color
- italic style where applicable

Do not guess values from screenshots.

Find the actual CSS rule in the exported theme.

---

# 22. Colors

Use the actual exported theme colors.

Verify:

- page background
- card background
- question text
- instruction text
- option text
- option border
- active option
- selected option
- Submit button
- disabled button
- Menu
- Transcript
- active menu item
- completion indicator
- header
- footer

Do not invent replacement colors.

---

# 23. Option Rows

SCQ/MCQ option rows must match Export.

Verify:

- width
- height
- border
- border radius
- background
- padding
- radio/checkbox size
- control alignment
- text alignment
- font size
- spacing

Selected state must remain functional and visually match Export.

---

# 24. Submit Button

Submit must match the exported theme.

Verify:

- width
- height
- padding
- font
- font size
- font weight
- border radius
- background
- text color
- disabled state
- hover state
- margin-top

Do not change submission functionality.

---

# 25. Result / View Result

If the theme displays:

View Result

or another result control, reproduce the exact exported styling.

Do not treat a disabled state as a CSS bug if the Export theme uses a disabled button.

Verify the actual functional state before changing styling.

---

# 26. Quiz / Assessment Functionality

Never modify:

- question scoring
- correct answers
- incorrect answers
- attempts
- retry
- passing score
- timer
- assessment result
- completion
- SCORM interactions

Only modify visual markup/CSS integration if required to match the exported theme.

---

# 27. Course Types

The theme parity must work for:

- Welcome
- AV
- Pretest
- Video
- CYU
- SCQ
- MCQ
- Quiz
- Summary
- other existing page types

Do not optimize only for one screenshot.

---

# 28. Responsive Requirements

Verify at minimum:

## Desktop

1366 × 768
1440 × 900
1920 × 1080
2560 × 1440

## Tablet

1366 × 1024
1180 × 820
820 × 1180

## Mobile

390 × 844
844 × 390

Preview and Export must maintain the same responsive behavior.

Do not use absolute positioning to reproduce screenshots.

Do not introduce viewport-specific hacks unless the exported theme itself uses them.

---

# 29. Browser Zoom

For visual comparisons use:

100% browser zoom.

If Preview and Export are compared, use:

- same browser
- same zoom
- same viewport size
- same course
- same theme
- same page
- same state

Do not interpret different viewport sizes as CSS defects.

---

# 30. DOM and CSS Audit

Before changing a visual issue, inspect:

- generated DOM
- parent/child relationships
- computed styles
- CSS source
- stylesheet order
- specificity
- inline styles
- inherited properties
- Bootstrap rules
- theme CSS

For every real defect document:

FILE
LINE
SELECTOR
PROPERTY
CURRENT VALUE
CASCADE OWNER
WHY IT IS WRONG
REFERENCE RULE
FIX

Do not blindly add CSS.

---

# 31. CSS Cascade Rules

Do NOT use:

!important

unless there is clear proof that:

1. The correct theme rule cannot win through normal cascade.
2. A cleaner structural solution is impossible.
3. The reason is documented.

Prefer:

- correct DOM structure
- correct stylesheet order
- correct selector
- reuse of existing theme selectors
- removal of conflicting legacy styles

---

# 32. No CSS Duplication

DO NOT:

- copy exported CSS into Preview
- create duplicate theme CSS
- create Preview-specific copies of theme styles
- manually reproduce dozens of theme rules
- create a second design system

If the exported theme already contains the correct rule:

USE IT.

---

# 33. No HTML Duplication

DO NOT copy the complete exported content.html into a new Preview template.

Instead:

Reuse the existing theme content/loader architecture.

If Preview needs the exported HTML, load the actual exported theme resource.

There must be one source of truth.

---

# 34. Legacy UI Removal

If old/default Preview UI is still being injected, identify the source.

Check:

- old templates
- legacy CSS
- old JavaScript
- PHP-generated markup
- inline styles
- hardcoded HTML
- default theme fallback
- old Reload SCORM player components

Remove or bypass only the obsolete Preview path required to allow the selected exported theme to render.

Do not remove functionality.

---

# 35. Export Theme Must Remain Read-Only

DO NOT modify:

/export_themes/

unless explicitly instructed by the user.

Exported themes are the source of truth.

Preview must adapt to the theme.

Do not change the reference implementation merely to make Preview easier to implement.

---

# 36. Forbidden Changes

❌ Angular

❌ Angular CSS

❌ Angular routing

❌ SCORM API

❌ SCORM 1.2 implementation

❌ SCORM 2004 implementation

❌ Bookmarking

❌ Suspend Data

❌ Resume logic

❌ Completion tracking logic

❌ Quiz scoring

❌ Assessment logic

❌ Attempts

❌ JSON structure

❌ Database schema

❌ Export functionality

❌ Course Builder logic

❌ Navigation logic

❌ Theme redesign

❌ New design system

❌ Copying theme CSS

❌ Copying theme HTML

❌ Modifying export_themes

❌ Unrelated course changes

❌ Unrelated theme changes

---

# 37. Allowed Changes

✔ Preview theme resolver

✔ Preview theme loader

✔ Preview content.html loading

✔ Preview CSS loading

✔ Preview JavaScript loading

✔ Preview asset path resolution

✔ Preview DOM integration required by the exported theme

✔ Preview-only CSS conflicts when proven

✔ Preview menu initial state

✔ Preview menu/transcript integration

✔ Preview visual completion indicator integration

✔ Preview SCQ/MCQ visual container integration

✔ Preview layout corrections required for exact theme parity

---

# 38. Multi-Agent / Audit Methodology

When performing a major Preview visual correction, use the following workflow.

## Phase 1 — Audit

Inspect independently:

- SCQ
- MCQ
- Menu
- Transcript
- Header
- Footer
- Completion indicators
- Theme loading
- CSS cascade
- DOM structure

Every finding must identify:

1. File
2. Line
3. Selector/markup
4. Current behavior
5. Expected behavior
6. Source-of-truth rule
7. Cascade ownership
8. Why the rule wins
9. Required change
10. Risk
11. Functional impact
12. Verification method

---

## Phase 2 — Fix

Before modifying a finding:

- re-check the actual source
- confirm cascade ownership
- confirm it is Preview-only
- confirm export_themes is not modified
- use the smallest possible fix
- reuse existing theme rules

Do not perform speculative fixes.

---

## Phase 3 — Re-Audit

After changes, inspect the same areas again.

Verify:

- original issue resolved
- no new CSS conflict
- no duplicate styles
- no DOM imbalance
- no JavaScript selector breakage
- no tracking regression
- no menu regression
- no unrelated theme changes

---

## Phase 4 — Synthesis

For every finding classify:

- RESOLVED
- STILL OPEN
- NOT ATTEMPTED
- NEW REGRESSION

Do not claim complete parity if any known visual mismatch remains.

---

# 39. Git Safety

Before modification:

git status

After modification:

git status
git diff --stat
git diff

Verify:

- export_themes unchanged
- Angular unchanged
- unrelated themes unchanged
- unrelated courses unchanged
- SCORM functionality unchanged
- only required Preview files changed

If unexpected files change:

STOP and investigate.

---

# 40. Verification Checklist

## Theme

[ ] Selected theme correctly identified

[ ] Preview uses selected theme

[ ] Export uses same theme

[ ] Same content.html

[ ] Same CSS

[ ] Same fonts

[ ] Same assets

[ ] Same JavaScript where applicable

---

## Menu

[ ] Menu closed on initial page load

[ ] Menu opens only after user clicks Menu

[ ] Menu width matches Export

[ ] Menu item width matches Export

[ ] Menu item height matches Export

[ ] Menu font matches Export

[ ] Menu colors match Export

[ ] Menu padding matches Export

[ ] Completion indicators match Export

---

## Transcript

[ ] Transcript does not open automatically

[ ] Transcript opens only after user interaction

[ ] Width matches Export

[ ] Height matches Export

[ ] Typography matches Export

[ ] Colors match Export

[ ] Padding matches Export

---

## Header

[ ] Icon matches

[ ] Title matches

[ ] Divider matches

[ ] Font matches

[ ] Page counter matches

[ ] Exit/power control matches

[ ] Header height matches

---

## SCQ

[ ] One unified question card

[ ] Question and options are not separate panels

[ ] Question width matches options

[ ] Instruction spacing matches

[ ] Option width matches

[ ] Option padding matches

[ ] Option height matches

[ ] Radio size matches

[ ] Submit width matches

[ ] Submit height matches

[ ] Submit padding matches

[ ] Font matches

[ ] Colors match

---

## MCQ

[ ] One unified question card

[ ] Question and options are not separate panels

[ ] Question width matches options

[ ] Instruction spacing matches

[ ] Option width matches

[ ] Option padding matches

[ ] Option height matches

[ ] Checkbox size matches

[ ] Submit width matches

[ ] Submit height matches

[ ] Submit padding matches

[ ] Font matches

[ ] Colors match

---

## Tracking

[ ] Page completion still works

[ ] Completion indicator appears

[ ] Completion persists

[ ] SCORM tracking unchanged

[ ] Resume unchanged

[ ] Bookmarking unchanged

[ ] Attempts unchanged

[ ] Quiz scoring unchanged

---

## Responsive

[ ] 1366 × 768

[ ] 1440 × 900

[ ] 1920 × 1080

[ ] 2560 × 1440

[ ] 1366 × 1024

[ ] 1180 × 820

[ ] 820 × 1180

[ ] 390 × 844

[ ] 844 × 390

---

# 41. Visual Comparison Rule

When comparing Preview and Export:

Use the same:

- course
- theme
- page
- browser
- viewport
- zoom
- content
- completion state

Do not compare different courses or different page states and conclude that font size/content is wrong.

For valid visual comparison:

Preview
VS
Export

must represent the SAME underlying content/state.

---

# 42. Acceptance Criteria

The implementation is complete only when:

Course Settings
    ↓
Select Theme
    ↓
Launch Preview
    ↓
Preview loads selected exported theme
    ↓
Menu remains closed initially
    ↓
User clicks Menu
    ↓
Menu opens exactly like Export
    ↓
User opens pages
    ↓
SCQ/MCQ layout matches Export
    ↓
Question + instruction + options + Submit
are contained in ONE unified question card
    ↓
Completion indicators match Export
    ↓
Transcript matches Export
    ↓
Header matches Export
    ↓
Fonts/colors/sizes/padding/spacing match Export
    ↓
Responsive behavior matches Export
    ↓
SCORM tracking remains unchanged
    ↓
Export still works unchanged

---

# 43. Output Format

Always report:

## Analysis

Root cause and evidence.

---

## Files to Change

Exact filenames only.

---

## Why

One sentence per file.

---

## Patch

Minimal changes only.

---

## Verification

Exact tests performed.

---

## Visual Parity

Report:

- Menu
- Transcript
- Header
- SCQ
- MCQ
- Completion
- Typography
- Colors
- Padding
- Spacing
- Width
- Responsive behavior

---

## Functional Regression

Confirm:

- SCORM
- completion
- bookmarking
- resume
- quiz
- attempts
- scoring
- navigation

---

## Git Verification

Report:

- modified files
- untouched files
- export_themes status
- diff summary

---

## Risk

Low / Medium / High

Explain why.

---

# 44. Final Principle

DO NOT recreate the Exported Theme.

DO NOT approximate the Exported Theme.

DO NOT redesign the Exported Theme.

DO NOT modify the Exported Theme to accommodate Preview.

Instead:

> Make Preview consume and render the exact selected Exported Theme.

There must be one visual source of truth.

Preview and Export must behave as two entry points into the same theme implementation.

The user's visual expectation is:

> "If I open Preview and Export using the same course, theme, page, viewport and state, I should not be able to tell which one is Preview and which one is Export."

That is the definition of success.