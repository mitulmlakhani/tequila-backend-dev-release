@extends('layouts.master')

@section('content')

<!--Main Section Start-->
<div class="wrapper home-section" id="full-width">
  <div class="container-fluid p-4">
    <div class="row">
      <div class="col-12 col-md-12 col-lg-12">
        <div class="main-heading">
          <h4>{{$panel}} Management</h4>
          @can($permission_prefix.'-create')
          <a href="{{ route($base_route.'.create') }}" data-bs-toggle="modal" data-bs-target="#addCategory"
            data-bs-whatever="@mdo">Add {{$panel}}</a>
          @endcan
        </div>
      </div>
      @include('layouts.flash-msg')
      @include('layouts.validation-error-msg')
      <div class="col-12 col-md-12 col-lg-12">
        <div class="main-content p-3">
          <table id="category_management" class="display nowrap w-100">
            <thead>
              <tr>
                <th scope="rowgroup"></th>
                <th scope="rowgroup">Parent Category</th>
                <th scope="rowgroup">Category</th>
                <th scope="rowgroup">Description</th>
                <th scope="rowgroup">Image/Icon</th>
                <th scope="rowgroup">Created by</th>
                <!-- <th scope="rowgroup">Updated By</th> -->
                <th scope="rowgroup">Status</th>
                <th scope="rowgroup">Action</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($category as $key => $value)

                <tr>
                    <td>{{ ++$i }}</td>
                    <td>
                       @if(isset($value->parentcategory) && count($value->parentcategory)>0)
                       @foreach($value->parentcategory as $key_parent=>$value_parent)
                       {{$value_parent->category_name}}
                       @endforeach

                       @else
                       -
                       @endif
                    </td>
                     <td>
                      {{ $value->category_name }}
                    </td>
                    <td>{{ $value->category_desc }}</td>
                    <td><a href="{{asset('images/menucat/'.$value->category_icon)}}" target="_blank"><img src="{{asset('images/menucat/'.$value->category_icon)}}"class="w-25"></a></td>
                    <td>{{ GetUserById($value->created_by) }}</td>
                    <!-- <td>{{ GetUserById($value->updated_by) }}</td> -->
                    <td>
                      <div class="{{ $value->status ? 'reserved' : 'pending' }}">
                        <img src="{{ $value->status ? 'assets/images/reserved.png' : 'assets/images/pending.png' }}"
                            class="me-2" alt="{{ $value->status ? 'reserved' : 'pending' }}">
                        <span>{{ $value->status ? 'Active' : 'In-active' }}</span>
                      </div>
                    </td>
                   
                    <td>
                        <span class="me-2">
                            @can($permission_prefix.'-edit')
                                <a aria-hidden="true" href="#" id="categoryedit" data-bs-toggle="modal"edit-atr="{{$value->id}}" data-bs-target="#editCategory"
                          data-bs-whatever="@mdo">
                                    <img src="{{asset('assets/images/edit.png')}}" alt="edit">
                                </a>
                             @endcan
                        </span>
                        <span>
                            @can($permission_prefix.'-delete')
                            {!! Form::open(['method' => 'DELETE','route' => [$base_route.'.destroy', $value->id],'style'=>'display:inline']) !!}
                                <a aria-hidden="true" id="" class="confirm-button">
                                 <img src="{{asset('assets/images/dustbin.png')}}" alt="dustbin">
                                </a>
                            {!! Form::close() !!}
                            @endcan
                        </span>
                    </td>
                </tr>
              @endforeach

            </tbody>
          </table>

        </div>
      </div>
    </div>
  </div>
</div>
<!--Main Section End-->
<!--Modal Popup Add start-->
<div class="modal fade" id="addCategory" tabindex="-1" aria-labelledby="addCategoryLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCategoryLabel">Add {{$panel}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      {!! Form::open(array('route' => $base_route.'.store','method'=>'POST','enctype'=>'multipart/form-data')) !!}
      <div class="modal-body">
        <div class="row">
            <div class="mb-3 col-12 col-md-6 col-lg-6">
                <label  class="form-label" for="add_parent_category">Parent Category </label>
                {!! Form::select('parent_id',$parent_cat,null, array('class' => 'form-control','id'=>'add_parent_category')) !!}
               
                @error('parent_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

            </div>
            <div class="mb-3 col-12 col-md-6 col-lg-6">
                <label  class="form-label" for="add_category_name">Category Name</label>
                {!! Form::text('category_name',null, array('class' => 'form-control','id'=>'add_category_name','required','placeholder'=>'Category Name')) !!}

                @error('category_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3 col-12 col-md-6 col-lg-6">
                <label  class="form-label" for="add_category_icon">Category Image/Icon </label>
                 {!! Form::file('category_icons', array('class' => 'form-control uploadimage','id'=>'add_category_icon inputGroupFile04','required','aria-describedby'=>'inputGroupFileAddon04','aria-label'=>'Upload')) !!}
                @error('category_icon')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 col-12 col-md-6 col-lg-6">
                <label class="form-label" for="add_status">Status</label>

                  {{ Form::select('status',[1=>'Active',0=>'In-active'], null, array('class' =>'form-select','id'=>'add_status')) }}
                @error('status')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
               
            </div>
             <div class="mb-3 col-12 col-md-12 col-lg-12">
                <label  class="form-label" for="add_description">Category description </label>
                {!! Form::textarea('category_desc', null, array('placeholder' => 'Category Description','class' => 'form-control','id'=>'add_description','rows'=>'3')) !!}
                @error('category_desc')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
      </div>
      <div class="modal-footer ">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="Submit" class="btn btn-primary">Add</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
<!--Modal Popup Add end-->

<!--Modal Popup Edit start-->
<div class="modal fade" id="editCategory" tabindex="-1" aria-labelledby="editCategoryLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCategoryLabel">Edit {{$panel}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      {!! Form::model($category, ['method' => 'PATCH','enctype'=>'multipart/form-data','route' => [$base_route.'.update',isset($category->id)?$category->id:0 ]]) !!}
      <div class="modal-body">
        <div class="row">
            <div class="mb-3 col-12 col-md-6 col-lg-6">
                <label  class="form-label" for="edit_parent_category">Parent Category </label>
                {!! Form::select('parent_id',$parent_cat,null, array('class' => 'form-control','id'=>'edit_parent_category','required')) !!}
                 {!!Form::hidden('category_id','',['id'=>'edit_category_id'])!!}
                @error('parent_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 col-12 col-md-6 col-lg-6">
                <label  class="form-label" for="edit_category_name">Category Name</label>
                {!! Form::text('category_name',null, array('class' => 'form-control','id'=>'edit_category_name','required')) !!}
                @error('category_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3 col-12 col-md-6 col-lg-6">
                <label  class="form-label" for="edit_category_icon">Category Image/Icon </label>
                 {!! Form::file('category_icons', array('class' => 'form-control uploadimage','id'=>'edit_category_icon inputGroupFile04','aria-describedby'=>'inputGroupFileAddon04','aria-label'=>'Upload')) !!}
                 <img src=""class="w-25" id="edit_category_icon">
                @error('category_icon')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 col-12 col-md-6 col-lg-6">
                <label class="form-label" for="edit_status">Status</label>
                  {{ Form::select('status',[1=>'Active',0=>'In-active'], null, array('class' =>'form-select','id'=>'edit_status')) }}
                @error('status')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
               
            </div>
             <div class="mb-3 col-12 col-md-12 col-lg-12">
                <label  class="form-label" for="edit_description">Category description </label>
                {!! Form::textarea('category_desc', null, array('placeholder' => 'Category Description','class' => 'form-control','id'=>'edit_description','rows'=>'3')) !!}
                @error('category_desc')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
      </div>
      <div class="modal-footer ">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
<!--Modal Popup Edit end-->



@endsection
@section('js')
<script type="text/javascript">
   function formatPhone(obj) {
    var numbers = obj.value.replace(/\D/g, ''),
        char = {0:'(',3:') ',6:'-'};
    obj.value = '';
    for (var i = 0; i < numbers.length; i++) {
        obj.value += (char[i]||'') + numbers[i];
    }

}
     
$(".uploadimage").change(function(e) {
        var a=(this.files[0].size);
        if(a > 5000000) {
            alert('File Size must be <=5MB');
            $(this).val('');
        };
    });
</script>
@endsection