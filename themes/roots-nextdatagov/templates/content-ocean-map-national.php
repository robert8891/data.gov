<?php
$category = get_the_category();
$term_name = $category[0]->cat_name;
$term_slug = $category[0]->slug;
?>
<?php
$cat_name = $category[0]->cat_name;
$cat_slug = $category[0]->slug;
?>
<div class="subnav banner">
    <div class="container">
        <nav role="navigation" class="topic-subnav">
            <ul class="nav navbar-nav">
                <?php
                // show Links associated to a community
                // we need to build $args based either term_name or term_slug
                $args = array(
                    'category_name'=> $term_slug, 'categorize'=>0, 'title_li'=>0,'orderby'=>'rating');
                wp_list_bookmarks($args);
                if (strcasecmp($term_name,$term_slug)!=0) {
                    $args = array(
                        'category_name'=> $term_name, 'categorize'=>0, 'title_li'=>0,'orderby'=>'rating');
                    wp_list_bookmarks($args);
                }
                ?>
            </ul></nav></div>
</div>
<div class="container">
<?php
                    while( have_posts() ) {
                        the_post();
                        ?>
                        <br/>

      <?php the_title();?>

    <?php the_content();   ?>
    <?php }?>
    <br/><br/>
<div class="map-gallery-wrap">
                            <?php
                            global $map_results;
                            $mapinfo = array();
                            $groupinfo = array();
                            $groupmapinfo = array();
                            for($i=0;$i<count($map_results);$i++){
                               if($map_results[$i]["info"]["type"]=="Map"){
                                   $mapinfo[$i] = array_merge($map_results[$i]["map_info"][0]);
                               }
                               if($map_results[$i]["info"]["type"]=="Group"){
                                   $groupinfo[$i] = array_merge($map_results[$i]["map_info"]);
                               }
                            }
                            for($j=0;$j<count($groupinfo);$j++){
                               unset($groupinfo[$j]["total_maps"]);
                              $groupmapinfo[] = array_merge($groupinfo[$j]);
                            }
                            $group = array();
                            foreach ($groupmapinfo as $array) {
                                $group = array_merge($group, $array);
                            }
                            $merged_maps_tosort = array_merge($mapinfo,$group);
                            $merged_maps = subval_sort($merged_maps_tosort,"title");
                            $total_maps = count($merged_maps);
                            //code for pagination
                            $mapsperpage = (get_option('arcgis_maps_per_page') != '') ? get_option('arcgis_maps_per_page') : '8';
                            if(isset($map_results)) {
                                $total_pages = ceil($total_maps / $mapsperpage);
                            } else {
                                $total_pages = 1;
                                $total_maps = 0;
                            }
                            if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
                                $currentpage = (int) $_GET['currentpage'];
                            } else {
                                $currentpage = 1;
                            }
                            if ($currentpage > $total_pages) {
                                $currentpage  = $total_pages;
                            }
                            if ($currentpage < 1) {
                                $currentpage = 1;
                            }
                            $start = ($currentpage - 1) * $mapsperpage + 1;
                            $count =0;
                            $output = "";
                            for($i=$start-1; $i<$start-1+$mapsperpage; $i++){
                            if(isset($merged_maps[$i])) {
                                $output .= '<div class="map-align">';
                                $output .= '<a target=_blank href="'. $merged_maps[$i]["img_href"] . '">';
                                $output .= '<img class="map-gallery-thumbnail" src="'. $merged_maps[$i]["img_src"] . '" title="' . $merged_maps[$i]["title"] .'" height="133" width="200">';

                                $output .= '<div class="map-gallery-caption">'. $merged_maps[$i]["title"] . '</div>';
                                $output .= '</a>';
                                $output .= '</div>';
                            }
                                $count ++;
                            }
                        $output .= "</div><div class='pagination' style='display:block;clear:both;'>";
                        $output .= "<p class='counter'>";
                        $output .= "Page $currentpage of $total_pages";
                        $output .= "</p>
                        <ul class='pagination'>";
                        if($total_maps > $mapsperpage) {
                            $range = 10;
                            if ($currentpage > 1) {
                                $output .= "<li class='pagination-prev'><a class='prev page-numbers pagenav local-link' href='?currentpage=$prevpage'>Previous</a> </li>";
                                // $output .= "<br clear='both'/><li class='pager-first first'><a href='?currentpage=1'><<< FIRST </a></li> ";
                                $prevpage = $currentpage - 1;
                                //$output .= "<li class='pager-previous'><a href='?currentpage=$prevpage'>< PREVIOUS  </a> </li>";
                            }
                            for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
                                if (($x > 0) && ($x <= $total_pages)) {
                                    if ($x == $currentpage) {
                                        /* if ($currentpage == 1) {
                                             $output .="<br clear='both'/><br/>";
                                         }*/
                                        if ($total_pages > 1) {
                                            $output .= "<li><span class='page-numbers pagenav current'> $x </span></li>";
                                        }
                                    }
                                    else {
                                        $output .= "<li><a class='page-numbers pagenav' href='?currentpage=$x'> $x </a></li>";
                                    }
                                }
                            }
                            if ($currentpage != $total_pages) {
                                $nextpage = $currentpage + 1;
                                $output .= " <li class='pagination-next'> <a href='?currentpage=$nextpage'> Next</a></li> ";
                                //$output .= " <li class='pager-last last'><a href='?currentpage=$total_pages'>  LAST >>></a> </li>";
                            }
                        }
                        print $output;
                        ?>
                    </div>
                    </div>