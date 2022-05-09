<?php
/*
Plugin Name: Force Delete Posts
Plugin URI: https://github.com/liamstewart23/WordPressForceDeletePosts
Description: Adds the ability for administrators to instantly delete posts by adding a Force Delete Button to the Post List for Pages, Posts, and all Custom Post Types.
Version: 2.1.1
Author: Liam Stewart
Author URI: https://liamstewart.ca
*/

// If this file is called directly, abort.
if ( ! defined('WPINC')) {
    die;
}

require 'admin/forceDeletePosts.php';
$plugin = new \ForceDeletePosts\forceDeletePosts();
