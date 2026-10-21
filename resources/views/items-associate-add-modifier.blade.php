<div class="accordion-item accordion-{{$modifierGroup->id}}">
    <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse1"
            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            <div class="row">
                <div class="col-md-1 d-flex align-items-center justify-content-around text-center">
                    <span class="fs-20" style="color: #0980B2; cursor:grab;">
                            <i class="fas fa-arrows-alt"></i>
                    </span>

                    <input type="number" value="" class="order_index index_input" />
                </div>
                <div class="col-md-1 d-flex align-items-center text-center"><img src="{{asset('assets/images/dustbin.png')}}" class="delete-modifier-group"></div>
                <div class="col-md-2 d-flex align-items-center fs-20 ps-0" data-bs-toggle="collapse"
                data-bs-target="#collapse_{{$modifierGroup->id}}"><strong>{{$modifierGroup->name}} <i class='bx bx-caret-down'></i></strong></div>
                <div class="col-md-1"><input type="number" name="{{$modifierGroup->id}}[free_modifier_count]" class="form-control"/></div>
                <div class="col-md-1">
                    <div class="form-check text-center">
                        <input class="form-check-input lg-checkbox float-none" value="1" name="{{$modifierGroup->id}}[is_multi_select]" type="checkbox" value="" id="multi_select">
                    </div>
                </div>
                <div class="col-md-1 text-center">
                    <div class="form-check">
                        <input class="form-check-input lg-checkbox float-none" value="1" name="{{$modifierGroup->id}}[is_forced_modifier_group]" type="checkbox" value="" id="mod_forced">
                    </div>
                </div>
                <div class="col-md-2"><input type="number" name="{{$modifierGroup->id}}[min_modifier_count]" class="form-control" /></div>
                <div class="col-md-2"><input type="number" name="{{$modifierGroup->id}}[max_modifier_count]" class="form-control" /></div>
                <div class="col-md-1">
                    <div class="form-check text-center">
                        <input class="form-check-input lg-checkbox float-none" value="1" name="{{$modifierGroup->id}}[is_available_online]" type="checkbox"  id="is_available_online" checked>
                    </div>
                </div>
            </div>
        </button>
    </h2>
    <div id="collapse_{{$modifierGroup->id}}" class="accordion-collapse collapse" aria-labelledby="headingOne">
        <div class="accordion-body">
            <div class="table-responsive">
            <table class="table table-bordered mx-auto item_modifier_table" style="font-size: 18px; width: auto; table-layout: auto; border-collapse: collapse; text-align:center;">
                <thead>
                    <tr>
                        <th class="px-3"></th>
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
                        <th class="px-5">
                            <input class="form-check-input lg-checkbox float-none status-select-all" type="checkbox">
                            Status
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
                    @foreach(
                        $modifierGroup->modifiersGroupDetails
                            ->filter(function($mod) {
                                return !is_null($mod->modifier);
                            })
                            ->sortBy(function($mod) {
                                return strtolower($mod->modifier->name);
                            }) as $modifiersGroupDetail
                    )
                        <tr>
                            <td class="px-3">
                                <span style="color: #0980B2; cursor:grab;">
                                        <i class="fas fa-arrows-alt"></i>
                                </span>
                            </td>
                            <td class="px-3"><input type="number" value="{{ $loop->index + 1 }}" class="item_index index_input" /></td>
                            <td class="px-5">{{ $modifiersGroupDetail->modifier->name }}</td>
                            <td class="px-5">
                                <div class="form-check">
                                    <input class="form-check-input lg-checkbox float-none"
                                        name="{{ $modifierGroup->id }}[modifiers][{{ $modifiersGroupDetail->modifier->id }}][status]"
                                        type="checkbox" value="1">
                                </div>
                            </td>
                            <td class="px-5">
                                <input type="text" class="form-control numberInput modifier-price-input"
                                    name="{{ $modifierGroup->id }}[modifiers][{{ $modifiersGroupDetail->modifier->id }}][price]"
                                    style="width: 150px"
                                    value="{{ currencyFormat($modifiersGroupDetail->price, false) }}">
                            </td>

                            <td class="px-5">
                                <input type="text" class="form-control numberInput web_price_input" name="{{ $modifierGroup->id }}[modifiers][{{ $modifiersGroupDetail->modifier->id }}][web_price]" style="width: 100px" value="">
                            </td>
                            <td class="px-5">
                                <input type="text" class="form-control numberInput ubereats_price_input" name="{{ $modifierGroup->id }}[modifiers][{{ $modifiersGroupDetail->modifier->id }}][ubereats_price]" style="width: 100px" value="">
                            </td>
                            <td class="px-5">
                                <input type="text" class="form-control numberInput grubhub_price_input" name="{{ $modifierGroup->id }}[modifiers][{{ $modifiersGroupDetail->modifier->id }}][grubhub_price]" style="width: 100px" value="">
                            </td>
                            <td class="px-5">
                                <input type="text" class="form-control numberInput door_dash_price_input" name="{{ $modifierGroup->id }}[modifiers][{{ $modifiersGroupDetail->modifier->id }}][door_dash_price]" style="width: 100px" value="">
                            </td>

                            <td class="px-5"><input type="text" class="form-control numberInput" name="{{ $modifierGroup->id }}[modifiers][{{ $modifiersGroupDetail->modifier->id }}][min_modifier_count]" style="width: 100px" value=""></td>
                            <td class="px-5"><input type="text" class="form-control numberInput" name="{{ $modifierGroup->id }}[modifiers][{{ $modifiersGroupDetail->modifier->id }}][max_modifier_count]" style="width: 100px" value=""></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>