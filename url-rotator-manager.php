<?php

/*
  Plugin Name: URL Rotator Manager
  Plugin URI: http://www.ninjapress.net/url_rotator_manager/
  Description: Url Rotator Manager
  Version: 1.1
  Author: Ninja Press
  Author URI: http://www.ninjapress.net
  License: GPL2
 * 
 */

if (!class_exists('URL_Rotator_manager')) {

   class URL_Rotator_manager {

      /**
       * Construct the plugin object
       */
      public function __construct() {
         // register actions
         add_action('admin_menu', array(&$this, 'add_menu'));
         add_action('admin_init', array(&$this, 'admin_init'));

         add_action('init', array(&$this, 'url_rotator_manager_init'));
      }

      /**
       * Activate the plugin
       */
      public static function activate() {
         add_option('url_rotator_manager_map', array());
      }

      /**
       * Deactivate the plugin
       */
      public static function deactivate() {
         // Do nothing
      }

      /**
       * hook into WP's admin_init action hook
       */
      public function admin_init() {
         // Set up the settings for this plugin
         // register the settings for this plugin
         register_setting('url_rotator_manager_option', 'url_rotator_manager_map');

         /*
          * DELETE
          */
         if (isset($_POST['url_rotator_manager_delete'])) {
            $map = get_option('url_rotator_manager_map', array());

            foreach ($map as $key => $value) {
               if ($value['name'] == $_POST['url_rotator_manager_delete']) {
                  unset($map[$key]);
               }
            }

            update_option('url_rotator_manager_map', $map);
         }

         /*
          * DELETE URL
          */
         if (isset($_POST['url_rotator_manager_delete_url'])) {
            $map = get_option('url_rotator_manager_map', array());

            foreach ($map as $key => $value) {
               if ($value['name'] == $_POST['url_rotator_manager_name']) {
                  $url_key = $_POST['url_rotator_manager_key'];
                  unset($map[$key]['link'][$url_key]);

                  foreach ($map[$key]['link'] as $url_key => $link) {
                     $map[$key]['link'][$url_key]['click'] = 0;
                     $map[$key]['link'][$url_key]['next'] = 0;
                  }
                  
                  sort($map[$key]['link']);

               }
            }
            update_option('url_rotator_manager_map', $map);
         }

         /*
          * RESET
          */
         if (isset($_POST['url_rotator_manager_reset_submit'])) {
            $map = get_option('url_rotator_manager_map', array());

            foreach ($map as $key => $value) {
               if ($value['name'] == $_POST['url_rotator_manager_name']) {
                  foreach ($value['link'] as $url_key => $link) {
                     $link['click'] = 0;

                     $map[$key]['link'][$url_key] = $link;
                  }
               }
            }

            update_option('url_rotator_manager_map', $map);
         }

         /*
          * NEW INBOUND
          */
         if (isset($_POST['url_rotator_manager_name']) and $_POST['url_rotator_manager_name'] != '') {

            $name = sanitize_title($_POST['url_rotator_manager_name']);
            $save = TRUE;

            $map = get_option('url_rotator_manager_map', array());

            foreach ($map as $key => $value) {
               if ($value['name'] == $name) {
                  $save = FALSE;
               }
            }

            if ($save) {
               $map = array_reverse($map);
               
               array_push($map, array(
                   'name' => $name,
                   'link' => array()
               ));
               
               $map = array_reverse($map);
            }

            update_option('url_rotator_manager_map', $map);
         }

         /*
          * NEW OR UPDATE URL
          */
         if (isset($_POST['url_rotator_manager_new_url_submit']) and $_POST['url_rotator_manager_new_url_submit'] != '') {

            $name = sanitize_title($_POST['url_rotator_manager_name']);
            $url = esc_url($_POST['url_rotator_manager_url'], 'http');
            $save = TRUE;

            $map = get_option('url_rotator_manager_map', array());

            foreach ($map as $key => $value) {
               if ($value['name'] == $name) {
                  if ($_POST['url_rotator_manager_key'] != '') {
                     $url_key = $_POST['url_rotator_manager_key'];
                     $map[$key]['link'][$url_key] = array(
                         'url' => $url,
                         'click' => 0,
                         'next' => false
                     );
                  } else {
                     $map[$key]['link'][] = array(
                         'url' => $url,
                         'click' => 0,
                         'next' => 0
                     );

                     foreach ($map[$key]['link'] as $url_key => $link) {
                        $map[$key]['link'][$url_key]['click'] = 0;
                        $map[$key]['link'][$url_key]['next'] = 0;
                     }
                  }
               }
            }
            update_option('url_rotator_manager_map', $map);
         }
      }

      /**
       * add a menu
       */
      public function add_menu() {
         add_management_page("URL Rotator Manager", "URL Rotator Manager", "manage_categories", 'wp_url_rotator_manager', array(&$this, 'url_rotator_manager_settings_page'));
      }

      public function url_rotator_manager_settings_page() {
         if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
         }

         $map = get_option('url_rotator_manager_map', array());

         wp_enqueue_script('', plugins_url('js/admin.js', __FILE__), array('jquery', 'jquery-ui-core', 'wp-color-picker'), time(), true);

         // Render the settings template
         include(sprintf("%s/templates/tools.php", dirname(__FILE__)));
      }

      function url_rotator_manager_init() {
         $name = substr($_SERVER["REQUEST_URI"], 1);

         $map = get_option('url_rotator_manager_map', array());

         foreach ($map as $key => $value) {

            if ($value['name'] == $name) {
               $id_link = NULL;

               foreach ($value['link'] as $url_key => $link) {
                  if ($link['next']) {
                     $id_link = $url_key;
                  }
               }

               if (is_null($id_link)) {
                  $id_link = 0;
               }

               $value['link'][$id_link]['click'] ++;
               $url = $value['link'][$id_link]['url'];
               $value['link'][$id_link]['next'] = 0;

               if ((count($value['link']) - 1) > $id_link) {
                  $value['link'][++$id_link]['next'] = 1;
               } else {
                  $value['link'][0]['next'] = 1;
               }

               $map[$key] = $value;

               update_option('url_rotator_manager_map', $map);

               wp_redirect($url);
               exit;
            }
         }
      }

   }

}

if (class_exists('URL_Rotator_manager')) {
   // Installation and uninstallation hooks
   register_activation_hook(__FILE__, array('URL_Rotator_manager', 'activate'));
   register_deactivation_hook(__FILE__, array('URL_Rotator_manager', 'deactivate'));

   // instantiate the plugin class
   $wp_url_rotator_manager = new URL_Rotator_manager();

   if (isset($wp_url_rotator_manager)) {

      // Add the settings link to the plugins page
      function url_rotator_manager_settings_link($links) {
         $settings_link = '<a href="tools.php?page=wp_url_rotator_manager">Settings</a>';
         array_unshift($links, $settings_link);
         return $links;
      }

      $plugin = plugin_basename(__FILE__);
      add_filter("plugin_action_links_$plugin", 'url_rotator_manager_settings_link');
   }
}   