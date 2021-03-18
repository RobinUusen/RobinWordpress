<?php


namespace Falang\Filter\Site;


use RankMath\Paper\Paper;
use RankMath\Post;

class RankMath{

    /**
     * Constructor
     *
     * @since 1.3.8
     *
     */
    public function __construct( ) {

        add_filter( 'rank_math/frontend/title', array( $this, 'rank_math_title' ), 10);

    }

    public function rank_math_title($title){
        if (Falang()->is_default() ) return $title;
        global $post;

        //home static posts page
        if (is_home()  && !Post::is_simple_page() ){
            return $title;
        }

        //manage title for all products pages
        //on all product pages the global post is the first product
        if (Post::is_shop_page()){
            $id = Post::get_shop_page_id();
            $shop_page = get_post($id);
            $post = $shop_page;
        }

        if (isset($post) && !empty($post->ID) ){
            $title = Falang()->translate_post_title($title,$post->ID);
            //set the title to the translate post
            $post->post_title = $title;
            if (isset($post->post_type)){
                $title =  Paper::get_from_options( "pt_{$post->post_type}_title", $post, '%title% %sep% %sitename%' );
            }

        }

        return $title;
    }

}