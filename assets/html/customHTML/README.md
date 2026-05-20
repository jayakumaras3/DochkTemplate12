# Modular eLearning Page Architecture

## Folder Structure

```
elearning/
└── page_001/                   ← Self-contained page folder
    ├── index.html              ← Single HTML shell (never changes)
    ├── page.json               ← All page data & configuration
    ├── page.css                ← Styles driven by CSS variables
    ├── page.js                 ← Modular rendering engine
    └── assets/
        ├── hero.jpg            ← Local images
        ├── video.mp4           ← Local videos
        └── placeholder.svg    ← Fallback image
```

---

## JSON Configuration Reference

### `meta`
| Key | Type | Description |
|-----|------|-------------|
| `pageId` | string | Unique page identifier |
| `version` | string | Schema version |
| `title` | string | Browser tab title |

### `theme`
| Key | Type | Description |
|-----|------|-------------|
| `primaryColor` | hex | Brand primary colour |
| `accentColor` | hex | Accent / highlight colour |
| `bgPage` | hex | Page background |
| `bgCard` | hex | Card background |
| `borderRadius` | css | Default border radius |
| `fontFamily` | css | Body font stack |
| `fontFamilyHeading` | css | Heading font stack |

### `layout`
| Key | Values | Description |
|-----|--------|-------------|
| `type` | `image-left` \| `image-right` \| `top-image` \| `bottom-image` \| `full-content` | Layout preset |
| `flexDirection` | `row` \| `row-reverse` \| `column` \| `column-reverse` | Override flex dir |
| `mediaRatio` | `%` | Width of media panel |
| `contentRatio` | `%` | Width of content panel |
| `gap` | `px` | Gap between panels |
| `padding` | `px` | Card padding |
| `maxWidth` | `px` | Container max width |

### `header`
| Key | Type | Description |
|-----|------|-------------|
| `visible` | bool | Show/hide header |
| `title` | string | Page title text |
| `titleAlignment` | `left` \| `center` \| `right` | Title alignment |
| `accentBar` | bool | Show coloured underline bar |

### `media`
| Key | Type | Description |
|-----|------|-------------|
| `visible` | bool | Show/hide media panel |
| `type` | `image` \| `video` \| `none` | Media type |
| `src` | string | Path to media file |
| `alt` | string | Accessibility alt text |
| `aspectRatio` | css | e.g. `4/3`, `16/9` |
| `borderRadius` | css | Image corner radius |

### `content`
| Key | Type | Description |
|-----|------|-------------|
| `visible` | bool | Show/hide content panel |
| `heading` | string | Section heading text |
| `headingTag` | `h2`–`h6` | Semantic heading level |
| `body` | HTML string | Rich text (supports HTML tags) |
| `components` | array | List of component configs |

### `navigation`
| Key | Type | Description |
|-----|------|-------------|
| `visible` | bool | Show/hide nav footer |
| `nextLabel` | string | Next button text |
| `nextHref` | string | Next page URL |
| `prevLabel` | string | Prev button text |
| `prevHref` | string | Prev page URL |
| `showProgress` | bool | Show progress bar |
| `currentPage` | number | Current page number |
| `totalPages` | number | Total pages in course |

---

## Component Types

### `feature-list`
```json
{
  "type": "feature-list",
  "items": [
    {
      "icon": "flexibility",
      "title": "Feature Title",
      "description": "Feature description text here."
    }
  ]
}
```
**Available icons:** `flexibility`, `code`, `scalability`, `video`, `quiz`

### `scq` (Single Choice Question)
```json
{
  "type": "scq",
  "id": "q1",
  "question": "What is the capital of France?",
  "correctIndex": 0,
  "options": [
    { "label": "Paris" },
    { "label": "London" },
    { "label": "Berlin" }
  ]
}
```

### `mcq` (Multiple Choice Question)
```json
{
  "type": "mcq",
  "id": "q2",
  "question": "Which are JavaScript frameworks?",
  "options": [
    { "label": "React" },
    { "label": "Vue" },
    { "label": "Django" }
  ]
}
```

### `tabs`
```json
{
  "type": "tabs",
  "tabs": [
    { "label": "Tab 1", "content": "<p>Tab 1 content</p>" },
    { "label": "Tab 2", "content": "<p>Tab 2 content</p>" }
  ]
}
```

### `accordion`
```json
{
  "type": "accordion",
  "items": [
    { "title": "Section 1", "content": "<p>Section 1 content</p>" },
    { "title": "Section 2", "content": "<p>Section 2 content</p>" }
  ]
}
```

### `html-block`
```json
{
  "type": "html-block",
  "html": "<table><tr><th>Col A</th><th>Col B</th></tr></table>"
}
```

---

## Layout Presets

| Preset | Flex Direction | Use Case |
|--------|---------------|----------|
| `image-left` | `row` | Default — image left, content right |
| `image-right` | `row-reverse` | Image right, content left |
| `top-image` | `column` | Full-width image above content |
| `bottom-image` | `column-reverse` | Full-width image below content |
| `full-content` | `column` | No image — text/component only |

---

## Rendering Pipeline

```
loadPageData()       → fetch page.json
    │
    ├── applyTheme()       → inject CSS variables
    ├── renderLayout()     → set flex direction + sizing
    ├── renderTitle()      → populate #page-header
    ├── renderMedia()      → populate #media-panel
    ├── renderContent()    → populate #content-panel
    │       └── renderComponent(type, data)
    │               ├── renderFeatureList()
    │               ├── renderSCQ()
    │               ├── renderMCQ()
    │               ├── renderTabs()
    │               └── renderAccordion()
    └── renderNavigation() → populate #page-footer
```

---

## Adding a New Page

1. Copy `page_001/` → `page_002/`
2. Edit only `page.json`
3. Add images/videos to `assets/`
4. Link from `page_001`'s `navigation.nextHref`

**No HTML or JS changes needed.**

---

## Future-Ready Components (Roadmap)

- `drag-drop` — Drag-and-drop matching exercise
- `hotspot` — Clickable image hotspots
- `simulation` — Branching scenario / sim
- `fill-blank` — Fill-in-the-blank text
- `slider` — Range-input interaction
- `timer` — Countdown assessment timer

---

## Accessibility

- Semantic HTML (`main`, `header`, `footer`, `aside`, `section`, `nav`)
- ARIA roles and labels on all interactive elements
- `aria-live` region for dynamic announcements
- Skip navigation link
- Full keyboard support (Tab, Enter, Space, Arrow keys)
- Focus-visible outlines on all focusable elements
- `alt` text on all images (driven from JSON)
- Colour contrast ≥ 4.5:1 on default theme

---

## Responsive Breakpoints

| Breakpoint | Behaviour |
|------------|-----------|
| `> 900px` | Side-by-side layout (honours JSON `flexDirection`) |
| `≤ 900px` | Stacks to column, full-width panels |
| `≤ 600px` | Navigation stacks vertically |
