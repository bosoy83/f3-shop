<div class="row">
    <div class="col-md-2">
        
        <h3>Publication</h3>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10 padding-10">

        <div class="form-group">
        
            <div class="col-md-4 padding-10">
                <select name="publication_status" class="form-control">
                    <option value="published" <?php if ($flash->old('publication_status') == 'published') { echo "selected='selected'"; } ?>>Published</option>
                    <option value="unpublished" <?php if ($flash->old('publication_status') == 'unpublished') { echo "selected='selected'"; } ?>>Unpublished</option>
                </select> 
            </div>
            
            // TODO include date range inputs for when this coupon is active/inactive
        </div>
        <!-- /.form-group -->

    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->