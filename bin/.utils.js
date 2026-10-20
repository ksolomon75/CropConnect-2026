/**
 * Utility functions shared between builds
 */

const fs = require("fs");
const path = require("path");
const distDir = path.resolve(`static/dist`);
const debounce = require("lodash.debounce");

require("dotenv").config();

// Theme root as working directory
const cwd = path.resolve("..");

// Compile Tailwind
module.exports.tailwindToCSS = debounce(() => {
  try {
    require("child_process").execSync(
      `npx @tailwindcss/cli -i styles/theme.css -o static/dist/theme.css`,
      { stdio: "inherit" }
    );
  } catch (error) {
    console.log(`Error compiling Tailwind to CSS:`, error);
  }
}, 50);
