@extends('layouts.master')
@section('title')
    {{$item->name}} - Add Modifiers
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-heading">
                        <h4>{{$item->name}} - Add Modifiers</h4>
                        <a href="{{ URL::previous() }}">Back</a>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-content p-3 main-content-height">
                        <form id="modifier_add" method="POST" action="{{ route('item-modifiers-add', ['itemId' => $item->id]) }}">
                            <div class="row d-flex justify-content-center">
                                <div class="col-12 col-md-3 col-lg-3">
                                    <select class="form-select" id="item_variant_id" required>
                                        <option value="">Select Variant</option>
                                        @foreach ($item->variants as $variant)
                                            <option value="{{ $variant->id }}">{{ $variant->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-3 col-lg-3">
                                    <select class="form-select" id="modifier_id" required>
                                        <option value="">Select Modifier</option>
                                        @foreach ($modifiers as $modifier)
                                        {{-- @foreach ($item->remainingModifiers() as $modifier) --}}
                                            <option value="{{ $modifier->id }}">{{ $modifier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-3 col-lg-3">
                                    <input type="text" class="form-control numberInput" id="modifier_price"
                                        placeholder="Modifier Price" required step="0.01">
                                </div>
                                <div class="col-12 col-md-3 col-lg-3">
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
                                                <th scope="col">Variant Name</th>
                                                <th scope="col">Modifier Name</th>
                                                <th scope="col">Modifier Price</th>
                                            </tr>
                                        </thead>
                                        <tbody id="modifier_table">
                                            @foreach ($item->itemModifiers as $itemModifier)
                                                <tr class="item_modifier_{{$itemModifier->id}}">
                                                    <td><img src="/assets/images/dustbin.png" alt="delete" role="button" onclick="deleteModifier({{ $itemModifier->id }},this)"></td>
                                                    <td>{{ $itemModifier->itemVariant->full_name }}</td>
                                                    <td>{{ $itemModifier->modifier->name }}</td>
                                                    <td>{{ $itemModifier->price }}</td>
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
    <div class="modal fade" id="deleteModifierModal" tabindex="-1" aria-labelledby="deleteRoleLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoleLabel">Delete Modifier</h5>
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
                    <a href="#" class="btn btn-primary" id="deleteItemModifierBtn">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <!--Modal Popup Edit end-->
@endsection
@section('js')
    <script>
        var itemModifierDeleteUrl = '{{ route('item-modifiers-delete', ['itemId' => $item->id]) }}';
    </script>
    <script>
        $(document).on("submit", "#modifier_add", function (e) {
            e.preventDefault();
            var itemVariantId = $("#item_variant_id").val();
            var modifierId = $("#modifier_id").val();
            var modifierPrice = $("#modifier_price").val();
            var url = this.action;
            
            $.ajax({
                type: 'POST',
                url: url,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                data: {
                    'item_variant_id': itemVariantId,
                    'modifier_id': modifierId,
                    'modifier_price':modifierPrice
                },
                success: function(response) {
                    if (response.status == 'success') {
                        var html = `<tr class="item_modifier_`+response.data.item_modifier.id+`">
                                        <td><img src="/assets/images/dustbin.png" alt="delete" role="button" onclick="deleteModifier(`+response.data.item_modifier.id+`,this)"></td>
                                        <td>`+response.data.item_variant_name+`</td>
                                        <td>`+response.data.modifier_name+`</td>
                                        <td>`+response.data.item_modifier.price+`</td>
                                    </tr>`;

                        $('#modifier_table').append(html);
                        // $('#modifier_id').html(response.data.remaining_modifiers_html);
                        $('#modifier_add')[0].reset();
                        showAlert('success','Modifier assigned successfully');
                    }else{
                        showAlert('error',response.message);
                    }
                }
            });
        });

        function deleteModifier(itemModifierId,This) {
            $("#deleteItemModifierBtn").data('id',itemModifierId);
            $('#deleteModifierModal').modal('show');
            // deleteModifierFinal(itemModifierId,This);
        }

        $(document).on('click',"#deleteItemModifierBtn",function(){
            var itemModifierId = $(this).data('id');
            deleteModifierFinal(itemModifierId);
        });

        function deleteModifierFinal(itemModifierId) {
            $("#deleteItemModifierBtn").addClass("disabled");
            $.ajax({
                type: 'POST',
                url: itemModifierDeleteUrl,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                data: {
                    'item_modifier_id':itemModifierId
                },
                success: function(response) {
                    if (response.status == 'success') {
                        // $('#modifier_id').html(response.data.remaining_modifiers_html);
                        $('#modifier_add')[0].reset();
                        $('.item_modifier_'+itemModifierId).remove();
                        $('#deleteModifierModal').modal('hide');
                        showAlert('success','Modifier deleted successfully');
                    }else{
                        showAlert('error',response.message);
                    }
                    $("#deleteItemModifierBtn").removeClass("disabled");
                }
            });
        }
    </script>
@endsection
