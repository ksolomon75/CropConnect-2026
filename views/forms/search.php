<?php
/**
 * Global Search Form
 *
 * Please review documentation upon first use, and, as-needed:
 * https://docs.vincentdevelopment.ca/docs/starter-v3-enhancements/search-global.html
 */

namespace BasicWP;

?>

<search role="search">
  <form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="global-search-form relative flex justify-start max-w-full w-full">
    <label for="globalSearch">
      <svg role="img" aria-labelledby="globalSearchLabel" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="absolute w-4 top-2 left-2 fill-primary-700">
        <title id="globalSearchLabel"><?php echo esc_attr_x( 'Search', 'search-label', 'basicwp' ); ?></title>

        <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/>
      </svg>
    </label>

    <input id="globalSearch" type="search" placeholder="" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" class="md:min-w-[25ch] w-full md:w-[18em] max-w-full text-sm p-1 pl-7 border-2 border-primary-700 rounded-l focus-visible:ring-2 ring-primary-700 !outline-default">

    <button type="submit" class="bg-primary rounded-r py-0 px-3 height-full text-sm text-white focus-visible:ring-2 ring-primary-700 !outline-default cursor-default">
      <?php echo esc_attr_x( 'Search', 'search-submit', 'basicwp' ); ?>
    </button>
  </form>
</search>
