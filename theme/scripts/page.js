/**
 * page.js — Modular eLearning Page Renderer
 * ============================================================
 * Architecture: Pure Vanilla JS, zero dependencies.
 * Each function is single-responsibility and tree-shakeable.
 * JSON → DOM rendering pipeline.
 * ------------------------------------------------------------
 * LOCATION / PATHS
 * This file lives in theme/scripts/. Its companion stylesheet is
 * theme/css/page.css; the page it renders and that page's data
 * stay in assets/html/customHTML/ (index.html + page.json).
 *
 * The relative paths below — fetch('page.json'), the image srcs
 * that come out of page.json, and the background filename — are
 * DELIBERATELY left document-relative. A relative URL in fetch()
 * and in markup written into the DOM resolves against the HOST
 * DOCUMENT, never against the location of this script, so they
 * still point at assets/html/customHTML/ from here. Do not
 * "correct" them to ../../assets/html/customHTML/... — that would
 * break them.
 * ============================================================
 */

'use strict';

/* ── Constants ───────────────────────────────────────────── */
const PAGE_JSON_PATH = 'page.json';

/* ── SVG Icon Library ────────────────────────────────────── */
const ICONS = {
  flexibility: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zm0 9.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zm9.75-9.75A2.25 2.25 0 0115.75 3.75H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zm0 9.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>`,
  code: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"/></svg>`,
  scalability: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h12M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-1.5m-6-9l3 3m0 0l3-3m-3 3V7.5"/></svg>`,
  video: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9A2.25 2.25 0 0013.5 5.25h-9A2.25 2.25 0 002.25 7.5v9A2.25 2.25 0 004.5 18.75z"/></svg>`,
  quiz: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/></svg>`,
  arrow_right: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>`,
  arrow_left: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>`,
  error: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="48" height="48"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>`
};

/* ── 1. Data Loader ──────────────────────────────────────── */
async function loadPageData(jsonPath = PAGE_JSON_PATH) {
  const res = await fetch(jsonPath);
  if (!res.ok) throw new Error(`Failed to load page data: ${res.status} ${res.statusText}`);
  const data = await res.json();
  return data;
}

/* ── 2. Theme ────────────────────────────────────────────────
   Intentionally absent. Colours, radii, fonts and shadows are owned
   entirely by the :root block in page.css. This renderer reads no
   colour from JSON, defines no colour fallback, and emits no inline
   colour style. To re-skin the page, edit page.css.
   ─────────────────────────────────────────────────────────── */

/* ── 2a. Background Image Applier ────────────────────────────
   The implementation was consolidated into theme/scripts/backgroundLayer.js
   so there is exactly one shared background mechanism across every page
   type (Custom HTML, SCQ, MCQ, Quiz) instead of a copy per page. That file
   sets window.applyBackground; this orphaned page.js (nothing loads it —
   see the file header) simply forwards to it so no second, competing
   implementation exists here even as dead-code reference material.
   ─────────────────────────────────────────────────────────── */
function applyBackground(background) {
  if (typeof window !== 'undefined' && typeof window.applyBackground === 'function' && window.applyBackground !== applyBackground) {
    return window.applyBackground(background);
  }
}

/* ── 2b. Responsive Fit ──────────────────────────────────────
   The fitting itself is pure CSS (object-fit + flex) and already survives
   every resize with no JS. This adds the one thing CSS cannot express: the
   height actually visible in the frame, minus the page's own header and
   footer, published as --available-h and used to cap .media-figure so a long
   text column cannot drive the image taller than the screen.

   Measured only from window.innerHeight and the header/footer boxes - never
   from anything that consumes --available-h - and written only when the
   value changed. Without that guard the write would resize .media-figure,
   re-firing the observer, which would write again: a ResizeObserver feedback
   loop. rAF-coalesced, so a drag-resize costs one measure per frame.
   ─────────────────────────────────────────────────────────── */
function initResponsiveFit() {
  const wrapper = document.getElementById('page-wrapper');
  if (!wrapper) return;
  const header = document.getElementById('page-header');
  const footer = document.getElementById('page-footer');

  let queued = 0;
  let last = -1;

  function measure() {
    queued = 0;
    const hdr = header && !header.hasAttribute('hidden') ? header.offsetHeight : 0;
    const ftr = footer && !footer.hasAttribute('hidden') ? footer.offsetHeight : 0;
    const avail = Math.max(160, Math.round(window.innerHeight - hdr - ftr));
    if (avail === last) return;
    last = avail;
    document.documentElement.style.setProperty('--available-h', avail + 'px');
  }

  const schedule = () => { if (!queued) queued = requestAnimationFrame(measure); };

  if (typeof ResizeObserver === 'function') {
    const ro = new ResizeObserver(schedule);
    ro.observe(document.documentElement);
    if (header) ro.observe(header);
    if (footer) ro.observe(footer);
  }
  window.addEventListener('resize', schedule, { passive: true });
  window.addEventListener('orientationchange', schedule, { passive: true });
  schedule();
}

/* ── 3. Layout Applier ───────────────────────────────────── */
function renderLayout(layout = {}) {
  const root = document.documentElement;
  const layoutMap = {
    'image-left':    'row',
    'image-right':   'row-reverse',
    'top-image':     'column',
    'bottom-image':  'column-reverse',
    'full-content':  'column',
  };
  const flexDir = layout.flexDirection || layoutMap[layout.type] || 'row';
  root.style.setProperty('--layout-flex-dir',       flexDir);
  root.style.setProperty('--layout-media-ratio',    layout.mediaRatio    || '45%');
  root.style.setProperty('--layout-content-ratio',  layout.contentRatio  || '55%');
  root.style.setProperty('--layout-gap',            layout.gap           || '48px');
  // page.json supplies "cardPadding"; "padding" is kept as a legacy alias.
  root.style.setProperty('--layout-padding',        layout.cardPadding   || layout.padding || '48px');
  root.style.setProperty('--layout-max-width',      layout.maxWidth      || '1100px');
}

/* ── 4. Title Renderer ───────────────────────────────────── */
function renderTitle(header = {}) {
  const el = document.getElementById('page-title');
  const bar = document.getElementById('title-accent-bar');
  if (!el) return;

  if (!header.visible) {
    el.closest('#page-header')?.setAttribute('hidden', '');
    return;
  }

  // page.json supplies "align"; "titleAlignment" is kept as a legacy alias.
  const align = header.align || header.titleAlignment || 'left';
  el.textContent = header.title || '';
  el.className = `page-title page-title--${align} animate-in`;

  if (bar) {
    bar.style.display = header.accentBar ? 'block' : 'none';
  }

  document.title = header.title || document.title;
}

/* ── 5. Media Renderer ───────────────────────────────────── */
function renderMedia(media = {}) {
  const panel = document.getElementById('media-panel');
  if (!panel) return;

  if (!media.visible || media.type === 'none') {
    panel.setAttribute('hidden', '');
    return;
  }

  panel.removeAttribute('hidden');

  let html = '';

  if (media.type === 'image') {
    // aspectRatio is layout data and stays author-controlled from JSON.
    // The corner radius is NOT emitted here - .media-figure and
    // .media-image carry `border-radius: var(--radius)` in page.css,
    // which is now the only place it is defined.
    const aspect = media.aspectRatio || '4/3';
    html = `
      <figure class="media-figure animate-in" role="img" aria-label="${escapeHtml(media.alt || '')}">
        <img
          class="media-image"
          src="${escapeHtml(media.src || '')}"
          alt="${escapeHtml(media.alt || '')}"
          style="aspect-ratio:${aspect};"
          loading="lazy"
          decoding="async"
          onerror="this.onerror=null;this.src='assets/placeholder.svg';"
        />
        ${media.caption ? `<figcaption class="media-caption">${escapeHtml(media.caption)}</figcaption>` : ''}
      </figure>`;
  } else if (media.type === 'video') {
    if (media.src && media.src.includes('youtube') || (media.src || '').includes('vimeo')) {
      html = `
        <div class="media-video-wrapper animate-in">
          <iframe src="${escapeHtml(media.src)}" title="${escapeHtml(media.alt || 'Video')}"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen></iframe>
        </div>`;
    } else {
      html = `
        <div class="media-video-wrapper animate-in">
          <video controls ${media.autoplay ? 'autoplay muted' : ''} preload="metadata">
            <source src="${escapeHtml(media.src || '')}" type="video/mp4">
            Your browser does not support the video element.
          </video>
        </div>`;
    }
  }

  panel.innerHTML = html;
}

/* ── 6. Content Renderer ─────────────────────────────────── */
function renderContent(content = {}) {
  const panel = document.getElementById('content-panel');
  if (!panel) return;

  if (!content.visible) {
    panel.setAttribute('hidden', '');
    return;
  }

  panel.removeAttribute('hidden');

  const headingTag = content.headingTag || 'h2';
  let html = '';

  if (content.heading) {
    html += `<${headingTag} class="content-heading animate-in animate-in--delay-1">${escapeHtml(content.heading)}</${headingTag}>`;
  }

  if (content.body) {
    // body supports raw HTML (trusted JSON source)
    html += `<div class="content-body animate-in animate-in--delay-2">${content.body}</div>`;
  }

  // Render components
  if (Array.isArray(content.components)) {
    content.components.forEach((comp, idx) => {
      html += `<div class="animate-in animate-in--delay-3">${renderComponent(comp.type, comp)}</div>`;
    });
  }

  panel.innerHTML = html;

  // Post-render: wire up interactive components
  wireComponents(panel);
}

/* ── 7. Component Renderer (extensible) ──────────────────── */
function renderComponent(type, data) {
  switch (type) {
    case 'feature-list': return renderFeatureList(data);
    case 'scq':          return renderSCQ(data);
    case 'mcq':          return renderMCQ(data);
    case 'tabs':         return renderTabs(data);
    case 'accordion':    return renderAccordion(data);
    case 'html-block':   return data.html || '';
    default:
      console.warn(`[eLearning] Unknown component type: "${type}"`);
      return '';
  }
}

/* ── 7a. Feature List Component ──────────────────────────── */
function renderFeatureList(data) {
  if (!Array.isArray(data.items)) return '';
  const items = data.items.map(item => {
    const iconSvg = ICONS[item.icon] || ICONS['flexibility'];
    return `
      <li class="feature-item" tabindex="0" role="listitem">
        <span class="feature-icon" aria-hidden="true">${iconSvg}</span>
        <div class="feature-text">
          <div class="feature-title">${escapeHtml(item.title)}</div>
          <div class="feature-description">${escapeHtml(item.description)}</div>
        </div>
      </li>`;
  }).join('');
  return `<ul class="feature-list" aria-label="Key features">${items}</ul>`;
}

/* ── 7b. SCQ Component (Single Choice Question) ──────────── */
function renderSCQ(data) {
  if (!data.question) return '';
  const optionsList = (data.options || []).map((opt, i) => `
    <li>
      <label class="quiz-option" tabindex="0" role="radio" aria-checked="false">
        <input type="radio" name="scq_${data.id || 'q'}" value="${i}" style="display:none;">
        <span>${escapeHtml(opt.label || opt)}</span>
      </label>
    </li>`).join('');
  return `
    <div class="quiz-wrapper" data-component="scq" data-correct="${data.correctIndex ?? 0}">
      <p class="quiz-question" id="scq_label_${data.id}">${escapeHtml(data.question)}</p>
      <ul class="quiz-options" role="radiogroup" aria-labelledby="scq_label_${data.id}">
        ${optionsList}
      </ul>
    </div>`;
}

/* ── 7c. MCQ Component (Multiple Choice Question) ────────── */
function renderMCQ(data) {
  if (!data.question) return '';
  const optionsList = (data.options || []).map((opt, i) => `
    <li>
      <label class="quiz-option" tabindex="0">
        <input type="checkbox" name="mcq_${data.id || 'q'}" value="${i}" style="display:none;">
        <span>${escapeHtml(opt.label || opt)}</span>
      </label>
    </li>`).join('');
  return `
    <div class="quiz-wrapper" data-component="mcq">
      <p class="quiz-question">${escapeHtml(data.question)}</p>
      <ul class="quiz-options">
        ${optionsList}
      </ul>
    </div>`;
}

/* ── 7d. Tabs Component ──────────────────────────────────── */
function renderTabs(data) {
  if (!Array.isArray(data.tabs)) return '';
  const tabBtns = data.tabs.map((tab, i) => `
    <button class="tab-btn" role="tab" aria-selected="${i === 0}" aria-controls="tabpanel_${i}" id="tab_${i}">
      ${escapeHtml(tab.label)}
    </button>`).join('');
  const tabPanels = data.tabs.map((tab, i) => `
    <div class="tab-panel" role="tabpanel" id="tabpanel_${i}" aria-labelledby="tab_${i}" aria-hidden="${i !== 0}">
      ${tab.content || ''}
    </div>`).join('');
  return `
    <div class="tabs-wrapper" data-component="tabs">
      <nav class="tab-nav" role="tablist">${tabBtns}</nav>
      ${tabPanels}
    </div>`;
}

/* ── 7e. Accordion Component ─────────────────────────────── */
function renderAccordion(data) {
  if (!Array.isArray(data.items)) return '';
  const items = data.items.map((item, i) => `
    <div class="accordion-item">
      <button class="accordion-trigger" aria-expanded="false" aria-controls="acc_panel_${i}">
        ${escapeHtml(item.title)}
        <span class="accordion-chevron" aria-hidden="true">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
        </span>
      </button>
      <div class="accordion-panel" id="acc_panel_${i}" hidden>
        ${item.content || ''}
      </div>
    </div>`).join('');
  return `<div class="accordion-wrapper" data-component="accordion">${items}</div>`;
}

/* ── 8. Navigation Renderer ──────────────────────────────── */
function renderNavigation(nav = {}) {
  const footer = document.getElementById('page-footer');
  if (!footer || !nav.visible) {
    footer?.setAttribute('hidden', '');
    return;
  }

  let progressHtml = '';
  if (nav.showProgress && nav.totalPages > 1) {
    const pct = Math.round((nav.currentPage / nav.totalPages) * 100);
    progressHtml = `
      <div class="progress-bar-wrap" role="progressbar" aria-valuenow="${nav.currentPage}" aria-valuemin="1" aria-valuemax="${nav.totalPages}" aria-label="Module progress">
        <div class="progress-label">Module ${nav.currentPage} of ${nav.totalPages}</div>
        <div class="progress-bar">
          <div class="progress-fill" style="width:${pct}%"></div>
        </div>
      </div>`;
  }

  let prevHtml = '';
  if (nav.prevLabel && nav.prevHref) {
    prevHtml = `
      <a href="${escapeHtml(nav.prevHref)}" class="btn-nav btn-nav--secondary" aria-label="${escapeHtml(nav.prevLabel)}">
        ${ICONS.arrow_left}
        ${escapeHtml(nav.prevLabel)}
      </a>`;
  }

  let nextHtml = '';
  if (nav.nextLabel && nav.nextHref) {
    nextHtml = `
      <a href="${escapeHtml(nav.nextHref)}" class="btn-nav" aria-label="${escapeHtml(nav.nextLabel)}">
        ${escapeHtml(nav.nextLabel)}
        ${ICONS.arrow_right}
      </a>`;
  }

  footer.innerHTML = `
    <nav class="nav-row" aria-label="Module navigation">
      ${prevHtml}
      ${progressHtml}
      ${nextHtml}
    </nav>`;
}

/* ── 9. Component Wiring (post-render interactivity) ─────── */
function wireComponents(container) {
  // SCQ / MCQ
  container.querySelectorAll('.quiz-option').forEach(opt => {
    opt.addEventListener('click', () => {
      const wrapper = opt.closest('[data-component]');
      const type = wrapper?.dataset.component;
      if (type === 'scq') {
        wrapper.querySelectorAll('.quiz-option').forEach(o => o.classList.remove('selected'));
        opt.classList.add('selected');
        const input = opt.querySelector('input[type=radio]');
        if (input) input.checked = true;
      } else if (type === 'mcq') {
        opt.classList.toggle('selected');
        const input = opt.querySelector('input[type=checkbox]');
        if (input) input.checked = opt.classList.contains('selected');
      }
    });
    // Keyboard: Space/Enter
    opt.addEventListener('keydown', e => {
      if (e.key === ' ' || e.key === 'Enter') { e.preventDefault(); opt.click(); }
    });
  });

  // Tabs
  container.querySelectorAll('[data-component="tabs"]').forEach(tabs => {
    const btns = tabs.querySelectorAll('.tab-btn');
    const panels = tabs.querySelectorAll('.tab-panel');
    btns.forEach((btn, i) => {
      btn.addEventListener('click', () => {
        btns.forEach((b, j) => {
          b.setAttribute('aria-selected', i === j);
          panels[j]?.setAttribute('aria-hidden', i !== j);
        });
      });
      btn.addEventListener('keydown', e => {
        if (e.key === 'ArrowRight') { e.preventDefault(); btns[(i + 1) % btns.length].click(); btns[(i + 1) % btns.length].focus(); }
        if (e.key === 'ArrowLeft')  { e.preventDefault(); btns[(i - 1 + btns.length) % btns.length].click(); btns[(i - 1 + btns.length) % btns.length].focus(); }
      });
    });
  });

  // Accordion
  container.querySelectorAll('.accordion-trigger').forEach(btn => {
    btn.addEventListener('click', () => {
      const expanded = btn.getAttribute('aria-expanded') === 'true';
      const panel = btn.nextElementSibling;
      btn.setAttribute('aria-expanded', !expanded);
      if (panel) panel.hidden = expanded;
    });
  });
}

/* ── 10. Error Renderer ──────────────────────────────────── */
function renderError(message) {
  const main = document.getElementById('page-main');
  if (!main) return;
  main.innerHTML = `
    <div class="error-state" role="alert">
      ${ICONS.error}
      <h3>Failed to load page content</h3>
      <p>${escapeHtml(message)}</p>
    </div>`;
}

/* ── 11. Loading State ───────────────────────────────────── */
function hideLoader() {
  console.log('[eLearning] Page loaded, hiding loader...');
  const loader = document.getElementById('page-loading');
  if (!loader) return;
  loader.classList.add('fade-out');
  setTimeout(() => loader.remove(), 400);
}

/* ── 11a. Auto-complete (this page only) ─────────────────────
   Fires once, ~1s after initPage() finishes rendering this page
   successfully. pageCompleteTriggered guards against a second call if
   initPage() were ever invoked again for the same document. */

/* ── 12. Utility: Safe HTML escaping ─────────────────────── */
function escapeHtml(str) {
  if (typeof str !== 'string') return String(str ?? '');
  return str
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

/* ── 13. Main Bootstrap ──────────────────────────────────── */
async function initPage() {
  try {
    const data = await loadPageData();

    // Apply background & layout first (no reflow later). Theme comes from page.css.
    applyBackground(data.background);
    renderLayout(data.layout || {});

    // Render sections
    renderTitle(data.header || {});
    renderMedia(data.media || {});
    renderContent(data.content || {});
    renderNavigation(data.navigation || {});
    // After the renders, so header/footer visibility is final before measuring.
    initResponsiveFit();

    hideLoader();
    triggerPageComplete();

    // Announce to screen readers that page is ready
    const announcer = document.getElementById('sr-announcer');
    if (announcer) announcer.textContent = `${data.header?.title || 'Page'} loaded.`;

  } catch (err) {
    console.error('[eLearning]', err);
    hideLoader();
    renderError(err.message || 'Unknown error');
  }
}

/* ── Boot on DOM ready ───────────────────────────────────── */
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initPage);
} else {
  initPage();
}

/* ── Public API (for future extension / AI orchestration) ── */
window.eLearning = {
  loadPageData,
  applyBackground,
  initResponsiveFit,
  renderLayout,
  renderTitle,
  renderMedia,
  renderContent,
  renderNavigation,
  renderComponent,
  escapeHtml,
};
