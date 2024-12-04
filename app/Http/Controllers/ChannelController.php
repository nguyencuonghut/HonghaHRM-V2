<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Models\AnnouncementChannel;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->cannot('viewAny', Channel::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('home');
        }

        return view('channel.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', Channel::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('channels.index');
        }

        return view('channel.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChannelRequest $request)
    {
        $channel = new Channel();
        $channel->name = $request->name;
        $channel->save();

        Alert::toast('Thêm cách phương tiện thành công!', 'success', 'top-right');
        return redirect()->route('channels.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Channel $channel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Channel $channel)
    {
        if (Auth::user()->cannot('update', $channel)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('channels.index');
        }

        return view('channel.edit', ['channel' => $channel]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChannelRequest $request, Channel $channel)
    {
        $channel->update(['name' => $request->name]);

        Alert::toast('Sửa phương tiện thành công!', 'success', 'top-right');
        return redirect()->route('channels.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Channel $channel)
    {
        if (Auth::user()->cannot('delete', $channel)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('channels.index');
        }

        //Check if Channel is used or not
        if ($channel->announcements()->count()) {
            Alert::toast('Phương tiện đang được sử dụng. Không thể xóa!', 'error', 'top-right');
            return redirect()->route('channels.index');
        }
        $channel->delete();

        Alert::toast('Xóa phương tiện thành công!', 'success', 'top-rigth');
        return redirect()->route('channels.index');
    }

    public function anyData()
    {
        $data = Channel::orderBy('id', 'desc');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row) {
                return $row->name;
            })
            ->addColumn('actions', function($row){
                $action = '<a href="' . route("channels.edit", $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                <form style="display:inline" action="'. route("channels.destroy", $row->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
