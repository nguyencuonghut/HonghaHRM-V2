<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnnouncementRequest;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnnouncementRequest $request)
    {
        if (Auth::user()->cannot('create', Announcement::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        //Create new Announcement
        $announcement = new Announcement();
        $announcement->recruitment_id = $request->recruitment_id;
        $announcement->status = 'Đã đăng';
        $announcement->save();

        //Create announcement_channel pivot item
        $announcement->channels()->attach($request->channel_id);

        Alert::toast('Thêm kênh đã đăng thành công!', 'success', 'top-right');
        return redirect()->route('recruitments.show', $announcement->recruitment_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcement $announcement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        if (Auth::user()->cannot('update', $announcement)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        // Delete all old announcement_channel pivot items
        $announcement->channels()->detach();

        //Create announcement_channel pivot item
        $announcement->channels()->attach($request->channel_id);

        Alert::toast('Sửa kế kênh đã đăng thành công!', 'success', 'top-right');
        return redirect()->route('recruitments.show', $announcement->recruitment_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        if (Auth::user()->cannot('delete', $announcement)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('recruitments.show', $announcement->recruitment_id);
        }

        $announcement->delete();
        Alert::toast('Xóa kênh đã đăng thành công!', 'success', 'top-right');
        return redirect()->route('recruitments.show', $announcement->recruitment_id);
    }
}
