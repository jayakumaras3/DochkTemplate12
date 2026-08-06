# CLAUDE.md

# Project Context

You are working on a production PHP SCORM Player integrated with the DOCHEK LMS.

This is NOT an Angular migration.

This is NOT a SCORM rewrite.

This is NOT a UI redesign.

The SCORM functionality is already stable and MUST NOT be modified.

Your task is ONLY to make the PHP Preview (Launch) use the same exported theme selected in Course Settings, exactly as the Export process already does.

---

# Existing Architecture

## Angular

Angular is only used for the LMS template.



Angular is NOT launched inside the SCORM package.

Do NOT modify Angular.

Do NOT modify Angular routing.

Do NOT modify Angular CSS.

Do NOT modify Angular components.

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

PHP Preview currently loads a fixed/default theme.

Export already loads the selected theme correctly.

---

# Theme Location

Themes are stored here:

/var/www/DOCHEK/assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/

Example

Default/
ModernTheme/
Vertical_ContentforU/
Knowledge_Works/
...

Each theme contains

content.html
css/
fonts/
images/
scripts/
json/

---

# Current Problem

Export:

Uses selected theme.

Preview:

Always loads old/default player UI.

Example

User selects

ModernTheme

↓

Export

ModernTheme ✅

↓

Preview

Old Reload Theme ❌

This is incorrect.

---

# Expected Behaviour

Preview must use EXACTLY the same theme as Export.

If selected theme is

ModernTheme

Preview loads

export_themes/ModernTheme/

If selected theme is

Vertical_ContentforU

Preview loads

export_themes/Vertical_ContentforU/

Nothing should be hardcoded.

---

# Important

DO NOT copy CSS.

DO NOT duplicate themes.

DO NOT recreate styles.

DO NOT rewrite HTML.

Use the existing exported theme exactly as Export does.

There must be only one source of truth.

---

# Preserve Everything

These modules are production code.

Never modify them unless absolutely required.

- SCORM 1.2
- SCORM 2004
- Bookmarking
- Suspend Data
- Resume
- Completion
- Quiz
- Tracking
- Navigation
- JSON generation
- Course Builder
- Assessment
- PHP APIs
- Angular
- Database

---

# Before Changing Code

First trace the Launch flow.

Find

Launch Button

↓

PHP Controller

↓

Preview Loader

↓

Theme Resolver

↓

content.html Loader

Do not modify code until you know exactly where the preview theme is selected.

---

# Goal

Reuse the Export theme-loading logic.

Do not create another implementation.

Preview should call the same theme resolver already used during Export.

---

# Analysis First

Before editing any file:

List

1. Launch entry file

2. Controller

3. Theme selection logic

4. Export theme logic

5. Preview theme logic

6. Files that must change

7. Files that must NOT change

If Preview already has access to selected theme information, reuse it.

---

# Change Strategy

Smallest possible changes.

Maximum reuse.

No refactoring.

No architectural changes.

No formatting-only edits.

No unrelated cleanup.

---

# Search Targets

Search for

launch

preview

theme

content.html

export_theme

theme_name

course_theme

template

Vertical_ContentforU

ModernTheme

Default

---

# Allowed Changes

✔ Preview theme loader

✔ Theme resolver

✔ Path resolution

✔ Dynamic CSS loading

✔ Dynamic content.html loading

✔ Theme asset resolution

---

# Forbidden Changes

❌ Angular

❌ SCORM API

❌ JSON structure

❌ Database schema

❌ Export functionality

❌ Quiz

❌ Tracking

❌ Bookmarking

❌ Navigation

❌ CSS redesign

❌ UI redesign

---

# If a Change Is Risky

STOP.

Explain

- why

- impacted files

- alternative solution

Do not continue automatically.

---

# Output Format

Always produce

## Analysis

Root cause

---

## Files to Change

exact filenames only

---

## Why

One sentence each

---

## Patch

Minimal code changes

---

## Verification

How to verify

---

## Risk

Low / Medium / High

---

# Success Criteria

Selecting any theme inside Course Settings

↓

Click Launch

↓

Preview loads that selected theme

↓

Export loads same theme

↓

Both Preview and Export look identical

↓

No SCORM functionality changes

↓

No Angular changes

↓

No duplicated CSS

↓

No duplicated HTML

↓

Single source of truth