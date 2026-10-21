@extends('layouts.master')
@section('title')
    {{$item->name}} - Add Ingredients
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-heading">
                        <h4>{{$item->name}} - Add Ingredients</h4>
                        <a href="{{ URL::previous() }}">Back</a>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-content p-3 main-content-height">
                        <form id="ingredient_add" method="POST" action="{{ route('item-ingredients-add', ['itemId' => $item->id]) }}">
                            <div class="row d-flex justify-content-center">
                                <!-- <div class="col-12 col-md-3 col-lg-3">
                                    <select class="form-select" id="item_variant_id" required>
                                        <option value="">Select Variant</option>
                                        @foreach ($item->variants as $variant)
                                            <option value="{{ $variant->id }}">{{ $variant->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div> -->
                                <div class="col-12 col-md-3 col-lg-3">
                                    <select class="form-select" id="ingredient_id" required>
                                        <option value="">Select Ingredient</option>
                                        @foreach ($ingredients as $ingredient)
                                            <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- <div class="col-12 col-md-2 col-lg-2">
                                    <select class="form-select" id="ingredient_uom" name="ingredient_uom" required>
                                        <option value="">Select Unit of Measure</option>
                                        @foreach ($unitOfMeasurements as $unitOfMeasurement)
                                            <option value="{{ $unitOfMeasurement->id }}">{{ $unitOfMeasurement->name }}</option>
                                        @endforeach
                                    </select>
                                </div> -->
                                <div class="col-12 col-md-2 col-lg-2">
                                    <input type="text" class="form-control numberInput" id="ingredient_quantity"
                                        placeholder="Ingredient Quantity" required>
                                </div>
                                <div class="col-12 col-md-2 col-lg-2">
                                    <button type="submit" class="btn btn-primary ms-2">Add</button>
                                </div>
                            </div>
                        </form>
                        <div class="row mt-4" id="add_list">
                            <div class="col-12 col-md-12 col-lg-12 mt-3">
                                <div class="table_scrol">
                                    <table class="table table-bordered table-decor">
                                        <thead>
                                            <tr>
                                                <th scope="col">Delete</th>
                                                <th scope="col">Ingredient Name</th>
                                                <th scope="col">Ingredient Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody id="ingredient_table">
                                            @foreach ($item->itemIngredients as $itemIngredient)
                                                <tr class="item_ingredient_{{$itemIngredient->id}}">
                                                    <td><img src="/assets/images/dustbin.png" alt="delete" role="button" onclick="deleteIngredient({{ $itemIngredient->id }},this)"></td>
                                                    <td>{{ $itemIngredient->ingredient->name }}</td>
                                                    <td>{{ $itemIngredient->quantity }} {{ $itemIngredient->ingredient->unitOfMeasurement->name }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Main Section End-->
    <!--Modal Popup delete start-->
    <div class="modal fade" id="deleteIngredientModal" tabindex="-1" aria-labelledby="deleteRoleLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoleLabel">Delete Ingredient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete-img">
                        <img src="{{asset('assets/images/delete.png')}}" alt="delete" class="img-fluid">
                    </div>
                    <div class="modalcontent">
                        <h4 class="text-center mt-3">Are you Sure?</h4>
                        <p class="text-center mt-3">Do you really want to delete these records? This process
                            cannot be undone.</p>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-primary" id="deleteItemIngredientBtn">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <!--Modal Popup Edit end-->
@endsection
@section('js')
    <script>
        var itemIngredientDeleteUrl = '{{ route('item-ingredients-delete', ['itemId' => $item->id]) }}';
    </script>
    <script>
        $(document).on("submit", "#ingredient_add", function (e) {
            e.preventDefault();
            // var itemVariantId = $("#item_variant_id").val();
            var ingredientId = $("#ingredient_id").val();
            var ingredientUom = $("#ingredient_uom").val();
            var ingredientQuantity = $("#ingredient_quantity").val();
            var url = this.action;
            
            $.ajax({
                type: 'POST',
                url: url,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                data: {
                    // 'item_variant_id': itemVariantId,
                    'ingredient_id': ingredientId,
                    'ingredient_uom': ingredientUom,
                    'ingredient_quantity':ingredientQuantity
                },
                success: function(response) {
                    if (response.status == 'success') {
                        var rowClass = "item_ingredient_" + response.data.item_ingredient.id;
                        var html = `<tr class="item_ingredient_`+response.data.item_ingredient.id+`">
                                        <td><img src="/assets/images/dustbin.png" alt="delete" role="button" onclick="deleteIngredient(`+response.data.item_ingredient.id+`,this)"></td>
                                        <td>`+response.data.ingredient_name+`</td>
                                        <td>`+response.data.item_ingredient.quantity+` `+response.data.uom_name+`</td>
                                    </tr>`;

                        // $('#ingredient_table').append(html);
                        if ($('.' + rowClass).length > 0) {
                            // If it exists, replace it
                            $('.' + rowClass).replaceWith(html);
                        } else {
                            // If it does not exist, append it
                            $('#ingredient_table').append(html);
                        }
                        // $('#ingredient_id').html(response.data.remaining_ingredients_html);
                        $('#ingredient_add')[0].reset();
                        showAlert('success','Ingredient assigned successfully');
                    }else{
                        showAlert('error',response.message);
                    }
                }
            });
        });

        function deleteIngredient(itemIngredientId,This) {
            $("#deleteItemIngredientBtn").data('id',itemIngredientId);
            $('#deleteIngredientModal').modal('show');
            // deleteIngredientFinal(itemIngredientId,This);
        }

        $(document).on('click',"#deleteItemIngredientBtn",function(){
            var itemIngredientId = $(this).data('id');
            deleteIngredientFinal(itemIngredientId);
        });

        function deleteIngredientFinal(itemIngredientId){
            $("#deleteItemIngredientBtn").addClass("disabled");
            $.ajax({
                type: 'POST',
                url: itemIngredientDeleteUrl,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                data: {
                    'item_ingredient_id':itemIngredientId
                },
                success: function(response) {
                    if (response.status == 'success') {
                        // $('#ingredient_id').html(response.data.remaining_ingredients_html);
                        $('#ingredient_add')[0].reset();
                        $('.item_ingredient_'+itemIngredientId).remove();
                        $('#deleteIngredientModal').modal('hide');
                        showAlert('success','Ingredient deleted successfully');
                    }else{
                        showAlert('error',response.message);
                    }
                    $("#deleteItemIngredientBtn").removeClass("disabled");
                }
            });
        }
    </script>
@endsection
