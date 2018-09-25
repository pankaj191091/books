<?php
class books
{
    static $initiated;
    public static function init()
    {
        if (!self::$initiated) {
            self::init_hooks();
            self::codex_Books_init();
            self::create_author_hierarchical_taxonomy();
        }
    }
    public static function init_hooks()
    {
        self::$initiated = true;
    }
    /**
     * Create a custom book post type
     */
    public static function codex_Books_init()
    {
        $labels = array(
            'name' => _x('Books', 'post type general name', 'your-plugin-textdomain'),
            'singular_name' => _x('Books', 'post type singular name', 'your-plugin-textdomain'),
            'menu_name' => _x('Books', 'admin menu', 'your-plugin-textdomain'),
            'name_admin_bar' => _x('Books', 'add new on admin bar', 'your-plugin-textdomain'),
            'add_new' => _x('Add New', 'Books', 'your-plugin-textdomain'),
            'add_new_item' => __('Add New Books', 'your-plugin-textdomain'),
            'new_item' => __('New Books', 'your-plugin-textdomain'),
            'edit_item' => __('Edit Books', 'your-plugin-textdomain'),
            'view_item' => __('View Books', 'your-plugin-textdomain'),
            'all_items' => __('All Books', 'your-plugin-textdomain'),
            'search_items' => __('Search Books', 'your-plugin-textdomain'),
            'parent_item_colon' => __('Parent Books:', 'your-plugin-textdomain'),
            'not_found' => __('No Books found.', 'your-plugin-textdomain'),
            'not_found_in_trash' => __('No Books found in Trash.', 'your-plugin-textdomain')
        );
        
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'Books'
            ),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array(
                'title',
                'editor',
                'author',
                'thumbnail',
                'excerpt',
                'comments'
            )
        );
        register_post_type('Books', $args);
    }
    /**
     * Register meta box
     */
    public static function add_books_meta_boxes()
    {
        add_meta_box("book_detail_meta", "Book Information", array(
            "books",
            "add_books_details_meta_box"
        ), "books", "normal", "high");
    }
    /**
     * View custom meta box under books post type
     */
    public static function add_books_details_meta_box()
    {
        global $post;
        $custom = get_post_custom($post->ID);
        
?>
           <style>.width99 {width:99%;}</style>
            <!--p>
                    <label>Book Author:</label><br />
                    <input type="text" name="book_author" value="<?= @$custom["book_author"][0] ?>" class="width99" />
            </p-->
            <p>
                    <label>ISBN:</label><br />
                    <input type="text" name="isbn" value="<?= @$custom["isbn"][0] ?>" class="width99" />
            </p>
            <p>
                    <label>Amazon Link:</label><br />
                    <input type="text" name="amazon_link" value="<?= @$custom["amazon_link"][0] ?>" class="width99" />
            </p>
            <?php
    }
    /**
     * Save custom field data when creating/updating books post
     */
    public static function save_books_custom_fields()
    {
        global $post;
        
        if ($post) {
            //update_post_meta($post->ID, "book_author", @$_POST["book_author"]);
            update_post_meta($post->ID, "isbn", @$_POST["isbn"]);
            update_post_meta($post->ID, "amazon_link", @$_POST["amazon_link"]);
        }
    }
    
    /**
     * Create Author Taxonomy For Books Post Type
     */
    public static function create_author_hierarchical_taxonomy()
    {
        $labels = array(
            'name' => _x('Authors', 'taxonomy general name'),
            'singular_name' => _x('Author', 'taxonomy singular name'),
            'search_items' => __('Search Authors'),
            'all_items' => __('All Authors'),
            'parent_item' => __('Parent Author'),
            'parent_item_colon' => __('Parent Author:'),
            'edit_item' => __('Edit Author'),
            'update_item' => __('Update Author'),
            'add_new_item' => __('Add New Author'),
            'new_item_name' => __('New Author Name'),
            'menu_name' => __('Authors')
        );
        
        // Now register the taxonomy
        
        register_taxonomy('authors', array(
            'books'
        ), array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'authors'
            )
        ));
        
        self::books_taxonomy_dummy_data();
    }
    /**
     * Authors Dummy Entries
     */
    public static function books_taxonomy_dummy_data()
    {
        $authors = array(
            "John",
            "Michel",
            "Stephen",
            "Mark"
        );
        foreach ($authors as $author) {
            if (!term_exists($author, 'authors')) {
                wp_insert_term($author, // the term 
                    'authors', // the taxonomy
                    array(
                    'description' => $author,
                    'slug' => $author
                ));
            }
        }
        self::books_dummy_data();
    }
    /**
     * Books Dummy Entries
     */
    public static function books_dummy_data()
    {
        $books_name = array(
            'Robot',
            'Strangers',
            'Time Machine'
        );
        $content    = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book";
        $excerpt    = "Lorem Ipsum is simply dummy text of the printing and typesetting industry.";
        $termid     = get_term_by('name', 'john', 'authors');
        foreach ($books_name as $name) {
            if (!get_page_by_title($name, '', 'books')) {
                // Create post object
                $my_post = array(
                    'post_title' => wp_strip_all_tags($name),
                    'post_content' => $content,
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_excerpt' => $excerpt,
                    'post_category' => array(
                        $termid->term_id
                    ),
                    'post_type' => 'books'
                );
                // Insert the post into the database
                $postid  = wp_insert_post($my_post);
                update_post_meta($postid, 'isbn', 2000);
                update_post_meta($postid, 'amazon_link', 'https://www.amazon.com/');
            }
        }
    }
    /**
     * Custom Single Page Template For All Books
     */
    public static function get_custom_post_type_template()
    {
        global $post;
        if ($post->post_type == 'books') {
            $single_template = books_plugin_Path . 'template/single.php';
        }
        return $single_template;
    }
    /**
     * Activation Hook
     */
    public static function plugin_activation()
    {
    }
    /**
     * Deactivation Hook
     */
    public static function plugin_deactivation()
    {
    }
}
?>