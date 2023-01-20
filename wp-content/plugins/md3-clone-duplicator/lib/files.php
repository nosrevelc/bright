<?php

if (!class_exists('MUCD_Files')) {

    class MUCD_Files
    {

        /**
         * Copy files from one site to another
         * @since 0.2.0
         * @param  int $from_site_id duplicated site id
         * @param  int $to_site_id   new site id
         */
        public static function copy_files($from_site_id, $to_site_id)
        {
            // Switch to Source site and get uploads info
            switch_to_blog($from_site_id);
            $wp_upload_info = wp_upload_dir();
            $from_dir['path'] = str_replace(' ', "\\ ", trailingslashit($wp_upload_info['basedir']));
            $from_site_id == MUCD_PRIMARY_SITE_ID ? $from_dir['exclude'] = MUCD_Option::get_primary_dir_exclude() : $from_dir['exclude'] = array();

            //$excluded_files = MUCD_Files::get_excluded_files(array('listing'));
            $included_files = MUCD_Files::get_included_files()['paths'];

            // Switch to Destination site and get uploads info
            switch_to_blog($to_site_id);
            $wp_upload_info = wp_upload_dir();
            $to_dir = str_replace(' ', "\\ ", trailingslashit($wp_upload_info['basedir']));

            restore_current_blog();

            $dirs = array();
            $dirs[] = array(
                'from_dir_path' => $from_dir['path'],
                'to_dir_path'   => $to_dir,
                'exclude_dirs'  => $from_dir['exclude'],
            );

            $dirs = apply_filters('mucd_copy_dirs', $dirs, $from_site_id, $to_site_id);

            foreach ($dirs as $dir) {
                if (isset($dir['to_dir_path']) && !MUCD_Files::init_dir($dir['to_dir_path'])) {
                    MUCD_Files::mkdir_error($dir['to_dir_path']);
                }
                MUCD_Duplicate::write_log('Copy files from ' . $dir['from_dir_path'] . ' to ' . $dir['to_dir_path']);
                MUCD_Files::recurse_copy($dir['from_dir_path'], $dir['to_dir_path'], $dir['exclude_dirs'], $included_files);
            }

            return true;
        }

        public static function get_included_files()
        {
            $posts = get_posts(array(
                'numberposts' => -1,
                'post_type' => array('page', 'product'),
                'post_status' => 'any',
                'lang' => ''
            ));
    
            $custom_logo_id = get_theme_mod('custom_logo');
            $media = $custom_logo_id ? array(get_post(($custom_logo_id))) : array();
            $paths = array();
            $files = array();
    
            foreach ($posts as $post) {
                $attachments = get_posts(array('post_type' => 'attachment', 'numberposts' => -1, 'post_status' => null, 'post_parent' => $post->ID, 'lang' => ''));
                $media = array_merge($media,$attachments);
            }
    
            if ($media) {
                foreach ($media as $attachment) {
                    $files['ids'][] = $attachment->ID;
                    $files['guids'][] = $attachment->guid;
                    $paths = array_merge($paths, MUCD_Files::get_attachment_files($attachment->ID));
                }
            }
    
            $files['paths'] = array_unique($paths);
    
            return $files;
        }

        public static function get_excluded_files($post_types = array())
        {
            $posts = get_posts(array(
                'numberposts' => -1,
                'post_type' => $post_types,
                'post_status' => 'any'
            ));

            $files = array();

            foreach ($posts as $post) {
                $media = get_attached_media('', $post);
                foreach ($media as $attachment) {
                    $files = array_merge($files, MUCD_Files::get_attachment_files($attachment->ID));
                }
            }

            return array_unique($files);
        }

        public static function get_attachment_files($attachment_id)
        {
            $files = array();
            $file = get_attached_file($attachment_id);
            $files[] = $file;
            $meta = wp_get_attachment_metadata($attachment_id);
            $uploadpath = wp_get_upload_dir();

            if (isset($meta['sizes']) && is_array($meta['sizes'])) {
                foreach ($meta['sizes'] as $sizeinfo) {
                    $intermediate_file = str_replace(wp_basename($file), $sizeinfo['file'], $file);

                    if (!empty($intermediate_file)) {
                        $intermediate_file = path_join($uploadpath['basedir'], $intermediate_file);
                        $files[] = $intermediate_file;
                    }
                }
            }

            return $files;
        }

        /**
         * Copy files from one directory to another
         * @since 0.2.0
         * @param  string $src source directory path
         * @param  string $dst destination directory path
         * @param  array  $exclude_dirs directories to ignore
         */
        public static function recurse_copy($src, $dst, $exclude_dirs = array(), $included_files = array())
        {
            $src = rtrim($src, '/');
            $dst = rtrim($dst, '/');
            $dir = opendir($src);
            @mkdir($dst);
            while (false !== ($file = readdir($dir))) {
                if (($file != '.') && ($file != '..')) {

                    if (is_dir($src . '/' . $file)) {

                        if (!in_array($file, $exclude_dirs)) {
                            MUCD_Files::recurse_copy($src . '/' . $file, $dst . '/' . $file, array(), $included_files);
                        }
                    } else {

                        if (in_array($src . '/' . $file, $included_files)) {
                            copy($src . '/' . $file, $dst . '/' . $file);
                        }
                    }
                }
            }
            closedir($dir);
        }

        /**
         * Set a directory writable, creates it if not exists, or return false
         * @since 0.2.0
         * @param  string $path the path
         * @return boolean True on success, False on failure
         */
        public static function init_dir($path)
        {
            $e = error_reporting(0);

            if (!file_exists($path)) {
                return mkdir($path, 0777);
            } else if (is_dir($path)) {
                if (!is_writable($path)) {
                    return chmod($path, 0777);
                }
                return true;
            }

            error_reporting($e);
            return false;
        }

        /**
         * Removes a directory and all its content
         * @since 0.2.0
         * @param  string $dir the path
         */
        public static function rrmdir($dir)
        {
            if (is_dir($dir)) {
                $objects = scandir($dir);
                foreach ($objects as $object) {
                    if ($object != "." && $object != "..") {
                        if (filetype($dir . "/" . $object) == "dir") self::rrmdir($dir . "/" . $object);
                        else unlink($dir . "/" . $object);
                    }
                }
                reset($objects);
                rmdir($dir);
            }
        }

        /**
         * Stop process on Creating dir Error, print and log error, removes the new blog
         * @since 0.2.0
         * @param  string  $dir_path the path
         */
        public static function mkdir_error($dir_path)
        {
            $error_1 = 'ERROR DURING FILE COPY : CANNOT CREATE ' . $dir_path;
            MUCD_Duplicate::write_log($error_1);
            $error_2 = sprintf(MUCD_NETWORK_PAGE_DUPLICATE_COPY_FILE_ERROR, MUCD_Functions::get_primary_upload_dir());
            MUCD_Duplicate::write_log($error_2);
            MUCD_Duplicate::write_log('Duplication interrupted on FILE COPY ERROR');
            echo '<br />Duplication failed :<br /><br />' . $error_1 . '<br /><br />' . $error_2 . '<br /><br />';
            if ($log_url = MUCD_Duplicate::log_url()) {
                echo '<a href="' . $log_url . '">' . MUCD_NETWORK_PAGE_DUPLICATE_VIEW_LOG . '</a>';
            }
            MUCD_Functions::remove_blog(self::$to_site_id);
            wp_die();
        }
    }
}
