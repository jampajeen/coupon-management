@extends('admin.layout')

@section('content')
<h1>Users list</h1>
<div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">

    <hr class="uk-grid-divider">
    <script>
        function confirm(id) {
            alertify.set({labels: {
                    ok: "Yes",
                    cancel: "No"
                }});
            alertify.set({buttonFocus: "cancel"}); // "none", "ok", "cancel"
            alertify.confirm("Do you really want to delete this User?", function (e) {
                if (e) {
                    document.location.href = "/admin/users/" + id + "/delete";
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
                <form action="/admin/users" method="get">
                <table class="uk-table">
                    <!--<caption>...</caption>-->
                    <p>
                        <!--<a class="uk-float-left" href="/admin/users/add"><button class="uk-button uk-button-small uk-button-primary">Add New User</button></a>--> 
                        <div class="uk-float-right">
                            <input class="uk-text-small" placeholder="Search" name="search_text" value="<?php echo $search_text; ?>">
                            <input type="submit" class="uk-button uk-button-small"value="Search">
                        </div>
                    </p>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Updated</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $count = 0; foreach ($users as $user) { ?>
                            <tr>
                                <td><a href="/admin/users/<?php echo $user['_id']; ?>/edit"><?php echo $user['first_name']." ".$user['last_name'] ?></a></td>

                                <td><a href="/admin/users/<?php echo $user['_id']; ?>/edit"><?php echo $user['email'] ?></a></td>
                                <td><?php echo $user['updated_time']; ?></td>
                                
                                <td>
                                    <a href="/admin/users/<?php echo $user['_id']; ?>/edit"><button type="button" class="uk-button uk-button-mini uk-button-primary">Edit</button></a>
                                    <button type="button" onclick="confirm('<?php echo $user['_id']; ?>');" class="uk-button uk-button-mini uk-button-danger">Deactivate</button>

                                </td>
                            </tr>
                        <?php $count++; } ?>

                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                                <div class="pagination">
                                    <?php ?>
                                    <a href="/admin/users?page=<?php echo $table_additional['first']; ?>&search_text=<?php echo $search_text; ?>">« First</a>
                                    <a href="/admin/users?page=<?php echo $table_additional['prev']; ?>&search_text=<?php echo $search_text; ?>">Prev </a>
                                    
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
                                    
                                    <a href="/admin/users?page=<?php echo $pl; ?>&search_text=<?php echo $search_text; ?>"><?php echo $pl; ?></a>
                                    
                                    <?php
                                        }
                                    }
                                    ?>
                                    
                                    <?php
                                    if(isset($table_additional['next_more'])) {
                                    ?>
                                    
                                    <a href="/admin/users?page=<?php echo $table_additional['next_more']; ?>&search_text=<?php echo $search_text; ?>">...</a>
                                    
                                    <?php 
                                    }
                                    ?>
                                    <a href="/admin/users?page=<?php echo $table_additional['next']; ?>&search_text=<?php echo $search_text; ?>">Next </a>
                                    <a href="/admin/users?page=<?php echo $table_additional['last']; ?>&search_text=<?php echo $search_text; ?>">Last »</a>
                                </div>
                            </td>
                            <td></td>
                            <td></td>
                            <td> <?php echo "Total : ".$count." Record"; ?></td>
                        </tr>
                    </tfoot>
                </table>
                </form>

            </div>
        </div>


    </div> 

</div>

@stop
