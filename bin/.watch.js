require("dotenv").config();

const fs = require("fs");
const path = require("path");
const browserSync = require("browser-sync").create();
const { tailwindToCSS } = require(`./.utils`);

// Theme root as working directory
const cwd = path.resolve("../");

// Initialize BrowserSync
browserSync.init({
  proxy: process.env.LOCALHOST_URL,
  port: process.env.BROWSERSYNC_PORT,
  cors: true,
  cwd: cwd,
  logLevel: `warn`,
  minify: false,
  open: false,
  ui: false,
  ghostMode: false,
  reloadOnRestart: true,
  notify: false,
  watch: true,
});

// Reload JS during development
browserSync.watch(`static/js/*.js`, (event, file) => {
  if (event !== "change") return;

  const start = performance.now();
  try {
    browserSync.stream();
    const end = performance.now();
    const runtime = (end - start).toFixed(3);
    console.log(`JS re-injected successfully in ${runtime}ms`);
  } catch (error) {
    console.log("Error compiling JS:", error);
  }
});

// Compile tailwind to css and reload on changes
browserSync.watch(["**/*.css", "**/*.php"], (event, file) => {
  if (event !== "change") return;

  tailwindToCSS();
  browserSync.reload();
});
