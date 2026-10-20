/**
 * VDI Main Nav - All Main Navigation Functionality
 *
 * Please review documentation upon first use, and, as-needed:
 * https://docs.vincentdevelopment.ca/docs/starter-v3-enhancements/navigation/
 */

/**
 * Navigation
 *  Handles navigation logic
 *
 * @param {string} mobileMenuButtonId
 * @param {string} dropDownClass
 * @param {string} subMenuLinkClass
 */
class Navigation {
  /**
   * The main toggle element
   *
   * @type {HTMLElement}
   * @link {https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Classes/Private_properties}
   */
  #mobileMenuButton;

  /**
   * List of sub dropdown buttons
   *
   * @type {NodeList}
   * @link {https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Classes/Private_properties}
   */
  #dropdownButtons;

  /**
   * Class name to identify sub menu items (ul>li>a)
   * @type {string}
   */
  #subMenuElementIdentifier;

  /**
   * Current navigation level in sliding viewport
   * @type {number}
   */
  #currentLevel = 0;

  /**
   * Navigation stack for breadcrumb functionality
   * @type {Array}
   */
  #navigationStack = [];

  /**
   * Whether sliding viewport mode is enabled
   * @type {boolean}
   */
  #slidingViewportEnabled = false;



  constructor(mobileMenuButtonId, dropDownClass, subMenuLinkClass = "sub-menu-item") {
    this.#mobileMenuButton = document.getElementById(mobileMenuButtonId);
    this.#dropdownButtons = document.querySelectorAll(dropDownClass); // Do not change this to getElementsByClassName, logic is iterating over non-live node list
    this.#subMenuElementIdentifier = subMenuLinkClass;

    this.handleEscapeKey();
    
    // Initialize sliding viewport immediately if styles are detected
    this.initializeSlidingViewport();
  }

  /**
   * Handles main mobile toggling
   *  Adds an event listener to mobile menu dropdown
   *  toggle button
   */
  mobileMenuToggle() {
    this.#mobileMenuButton.addEventListener("click", (event) => {
      this.toggleMobileMenu(event, this.#mobileMenuButton); // toggle submenu on mobile
    });

    this.closeOnBlur(this.#mobileMenuButton, { "navType": "desktop" }); // close submenu when user clicks outside
    this.closeOnBlur(this.#mobileMenuButton, { "navType": "mobile" });
  }

  /**
   * Handles dropdowns on desktop navigation
   *  Loops over list of navigation dropdown buttons
   *  and adds eventlisteners to toggle view
   */
  desktopMenuDropdowns() {
    this.#dropdownButtons.forEach((button) => {
      button.addEventListener("click", (event) => {
        this.toggleDropdown(event, button);
      });

      this.closeOnBlur(button); // close menu when user clicks outside
      this.handleFocus(button);  // close dropdown when user finishes tabbing submenu elements
    });
  }

  /**
   * Toggles desktop dropdowns
   *
   * @param {EventTarget} event
   * @param {HTMLButtonElement} button
   */
  toggleDropdown(event, button) {
    this.toggleAriaExpanded(event, button);
  }

  /**
   * Toggles mobile menu
   *
   * @param {EventTarget} event
   * @param {HTMLButtonElement} button
   */
  toggleMobileMenu(event, button) {
    this.toggleAriaExpanded(event, button);
    
    const isExpanded = button.getAttribute("aria-expanded") === "true";
    
    if (!isExpanded) {
      // Reset sliding navigation when menu is closed
      if (this.#slidingViewportEnabled) {
        this.resetSlidingNavigation();
      }
    }
  }

  /**
   * Toggles aria-expanded attribute
   *
   * @param {EventTarget} event
   * @param {HTMLButtonElement} button
   */
  toggleAriaExpanded(event, button) {
    let isExpanded = event.currentTarget.getAttribute("aria-expanded") === "true"; // true is returned as string

    // ... but open the targeted secondary nav
    if (!isExpanded) {
      // close all dropdowns...
      this.closeAllDropDowns();
      // then toggle targeted dropdown...
      button.setAttribute("aria-expanded", true);
    } else {
      button.setAttribute("aria-expanded", false);
    }
  }

  /**
   * Close dropdown when user clicks anywhere else on the screen
   *
   * @param {HTMLButtonElement} button
   * @link {https://developer.mozilla.org/en-US/docs/Web/API/Element/blur_event}
   * @link {https://developer.mozilla.org/en-US/docs/Web/API/FocusEvent/relatedTarget}
   */
  closeOnBlur(button, args) {
    if (args === undefined || args?.navType === "desktop") {
      button.addEventListener("blur", (event) => {
        if (event.relatedTarget == null) {
          event.currentTarget.setAttribute("aria-expanded", false);
        }
      }, { passive: true });
    }

    //FIXME: Remove hardcoded literal
    if (args?.navType === "mobile") {
      document.getElementById("menu-container").addEventListener("blur", (event) => {
        // Note: cannot group negation -1*-1*-1 != -(1*1*1)
        const isNull = event.relatedTarget == null;
        const isNotMenuItem = !isNull && !event.relatedTarget.classList.contains("menu-vdi__toggle")
          && !event.relatedTarget.classList.contains("sub-menu-item") 
          && !event.relatedTarget.classList.contains("menu-vdi__link")
          && !event.relatedTarget.classList.contains("menu-vdi__back"); // Don't close on back button click

        if (isNull || isNotMenuItem)
          button.setAttribute("aria-expanded", false);
      }, true)
    }
  }

  /**
   * Handles escape behaviour
   *  Closes all dropdown when user hits
   *  escape key
   *
   * @link {https://developer.mozilla.org/en-US/docs/Web/API/Element/keyup_event}
   */
  handleEscapeKey() {
    window.addEventListener("keyup", (event) => {
      if (event.key === "Escape") {
        this.#mobileMenuButton.setAttribute("aria-expanded", false);
        this.closeAllDropDowns();
        
        // Reset sliding navigation on escape
        if (this.#slidingViewportEnabled) {
          this.resetSlidingNavigation();
        }
      }
    }, { passive: true });
  }

  /**
   * Close all dropdown menus
   *  Sets aria expanded property by looping
   *  over the list of dropdown button elements
   *
   * @link {https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Attributes/aria-expanded}
   */
  closeAllDropDowns() {
    this.#dropdownButtons.forEach((button) => {
      button.setAttribute("aria-expanded", false);
    });
  }

  /**
   * Handle focus
   *  Watches blur event on submenu list container, if the next
   *  focus element is not a submenu list item, closes the
   *  dropdown.
   *
   *  Implemented for WCAG 2.2 compliance
   *
   * @param {htmlButtonElement} button
   */
  handleFocus(button) {
    const subMenuListElement = button.closest("ul");

    /**
     * Ducking JavaScript, I am not sure why the blur event on submenu(ul)
     * would bubble up to the button since the button and sub menu list element
     * are siblings and not nested. This is a mystery to me.
     *
     * Here we are stopping any bubbling event that may be propagated to the
     * top level buttons. This includes the bubbling event from sub menu list element.
     *
     * If anyone finds a better solution to this or can explain bubbling of focus event
     * to next sibling, you time and effort would be much appreciated.
     */
    button.addEventListener("focusout", (event) => {
      event.stopImmediatePropagation();
    })

    subMenuListElement.addEventListener("focusout", (event) => {
      // blur event triggers when user clicks outside
      const nextFocusElement = event.relatedTarget;

      let isSubMenuElement;

      if (nextFocusElement !== null) {
        isSubMenuElement = nextFocusElement.classList.contains(this.#subMenuElementIdentifier);
      } else {
        isSubMenuElement = false; // close when user clicks outside
      }

      if (!isSubMenuElement) {
        this.closeAllDropDowns();
      }
    });
  }

  /**
   * Initialize sliding viewport navigation
   * Detects if sliding viewport should be enabled and sets up the structure
   */
  initializeSlidingViewport() {
    // Check if we should enable sliding viewport (could be based on screen size, user preference, etc.)
    this.#slidingViewportEnabled = this.shouldEnableSlidingViewport();
    
    if (this.#slidingViewportEnabled) {
      console.log('Sliding viewport enabled, setting up structure');
      this.setupSlidingViewportStructure();
    } else {
      console.log('Sliding viewport not enabled');
    }
  }

  /**
   * Determine if sliding viewport should be enabled
   * @returns {boolean}
   */
  shouldEnableSlidingViewport() {
    // Check if sliding viewport styles are loaded by testing if the CSS rule exists
    // This allows CSS-based switching between navigation styles
    const isMobile = window.innerWidth <= 1000; // 62.5rem converted to px
    
    if (!isMobile) return false;
    
    // Check if sliding viewport CSS is loaded by testing a specific rule
    // We need to test the element within the proper context (.nav-main)
    try {
      const navMain = document.querySelector('.nav-main');
      if (!navMain) return false;
      
      const testElement = document.createElement('div');
      testElement.className = 'menu-vdi--sliding';
      testElement.style.position = 'absolute';
      testElement.style.visibility = 'hidden';
      testElement.style.height = '1px';
      testElement.style.width = '1px';
      
      navMain.appendChild(testElement);
      
      // Get computed styles to check if sliding viewport CSS is active
      const computedStyle = window.getComputedStyle(testElement);
      const hasOverflowHidden = computedStyle.overflow === 'hidden';
      
      navMain.removeChild(testElement);
      
      return hasOverflowHidden;
    } catch (error) {
      console.warn('Error detecting sliding viewport styles:', error);
      return false;
    }
  }

  /**
   * Setup the HTML structure for sliding viewport navigation
   */
  setupSlidingViewportStructure() {
    const menuContainer = document.getElementById('menu-container');
    if (!menuContainer) {
      console.warn('Menu container not found');
      return;
    }

    // Don't set up if already configured
    if (menuContainer.classList.contains('menu-vdi--sliding')) {
      console.log('Sliding viewport already configured');
      return;
    }

    console.log('Setting up sliding viewport structure');

    // Add sliding class to enable sliding styles
    menuContainer.classList.add('menu-vdi--sliding');

    // Create viewport container
    const viewport = document.createElement('div');
    viewport.className = 'menu-vdi__viewport';
    viewport.setAttribute('data-current-level', '0');

    // Create main level
    const mainLevel = document.createElement('div');
    mainLevel.className = 'menu-vdi__level menu-vdi__level--main';
    mainLevel.setAttribute('data-level', '0');

    // Move existing menu items to main level
    const existingItems = Array.from(menuContainer.children);
    console.log('Moving', existingItems.length, 'existing items to main level');
    
    existingItems.forEach(item => {
      mainLevel.appendChild(item);
    });

    // Setup click handlers for parent items in sliding mode
    this.setupSlidingClickHandlers(mainLevel);

    viewport.appendChild(mainLevel);
    menuContainer.appendChild(viewport);
    
    console.log('Sliding viewport structure complete');
  }

  /**
   * Setup click handlers for parent menu items in sliding mode
   * @param {HTMLElement} level
   */
  setupSlidingClickHandlers(level) {
    const parentButtons = level.querySelectorAll('.menu-vdi__toggle');
    
    parentButtons.forEach(button => {
      button.addEventListener('click', (event) => {
        if (this.#slidingViewportEnabled) {
          event.preventDefault();
          event.stopPropagation();
          
          const parentItem = button.closest('.menu-vdi__item--parent');
          const submenu = parentItem.querySelector('.menu-vdi__submenu');
          
          if (submenu) {
            this.navigateToLevel(button.textContent.trim(), submenu);
          }
        }
      }, true); // Use capture phase to intercept before other handlers
    });
  }

  /**
   * Navigate to a specific navigation level
   * @param {string} levelTitle
   * @param {HTMLElement} submenuElement
   */
  navigateToLevel(levelTitle, submenuElement) {
    const viewport = document.querySelector('.menu-vdi__viewport');
    if (!viewport) return;

    this.#currentLevel++;
    this.#navigationStack.push({ title: levelTitle, level: this.#currentLevel });

    // Create new level
    const newLevel = document.createElement('div');
    newLevel.className = 'menu-vdi__level';
    newLevel.setAttribute('data-level', this.#currentLevel);

    // Add back button
    const backButton = this.createBackButton();
    newLevel.appendChild(backButton);

    // Add level title
    const levelTitleEl = document.createElement('div');
    levelTitleEl.className = 'menu-vdi__level-title';
    levelTitleEl.textContent = levelTitle;
    newLevel.appendChild(levelTitleEl);

    // Clone and add submenu items
    const submenuItems = submenuElement.cloneNode(true);
    submenuItems.className = 'menu-vdi__level-items';
    
    // Convert submenu items to level items and ensure proper visibility
    const items = submenuItems.querySelectorAll('.menu-vdi__item');
    items.forEach(item => {
      // Remove any nested classes that don't apply to sliding mode
      item.classList.remove('menu-vdi__item--child', 'menu-vdi__item--grandchild');
      
      // Handle nested items if they exist
      const nestedToggle = item.querySelector('.menu-vdi__toggle');
      if (nestedToggle) {
        nestedToggle.addEventListener('click', (event) => {
          event.preventDefault();
          // Could implement deeper nesting here if needed
        });
      }
    });

    // Remove any hidden submenu classes from nested elements to ensure visibility
    const nestedSubmenus = submenuItems.querySelectorAll('.menu-vdi__submenu');
    nestedSubmenus.forEach(nestedSubmenu => {
      nestedSubmenu.classList.remove('menu-vdi__submenu');
      nestedSubmenu.classList.add('menu-vdi__nested-items');
    });

    // Ensure all list items are visible in sliding mode
    const allListItems = submenuItems.querySelectorAll('li');
    allListItems.forEach(li => {
      if (!li.classList.contains('menu-vdi__item')) {
        li.classList.add('menu-vdi__item');
      }
    });

    newLevel.appendChild(submenuItems);
    viewport.appendChild(newLevel);

    // Animate to new level
    this.animateToLevel(this.#currentLevel);
  }

  /**
   * Create a back button for navigation levels
   * @returns {HTMLElement}
   */
  createBackButton() {
    const backButton = document.createElement('button');
    backButton.className = 'menu-vdi__back';
    backButton.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
      </svg>
      Back
    `;

    backButton.addEventListener('click', (event) => {
      event.preventDefault();
      event.stopPropagation();
      this.navigateBack();
    });

    return backButton;
  }

  /**
   * Navigate back to previous level
   */
  navigateBack() {
    if (this.#currentLevel > 0) {
      this.#currentLevel--;
      this.#navigationStack.pop();

      // Remove the current level element
      const viewport = document.querySelector('.menu-vdi__viewport');
      const currentLevelEl = viewport.querySelector(`[data-level="${this.#currentLevel + 1}"]`);
      if (currentLevelEl) {
        currentLevelEl.remove();
      }

      // Animate back to previous level
      this.animateToLevel(this.#currentLevel);
      
      // Ensure menu stays open after back navigation
      setTimeout(() => {
        if (this.#mobileMenuButton.getAttribute("aria-expanded") === "false") {
          this.#mobileMenuButton.setAttribute("aria-expanded", "true");
        }
      }, 50);
    }
  }

  /**
   * Animate viewport to specific level
   * @param {number} level
   */
  animateToLevel(level) {
    const viewport = document.querySelector('.menu-vdi__viewport');
    if (!viewport) return;

    const translateX = -level * 100;
    viewport.style.transform = `translateX(${translateX}%)`;
    viewport.setAttribute('data-current-level', level);
  }

  /**
   * Reset sliding navigation to main level
   */
  resetSlidingNavigation() {
    const menuContainer = document.getElementById('menu-container');
    if (!menuContainer) return;
    
    if (this.#slidingViewportEnabled) {
      this.#currentLevel = 0;
      this.#navigationStack = [];
      
      const viewport = document.querySelector('.menu-vdi__viewport');
      if (viewport) {
        // Remove all levels except main
        const levels = viewport.querySelectorAll('.menu-vdi__level:not(.menu-vdi__level--main)');
        levels.forEach(level => level.remove());
        
        // Reset position
        this.animateToLevel(0);
      }
    } else {
      // Clean up sliding structure and restore normal menu
      this.cleanupSlidingStructure();
    }
  }

  /**
   * Clean up sliding viewport structure and restore normal menu
   */
  cleanupSlidingStructure() {
    const menuContainer = document.getElementById('menu-container');
    if (!menuContainer) return;

    // Remove sliding class
    menuContainer.classList.remove('menu-vdi--sliding');

    // Find viewport and move items back to container
    const viewport = menuContainer.querySelector('.menu-vdi__viewport');
    if (viewport) {
      const mainLevel = viewport.querySelector('.menu-vdi__level--main');
      if (mainLevel) {
        // Move all items back to menu container
        const items = Array.from(mainLevel.children);
        items.forEach(item => {
          menuContainer.appendChild(item);
        });
      }
      
      // Remove viewport structure
      viewport.remove();
    }

    console.log('Sliding structure cleaned up');
  }
}

export default Navigation;
