@extends('admin.layout')

@section('content')
<h1>Categories list</h1>
<div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">

    <hr class="uk-grid-divider">
    <script>
        function confirm(id) {
            alertify.set({labels: {
                    ok: "Yes",
                    cancel: "No"
                }});
            alertify.set({buttonFocus: "cancel"}); // "none", "ok", "cancel"
            alertify.confirm("Do you really want to delete this category?", function (e) {
                if (e) {
                    document.location.href = "/admin/categories/" + id + "/delete";
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
                <form action="/admin/categories" method="get">
                <table class="uk-table">
                    <!--<caption>...</caption>-->
                    <p>
                        <a class="uk-float-left" href="/admin/categories/addcat"><button type="button" class="uk-button uk-button-small uk-button-primary">Add New Category</button></a> 
                        <div class="uk-float-right">
                            <input class="uk-text-small" placeholder="Search" name="search_text" value="<?php echo $search_text; ?>">
                            <input type="submit" class="uk-button uk-button-small"value="Search">
                        </div>
                    </p>
                    <thead>
                        <tr>
                            <th>Name (TH)</th>
                            <th>Name (EN)</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category) { ?>
                            <tr>
                                <td><a href="/admin/categories/<?php echo $category['_id']; ?>/edit"><?php echo $category['main']; ?></a></td>

                                <td><a href="/admin/categories/<?php echo $category['_id']; ?>/edit"><?php echo $category['main']; ?></a></td>
                                <td><a href="<?php echo $category['img'] ?>"><?php echo $category['img']; ?></a></td>
                                <td><?php echo $category['status']; ?></td>
                                <td>
                                    <a href="/admin/categories/<?php echo $category['_id']; ?>/edit"><button type="button" class="uk-button uk-button-mini uk-button-primary">Edit</button></a>
                                    <button type="button" onclick="confirm('<?php echo $category['_id']; ?>');" class="uk-button uk-button-mini <?php if(isset($category['status']) && $category['status'] == "active" ) echo "uk-button-danger"; else echo "uk-button-success"; ?>"><?php if(isset($category['status']) && $category['status'] == "active" ) echo "Deactivate"; else echo "Activate"; ?></button>


                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                                <div class="pagination">
                                    <?php ?>
                                    <a href="/admin/categories?page=<?php echo $table_additional['first']; ?>&search_text=<?php echo $search_text; ?>">« First</a>
                                    <a href="/admin/categories?page=<?php echo $table_additional['prev']; ?>&search_text=<?php echo $search_text; ?>">Prev </a>
                                    
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
                                    
                                    <a href="/admin/categories?page=<?php echo $pl; ?>&search_text=<?php echo $search_text; ?>"><?php echo $pl; ?></a>
                                    
                                    <?php
                                        }
                                    }
                                    ?>
                                    
                                    <?php
                                    if(isset($table_additional['next_more'])) {
                                    ?>
                                    
                                    <a href="/admin/categories?page=<?php echo $table_additional['next_more']; ?>&search_text=<?php echo $search_text; ?>">...</a>
                                    
                                    <?php 
                                    }
                                    ?>
                                    <a href="/admin/categories?page=<?php echo $table_additional['next']; ?>&search_text=<?php echo $search_text; ?>">Next </a>
                                    <a href="/admin/categories?page=<?php echo $table_additional['last']; ?>&search_text=<?php echo $search_text; ?>">Last »</a>
                                </div>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td> <?php echo "Total : "."10"." Record"; ?></td>
                        </tr>
                    </tfoot>
                </table>
                </form>

            </div>
        </div>


    </div> 

</div>

@stop
