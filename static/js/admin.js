/**
 * Admin JS
 */
import { registerButtonComponent } from './components/button.js';

const app = () => {
  registerButtonComponent();
}

document.addEventListener('DOMContentLoaded', app);

console.log(`admin.js loaded.`);
