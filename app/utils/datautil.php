<?php
namespace App\utils;

class datautil {
    
    /*
     * Return array of pagination > first, last, step more, current page,
     * 
     */
    public static function makePagination($total_documents, $docs_per_page, $current_page ) {
        /*
         * Pagination
         */
        $page = $current_page;
        $first = 1;
        $step_more = 5;
        $last =(int)($total_documents/$docs_per_page) + 1; // page count
        $table_additional = array();
        
        /*
         * First
         */
        $table_additional['first'] = $first;
        /*
         * Prev
         */
        if($page > $first) {
            $table_additional['prev'] = $page - 1;
        } else {
            $table_additional['prev'] = $first;
        }
        /*
         * Prev more
         */
        if( $page - $step_more > 0 ) {
            $table_additional['prev_more'] = $page - $step_more;
        }
        /*
         * Page list
         */
        $table_additional['pages_list'] = array();
        
        $from = $first;
        $to = $last;
        if($page > 3) {
            $from = $page - 1;
            $to = $page + 1;
        }
        
        if($last - $page > $step_more) {
            $to = $page + 2;
        }
        
        for($i = $from ; $i <= $to ; $i++ ) {
            if($page == $i) {
                $table_additional['pages_list']['current'] = $page;
            } else {
                $table_additional['pages_list'][] = $i;
            }
        }
        
        
        /*
         * Next more
         */
        if($page + $step_more < $total_documents) {
            $table_additional['next_more'] = $page + $step_more;
        }
        /*
         * Next
         */
        if($page < $last) {
            $table_additional['next'] = $page + 1;
        } else {
            $table_additional['next'] = $last;
        }
        /*
         * Last
         */
        $table_additional['last'] = $last;
        
        return $table_additional;
    }
    
}