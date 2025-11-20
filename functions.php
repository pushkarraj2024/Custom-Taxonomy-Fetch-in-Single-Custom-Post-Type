<?php
function get_taxonomy_acf_field_shortcode($atts) {

    // Allow multiple fields to be passed
    $atts = shortcode_atts([
        'taxonomy'              => '',
        'image'                 => '',
        'name'                  => '',
        'description'           => '',
    ], $atts);

    $post_id = get_the_ID();
    $terms = get_the_terms($post_id, $atts['taxonomy']);

    if (empty($terms) || is_wp_error($terms)) {
        return '';
    }

    $term_id = $terms[0]->term_id;

    // Get ACF fields
    $author_image        = get_field($atts['image'], 'term_' . $term_id);
    $author_name         = get_field($atts['name'], 'term_' . $term_id);
    $author_description  = get_field($atts['description'], 'term_' . $term_id);

    // Build HTML
    $output  = '<div class="blog_author">';

    // Image (ACF image array)
    if (!empty($author_image['url'])) {
        $output .= '<div class="blog_header">';
        $output .= '<img src="' . esc_url($author_image['url']) . '" class="blog_author_image" />';
    } else {
        $output .= '<div class="blog_header">';
    }

    // Name
    if ($author_name) {
        $output .= '<p class="blog_author_name">' . esc_html($author_name) . '</p>';
    }

    $output .= '</div>'; // close blog_header

    // Description
    if ($author_description) {
        $output .= '<div class="blog_description">';
        $output .= $author_description;   // <— NO esc_html, allows formatting
        $output .= '</div>';
    }

    $output .= '</div>'; // close blog_author

    return $output;
}

add_shortcode('tax_acf', 'get_taxonomy_acf_field_shortcode');

?>

