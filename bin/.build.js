require("dotenv").config();

const fs = require("fs");
const path = require("path");
const distDir = path.resolve(`static/dist`);
const { tailwindToCSS } = require(`./.utils`);
const start = performance.now();

// Tailwind to CSS
try {
  // Ensure the output directory exists
  if (!fs.existsSync(distDir)) {
    fs.mkdirSync(distDir, { recursive: true });
  }

  // Compile Tailwind
  tailwindToCSS();

  const end = performance.now();
  const runtime = (end - start).toFixed(3);
  console.log(`Production build successfully run in ${runtime}ms`);
} catch (error) {
  console.error("Error processing CSS files:", error);
}
