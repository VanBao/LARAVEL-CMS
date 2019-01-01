<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default grid">
		  <div class="panel-heading">
		    <button {!!linkAdd('file_data','parent',$menuPage->id)!!} data-name="none" data-type="listImg">
		      <i class="fa fa-plus"></i> Thêm danh mục hình ảnh
		    </button>
		    <button class="btn btn-success selectAll" data-target=".table > tbody > tr" type="button"><i class="fa fa-check-square-o"></i> Chọn tất cả</button> 
		    <button class="btn btn-danger delAll" data-table="file_data"  data-target=".table >tbody > tr.selected" type="button"><i class="fa fa-trash"></i> Xóa đã chọn</button>
		  </div>
		  <table class="table" id="tableMenu">
		    <thead>
		      <tr>
		        <th>Tiêu đề</th>
		        <th>Tên định dạng ($list->)</th>
		        <th width="50px">Xóa</th>
		      </tr>
		    </thead>
		    <tbody align="center">
		    	@foreach($listData as $data)
		     	<tr data-id="{{$data->id}}">
		     		<td>
		     		<input type="text" value="{{$data->title}}" name="listRow[file_data][{{$data->id}}][title]" class="form-control" id="f{{$data->id}}">
		     		</td>
		     		<td>
		     			<input type="text" value="{{$data->name}}" name="listRow[file_data][{{$data->id}}][name]" class="form-control" id="f{{$data->id}}">
		     		</td>
		     		<td>
						<button {!!linkDelId($data->id, 'file_data')!!}>
							<i class="fa fa-trash"></i>
						</button>
		     		</td>
		     	</tr>
		     	@endforeach
		    </tbody>
		  </table>
		</div>
	</div>

	<div class="col-md-6">
		<label>Custom Html</label>
		<div id="customHtml" class="codeEditor" style="height:650px;">{{$configMenu->customHtml}}</div>
		<input for="customHtml" type="hidden" name="listRow[file][{{$configMenu->id}}][customHtml]" value="{{$configMenu->customHtml}}">
	</div>
</div>

