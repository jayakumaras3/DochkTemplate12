# Steps Toggle Button - Debug & Testing Guide

## Quick Debug Steps

To verify the Steps Toggle Button is working correctly:

### 1. **Browser Developer Tools Check**
1. Open your browser's Developer Tools (F12 or Right-click → Inspect)
2. Go to the **Console** tab
3. Reload the page (F5)
4. Look for console messages starting with `[StepsToggleButton]` and `[ContentController]`

### 2. **Expected Console Messages**
When navigating to a page WITH steps, you should see:
```
[StepsToggleButton] Module script loaded
[StepsToggleButton] DOMContentLoaded fired, module ready
[ContentController] Steps toggle initialization check...
[ContentController] StepsToggleButton available: true
[ContentController] Response has steps: Array(5)
[ContentController] Found StepsToggleButton, steps count: 5
[DEBUG] Initializing steps with 5 steps
[StepsToggleButton] Initializing with steps: Array(5)
[StepsToggleButton] Stored 5 steps
[StepsToggleButton] Toggle button created and visible
[StepsToggleButton] Button element: <button id="stepsToggleButton"...>
[StepsToggleButton] Button in DOM: true
[StepsToggleButton] Step panel created and appended to body
[StepsToggleButton] Steps content updated
[StepsToggleButton] Initialization complete
```

### 3. **Manual Element Verification**
In the Console, type these commands to check if elements exist:

```javascript
// Check if StepsToggleButton module is loaded
window.StepsToggleButton

// Check if the button element exists in the DOM
document.getElementById('stepsToggleButton')

// Check if the steps panel exists
document.getElementById('stepsPanel')

// Check button visibility
document.querySelector('.steps-toggle-btn')

// Check button inline styles
document.getElementById('stepsToggleButton').style.cssText
```

### 4. **Visual Verification**
- Look at the **bottom-right corner** of the video player (above the controls)
- You should see a circular button with an emoji (📄)
- The button should be visible even if partially offscreen
- Click it to toggle the steps panel

---

## How to Use

### When Button Appears (on pages with steps):
1. **Click the 📄 button** at the bottom-right
   - Step panel should slide in from the right
   - Shows list of steps with times and descriptions
2. **Click a step** to seek video to that time
3. **Click step title again** or use the ✕ button to close panel
4. Watch the video - **active step highlights** as video plays

---

## Troubleshooting

### Problem: Button not appearing
**Check:**
1. Is the page supposed to have steps? (Check JSON data)
2. Browser console for errors
3. Make sure you're on a page WITH "steps" in the settings
4. Clear browser cache (Ctrl+Shift+Delete)

**Solution:**
- Press F12 to open dev tools
- Copy this in Console:
  ```javascript
  if(window.StepsToggleButton) {
      console.log('StepsToggleButton loaded');
      StepsToggleButton.init([
          {step: 1, title: "Test", description: "Test step", time: 0},
          {step: 2, title: "Test 2", description: "Test step 2", time: 10}
      ]);
  }
  ```
- This will manually create a test button

### Problem: Button appears but doesn't work
**Check:**
1. Open console for JavaScript errors
2. Verify video element exists: `document.getElementById('vidArea')`
3. Try clicking the button - should see log: `[StepsToggleButton] Button clicked`

**Solution:**
- Right-click → Inspect the button
- Check if it has the correct z-index (9999)
- Check computed styles in DevTools

### Problem: Panel appears but steps are empty
**Check:**
1. Is steps array populated? Check Network tab in DevTools
2. Look at the toc.json data - does it have steps?
3. Check console for parsing errors

**Solution:**
- Make sure you're on the right page (one with steps)
- Reload the page
- Check the actual JSON data in toc.json file

---

## File Locations for Reference

| Component | File |
|-----------|------|
| Main Module | `/theme/scripts/stepsToggleButton.js` |
| CSS Styles | `/theme/css/content.css` (lines 572+) |
| Controller Integration | `/theme/scripts/controller/contentController.js` (lines 307-332) |
| Script Reference | `/index.html` (line 35) |
| Sample Data | `/assets/json/toc.json` (page with 5 steps) |

---

## Test Page
Navigate to a page that has steps. Based on toc.json, the 3rd page (index 2) has 5 steps:
- Remove screens
- Clean blades
- Inspect impeller
- Check clearance
- Procedure Complete

---

## Browser Compatibility
- Modern browsers (Chrome, Firefox, Edge, Safari)
- Requires JavaScript enabled
- Uses ES6+ features (const, let, arrow functions)
- Requires DOM Level 4 (classList, querySelector)

---

## Performance Notes
- Button and panel are created dynamically (no memory overhead on non-steps pages)
- Video tracking uses requestAnimationFrame indirectly via timeupdate event
- All styles use CSS transitions for smooth animations
- Minimal DOM manipulation - only creates elements once

---

## If Nothing Works
1. **Hard refresh**: Ctrl+Shift+R (or Cmd+Shift+R on Mac)
2. **Clear cache**: Ctrl+Shift+Delete
3. **Check console**: F12 → Console tab → reload
4. **Verify files**: Make sure all 4 files above are modified correctly
5. **Test in incognito**: Open page in private/incognito mode
6. **Different browser**: Try Chrome, Firefox, Safari

---

For any issues, check the browser console first - it will show exactly what's happening!
