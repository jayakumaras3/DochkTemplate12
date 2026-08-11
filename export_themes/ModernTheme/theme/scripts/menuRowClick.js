/**
 * Full-row hit area for the sidebar menu - CLICK ROUTING ONLY.
 *
 * The controller renders each menu row as:
 *
 *   <span>                                  <- row wrapper
 *     <li id="Mitem{n}">
 *       <span id="LSitem{n}" role="button"
 *             onclick="tocDataClick(this)"  <- the ONLY handler
 *             onkeydown="tocKeyHandler(event, this)">...</span>
 *     </li>
 *     <p class="tickSymbol" id="Sitem{n}">  <- completion tick
 *   </span>
 *
 * so anything that lands on the wrapper, the <li> box or the tick - i.e.
 * not on the interactive <span> itself - produced no navigation.
 *
 * This listener does NOT navigate. It looks up the row's existing
 * `span[role="button"]` and calls the existing global `tocDataClick()`
 * with it - byte for byte what the inline onclick does when the text is
 * clicked. No navigation logic is duplicated, no page counter is
 * touched, no lock/completion state is evaluated here: an item that is
 * locked behaves on a row click exactly as it already does on a text
 * click, because it is the same call.
 *
 * It never fires twice: clicks that already reached the interactive span
 * (or any of its descendants) are ignored and left to the inline onclick.
 */
(function () {
    "use strict";

    var ROW_HANDLER_SELECTOR = 'span[role="button"]';

    /* The row wrapper is the direct child of the <ol id="tocData">. */
    function rowWrapperFrom(node) {
        while (node && node.parentNode) {
            if (node.parentNode.id === "tocData") {
                return node;
            }
            node = node.parentNode;
        }
        return null;
    }

    function onDocumentClick(event) {
        var target = event.target;
        if (!target || typeof target.closest !== "function") {
            return;
        }

        /* Already handled by the row's own inline onclick - leave it be. */
        if (target.closest(ROW_HANDLER_SELECTOR)) {
            return;
        }

        var wrapper = rowWrapperFrom(target);
        if (!wrapper) {
            return;
        }

        var label = wrapper.querySelector("li > " + ROW_HANDLER_SELECTOR);
        if (!label) {
            return;
        }

        /* Hidden rows (e.g. the audio-version entry) must stay unreachable. */
        var listItem = label.parentNode;
        if (listItem && listItem.style && listItem.style.display === "none") {
            return;
        }

        if (typeof tocDataClick === "function") {
            tocDataClick(label);
        }
    }

    document.addEventListener("click", onDocumentClick, false);
}());
