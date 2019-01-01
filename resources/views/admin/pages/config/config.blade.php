<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default grid">
		  <table class="table notSl" id="tableMenu">
		    <thead>
		      <tr>
		        <th>Mô tả</th>
		        <th>($config->*)</th>
		        <th width="100px">Hiển thị</th>
		      </tr>
		    </thead>
		    <tbody align="center">
		    	@foreach($listData as $data)
		    	@if($data->type !== 'codeEditor')
		     	<tr data-id="{{$data->id}}" align="center">
		     		<td>
		     			<label class="control-label" for="f{{$data->id}}">{{$data->title}}:</label>
		     		</td>
		     		<td>
		     			{{$data->name}}
		     		</td>
		     		<td>
		     			@switch ($data->type) 
							@case('text')
					  			<input type="text" value="{{$data->value}}" name="listRow[config][{{$data->id}}][value]" class="form-control" id="f{{$data->id}}">
								@break
							@case('number')
								<input type="number" value="{{$data->value}}" name="listRow[config][{{$data->id}}][value]" class="form-control" id="f{{$data->id}}">
								@break;
							@default
								<div class="onoffswitch">
									<input type="hidden" name="listRow[config][{{$data->id}}][value]" value="0" />
									<input type="checkbox"  name="listRow[config][{{$data->id}}][value]" class="onoffswitch-checkbox" id="f<?=$data->id ?>" value="1" />
									<label class="onoffswitch-label" for="f{{$data->id}}"></label>
								</div>
								@break
						@endswitch
		     		</td>
		     	</tr>
		     	@endif
		     	@endforeach
		    </tbody>
		  </table>
		</div>
	</div>
	<div class="col-md-6">
		@foreach($listEditor as $data)
	    @if($data->des !== '' )<label>{{$data->des}}</label>@endif
		<div id="{{$data->name}}" class="codeEditor">{!!$data->value!!}</div>
		<input for="{{$data->name}}" type="hidden" name="listRow[config][{{$data->id}}][value]" />
		@endforeach
	</div>
</div>
