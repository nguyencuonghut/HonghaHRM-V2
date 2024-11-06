
<!-- Báo cáo -->
<div class="tab-pane fade" id="recruitment-10" role="tabpanel" aria-labelledby="recruitment-tab-10">
    @php
        $recruitment_candidate_ids = App\Models\RecruitmentCandidate::where('recruitment_id', $recruitment->id)->pluck('id')->toArray();
        $offers = App\Models\Offer::whereIn('recruitment_candidate_id', $recruitment_candidate_ids)->where('result', '!=', null)->get();
    @endphp
    <!-- Default box -->
    @if ($offers->count())
    <div class="card card-solid">
        <div class="card-body pb-0">
            <div class="row">
                @foreach ($recruitment->candidates as $candidate)
                @php
                $recruitment_candidate = App\Models\RecruitmentCandidate::where('recruitment_id', $recruitment->id)->where('candidate_id', $candidate->id)->first();
                $offer = App\Models\Offer::where('recruitment_candidate_id', $recruitment_candidate->id)->first();
                @endphp
                @if($offer && $offer->result)
                    <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column mb-4">
                        <div class="position-relative p-3 bg-gray" style="height: 180px">
                            <div class="ribbon-wrapper ribbon-lg">
                                <div class="ribbon
                                    @if ('Không đạt' == $offer->result)
                                        bg-danger
                                    @elseif ('Ký HĐLĐ' == $offer->result)
                                        bg-success
                                    @elseif ('Ký HĐTV' == $offer->result)
                                        bg-primary
                                    @else
                                        bg-warning
                                    @endif
                                    text-lg"
                                >
                                    {{$offer->result}}
                                </div>
                            </div>
                            {{$candidate->name}}
                            <br>
                            <i class="fas fa-calendar-alt"></i><small style="margin:5px;"> {{date('d/m/Y', strtotime($candidate->date_of_birth))}}</small>
                            <br>
                            <i class="fas fa-map-marker-alt"></i><small style="margin:5px;"> {{$candidate->commune->name}}, {{$candidate->commune->district->name}}, {{$candidate->commune->district->province->name}}</small>
                            <br>
                            <i class="fas fa-mobile-alt"></i><small style="margin:5px;"> {{$candidate->phone}}</small>
                            <br>
                            <i class="fas fa-graduation-cap">
                            </i>
                            <small>
                              @php
                                  $schools_info = '';

                                  foreach ($candidate->schools as $school) {
                                      $candidate_school = App\Models\CandidateSchool::where('candidate_id', $candidate->id)->where('school_id', $school->id)->first();
                                      $degree = App\Models\Degree::findOrFail($candidate_school->degree_id);
                                      if ($candidate_school->major) {
                                          $schools_info = $schools_info . $school->name . ' - ' . $degree->name . ' - ' . $candidate_school->major . '<br>';
                                      } else {
                                          $schools_info = $schools_info . $degree->name . ' - ' . $school->name;
                                      }
                                  }
                              @endphp
                              {!! $schools_info !!}
                            </small>
                            <i class="fas fa-hand-holding-usd"></i><small style="margin:5px;"> {{number_format($offer->position_salary + $offer->capacity_salary + $offer->position_allowance, 0, '.', ',')}}<sup>đ</sup></small>
                        </div>
                    </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
