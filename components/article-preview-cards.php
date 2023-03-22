<?php

/**
 * A Component Class that displays different article previews
 * 
 * @param bool $acf class-wide control to use acf fields or standard Wordpress field lookups (e.g. `get_field` vs `get_the_excerpt`). If true, excerpt will be set with `get_field('archive_content',$id)`. Defaults `false`
 * 
 * @author KJ Roelke
 * @version 1.0.0
 */


class ArticlePreviewComponents {
    private $acf;
    private function __construct(bool $acf = false) {
        $this->acf = $acf;
    }
    /**
     * Generates Card Markup with the appropriate CSS Class Names
     * @param string $direction "vertical" or "horizontal"
     * @return HTMLString
     */
    private function get_the_markup(string $title, string $excerpt, string $image_src, string $direction = 'vertical') {
        return "<article class='{$direction}-article'>
	<figure class='{$direction}-article__image'><img src={$image_src} /></figure>
	<div class='{$direction}-article__content'>
		<h2 class='{$direction}-article__title'>{$title}</h2>
		<p class='{$direction}-article__excerpt'>{$excerpt}</p>
	</div>
</article>";
    }
    private function set_the_fields($id) {
        $args = array(
            'btn_link'  => get_the_permalink($id),
            'title'     => get_the_title($id),
            'image_src' => get_the_post_thumbnail_url($id)
        );
        if (false === $this->acf) {
            $args['excerpt'] = get_the_excerpt($id);
        } else {
            $args['excerpt'] = get_field('archive_content', $id);
        }
        return $args;
    }
    /** 
     * Featured Article has an Image and `View Article` Button.
     * 
     * @param int $id the post ID
     * @param bool $echo whether to `echo` or `return`
     * @param array $args if bypassing first two params, $args expects the following strings:
     * ($excerpt, $title, $btn_link, $style)
     * 
     */
    public function featured_article(int $id = null, bool $echo = true, ...$args) {
        if (empty($id)) {
            extract($args);
        } else {
            extract($this->set_the_fields($id, true));
        }
        $style = empty($args['style']) ? '' : $args['style'];
        $markup = "<article class='featured-article' style='{$style}'>
        <div class='row'>
        <div class='col-8'>
            <figure class='featured-article__image'><img src={$image_src} /></figure>
        </div>
        <div class='featured-article__content col-4'>
            <h2 class='featured-article__title'>{$title}</h2>
            <p class='featured-article__excerpt'>{$excerpt}</p>";
        $markup .= "<a href='{$btn_link}' class='btn'>View Article</a>";
        $markup .= "</div></div></article>";

        if ($echo) {
            echo $markup;
        } else return $markup;
    }

    /** 
     * Vertical Layout
     */
    public function vertical_card(int $id = null, bool $echo = true, array ...$args) {
        if (empty($id)) {
            extract($args);
        } else {
            extract($this->set_the_fields($id, true));
        }
        $markup = $this->get_the_markup(title: $title, image_src: $image_src, excerpt: $excerpt);
        if ($echo) {
            echo $markup;
        } else return $markup;
    }

    public function horizontal_card(int $id = null, bool $echo = true, array ...$args) {
        if (empty($id)) {
            list($image_src, $title, $excerpt) = array_values($args);
        } else {
            list($image_src, $title, $excerpt) = array_values($this->set_the_fields($id, true));
        }
        $markup = $this->get_the_markup(direction: 'horizontal', title: $title, image_src: $image_src, excerpt: $excerpt);
        if ($echo) {
            echo $markup;
        } else return $markup;
    }

    /**
     * Takes an array of articles and sets them as clickable `li`s inside of a `ul`
     */
    public function popular_articles(array $articles, $echo = true) {
        $markup = "<aside class='popular-articles'>
	<ul>";
        foreach ($articles as $article) {
            $link = get_the_permalink($article['ID']);
            $title = get_the_title($article['ID']);
            $excerpt = get_field('archive_content', $article['ID']);
            $markup .= "<li class='popular-article'>
					<a href={$link}>
						<article>
							<span class='popular-article__title'>{$title}</span> 
							&mdash; {$excerpt}
						</article>
					</a>
				</li>";
        }
        $markup .= "</ul></aside>";
        if ($echo) {
            echo $markup;
        } else return $markup;
    }
}
