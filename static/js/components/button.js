class ButtonComponent extends HTMLElement {
	/**
	 * Parameters
	 *  - btnClasses: Additional classes to add to the block.
	 *  - element: The element to use for the button. Defaults to 'a'.
	 *  - url: The URL to link to.
	 *  - target: The target for the link.
	 *  - title: The text to display on the button.
	 *  - ariaLabel: The ARIA label for the button.
	 *  - color: The color of the button.
	 *  - variant: The variant of the button.
	 *  - size: The size of the button.
	 *  - width: The width of the button.
	 *
	 */

	connectedCallback() {
		if (!this.querySelector(this.getAttribute('element'))) {
			this.append(document.createElement(this.getAttribute('element')));
		}

		this.update();
	}

	static get observedAttributes() {
		return [
			'btnClasses',
			'el',
			'element',
			'type',
			'url',
			'target',
			'title',
			'ariaLabel',
			'color',
			'variant',
			'size',
			'width',
		]
	}

	attributeChangedCallback() {
		this.update();
	}

	update() {
		const btn = this.querySelector(this.getAttribute('element'));

		// console.log('[ButtonComponent] attributes', {
		//   btnClasses: this.getAttribute('btnClasses'),
		//   element: this.getAttribute('element'),
		//   type: this.getAttribute('type'),
		//   url: this.getAttribute('url'),
		//   target: this.getAttribute('target'),
		//   title: this.getAttribute('title'),
		//   ariaLabel: this.getAttribute('ariaLabel'),
		//   color: this.getAttribute('color'),
		//   variant: this.getAttribute('variant'),
		//   size: this.getAttribute('size'),
		//   width: this.getAttribute('width'),
		// });

		if (btn) {
			btn.classList = this.getAttribute('btnClasses') || '';

			if (this.getAttribute('element') == 'a') {
				btn.href = this.getAttribute('url') || '#';

				if (btn.target) {
					btn.target = 'target="${this.getAttribute(target)}"';
				}
			}

			const type = this.getAttribute('type');
			if (type && this.getAttribute('element') !== 'a') {
				btn.type = type;
			}

			btn.title        = this.getAttribute('title') || '';
			btn.textContent  = this.getAttribute('title') || '';

			const ariaLabel = this.getAttribute('ariaLabel');
			if (ariaLabel) {
				btn.setAttribute('aria-label', ariaLabel);
			} else {
				btn.removeAttribute('aria-label');
			}

			btn.setAttribute('data-button-color',   this.getAttribute('color'));
			btn.setAttribute('data-button-variant', this.getAttribute('variant'));
			btn.setAttribute('data-button-size',    this.getAttribute('size'));
			btn.setAttribute('data-button-width',   this.getAttribute('width'));
		}
	}
}

export const registerButtonComponent = () => {
	customElements.define('x-button', ButtonComponent);
}
