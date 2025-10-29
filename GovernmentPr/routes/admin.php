<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddEventController;
use App\Http\Controllers\AddPostController;
use App\Http\Controllers\AdminsController;
use App\Http\Controllers\AnnualOperationsLogController;
use App\Http\Controllers\CompanyUsersController;
use App\Http\Controllers\CalendarYearController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChemicalStockMovementController;
use App\Http\Controllers\ChemicalsController;
use App\Http\Controllers\CompanyChemicalController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyMaterialController;
use App\Http\Controllers\CompanyOperationController;
use App\Http\Controllers\CompanyWasteController;
use App\Http\Controllers\EmailApp;
use App\Http\Controllers\EmailIntegration;
use App\Http\Controllers\EquipmentTypeController;
use App\Http\Controllers\EquipmentLogController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\generalSetting;
use App\Http\Controllers\GuardsController;
use App\Http\Controllers\InventoryForecastingController;
use App\Http\Controllers\IotDeviceController;
use App\Http\Controllers\MapReport;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\CMS\MediaController;
use App\Http\Controllers\MediaCategoryController;
use App\Http\Controllers\OperationTypeController;
use App\Http\Controllers\OperationCategoryController;
use App\Http\Controllers\CMS\PagesController;
use App\Http\Controllers\CMS\PageController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductionLogController;
use App\Http\Controllers\ProductionBatchTrackingController;
use App\Http\Controllers\ProductionProcessController;
use App\Http\Controllers\QualityControlController;
use App\Http\Controllers\RealTimeUpdateController;
use App\Http\Controllers\RECPController;
use App\Http\Controllers\ReportingAnalyticsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\StockTradingController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\UsersManagementController;
use App\Http\Controllers\viewEmailController;
use App\Http\Controllers\WaterSourceDetailsController;
use App\Http\Controllers\WaterUsageLogsController;
use App\Http\Controllers\WaterRecyclingLogsController;
use App\Http\Controllers\WaterQualityLogsController;
use App\Http\Controllers\WaterStockMovementController;
use App\Http\Controllers\WasteDisposalController;
use App\Http\Controllers\WasteItemController;
use App\Http\Controllers\WasteController;
use App\Http\Controllers\ProductionReport;
use App\Http\Controllers\WasteCategoryController;
use App\Http\Controllers\WasteSubCategoriesController;
use App\Http\Controllers\AnnualOperation\MetadataController;
use App\Http\Controllers\AnnualOperation\ActivityController;
use App\Http\Controllers\HRMS\CompanyDepartmentController;
use App\Http\Controllers\HRMS\CompanyEmployeesController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\Workflow\CompanyWorkflowController;
use App\Http\Controllers\Workflow\CompanyStageController;
use App\Http\Controllers\Workflow\StageTaskController;
use App\Http\Controllers\Workflow\TaskScheduleController;
use App\Http\Controllers\Workflow\TaskScheduleMetricsController;
use App\Http\Controllers\Workflow\RecurrenceRuleController;
use App\Http\Controllers\Workflow\TaskEmployeeController;

// Guest Admin Routes ['guest', 'admin']
Route::prefix('admin')->middleware('guest:admin')->group(function(){
    Route::controller(AdminsController::class)->group(function () {
        Route::get('/login', 'create_login')->name('admin.login');
        Route::post('/login', 'process_login')->name('admin.login');
        Route::get('/register', 'create_register')->name('admin.register');
        Route::post('/register', 'store')->name('admin.register');
    });
});

// Authenticated Admin Routes
Route::prefix('admin')->middleware('auth:admin')->group(function() {
    // Admin Dashboard and Logout
    Route::controller(AdminsController::class)->group(function(){
        Route::get('/admin-details',  'getAllAdmins')->name('admins.details');
        Route::get('/user-details', 'getAllAdmins')->name('user.details');
        Route::get('/dashboard', 'display_dashboard')->name('admin.dashboard');
        Route::get('/logout', 'destroy')->name('admin.logout');
        Route::get('/admin-details',  'getAllAdmins')->name('admins.details');
        Route::get('/admin/recp-trend-data', 'recpTrendData')->name('admin.recp-trend-data');
    });


    Route::controller(PermissionsController::class)->group(function() {
        Route::get('/permissions', 'index')->name('admin.permissions');
        Route::post('/permission', 'store')->name('admin.store-permission');
        Route::post('/permissions/{id}', 'update')->name('admin.update-permission'); // <- used by data-url
    });

    //users
    Route::controller(UsersManagementController::class)->group(function(){
        Route::get('/users-management', 'index')->name('admin.users-management');
        Route::get('/users-details',  'getAllUsers')->name('users.details');
        Route::post('/view-users',  'store')->name('store-users-details');
    });

    // roles
    Route::controller(RolesController::class)->group(function(){
        Route::get('/roles', 'manage')->name('admin.roles');
        Route::get('/settings/role', 'index')->name('admin.display-roles');
        Route::post('/settings/role', 'store')->name('admin.store-role');
        Route::post('/update-role', 'update')->name('admin.update.role');
        Route::delete('/admin/roles/{id}', 'destroy')->name('admin.delete.role');
        Route::post('/settings/assign_role_has_permission', 'assign_role_permission')->name('admin.update.permission-role');
        Route::post('/settings/revoke_role_has_permission', 'revoke_role_permission')->name('admin.revoke.permission-role');
        Route::post('/settings/role-change', 'guard_change')->name('admin.guard-change');
        Route::post('/settings/fetch-role-permission', 'get_role_permission')->name('admin.fetch.role-permission');
    });
    

    // settings
    Route::controller(generalSetting::class)->group(function() {
        Route::get('/general-setting', 'create')->name('admin.general-setting');
        Route::post ('/register-settings', 'store')->name('admin.store-settings');
    });

    // Guards
    Route::controller(GuardsController::class)->group(function() {
        Route::get('/guards', 'index')->name('admin.guards');
        Route::post('/guard', 'store')->name('admin.store-guard');
        Route::post('/guard/{guard_id}', 'update')->name('admin.update-guard'); // <- used by data-url
    });

    // Media
    Route::controller(MediaController::class)->group(function() {
        Route::get('/media', 'index')->name('admin.media.index');
        Route::get('/media-search', 'search')->name('admin.media.search');
        Route::get('/media/files/{categoryId}', 'getFiles')->name('media.files');
        Route::post('/media', 'store')->name('admin.media.store');
        Route::get('/media/{media}', 'show')->name('admin.media.show');
        Route::get('/media/{media}/edit', 'edit')->name('admin.media.edit');
        Route::put('/media/{media}', 'update')->name('admin.media.update');
        Route::delete('/media/{media}', 'destroy')->name('admin.media.destroy');
        Route::post('/media/upload-server', 'store')->name('admin.media.uploadServer');
        Route::post('/media/upload-dropbox', 'uploadToDropbox')->name('admin.media.uploadDropbox');
        Route::post('/media/upload-google', 'uploadToGoogleDrive')->name('admin.media.uploadGoogle');
        Route::post('/media/upload-onedrive', 'uploadToOneDrive')->name('admin.media.uploadOneDrive');
    });

    Route::prefix('media/categories')->name('admin.media.categories.')->group(function() {
        Route::post('/', [MediaCategoryController::class, 'store'])->name('store');
        Route::delete('/{id}', [MediaCategoryController::class, 'destroy'])->name('destroy');
    });


    //Pages
    Route::controller(PageController::class)->group(function() {
        // Route::get ('/pages', 'index')->name('admin.pages.index');
        // Route::get ('/create-page', 'create')->name('admin.pages.create');
        // Route::post ('/store-page', 'store')->name('admin.pages.store');
        Route::post ('/preview-page', 'store')->name('admin.pages.preview');
        // Route::get ('/edit-page/{id}', 'edit')->name('admin.pages.edit');
        // Route::put ('/update-page/{id}', 'update')->name('admin.pages.update');
        // Route::delete ('/delete-page/{id}', 'destroy')->name('admin.pages.delete');
        Route::post('/upload-image', 'uploadImage')->name('admin.upload.image');
        Route::post('/upload-media', 'uploadMedia')->name('admin.pages.uploadMedia');
        Route::get('/search-author', 'searchAuthor')->name('admin.search-author');
        Route::get('/search-category', 'searchCategory')->name('admin.search-category');
        Route::get('/search-cms-tag', 'searchTag');
        Route::get('/search-cms-role','searchRole')->name('admin.search-cms-role');
        Route::get('/pages/fetch', 'fetchMedia');
    });

    Route::resource('pages', PageController::class)->names('admin.pages');


    //Posts
    Route::controller(PostsController::class)->group(function() {
        Route::get ('/cms-posts', 'index')->name('CMS.posts');
        Route::post ('/save-posts', 'store')->name('admin.store-post');
    }); 

    Route::controller(AddPostController::class)->group(function() {
        Route::get ('/cms-Addpost', 'index')->name('CMS.add-post');
    });

    //Events
    Route::controller(EventController::class)->group(function() {
        Route::get ('/cms-events', 'index')->name('CMS.event');
    });


    Route::controller(AddEventController::class)->group(function() {
        Route::get ('/create-events', 'index')->name('CMS.add-event');
    });

    // Email integration
    Route::controller(EmailIntegration::class)->group(function() {
        Route::get ('/email', 'index')->name('email-configuration');
    });
    
    // email application
    Route::controller(EmailApp::class)->group(function() {
        Route::get ('/email-app', 'index')->name('view-email');
        Route::get ('/fetch-user', 'fetch_users')->name('get-user');
        Route::post ('/save-email', 'store')->name('send-mail');
    });
    
    Route::get('/show-email/{email}', [ViewEmailController::class, 'index'])->name('admin.show-email');
    Route::get('/notifications/{id}', [ViewEmailController::class, 'show'])->name('notifications.show');
    
    //Material
    Route::controller(MaterialController::class)->group(function() {
        Route::get ('/materials', 'index')->name('materials.material');
        Route::post ('/save-material', 'store')->name('admin.store-material');
        Route::delete('/delete-material/{id}', 'destroy');
    }); 

    // Calendar Year
    Route::controller(CalendarYearController::class)->group(function() {
        Route::get('/calendar-year', 'index')->name('admin.calendar-year');
        Route::post('/calendar-year/store', 'store')->name('admin.store-calendar-year');
        Route::get('/calendar-year/{id}', 'show')->name('admin.show-calendar-year');
        Route::put('/calendar-year/{id}', 'update')->name('admin.update-calendar-year');
        Route::delete('/calendar-year/{id}', 'destroy')->name('admin.delete-calendar-year');
        Route::get('/get-calendar-years/{value}', 'get_years');
    });

    //Chemicals
    Route::controller(ChemicalUsageController::class)->group(function() {
        Route::get ('/chemicals', 'index')->name('chemicals.chemicalUsage');
        Route::post ('/save-chemicals', 'store')->name('admin.store-chemical');
        Route::delete('/delete-chemical/{id}', 'destroy');
    }); 

    Route::controller(CompanyChemicalController::class)->group(function() {
        // Add / Update chemical
        Route::post('/save-company-chemical', 'store_company_chemical')->name('admin.store-company-chemical');

        // View chemical
        Route::get('/chemical-view/{chemical}', 'show')->name('admin.view-chemical');

        // Delete chemical
        Route::post('/delete-company-chemical/{chemical}', 'delete_company_chemical')->name('admin.delete-company-chemical');

        // Check-in chemical
        Route::post('/save-company-chemical-check-in', 'save_check_in')->name('admin.save-company-chemical-check-in');

        // Check-out chemical
        Route::post('/save-company-chemical-check-out', 'save_check_out')->name('admin.save-company-chemical-check-out');
    });

    // company users
    Route::controller(CompanyUsersController::class)->group(function(){
        Route::post('/assign-users/store', 'store')->name('admin.store-assign-users');
    });

    // Company Waste
    Route::controller(CompanyWasteController::class)->group(function() {
        Route::get('/waste', 'index')->name('admin.view-waste');
        Route::post('/waste/store', 'store')->name('admin.store-waste');
        Route::get('/waste/{id}', 'show')->name('admin.show-waste');
        Route::put('/waste/{id}', 'update')->name('admin.update-waste');
        Route::delete('/waste/{id}', 'destroy')->name('admin.delete-waste');
        Route::get('/get-waste/{value}', 'getCompanyWastes');
    });

    // Workflow Management
    Route::controller(CompanyWorkflowController::class)->group(function() {
        Route::get('/workflow', 'index')->name('admin.workflow');
        Route::post('/workflow/store', 'store')->name('admin.store-company-workflow');
        Route::get('/workflow/{id}', 'show')->name('admin.show-workflow');
        Route::post('/update-company-workflow/', 'update')->name('admin.update-company-workflow');
        Route::delete('/workflow/{id}', 'destroy')->name('admin.delete-workflow');
        Route::get('/workflow/company/{companyId}', 'getWorkflowsByCompany')->name('admin.workflow-by-company');
        Route::get('/get-workflows/{company_id}', 'getWorkflows')->name('admin.get-workflows');
    });

    // Company Stage
    Route::controller(CompanyStageController::class)->group(function() {
        Route::get('/company-stages', 'index')->name('admin.company-stages');
        Route::post('/company-stages/store', 'store')->name('admin.store-company-stage');
        Route::get('/company-stages/{id}', 'show')->name('admin.show-company-stage');
        Route::post('/update-company-stage/', 'update')->name('admin.update-company-stage');
        Route::post('/company-stage-tasks/estimated-time/', 'updateEstimatedTime')->name('admin.update-estimated-time');
        Route::delete('/company-stages/{id}', 'destroy')->name('admin.delete-company-stage');
        Route::get('/get-company-stages/{workflowId}', 'getStagesByWorkflow')->name('admin.get-company-stages');
    });

    // Recurrence Rule
    Route::controller(RecurrenceRuleController::class)->group(function() {
        Route::get('/recurrence-rules', 'index')->name('admin.recurrence-rules');
        Route::post('/recurrence-rules/store', 'store')->name('admin.store-recurrence-rule');
        Route::get('/recurrence-rules/{id}', 'show')->name('admin.show-recurrence-rule');
        Route::put('/recurrence-rules/{id}', 'update')->name('admin.update-recurrence-rule');
        Route::delete('/recurrence-rules/{id}', 'destroy')->name('admin.delete-recurrence-rule');
        Route::get('/get-recurrence-rules/{companyId}', 'getRecurrenceRulesByCompany')->name('admin.get-recurrence-rules');
    });

    Route::controller(TaskEmployeeController::class)->group(function() {
        Route::get('/task-employees', 'index')->name('admin.task-employees');
        Route::post('/task-employees/store', 'store')->name('admin.store-task-employee');
        Route::get('/task-employees/{id}', 'show')->name('admin.show-task-employee');
        Route::put('/task-employees/{id}', 'update')->name('admin.update-task-employee');
        Route::delete('/task-employees/{id}', 'destroy')->name('admin.delete-task-employee');
        Route::get('/get-task-employees/{taskId}', 'getEmployeesByTask')->name('admin.get-task-employees');
    });
    
    // Company Stage Tasks
    Route::controller(StageTaskController::class)->group(function() {
        Route::get('/company-stage-tasks', 'index')->name('admin.company-stage-tasks');
        Route::post('/company-stage-tasks/store', 'store')->name('admin.store-company-stage-task');
        Route::get('/company-stage-tasks/{id}', 'show')->name('admin.show-company-stage-task');
        Route::post('/company-stage-tasks/', 'update')->name('admin.update-company-stage-task');
        Route::delete('/company-stage-tasks/{id}', 'destroy')->name('admin.delete-company-stage-task');
        Route::get('/get-stage-tasks/{stageId}', 'getTasksByStage')->name('admin.get-stage-tasks');
    });

    // Company Stage Task Schedules
    Route::controller(TaskScheduleController::class)->group(function() {
        Route::get('/company-stage-task-schedules', 'index')->name('admin.company-stage-task-schedules');
        Route::post('/company-stage-task-schedules/store', 'store')->name('admin.store-company-stage-task-schedule');
        Route::get('/company-stage-task-schedules/{id}', 'show')->name('admin.show-company-stage-task-schedule');
        Route::put('/company-stage-task-schedules/{id}', 'update')->name('admin.update-company-stage-task-schedule');
        Route::delete('/company-stage-task-schedules/{id}', 'destroy')->name('admin.delete-company-stage-task-schedule');
        Route::get('/get-task-schedules/{taskId}', 'getSchedulesByTask')->name('admin.get-task-schedules');
    });
    // Task Schedule Metrics
    Route::controller(TaskScheduleMetricsController::class)->group(function() {
        Route::get('/task-schedule-metrics', 'index')->name('admin.task-schedule-metrics');
        Route::post('/task-schedule-metrics/store', 'store')->name('admin.store-task-metrics');
        Route::get('/task-schedule-metrics/{id}', 'show')->name('admin.show-task-schedule-metric');
        Route::post('/task-schedule-metrics/{id}', 'update')->name('admin.update-task-schedule-metric');
        Route::delete('/task-schedule-metrics/{id}', 'destroy')->name('admin.delete-task-schedule-metric');
        Route::get('/get-task-schedule-metrics/{taskId}', 'getMetricsByTask')->name('admin.get-task-schedule-metrics');
    });


    // Equipment Log
    Route::controller(EquipmentLogController::class)->group(function() {
        Route::get('/equipment-logs', 'index')->name('admin.equipment-logs');
        Route::post('/equipment-logs', 'store')->name('admin.store-equipment-log');
        Route::get('/equipment-logs/{id}', 'show')->name('admin.show-equipment-log');
        Route::put('/equipment-logs/{id}', 'update')->name('admin.update-equipment-log');
        Route::delete('/equipment-logs/{id}', 'destroy')->name('admin.delete-equipment-log');
    });
    // Equipment Type
    Route::controller(EquipmentTypeController::class)->group(function() {
        Route::get('/equipment-types', 'index')->name('admin.equipment-types');
        Route::post('/equipment-types', 'store')->name('admin.store-equipment-type');
        Route::get('/equipment-types/{id}', 'show')->name('admin.show-equipment-type');
        Route::put('/equipment-types/{id}', 'update')->name('admin.update-equipment-type');
        Route::delete('/equipment-types/{id}', 'destroy')->name('admin.delete-equipment-type');
    });

    // Water Sources Details Route
    Route::controller(WaterSourceDetailsController::class)->group(function() {
        Route::post('/store-water-source-details', 'store')->name('admin.store-water-source-details');
    });
    // Water Usage Logs Route
    Route::controller(WaterUsageLogsController::class)->group(function() {
    Route::post('/store-water-usage-logs', 'store')->name('admin.store-water-usage-logs');
    });
    // Water Recycling Logs Route
    Route::controller(WaterRecyclingLogsController::class)->group(function() {
        Route::post('/store-water-recycling-logs', 'store')->name('admin.store-water-recycling-logs');
    });
    // Water Quality Logs Route
    Route::controller(WaterQualityLogsController::class)->group(function() {
        Route::post('/store-water-quality-logs', 'store')->name('admin.store-water-quality-logs');
        Route::post('/remove-water-quality-logs', 'store')->name('admin.remove-water-quality-logs');
        Route::get('/water-quality-logs', 'index')->name('admin.water-quality-logs');
        Route::get('/water-quality-logs/{id}', 'show')->name('admin.show-water-quality-log');
        Route::put('/water-quality-logs/{id}', 'update')->name('admin.update-water-quality-log');
        Route::delete('/water-quality-logs/{id}', 'destroy')->name('admin.delete-water-quality-log');
    });
    // Waste Disposal Route
    Route::controller(WasteDisposalController::class)->group(function() {
        Route::post('/store-waste-disposal', 'store')->name('admin.store-waste-disposal');
    });

    // Quality Control
    Route::controller(QualityControlController::class)->group(function() {
        Route::post('/quality-control/store', 'store')->name('admin.store-quality-control');
    });

    Route::controller(CompanyMaterialController::class)->group(function(){
        Route::post('/company/material-setup', 'store')->name('admin.save-company-material');
        Route::post('/company/material-setup/price', 'store_price')->name('admin.save-company-material-price');
        Route::get('/material-view/{material}', 'show')->name('admin.view-material');
        Route::get('/get-materials/{value}', 'getCompanyMaterials');
    });

    // Category
    Route::controller(CategoryController::class)->group(function(){
        Route::get ('/create-category', 'create')->name('admin.create-category');
        Route::post ('/store-category', 'store')->name('admin.store-category');
        Route::delete('/delete-category/{id}', 'destroy')->name('admin.delete-category');
        Route::post('/update-category/{id}', 'update')->name('admin.update-category');
    });

    
    // Chemical Stock Movement
    Route::controller(ChemicalStockMovementController::class)->group(function(){
        Route::post('/company/chemical-setup/check-in', 'store_chemical_checkin')->name('admin.save-company-chemical-check-in');
        Route::post('/company/chemical-setup/check-out', 'store_chemical_checkout')->name('admin.save-company-chemical-check-out');
        Route::get('/chemical-stock-analysis', 'getChemicalStockAnalysis')->name('admin.chemical-stock-analysis');
    });

    // Chemicals
    Route::controller(ChemicalsController::class)->group(function() {
        Route::get('/chemicals', 'create')->name('admin.create-chemical');
        Route::post('/chemicals/store', 'store')->name('admin.store-chemical');
        Route::get('/chemicals/{id}', 'show')->name('admin.show-chemical');
        Route::put('/chemicals/{id}', 'update')->name('admin.update-chemical');
        Route::delete('/chemicals/{id}', 'destroy')->name('admin.delete-chemical');
    });

    Route::controller(CompanyChemicalController::class)->group(function() {
        Route::post('/save-company-chemical', 'store_company_chemical')->name('admin.store-company-chemical');
        Route::get('/chemical-view/{chemical}', 'show')->name('admin.view-chemical');
        Route::post('/company/chemical-setup/price', 'store_price')->name('admin.save-company-chemical-price');
        Route::get('/get-chemicals/{value}', 'getCompanyChemicals');
        Route::post('/update-chemical-details', 'updateChemicalDetails')->name('admin.update-chemical-details');
        Route::delete('/delete-chemical/{id}', 'deleteChemical')->name('admin.delete-chemical');
    });

    // Company
    Route::controller(CompanyController::class)->group(function() {
        Route::get('/company', 'index')->name('admin.view-company');
        Route::get('/create-company', 'create')->name('admin.create-company');
        Route::post('/save-company', 'store')->name('admin.store-company');
        Route::get('/show-company/{company}', 'show')->name('admin.show-company');
        Route::get('/company/{id}/create-recp', 'create_resp')->name('admin.create-recp');
        Route::post('/save-company-recp', 'store_recp')->name('admin.store-company-recp');
        Route::post('/add-company-policy', 'add_company_policy')->name('admin.add-company-policy');
        Route::post('/remove-company-policy', 'remove_company_policy')->name('admin.remove-company-policy');
        Route::post('/add-company-objective', 'add_company_objective')->name('admin.add-company-objective');
        Route::post('/remove-company-objective', 'remove_company_objective')->name('admin.remove-company-objective');
        Route::post('/company/update', 'updateCompanyDetails')->name('update-company-details');
        Route::post('/company/update-location', 'updateCompanyLocation')->name('update-company-location');
        Route::post('/company/update-contact', 'updateCompanyContact')->name('update-company-contact');
        Route::post('/companies/{company}/update-status', 'updateStatus')->name('update-company-status');
        Route::post('/companies/{company}/update-efficiency', 'updateEfficiency');
        Route::post('/company/toggle-status', 'toggleStatus')->name('company.toggleStatus');
        Route::post('/company/add-question', 'store_question')->name('company.add-question');
        Route::post('/company/add-water-conservation-method', 'store_water_conservation_method')->name('company.add-water-conservation-method');
        Route::post('/company/add-water-sources', 'store_water_sources')->name('company.add-water-sources');
        Route::post('/company/remove-question', 'remove_question')->name('company.remove-question');
        Route::post('/company/remove-water-conservation-method', 'remove_water_conservation_method')->name('company.remove-water-conservation-method');
        Route::post('/company/remove-water-sources', 'remove_water_sources')->name('company.remove-water-sources');
        Route::post('/company/add-water-usage', 'store_water_usage')->name('company.add-water-usage');
        Route::get('/company/{id}',  'display')->name('company.display');
        Route::post('/add-company-objective', 'add_company_objective')->name('admin.add-company-objective');
        Route::get('/company/{id}/create-recp', 'create_resp')->name('admin.create-recp');
        Route::get('/create-company', 'create')->name('admin.create-company');
        Route::get('/show-company/{company}', 'show')->name('admin.show-company');
        Route::get('/company', 'index')->name('admin.view-company');
        Route::post('/remove-company-objective', 'remove_company_objective')->name('admin.remove-company-objective');
        Route::post('/remove-company-policy', 'remove_company_policy')->name('admin.remove-company-policy');
        Route::post('/save-company-recp', 'store_recp')->name('admin.store-company-recp');
        Route::post('/save-company', 'store')->name('admin.store-company');
        Route::get('/companies-data', 'getCompaniesData')->name('admin.companies-data');
        Route::get('/companies-state', [CompanyController::class, 'getCompaniesByState'])->name('admin.companies-state-query');
        Route::get('/count/companies/{industry}', [CompanyController::class, 'countCompaniesByIndustry']);
    });

    // Company Department
    Route::controller(CompanyDepartmentController::class)->group(function() {
        Route::get('/company-departments', 'index')->name('admin.company-departments');
        Route::get('/get-departments/{companyId}', 'getDepartmentsByCompany')->name('admin.get-departments');
        Route::post('/company-departments/store', 'store')->name('admin.store-company-department');
        Route::get('/company-departments/{id}', 'show')->name('admin.show-company-department');
        Route::put('/company-departments/{id}', 'update')->name('admin.update-company-department');
        Route::delete('/company-departments/{id}', 'destroy')->name('admin.delete-company-department');
    });

    // Company Employee
    Route::controller(CompanyEmployeesController::class)->group(function() {
        Route::get('/company-employees/{company}', 'index')->name('admin.company-employees');
        Route::get('/search-employee', 'search')->name('admin.search-employee');
        Route::post('/company-employees/{company}/store', 'store')->name('admin.store-company-employee');
        Route::get('/company-employees/{id}', 'show')->name('admin.show-company-employee');
        Route::put('/company-employees/{employee}', 'update')->name('admin.update-company-employee');
        Route::delete('/company-employees/{employee}', 'destroy')->name('admin.delete-company-employee');
    });

    // Company Material
    Route::controller(CompanyMaterialController::class)->group(function(){
        Route::get('/material-view/{material}', 'show')->name('admin.view-material');
        Route::post('/company/material-setup', 'store')->name('admin.save-company-material');
        Route::post('/company/material-setup/price', 'store_price')->name('admin.save-company-material-price');
    });

    // Company Operation
    Route::controller(CompanyOperationController::class)->group(function(){
        Route::get('/operation', 'index')->name('admin.operation');
        Route::post('/operation/store', 'store')->name('admin.store-operation');
        Route::get('/operation/{id}', 'show')->name('admin.show-operation');
        Route::post('/operation', 'update')->name('admin.update-operation');
        Route::delete('/operation/{id}', 'destroy')->name('admin.delete-operation');
        Route::get('/get-operations/{value}', 'get_operations');
        Route::get('/get-operations-log/{value}', 'get_all_operations');
    });

    // Email Application
    Route::controller(EmailApp::class)->group(function() {
        Route::get ('/email-app', 'index')->name('view-email');
        Route::get ('/fetch-user', 'fetch_users')->name('get-user');
        Route::post ('/save-email', 'store')->name('send-mail');
    });

    // Email Integration
    Route::controller(EmailIntegration::class)->group(function() {
        Route::get ('/email', 'index')->name('email-configuration');
    });

    // Events
Route::controller(EventController::class)->group(function() {
    Route::get('/cms-events', 'index')->name('CMS.event');
});
Route::get('/cms-events', [EventController::class, 'index'])->name('CMS.event');

// Create (GET form)
Route::get('/create-events', [AddEventController::class, 'index'])->name('CMS.add-event');

// Store (POST create)
Route::post('/create-events', [AddEventController::class, 'store'])->name('CMS.store-event');

// Edit (GET form)
Route::get('/events/{event}/edit', [AddEventController::class, 'edit'])->name('CMS.edit-event');

// Update (PUT update)
Route::put('/events/{event}', [AddEventController::class, 'update'])->name('CMS.update-event');

// Delete
Route::delete('/events/{event}', [AddEventController::class, 'destroy'])->name('CMS.delete-event');

// General Settings
Route::controller(generalSetting::class)->group(function() {
    Route::get('/general-setting', 'create')->name('admin.general-setting');
    Route::post('/register-settings', 'store')->name('admin.store-settings');
});

    // Guards
    Route::controller(GuardsController::class)->group(function() {
        Route::post('/guard', 'store')->name('admin.store-guard');
    });
    
    // Inventory Forecasting
    Route::controller(InventoryForecastingController::class)->group(function() {
        Route::get('/inventory-forecasting', 'index')->name('admin.inventory-forecasting');
    });
    // iot devices
    Route::controller(IotDeviceController::class)->group(function() {
        Route::get('/iot-devices', 'index')->name('admin.iot-devices');
        Route::post('/iot-devices/store', 'store')->name('admin.store-iot-device');
        Route::get('/iot-devices/{id}', 'show')->name('admin.show-iot-device');
        Route::put('/iot-devices/{id}', 'update')->name('admin.update-iot-device');
        Route::delete('/iot-devices/{id}', 'destroy')->name('admin.delete-iot-device');
    });

    // Map Report
    Route::controller(MapReport::class)->group(function(){
        Route::get ('/all-companies', 'get_all_companies')->name('admin.get-all-companies');
        Route::get ('/companies-map', 'show_all_companies')->name('admin.show-all-companies');
    });

    // Material
    Route::controller(MaterialController::class)->group(function() {
        Route::get ('/materials', 'index')->name('materials.material');
        Route::post ('/save-material', 'store')->name('admin.store-material');
    });

    // Operation Category
    Route::controller(OperationCategoryController::class)->group(function(){
        Route::get('/operation-category', 'index')->name('admin.operation-category');
        Route::post('/operation-category/store', 'store')->name('admin.store-operation-category');
        Route::get('/operation-category/{id}', 'show')->name('admin.show-operation-category');
        Route::post('/operation-category', 'update')->name('admin.update-operation-category');
        Route::delete('/operation-category/{id}', 'destroy')->name('admin.delete-operation-category');
        Route::get('/get-operation-category/{value}', 'getOperationCategories');
    });

    // Operation Type
    Route::controller(OperationTypeController::class)->group(function(){
        Route::get('/operation-type', 'index')->name('admin.operation-type');
        Route::post('/operation-type/store', 'store')->name('admin.store-operation-type');
        Route::get('/operation-type/{id}', 'show')->name('admin.show-operation-type');
        Route::post('/operation-type', 'update')->name('admin.update-operation-type');
        Route::delete('/operation-type/{operationType}', 'destroy')->name('admin.delete-operation-type');
        Route::get('/get-operation-types/{value}', 'get_operation_types');
    });

    // annual operation log
    Route::controller(AnnualOperationsLogController::class)->group(function() {
        Route::post('/annual-operations-log/store', 'store')->name('admin.store-annual-operation-log');
    });

    // Metadata
    Route::controller(MetadataController::class)->group(function() {
        Route::get('/metadata', 'index')->name('admin.metadata');
        Route::post('/metadata/store', 'store_metadata')->name('admin.store-annual-operation-metadata');
        Route::get('/metadata/{id}', 'show')->name('admin.show-metadata');
        Route::put('/metadata/{id}', 'update')->name('admin.update-metadata');
        Route::delete('/metadata/{id}', 'destroy')->name('admin.delete-metadata');
        Route::get('/metadata/company/{companyId}', 'getMetadataByCompany')->name('admin.metadata-by-company');
    });

    // Activity
    Route::controller(ActivityController::class)->group(function() {
        Route::get('/activity', 'index')->name('admin.activity');
        Route::post('/activity/store', 'store')->name('admin.store-activity');
        Route::get('/activity/{id}', 'show')->name('admin.show-activity');
        Route::put('/activity/{id}', 'update')->name('admin.update-activity');
        Route::delete('/activity/{id}', 'destroy')->name('admin.delete-activity');
        Route::get('/metadata/activities/{metadataId}', 'getActivitiesByMetadata');
    });

    // Permissions
    Route::controller(PermissionsController::class)->group(function() {
        Route::post('/permission', 'store')->name('admin.store-permission');
    });

    // Posts
    Route::controller(PostsController::class)->group(function() {
        Route::get ('/cms-posts', 'index')->name('CMS.posts');
        Route::post ('/save-posts', 'store')->name('admin.store-post');
    });

    // Product
    Route::controller(ProductController::class)->group(function() {
        Route::get('/products', 'index')->name('admin.products');
        Route::get('/products/{id}', 'show')->name('admin.show-product');
        Route::post('/products', 'store')->name('admin.store-product');
        Route::put('/products/{id}', 'update')->name('admin.update-product');
        Route::delete('/products/{id}', 'destroy')->name('admin.delete-product');
        Route::get('/get-product/{value}', 'getProduct');
        Route::get('/products-by-company/{companyId}', 'productsByCompany');
    });

    // Product Category
    Route::controller(ProductCategoryController::class)->group(function() {
        Route::get('/product-categories', 'index')->name('admin.product-categories');
        Route::get('/product-categories/{id}', 'show')->name('admin.show-product-category');
        Route::post('/product-categories', 'store')->name('admin.store-product-category'); 
        Route::put('/product-categories', 'update')->name('admin.update-product-category');
        Route::delete('/product-categories/{id}', 'destroy')->name('admin.delete-product-category');
    });

    // Production Log
    Route::controller(ProductionLogController::class)->group(function() {
        Route::get('/production-logs', 'index')->name('admin.production-logs');
        Route::post('/production-logs', 'store')->name('admin.store-production-log');
        Route::get('/production-logs/{id}', 'show')->name('admin.show-production-log');
        Route::put('/production-logs/{id}', 'update')->name('admin.update-production-log');
        Route::delete('/production-logs/{id}', 'destroy')->name('admin.delete-production-log');
    });

    // Production Report
    Route::controller(ProductionReport::class)->group(function() {
        Route::get('/production-report', 'index')->name('admin.production-report');
        Route::post('/production-report/store', 'store')->name('admin.store-production-report');
        Route::get('/production-report/{id}', 'show')->name('admin.show-production-report');
        Route::put('/production-report/{id}', 'update')->name('admin.update-production-report');
        Route::delete('/production-report/{id}', 'destroy')->name('admin.delete-production-report');
        Route::get('/get-production-data/{selectedCompany}/{selectedYear}', 'create_report');
    });

    // production batch tracking
    Route::controller(ProductionBatchTrackingController::class)->group(function() {
        Route::get('/production-batch-tracking', 'index')->name('admin.production-batch-tracking');
        Route::post('/production-batch-tracking/store', 'store')->name('admin.store-batch-tracking');
        Route::get('/production-batch-tracking/{id}', 'show')->name('admin.show-production-batch-tracking');
        Route::put('/production-batch-tracking/{id}', 'update')->name('admin.update-production-batch-tracking');
        Route::delete('/production-batch-tracking/{id}', 'destroy')->name('admin.delete-production-batch-tracking');
        Route::get('/batch-tracking/company/{companyId}', 'getProductionBatchTrackingByCompany');
        Route::get('/get-production-batch-tracking-data/{selectedCompany}/{selectedYear}', 'create_report');
    });

    // Production Process
    Route::controller(ProductionProcessController::class)->group(function () {
        Route::get('/production-process', 'index')->name('admin.production-process');
        Route::post('/production-process/store', 'store')->name('admin.store-production-process');
        Route::get('/production-process/{id}', 'show')->name('admin.show-production-process');
        Route::post('/production-process/{id}', 'update')->name('admin.update-production-process');
        Route::delete('/production-process/{id}', 'destroy')->name('admin.delete-production-process');
        Route::get('/get-production-process/{batch_id}', 'getProductionProcessByBatch')->name('admin.get-production-processes');
    });

    
    Route::controller(AddPostController::class)->group(function() {
        Route::get ('/cms-Addpost', 'index')->name('CMS.add-post');
    });

    // Real-Time Updates
    Route::controller(RealTimeUpdateController::class)->group(function() {
        Route::get('/real-time-updates', 'index')->name('admin.real-time-updates');
    });

    // RECP
    Route::controller(RECPController::class)->group(function(){
        // Add
        Route::post('/add-area-benefit', 'add_utmost_benefit')->name('admin.add-recp-project');
        Route::post('/add-environmental-benefit', 'add_environmental_benefit')->name('admin.add-recp-environmental');
        Route::post('/add-hazarduous-material', 'add_hazarduous_material')->name('admin.add-hazarduous-material');
        Route::post('/add-house-keeping', 'add_house_keeping')->name('admin.add-house-keeping');
        Route::post('/add-improvement-key-area', 'add_improvement_key_area')->name('admin.add-improvement-key-area');
        Route::post('/add-product-innovation', 'add_product_innovation')->name('admin.add-product-innovation');
        Route::post('/add-product-recovery-measure', 'add_product_recovery_measure')->name('admin.add-product-recovery-measure');
        Route::post('/add-problem-solution', 'add_problem_solution')->name('admin.add-problem-solution');
        Route::post('/add-unit-process', 'add_unit_process')->name('admin.add-unit-process');
        Route::post('/add-waste-disposal-method', 'add_waste_management_method')->name('admin.add-waste-disposal-method');
        Route::post('/add-waste-reduction-measure', 'add_waste_reduction_measure')->name('admin.add-waste-reduction-measure');
        Route::post('/store-recp-status', 'store_recp_status')->name('admin.store-recp-status');
        
        
        // Update
        Route::post('/update-hazarduous-material', 'update_hazarduous_material')->name('admin.update-hazarduous-material');
        Route::post('/update-improvement-key-area', 'update_improvement_key_area')->name('admin.update-improvement-key-area');
        Route::post('/update-problem-summary', 'update_problem')->name('admin.update-problem-summary');
        Route::post('/update-product-innovation', 'update_product_innovation')->name('admin.update-product-innovation');
        Route::post('/update-suggested-solution', 'update_solution')->name('admin.update-suggested-solution');
        Route::post('/update-unit-process', 'update_unit_process')->name('admin.update-unit-process');
        
        // Remove
        Route::post('/remove-area-benefit', 'remove_utmost_benefit')->name('admin.remove-recp-project');
        Route::post('/remove-environmental-benefit', 'remove_environmetal_benefit')->name('admin.remove-recp-environmental');
        Route::post('/remove-hazaduous-material', 'remove_hazarduous_material')->name('admin.remove-hazarduous-material');
        Route::post('/remove-house-keeping', 'remove_house_keeping')->name('admin.remove-house-keeping');
        Route::post('/remove-improvement-key-area', 'remove_improvement_key_area')->name('admin.remove-improvement-key-area');
        Route::post('/remove-product-innovation', 'remove_product_innovation')->name('admin.remove-product-innovation');
        Route::post('/remove-product-recovery-measure', 'remove_product_recovery_measure')->name('admin.remove-product-recovery-measure');
        Route::post('/remove-problem-solution', 'remove_problem_solution')->name('admin.remove-problem-solution');
        Route::post('/remove-unit-process', 'remove_unit_process')->name('admin.remove-unit-process');
        Route::post('/remove-waste-disposal-method', 'remove_waste_management_method')->name('admin.remove-waste-disposal-method');
        Route::post('/remove-waste-reduction-measure', 'remove_waste_reduction_measure')->name('admin.remove-waste-reduction-measure');
    });

    // Inventory Reporting and Analytics
    Route::controller(ReportingAnalyticsController::class)->group(function() {
        Route::get('/reporting-analytics', 'index')->name('admin.reporting-analytics');
        Route::get('/reporting-analytics/stock-performance', 'getStockPerformance')->name('admin.stock-performance');
        Route::get('/reporting-analytics/trading-summary', 'getTradingSummary')->name('admin.trading-summary');
        Route::get('/reporting-analytics/user-activity', 'getUserActivity')->name('admin.user-activity');
    });



    // Roles
    Route::controller(RolesController::class)->group(function(){
        Route::post('/settings/assign_role_has_permission', 'assign_role_permission')->name('admin.update.permission-role');
        Route::post('/settings/fetch-role-permission', 'get_role_permission')->name('admin.fetch.role-permission');
        Route::post('/settings/revoke_role_has_permission', 'revoke_role_permission')->name('admin.revoke.permission-role');
        Route::post('/settings/role-change', 'guard_change')->name('admin.guard-change');
        Route::post('/settings/role', 'store')->name('admin.store-role');
        Route::get('/settings/role', 'index')->name('admin.display-roles');
    });

    // Stock Movement
    Route::controller(StockMovementController::class)->group(function(){
        Route::post('/company/material-setup/check-in', 'store_checkin')->name('admin.save-company-material-check-in');
        Route::post('/company/material-setup/check-out', 'store_checkout')->name('admin.save-company-material-check-out');
        Route::get('/material-stock-analysis', 'MaterialStockAnalysis')->name('admin.material-stock-analysis');
    });

    // Stock Trading
    Route::controller(StockTradingController::class)->group(function() {
        Route::post('/stock-trading/buy', 'buyStock')->name('admin.buy-stock');
        Route::post('/stock-trading/sell', 'sellStock')->name('admin.sell-stock');
        Route::get('/stock-trading/history', 'getTradingHistory')->name('admin.trading-history');
        Route::get('/stock-trading', 'index')->name('admin.stock-trading');
    });

    // Tag
    Route::controller(TagController::class)->group(function() {
        Route::get('/tags', 'index')->name('admin.tags');
        Route::get('/search-tag', 'search')->name('admin.search-tag');
        Route::post('/tags', 'store')->name('admin.store-tag');
        Route::get('/tags/{id}', 'show')->name('admin.show-tag');
        Route::put('/tags/{id}', 'update')->name('admin.update-tag');
        Route::delete('/tags/{id}', 'destroy')->name('admin.delete-tag');
    });
    Route::prefix('admin')->middleware('auth:admin')->group(function() {
        // Events & News (CMS)
        Route::controller(EventController::class)->group(function() {
            Route::get('/cms-events', 'index')->name('CMS.event');
        });

        Route::controller(AddEventController::class)->group(function() {
            Route::get('/create-events', 'index')->name('CMS.add-event');
            Route::post('/create-events', 'store')->name('CMS.store-event');
            Route::get('/events/{event}/edit', 'edit')->name('CMS.edit-event');
            Route::put('/events/{event}', 'update')->name('CMS.update-event');
            Route::delete('/events/{event}', 'destroy')->name('CMS.delete-event');
        });
    });


     });
  

    // waste category
    Route::controller(WasteCategoryController::class)->group(function() {
        Route::get('/waste-categories', 'index')->name('admin.waste-categories');
        Route::post('/waste-categories', 'store')->name('admin.store-waste-category');
        Route::get('/waste-categories/{id}', 'show')->name('admin.show-waste-category');
        Route::put('/waste-categories/{id}', 'update')->name('admin.update-waste-category');
        Route::delete('/waste-categories/{id}', 'destroy')->name('admin.delete-waste-category');
        Route::get('/get-waste-categories', 'fetchCategories')->name('admin.get-waste-categories');
    });

    // Waste Subcategory
    Route::controller(WasteSubCategoriesController::class)->group(function() {
        Route::get('/waste-subcategories', 'index')->name('admin.waste-subcategories');
        Route::post('/store-waste-subcategory', 'store')->name('admin.store-waste-subcategory');
        Route::get('/waste-subcategories/{id}', 'show')->name('admin.show-waste-subcategory');
        Route::put('/waste-subcategories/{id}', 'update')->name('admin.update-waste-subcategory');
        Route::delete('/waste-subcategories/{id}', 'destroy')->name('admin.delete-waste-subcategory');
        Route::get('/get-waste-subcategories/{value}', 'getWasteSubCategories');
    });
    // Waste Management
    Route::controller(WasteItemController::class)->group(function() {
        Route::get('/waste-items', 'index')->name('admin.waste-items');
        Route::post('/waste-items', 'store')->name('admin.store-waste-item');
        Route::get('/waste-items/{id}', 'show')->name('admin.show-waste-item');
        Route::put('/waste-items/{id}', 'update')->name('admin.update-waste-item');
        Route::delete('/waste-items/{id}', 'destroy')->name('admin.delete-waste-item');
        Route::get('/get-waste-items', 'data')->name('admin.get-waste-items');

    });

    Route::controller(WasteController::class)->group(function() {
        Route::get('/waste-report', 'index')->name('admin.waste-report');
        Route::post('/waste-report/store', 'store')->name('admin.store-waste-report');
        Route::get('/waste-report/{id}', 'show')->name('admin.show-waste-report');
        Route::put('/waste-report/{id}', 'update')->name('admin.update-waste-report');
        Route::delete('/waste-report/{id}', 'destroy')->name('admin.delete-waste-report');
        Route::get('/get-waste-report-data/{selectedCompany}/{selectedYear}', 'create_report');
        Route::get('/get-waste-items', 'getAll')->name('admin.get-waste-items');
    });
    Route::resource('waste', WasteController::class)->names('admin.waste');

    // Water Stock Movement
    Route::controller(WaterStockMovementController::class)->group(function() {
        Route::post('/water-stock/check-in', 'store_water_checkin')->name('admin.water-stock-check-in');
        Route::post('/water-stock/check-out', 'store_water_checkout')->name('admin.water-stock-check-out');
        Route::post('/water-stock/recycling-log', 'store_water_recycling_log')->name('admin.water-stock-recycling-log');
        Route::get('/water-stock-analysis', 'getWaterStockAnalysis')->name('admin.water-stock-analysis');
    });

    // Team Member
    Route::controller(TeamMemberController::class)->group(function(){
        Route::get ('/team-member', 'index')->name('admin.team-member');
    });

    // Users Management
    Route::controller(UsersManagementController::class)->group(function(){
        Route::post('/view-users',  'store')->name('view.details');
        Route::get('/users-details',  'getAllUsers')->name('users.details');
        Route::get('/users-management', 'show_usersmanagement')->name('admin.users-management');
    });

    // View Email
    Route::get('/notifications/{id}', [ViewEmailController::class, 'show'])->name('notifications.show');
    Route::get('/show-email/{email}', [ViewEmailController::class, 'index'])->name('admin.show-email');
    
;