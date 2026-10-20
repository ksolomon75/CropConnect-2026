/**
 * Theme JS
 *
 * Front-end entry point. Loaded via wp_enqueue_script_module() so the
 * Slideout library (enqueued as a classic script in the document head) is
 * already on the page by the time this module executes.
 */

// Add passive event listeners
! function (e) {
  "function" == typeof define && define.amd ? define(e) : e()
}(function () {
  let e;
  const t = ["scroll", "wheel", "touchstart", "touchmove", "touchenter", "touchend", "touchleave", "mouseout", "mouseleave", "mouseup", "mousedown", "mousemove", "mouseenter", "mousewheel", "mouseover"];
  if (function () {
    let e = !1;
    try {
      const t = Object.defineProperty({}, "passive", {
        get: function () {
          e = !0
        }
      });
      window.addEventListener("test", null, t);
      window.removeEventListener("test", null, t);
    } catch (e) { }
    return e
  }()) {
    const n = EventTarget.prototype.addEventListener;
    e = n;
    EventTarget.prototype.addEventListener = function (n, o, r) {
      let i;
      const s = "object" == typeof r && null !== r,
        u = s ? r.capture : r;
      if (s) {
        const t = Object.getOwnPropertyDescriptor(r, "passive");
        r = t && !0 !== t.writable && void 0 === t.set ? { ...r } : r;
      } else {
        r = {};
      }
      r.passive = void 0 !== (i = r.passive) ? i : -1 !== t.indexOf(n) && !0;
      r.capture = void 0 !== u && u;
      e.call(this, n, o, r);
    };
    EventTarget.prototype.addEventListener._original = e
  }
});

/**
 * Mobile navigation driven by the Slideout library.
 *
 * The previous theme used jQuery for the submenu hover and Slideout for the
 * off-canvas panel; both behaviours are preserved here without jQuery.
 */
function initMobileNav() {
  const menu = document.getElementById('js-menu');
  const panel = document.getElementById('js-main');

  if (!menu || !panel || typeof window.Slideout !== 'function') {
    return;
  }

  const slideout = new window.Slideout({
    panel: panel,
    menu: menu,
    padding: 266,
    tolerance: 70,
  });

  // Toggle button.
  const toggleButton = document.querySelector('.toggle-button');
  if (toggleButton) {
    toggleButton.addEventListener('click', function () {
      slideout.toggle();
    });
  }

  // Close button inside the panel.
  const closeButton = document.querySelector('.nav-mobile__close');
  if (closeButton) {
    closeButton.addEventListener('click', function () {
      slideout.toggle();
    });
  }

  // Click on the visible panel area closes the menu.
  const openPanel = document.querySelector('.slideout-open #js-main');
  if (openPanel) {
    openPanel.addEventListener('click', function () {
      slideout.toggle();
    });
  }

  // Desktop submenu hover (replaces the old jQuery .hover() binding).
  document.querySelectorAll('.main-menu--nav-main .main-menu__item--parent').forEach(function (item) {
    item.addEventListener('mouseenter', function () {
      item.classList.add('main-menu__item--parent--open');
      const submenu = item.querySelector('.main-menu__sub-menu');
      if (submenu) {
        submenu.classList.add('main-menu__sub-menu--open');
      }
    });

    item.addEventListener('mouseleave', function () {
      item.classList.remove('main-menu__item--parent--open');
      const submenu = item.querySelector('.main-menu__sub-menu');
      if (submenu) {
        submenu.classList.remove('main-menu__sub-menu--open');
      }
    });
  });
}

/**
 * Application entrypoint
 */
document.addEventListener('DOMContentLoaded', () => {
  initMobileNav();
});