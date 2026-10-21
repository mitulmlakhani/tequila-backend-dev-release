<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="{{ $module }}_rows" class="form-label">Number of Rows</label>
            <input type="number" class="form-control" id="{{ $module }}_rows" name="{{ $module }}_rows" value="{{ $settings["{$module}_rows"] ?? 4 }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="{{ $module }}_columns" class="form-label">Number of Columns</label>
            <input type="number" class="form-control" id="{{ $module }}_columns" name="{{ $module }}_columns" value="{{ $settings["{$module}_columns"] ?? 5 }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="{{ $module }}_button_height" class="form-label">Button Height</label>
            <input type="number" class="form-control" id="{{ $module }}_button_height" name="{{ $module }}_button_height" value="{{ $settings["{$module}_button_height"] ?? 50 }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="{{ $module }}_button_width" class="form-label">Button Width</label>
            <input type="number" class="form-control" id="{{ $module }}_button_width" name="{{ $module }}_button_width" value="{{ $settings["{$module}_button_width"] ?? 150 }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="{{ $module }}_font_name" class="form-label">Font Name</label>
            <select class="form-control" id="{{ $module }}_font_name" name="{{ $module }}_font_name">
                <option value="Arial" {{ ($settings["{$module}_font_name"] ?? 'Arial') == 'Arial' ? 'selected' : '' }}>Arial</option>
                <option value="Times New Roman" {{ ($settings["{$module}_font_name"] ?? '') == 'Times New Roman' ? 'selected' : '' }}>Times New Roman</option>
                <option value="Courier New" {{ ($settings["{$module}_font_name"] ?? '') == 'Courier New' ? 'selected' : '' }}>Courier New</option>
                <option value="Verdana" {{ ($settings["{$module}_font_name"] ?? '') == 'Verdana' ? 'selected' : '' }}>Verdana</option>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="{{ $module }}_font_size" class="form-label">Font Size</label>
            <input type="number" class="form-control" id="{{ $module }}_font_size" name="{{ $module }}_font_size" value="{{ $settings["{$module}_font_size"] ?? 12 }}">
        </div>
    </div>
</div>
