<?php
/*
Plugin Name: Force Delete Posts
Plugin URI: https://github.com/liamstewart23/WordPressForceDeletePosts
Description: Adds the ability for administrators to instantly delete posts by skipping the trash.
Version: 1.1.0
Author: Liam Stewart
Author URI: https://liamstewart.ca
*/

/**
 * Post List Styles
 */
function ls_fd_column_width()
{
    echo '<style type="text/css">';
    echo '.column-ls_fd_column { text-align: center; width:60px !important; overflow:hidden }';// Custom Column styles
    echo '.ls_plfi_label {justify-content:center;display:flex;}';
    echo '</style>';
}

add_action('admin_head', 'ls_fd_column_width');

/**
 * @param $defaults
 * @return mixed
 * Adding column to post list.
 */
function ls_fd_columns_head($defaults)
{
    $defaults['ls_fd_column'] = '<span class="ls_plfi_label">Force <br/> Delete</span>';
    return $defaults;
}

/**
 * @param $column_name
 * @param $post_ID
 * Adding column icon.
 */
function ls_fd_columns_content($column_name, $post_ID)
{
    // If column exists
    if ($column_name === 'ls_fd_column') {
        ls_fd_force_delete('<span class="dashicons dashicons-trash"></span>');
    }
}

// Pages
add_filter('manage_pages_columns', 'ls_fd_columns_head');
add_action('manage_pages_custom_column', 'ls_fd_columns_content', 10, 2);

// All Post Types
add_filter('manage_posts_columns', 'ls_fd_columns_head');
add_action('manage_posts_custom_column', 'ls_fd_columns_content', 10, 2);

/**
 * @param string $link
 * Delete Post
 */
function ls_fd_force_delete($link = '<span class="dashicons dashicons-trash"></span>')
{
    global $post;
    if (!current_user_can('edit_post', $post->ID)) {
        return;
    }

    $link = "<a onclick=\"return confirm('Permanently delete this?')\"  href='" . wp_nonce_url(get_admin_url() . 'post.php?action=delete&amp;post=' . $post->ID, 'delete-post_' . $post->ID) . "'>" . $link . "</a>";
    echo $link;
}

/**
 * @param $post_id
 * Delete post attachments
 */
function ls_fd_remove_attachment($post_id)
{
    if(has_post_thumbnail( $post_id )) {
        $attachment_id = get_post_thumbnail_id( $post_id );
        wp_delete_attachment($attachment_id, true);
    }
}
add_action( 'before_delete_post', 'ls_fd_remove_attachment', 10 );
