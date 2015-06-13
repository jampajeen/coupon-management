@extends('admin.layout')

@section('content')
<h1>Ad New Category</h1>

<div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">

    <hr class="uk-grid-divider">

    <div class="uk-grid" data-uk-grid-margin>

        <div class="uk-width-medium-2-3">
            <div class="uk-panel uk-panel-header">

                <!--<h3 class="uk-panel-title">Edit Profile</h3>-->

                <form id="addcat-form" method="post" action="/admin/categories/addcat" class="uk-form uk-form-stacked">

                    <div class="uk-form-row">
                        <script>
                                    function increase(id) {
                                        var val = parseInt(document.getElementById(id).value);
                                        document.getElementById(id).value = val + 1;
                                        console.log(document.getElementById(id).value);
                                    }
                                    
                                    function addElement(parentId, elementTag, elementId, html) {
                                        // Adds an element to the document
                                        var p = document.getElementById(parentId);
                                        var newElement = document.createElement(elementTag);
                                        newElement.setAttribute('id', elementId);
                                        newElement.setAttribute('class', 'uk-grid uk-form-row');
                                        newElement.setAttribute('data-uk-grid-margin','');
                                        newElement.innerHTML = html;
                                        p.appendChild(newElement);
                                        
                                    }

                                    function removeElement(elementId) {
                                        // Removes an element from the document
                                        var element = document.getElementById(elementId);
                                        element.parentNode.removeChild(element);
                                        
                                    }
                                    
                                    var catMainId = 0;
                                    function addCatMain() {
                                        catMainId++;
                                        var html = 
//                                                '<div class="uk-grid" data-uk-grid-margin>' +
//                                                    '<div class="uk-width-2-5">' +
//                                                            '<input class="uk-width-1-1" type="text" name="cat_main_'+catMainId+'" id="cat_main_'+catMainId+'" value="">' +
//                                                        '</div>' +
                                                        '<div class="uk-width-2-5">' +
                                                         '<input class="uk-width-1-1" style="visibility: hidden;">' +
                                                         '</div>' +

                                                        '<div class="uk-width-2-5">' +
                                                            '<input class="uk-width-1-1" type="text" placeholder="Sub category" name="cat_sub_'+catMainId+'" id="cat_sub_'+catMainId+'" value="">' +
                                                        '</div>' +
                                                        
                                                        '<div class="uk-width-1-5">' +
                                                        '<a onclick="javascript:removeElement(\'cat_row_' + catMainId + '\'); return false;">Remove</a>';
                                                        '</div>' ;
                                                    
//                                                    '</div>' +
                                                    
                                            
                                            addElement('cat_main_container', 'div', 'cat_row_' + catMainId, html);
                                            increase("cats_max");
                                    }
                                    
                                        // ไปทำให้เหมือนกันทุกไฟล์
                                    $(function() {
                                        
                                    });
                                    
                                </script>
                    </div>
                    
                        <div class="uk-form-row">
                            <label class="uk-form-label"> Categories </label>
                            <div class="uk-form-controls">
                                <div id="cat_main_container" class="uk-form-row">
                                    <div id="cat_row_0" class="uk-grid uk-form-row" data-uk-grid-margin>
                                        <div class="uk-width-2-5">
                                            <input class="uk-width-1-1" type="text" placeholder="Main category" name="cat_main_0" id="cat_main_0" value="">
                                        </div>
                                        <div class="uk-width-2-5">
                                            <input class="uk-width-1-1" type="text" placeholder="Sub category" name="cat_sub_0" id="cat_sub_0" value="">
                                        </div>
                                        
                                        <div class="uk-width-1-5" style="visibility: hidden;">
                                            <a onclick="javascript:removeElement('cat_row_0'); return false;">Remove</a>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="cats_max" id="cats_max" value="0">
                                <br>
                                <div class="uk-grid uk-form-row" data-uk-grid-margin>
                                    <div class="uk-width-2-5">
                                        <input class="uk-width-1-1" style="visibility: hidden;">
                                    </div>
                                    <div class="uk-width-2-5">
                                        <p><a onclick="addCatMain();">Add More Sub Category... </a></p>
                                    </div>
                                    
                                </div>
                            </div>
                            
                        </div>
                    
                    
                    <div class="uk-form-row">
                        <label class="uk-form-label">Image URL</label>
                        <div class="uk-form-controls">
                            <input type="text" placeholder="http://" class="uk-width-4-5" name="img" value="">
                        </div>
                    </div>

                    <div class="uk-form-row">
                        <div class="uk-form-controls">
                            <input type="submit" class="uk-button uk-button-primary" value="Save"/>
                        </div>
                    </div>

                </form>

            </div>
        </div>


    </div>

</div>

@stop
