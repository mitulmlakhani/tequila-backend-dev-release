<div class="modal-header w-100 w-lg-80 mx-auto" style="border: 1px solid #d1d1d1;">
    <h5 class="modal-title" id="deleteRoleLabel"><strong class="text-black">{{ $category->category_name }}</strong> - Add/Edit Modifier</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body w-100 w-lg-80 mx-auto" style="border: 1px solid #d1d1d1;">
    <div class="modalcontent">
        <form id="modifier_group_add">
            <div class="row">
                <div class="col-md-10 col-lg-10">
                    <input type="hidden" id="category_id" value="{{$category->id}}">
                    <select class="form-select " id="modifier_group_id" style="font-size: 18px; margin: auto; height: 100%;" required>
                        <option value="">Select Modifier Group</option>
                        @foreach ($modifierGroups as $modifierGroup)
                        <option value="{{ $modifierGroup->id }}">{{ $modifierGroup->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-lg-2">
                    <button type="submit" class="btn w-100 btn-primary ms-2 modifier_group_add">Add</button>
                </div>
            </div>
        </form>
        <div class="row mt-5">
            <div class="col-md-1 text-end"><strong>#</strong></div>
            <div class="col-md-1"><strong>Delete</strong></div>
            <div class="col-md-2"><strong> Modifier Group</strong></div>
            <div class="col-md-2 text-center"><strong>Free Mod Count</strong></div>
            <div class="col-md-1 text-center"><strong>Multi Select</strong></div>
            <div class="col-md-1 text-center"><strong>Forced Mod</strong></div>
            <div class="col-md-2 text-center"><strong>Min Forced Count</strong></div>
            <div class="col-md-2 text-center"><strong>Max Forced Count</strong></div>
        </div>

        <form id="save_associate_modifiers_form" action="{{route('save-category-modifier-group',['categoryId'=>$category->id])}}">
            <div class="accordion" id="accordionExample">

                @php $maxCategoryModifierId = count($categoryModifierIds ?? []) + 1; @endphp
                {{-- {{  dd($categoryModifierIds) }} --}}
                @foreach($categoryModifierGroups->sortBy(function ($cmod) use ($categoryModifierIds, $maxCategoryModifierId) {
                    $s = array_search($cmod->id, $categoryModifierIds);
                    return $s !== false ? $s : $maxCategoryModifierId;
                }) as $categoryModifierGroup)

                @php $categoryModifier = $categoryModifiers[$categoryModifierGroup->id] ?? []; @endphp
                <div class="accordion-item accordion-{{$categoryModifierGroup->id}}">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse1"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"
                            style="{{ $loop->index % 2 == 1 ? 'background-color: #EFEEEE;' : '' }}">
                            <div class="row">
                                <div class="col-md-1 d-flex align-items-center justify-content-around text-center">
                                    <span class="fs-20" style="color: #0980B2; cursor:grab;">
                                        <i class="fas fa-arrows-alt"></i>
                                    </span>
                                    <input type="number" value="{{ $loop->iteration }}"
                                        class="order_index index_input" />
                                </div>
                                <div class="col-md-1 d-flex align-items-center text-center"><img
                                        src="{{asset('assets/images/dustbin.png')}}" class="delete-modifier-group">
                                </div>
                                <div class="col-md-2 d-flex align-items-center fs-20 ps-0" data-bs-toggle="collapse"
                                    data-bs-target="#collapse_{{$categoryModifierGroup->id}}">
                                    <strong>{{$categoryModifierGroup->name}} <i class='bx bx-caret-down'></i></strong>
                                </div>
                                <div class="col-md-2"><input type="number"
                                        name="{{$categoryModifierGroup->id}}[free_modifier_count]" class="form-control"
                                        value="{{$categoryModifier['free_modifier_count'] ?? ''}}" /></div>
                                <div class="col-md-1">
                                    <div class="form-check text-center">
                                        <input class="form-check-input lg-checkbox float-none" value="1"
                                            name="{{$categoryModifierGroup->id}}[is_multi_select]" type="checkbox"
                                            id="multi_select" @if($categoryModifier['is_multi_select'] ?? null) checked
                                            @endif>
                                    </div>
                                </div>
                                <div class="col-md-1 text-center">
                                    <div class="form-check">
                                        <input class="form-check-input lg-checkbox float-none" value="1"
                                            name="{{$categoryModifierGroup->id}}[is_forced_modifier_group]"
                                            type="checkbox" id="mod_forced"
                                            @if($categoryModifier['is_forced_modifier_group'] ?? null) checked @endif>
                                    </div>
                                </div>
                                <div class="col-md-2"><input type="number"
                                        name="{{$categoryModifierGroup->id}}[min_modifier_count]" class="form-control min_modifier_count"
                                        value="{{ $categoryModifier['min_modifier_count'] ?? null }}" /></div>
                                <div class="col-md-2"><input type="number"
                                        name="{{$categoryModifierGroup->id}}[max_modifier_count]" class="form-control max_modifier_count"
                                        value="{{ $categoryModifier['max_modifier_count'] ?? null }}" /></div>
                            </div>
                        </button>
                    </h2>
                    <div id="collapse_{{$categoryModifierGroup->id}}" class="accordion-collapse collapse"
                        aria-labelledby="headingOne">
                        <div class="accordion-body">
                            <p class="text-danger mod_err text-center"></p>
                            <div class="table-responsive">
                            <table class="table table-bordered mx-auto item_modifier_table"
                                style="font-size: 18px; width: auto; table-layout: auto; border-collapse: collapse; text-align:center;">
                                <thead>
                                    <tr>
                                        <th class="px-2"></th>
                                        <th class="px-2">
                                            <span class="sort-header" data-sort="idx" style="cursor:pointer">
                                                # <i class="fas fa-sort ms-1"></i>
                                            </span>
                                        </th>
                                        <th class="px-5">
                                            <span class="sort-header" data-sort="name" style="cursor:pointer">
                                                Modifier Name <i class="fas fa-sort ms-1"></i>
                                            </span>
                                        </th>
                                        <th class="px-5 flex align-items-center justify-content-center">
                                            <input
                                                class="form-check-input lg-checkbox float-none status-select-all me-2"
                                                type="checkbox">
                                            <span>Status</span>
                                        </th>
                                        <th class="px-5">Price</th>

                                        <th class="px-5">Web Price</th>
                                        <th class="px-5">Door Dash Price</th>
                                        <th class="px-5">UberEats Price</th>
                                        <th class="px-5">Grubhub Price</th>

                                        <th class="px-5">Min Forced Count</th>
                                        <th class="px-5">Max Forced Count</th>
                                    </tr>
                                </thead>
                                <tbody class="sortable">

                                    @php 
                                        $categoryModifiersGroupDetailsIds= array_keys($categoryModifier['modifiers'] ?? []);
                                        $maxItemIndex = count(array_values($categoryModifiersGroupDetailsIds)) + 1; 
                                    @endphp
                                    @foreach($categoryModifierGroup->modifiersGroupDetails->filter(function ($mod) {
                                        return !is_null($mod->modifier);
                                    })
                                    ->sortBy(function ($mod) use ($categoryModifiersGroupDetailsIds, $maxItemIndex) {
                                        $s = array_search($mod->modifier_id, $categoryModifiersGroupDetailsIds);

                                        return $s !== false ? $s : $maxItemIndex;
                                    }) as $modifiersGroupDetail)
                                    @php
                                        $categoryModifiersGroupDetail = $categoryModifier['modifiers'][$modifiersGroupDetail->modifier_id] ?? [];
                                    @endphp

                                    <tr>
                                        <td class="px-3">
                                            <span style="color: #0980B2; cursor:grab;">
                                                <i class="fas fa-arrows-alt"></i>
                                            </span>
                                        </td>
                                        <td class="px-3"><input type="number" value="{{ $loop->index + 1 }}"
                                                class="item_index index_input" /></td>
                                        <td class="px-5">{{$modifiersGroupDetail->modifier->name}}</td>
                                        <td class="px-5">
                                            <div class="form-check">
                                                <input class="form-check-input lg-checkbox float-none mod_status"
                                                    name="{{$categoryModifierGroup->id}}[modifiers][{{$modifiersGroupDetail->modifier_id}}][status]"
                                                    type="checkbox" value="1"
                                                    @if($categoryModifiersGroupDetail['status'] ?? null) checked @endif 
                                                >
                                            </div>
                                        </td>
                                        <td class="px-5"><input type="text" class="form-control numberInput"
                                                name="{{$categoryModifierGroup->id}}[modifiers][{{$modifiersGroupDetail->modifier_id}}][price]"
                                                style="width: 100px"
                                                value="{{ $categoryModifiersGroupDetail['price'] ?? null }}"
                                                >
                                        </td>

                                        <td class="px-5"><input type="text" class="form-control numberInput" name="{{$categoryModifierGroup->id}}[modifiers][{{$modifiersGroupDetail->modifier_id}}][web_price]" style="width: 100px" value="{{ $categoryModifiersGroupDetail['web_price'] ?? 0 }}"></td>
                                        <td class="px-5"><input type="text" class="form-control numberInput" name="{{$categoryModifierGroup->id}}[modifiers][{{$modifiersGroupDetail->modifier_id}}][door_dash_price]" style="width: 100px" value="{{ $categoryModifiersGroupDetail['door_dash_price'] ?? 0 }}"></td>
                                        <td class="px-5"><input type="text" class="form-control numberInput" name="{{$categoryModifierGroup->id}}[modifiers][{{$modifiersGroupDetail->modifier_id}}][ubereats_price]" style="width: 100px" value="{{ $categoryModifiersGroupDetail['ubereats_price'] ?? 0 }}"></td>
                                        <td class="px-5"><input type="text" class="form-control numberInput" name="{{$categoryModifierGroup->id}}[modifiers][{{$modifiersGroupDetail->modifier_id}}][grubhub_price]" style="width: 100px" value="{{ $categoryModifiersGroupDetail['grubhub_price'] ?? 0 }}"></td>

                                        <td class="px-5"><input type="text" class="form-control numberInput" value="{{ $categoryModifiersGroupDetail['min_modifier_count'] ?? null }}" name="{{ $categoryModifierGroup->id }}[modifiers][{{ $modifiersGroupDetail->modifier->id }}][min_modifier_count]" style="width: 100px" value=""></td>
                                        <td class="px-5"><input type="text" class="form-control numberInput" value="{{ $categoryModifiersGroupDetail['max_modifier_count'] ?? null }}" name="{{ $categoryModifierGroup->id }}[modifiers][{{ $modifiersGroupDetail->modifier->id }}][max_modifier_count]" style="width: 100px" value=""></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>


                @endforeach



            </div>
        </form>
    </div>
</div>
<div class="modal-footer w-100 w-lg-80 mx-auto" style="border: 1px solid #d1d1d1;">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    <a href="#" class="btn btn-primary" id="save_associate_modifiers_form_submit">Save</a>
</div>