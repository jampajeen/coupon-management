@extends('coupon.layout')

@section('content')
<h1>Your Coupon</h1>
<div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">

    <hr class="uk-grid-divider">
    <script>
        function confirm(id) {
            alertify.set({labels: {
                    ok: "Yes",
                    cancel: "No"
                }});
            alertify.set({ buttonFocus: "cancel" }); // "none", "ok", "cancel"
            alertify.confirm("Do you really want to delete this coupon?", function(e) {
                if (e) {
                    document.location.href = "/coupon/" + id + "/delete";
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
                <table class="uk-table">
                    <!--<caption>...</caption>-->
                    <p><a href="/coupon/addcoupon"><button class="uk-button uk-button-small uk-button-primary">Create New Coupon</button></a></p>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Shop</th>
                            <th>Price</th>
                            <th>Amount</th>
                            <th>Remaining Amount</th>
                            <th>Begin</th>
                            <th>End</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $count = 0; foreach ($coupons as $coupon) { ?>
                        <tr>
                            <td><a data-uk-modal="{target:'#view_modal-<?php echo $coupon['_id']; ?>'}" target="_blank"><?php echo $coupon['coupon_name']  ?></a></td>
                            <td><a href="/shop/<?php echo $coupon['coupon_poi_id'];  ?>/edit"><?php foreach ($pois as $key => $poi) { if($key == $coupon['coupon_poi_id']) {echo $poi['name'];} } ?></a></td>
                            <td><?php echo $coupon['coupon_price'];  ?></td>
                            <td><?php echo $coupon['coupon_amt'];  ?></td>
                            <td><?php echo $coupon['coupon_amt'];  ?></td>
                            <td><?php echo substr($coupon['coupon_startdate'],0,10); ?></td>
                            <td><?php echo substr($coupon['coupon_enddate'],0,10); ?></td>
                            
                            <td>
                                <a href="#view_modal-<?php echo $coupon['_id']; ?>" data-uk-modal></a>
                                <div id="view_modal-<?php echo $coupon['_id']; ?>" class="uk-modal"> 

                                    <div class="uk-modal-dialog">
                                        <a class="uk-modal-close uk-close"></a>
                                        <iframe width="440" height="235" scrolling="no" src="/coupontemplate/<?php echo $coupon['_id']  ?>.html"> </iframe>
                                    </div>

                                </div>
                                <!--<a data-uk-modal="{target:'#view_modal-<?php //echo $coupon['_id']; ?>'}"><button class="uk-button uk-button-mini uk-button-success">View</button></a>-->
                                <a href="/coupon/<?php echo $coupon['_id']; ?>/edit"><button class="uk-button uk-button-mini uk-button-primary">Edit</button></a>
                                <button type="button" onclick="confirm('<?php echo $coupon['_id']; ?>');" class="uk-button uk-button-mini uk-button-danger">Delete</button>
                                
                            </td>
                        </tr>
                        <?php $count++; } ?>
                         
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Total: <?php echo $count; ?> Coupon</td>
                        </tr>
                    </tfoot>
                    
                </table>

            </div>
        </div>


    </div> 

</div>

@stop
