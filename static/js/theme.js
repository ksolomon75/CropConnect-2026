/**
 * Theme JS
 */

import { registerButtonComponent } from './components/button.js';
import { registerBackToTopButton } from './components/backToTop.js';
import GetHeaderHeight from './modules/GetHeaderHeight.js';
import tagExternalLinks from './modules/TagExternalLinks.js';
import Navigation from './modules/Navigation.js';

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
 * Application entrypoint
 */
document.addEventListener('DOMContentLoaded', () => {
    // Tag external links
    tagExternalLinks();

    // Register button component
    registerButtonComponent();
    registerBackToTopButton();

    // Initialize Navigation
    const navigation = new Navigation('navMainToggle', '.menu-vdi__toggle');

    // Initialize Navigation
    navigation.desktopMenuDropdowns();
    navigation.mobileMenuToggle();

    // Initialize Header Height
    GetHeaderHeight();

    // Add Back to Top button to body
    const backToTop = document.createElement('back-to-top');
    document.body.appendChild(backToTop);
});

console.log(`theme.js loaded.`);
