@extends('admin.layout')

@section('content')
<h1>POI list</h1>
<div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">

    <hr class="uk-grid-divider">
    <script>
        function confirm(id) {
            alertify.set({labels: {
                    ok: "Yes",
                    cancel: "No"
                }});
            alertify.set({buttonFocus: "cancel"}); // "none", "ok", "cancel"
            alertify.confirm("Do you really want to change poi status?", function (e) {
                if (e) {
                    document.location.href = "/admin/poi/" + id + "/delete?search_text=<?php echo $search_text; ?>";
                } else {
                }
            });

        }
        // confirm dialog

    </script>
    <div class="uk-grid" data-uk-grid-margin>
        <div class="uk-width-medium-3-3">
            <div class="uk-panel uk-panel-header">
                <!--<h3 class="uk-panel-title">Shop List</h3>-->
                <form action="/admin/poi" method="get">
                <table class="uk-table">
                    <!--<caption>...</caption>-->
                    <p>
                        <!--<a class="uk-float-left" href="/admin/poi/add"><button class="uk-button uk-button-small uk-button-primary">Add New POI</button></a>--> 
                        <div class="uk-float-right">
                            <input class="uk-text-small" placeholder="Search" name="search_text" value="<?php echo $search_text; ?>">
                            <input type="submit" class="uk-button uk-button-small"value="Search">
                        </div>
                    </p>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Keyword</th>
                            <th>Coupon</th>
                            <th>Owner</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $count = 0; foreach ($pois as $poi) { ?>
                            <tr>
                                <td><a href="/admin/poi/<?php echo $poi['_id']; ?>/edit"><?php echo $poi['name'] ?></a></td>

                                <td><?php $cats = $poi['cat']; $str = "N/A"; if(is_array($cats))  {$str = "";  foreach($cats as $k) { $str = $str.$k['main'].", ";} } echo $str;  //echo $poi['keyword']; ?></td>
                                <td><?php if(isset($poi['keyword'])) $keywords = $poi['keyword']; $str = "N/A"; if(isset($keywords))  {$str = "";  foreach($keywords as $k) { $str = $str.$k.", ";} } echo $str;  //echo $poi['keyword']; ?></td>
                                <td><a href="/admin/poi/<?php echo $poi['_id']; ?>/coupon"><?php echo $poi_additional[(string)$poi['_id']]['coupon_count'] ?></a></td>
                                <td><?php echo $poi_additional[(string)$poi['_id']]['client_name'] ?></td>
                                <td><?php if(isset($poi['status'])) echo $poi['status']; else echo "N/A"; ?></td>
                                <td>
                                    <a href="/admin/poi/<?php echo $poi['_id']; ?>/edit"><button type="button" class="uk-button uk-button-mini uk-button-primary">Edit</button></a>
                                    <button type="button" onclick="confirm('<?php echo $poi['_id']; ?>');" class="uk-button uk-button-mini <?php if(isset($poi['status']) && $poi['status'] == "active" ) echo "uk-button-danger"; else echo "uk-button-success"; ?>"><?php if(isset($poi['status']) && $poi['status'] == "active" ) echo "Deactivate"; else echo "Activate"; ?></button>

                                </td>
                            </tr>
                        <?php $count++; } ?>

                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                                <div class="pagination">
                                    <?php ?>
                                    <a href="/admin/poi?page=<?php echo $table_additional['first']; ?>&search_text=<?php echo $search_text; ?>">« First</a>
                                    <a href="/admin/poi?page=<?php echo $table_additional['prev']; ?>&search_text=<?php echo $search_text; ?>">Prev </a>
                                    
                                    <?php
                                    if(isset($table_additional['prev_more'])) {
                                    ?>
                                    
                                    <a href="<?php echo $table_additional['prev_more']; ?>">...</a>
                                    
                                    <?php 
                                    }
                                    
                                    foreach($table_additional['pages_list'] as $key => $pl) {
                                        if( "$key" == "current") {
                                    ?>
                                    
                                    <span class="active"><?php echo $pl; ?></span>
                                    
                                    <?php 
                                        } else {
                                    ?>
                                    
                                    <a href="/admin/poi?page=<?php echo $pl; ?>&search_text=<?php echo $search_text; ?>"><?php echo $pl; ?></a>
                                    
                                    <?php
                                        }
                                    }
                                    ?>
                                    
                                    <?php
                                    if(isset($table_additional['next_more'])) {
                                    ?>
                                    
                                    <a href="/admin/poi?page=<?php echo $table_additional['next_more']; ?>&search_text=<?php echo $search_text; ?>">...</a>
                                    
                                    <?php 
                                    }
                                    ?>
                                    <a href="/admin/poi?page=<?php echo $table_additional['next']; ?>&search_text=<?php echo $search_text; ?>">Next </a>
                                    <a href="/admin/poi?page=<?php echo $table_additional['last']; ?>&search_text=<?php echo $search_text; ?>">Last »</a>
                                </div>
                            </td>
<!--                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>-->
                            <!--<td> <?php echo "Total : "." Record"; ?></td>-->
                        </tr>
                    </tfoot>
                </table>
                </form>

            </div>
        </div>


    </div> 

</div>

@stop
