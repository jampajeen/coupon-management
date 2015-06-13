@extends('shop.layout')

@section('content')

<h1>Your Shop</h1>
<div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">

    <hr class="uk-grid-divider">
    <script>
        function confirm(id) {
            alertify.set({labels: {
                    ok: "Yes",
                    cancel: "No"
                }});
            alertify.set({buttonFocus: "cancel"}); // "none", "ok", "cancel"
            alertify.confirm("Do you really want to delete this shop?", function (e) {
                if (e) {
                    document.location.href = "/shop/" + id + "/delete";
                } else {
                }
            });

        }
        // confirm dialog

    </script>
    <div class="uk-grid" data-uk-grid-margin>
        <div class="uk-width-medium-3-3">
            <div class="uk-panel uk-panel-header">
                <table class="uk-table">
                    <p><a href="/shop/addshop"><button class="uk-button uk-button-small uk-button-primary">Add New Shop</button></a></p>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Keyword</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $count = 0;
                        foreach ($pois as $poi) { ?>
                            <tr>
                                <td> <?php if (isset($poi['name'])) echo "<a href=\"/shop/" . $poi['_id'] . "/edit\">" . $poi['name'] . "</a>"; ?> </td>
                                <td> <?php if (isset($poi['cat'])) {
                                $shop_cat = $poi['cat'];
                                foreach ($shop_cat as $scat) {
                                    echo $scat['main'] . ", ";
                                }
                            } ?></td>
                                <td> <?php if (isset($poi['desc'])) echo $poi['desc']; ?></td>
                                <td> <?php if (isset($poi['keyword'])) {
                                $keywords = $poi['keyword'];
                                foreach ($keywords as $k) {
                                    echo $k . ", ";
                                }
                            } ?></td>
                                <td>

                                    <a href="/shop/<?php echo $poi['_id']; ?>/edit"><button class="uk-button uk-button-mini uk-button-primary">Edit</button></a>
                                    <button type="button" onclick="confirm('<?php echo $poi['_id']; ?>');" class="uk-button uk-button-mini uk-button-danger">Delete</button>
                                </td>
                            </tr>
    <?php $count++;
} ?>

                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Total: <?php echo $count; ?> Shop</td>
                        </tr>
                    </tfoot>

                </table>

            </div>
        </div>


    </div> 

</div>


@stop
