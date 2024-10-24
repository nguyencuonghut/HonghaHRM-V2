<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidateRequest;
use App\Models\Candidate;
use App\Models\CandidateSchool;
use App\Models\Commune;
use App\Models\Degree;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('candidate.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', Candidate::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('candidates.index');
        }

        $communes = Commune::orderBy('name', 'asc')->get();
        $schools = School::orderBy('name', 'asc')->get();
        $degrees = Degree::orderBy('id', 'asc')->get();

        return view('candidate.create',[
            'communes' => $communes,
            'schools' => $schools,
            'degrees' => $degrees,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCandidateRequest $request)
    {
        $candidate = new Candidate();
        $candidate->name = $request->name;
        if ($request->email) {
            $candidate->email = $request->email;
        }
        $candidate->phone = $request->phone;
        if ($request->relative_phone) {
            $candidate->relative_phone = $request->relative_phone;
        }
        $candidate->date_of_birth = Carbon::createFromFormat('d/m/Y', $request->date_of_birth);
        if ($request->cccd) {
            $candidate->cccd = $request->cccd;
        }
        if ($request->issued_date) {
            $candidate->issued_date = Carbon::createFromFormat('d/m/Y', $request->issued_date);
        }
        if ($request->issued_by) {
            $candidate->issued_by = $request->issued_by;
        }
        $candidate->gender = $request->gender;
        $candidate->address = $request->address;
        $candidate->commune_id = $request->commune_id;
        $candidate->experience = $request->experience;
        if ($request->note) {
            $candidate->issued_by = $request->note;
        }
        $candidate->creator_id = Auth::user()->id;
        $candidate->save();

        // Create CandidateSchool
        foreach ($request->addmore as $item) {
            $candidate_school = new CandidateSchool();
            $candidate_school->candidate_id = $candidate->id;
            $candidate_school->degree_id = $item['degree_id'];
            $candidate_school->school_id = $item['school_id'];
            if ($item['major']) {
                $candidate_school->major = $item['major'];
            }
            $candidate_school->save();
        }

        Alert::toast('Thêm ứng viên mới thành công!', 'success', 'top-right');
        return redirect()->route('candidates.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Candidate $candidate)
    {
        return view('candidate.show', ['candidate' => $candidate]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Candidate $candidate)
    {
        if (Auth::user()->cannot('update', $candidate)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('candidates.index');
        }

        $communes = Commune::orderBy('name', 'asc')->get();
        $schools = School::orderBy('name', 'asc')->get();
        $degrees = Degree::orderBy('id', 'asc')->get();

        return view('candidate.edit',[
            'candidate' => $candidate,
            'communes' => $communes,
            'schools' => $schools,
            'degrees' => $degrees,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Candidate $candidate)
    {
        $candidate->name = $request->name;
        if ($request->email) {
            $candidate->email = $request->email;
        }
        $candidate->phone = $request->phone;
        if ($request->relative_phone) {
            $candidate->relative_phone = $request->relative_phone;
        }
        $candidate->date_of_birth = Carbon::createFromFormat('d/m/Y', $request->date_of_birth);
        if ($request->cccd) {
            $candidate->cccd = $request->cccd;
        }
        if ($request->issued_date) {
            $candidate->issued_date = Carbon::createFromFormat('d/m/Y', $request->issued_date);
        }
        if ($request->issued_by) {
            $candidate->issued_by = $request->issued_by;
        }
        $candidate->gender = $request->gender;
        $candidate->address = $request->address;
        $candidate->commune_id = $request->commune_id;
        $candidate->experience = $request->experience;
        if ($request->note) {
            $candidate->issued_by = $request->note;
        }
        $candidate->creator_id = Auth::user()->id;
        $candidate->save();

        //Delete all old CandidateSchool
        $candidate->schools()->detach();

        // Create CandidateSchool
        foreach ($request->addmore as $item) {
            //dd($item['major']);
            $candidate_school = new CandidateSchool();
            $candidate_school->candidate_id = $candidate->id;
            $candidate_school->degree_id = $item['degree_id'];
            $candidate_school->school_id = $item['school_id'];
            if ($item['major']) {
                $candidate_school->major = $item['major'];
            }
            $candidate_school->save();
        }

        Alert::toast('Sửa ứng viên thành công!', 'success', 'top-right');
        return redirect()->route('candidates.index', $candidate->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidate $candidate)
    {
        if (Auth::user()->cannot('delete', $candidate)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('candidates.index');
        }

        //TODO: heck if Candidate is used or not
        $candidate->delete();

        Alert::toast('Xóa ứng viên thành công!', 'success', 'top-rigth');
        return redirect()->route('candidates.index');
    }


    public function anyData()
    {
        $data = Candidate::with(['commune'])->orderBy('name', 'desc')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($data) {
                return '<a href="'.route('candidates.show', $data->id).'">'.$data->name.'</a>';
            })
            ->editColumn('email', function ($data) {
                return $data->email;
            })
            ->editColumn('phone', function ($data) {
                return $data->phone;
            })
            ->editColumn('addr', function ($data) {
                return $data->address . ', ' .  $data->commune->name .', ' .  $data->commune->district->name .', ' . $data->commune->district->province->name;
            })
            ->editColumn('cccd', function ($data) {
                return $data->cccd;
            })
            ->addColumn('recruitments', function ($data) {
                $recruitments = '';
                foreach ($data->recruitments as $recruitment) {
                    $url = '<a href="' . route('recruitments.show', $recruitment->id) . '">' . $recruitment->position->name . '</a>';
                    $recruitments = $recruitments . ' - ' . $url . '<br>';
                }
                return $recruitments;
            })
            ->addColumn('actions', function ($data) {
                $action = '<a href="' . route("candidates.edit", $data->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                           <form style="display:inline" action="'. route("candidates.destroy", $data->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions', 'name', 'recruitments'])
            ->make(true);
    }
}
