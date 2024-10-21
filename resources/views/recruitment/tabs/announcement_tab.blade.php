<!-- Announcement Tab -->
<div class="tab-pane fade" id="recruitment-3" role="tabpanel" aria-labelledby="recruitment-tab-3">
    <div class="card">
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-12 mb-4">
                    @can('create', App\Models\Announcement::class)
                        <button type="button" class="btn btn-success float-left" data-toggle="modal" data-target="#create_announcement">
                            Tạo
                        </button>
                    @endcan
                </div>
            </div>
            @if ($recruitment->announcement)

            <div class="row">
                <div class="col-12">
                <table id="announcements-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Phương tiện</th>
                            <th>Trạng thái</th>
                            @if (Auth::user()->can('create', App\Models\Announcement::class)
                                || Auth::user()->can('update', $recruitment->announcement)
                                || Auth::user()->can('delete', $recruitment->announcement))
                            <th>Thao tác</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                @php
                                    $i = 0;
                                    $length = count($recruitment->announcement->channels);
                                    $channels_list = '';
                                    foreach ($recruitment->announcement->channels as $item) {
                                        if(++$i === $length) {
                                            $channels_list =  $channels_list . $item->name;
                                        } else {
                                            $channels_list = $channels_list . $item->name . ', ';
                                        }
                                    }
                                @endphp
                                {!! $channels_list !!}
                            </td>
                            <td>{{$recruitment->announcement->status}}</td>
                            @if (Auth::user()->can('create', App\Models\Announcement::class)
                                || Auth::user()->can('update', $recruitment->announcement)
                                || Auth::user()->can('delete', $recruitment->announcement))
                            <td>
                                @php
                                $update = '';
                                $delete = '';
                                if (Auth::user()->can('update', $recruitment->announcement)) {
                                    $update = '<a href="#edit{{' . $recruitment->announcement->id . '}}" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#edit-announcement-' . $recruitment->announcement->id. '"><i class="fas fa-edit"></i></a>';
                                }
                                if (Auth::user()->can('delete', $recruitment->announcement)) {
                                    $delete = '<form style="display:inline" action="'. route("announcements.destroy", $recruitment->announcement->id) . '" method="POST">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                            <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                                }
                                $action = $update . $delete;
                                @endphp
                                {!! $action !!}
                            </td>
                            @endif
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>


<!-- Modals for create announcement -->
<form class="form-horizontal" method="post" action="{{ route('announcements.store') }}" name="make_announcement" id="make_announcement" novalidate="novalidate">
    {{ csrf_field() }}
    <div class="modal fade" id="create_announcement">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Tạo kênh đã đăng tin</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="recruitment_id" id="recruitment_id" value="{{$recruitment->id}}">
                    <div class="row">
                        <div class="col-12">
                            <div class="control-group">
                                <label class="required-field" class="control-label">Phương tiện</label>
                                <div class="controls">
                                    <select name="channel_id[]" id="channel_id[]" data-placeholder="Chọn" class="form-control select2" multiple="multiple" style="width: 100%;">
                                        @foreach($channels as $channel)
                                            <option value="{{$channel->id}}">{{$channel->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
</form>


<!-- Modals for edit announcement -->
@if ($recruitment->announcement)
<form class="form-horizontal" method="post" action="{{ route('announcements.update', $recruitment->announcement->id) }}" name="edit_announcement" id="edit_announcement" novalidate="novalidate">
    {{ csrf_field() }}
    @method('PATCH')
    <div class="modal fade" id="edit-announcement-{{$recruitment->announcement->id}}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Sửa kế hoạch</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="recruitment_id" id="recruitment_id" value="{{$recruitment->id}}">
                    <div class="row">
                        <div class="col-12">
                            <div class="control-group">
                                <label class="required-field" class="control-label">Phương tiện</label>
                                <div class="controls">
                                    <select name="channel_id[]" id="channel_id[]" data-placeholder="Chọn" class="form-control select2" multiple="multiple" style="width: 100%;">
                                        @php
                                            $selected_channels = App\Models\AnnouncementChannel::where('announcement_id', $recruitment->announcement->id)->pluck('channel_id')->toArray();
                                        @endphp
                                        @foreach($channels as $channel)
                                            <option value="{{$channel->id}}" @if(in_array($channel->id, $selected_channels)) selected="selected" @endif>{{$channel->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
</form>
@endif

@push('scripts')
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4'
        })
    })
</script>
@endpush
