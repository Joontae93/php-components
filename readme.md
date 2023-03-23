# About this Project

This is a collection of PHP classes that can be used to create html. It's kind of like a JSX style of PHP...but as OOP....

So you _might_ say it's just regular ol' PHP.

Specifically, this is built with Wordpress in mind, so take `index.php` as if it was your theme's `functions.php`. Also, many of the classes might rely on project dependencies, e.g. [Advanced Custom Fields](advancedcustomfields.com/) or a custom post type.

Big shoutout to [Astro.js](https://astro.build) for the images :)

# Changelog

## v1.1

Added a Content-Sections class that assumes ACF is installed. Shoutout to the team at [Themeco](https://theme.co) for inspiring me with component types and controls.

### Methods (aka the components)

-   **headline**
    -   with optional `subheadline`
    -   both with controllable elements and classes
-   **cta_button**
    -   Generates an `<a class='btn'>` or `<button class='btn'>` depending on the presence of a `$link`
-   **hero_section**
    -   a whole `<section class='hero'>` with controls
-   **two_col_text_and_media**
    -   Makes use of `headline` and `cta_button`
    -   Generates a `row` with 2 `<div class='col-6'>` inside
    -   Reversible!
-   **vertical_card**
    -   Like two_col_text_and_media, but is bootstrap agnostic
    -   Also makes use of `headline` and its `$args` controls the `headline`'s `$args` parameter.

## v1.0

Init!

This first class has 4 components: Featured Article, Vertical Card, Horizontal Card, and Popular Articles sidebar.

Each method has an `$echo` paramter (default `true`) to control whether to `echo` or `return` the HTML.

### Featured Article

Featured Article has an Image and `View Article` Button.

[Preview Screenshot](public/images/Featured%20Article.png)

-   @param {int} $id &mdash; the WP_Post ID
-   @param {bool} $echo &mdash; whether to `echo` or `return`
-   @param {array} `$args` &mdash; If bypassing first two params, `$args` expects the following strings: ($excerpt, $title, $btn_link, $style)

### Vertical / Horizontal Card

-   [Vertical Card (in 2-up Layout)](public/images/Vertical%20Card%20Layout%202-up.png)
-   [Horizontal Card (in 3-up Layout)](public/images/Horizontal%20Card%20Layout%20with%20Popular%20Articles%20Sidebar.png)

The same parameters as Featured Article, but it looks different depending on which method you call.

### Popular Articles

Takes an array of articles and sets them as clickable `li`s inside of a `ul`

-   @param {array} $articles &mdash An array of articles.
