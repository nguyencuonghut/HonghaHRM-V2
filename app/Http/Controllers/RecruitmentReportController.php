<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AnnouncementChannel;
use App\Models\Candidate;
use App\Models\Channel;
use App\Models\Filter;
use App\Models\FirstInterviewInvitation;
use App\Models\FirstInterviewResult;
use App\Models\JoinDate;
use App\Models\Offer;
use App\Models\Recruitment;
use App\Models\RecruitmentCandidate;
use App\Models\SecondInterviewResult;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RecruitmentReportController extends Controller
{

    public function index()
    {
        return view('report.recruitment.index');
    }

    public function show()
    {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        return view('report.recruitment.show', [
            'month' => $month,
            'year' => $year,
        ]);
    }

    public function byMonth(Request $request)
    {
        $filter_month_year = explode('/', $request->month_of_year);
        $month = $filter_month_year[0];
        $year   = $filter_month_year[1];
        return view('report.recruitment.by_month',
                    [
                        'month' => $month,
                        'year' => $year,
                    ]);
    }

    public function anyData()
    {
        $data = Recruitment::orderBy('id', 'desc')->where('status', 'Đã duyệt')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('position', function ($data) {
                return $data->position->name;
            })
            ->editColumn('division', function ($data) {
                if ($data->position->division_id) {
                    return $data->position->division->name;
                } else {
                    return '-';
                }
            })
            ->editColumn('department', function ($data) {
                return $data->position->department->name;
            })
            ->editColumn('approved_time', function ($data) {
                return date('d/m/Y', strtotime($data->approved_time));
            })
            ->editColumn('work_time', function ($data) {
                return date('d/m/Y', strtotime($data->work_time));
            })
            ->editColumn('quantity', function ($data) {
                return $data->quantity;
            })
            ->editColumn('result', function ($data) {
                $recruitment_candidate_ids = RecruitmentCandidate::where('recruitment_id', $data->id)->pluck('id')->toArray();
                $offers = Offer::whereIn('recruitment_candidate_id', $recruitment_candidate_ids)
                                ->whereIn('result', ['Ký HĐLĐ', 'Ký HĐTV', 'Ký HĐCTV'])
                                ->get();
                return $offers->count();
            })
            ->editColumn('is_on_deadline', function ($data) {
                //Trường hợp số lượng tuyển được không đạt số lượng yêu cầu
                $recruitment_candidate_ids = RecruitmentCandidate::where('recruitment_id', $data->id)->pluck('id')->toArray();
                $offers = Offer::whereIn('recruitment_candidate_id', $recruitment_candidate_ids)
                                ->whereIn('result', ['Ký HĐLĐ', 'Ký HĐTV', 'Ký HĐCTV'])
                                ->get();
                if ($offers->count() < $data->quantity) {
                    return '<span class="badge badge-danger">Quá hạn</span>';
                } else {
                    //Khi số lượng đủ, so sánh giữa JoinDate của từng ứng viên
                    $recruitment_candidate_ids = RecruitmentCandidate::where('recruitment_id', $data->id)->pluck('id')->toArray();
                    $offer_recruitment_candidate_ids = Offer::whereIn('recruitment_candidate_id', $recruitment_candidate_ids)
                                                            ->whereIn('result', ['Ký HĐLĐ', 'Ký HĐTV', 'Ký HĐCTV'])
                                                            ->pluck('recruitment_candidate_id')
                                                            ->toArray();
                    $offer_recruitment_candidate_ids = RecruitmentCandidate::whereIn('id', $offer_recruitment_candidate_ids)
                                                                        ->pluck('id')
                                                                        ->toArray();
                    $join_dates = JoinDate::whereIn('recruitment_candidate_id', $offer_recruitment_candidate_ids)->get();
                    $is_on_deadline = true;

                    foreach ($join_dates as $join_date) {
                        if (Carbon::parse($join_date->join_date)->gt(Carbon::parse($data->work_time))) {
                            $is_on_deadline = false;
                            break;
                        }
                    }
                    if ($is_on_deadline) {
                        return '<span class="badge badge-success">Đúng hạn</span>';
                    } else {
                        return '<span class="badge badge-danger">Quá hạn</span>';
                    }
                }
            })
            ->editColumn('employees', function ($data) {
                $recruitment_candidate_ids = RecruitmentCandidate::where('recruitment_id', $data->id)->pluck('id')->toArray();
                $offer_recruitment_candidate_ids = Offer::whereIn('recruitment_candidate_id', $recruitment_candidate_ids)
                                ->whereIn('result', ['Ký HĐLĐ', 'Ký HĐTV', 'Ký HĐCTV'])
                                ->pluck('recruitment_candidate_id')
                                ->toArray();
                $offer_candidate_ids = RecruitmentCandidate::whereIn('id', $offer_recruitment_candidate_ids)
                                                                    ->pluck('candidate_id')
                                                                    ->toArray();
                $offer_candidates = Candidate::whereIn('id', $offer_candidate_ids)->get();
                $employees_str = '';
                $i = 0;
                $length = count($offer_candidates);
                if ($length) {
                    foreach ($offer_candidates as $offer_candidate) {
                        $my_recruitment_candiate = RecruitmentCandidate::where('recruitment_id', $data->id)
                                                                        ->where('candidate_id', $offer_candidate->id)
                                                                        ->first();
                        $join_date = JoinDate::where('recruitment_candidate_id', $my_recruitment_candiate->id)->first();
                        if(++$i === $length) {
                            if ($join_date) {
                                $employees_str .= '- ' . $offer_candidate->name . ' (' . date('d/m/Y', strtotime($join_date->join_date)) .')';
                            } else {
                                $employees_str .= '- '.  $offer_candidate->name;
                            }
                        } else {
                            if ($join_date) {
                                $employees_str .= '- ' . $offer_candidate->name . ' (' . date('d/m/Y', strtotime($join_date->join_date)) .')';
                                $employees_str .= ', <br>';
                            } else {
                                $employees_str .= '- '. $offer_candidate->name;
                                $employees_str .= ', <br>';
                            }
                        }
                    }
                } else {
                    $employees_str .= '-';
                }
                return $employees_str;
            })
            ->rawColumns(['is_on_deadline', 'employees'])
            ->make(true);
    }

    public function byMonthData($month, $year)
    {
        $data = Recruitment::orderBy('id', 'desc')
                            ->where('status', 'Đã duyệt')
                            ->whereMonth('approved_time', $month)
                            ->whereYear('approved_time', $year)
                            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('position', function ($data) {
                return $data->position->name;
            })
            ->editColumn('division', function ($data) {
                if ($data->position->division_id) {
                    return $data->position->division->name;
                } else {
                    return '-';
                }
            })
            ->editColumn('department', function ($data) {
                return $data->position->department->name;
            })
            ->editColumn('approved_time', function ($data) {
                return date('d/m/Y', strtotime($data->approved_time));
            })
            ->editColumn('work_time', function ($data) {
                return date('d/m/Y', strtotime($data->work_time));
            })
            ->editColumn('quantity', function ($data) {
                return $data->quantity;
            })
            ->editColumn('result', function ($data) {
                $recruitment_candidate_ids = RecruitmentCandidate::where('recruitment_id', $data->id)->pluck('id')->toArray();
                $offers = Offer::whereIn('recruitment_candidate_id', $recruitment_candidate_ids)
                                ->whereIn('result', ['Ký HĐLĐ', 'Ký HĐTV', 'Ký HĐCTV'])
                                ->get();
                return $offers->count();
            })
            ->editColumn('is_on_deadline', function ($data) {
                //Trường hợp số lượng tuyển được không đạt số lượng yêu cầu
                $recruitment_candidate_ids = RecruitmentCandidate::where('recruitment_id', $data->id)->pluck('id')->toArray();
                $offers = Offer::whereIn('recruitment_candidate_id', $recruitment_candidate_ids)
                                ->whereIn('result', ['Ký HĐLĐ', 'Ký HĐTV', 'Ký HĐCTV'])
                                ->get();
                if ($offers->count() < $data->quantity) {
                    return '<span class="badge badge-danger">Quá hạn</span>';
                } else {
                    //Khi số lượng đủ, so sánh giữa JoinDate của từng ứng viên
                    $recruitment_candidate_ids = RecruitmentCandidate::where('recruitment_id', $data->id)->pluck('id')->toArray();
                    $offer_recruitment_candidate_ids = Offer::whereIn('recruitment_candidate_id', $recruitment_candidate_ids)
                                                            ->whereIn('result', ['Ký HĐLĐ', 'Ký HĐTV', 'Ký HĐCTV'])
                                                            ->pluck('recruitment_candidate_id')
                                                            ->toArray();
                    $offer_recruitment_candidate_ids = RecruitmentCandidate::whereIn('id', $offer_recruitment_candidate_ids)
                                                                        ->pluck('id')
                                                                        ->toArray();
                    $join_dates = JoinDate::whereIn('recruitment_candidate_id', $offer_recruitment_candidate_ids)->get();
                    $is_on_deadline = true;

                    foreach ($join_dates as $join_date) {
                        if (Carbon::parse($join_date->join_date)->gt(Carbon::parse($data->work_time))) {
                            $is_on_deadline = false;
                            break;
                        }
                    }
                    if ($is_on_deadline) {
                        return '<span class="badge badge-success">Đúng hạn</span>';
                    } else {
                        return '<span class="badge badge-danger">Quá hạn</span>';
                    }
                }
            })
            ->editColumn('employees', function ($data) {
                $recruitment_candidate_ids = RecruitmentCandidate::where('recruitment_id', $data->id)->pluck('id')->toArray();
                $offer_recruitment_candidate_ids = Offer::whereIn('recruitment_candidate_id', $recruitment_candidate_ids)
                                ->whereIn('result', ['Ký HĐLĐ', 'Ký HĐTV', 'Ký HĐCTV'])
                                ->pluck('recruitment_candidate_id')
                                ->toArray();
                $offer_candidate_ids = RecruitmentCandidate::whereIn('id', $offer_recruitment_candidate_ids)
                                                                    ->pluck('candidate_id')
                                                                    ->toArray();
                $offer_candidates = Candidate::whereIn('id', $offer_candidate_ids)->get();
                $employees_str = '';
                $i = 0;
                $length = count($offer_candidates);
                if ($length) {
                    foreach ($offer_candidates as $offer_candidate) {
                        $my_recruitment_candiate = RecruitmentCandidate::where('recruitment_id', $data->id)
                                                                        ->where('candidate_id', $offer_candidate->id)
                                                                        ->first();
                        $join_date = JoinDate::where('recruitment_candidate_id', $my_recruitment_candiate->id)->first();
                        if(++$i === $length) {
                            if ($join_date) {
                                $employees_str .= '- ' . $offer_candidate->name . ' (' . date('d/m/Y', strtotime($join_date->join_date)) .')';
                            } else {
                                $employees_str .= '- '.  $offer_candidate->name;
                            }
                        } else {
                            if ($join_date) {
                                $employees_str .= '- ' . $offer_candidate->name . ' (' . date('d/m/Y', strtotime($join_date->join_date)) .')';
                                $employees_str .= ', <br>';
                            } else {
                                $employees_str .= '- '. $offer_candidate->name;
                                $employees_str .= ', <br>';
                            }
                        }
                    }
                } else {
                    $employees_str .= '-';
                }
                return $employees_str;
            })
            ->rawColumns(['is_on_deadline', 'employees'])
            ->make(true);
    }

    public function byRange(Request $request)
    {
        if ($request->ajax()) {
            $data = Recruitment::orderBy('id', 'desc')->where('status', 'Đã duyệt')->get();
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $data = $data->whereBetween('approved_time', [$request->from_date, $request->to_date]);
            }

            return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('position', function ($data) {
                return $data->position->name;
            })
            ->editColumn('division', function ($data) {
                if ($data->position->division_id) {
                    return $data->position->division->name;
                } else {
                    return '-';
                }
            })
            ->editColumn('department', function ($data) {
                return $data->position->department->name;
            })
            ->editColumn('approved_time', function ($data) {
                return date('d/m/Y', strtotime($data->approved_time));
            })
            ->editColumn('work_time', function ($data) {
                return date('d/m/Y', strtotime($data->work_time));
            })
            ->editColumn('quantity', function ($data) {
                return $data->quantity;
            })
            ->editColumn('result', function ($data) {
                $recruitment_candidate_ids = RecruitmentCandidate::where('recruitment_id', $data->id)->pluck('id')->toArray();
                $offers = Offer::whereIn('recruitment_candidate_id', $recruitment_candidate_ids)
                                ->whereIn('result', ['Ký HĐLĐ', 'Ký HĐTV', 'Ký HĐCTV'])
                                ->get();
                return $offers->count();
            })
            ->editColumn('is_on_deadline', function ($data) {
                //Trường hợp số lượng tuyển được không đạt số lượng yêu cầu
                $recruitment_candidate_ids = RecruitmentCandidate::where('recruitment_id', $data->id)->pluck('id')->toArray();
                $offers = Offer::whereIn('recruitment_candidate_id', $recruitment_candidate_ids)
                                ->whereIn('result', ['Ký HĐLĐ', 'Ký HĐTV', 'Ký HĐCTV'])
                                ->get();
                if ($offers->count() < $data->quantity) {
                    return '<span class="badge badge-danger">Quá hạn</span>';
                } else {
                    //Khi số lượng đủ, so sánh giữa JoinDate của từng ứng viên
                    $recruitment_candidate_ids = RecruitmentCandidate::where('recruitment_id', $data->id)->pluck('id')->toArray();
                    $offer_recruitment_candidate_ids = Offer::whereIn('recruitment_candidate_id', $recruitment_candidate_ids)
                                                            ->whereIn('result', ['Ký HĐLĐ', 'Ký HĐTV', 'Ký HĐCTV'])
                                                            ->pluck('recruitment_candidate_id')
                                                            ->toArray();
                    $offer_recruitment_candidate_ids = RecruitmentCandidate::whereIn('id', $offer_recruitment_candidate_ids)
                                                                        ->pluck('id')
                                                                        ->toArray();
                    $join_dates = JoinDate::whereIn('recruitment_candidate_id', $offer_recruitment_candidate_ids)->get();
                    $is_on_deadline = true;

                    foreach ($join_dates as $join_date) {
                        if (Carbon::parse($join_date->join_date)->gt(Carbon::parse($data->work_time))) {
                            $is_on_deadline = false;
                            break;
                        }
                    }
                    if ($is_on_deadline) {
                        return '<span class="badge badge-success">Đúng hạn</span>';
                    } else {
                        return '<span class="badge badge-danger">Quá hạn</span>';
                    }
                }
            })
            ->editColumn('employees', function ($data) {
                $recruitment_candidate_ids = RecruitmentCandidate::where('recruitment_id', $data->id)->pluck('id')->toArray();
                $offer_recruitment_candidate_ids = Offer::whereIn('recruitment_candidate_id', $recruitment_candidate_ids)
                                ->whereIn('result', ['Ký HĐLĐ', 'Ký HĐTV', 'Ký HĐCTV'])
                                ->pluck('recruitment_candidate_id')
                                ->toArray();
                $offer_candidate_ids = RecruitmentCandidate::whereIn('id', $offer_recruitment_candidate_ids)
                                                                    ->pluck('candidate_id')
                                                                    ->toArray();
                $offer_candidates = Candidate::whereIn('id', $offer_candidate_ids)->get();
                $employees_str = '';
                $i = 0;
                $length = count($offer_candidates);
                if ($length) {
                    foreach ($offer_candidates as $offer_candidate) {
                        $my_recruitment_candiate = RecruitmentCandidate::where('recruitment_id', $data->id)
                                                                        ->where('candidate_id', $offer_candidate->id)
                                                                        ->first();
                        $join_date = JoinDate::where('recruitment_candidate_id', $my_recruitment_candiate->id)->first();
                        if(++$i === $length) {
                            if ($join_date) {
                                $employees_str .= '- ' . $offer_candidate->name . ' (' . date('d/m/Y', strtotime($join_date->join_date)) .')';
                            } else {
                                $employees_str .= '- '.  $offer_candidate->name;
                            }
                        } else {
                            if ($join_date) {
                                $employees_str .= '- ' . $offer_candidate->name . ' (' . date('d/m/Y', strtotime($join_date->join_date)) .')';
                                $employees_str .= ', <br>';
                            } else {
                                $employees_str .= '- '. $offer_candidate->name;
                                $employees_str .= ', <br>';
                            }
                        }
                    }
                } else {
                    $employees_str .= '-';
                }
                return $employees_str;
            })
            ->rawColumns(['is_on_deadline', 'employees'])
            ->make(true);
        }

        return view('report.recruitment.by_range');
    }
}
