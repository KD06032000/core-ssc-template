<div class="tab-pane" id="tab_google_analytics">
    <div class="box-body">
        <div class="form-group">
            <label>Tracking Code</label>
            <input name="tracking_code" class="form-control" placeholder="Tracking Code"
                   value="<?php echo !empty($tracking_code) ? $tracking_code : ''; ?>">
        </div>
        <div class="form-group">
            <label>Views ID</label>
            <input name="view_id" class="form-control" placeholder="View ID"
                   value="<?php echo !empty($view_id) ? $view_id : ''; ?>">
        </div>
        <div class="form-group">
            <label>Service Account Credentials</label>
            <textarea name="service_account_credentials" class="form-control" cols="30" rows="5" placeholder="Service Account Credentials"><?php echo (isset($service_account_credentials))? $service_account_credentials:'' ?></textarea>
        </div>
    </div>
</div>