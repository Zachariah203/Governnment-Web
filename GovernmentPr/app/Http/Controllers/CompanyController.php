<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyObjectives;
use App\Models\RECPHistory;
use App\Models\RECP_areas_of_benefit;
use App\Models\RECP_human_and_environmental_health_benefit;
use App\Models\RECP_innovation_areas;
use App\Models\RECP_areas_of_improvement;
use App\Models\RECP_harzardous_materials;
use App\Models\RECP_house_keep_practice;
use App\Models\RECP_unit_of_process;
use App\Models\RECP_problem_and_solution;
use App\Models\RECP_waste_management_method;
use App\Models\RECP_waste_reduction_measure;
use App\Models\RECP_product_recovery_method;
use App\Models\CompanyPolicy;
use App\Models\Objectives;
use App\Models\Material;
use App\Models\WaterQuestionaire;
use App\Models\CompanyWaterQuestion;
use App\Models\WaterConservationMethod;
use App\Models\CompanyWaterConservationOpportunity;
use App\Models\WaterSources;
use App\Models\WaterSourceDetails;
use App\Models\CompanyWaterSources;
use App\Models\CompanyMaterial;
use App\Models\Chemicals;
use App\Models\ChemicalUsage;
use App\Models\CompanyChemical;
use App\Models\Admins;
use App\Models\company_water_usage;
use App\Models\OperationCategory;
use App\Models\OperationType;
use App\Models\CompanyOperation;
use App\Models\CalendarYear;
use App\Models\CompanyWaste;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Policies;
use App\Models\EquipmentType;
use App\Models\EquipmentLog;
use App\Models\User;
use App\Models\recp;
use App\Models\WasteDisposal;
use App\Models\WaterStockMovement;
use App\Models\WaterQualityLogs;
use App\Models\CompanyWorkflow;
use App\Models\AnnualOperationsLog;
use App\Models\ProductionLog;
use App\Models\QualityControl;
use App\Models\HRMS\CompanyDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class CompanyController extends WaterStockMovementController
{
    public function show($company)
    {
        $companyID = decrypt($company);

        // Fetch company details
        $data['company'] = Company::find($companyID);

        // Fetch related data
        $data['policies'] = Policies::where('status', 'active')->get(['policy_id', 'title']);
        $data['objectives']= Objectives::where('status', 'active')->get(['objective_id', 'name']);
        $data['company_policies'] = CompanyPolicy::where('companyID', $companyID)->get();
        $data['company_objectives'] = CompanyObjectives::where('companyID', $companyID)->get();
        $data['company_benefits'] = RECP_areas_of_benefit::active()->where('companyID', $companyID)->get();
        $data['company_enviromental_benefits'] = RECP_human_and_environmental_health_benefit::active()->where('companyID', $companyID)->get();
        $data['company_house_keeping'] = RECP_house_keep_practice::active()->where('companyID', $companyID)->get();
        $data['company_waste_reduction_measures'] = RECP_waste_reduction_measure::active()->where('companyID', $companyID)->get();
        $data['company_management_measures'] = RECP_waste_management_method::active()->where('companyID', $companyID)->get();
        $data['company_product_recovery_measures'] = RECP_product_recovery_method::active()->where('companyID', $companyID)->get();
        $data['company_areas_of_improvement'] = RECP_areas_of_improvement::active()->where('companyID', $companyID)->select('improvementAreaID', 'area_title')->get();
        $data['company_product_innovation'] = RECP_innovation_areas::active()->where('companyID', $companyID)->select('innovationAreaID', 'innovation_area_title')->get();
        $data['company_hazarduous_material'] = RECP_harzardous_materials::active()->where('companyID', $companyID)->select('hazarduousMaterialID', 'material_title')->get();
        $data['company_unit_process'] = RECP_unit_of_process::active()->where('companyID', $companyID)->select('unitProcessID', 'unit_process_title')->get();
        $data['company_problems_and_solutions'] = RECP_problem_and_solution::active()->where('companyID', $companyID)->select('problemSolutionID', 'problem_title', 'solution_title')->get();
        $data['recp_state'] = recp::where('company_id', $companyID)->first(['recp_id', 'status', 'remark']);

        // Fetch materials
        $data['materials'] = Material::active()->select('materialID', 'material')->get();
        $data['companyMaterials'] = CompanyMaterial::where('companyID', $companyID)
            ->join('materials', 'materials.materialID', '=', 'company_materials.materialID')
            ->select('materials.materialID as material_id', 'company_materials.*', 'materials.material')
            ->get();

        // Fetch water-related data
        $data['waterQuestions'] = WaterQuestionaire::active()->get(['questionId', 'label', 'question']);
        $data['CompanyWaterQuestions'] = CompanyWaterQuestion::where('companyID', $companyID)->get(['questionID']);
        $data['WaterConservationMethod'] = WaterConservationMethod::active()->get(['WaterConservationMethodId', 'label', 'method']);
        $data['companyWaterConservationMethod'] = CompanyWaterConservationOpportunity::where('companyID', $companyID)->get(['conservation_id']);
        $data['WaterSources'] = WaterSources::active()->get(['WaterSourcesId', 'label', 'sources']);
        $data['companyWaterSources'] = CompanyWaterSources::where('companyID', $companyID)->get(['WaterSources_id', 'CompanyWaterSourcesID']);
        $data['company_water_usage'] = company_water_usage::where('companyID', $companyID)->get(['companyWaterUsageID', 'volume', 'date_type', 'date', 'remark']);
        $data['water_stock_movements'] = WaterStockMovement::where('company_id', $companyID)->get(['waterStockID', 'water_source_id', 'movement_type', 'volume', 'calendar_year_id', 'movement_date', 'remark', 'status']);

        // Fetch chemical inventory
        $data['approved_chemicals'] = Chemicals::active()->get(['chemical_id', 'name']);
        $data['company_chemicals'] = CompanyChemical::active()->where('company_id', $companyID)->get();
        $data['approved_company_chemicals'] = CompanyChemical::active()->where('company_id', $companyID)->get(['chemical_id', 'unit', 'status', 'company_chemical_id']);

        // Fetch water inventory
        $data['availableWaterBalance'] = $this->getWaterBalance($companyID);
        $data['availableWaterInflowBalance'] = $this->getTotalCheckIn($companyID);
        $data['availableWaterOutflowBalance'] = $this->getTotalCheckOut($companyID);
        $data['availableWaterRecycleBalance'] = $this->getTotalRecycle($companyID);
        // Fetch operations

        // Fetch calendar years
        $data['calendar_years'] = CalendarYear::where('company_id', $companyID)->get(['calendar_year_id', 'name', 'start_date', 'end_date', 'is_active']);
        $data['active_calendar_years'] = CalendarYear::active()->where('company_id', $companyID)->get(['calendar_year_id', 'name', 'start_date', 'end_date', 'is_active']);

        // Fetch waste and products
        $data['waste_items'] = CompanyWaste::where('company_id', $companyID)->get();
        $data['product_categories'] = ProductCategory::where('company_id', $companyID)->get(['product_category_id', 'name']);
        $data['active_product_categories'] = ProductCategory::active()->where('company_id', $companyID)->get(['product_category_id', 'name']);
        $data['products'] = Product::where('company_id', $companyID)->get();
        $data['active_products'] = Product::active()->where('company_id', $companyID)->get();

        // Fetch equipment
        $data['active_equipment_types'] = EquipmentType::active()->where('company_id', $companyID)->get(['equipment_type_id', 'name']);
        $data['industrial_equipments'] = EquipmentLog::where('company_id', $companyID)->get();

        // Fetch water source details
        $data['water_source_details'] = WaterSourceDetails::where('companyID', $companyID)->get();

        // Fetch waste disposal and quality logs
        $data['waste_disposals'] = WasteDisposal::where('company_id', $companyID)->get();
        $data['water_quality_logs'] = WaterQualityLogs::where('companyID', $companyID)->get();
        $data['annual_operations_logs'] = AnnualOperationsLog::where('company_id', $companyID)->get();
        $data['production_logs'] = ProductionLog::with(['company', 'companyOperation', 'calendarYear'])->where('company_id', $companyID)->get();
        $data['quality_controls_record'] = QualityControl::where('company_id', $companyID)->get();
        // fetch company departments
        $data['company_departments'] = CompanyDepartment::withCount(['employees'])->where('CompanyID', $companyID)->get(['DepartmentID', 'DepartmentName', 'ManagerIDs'])
        ->map(function($dept) {
            return [
                'DepartmentID' => $dept->DepartmentID,
                'DepartmentName' => $dept->DepartmentName,
                'ManagerIDs' => $dept->ManagerIDs,
                'employee_count' => $dept->employees_count,
            ];
        });
        //fetch company workflows
        $data['company_workflows'] = CompanyWorkflow::where('company_id', $companyID)->get(['workflow_id', 'workflow_name']);
        //fetch all states
        $data['regions'] = Company::getAllRegions();
        $data['incomes'] = [];
        $data['expenses'] = [];
        $data['trainings'] = [];
        $data['performances'] = [];
        $data['welfarePrograms'] = [];
        $data['assets'] = [];
        $data['equipmentList'] = [];
        $data['stocks'] = [];
        $data['generalItems'] = [];
        $data['chemicalItems'] = [];
        $data['waterRecords'] = [];
        $data['rawMaterials'] = [];
        $data['productions'] = [];
        $data['logistics'] = [];
        $data['quality_checks'] = [];
        $data['wastes'] = [];
        $data['operation_years'] = [];
        $data['quality_controls'] = [];
        $data['maintenances'] = [];
        $data['batches'] = [];
        $data['annualPlans'] = [];
        $data['chemicalUsage'] = [];
        $data['years'] = [];
        $data['transportRecords'] = [];
        $data['topVehicles'] = [];
        $data['supplyChain'] = [];
        $data['inspections'] = [];
        $data['defects'] = [];
        $data['actions'] = [];
        $data['batchWastes'] = [];
        $data['disposals'] = [];
        $data['wasteCategories'] = [];
        $data['wasteSubCategories'] = [];
        $data['reportData'] = [];
        $data['processes'] = [];


        return view('components.apps.companyProfile', $data);
    }   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // update company personal details
    public function updateCompanyDetails(Request $request)
    {
        // Extract the company ID from the request
        $companyId = $request->input('company_id');

    
        // Validation rules
        $companyId = $request->company_id;
        $validator = Validator::make($request->all(), [
            'company_id' => ['required', 'numeric'],
            'company_name' => ['required', 'string', 'max:255', Rule::unique('companies', 'company_name')->where(function ($query) use ($request) {
                    return $query->where('company_id', $request['company_id']);
                })->ignore($companyId, 'company_id')
            ],
            'industry' => ['required', 'string', 'max:255'],
            'industry_process' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('companies', 'email')->ignore($companyId, 'company_id')],
            'website_address' => ['nullable', 'url'],
            'primary_phone_number' => ['required', 'string', 'max:15'],
            'secondary_phone_number' => ['nullable', 'string', 'max:15'],
            'number_of_employees' => ['nullable', 'integer'],
            'establishment_date' => ['nullable', 'date'],
        ]);
    
        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }
    
       
        $result = Company::where('company_id', $companyId)->where('status', 'active ')->update([
            'company_name' => $request->input('company_name'),
            'industry' => $request->input('industry'),
            'industry_process' => $request->input('industrial_process'),
            'email' => $request->input('email'),
            'website_url' => $request->input('website_address'),
            'primary_phone_number' => $request->input('primary_phone_number'),
            'secondary_phone_number' => $request->input('secondary_phone_number'),
            'number_of_employees' => $request->input('number_of_employees'),
            'date_of_establishment' => $request->input('establishment_date'),
        ]);
    
        // Return success or error response
        if ($result) {
            $companiesInfo = Company::where('status', '=', 'active')
            ->where('company_id', $companyId)
            ->select('company_name', 'industry', 'industry_process', 'email', 'website_url', 'primary_phone_number','secondary_phone_number', 'number_of_employees', 'date_of_establishment')->first();
            return response()->json([
                'status' => 'success',
                'message' => 'Company details updated successfully.',
                'companies_info' => $companiesInfo
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update company details.',
            ], 400);
        }
    }
    // end update company personal details

    // update company location
    public function updateCompanyLocation(Request $request)
    {
        // Extract the company ID from the request
        $companyId = $request->input('company_id');
    
        // Validation rules
        $companyId = $request->company_id;
        $validator = Validator::make($request->all(), [
            'company_id' => ['required', 'numeric'],
            'country' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'address' => ['required'],
            'zip_code' => ['nullable', 'string'],
            'longitude' => ['nullable', 'numeric'],
            'latitude' => ['nullable', 'numeric'],
            'mgrs' => ['nullable', 'string'],
        ]);
    
        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }
    
       
        $result = Company::where('company_id', $companyId)->where('status', 'active ')->update([
            'country' => $request->input('country'),
            'state' => $request->input('state'),
            'city' => $request->input('city'),
            'address' => $request->input('address'),
            'zip_code' => $request['zip_code'],
            'longitude' => $request['longitude'],
            'latitude' => $request['latitude'],
            'mgrs' => $request['mgrs'],
           
        ]);
    
        // Return success or error response
        if ($result) {
            $companiesInfo = Company::where('status', '=', 'active')
            ->where('company_id', $companyId)
            ->select('country', 'state', 'city', 'address', 'zip_code', 'latitude', 'longitude', 'mgrs')->first();
            return response()->json([
                'status' => 'success',
                'message' => 'Company Location updated successfully.',
                'companies_info' => $companiesInfo
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update company location.',
            ], 400);
        }
    }
    // end update company location
    // update contact personnel
    public function updateCompanyContact(Request $request)
    {
        // Extract the company ID from the request
        $companyId = $request->input('company_id');
    
        // Validation rules
        $companyId = $request->company_id;
        $validator = Validator::make($request->all(), [
            'company_id' => ['required', 'numeric'],
            'enviromental_operations_manager' => ['required', 'string', 'max:255'],
            'contact_person_name' => ['required', 'string', 'max:255'],
            'contact_person_position' => ['required', 'string', 'max:255'],
            'contact_person_phone_number' => ['required'],
           
        ]);
    
        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }
    
       
        $result = Company::where('company_id', $companyId)->where('status', 'active ')->update([
            'operations_manager' => $request->input('enviromental_operations_manager'),
            'contact_person_full_name' => $request->input('contact_person_name'),
            'contact_person_position' => $request->input('contact_person_position'),
            'contact_person_contact_number' => $request->input('address'),
        ]);
    
        // Return success or error response
        if ($result) {
            // $companiesInfo = Company::where('status', '=', 'active')
            // ->where('company_id', $companyId)
            // ->select('company_name', 'industry', 'industry_process', 'email', 'website_url', 'primary_phone_number','secondary_phone_number', 'number_of_employees', 'date_of_establishment')->first();
            return response()->json([
                'status' => 'success',
                'message' => 'Contact Personnel updated successfully.',
                // 'companies_info' => $companiesInfo
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update contact personnel.',
            ], 400);
        }
    }
    // end update company contact personnel
    // activate company start
    public function toggleStatus(Request $request)
    {
        $company = Company::find($request->company_id);
    
        if ($company) {
            // Toggle the status based on the checkbox value
            $company->status = $request->status ? 'active' : 'inactive';
            $company->save();
    
            // Return a success response
            return response()->json([
                'success' => true,
                'message' => $company->status === 'active' ? 'Company activated!' : 'Company deactivated!'
            ]);
        }
    
        return response()->json(['success' => false, 'message' => 'Company not found'], 404);
    }
    // end activation and deactivation

    // company 404
    public function dispaly($id)
    {
        $company = Company::find($id);
    
        // Check if the company is active
        if (!$company || $company->status !== 'active') {
            abort(404, 'Company has been deactivated.');
        }
    
        return view('displayCompany', compact('company'));
    }

    public function getCompaniesData()
    {
        $data['pageTitle'] = 'Advance Companies Overview';
        $data['companies'] = Company::with(['companyMaterials', 'companyChemicals', 'stock_movements'])
            ->where('status', 'active')
            ->get([
            'company_id',
            'company_name',
            'industry',
            'industry_process',
            'email',
            'primary_phone_number',
            'secondary_phone_number',
            'country',
            'state',
            'city',
            'address',
            'zip_code',
            'longitude',
            'latitude',
            'mgrs',
            'website_url',
            'date_of_establishment',
            'number_of_employees',
            'operations_manager',
            'contact_person_full_name',
            'contact_person_position',
            'contact_person_contact_number',
            'is_sharable',
            'status',
            'created_at',
            'updated_at'
            ]);
        // $data = $data['companies']->get();
        $data['all_materials'] = Material::get(['materialID', 'material']);
        $data['all_water_questions'] = WaterQuestionaire::get(['questionId', 'label', 'question']);
        $data['all_water_conservation_methods'] = WaterConservationMethod::get(['WaterConservationMethodId', 'label', 'method']);
        $data['all_water_sources'] = WaterSources::get(['WaterSourcesId', 'sources']);
        $data['all_chemicals'] = Chemicals::get(['chemical_id', 'name']);
        $data['policies'] = Policies::get(["policy_id", "title"]);
        $data['objectives'] = Objectives::get(["objective_id", "name"]);

        $data['total_materials'] = Material::count();
        $data['total_water_sources'] = WaterSources::count();
        $data['total_water_questions'] = WaterQuestionaire::count();
        $data['total_water_conservation_methods'] = WaterConservationMethod::count();
        $data['total_chemicals'] = Chemicals::count();
        return view('components.reportinganalytics.companies-report', $data);
    }

    public function getCompaniesByState(Request $request)
    {
        $country = $request->input('country');
        // dd($country);
        if (!$country) {
            return response()->json([
                'status' => 'error',
                'message' => 'Country is required.'
            ], 400);
        }

        $companiesByState = Company::where('country', $country)
            ->select('state', \DB::raw('COUNT(*) as company_count'))
            ->groupBy('state')
            ->get();
        // dd($companiesByState);
        return response()->json([
            'status' => 'success',
            'companies' => $companiesByState
        ]);
    }
    
    // company 404
    public function index()
    {
        //
        $data['companies'] = Company::where('status', '=', 'active')->with(['companyProducts'])->get();
        // dd($data['companies']);
        return view('components.apps.displayCompany', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $data['usersList'] = User::where('status','active')->select('id','first_name','last_name')->get();
        $data['pageTitle'] = 'Create New Company';
        $data['policies'] = Policies::where('status', 'active')->get(['policy_id', 'title']);
        $data['objectives'] = Objectives::where('status', 'active')->get(['objective_id', 'name']);
        return view('components.apps.create-company', $data);
    }
    
    public function create_resp($id)
    {
        //
        $id = decrypt($id);
        return view('components.apps.create-recp')->with(['companyID' => $id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // dd($request);
        $request->validate([
            'company_name' => ['required', 'string', 'min:3', 'max:225'],
            'industry' => ['required', 'string', 'min:3', 'max:225'],
            'industry_process_used' => ['nullable', 'string', 'min:3', 'max:225'],
            'email' => ['nullable', 'string', 'min:3', 'max:225'],
            'website_address' => ['nullable', 'url'],
            'primary_phone_number' => ['required', 'numeric', 'regex:/^(\+?[1-9][0-9]{1,14})$/', 'phone:*'],
            'secondary_phone_number' => ['nullable', 'numeric', 'regex:/^(\+?[1-9][0-9]{1,14})$/', 'phone:*'],
            'number_of_employees' => ['required', 'numeric'],
            'date_of_establishment' => ['required', 'date', 'before:now'],
            'country' => ['required', 'string'],
            'state' => ['required', 'string'],
            'city' => ['required', 'string'],
            'address' => ['required', 'string'],
            'zip_code' => ['nullable', 'string'],
            'longitude' => ['nullable', 'numeric'],
            'latitude' => ['nullable', 'numeric'],
            'mgrs' => ['nullable', 'string'],
            'policy' => ['nullable', 'array'],
            'policy.*' => ['string'],
            'objective' => ['nullable', 'array'],
            'objective.*' => ['string'],
            'enviromental_operations_manager' => ['nullable', 'string', 'min:3', 'max:225'],
            'contact_person_name' => ['nullable', 'string', 'min:3', 'max:225'],
            'contact_person_position' => ['nullable', 'string', 'min:3', 'max:225'],
            'contact_person_phone_number' => ['nullable', 'numeric', 'regex:/^(\+?[1-9][0-9]{1,14})$/', 'phone:*'],
            'is_sherable' => ['nullable', 'string']
        ]);

        // company eloquent save
        $company = Company::create([
            'company_name' => $request['company_name'],
            'industry' => $request['industry'],
            'industry_process' => $request['industry_process_used'],
            'email' => $request['email'],
            'primary_phone_number' => $request['primary_phone_number'],
            'secondary_phone_number' => $request['secondary_phone_number'],
            'country' => $request['country'],
            'state' => $request['state'],
            'city' => $request['city'],
            'address' => $request['address'],
            'zip_code' => $request['zip_code'],
            'longitude' => $request['longitude'],
            'latitude' => $request['latitude'],
            'mgrs' => $request['mgrs'],
            'website_url' => $request['website_address'],
            'date_of_establishment' => $request['date_of_establishment'],
            'number_of_employees' => $request['number_of_employees'],
            'operations_manager' => $request['enviromental_operations_manager'],
            'contact_person_full_name' => $request['contact_person_name'],
            'contact_person_position' => $request['contact_person_position'],
            'contact_person_contact_number' => $request['contact_person_phone_number'],
            'is_sharable' => ($request['is_sherable'])? $request['is_sherable']:"inactive",
        ]);

        if ($company) {
            # code...
            if (isset($request['policy'])) {
        foreach ($request['policy'] as $policyID) {
            CompanyPolicy::create([
                'companyID'  => $company->company_id,
                'policy_id'  => $policyID,
            ]);
        }
    }


            if (isset($request['objective'])) {
                # code...
                
            foreach ($request['objective'] as $key => $objective) {
                # code...
                $objectiveModel = CompanyObjectives::create([
                    'companyID' => $company->company_id,
                    'objective_id' => $objective
                ]);
            }
            }
        }

        return back()->with(['success' => 'Company registered successfully']);
    }

    public function store_recp(Request $request) {
        // dd($request);
        $validator = $request->validate([
            'company_id'                        => ['required', 'numeric', 'unique:recp_histories,companyID'],
            'areas_of_company_benefit'          => ['nullable', 'array'],
            'areas_of_company_benefit.*'        => ['nullable', 'string'],
            'environment_health_benefit'        => ['nullable', 'array'],
            'environment_health_benefit.*'      => ['nullable', 'string'],
            'key_area_for_improvent'            => ['nullable', 'array'],
            'key_area_for_improvent.*'          => ['nullable', 'string'],
            'innovation_that_enhance_product'   => ['nullable', 'array'],
            'innovation_that_enhance_product.*' => ['nullable', 'string'],
            'hazardous_material_in_process'     => ['nullable', 'array'],
            'hazardous_material_in_process.*'   => ['nullable', 'string'],
            'house_keeping'                     => ['nullable', 'array'],
            'house_keeping.*'                   => ['nullable', 'string'],
            'unit_process'                      => ['nullable', 'array'],
            'unit_process.*'                    => ['nullable', 'string'],
            'problem_and_solution'              => ['nullable', 'array'],
            'problem_and_solution.*'            => ['nullable', 'string'],
            'RECP_waste_reduction_measures'     => ['nullable', 'array'],
            'RECP_waste_reduction_measures.*'   => ['nullable', 'string'],
            'waste_management_methods'          => ['nullable', 'array'],
            'waste_management_methods.*'        => ['nullable', 'string'],
            'product_recovery_measures'         => ['nullable', 'array'],
            'product_recovery_measures.*'       => ['nullable', 'string'],
        ], [
            'company_id.unique'=> 'Company already registered for RECP.'
        ]);

        $check = false;

        // Areas of company benefit
        if(is_array($request['areas_of_company_benefit']) && isset($request['company_id'])){
            foreach ($request['areas_of_company_benefit'] as $key => $benefit) {
                # code...
                if ($benefit != null) {
                    # code...
                    $RECP_areas_of_benefit = 
                    RECP_areas_of_benefit::create([
                        'companyID' => $request['company_id'],
                        'benefit_title' => $benefit
                    ]);
                    $check = true;
                }

            }
        }

        // Environment and health benefit
        if(is_array($request['environment_health_benefit']) && isset($request['company_id'])){
            foreach ($request['environment_health_benefit'] as $key => $healthBenefit) {
                # code...
                if($healthBenefit != null){
                    $RECP_environment_health_benefit = 
                    RECP_human_and_environmental_health_benefit::create([
                        'companyID' => $request['company_id'],
                        'enviromental_benefit_title' => $healthBenefit
                    ]);
                    $check = true;
                }

            }
        }

        // Key area of innovation
        if(is_array($request['innovation_that_enhance_product']) != null && isset($request['company_id'])){
            foreach ($request['innovation_that_enhance_product'] as $key => $innovation) {
                # code...
                if ($innovation != null) {
                    # code...
                    // dd($innovation);
                    $RECP_areas_of_innovation = RECP_innovation_areas::create([
                        'companyID' => $request['company_id'],
                        'innovation_area_title' => $innovation
                    ]);
    
                    $check = true;
                }

            }
        }

        // Key area of improvement
        if(is_array($request['key_area_for_improvent']) && isset($request['company_id'])){
            
            foreach ($request['key_area_for_improvent'] as $key => $improvement) {
                # code...
                if ($improvement != null) {
                    # code...
                    $RECP_areas_of_improvement = RECP_areas_of_improvement::create([
                        'companyID' => $request['company_id'],
                        'area_title' => $improvement
                    ]);
                    $check = true;
                }
            }
        }

        // Key area of hazarduous materials
        if(isset($request['hazardous_material_in_process']) && isset($request['company_id'])){
            foreach ($request['hazardous_material_in_process'] as $key => $harzaduous_material) {
                # code...
                if ($harzaduous_material != null) {
                    # code...
                    $RECP_areas_of_hazarduous_material = 
                    RECP_harzardous_materials::create([
                        'companyID' => $request['company_id'],
                        'material_title' => $harzaduous_material
                    ]);
    
                    $check = true;
                }

            }
        }

        // good housekeeping practice
        if(is_array($request['house_keeping']) && isset($request['company_id'])){
            foreach ($request['house_keeping'] as $key => $practice) {
                # code...
                if ($practice != null) {
                    # code...
                    $RECP_house_keeping_practice = 
                    RECP_house_keep_practice::create([
                        'companyID' => $request['company_id'],
                        'practice_title' => $practice
                    ]);

                    $check = true;
                }

            }
        }

        // Key area of unit process
        if(isset($request['unit_process']) && isset($request['company_id'])){
            foreach ($request['unit_process'] as $key => $unit_process) {
                # code...
                if ($unit_process != null) {
                    # code...
                    $RECP_areas_of_unit_process = RECP_unit_of_process::create([
                        'companyID' => $request['company_id'],
                        'unit_process_title' => $unit_process
                    ]);
                    $check = true;
                }

            }
        }

        // Key area of problem and solution
        if(isset($request['problem_and_solution']) && isset($request['company_id'])){
            foreach ($request['problem_and_solution'] as $key => $problem_solution) {
                # code...
                if ($problem_solution != null) {
                    # code...
                    $RECP_areas_of_problem_solution = RECP_problem_and_solution::create([
                        'companyID' => $request['company_id'],
                        'problem_solution_title' => $problem_solution
                    ]);
                    $check = true;
                }

            }
        }

        // Waste Reduction Measure
        if(isset($request['RECP_waste_reduction_measures']) && isset($request['company_id'])){
            foreach ($request['RECP_waste_reduction_measures'] as $key => $reduction_measures) {
                # code...
                if ($reduction_measures != null) {
                    # code...
                    $RECP_waste_reduction_measure = RECP_waste_reduction_measure::create([
                        'companyID' => $request['company_id'],
                        'waste_reduction_title' => $reduction_measures
                    ]);
                    $check = true;
                }

            }
        }

        // Waste Management Method
        if(isset($request['waste_management_methods']) && isset($request['company_id'])){
            foreach ($request['waste_management_methods'] as $key => $method) {
                # code...
                if ($method != null) {
                    # code...
                    $RECP_waste_management_method = RECP_waste_management_method::create([
                        'companyID' => $request['company_id'],
                        'management_method_title' => $method
                    ]);
                    $check = true;
                }

            }
        }

        // Recovery Measures
        if(isset($request['product_recovery_measures']) && isset($request['company_id'])){
            foreach ($request['product_recovery_measures'] as $key => $measure) {
                # code...
                if ($measure != null) {
                    # code...
                    $RECP_recovery_measures = RECP_product_recovery_method::create([
                        'companyID' => $request['company_id'],
                        'recovery_method_title' => $measure
                    ]);
                    $check = true;
                }
            }
        }

        if ($check == true) {
            # code...
            RECPHistory::create(['companyID' => $request['company_id']]);
            return back()->with(['success' => 'RECP registeration successful.']);
        }

        $error = new MessageBag(['Please fill in the form before submission.']);
        return back()->withErrors($error);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */


    public function add_company_policy(Request $request) {
        $validator =Validator::make($request->all(),[
            'company'   => ['required', 'numeric'],
            'policy'    =>  ['required', Rule::unique('company_policies', 'policy_id')->where(function ($query) use ($request) {
                                return $query->where('companyID', $request['company']);
                            }),]
        ]);
        if ($validator->fails()) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'Validation failed.',
                'errors'    => $validator->errors()
            ]);
        }
        CompanyPolicy::create([
            'companyID'    =>  $request->company,
            'policy_id'  =>  $request->policy
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Policy added successfully.',
            'policy' => $request->policy
        ]);
    }
    public function remove_company_policy(Request $request) {
        $validator =Validator::make($request->all(),[
            'company'   => ['required', 'numeric'],
            'policy'    =>  ['required']
        ]);
        if ($validator->fails()) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'Validation failed.',
                'errors'    => $validator->errors()
            ]);
        }
        CompanyPolicy::where('companyID', $request->company)->where('policy_id',$request->policy)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Policy removed successfully.',
            'policy' => $request->policy
        ]);
    }

    public function add_company_objective(Request $request) {
        $validator =Validator::make($request->all(),[
            'company'   => ['required', 'numeric'],
            'objective'    =>  ['required', Rule::unique('company_objectives', 'objective_id')->where(function ($query) use ($request) {
                                return $query->where('companyID', $request['company']);
                            }),]
        ]);
        if ($validator->fails()) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'Validation failed.',
                'errors'    => $validator->errors()
            ]);
        }
        CompanyObjectives::create([
            'companyID'    =>  $request->company,
            'objective_id'  =>  $request->objective
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Objective added successfully.',
            'objective' => $request->objective
        ]);
    }

    public function remove_company_objective(Request $request) {
        $validator =Validator::make($request->all(),[
            'company'   => ['required', 'numeric'],
            'objective'    =>  ['required']
        ]);
        if ($validator->fails()) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'Validation failed.',
                'errors'    => $validator->errors()
            ]);
        }
        CompanyObjectives::where('companyID', $request->company)->where('objective_id',$request->objective)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Objective removed successfully.',
            'objective' => $request->objective
        ]);
    }

    public function store_question(Request $request) {
        $validator =Validator::make($request->all(),[
            'company'       => ['required', 'numeric'],
            'question_id'   =>  ['required', Rule::unique('company_water_questions', 'questionID')->where(function ($query) use ($request) {
                                return $query->where('companyID', $request['company']);
                            }),]
        ]);
        if ($validator->fails()) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'Validation failed.',
                'errors'    => $validator->errors()
            ]);
        }
        CompanyWaterQuestion::create([
            'companyID'   =>  $request->company,
            'questionID'  =>  $request->question_id
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Question added successfully.',
            'objective' => $request->question_id
        ]);
    }
    public function remove_question(Request $request) {
        $validator =Validator::make($request->all(),[
            'company'   => ['required', 'numeric'],
            'question_id'    =>  ['required']
        ]);
        if ($validator->fails()) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'Validation failed.',
                'errors'    => $validator->errors()
            ]);
        }
        CompanyWaterQuestion::where('companyID', $request->company)->where('questionID',$request->question_id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Question removed successfully.',
            'objective' => $request->question_id
        ]);
    }

    // water conservation
     public function store_water_conservation_method(Request $request) {
        // dd($request);
        $validator =    Validator::make($request->all(),[
            'company'                      => ['required', 'numeric'],
            'water_conservation_method_id'   =>  ['required', Rule::unique('company_water_conservation_opportunities', 'waterConservationMethod_id')->where(function ($query) use ($request) {
                                return $query->where('companyID', $request['company']);
                            }),]
        ]);

        if ($validator->fails()) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'Validation failed.',
                'errors'    => $validator->errors()
            ]);
        }

        CompanyWaterConservationOpportunity::create([
            'companyID'   =>  $request->company,
            'waterConservationMethod_id'  =>  $request->water_conservation_method_id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Water conservation method added successfully.',
            'objective' => $request->water_conservation_method_id
        ]);

    }

    public function remove_water_conservation_method(Request $request) {
        // dd($request);
        $validator =Validator::make($request->all(),[
            'company'                       => ['required', 'numeric'],
            'water_conservation_method_id'    =>  ['required']
        ]);
        if ($validator->fails()) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'Validation failed.',
                'errors'    => $validator->errors()
            ]);
        }
        CompanyWaterConservationOpportunity::where('companyID', $request->company)->where('waterConservationMethod_id',$request->water_conservation_method_id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Water conservation method removed successfully.',
            'objective' => $request->water_conservation_method_id
        ]);
    }

    // water Sources
    public function store_water_sources(Request $request) {
        $validator =Validator::make($request->all(),[
            'company'           => ['required', 'numeric'],
            'water_sources_id'   =>  ['required', Rule::unique('company_water_sources', 'WaterSources_id')->where(function ($query) use ($request) {
                                return $query->where('companyID', $request['company']);
                            }),]
        ]);
        if ($validator->fails()) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'Validation failed.',
                'errors'    => $validator->errors()
            ]);
        }
        CompanyWaterSources::create([
            'companyID'   =>  $request->company,
            'WaterSources_id'  =>  $request->water_sources_id
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Water source added successfully.',
            'objective' => $request->water_sources_id
        ]);
    }
    public function remove_water_sources(Request $request) {
        $validator =Validator::make($request->all(),[
            'company'            => ['required', 'numeric'],
            'water_sources_id'    =>  ['required']
        ]);
        if ($validator->fails()) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'Validation failed.',
                'errors'    => $validator->errors()
            ]);
        }
        CompanyWaterSources::where('companyID', $request->company)->where('WaterSources_id',$request->water_sources_id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Water source removed successfully.',
            'objective' => $request->waterSources_id
        ]);
    }
       
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */

    // water Sources
    public function store_water_usage(Request $request) {
        $validator = Validator::make($request->all(), [
            'company_id'   =>  ['required', 'numeric'],
            'volume'       =>  ['required', 'numeric', 'min:1'],
            'date_type'    =>  ['required', 'string'],
            'date'         =>  ['required'],
            'remark'       =>  ['nullable', 'string', 'min:4'],
        ]);
        if ($validator->fails()) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'Validation failed.',
                'errors'    => $validator->errors()
            ]);
        }
        $result = company_water_usage::create([
            'companyID' =>  $request->company_id,
            'volume'    =>  $request->volume,
            'date_type' =>  $request->date_type,
            'date'      =>  $request->date,
            'remark'    =>  $request->remark,
        ]);

        if ($result) {
            # code...
            $water_usage = company_water_usage::where('companyID', $request->company_id)
            ->get(['companyWaterUsageID', 'volume', 'date_type', 'date', 'remark']);
            return response()->json([
                'status' => 'success',
                'message' => 'Water usage added successfully.',
                'water_usage' => $water_usage
            ]);
        }
    }
    //end store water source

    public function countCompaniesByIndustry($industry) {
        $companyCount = Company::whereRaw('LOWER(industry) = ?', $industry)
            ->where('status', 'active')
            ->count();
        return response()->json([
            'status' => 'success',
            'industry' => $industry,
            'company_count' => $companyCount
        ]);
    }

//    water usage logs
public function store_water_usage_log(Request $request) {
   
}
//end water usage logs
    
    public function updateStatus(Request $request, \App\Models\Company $company)
    {
        $request->validate([
            'status' => 'required|in:approved,pending,disapproved',
        ]);

        $company->status = $request->status;
        $company->save();

        return response()->json(['success' => true]);
    }

    public function updateEfficiency(Request $request, \App\Models\Company $company)
    {
        $request->validate([
            'efficiency' => 'required|numeric|min:0|max:100',
        ]);

        $company->efficiency = $request->efficiency;
        $company->save();

        return response()->json(['success' => true]);
    }


    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        //
    }
}