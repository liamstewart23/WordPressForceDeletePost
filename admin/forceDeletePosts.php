<?php

namespace ForceDeletePosts;

class forceDeletePosts
{

    public function __construct()
    {
        // Styles
        add_action('admin_head', [$this, 'column_styles']);

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
     *
     * Set Post List Styles
     *
     */
    public function column_styles(): void
    {
        echo '<style>.column-ls_fd_column { text-align: center; width:60px !important; overflow:hidden }.ls_plfi_label {justify-content:center;display:flex;}</style>';
    }

    /**
     *
     * Adding column to post list.
     *
     * @param $defaults
     *
     * @return mixed
     */
    function create_column_heading($defaults): mixed
    {
        $defaults['ls_fd_column'] = '<span class="ls_plfi_label">Force <br/> Delete</span>';

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
     *
     * Delete Post
     *
     * @return void
     */
    public function delete_post(): void
    {
        global $post;
        if ( ! current_user_can('edit_post', $post->ID)) {
            return;
        }

        $link = wp_nonce_url(get_admin_url().'post.php?action=delete&amp;post='.$post->ID,
            'delete-post_'.$post->ID);
        echo "<a onclick=\"return confirm('Permanently delete this post?')\"  href=\"{$link}\"><span class=\"dashicons dashicons-trash\" style=\"color: #b90e0e;\"></span></a>";
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