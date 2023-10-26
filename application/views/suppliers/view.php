<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">

	    <div class="modal-body clearfix">
	    
		    <table class="table table-hover">
		    	<thead>
		    		<tr>
		    			<th colspan="2" style="text-align:center;"><?php echo lang("supplier");?></th>
		    		</tr>
		    	</thead>
		    	<tr>
		    		<td><?php echo lang('id'); ?></td>
		    		<td><?php echo isset($model_info->id)?$model_info->id:"";?></td>
		    	</tr>

		    	<tr>
		    		<td><?php echo lang('company_name'); ?></td>
		    		<td><?php echo isset($model_info->company_name)?$model_info->company_name:"";?></td>
		    	</tr>
		    	
		    	<tr>
		    		<td><?php echo lang("contact")?></td>
		    		<td><?php echo isset($model_info->contact_name)?$model_info->contact_name:"";?></td>
		    	</tr>

		    	<tr>
		    		<td><?php echo lang("phone")?></td>
		    		<td><?php echo isset($model_info->phone)?$model_info->phone:"";?></td>
		    	</tr>

		    	<tr>
		    		<td><?php echo lang("alternative_phone")?></td>
		    		<td><?php echo isset($model_info->alternative_phone)?$model_info->alternative_phone:"";?></td>
		    	</tr>

		    	<tr>
		    		<td><?php echo lang("address")?></td>
		    		<td><?php echo isset($model_info->address)?$model_info->address:"";?></td>
		    	</tr>


		    	<!-- <tr>
		    		<td><?php echo lang("city")?></td>
		    		<td><?php echo isset($model_info->city)?$model_info->city:"";?></td>
		    	</tr>

		    	<tr>
		    		<td><?php echo lang("state")?></td>
		    		<td><?php echo isset($model_info->state)?$model_info->state:"";?></td>
		    	</tr>

		    	<tr>
		    		<td><?php echo lang("zip")?></td>
		    		<td><?php echo isset($model_info->zip)?$model_info->zip:"";?></td>
		    	</tr>

		    	<tr>
		    		<td><?php echo lang("country")?></td>
		    		<td><?php echo isset($model_info->country)?$model_info->country:"";?></td>
		    	</tr>

		    	<tr>
		    		<td><?php echo lang("website")?></td>
		    		<td><?php echo isset($model_info->website)?$model_info->website:"";?></td>
		    	</tr>

		    	<tr>
		    		<td><?php echo lang("currency_symbol")?></td>
		    		<td><?php echo isset($model_info->currency_symbol)?$model_info->currency_symbol:"";?></td>
		    	</tr>

		    	<tr>
		    		<td><?php echo lang("currency")?></td>
		    		<td><?php echo isset($model_info->currency)?$model_info->currency:"";?></td>
		    	</tr>

		    	<tr>
		    		<td><?php echo lang("vat_number")?></td>
		    		<td><?php echo isset($model_info->vat_number)?$model_info->vat_number:"";?></td>
		    	</tr> -->

		    	<tr>
		    		<td><?php echo lang("note")?></td>
		    		<td><?php echo isset($model_info->note)?$model_info->note:"";?></td>
		    	</tr>

		    	<tr>
		    		<td><?php echo lang("created_date")?></td>
		    		<td><?php echo $model_info->created_date;?></td>
		    	</tr>

		    </table>
	    </div>
    </div>
    
</div> 