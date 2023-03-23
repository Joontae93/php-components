<?php

/**
 * A Component Class that displays content a few different ways. All methods have an $args bypass and an $echo control where `false` returns the markup and `true` echoes the markup. The $args array also shows expected parameters.
 * 
 * @param bool $acf class-wide control to use acf fields or standard Wordpress field lookups (e.g. `get_field` vs `get_the_excerpt`). If true, excerpt will be set with `get_field('archive_content',$id)`. Defaults `true`
 * 
 * @author KJ Roelke
 * @version 1.0.0
 */


class ContentSectionComponents {
    private bool $acf = true;
    function __construct(bool $acf = true) {
        $this->acf = $acf;
    }

    private function print_error(string $message, bool $return = true) {
        if ($return) {
            return "<span class='d-block' style='padding:4em;background-color:darkred;color:white;'>Error: {$message}</span>";
        } else print_r("<span class='d-block' style='padding:4em;background-color:darkred;color:white;'>Error: {$message}</span>", $return);
    }

    /**
     * A headline element that has lots of optional parameters in the $args array.
     * 
     * @param array $args Pass optional customizations
     * 
     * ```php
     * $args = array(
     * 'headline_element'        => ?string default "h2",
     * 'headline_class'          => ?string default "headline",
     * 'subheadline_element'     => ?string default 'span');
     * 'subheadline_class'       => ?string default 'subheadline');
     * 'subheadline_content'     => ?string the subheadline content,
     * ```
     */
    public function headline(string $headline, bool $echo = true, array ...$args) {
        $default = array(
            'headline_element'       => 'h2',
            'headline_class'         => 'headline',
            'subheadline_element'    => 'span',
            'subheadline_class'      => 'subheadline',
        );

        $options = array_merge($default, ...$args);
        extract($options);

        $markup = "<{$headline_element} class={$headline_class}>{$headline}</{$headline_element}>";
        if (!empty($subheadline_content)) $markup .= "<{$subheadline_element} class='{$subheadline_class}'>{$subheadline_content}</{$subheadline_element}>";
        if ($echo) {
            echo $markup;
        } else return $markup;
    }

    /**
     *  Generates an `<a>` or `<button>` based on whether or not a $link is passed. Recommended use with named parameters for simple configuration.
     */
    public function cta_button(string $text = 'Learn More', string $link = null, string $html_class = 'btn__primary--fill mt-5', bool $echo = true) {
        $markup = ($link) ? "<a href='{$link}' class='{$html_class}'>{$text}</a>" : "<button class='{$html_class}'>{$text}</button>";
        if ($echo) {
            echo $markup;
        } else return $markup;
    }

    /**
     * Gets the Hero `<section>` with class 'hero'. Optional Background Image or color.
     * 
     * @param array $args Expects an associative array: 
     * ```
     * $args = array(
     * 'has_background_image' => bool,
     * 'background_image' => ?string the URL for CSS `background-image`,
     * 'headline' => string,
     * 'subheadline' => ?string,
     * 'has_cta' => bool,
     * 'cta_link' => ?string the url
     * );
     * ```
     */
    public function hero_section(int $post_id = null, $echo = true, array ...$args) {
        if (empty($post_id)) {
            extract($args);
        } else {
            $hero = get_field('hero', $post_id);
            extract($hero);
        }
        $markupStart = $has_background_image ? "<section class='hero w-100 py-5 style='background-image:url('{$background_image}')>" : "<section class='hero w-100 py-5' style='background-color:var(--bs-secondary);'>";
        $markupStart .= "
        <div class='container'>
            <div class='row my-5'>
                <div class='col text-center py-5'>";
        $markupInner =  $this->headline($headline, false, array('headline_element' => 'h1', 'headline_class' => 'headline mb-5', 'subheadline_content' => $subheadline)) . $this->cta_button(text: $cta_text, link: $cta_link, echo: false);
        $markupEnd = "</div></div></div></section>";
        $markup = "{$markupStart}{$markupInner}{$markupEnd}";
        if ($echo) {
            echo $markup;
        } else return $markup;
    }

    /**
     * $args array parameters
     * ```php
     * $args = array(
     * 'media_type'       => 'photo',
     * 'reverse'          => false,
     * 'image_src'        => null,
     * )
     * ```
     * @param array $args additional parameters
     * 
     */
    public function two_col_text_and_media(string $headline, string $content, string $cta_text, string $cta_link, bool $echo = true, array ...$args) {
        $default = array(
            'media_type'       => 'photo',
            'reverse'          => false,
            'image_src'        => null,
        );
        $options = array_merge($default, ...$args);
        extract($options);
        $container_start = $reverse ? '<div class="row flex-row-reverse">' : '<div class="row">';
        $div_end = '</div>';
        $col_start = '<div class="col-6">';
        $col_1_content = '';
        if ($media_type === 'photo' && $image_src) {
            $col_1_content = "<figure class='two-col__image'><img src={$image_src} /></figure>";
        } else if ($media_type === 'video') {
            $col_1_content = "<figure class='two-col__video'>Video!</figure>";
        }
        $col_2_content = $this->headline($headline, false, array("subheadline_content" => $content, "subheadline_element" => "p", "subheadline_class" => "text-content")) . $this->cta_button(text: $cta_text, link: $cta_link, echo: false);
        $markup = "
        {$container_start}
            {$col_start}{$col_1_content}{$div_end}
            {$col_start}{$col_2_content}{$div_end}
        {$div_end}";

        if ($echo) {
            echo $markup;
        } else return $markup;
    }

    /**
     * Vertical Card Layout with an image. $args overrides the `headline` settings
     * 
     * @param array $args Expects an associative array: 
     * ```php
     * $headline_args = array(
     * 'headline_element'        => ?string default "h2",
     * 'headline_class'          => ?string default "vertical-card__title",
     * 'subheadline_element'     => ?string default 'p');
     * 'subheadline_class'       => ?string default 'vertical-card__excerpt');
     * 'subheadline_content'     => ?string the subheadline content,
     * ```
     */
    public function vertical_card(string $image_src, string $headline, string $excerpt, bool $echo = true, array ...$args) {
        $headline_args = array(
            'headline_class'         => 'vertical-card__title',
            'subheadline_element'    => 'p',
            'subheadline_class'      => 'vertical-card__excerpt',
            'subheadline_content'    => $excerpt,
        );

        $options = array_merge($headline_args, ...$args);
        extract($options);
        $card_image = "<figure class='vertical-card__image'><img src={$image_src} /></figure>";
        $card_text_content = "<div class='vertical-card__content'>{$this->headline($headline, false,$options)}</div>";
        $markup = "<div class='vertical-card'>{$card_image}{$card_text_content}</div>";
        if ($echo) {
            echo $markup;
        } else return $markup;
    }
}
