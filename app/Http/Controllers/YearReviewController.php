<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreYearReviewRequest;
use App\Http\Requests\UpdateYearReviewRequest;
use App\Models\Position;
use App\Models\Work;
use App\Models\YearReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class YearReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('year_review.index');
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
    public function store(StoreYearReviewRequest $request)
    {
        if (Auth::user()->cannot('create', YearReview::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $year_review = new YearReview();
        $year_review->employee_id = $request->employee_id;
        $year_review->position_id = $request->position_id;
        $year_review->year = $request->year;
        $year_review->kpi_average = $request->kpi_average;
        $year_review->result = $request->result;
        if ($request->detail) {
            $year_review->detail = $request->detail;
        }
        $year_review->save();

        Alert::toast('Nhập đánh giá năm thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(YearReview $yearReview)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(YearReview $yearReview)
    {
        if (Auth::user()->cannot('update', $yearReview)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $my_position_ids = Work::where('employee_id', $yearReview->employee_id)->where('status', 'On')->pluck('position_id')->toArray();
        $my_positions = Position::whereIn('id', $my_position_ids)->get();

        return view('year_review.edit', [
            'year_review' => $yearReview,
            'my_positions' => $my_positions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateYearReviewRequest $request, YearReview $yearReview)
    {
        $yearReview->position_id = $request->position_id;
        $yearReview->year = $request->year;
        $yearReview->kpi_average = $request->kpi_average;
        $yearReview->result = $request->result;
        if ($request->detail) {
            $yearReview->detail = $request->detail;
        }
        $yearReview->save();

        Alert::toast('Sửa đánh giá năm thành công!', 'success', 'top-right');
        return redirect()->route('employees.show', $yearReview->employee_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(YearReview $yearReview)
    {
        if (Auth::user()->cannot('delete', $yearReview)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $yearReview->delete();

        Alert::toast('Xóa đánh giá năm thành công!', 'success', 'top-right');
        return redirect()->back();
    }


    public function employeeData($employee_id)
    {
        $data = YearReview::where('employee_id', $employee_id)->orderBy('id', 'desc')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('year', function ($data) {
                return $data->year;
            })
            ->editColumn('position', function ($data) {
                return $data->position->name;
            })
            ->editColumn('kpi_average', function ($data) {
                return $data->kpi_average;
            })
            ->editColumn('result', function ($data) {
                if($data->result == 'Xuất sắc') {
                    return '<span class="badge" style="background-color: purple; color:white;">Xuất sắc</span>';
                } else if($data->result == 'Tốt'){
                    return '<span class="badge badge-success">Tốt</span>';
                }
                else if($data->result == 'Đạt'){
                    return '<span class="badge badge-warning">Đạt</span>';
                }
                else {
                    return '<span class="badge badge-danger">Cải thiện</span>';
                }
            })
            ->editColumn('detail', function ($data) {
                return $data->detail;
            })
            ->addColumn('actions', function ($data) {
                $action = '<a href="' . route("year_reviews.edit", $data->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                           <form style="display:inline" action="'. route("year_reviews.destroy", $data->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['result', 'actions', 'detail'])
            ->make(true);
    }

    public function anyData()
    {
        $data = YearReview::join('employees', 'employees.id', 'year_reviews.employee_id')
                                ->select('year_reviews.*', 'employees.code as employees_code')
                                ->orderBy('employees_code', 'desc')
                                ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('department', function ($data) {
                return $data->position->department->name;
            })
            ->editColumn('employee_code', function ($data) {
                return $data->employees_code;
            })
            ->editColumn('employee', function ($data) {
                return '<a href="' . route("employees.show", $data->employee_id) . '">' . $data->employee->name . '</a>';
            })
            ->editColumn('position', function ($data) {
                return $data->position->name;
            })
            ->editColumn('year', function ($data) {
                return $data->year;
            })
            ->editColumn('kpi_average', function ($data) {
                return $data->kpi_average;
            })
            ->editColumn('result', function ($data) {
                if($data->result == 'Xuất sắc') {
                    return '<span class="badge" style="background-color: purple; color:white;">Xuất sắc</span>';
                } else if($data->result == 'Tốt'){
                    return '<span class="badge badge-success">Tốt</span>';
                }
                else if($data->result == 'Đạt'){
                    return '<span class="badge badge-warning">Đạt</span>';
                }
                else {
                    return '<span class="badge badge-danger">Cải thiện</span>';
                }
            })
            ->editColumn('detail', function ($data) {
                return $data->detail;
            })
            ->addColumn('actions', function ($data) {
                $action = '<a href="' . route("year_reviews.edit", $data->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                           <form style="display:inline" action="'. route("year_reviews.destroy", $data->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['department', 'employee', 'result', 'actions'])
            ->make(true);
    }
}
