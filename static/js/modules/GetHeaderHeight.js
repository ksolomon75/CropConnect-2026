/**
 * Get Header Height
 * -
 * Get header height and set CSS variable "--hgtHeader" to the header height.
 */

function getHeaderHeight() {
  const headerHeight = document.querySelector('.header__nav-main').getBoundingClientRect().height;
  document.documentElement.style.setProperty('--hgtHeader', `${headerHeight}px`);
}

export default getHeaderHeight;
