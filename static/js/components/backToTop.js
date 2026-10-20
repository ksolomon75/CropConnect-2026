// Back to Top Button Component
class BackToTopButton extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
            <button id="backToTopBtn" aria-label="Back to top" class="back-to-top" style="">
                ↑ Top
            </button>
        `;

        const btn = this.querySelector('#backToTopBtn');

        let previousScrollY = window.scrollY;

        window.addEventListener('scroll', () => {
            const currentScrollY = window.scrollY;
            const isScrollingUp = currentScrollY < previousScrollY;
            const shouldShowButton = currentScrollY > 300 && isScrollingUp;

            btn.style.display = shouldShowButton ? 'block' : 'none';
            previousScrollY = currentScrollY;
        });

        btn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
}

export function registerBackToTopButton() {
    if (!customElements.get('back-to-top')) {
        customElements.define('back-to-top', BackToTopButton);
    }
}
