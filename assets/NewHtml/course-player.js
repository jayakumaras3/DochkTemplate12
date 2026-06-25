(function () {
  "use strict";

  var state = {
    menu: [],
    activeId: null,
    tab: "menu"
  };

  var shell = document.getElementById("playerShell");
  var sidebar = document.getElementById("sidebar");
  var sidebarToggle = document.getElementById("sidebarToggle");
  var sidebarOverlay = document.getElementById("sidebarOverlay");
  var menuTree = document.getElementById("menuTree");
  var menuTab = document.getElementById("menuTab");
  var transcriptTab = document.getElementById("transcriptTab");
  var menuPanel = document.getElementById("menuPanel");
  var transcriptPanel = document.getElementById("transcriptPanel");
  var transcriptContent = document.getElementById("transcriptContent");
  var currentMenuTitle = document.getElementById("currentMenuTitle");
  var heroBanner = document.querySelector(".hero-banner");
  var dashboardView = document.querySelector(".dashboard");
  var contentFrameWrap = document.getElementById("contentFrameWrap");
  var contentFrame = document.getElementById("contentFrame");
  var pageCounter = document.getElementById("pageCounter");
  var menuIndex = {};
  var flatLeafOrder = [];

  function isCompactViewport() {
    return window.matchMedia("(max-width: 1180px)").matches;
  }

  function setSidebarOpen(isOpen) {
    var shouldOpen = !!isOpen;
    shell.classList.toggle("sidebar-open", shouldOpen);
    shell.classList.toggle("mobile-menu-open", shouldOpen);
    sidebarToggle.setAttribute("aria-expanded", shouldOpen ? "true" : "false");
    sidebarOverlay.hidden = !shouldOpen;
  }

  function closeSidebarOnCompact() {
    if (isCompactViewport()) {
      setSidebarOpen(false);
    }
  }

  function indexMenu(items) {
    items.forEach(function (item) {
      menuIndex[item.id] = item;
      if (!Array.isArray(item.children) || item.children.length === 0) {
        flatLeafOrder.push(item.id);
      }
      if (Array.isArray(item.children) && item.children.length > 0) {
        indexMenu(item.children);
      }
    });
  }

  function updatePageCounter(menuId) {
    var idx = flatLeafOrder.indexOf(menuId);
    if (idx === -1) {
      pageCounter.textContent = "-/-";
      return;
    }
    pageCounter.textContent = String(idx + 1) + "/" + String(flatLeafOrder.length);
  }

  function loadPageForItem(item) {
    currentMenuTitle.textContent = item.title || "Welcome";
    updatePageCounter(item.id);

    if (item && item.page) {
      heroBanner.hidden = true;
      dashboardView.hidden = true;
      contentFrameWrap.hidden = false;

      if (/\.(mp4|webm|ogg)$/i.test(item.page)) {
        contentFrame.removeAttribute("src");
        contentFrame.srcdoc =
          "<!doctype html><html><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'></head><body style='margin:0;background:#000;display:flex;align-items:center;justify-content:center;height:100vh;'><video controls autoplay style='width:100%;height:100%;object-fit:contain;'><source src='" +
          item.page +
          "'></video></body></html>";
      } else {
        contentFrame.removeAttribute("srcdoc");
        contentFrame.src = item.page;
      }
    } else {
      heroBanner.hidden = false;
      contentFrame.removeAttribute("src");
      contentFrame.removeAttribute("srcdoc");
      contentFrameWrap.hidden = true;
      dashboardView.hidden = false;
    }
  }

  function activateTab(tabName) {
    var isMenu = tabName === "menu";
    state.tab = isMenu ? "menu" : "transcript";

    menuTab.classList.toggle("is-active", isMenu);
    transcriptTab.classList.toggle("is-active", !isMenu);

    menuTab.setAttribute("aria-selected", isMenu ? "true" : "false");
    transcriptTab.setAttribute("aria-selected", isMenu ? "false" : "true");

    menuPanel.classList.toggle("is-visible", isMenu);
    transcriptPanel.classList.toggle("is-visible", !isMenu);

    menuPanel.setAttribute("aria-hidden", isMenu ? "false" : "true");
    transcriptPanel.setAttribute("aria-hidden", isMenu ? "true" : "false");
  }

  function updateChildrenMaxHeight(parentLi) {
    var panel = parentLi.querySelector(":scope > .menu-children");
    if (!panel) {
      return;
    }

    if (parentLi.classList.contains("is-open")) {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } else {
      panel.style.maxHeight = "0px";
    }

    var parentPanel = parentLi.parentElement.closest(".menu-item");
    if (parentPanel) {
      updateChildrenMaxHeight(parentPanel);
    }
  }

  function setOpenState(parentLi, shouldOpen) {
    var panel = parentLi.querySelector(":scope > .menu-children");
    if (!panel) {
      return;
    }

    parentLi.classList.toggle("is-open", shouldOpen);
    parentLi.setAttribute("aria-expanded", shouldOpen ? "true" : "false");
    var toggleBtn = parentLi.querySelector(":scope > .menu-row > .menu-toggle");
    if (toggleBtn) {
      toggleBtn.setAttribute("aria-expanded", shouldOpen ? "true" : "false");
    }

    if (shouldOpen) {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } else {
      panel.style.maxHeight = "0px";
    }

    updateChildrenMaxHeight(parentLi);
  }

  function closeSiblingAccordions(currentLi) {
    var siblings = currentLi.parentElement.children;
    for (var i = 0; i < siblings.length; i += 1) {
      var sibling = siblings[i];
      if (sibling !== currentLi && sibling.classList.contains("menu-item")) {
        setOpenState(sibling, false);
      }
    }
  }

  function setActiveItem(menuId) {
    state.activeId = menuId;

    var allItems = menuTree.querySelectorAll(".menu-item");
    for (var i = 0; i < allItems.length; i += 1) {
      allItems[i].classList.remove("is-active");
      allItems[i].removeAttribute("aria-current");
    }

    var target = menuTree.querySelector('.menu-item[data-id="' + menuId + '"]');
    if (!target) {
      return;
    }

    target.classList.add("is-active");
    target.setAttribute("aria-current", "page");

    // Open all ancestors for the selected item.
    var parentItem = target.parentElement.closest(".menu-item");
    while (parentItem) {
      closeSiblingAccordions(parentItem);
      setOpenState(parentItem, true);
      parentItem = parentItem.parentElement.closest(".menu-item");
    }

    target.scrollIntoView({ block: "nearest", behavior: "smooth" });

    if (menuIndex[menuId]) {
      loadPageForItem(menuIndex[menuId]);
    }

    closeSidebarOnCompact();
  }

  function markActiveRow(menuId) {
    state.activeId = menuId;
    var allItems = menuTree.querySelectorAll(".menu-item");
    for (var i = 0; i < allItems.length; i += 1) {
      allItems[i].classList.remove("is-active");
      allItems[i].removeAttribute("aria-current");
    }

    var target = menuTree.querySelector('.menu-item[data-id="' + menuId + '"]');
    if (target) {
      target.classList.add("is-active");
      target.setAttribute("aria-current", "page");
    }
  }

  function createChevron() {
    var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    svg.setAttribute("class", "menu-chevron");
    svg.setAttribute("viewBox", "0 0 24 24");
    svg.setAttribute("aria-hidden", "true");

    var path = document.createElementNS("http://www.w3.org/2000/svg", "path");
    path.setAttribute("d", "M9 6l6 6-6 6");
    path.setAttribute("fill", "none");
    path.setAttribute("stroke", "currentColor");
    path.setAttribute("stroke-width", "2");
    path.setAttribute("stroke-linecap", "round");
    path.setAttribute("stroke-linejoin", "round");
    svg.appendChild(path);
    return svg;
  }

  function createSvgIcon(paths) {
    var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    svg.setAttribute("class", "menu-icon menu-icon-svg");
    svg.setAttribute("viewBox", "0 0 24 24");
    svg.setAttribute("aria-hidden", "true");

    paths.forEach(function (d) {
      var path = document.createElementNS("http://www.w3.org/2000/svg", "path");
      path.setAttribute("d", d);
      path.setAttribute("fill", "none");
      path.setAttribute("stroke", "currentColor");
      path.setAttribute("stroke-width", "1.9");
      path.setAttribute("stroke-linecap", "round");
      path.setAttribute("stroke-linejoin", "round");
      svg.appendChild(path);
    });

    return svg;
  }

  function createMenuIcon(iconNameOrPath) {
    var iconMap = {
      home: ["m3 9 9-7 9 7", "M9 22V12h6v10", "M21 22H3"],
      video: ["M23 7l-7 5 7 5V7", "M1 5h15v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2z"],
      "book-open": ["M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z", "M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"],
      brain: ["M9.5 2a3.5 3.5 0 0 0-3.5 3.5V8a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2v1.5A3.5 3.5 0 0 0 9.5 18H10v4", "M14.5 2A3.5 3.5 0 0 1 18 5.5V8a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2v1.5A3.5 3.5 0 0 1 14.5 18H14v4", "M10 8h4", "M10 13h4"],
      lightbulb: ["M9 18h6", "M10 22h4", "M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"],
      "clipboard-check": ["M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2", "M9 3h6v4a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2z", "m9 12 2 2 4-4"],
      accessibility: ["M16 4a1 1 0 1 1 0 2 1 1 0 0 1 0-2", "m18 19 1-7-6 1", "m5 8 3-3 5.5 3-2.36 3.5", "M4.24 14.5a5 5 0 0 0 6.88 6", "M13.76 17.5a5 5 0 0 0-6.88-6"],
      "badge-check": ["M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z", "m9 12 2 2 4-4"],
      "check-circle": ["M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20", "M8 12l2.5 2.5L16 9"],
      "clipboard-list": ["M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2", "M9 3h6v4a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2z", "M9 12h6", "M9 16h6"]
    };

    if (iconMap[iconNameOrPath]) {
      return createSvgIcon(iconMap[iconNameOrPath]);
    }

    var icon = document.createElement("img");
    icon.className = "menu-icon";
    icon.src = iconNameOrPath || "icons/resource.svg";
    icon.alt = "";
    icon.setAttribute("aria-hidden", "true");
    return icon;
  }

  function buildMenuBranch(items, level) {
    var ul = document.createElement("ul");
    ul.className = level === 1 ? "menu-tree" : "menu-level";
    ul.setAttribute("role", level === 1 ? "tree" : "group");

    items.forEach(function (item) {
      var li = document.createElement("li");
      li.className = "menu-item";
      li.dataset.id = item.id;
      li.setAttribute("role", "treeitem");
      li.setAttribute("aria-level", String(level));

      var row = document.createElement("div");
      row.className = "menu-row";

      var icon = createMenuIcon(item.icon);
      row.appendChild(icon);

      var hasChildren = Array.isArray(item.children) && item.children.length > 0;

      if (hasChildren) {
        li.setAttribute("aria-expanded", "false");
        var toggle = document.createElement("button");
        toggle.type = "button";
        toggle.className = "menu-toggle";
        toggle.textContent = item.title;
        toggle.setAttribute("aria-expanded", "false");
        toggle.setAttribute("aria-controls", "children-" + item.id);
        toggle.addEventListener("click", function () {
          markActiveRow(item.id);
          currentMenuTitle.textContent = item.title;
          if (item.page) {
            loadPageForItem(item);
          }

          var isOpen = li.classList.contains("is-open");
          if (isOpen) {
            setOpenState(li, false);
          } else {
            closeSiblingAccordions(li);
            setOpenState(li, true);
          }
        });

        row.appendChild(toggle);
        row.appendChild(createChevron());

        var childrenWrap = document.createElement("div");
        childrenWrap.className = "menu-children";
        childrenWrap.id = "children-" + item.id;
        childrenWrap.appendChild(buildMenuBranch(item.children, level + 1));
        li.appendChild(row);
        li.appendChild(childrenWrap);
      } else {
        var leaf = document.createElement("button");
        leaf.type = "button";
        leaf.className = "menu-leaf";
        leaf.textContent = item.title;
        leaf.addEventListener("click", function () {
          setActiveItem(item.id);
        });

        row.appendChild(leaf);
        li.appendChild(row);
      }

      if (item.active) {
        state.activeId = item.id;
      }

      ul.appendChild(li);
    });

    return ul;
  }

  function renderTranscript(data) {
    transcriptContent.innerHTML = "";

    data.entries.forEach(function (entry) {
      var p = document.createElement("p");
      p.textContent = entry;
      transcriptContent.appendChild(p);
    });
  }

  function renderCourseData(course) {
    document.getElementById("courseBadge").textContent = course.badge;
    document.getElementById("courseTitle").textContent = course.title;
    document.getElementById("courseDescription").textContent = course.description;
    document.getElementById("durationValue").textContent = course.duration;
    document.getElementById("assessmentValue").textContent = course.assessment;
    document.getElementById("resourcesValue").textContent = course.resources;
  }

  function renderMenu(menuItems) {
    state.menu = menuItems;
    menuIndex = {};
    flatLeafOrder = [];
    indexMenu(menuItems);
    menuTree.innerHTML = "";
    var branch = buildMenuBranch(menuItems, 1);
    menuTree.replaceWith(branch);
    branch.id = "menuTree";
    branch.classList.add("menu-tree");
    branch.setAttribute("role", "tree");
    menuTree = branch;

    if (state.activeId) {
      setActiveItem(state.activeId);
    } else {
      heroBanner.hidden = false;
      dashboardView.hidden = false;
      contentFrameWrap.hidden = true;
      pageCounter.textContent = "1/" + String(flatLeafOrder.length || 1);
    }
  }

  function wireEvents() {
    sidebarToggle.addEventListener("click", function () {
      var isOpen = shell.classList.contains("sidebar-open");
      setSidebarOpen(!isOpen);
    });

    sidebarOverlay.addEventListener("click", function () {
      setSidebarOpen(false);
    });

    menuTab.addEventListener("click", function () {
      activateTab("menu");
    });

    transcriptTab.addEventListener("click", function () {
      activateTab("transcript");
    });

    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape") {
        setSidebarOpen(false);
      }
    });

    window.addEventListener("resize", function () {
      if (!isCompactViewport()) {
        shell.classList.remove("mobile-menu-open");
      }

      if (!shell.classList.contains("sidebar-open")) {
        sidebarOverlay.hidden = true;
      }
    });

    document.getElementById("startCourseBtn").addEventListener("click", function () {
      var firstLeaf = menuTree.querySelector(".menu-leaf");
      if (firstLeaf) {
        firstLeaf.click();
      }
    });

    document.getElementById("audioBtn").addEventListener("click", function () {
      console.log("Audio version action placeholder");
    });
  }

  function init() {
    wireEvents();
    activateTab("menu");
    setSidebarOpen(false);

    fetch("./menu.json")
      .then(function (response) {
        if (!response.ok) {
          throw new Error("Failed to load menu.json");
        }
        return response.json();
      })
      .then(function (payload) {
        renderCourseData(payload.course);
        renderMenu(payload.menu || []);
        renderTranscript(payload.transcript || { heading: "Transcript", entries: [] });
      })
      .catch(function (error) {
        console.error(error);
      });
  }

  init();
})();
