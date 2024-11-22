<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AppendixController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\CommuneController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\DegreeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DepartmentManagerController;
use App\Http\Controllers\DepartmentViceController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\DivisionManagerController;
use App\Http\Controllers\DocTypeController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExaminationController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\FirstInterviewDetailController;
use App\Http\Controllers\FirstInterviewInvitationController;
use App\Http\Controllers\FirstInterviewResultController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InitialInterviewController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\KpiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MethodController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProbationController;
use App\Http\Controllers\ProbationPlanController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RecruitmentCandidateController;
use App\Http\Controllers\RecruitmentController;
use App\Http\Controllers\RegimeController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SecondInterviewDetailController;
use App\Http\Controllers\SecondInterviewInvitationController;
use App\Http\Controllers\SecondInterviewResultController;
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

    //Regimes
    Route::get('regimes/data', [RegimeController::class, 'anyData'])->name('regimes.data');
    Route::resource('regimes', RegimeController::class, ['names' => 'regimes']);

    //Welfares
    Route::get('welfares/data', [WelfareController::class, 'anyData'])->name('welfares.data');
    Route::resource('welfares', WelfareController::class, ['names' => 'welfares']);

    //Kpi
    Route::get('kpis/employeeData/{employee_id}', [KpiController::class, 'employeeData'])->name('kpis.employeeData');
    Route::get('kpis/data', [KpiController::class, 'anyData'])->name('kpis.data');
    Route::resource('kpis', KpiController::class, ['names' => 'kpis']);

    //YearReview
    Route::get('hyear_reviews/data', [YearReviewController::class, 'anyData'])->name('year_reviews.data');
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

    //WorkRotationReport
    Route::get('work_rotation_reports/data', [WorkRotationReportController::class, 'anyData'])->name('work_rotation_reports.data');
    Route::post('work_rotation_reports/by_month', [WorkRotationReportController::class, 'byMonth'])->name('work_rotation_reports.by_month');
    Route::get('work_rotation_reports/{month}/{year}', [WorkRotationReportController::class, 'byMonthData'])->name('work_rotation_reports.byMonthData');
    Route::get('work_rotation_reports/show', [WorkRotationReportController::class, 'show'])->name('work_rotation_reports.show');
    Route::get('work_rotation_reports', [WorkRotationReportController::class, 'index'])->name('work_rotation_reports.index');
});
