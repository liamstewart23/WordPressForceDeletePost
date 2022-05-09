<?php

namespace ForceDeletePosts;

class forceDeletePosts
{

    public function __construct()
    {
        // Styles
        add_action('admin_enqueue_scripts', [$this, 'plugin_styles']);

        // Add to Pages
        add_filter('manage_pages_columns', [$this, 'create_column_heading']);
        add_action('manage_pages_custom_column', [$this, 'column_content'], 10, 2);

        // All Post Types
        add_filter('manage_posts_columns', [$this, 'create_column_heading']);
        add_action('manage_posts_custom_column', [$this, 'column_content'], 10, 2);

        // Delete Featured Image
        add_action('before_delete_post', [$this, 'delete_featured_image'], 10);
    }

    /**
     * Enqueue plugin stylesheet
     */
    public function plugin_styles()
    {
        wp_register_style( 'ls_fd_admin_css',  plugin_dir_url( __FILE__ ) . 'styles.css', false, '1.0.0' );
        wp_enqueue_style( 'ls_fd_admin_css' );
    }

    /**
     *
     * Adding column to post list.
     *
     * @param $defaults
     *
     */
    public function create_column_heading($defaults)
    {
        $defaults['ls_fd_column'] = '<span class="ls_fd_label">Force Delete</span>';

        return $defaults;
    }

    /**
     *
     * Adding column icon.
     *
     * @param $column_name
     * @param $post_ID
     */
    public function column_content($column_name, $post_ID): void
    {
        if ($column_name === 'ls_fd_column') {
            $this->delete_post();
        }
    }

    /**
     * Delete Post
     */
    public function delete_post()
    {
        global $post;
        if ( ! current_user_can('edit_post', $post->ID)) {
            return;
        }

        $link = wp_nonce_url(get_admin_url().'post.php?action=delete&amp;post='.$post->ID,
            'delete-post_'.$post->ID);
        $pt = get_post_type_object( get_post_type($post->ID) )->labels->singular_name;
        $message = "Permanently delete this {$pt}?";
        echo "<a onclick='return confirm(\"{$message}\")' href=\"{$link}\"><span class=\"dashicons dashicons-trash\"></span></a>";
    }

    /**
     *
     * Delete Featured Image Attachment
     *
     * @param $post_id
     *
     * @return bool
     */
    public function delete_featured_image($post_id): bool
    {
        if (has_post_thumbnail($post_id)) {
            $attachment_id = get_post_thumbnail_id($post_id);
            wp_delete_attachment($attachment_id, true);

            return true;
        }

        return false;
    }

}
