<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AppendixController;
use App\Http\Controllers\BirthdayReportController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\CommuneController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\DecreaseInsuranceController;
use App\Http\Controllers\DegreeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DepartmentManagerController;
use App\Http\Controllers\DepartmentViceController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\DisciplineReportController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\DivisionManagerController;
use App\Http\Controllers\DocTypeController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentReportController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExaminationController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\FirstInterviewDetailController;
use App\Http\Controllers\FirstInterviewInvitationController;
use App\Http\Controllers\FirstInterviewResultController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IncreaseDecreaseInsuranceReportController;
use App\Http\Controllers\IncreaseInsuranceController;
use App\Http\Controllers\InitialInterviewController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\JoinDateController;
use App\Http\Controllers\KidPolicyReportController;
use App\Http\Controllers\KpiController;
use App\Http\Controllers\KpiReportController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MethodController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OffWorkReportController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProbationController;
use App\Http\Controllers\ProbationPlanController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RecruitmentCandidateController;
use App\Http\Controllers\RecruitmentController;
use App\Http\Controllers\RecruitmentReportController;
use App\Http\Controllers\RegimeController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\RewardReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SecondInterviewDetailController;
use App\Http\Controllers\SecondInterviewInvitationController;
use App\Http\Controllers\SecondInterviewResultController;
use App\Http\Controllers\SeniorityReportController;
use App\Http\Controllers\SituationReportController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WelfareController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\WorkRotationReportController;
use App\Http\Controllers\YearReviewController;
use Illuminate\Support\Facades\Route;


/**Authentication */
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'handleLogin'])->name('handleLogin');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [LoginController::class, 'showForgotPasswordForm'])->name('forgot.password.get');
Route::post('/forgot-password', [LoginController::class, 'submitForgotPasswordForm'])->name('forgot.password.post');
Route::get('/reset-password/{token}', [LoginController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('/reset-password', [LoginController::class, 'submitResetPasswordForm'])->name('reset.password.post');


Route::group(['middleware'=>'auth:web'], function() {
    //Home
    Route::get('/', [HomeController::class, 'home'])->name('home');

    //Roles
    Route::post('/roles/import', [RoleController::class, 'import'])->name('roles.import');
    Route::get('/roles/data', [RoleController::class, 'anyData'])->name('roles.data');
    Route::resource('/roles', RoleController::class);

    //Users
    Route::post('/users/import', [UsersController::class, 'import'])->name('users.import');
    Route::get('/users/data', [UsersController::class, 'anyData'])->name('users.data');
    Route::resource('/users', UsersController::class);

    //Departments
    Route::get('/departments/get-division/{department_id}', [DepartmentController::class, 'getDivision'])->name('departments.getDivision');
    Route::post('/departments/import', [DepartmentController::class, 'import'])->name('departments.import');
    Route::get('/departments/data', [DepartmentController::class, 'anyData'])->name('departments.data');
    Route::resource('/departments', DepartmentController::class);

    //Divisions
    Route::post('/divisions/import', [DivisionController::class, 'import'])->name('divisions.import');
    Route::get('/divisions/data', [DivisionController::class, 'anyData'])->name('divisions.data');
    Route::resource('/divisions', DivisionController::class);

    //Positions
    Route::post('/positions/import', [PositionController::class, 'import'])->name('positions.import');
    Route::get('/positions/data', [PositionController::class, 'anyData'])->name('positions.data');
    Route::resource('/positions', PositionController::class);

    //Recruitment
    Route::post('/recruitments/approve/{recruitment}', [RecruitmentController::class, 'approve'])->name('recruitments.approve');
    Route::post('/recruitments/review/{recruitment}', [RecruitmentController::class, 'review'])->name('recruitments.review');
    Route::get('/recruitments/data', [RecruitmentController::class, 'anyData'])->name('recruitments.data');
    Route::resource('/recruitments', RecruitmentController::class);

    //Methods
    Route::get('/methods/data', [MethodController::class, 'anyData'])->name('methods.data');
    Route::resource('/methods', MethodController::class);

    //Plans
    Route::post('/plans/approve/{plan}', [PlanController::class, 'approve'])->name('plans.approve');
    Route::resource('/plans', PlanController::class);

    //Social Media
    Route::get('/channels/data', [ChannelController::class, 'anyData'])->name('channels.data');
    Route::resource('/channels', ChannelController::class);

    //Announcements
    Route::resource('/announcements', AnnouncementController::class);

    //Provinces
    Route::get('/provinces/data', [ProvinceController::class, 'anyData'])->name('provinces.data');
    Route::resource('/provinces', ProvinceController::class);

    //Districts
    Route::get('/districts/data', [DistrictController::class, 'anyData'])->name('districts.data');
    Route::resource('/districts', DistrictController::class);

    //Communes
    Route::post('/communes/import', [CommuneController::class, 'import'])->name('communes.import');
    Route::get('/communes/data', [CommuneController::class, 'anyData'])->name('communes.data');
    Route::resource('/communes', CommuneController::class);

    //Schools
    Route::post('/schools/import', [SchoolController::class, 'import'])->name('schools.import');
    Route::get('/schools/data', [SchoolController::class, 'anyData'])->name('schools.data');
    Route::resource('/schools', SchoolController::class);

    //Degrees
    Route::get('/degrees/data', [DegreeController::class, 'anyData'])->name('degrees.data');
    Route::resource('/degrees', DegreeController::class);

    //Candidates
    Route::get('/candidates/data', [CandidateController::class, 'anyData'])->name('candidates.data');
    Route::resource('/candidates', CandidateController::class);

    //RecruitmentCandidates
    Route::get('/recruitment_candidates/data', [RecruitmentCandidateController::class, 'anyData'])->name('recruitment_candidates.data');
    Route::resource('/recruitment_candidates', RecruitmentCandidateController::class);

    //Candidate Filters
    Route::post('filters/approve/{filter}', [FilterController::class, 'approve'])->name('filters.approve');
    Route::resource('filters', FilterController::class);

    //FirstInterviewInvitations
    Route::get('first_interview_invitations/add/{recruitment_candidate_id}', [FirstInterviewInvitationController::class, 'add'])->name('first_interview_invitations.add');
    Route::get('first_interview_invitations/feedback/{recruitment_candidate_id}', [FirstInterviewInvitationController::class, 'feedback'])->name('first_interview_invitations.feedback');
    Route::resource('first_interview_invitations', FirstInterviewInvitationController::class, ['names' => 'first_interview_invitations'], ['except' => 'create']);

    //InitialInterviews
    Route::resource('initial_interviews', InitialInterviewController::class, ['names' => 'initial_interviews']);

    //Examinations
    Route::resource('examinations', ExaminationController::class, ['names' => 'examinations']);

    //FirstInterviewDetails
    Route::resource('first_interview_details', FirstInterviewDetailController::class, ['names' => 'first_interview_details']);

    //FirstInterviewResults
    Route::resource('first_interview_results', FirstInterviewResultController::class, ['names' => 'first_interview_results']);

    //SecondInterviewInvitations
    Route::get('second_interview_invitations/add/{recruitment_candidate_id}', [SecondInterviewInvitationController::class, 'add'])->name('second_interview_invitations.add');
    Route::get('second_interview_invitations/feedback/{recruitment_candidate_id}', [SecondInterviewInvitationController::class, 'feedback'])->name('second_interview_invitations.feedback');
    Route::resource('second_interview_invitations', SecondInterviewInvitationController::class, ['names' => 'second_interview_invitations'], ['except' => 'create']);

    //SecondInterviewerDetails
    Route::resource('second_interview_details', SecondInterviewDetailController::class, ['names' => 'second_interview_details']);

    //SecondInterviewerResults
    Route::resource('second_interview_results', SecondInterviewResultController::class, ['names' => 'second_interview_results']);

    //Offers
    Route::post('offers/approve/{offer}', [OfferController::class, 'approve'])->name('offers.approve');
    Route::resource('offers', OfferController::class, ['names' => 'offers']);

    //Employees
    Route::get('employees/gallery', [EmployeeController::class, 'gallery'])->name('employees.gallery');
    Route::get('employees/export', [EmployeeController::class, 'export'])->name('employees.export');
    Route::get('employees/data', [EmployeeController::class, 'anyData'])->name('employees.data');
    Route::get('employees/create_from_candidate/{recruitment_candidate_id}', [EmployeeController::class, 'createFromCandidate'])->name('employees.create_from_candidate');
    Route::post('employees/store_from_candidate', [EmployeeController::class, 'storeFromCandidate'])->name('employees.store_from_candidate');
    Route::resource('employees', EmployeeController::class, ['names' => 'employees']);

    //ContractTypes
    Route::get('contract_types/data', [ContractTypeController::class, 'anyData'])->name('contract_types.data');
    Route::resource('contract_types', ContractTypeController::class, ['names' => 'contract_types']);

    //Contracts
    Route::get('contracts/export/{contract}', [ContractController::class, 'export'])->name('contracts.export');
    Route::get('contracts/terminate_form/{contract}', [ContractController::class, 'terminateForm'])->name('contracts.terminate_form');
    Route::post('contracts/off/{contract}', [ContractController::class, 'off'])->name('contracts.off');
    Route::get('contracts/off/{contract}', [ContractController::class, 'getOff'])->name('contracts.getOff');
    Route::get('contracts/data', [ContractController::class, 'anyData'])->name('contracts.data');
    Route::resource('contracts', ContractController::class, ['names' => 'contracts']);

    //Appendixes
    Route::get('appendixes/data', [AppendixController::class, 'anyData'])->name('appendixes.data');
    Route::post('appendixes/add/{contract_id}', [AppendixController::class, 'add'])->name('appendixes.add');
    Route::get('appendixes/add/{contract_id}', [AppendixController::class, 'getAdd'])->name('appendixes.getAdd');
    Route::resource('appendixes', AppendixController::class, ['names' => 'appendixes']);

    //Works
    Route::get('works/data', [WorkController::class, 'anyData'])->name('works.data');
    Route::post('works/off/{work}', [WorkController::class, 'off'])->name('works.off');
    Route::get('works/off/{work}', [WorkController::class, 'getOff'])->name('works.getOff');
    Route::resource('works', WorkController::class, ['names' => 'works']);

    //Salary
    Route::get('salaries/off/{salary}', [SalaryController::class, 'getOff'])->name('salaries.getOff');
    Route::post('salaries/off/{salary}', [SalaryController::class, 'off'])->name('salaries.off');
    Route::get('salaries/data', [SalaryController::class, 'anyData'])->name('salaries.data');
    Route::get('salaries/employeeData/{employee_id}', [SalaryController::class, 'employeeData'])->name('salaries.employeeData');
    Route::resource('salaries', SalaryController::class, ['names' => 'salaries']);

    //DocTypes
    Route::get('doc_types/data', [DocTypeController::class, 'anyData'])->name('doc_types.data');
    Route::resource('doc_types', DocTypeController::class);

    //Documents
    Route::resource('documents', DocumentController::class);

    //Probations
    Route::post('probations/approve/{probation}', [ProbationController::class, 'approve'])->name('probations.approve');
    Route::post('probations/review/{probation}', [ProbationController::class, 'review'])->name('probations.review');
    Route::post('probations/evaluate/{probation}', [ProbationController::class, 'evaluate'])->name('probations.evaluate');
    Route::get('probations/data', [ProbationController::class, 'anyData'])->name('probations.data');
    Route::resource('probations', ProbationController::class, ['names' => 'probations']);

    //ProbationPlans
    Route::resource('probation_plans', ProbationPlanController::class);

    //Families
    Route::resource('families', FamilyController::class);

    //Insurances
    Route::get('insurances/data', [InsuranceController::class, 'anyData'])->name('insurances.data');
    Route::resource('insurances', InsuranceController::class, ['names' => 'insurances']);

    //IncreaseInsurance
    Route::get('increase_insurances/data', [IncreaseInsuranceController::class, 'anyData'])->name('increase_insurances.data');
    Route::get('increase_insurances/getConfirm/{id}', [IncreaseInsuranceController::class, 'getConfirm'])->name('increase_insurances.getConfirm');
    Route::post('increase_insurances/confirm/{id}', [IncreaseInsuranceController::class, 'confirm'])->name('increase_insurances.confirm');
    Route::resource('increase_insurances', IncreaseInsuranceController::class, ['names' => 'increase_insurances']);

    //DecreaseInsurance
    Route::get('decrease_insurances/data', [DecreaseInsuranceController::class, 'anyData'])->name('decrease_insurances.data');
    Route::get('decrease_insurances/getConfirm/{id}', [DecreaseInsuranceController::class, 'getConfirm'])->name('decrease_insurances.getConfirm');
    Route::post('decrease_insurances/confirm/{id}', [DecreaseInsuranceController::class, 'confirm'])->name('decrease_insurances.confirm');
    Route::resource('decrease_insurances', DecreaseInsuranceController::class, ['names' => 'decrease_insurances']);

    //Regimes
    Route::get('regimes/data', [RegimeController::class, 'anyData'])->name('regimes.data');
    Route::resource('regimes', RegimeController::class, ['names' => 'regimes']);

    //Welfares
    Route::get('welfares/data', [WelfareController::class, 'anyData'])->name('welfares.data');
    Route::resource('welfares', WelfareController::class, ['names' => 'welfares']);

    //Kpi
    Route::post('kpis/import', [KpiController::class, 'import'])->name('kpis.import');
    Route::get('kpis/employeeData/{employee_id}', [KpiController::class, 'employeeData'])->name('kpis.employeeData');
    Route::get('kpis/data', [KpiController::class, 'anyData'])->name('kpis.data');
    Route::resource('kpis', KpiController::class, ['names' => 'kpis']);

    //YearReview
    Route::get('year_reviews/data', [YearReviewController::class, 'anyData'])->name('year_reviews.data');
    Route::get('year_reviews/employeeData/{employee_id}', [YearReviewController::class, 'employeeData'])->name('year_reviews.employeeData');
    Route::resource('year_reviews', YearReviewController::class, ['names' => 'year_reviews']);

    //Rewards
    Route::get('rewards/data', [RewardController::class, 'anyData'])->name('rewards.data');
    Route::get('rewards/employeeData/{employee_id}', [RewardController::class, 'employeeData'])->name('rewards.employeeData');
    Route::resource('rewards', RewardController::class, ['names' => 'rewards']);

    //Discipline
    Route::get('disciplines/data', [DisciplineController::class, 'anyData'])->name('disciplines.data');
    Route::get('disciplines/employeeData/{employee_id}', [DisciplineController::class, 'employeeData'])->name('disciplines.employeeData');
    Route::resource('disciplines', DisciplineController::class, ['names' => 'disciplines']);

    //Calendar
    Route::get('calendars', [CalendarController::class, 'index'])->name('calendars.index');

    //DepartmentManager
    Route::get('department_managers/data', [DepartmentManagerController::class, 'anyData'])->name('department_managers.data');
    Route::resource('department_managers', DepartmentManagerController::class);

    //DivisionManager
    Route::get('division_managers/data', [DivisionManagerController::class, 'anyData'])->name('division_managers.data');
    Route::resource('division_managers', DivisionManagerController::class);

    //DepartmentVice
    Route::get('department_vices/data', [DepartmentViceController::class, 'anyData'])->name('department_vices.data');
    Route::resource('department_vices', DepartmentViceController::class);

    //JoinDate
    Route::get('join_dates/data', [JoinDateController::class, 'anyData'])->name('join_dates.data');
    Route::resource('join_dates', JoinDateController::class, ['names' => 'join_dates']);

    //WorkRotationReport
    Route::get('work_rotation_reports/data', [WorkRotationReportController::class, 'anyData'])->name('work_rotation_reports.data');
    Route::post('work_rotation_reports/by_month', [WorkRotationReportController::class, 'byMonth'])->name('work_rotation_reports.by_month');
    Route::get('work_rotation_reports/{month}/{year}', [WorkRotationReportController::class, 'byMonthData'])->name('work_rotation_reports.byMonthData');
    Route::get('work_rotation_reports/show', [WorkRotationReportController::class, 'show'])->name('work_rotation_reports.show');
    Route::get('work_rotation_reports/by_range', [WorkRotationReportController::class, 'byRange'])->name('work_rotation_reports.by_range');
    Route::get('work_rotation_reports', [WorkRotationReportController::class, 'index'])->name('work_rotation_reports.index');

    //OffWorkReport
    Route::get('off_work_reports/data', [OffWorkReportController::class, 'anyData'])->name('off_work_reports.data');
    Route::post('off_work_reports/by_month', [OffWorkReportController::class, 'byMonth'])->name('off_work_reports.by_month');
    Route::get('off_work_reports/{month}/{year}', [OffWorkReportController::class, 'byMonthData'])->name('off_work_reports.byMonthData');
    Route::get('off_work_reports/show', [OffWorkReportController::class, 'show'])->name('off_work_reports.show');
    Route::get('off_work_reports/by_range', [OffWorkReportController::class, 'byRange'])->name('off_work_reports.by_range');
    Route::get('off_work_reports', [OffWorkReportController::class, 'index'])->name('off_work_reports.index');


    //RewardReport
    Route::get('reward_reports/show', [RewardReportController::class, 'show'])->name('reward_reports.show');
    Route::get('reward_reports/data', [RewardReportController::class, 'anyData'])->name('reward_reports.data');
    Route::post('reward_reports/by_year', [RewardReportController::class, 'byYear'])->name('reward_reports.by_year');
    Route::get('reward_reports/{year}', [RewardReportController::class, 'byYearData'])->name('reward_reports.byYearData');
    Route::get('reward_reports', [RewardReportController::class, 'index'])->name('reward_reports.index');

    //DisciplineReport
    Route::get('discipline_reports/show', [DisciplineReportController::class, 'show'])->name('discipline_reports.show');
    Route::get('discipline_reports/data', [DisciplineReportController::class, 'anyData'])->name('discipline_reports.data');
    Route::post('discipline_reports/by_year', [DisciplineReportController::class, 'byYear'])->name('discipline_reports.by_year');
    Route::get('discipline_reports/{year}', [DisciplineReportController::class, 'byYearData'])->name('discipline_reports.byYearData');
    Route::get('discipline_reports', [DisciplineReportController::class, 'index'])->name('discipline_reports.index');

    //KpiReport
    Route::get('kpi_reports/show', [KpiReportController::class, 'show'])->name('kpi_reports.show');
    Route::post('kpi_reports/by_year', [KpiReportController::class, 'byYear'])->name('kpi_reports.by_year');
    Route::get('kpi_reports/{year}', [KpiReportController::class, 'byYearData'])->name('kpi_reports.byYearData');

    //BirthdayReport
    Route::get('birthday_reports', [BirthdayReportController::class, 'index'])->name('birthday_reports.index');

    //RecruitmentReport
    Route::get('recruitment_reports/data', [RecruitmentReportController::class, 'anyData'])->name('recruitment_reports.data');
    Route::post('recruitment_reports/by_month', [RecruitmentReportController::class, 'byMonth'])->name('recruitment_reports.by_month');
    Route::get('recruitment_reports/{month}/{year}', [RecruitmentReportController::class, 'byMonthData'])->name('recruitment_reports.byMonthData');
    Route::get('recruitment_reports/show', [RecruitmentReportController::class, 'show'])->name('recruitment_reports.show');
    Route::get('recruitment_reports/by_range', [RecruitmentReportController::class, 'byRange'])->name('recruitment_reports.by_range');
    Route::get('recruitment_reports', [RecruitmentReportController::class, 'index'])->name('recruitment_reports.index');

    //SituationReport
    Route::get('situation_reports', [SituationReportController::class, 'index'])->name('situation_reports.index');
    Route::get('situation_reports/data', [SituationReportController::class, 'anyData'])->name('situation_reports.data');

    //KidPolicyReport
    Route::get('kid_policy_reports', [KidPolicyReportController::class, 'index'])->name('kid_policy_reports.index');
    Route::get('kid_policy_reports/data', [KidPolicyReportController::class, 'anyData'])->name('kid_policy_reports.data');

    //DocumentReport
    Route::get('document_reports', [DocumentReportController::class, 'index'])->name('document_reports.index');
    Route::get('document_reports/data', [DocumentReportController::class, 'anyData'])->name('document_reports.data');

    //SeniorityReport
    Route::get('seniority_reports/show', [SeniorityReportController::class, 'show'])->name('seniority_reports.show');
    Route::get('seniority_reports/data', [SeniorityReportController::class, 'anyData'])->name('seniority_reports.data');
    Route::post('seniority_reports/by_year', [SeniorityReportController::class, 'byYear'])->name('seniority_reports.by_year');
    Route::get('seniority_reports/{year}', [SeniorityReportController::class, 'byYearData'])->name('seniority_reports.byYearData');
    Route::get('seniority_reports', [SeniorityReportController::class, 'index'])->name('seniority_reports.index');

    //IncreaseDecreaseInsuranceReport
    Route::get('increase_decrease_insurance_reports/export_dec_bhxh/{month}/{year}', [IncreaseDecreaseInsuranceReportController::class, 'exportDecBhxh'])->name('increase_decrease_insurance_reports.exportDecBhxh');
    Route::get('increase_decrease_insurance_reports/export_inc_bhxh/{month}/{year}', [IncreaseDecreaseInsuranceReportController::class, 'exportIncBhxh'])->name('increase_decrease_insurance_reports.exportIncBhxh');
    Route::post('increase_decrease_insurance_reports/by_month', [IncreaseDecreaseInsuranceReportController::class, 'byMonth'])->name('increase_decrease_insurance_reports.by_month');
    Route::get('increase_decrease_insurance_reports/decrease/{month}/{year}', [IncreaseDecreaseInsuranceReportController::class, 'decreaseByMonthData'])->name('increase_decrease_insurance_reports.decreaseByMonthData');
    Route::get('increase_decrease_insurance_reports/increase/{month}/{year}', [IncreaseDecreaseInsuranceReportController::class, 'increaseByMonthData'])->name('increase_decrease_insurance_reports.increaseByMonthData');
    Route::get('increase_decrease_insurance_reports/show', [IncreaseDecreaseInsuranceReportController::class, 'show'])->name('increase_decrease_insurance_reports.show');
});
