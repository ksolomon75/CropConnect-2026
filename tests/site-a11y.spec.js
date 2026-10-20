const { test, expect } = require('@playwright/test');
const AxeBuilder = require('@axe-core/playwright').default;

const domain = 'http://domain.local/';
test.use({
  viewport: { width: 1920, height: 1080 },
});

test.describe('site-test', () => {
  test('Homepage Test', async ({ page }, testInfo) => {
    await page.goto(domain);

    await page.screenshot({ path: 'test-results/homepage.png', fullPage: true });

    const accessibilityScanResults = await new AxeBuilder({ page })
      .withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'wcag22a', 'wcag22aa'])
      .analyze();

    await testInfo.attach('accessibility-scan-results', {
      body: JSON.stringify(accessibilityScanResults, null, 2),
      contentType: 'application/json'
    });

    expect(accessibilityScanResults.violations).toEqual([]);
  });

  test('Blog Index Page Test', async ({ page }, testInfo) => {
    await page.goto(`${domain}news/`);

    await page.screenshot({ path: 'test-results/blog-index.png', fullPage: true });

    const accessibilityScanResults = await new AxeBuilder({ page })
      .withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'wcag22a', 'wcag22aa'])
      .analyze();

    await testInfo.attach('accessibility-scan-results', {
      body: JSON.stringify(accessibilityScanResults, null, 2),
      contentType: 'application/json'
    });

    expect(accessibilityScanResults.violations).toEqual([]);
  });

  test('404 Page Test', async ({ page }, testInfo) => {
    await page.goto(`${domain}yaya/`);

    await page.screenshot({ path: 'test-results/404.png', fullPage: true });

    const accessibilityScanResults = await new AxeBuilder({ page })
      .withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'wcag22a', 'wcag22aa'])
      .analyze();

    await testInfo.attach('accessibility-scan-results', {
      body: JSON.stringify(accessibilityScanResults, null, 2),
      contentType: 'application/json'
    });

    expect(accessibilityScanResults.violations).toEqual([]);
  });
});
