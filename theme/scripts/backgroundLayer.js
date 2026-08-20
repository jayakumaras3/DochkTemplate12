/**
 * backgroundLayer.js — Shared page-background mechanism
 * ============================================================
 * ONE implementation, used by every page type that supports a
 * configurable background image:
 *   - assets/html/customHTML/index.html         (Custom HTML)
 *   - theme/scripts/QuizTemplate/Quiz/Quiz.html
 *   - theme/scripts/QuizTemplate/SCQ/SCQ.html
 *   - theme/scripts/QuizTemplate/MCQ/MCQ.html
 * Each of those documents supplies its own `#backgroundLayer` element and
 * its own CSS for how that element is painted; this file owns only the
 * DECISION of whether to show it and the RESOLUTION of the image path, so
 * there is exactly one implementation of that logic instead of one per page
 * type.
 *
 * SINGLE SOURCE OF TRUTH: the shared asset is theme/images/Background.png.
 * A page's config only ever needs `{"visible": true}` — no filename to
 * repeat — because a bare/omitted image name defaults to that one file.
 * A config may still supply an explicit "image" filename for backward
 * compatibility (existing customHTML pages already do); a bare filename
 * always resolves against theme/images/, never against the page's own
 * folder, so it can never point at (or require) a per-page duplicate.
 *
 * PATH RESOLUTION: theme/images/ is located relative to THIS SCRIPT FILE's
 * own address, not relative to whichever document loaded it. That is what
 * lets the exact same function resolve correctly whether the caller is 3
 * folders deep (assets/html/customHTML/) or 4 (theme/scripts/QuizTemplate/
 * Quiz/) — the caller's nesting depth never has to be known or hardcoded
 * here, and every consumer gets the correct path from one shared constant
 * instead of a different hand-written relative path per page type.
 * ============================================================
 */
(function () {
  'use strict';

  // document.currentScript is only valid while a script is initially
  // executing, so its value must be captured immediately at load time, not
  // read later from inside a function. Falls back to a filename lookup for
  // any script-loading method where currentScript is unavailable (e.g. a
  // dynamically inserted <script>, which none of today's callers use, but
  // costs nothing to guard against).
  var SELF_SCRIPT_URL = (function () {
    var el = document.currentScript ||
      document.querySelector('script[src$="backgroundLayer.js"]');
    return (el && el.src) ? el.src : document.baseURI;
  })();

  // Resolved once, from the script's own location: theme/scripts/ -> ../images/
  // = theme/images/. `.src` on a script element is always the browser's fully
  // resolved absolute URL (never the raw attribute text), so this is correct
  // under file://, http(s)://, or an LMS-hosted deployment alike.
  var THEME_IMAGES_BASE = new URL('../images/', SELF_SCRIPT_URL).href;

  // The one shared asset every page falls back to when a config enables the
  // background without naming a file.
  var DEFAULT_BACKGROUND_IMAGE = 'Background.png';

  /* A value that already contains a "/", or is a data:/http(s):// URL, is a
     path someone wrote on purpose (or a page-specific image living next to
     its own page.json) and is left exactly as given. Only a bare filename
     ("Background.png") is redirected to the shared theme/images/ folder. */
  function resolveBackgroundImagePath(file) {
    if (/^([a-z][a-z0-9+.-]*:)?\/\//i.test(file) || file.indexOf('data:') === 0 || file.indexOf('/') !== -1) {
      return file;
    }
    return THEME_IMAGES_BASE + file;
  }

  /* Shown only when visible is exactly true (or the string "true" - configs
     in this package are hand-edited and sometimes quote their values;
     everything else, including the string "false", counts as off, so a typo
     can never leave it stuck on) AND resolves to a non-empty filename, so a
     missing `background` block, an older/newer config that omits it, or
     `{"visible": false}` all resolve to "off" rather than loading
     `url(undefined)`. `{"visible": true}` with no "image" key resolves ON,
     using DEFAULT_BACKGROUND_IMAGE - this is what lets a page's JSON stay
     just `{"visible": true}` instead of repeating the filename. */
  function applyBackground(bg) {
    var layer = document.getElementById('backgroundLayer');
    if (!layer) return;

    var cfg = bg || {};
    var visible = cfg.visible === true ||
      (typeof cfg.visible === 'string' && cfg.visible.trim().toLowerCase() === 'true');

    var file = typeof cfg.image === 'string' ? cfg.image.trim() : '';
    if (visible && file === '') file = DEFAULT_BACKGROUND_IMAGE;
    var on = visible && file !== '';

    if (on) {
      layer.style.setProperty('--bg-image', 'url("' + encodeURI(resolveBackgroundImagePath(file)) + '")');
      layer.hidden = false;
    } else {
      layer.style.removeProperty('--bg-image');
      layer.hidden = true;
    }
  }

  window.applyBackground = applyBackground;
})();
