<x-layouts.admin-app>
    @section('PageTitle', 'Company Profile')

    @section('styles')
    
    <link href="{{asset('adminAssets/libs/simple-datatables/style.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('adminAssets/css/toastify.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.33.0/tagify.min.css">
    <link href="{{asset('adminAssets/libs/huebee/huebee.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('adminAssets/libs/vanillajs-datepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('adminAssets/libs/mobius1-selectr/selectr.min.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .tag-input {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            /* border: 1px solid #ccc; */
            padding: 5px;
            /* border-radius: 8px; */
            cursor: text;
            position: relative;
        }

        .tag-input input {
            border: none;
            outline: none;
            flex: 1;
            min-width: 100px;
        }

        .tag {
            display: flex;
            align-items: center;
            background-color: #e0e7ff;
            color: #1d4ed8;
            border-radius: 16px;
            padding: 5px 10px;
            margin: 5px;
            font-size: 14px;
        }

        .tag span {
            margin-left: 5px;
            cursor: pointer;
        }

        .tag span:hover {
            color: #dc2626;
        }

        .suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            /* border: 1px solid #ccc; */
            border-radius: 4px;
            max-height: 150px;
            overflow-y: auto;
            z-index: 10;
        }

        .TagSuggestion {
            padding: 5px;
            border: 1px solid #ccc;
            cursor: pointer;
        }
        .TagSuggestion:hover {
            background-color: #f0f0f0;
        }

        .suggestion {
            padding: 8px 10px;
            cursor: pointer;
        }

        .suggestion img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }

        .suggestion:hover {
            background-color: #f3f4f6;
        }

        /* Profile card */
        .profile-card {
        width: 250px;
        border: 1px solid #ccc;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        text-align: center;
        }

        .profile-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        }

        .profile-card .profile-info {
        padding: 15px;
        }

        .profile-card .profile-info h2 {
        margin: 10px 0 5px;
        font-size: 18px;
        }

        .profile-card .profile-info p {
        margin: 0;
        color: #666;
        font-size: 14px;
        }
        /* Profile card */
        .taggable-container {
            flex: 1;
            max-width: 400px;
        }

        .supervisor-tag-input {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            /* border: 1px solid #ccc; */
            /* padding: 5px; */
            border-radius: 8px;
            cursor: text;
            position: relative;
            /* background-color: #fff; */
        }

        .supervisor-tag-input input {
            /* border: none;
            outline: none; */
            flex: 1;
            min-width: 100px;
        }

        .manager-tag-input {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            /* border: 1px solid #ccc; */
            /* padding: 5px; */
            border-radius: 8px;
            cursor: text;
            position: relative;
            /* background-color: #fff; */
        }

        .manager-tag-input input {
            /* border: none;
            outline: none; */
            flex: 1;
            min-width: 100px;
        }
    </style>
    <style>
        /* Tag Input Wrapper */
        .tag-inline-container {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            /* gap: 4px;
            padding: 4px 8px; */
            background: #fff;
            border-radius: 0.375rem;
            border: 1px solid #ced4da;
            /* min-height: 38px; */
            position: relative;
            transition: box-shadow 0.2s, border-color 0.2s;
        }
        .tag-inline-container:focus-within {
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
            border-color: #86b7fe;
        }

        /* Tag Styling */
        .task-tag {
            display: inline-flex;
            align-items: center;
            background: #0d6efd;
            color: #fff;
            padding: 4px 12px 4px 10px;
            border-radius: 1rem;
            font-size: 14px;
            margin: 2px 2px 2px 0;
            box-shadow: 0 1px 2px rgba(13,110,253,0.08);
            font-weight: 500;
            cursor: default;
            transition: background 0.2s, box-shadow 0.2s, transform 0.1s;
        }
        .task-tag:hover {
            background: #0b5ed7;
            box-shadow: 0 2px 6px rgba(13,110,253,0.12);
            transform: translateY(-1px) scale(1.04);
        }
        .task-tag span {
            margin-left: 8px;
            cursor: pointer;
            font-weight: bold;
            color: #fff;
            opacity: 0.7;
            transition: opacity 0.15s;
        }
        .task-tag span:hover {
            opacity: 1;
            color: #f87171;
        }

        /* Input Styling */
        #task-tag-input {
            flex-grow: 1;
            min-width: 120px;
            padding: 6px 10px;
            border: none;
            outline: none;
            font-size: 15px;
            background: transparent;
            color: #212529;
            margin: 2px 0;
        }
        #task-tag-input::placeholder {
            color: #adb5bd;
            opacity: 1;
        }

        /* Suggestions Dropdown */
        #task-suggestions {
            margin-top: 4px;
            background: #fff;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            box-shadow: 0 4px 16px rgba(13,110,253,0.10);
            max-height: 220px;
            overflow-y: auto;
            z-index: 20;
            position: absolute;
            /* left: 0;
            right: 0;
            min-width: 180px; */
        }

        .task-suggestion {
            padding: 8px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            font-size: 15px;
            color: #212529;
            background: transparent;
            transition: background 0.18s, color 0.18s;
        }
        .task-suggestion:last-child {
            border-bottom: none;
        }
        .task-suggestion:hover,
        .task-suggestion.active {
            background: #0d6efd;
            color: #fff;
        }

        /* Scrollbar Styling */
        #task-suggestions::-webkit-scrollbar {
            width: 8px;
        }
        #task-suggestions::-webkit-scrollbar-thumb {
            background: #0d6efd;
            border-radius: 10px;
        }
        #task-suggestions::-webkit-scrollbar-thumb:hover {
            background: #0b5ed7;
        }
    </style>
    <style>
        /* Tag Input Wrapper */
        .tag-inline-container {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            /* gap: 4px;
            padding: 4px 8px; */
            background: #fff;
            border-radius: 0.375rem;
            border: 1px solid #ced4da;
            /* min-height: 38px; */
            position: relative;
            transition: box-shadow 0.2s, border-color 0.2s;
        }
        .tag-inline-container:focus-within {
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
            border-color: #86b7fe;
        }

        /* Tag Styling */
        .task-tag {
            display: inline-flex;
            align-items: center;
            background: #0d6efd;
            color: #fff;
            padding: 4px 12px 4px 10px;
            border-radius: 1rem;
            font-size: 14px;
            margin: 2px 2px 2px 0;
            box-shadow: 0 1px 2px rgba(13,110,253,0.08);
            font-weight: 500;
            cursor: default;
            transition: background 0.2s, box-shadow 0.2s, transform 0.1s;
        }
        .task-tag:hover {
            background: #0b5ed7;
            box-shadow: 0 2px 6px rgba(13,110,253,0.12);
            transform: translateY(-1px) scale(1.04);
        }
        .task-tag span {
            margin-left: 8px;
            cursor: pointer;
            font-weight: bold;
            color: #fff;
            opacity: 0.7;
            transition: opacity 0.15s;
        }
        .task-tag span:hover {
            opacity: 1;
            color: #f87171;
        }

        /* Input Styling */
        #task-tag-input {
            flex-grow: 1;
            min-width: 120px;
            padding: 6px 10px;
            border: none;
            outline: none;
            font-size: 15px;
            background: transparent;
            color: #212529;
            margin: 2px 0;
        }

        #task-tag-input::placeholder {
            color: #adb5bd;
            opacity: 1;
        }

        /* Suggestions Dropdown */
        #task-suggestions {
            margin-top: 4px;
            background: #fff;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            box-shadow: 0 4px 16px rgba(13,110,253,0.10);
            max-height: 220px;
            overflow-y: auto;
            z-index: 20;
            position: absolute;
            /* left: 0;
            right: 0;
            min-width: 180px; */
        }

        .task-suggestion {
            padding: 8px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            font-size: 15px;
            color: #212529;
            background: transparent;
            transition: background 0.18s, color 0.18s;
        }
        .task-suggestion:last-child {
            border-bottom: none;
        }
        .task-suggestion:hover,
        .task-suggestion.active {
            background: #0d6efd;
            color: #fff;
        }

        /* Scrollbar Styling */
        #task-suggestions::-webkit-scrollbar {
            width: 8px;
        }
        #task-suggestions::-webkit-scrollbar-thumb {
            background: #0d6efd;
            border-radius: 10px;
        }
        #task-suggestions::-webkit-scrollbar-thumb:hover {
            background: #0b5ed7;
        }
    </style>
    <style>
        .tagify {
            width: 100%;
            max-width: 700px;
            background: rgba(white, .8);
        }

        :root {
            --tagify-dd-item-pad: .5em .7em;
        }

        .tagify__dropdown.users-list .tagify__dropdown__item {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 0 1em;
            grid-template-areas: "avatar name"
                "avatar email";
        }

        .tagify__dropdown.users-list header.tagify__dropdown__item {
            grid-template-areas: "add remove-tags"
                "remaning .";
        }

        .tagify__dropdown.users-list .tagify__dropdown__item:hover .tagify__dropdown__item__avatar-wrap {
            transform: scale(1.2);
        }

        .tagify__dropdown.users-list .tagify__dropdown__item__avatar-wrap {
            grid-area: avatar;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            overflow: hidden;
            background: #EEE;
            transition: .1s ease-out;
        }

        .tagify__dropdown.users-list img {
            width: 100%;
            vertical-align: top;
        }

        .tagify__dropdown.users-list header.tagify__dropdown__item>div,
        .tagify__dropdown.users-list .tagify__dropdown__item strong {
            grid-area: name;
            width: 100%;
            align-self: center;
        }

        .tagify__dropdown.users-list span {
            grid-area: email;
            width: 100%;
            font-size: .9em;
            opacity: .6;
        }

        .tagify__dropdown.users-list .tagify__dropdown__item__addAll {
            border-bottom: 1px solid #DDD;
            gap: 0;
        }

        .tagify__dropdown.users-list .remove-all-tags {
            grid-area: remove-tags;
            justify-self: self-end;
            font-size: .8em;
            padding: .2em .3em;
            border-radius: 3px;
            user-select: none;
        }

        .tagify__dropdown.users-list .remove-all-tags:hover {
            color: white;
            background: salmon;
        }


        /* Tags items */
        .tagify__tag {
            white-space: nowrap;
        }

        .tagify__tag img {
            width: 100%;
            vertical-align: top;
            pointer-events: none;
        }

        .tagify__tag:hover .tagify__tag__avatar-wrap {
            transform: scale(1.6) translateX(-10%);
        }

        .tagify__tag .tagify__tag__avatar-wrap {
            width: 16px;
            height: 16px;
            white-space: normal;
            border-radius: 50%;
            background: silver;
            margin-right: 5px;
            transition: .12s ease-out;
        }

        .users-list .tagify__dropdown__itemsGroup:empty {
            display: none;
        }

        .users-list .tagify__dropdown__itemsGroup::before {
            content: attr(data-title);
            display: inline-block;
            font-size: .9em;
            padding: 4px 6px;
            margin: var(--tagify-dd-item-pad);
            font-style: italic;
            border-radius: 4px;
            background: #00ce8d;
            color: white;
            font-weight: 600;
        }

        .users-list .tagify__dropdown__itemsGroup:not(:first-of-type) {
            border-top: 1px solid #DDD;
        }
    </style>
    <style>
        /* Loader style */
        .loader {
            display: none;
            margin: 20px auto;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
        }

        .profile-header {
            background: linear-gradient(to right, #4e73df, #1cc88a);
            color: white;
            padding: 20px;
            border-radius: 8px;
        }

        .fancy-card {
            /* border: 1px solid #dee2e6; */
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .fancy-card:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .fancy-card .card-header {
            /* background-color: #f8f9fa; */
            /* font-weight: 600; */
            /* border-bottom: 1px solid #dee2e6; */
        }

        .offcanvas {
            border-top-left-radius: 1rem;
            border-bottom-left-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            border-radius: 0.5rem;
        }

        .btn-danger {
            border-radius: 0.5rem;
        }

        .bg-gradient-primary {
            background: linear-gradient(90deg, #007bff, #22c55e); /* Updated gradient colors for primary */
            color: #ffffff; /* Ensures text is visible on the gradient */
        }


        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>

     <style>
        #production-process-form-container .card {
            border-radius: 1rem;
            box-shadow: 0 6px 24px rgba(0,0,0,0.10);
            background: #f8fafc;
        }
        #production-process-form-container .card-header {
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
            background: linear-gradient(90deg, #36b3e8 0%, #0ea5e9 100%);
        }
        #production-process-form-container .btn-close,
        #production-process-form-container .btn[aria-label="Cancel"] {
            opacity: 0.8;
            background: none !important;
            box-shadow: none;
            outline: none;
            color: #333;
            font-size: 2rem;
        }
        #production-process-form-container .btn-close:hover,
        #production-process-form-container .btn[aria-label="Cancel"]:hover {
            opacity: 1;
            color: #0ea5e9;
        }
        #production-process-form-container .card-body {
            background: #f8fafc;
        }
        #production-process-form-container label {
            font-weight: 500;
            color: #0ea5e9;
        }
        #production-process-form-container .form-control,
        #production-process-form-container .form-select {
            border-radius: 0.5rem;
            border: 1px solid #b6e0fe;
            background: #fff;
        }
        #production-process-form-container .btn-info {
            background: linear-gradient(90deg, #36b3e8 0%, #0ea5e9 100%);
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(14,165,233,0.10);
        }
        #production-process-form-container .btn-secondary {
            border-radius: 0.5rem;
        }
        @media (min-width: 992px) {
            #productionProcessModal .modal-dialog {
                max-width: 1000px;
            }
        }
    </style>
    <style>
        :root {
            --primary: #0072ff;
            --accent: #00c6ff;
            --muted: #6c757d;
            --radius: 14px;
            --bg-light: #f8f9fa;
            --bg-dark: #1a1a2f;
            --card-bg: #ffffff;
        }

        /* Hero Section */
        .wm-hero {
            background: linear-gradient(90deg, var(--accent), var(--primary));
            border-radius: var(--radius);
            padding: 2rem;
            position: relative;
            color: #fff;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .wm-hero .illustration {
            position: absolute;
            right: 2rem;
            bottom: 0;
            z-index: -0;
            width: 260px;
            height: 160px;
            background: url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=1000&auto=format&fit=crop') center/cover no-repeat;
            opacity: 0.15;
        }

        .wm-hero .company-meta h2 {
            font-weight: 700;
            margin-bottom: 0.4rem;
        }

        .wm-hero small.badge {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Navigation Tabs */
        .nav-wm {
            /* background: var(--card-bg); */
            border-radius: var(--radius);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 0.5rem;
        }

        .nav-wm .nav-link {
            border-radius: 10px;
            color: var(--muted);
            transition: all 0.25s ease;
        }

        .nav-wm .nav-link.active {
            background: linear-gradient(90deg, var(--accent), var(--primary));
            color: #fff;
            box-shadow: 0 4px 14px rgba(0, 114, 255, 0.18);
        }

        /* Cards */
        .info-card {
            border: none;
            border-radius: var(--radius);
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-3px);
        }

        .info-card .card-header {
            background: rgba(0, 114, 255, 0.08);
            font-weight: 600;
            border-bottom: none;
            color: var(--primary);
        }

        .info-card .card-body p {
            margin-bottom: 0.5rem;
        }

        .section-heading {
            font-weight: 600;
            margin: 1rem 0;
            color: var(--primary);
            border-left: 4px solid var(--primary);
            padding-left: 0.6rem;
        }

        .policy-item:hover, .objective-item:hover {
            background: #f0f7ff;
            transform: translateY(-2px);
            border-color: var(--primary);
        }

        .policy-item .form-check-input:checked,
        .objective-item .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #0072ff, #00c6ff);
        }

        .accordion-button:not(.collapsed) {
            background-color: #0072ff !important;
            color: #fff !important;
            box-shadow: 0 2px 6px rgba(0, 114, 255, 0.3);
        }

        .accordion-button:focus {
            box-shadow: none !important;
        }

        .table-hover tbody tr:hover {
            background-color: #f3faff !important;
        }

        .btn-outline-primary:hover {
            background-color: #0072ff;
            color: #fff;
        }


        /* Responsiveness */
        @media (max-width: 768px) {
            .wm-hero .illustration {
                display: none;
            }
        }
    </style>
    @endsection

    <div class="container-fluid py-3">

        <!-- Hero Header -->
        <div class="wm-hero d-flex align-items-center justify-content-between flex-wrap">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ asset($company->logo ?? 'images/default-logo.png') }}" alt="Company Logo"
                    class="rounded-circle border border-light" style="width: 80px; height: 80px; object-fit: cover;">

                <div class="company-meta">
                    <h2>{{ $company->company_name ?? 'Company Name' }}</h2>
                    <p class="mb-1">{{ $company->industry ?? 'Industry Not Set' }}</p>
                    <small class="badge">{{ $company->status ?? 'Active' }}</small>
                </div>
            </div>

            <a href="" class="btn btn-light btn-sm">
                <i class="la la-edit me-1"></i> Edit Profile
            </a>

            <div class="illustration"></div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-pills nav-wm mb-4 bg-light flex-wrap justify-content-center" id="profileTabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#overview" role="tab"><i class="la la-info-circle me-1"></i> Overview</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#policy" role="tab"><i class="la la-file-alt me-1"></i> Policies</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#recp" role="tab"><i class="la la-chart-line me-1"></i> R.E.C.P</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#finance" role="tab"><i class="la la-dollar-sign me-1"></i> Finance</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#hr" role="tab"><i class="la la-users me-1"></i> HR</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#inventory" role="tab"><i class="la la-box me-1"></i> Inventory</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#operations" role="tab"><i class="la la-cogs me-1"></i> Operations</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#settings" role="tab"><i class="la la-cog me-1"></i> Settings</a></li>
        </ul>

        <!-- Tab Contents -->
        <div class="tab-content">
            <!-- Overview -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <h5 class="section-heading">Company Overview</h5>
                <div class="row g-3">
                    <div class="col-md-6 col-lg-4">
                        <div class="card info-card">
                            <div class="card-header">Basic Information</div>
                            <div class="card-body">
                                <p><strong>Name:</strong> {{ $company->company_name ?? 'N/A' }}</p>
                                <p><strong>Industry:</strong> {{ $company->industry ?? 'N/A' }}</p>
                                <p><strong>Founded:</strong>
                                    {{ $company->date_of_establishment ? \Carbon\Carbon::parse($company->date_of_establishment)->format('F j, Y') : 'N/A' }}
                                </p>
                                <p><strong>Employees:</strong> {{ $company->number_of_employees ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card info-card">
                            <div class="card-header">Headquarters</div>
                            <div class="card-body">
                                <p>{{ $company->address ?? 'Address not set' }}</p>
                                <p>{{ $company->city }}, {{ $company->state }}</p>
                                <p>{{ $company->country }}</p>
                                <p><strong>ZIP:</strong> {{ $company->zip_code ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card info-card">
                            <div class="card-header">Contact</div>
                            <div class="card-body">
                                <p><strong>Email:</strong> {{ $company->email ?? 'N/A' }}</p>
                                <p><strong>Phone:</strong> {{ $company->primary_phone_number ?? 'N/A' }}</p>
                                <p><strong>Website:</strong>
                                    <a href="{{ $company->website_url }}" target="_blank">{{ $company->website_url }}</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card info-card">
                            <div class="card-header">Location Data</div>
                            <div class="card-body">
                                <p><strong>Latitude:</strong> {{ $company->latitude ?? 'N/A' }}</p>
                                <p><strong>Longitude:</strong> {{ $company->longitude ?? 'N/A' }}</p>
                                <p><strong>MGRS:</strong> {{ $company->mgrs ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card info-card">
                            <div class="card-header">Primary Contact</div>
                            <div class="card-body">
                                <p><strong>Name:</strong> {{ $company->contact_person_full_name ?? 'N/A' }}</p>
                                <p><strong>Position:</strong> {{ $company->contact_person_position ?? 'N/A' }}</p>
                                <p><strong>Phone:</strong> {{ $company->contact_person_contact_number ?? 'N/A' }}</p>
                                <p><strong>Email:</strong> {{ $company->contact_person_email ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card info-card">
                            <div class="card-header">Operations Manager</div>
                            <div class="card-body">
                                <p><strong>Name:</strong> {{ $company->operations_manager ?? 'N/A' }}</p>
                                <p><strong>Email:</strong> {{ $company->operations_email ?? 'N/A' }}</p>
                                <p><strong>Phone:</strong> {{ $company->operations_phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Other tabs placeholder -->
             <!-- Policy -->
            <div class="tab-pane fade" id="policy" role="tabpanel">
                <div class="row">
                    <!-- Company Policies -->
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
                                <h4 class="card-title fw-semibold mb-0 text-primary">
                                    <i class="la la-file-alt me-2 text-primary"></i>Company Policies
                                </h4>
                            </div>
                            <div class="card-body pt-3">
                                @if($policies->count() > 0)
                                    <div class="row">
                                        @foreach($policies as $policy)
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <div class="policy-item p-3 border rounded-3 shadow-sm d-flex align-items-center justify-content-between"
                                                    style="transition: all 0.3s ease;">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <i class="la la-balance-scale text-accent fs-4"></i>
                                                        <label class="form-check-label fw-medium mb-0" for="policy-{{ $policy->policy_id }}">
                                                            {{ ucfirst($policy->title) }}
                                                        </label>
                                                    </div>
                                                    <div>
                                                        <input class="form-check-input ms-2" type="checkbox" role="switch"
                                                            id="policy-{{ $policy->policy_id }}"
                                                            value="{{ $policy->policy_id }}"
                                                            onchange="ChangePolicy(this, '{{ $company->company_id }}', '{{ $policy->policy_id }}')"
                                                            name="policy[]"
                                                            {{ in_array($policy->policy_id, array_column($company_policies->toArray(), 'policy_id')) ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No company policies defined yet.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Company Objectives -->
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
                                <h4 class="card-title fw-semibold mb-0 text-primary">
                                    <i class="la la-bullseye me-2 text-primary"></i>Company Objectives
                                </h4>
                            </div>
                            <div class="card-body pt-3">
                                @if($objectives->count() > 0)
                                    <div class="row">
                                        @foreach($objectives as $objective)
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <div class="objective-item p-3 border rounded-3 shadow-sm d-flex align-items-center justify-content-between"
                                                    style="transition: all 0.3s ease;">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <i class="la la-check-circle text-success fs-4"></i>
                                                        <label class="form-check-label fw-medium mb-0" for="objective-{{ $objective->objective_id }}">
                                                            {{ ucfirst($objective->name) }}
                                                        </label>
                                                    </div>
                                                    <div>
                                                        <input class="form-check-input ms-2" type="checkbox" role="switch"
                                                            id="objective-{{ $objective->objective_id }}"
                                                            value="{{ $objective->objective_id }}"
                                                            onchange="ChangeObjective(this, '{{ $company->company_id }}', '{{ $objective->objective_id }}')"
                                                            name="objective[]"
                                                            {{ in_array($objective->objective_id, array_column($company_objectives->toArray(), 'objective_id')) ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No company objectives defined yet.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Policy -->
             <!-- RECP -->
            <div class="tab-pane fade" id="recp" role="tabpanel">
                <div class="recp-section">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
                            <h4 class="card-title fw-semibold text-primary mb-0">
                                <i class="la la-recycle me-2 text-primary"></i>Resource Efficiency & Cleaner Production (R.E.C.P)
                            </h4>
                            <a href="#" class="btn btn-sm btn-gradient">
                                <i class="la la-plus me-1"></i> Add Initiative
                            </a>
                        </div>

                        <div class="card-body">
                            <p class="text-muted">
                                This section helps track your company’s progress toward sustainable and cleaner production goals — 
                                monitoring resource consumption, waste reduction, and environmental efficiency.
                            </p>

                            <!-- RECP Metrics -->
                            <div class="row g-3 mt-3">
                                <div class="col-md-4">
                                    <div class="card card-wm p-3 text-center border-0 shadow-sm">
                                        <i class="la la-tint fs-1 text-primary mb-2"></i>
                                        <h6 class="fw-semibold">Water Efficiency</h6>
                                        <p class="text-muted small mb-1">Usage per production cycle</p>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-primary" style="width: 72%;"></div>
                                        </div>
                                        <small class="text-success fw-medium mt-1 d-block">72% Efficiency</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card card-wm p-3 text-center border-0 shadow-sm">
                                        <i class="la la-bolt fs-1 text-warning mb-2"></i>
                                        <h6 class="fw-semibold">Energy Efficiency</h6>
                                        <p class="text-muted small mb-1">kWh saved this quarter</p>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-warning" style="width: 64%;"></div>
                                        </div>
                                        <small class="text-success fw-medium mt-1 d-block">64% Target Achieved</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card card-wm p-3 text-center border-0 shadow-sm">
                                        <i class="la la-cubes fs-1 text-success mb-2"></i>
                                        <h6 class="fw-semibold">Material Utilization</h6>
                                        <p class="text-muted small mb-1">Recycled vs. new materials</p>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: 81%;"></div>
                                        </div>
                                        <small class="text-success fw-medium mt-1 d-block">81% Reuse Rate</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Initiatives -->
                            <div class="mt-5">
                                <h5 class="fw-semibold mb-3">Recent R.E.C.P Initiatives</h5>
                                <div class="table-responsive">
                                    <table class="table align-middle table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Initiative</th>
                                                <th>Category</th>
                                                <th>Status</th>
                                                <th>Impact</th>
                                                <th>Date Implemented</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Water Recycling System</td>
                                                <td>Water Efficiency</td>
                                                <td><span class="badge bg-success">Ongoing</span></td>
                                                <td>Reduced water usage by 30%</td>
                                                <td>Mar 2025</td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-outline-primary"><i class="la la-eye"></i></a>
                                                    <a href="#" class="btn btn-sm btn-outline-secondary"><i class="la la-pencil"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Solar Power Integration</td>
                                                <td>Energy Efficiency</td>
                                                <td><span class="badge bg-info">Completed</span></td>
                                                <td>Cut energy cost by 25%</td>
                                                <td>Jan 2025</td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-outline-primary"><i class="la la-eye"></i></a>
                                                    <a href="#" class="btn btn-sm btn-outline-secondary"><i class="la la-pencil"></i></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="empty-state mt-4" style="display:none;">
                                    <img src="{{ asset('images/empty-state.svg') }}" alt="No initiatives">
                                    <p class="mt-2 text-muted">No R.E.C.P initiatives added yet.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="">General Knowledge of Nigeria IEE RECP Concept/Benefit</h4>
                    <p class="subtitle">In Nigeria, Industrial Energy Efficiency (IEE) and Resource Efficiency
                        and Cleaner Production (RECP) focus on optimizing energy and resource use while minimizing
                        waste.
                        These practices help businesses reduce costs, enhance sustainability, and lower
                        environmental impacts.
                        The benefits include cost savings, compliance with regulations, improved competitiveness,
                        and positive
                        contributions to Nigeria's economic growth and environmental protection. Adopting IEE and
                        RECP strategies
                        enables industries to operate more efficiently and sustainably, fostering a cleaner, greener
                        future.</p>
                    <!-- this projct -->
                    <div class="card">
                        <!-- card header -->
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="true" href="#this-project"
                                        data-bs-toggle="tab">This Project</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#environmental-health" data-bs-toggle="tab">Human and
                                        Environmental Health/Business Benefits</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab-innovation" data-bs-toggle="tab">Innovation</a>
                                </li>
                            </ul>
                        </div><!--end card-header-->
                        <div class="card-body pt-2">
                            <div class="tab-content">
                                <div class="row tab-pane fade show active" id="this-project">
                                    <div class="col-md-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id=""
                                                onchange="ChangeUtmostBenefit(this, '{{$company->company_id}}', `Develop policy and regulation that deliver economic, human and environmental health gain to your company.`)"
                                                value="Develop policy and regulation that deliver economic, human and environmental health gain to your company."
                                                name="areas_of_company_benefit[]"
                                                {{ in_array("Develop policy and regulation that deliver economic, human and environmental health gain to your company.", array_column($company_benefits->toArray(), 'benefit_title')) ? "checked" : "" }}>
                                            <label class="form-check-label" for=""> Develop policy and regulation
                                                that deliver economic, humanand environmental health gain to your
                                                company. </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-md-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id=""
                                                onchange="ChangeUtmostBenefit(this, '{{$company->company_id}}', `To offer standard accreditation and certification capacity building on ISO 15000 and 14000 series to your enterprise.`)"
                                                value="To offer standard accreditation and certification capacity building on ISO 15000 and 14000 series to your enterprise."
                                                name="areas_of_company_benefit[]"
                                                {{ in_array("To offer standard accreditation and certification capacity building on ISO 15000 and 14000 series to your enterprise.", array_column($company_benefits->toArray(), 'benefit_title')) ? "checked" : "" }}>
                                            <label class="form-check-label" for=""> To offer standard accreditation
                                                and certification capacity building on ISO 15000 and 14000 series to
                                                your enterprise </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-md-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id=""
                                                onchange="ChangeUtmostBenefit(this, '{{$company->company_id}}', `To deliver impactful training on Resource Efficient and Cleaner Production (RECP), including comprehensive support materials, toolkits, and learning resources, tailored for staff and employees across Nigeria's industrial manufacturing sector.`)"
                                                value="To deliver impactful training on Resource Efficient and Cleaner Production (RECP), including comprehensive support materials, toolkits, and learning resources, tailored for staff and employees across Nigeria's industrial manufacturing sector."
                                                name="areas_of_company_benefit[]"
                                                {{ in_array("To deliver impactful training on Resource Efficient and Cleaner Production (RECP), including comprehensive support materials, toolkits, and learning resources, tailored for staff and employees across Nigeria's industrial manufacturing sector.", array_column($company_benefits->toArray(), 'benefit_title')) ? "checked" : "" }}>
                                            <label class="form-check-label" for=""> To deliver impactful training on
                                                Resource Efficient and Cleaner Production (RECP), including
                                                comprehensive support materials, toolkits, and learning resources,
                                                tailored for staff and employees across Nigeria's industrial
                                                manufacturing sector. </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-md-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id=""
                                                onchange="ChangeUtmostBenefit(this, '{{$company->company_id}}', `To strengthen the internal capacity for delivering RECP training and related technical assistance to your enterprise, ensuring long-term impact and achieving commercially sustainable outcomes.`)"
                                                value="To strengthen the internal capacity for delivering RECP training and related technical assistance to your enterprise, ensuring long-term impact and achieving commercially sustainable outcomes."
                                                name="areas_of_company_benefit[]"
                                                {{ in_array("To strengthen the internal capacity for delivering RECP training and related technical assistance to your enterprise, ensuring long-term impact and achieving commercially sustainable outcomes.", array_column($company_benefits->toArray(), 'benefit_title')) ? "checked" : "" }}>
                                            <label class="form-check-label" for=""> To strengthen the internal
                                                capacity for delivering RECP training and related technical
                                                assistance to your enterprise, ensuring long-term impact and
                                                achieving commercially sustainable outcomes. </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-md-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id=""
                                                onchange="ChangeUtmostBenefit(this, '{{$company->company_id}}', `To raise awareness and implement pilot programs on RECP, aimed at enhancing productivity through efficient use of manufacturing inputs (water, chemicals, and materials), minimizing waste and emissions, and promoting regulatory compliance while boosting competitiveness within your industrial sector.`)"
                                                value="To raise awareness and implement pilot programs on RECP, aimed at enhancing productivity through efficient use of manufacturing inputs (water, chemicals, and materials), minimizing waste and emissions, and promoting regulatory compliance while boosting competitiveness within your industrial sector."
                                                name="areas_of_company_benefit[]"
                                                {{ in_array("To raise awareness and implement pilot programs on RECP, aimed at enhancing productivity through efficient use of manufacturing inputs (water, chemicals, and materials), minimizing waste and emissions, and promoting regulatory compliance while boosting competitiveness within your industrial sector.", array_column($company_benefits->toArray(), 'benefit_title')) ? "checked" : "" }}>
                                            <label class="form-check-label" for=""> To raise awareness and implement
                                                pilot programs on RECP, aimed at enhancing productivity through
                                                efficient use of manufacturing inputs (water, chemicals, and
                                                materials), minimizing waste and emissions, and promoting regulatory
                                                compliance while boosting competitiveness within your industrial
                                                sector. </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-md-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id=""
                                                onchange="ChangeUtmostBenefit(this, '{{$company->company_id}}', `To enhance the adoption of RECP practices and associated investments by providing a targeted financial assistance package for companies participating in RECP pilot programs.`)"
                                                value="To enhance the adoption of RECP practices and associated investments by providing a targeted financial assistance package for companies participating in RECP pilot programs."
                                                name="areas_of_company_benefit[]"
                                                {{ in_array("To enhance the adoption of RECP practices and associated investments by providing a targeted financial assistance package for companies participating in RECP pilot programs.", array_column($company_benefits->toArray(), 'benefit_title')) ? "checked" : "" }}>
                                            <label class="form-check-label" for=""> To enhance the adoption of RECP
                                                practices and associated investments by providing a targeted
                                                financial assistance package for companies participating in RECP
                                                pilot programs. </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-md-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id=""
                                                onchange="ChangeUtmostBenefit(this, '{{$company->company_id}}', `To deliver the cost-saving benefits of RECP to your industrial manufacturing sector by facilitating greater access to financial mechanisms—both commercial and government—to support the financing of RECP projects.`)"
                                                value="To deliver the cost-saving benefits of RECP to your industrial manufacturing sector by facilitating greater access to financial mechanisms—both commercial and government—to support the financing of RECP projects."
                                                name="areas_of_company_benefit[]"
                                                {{ in_array("To deliver the cost-saving benefits of RECP to your industrial manufacturing sector by facilitating greater access to financial mechanisms—both commercial and government—to support the financing of RECP projects.", array_column($company_benefits->toArray(), 'benefit_title')) ? "checked" : "" }}>
                                            <label class="form-check-label" for=""> To deliver the cost-saving
                                                benefits of RECP to your industrial manufacturing sector by
                                                facilitating greater access to financial mechanisms—both commercial
                                                and government—to support the financing of RECP projects. </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- environmental health -->
                                <div class="row tab-pane fade" id="environmental-health">
                                    <div class="col-md-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id=""
                                                onchange="ChangeEnvironmentalBenefit(this, '{{$company->company_id}}', `Achieve a minimum 20% reduction in energy consumption within one year.`)"
                                                value="Achieve a minimum 20% reduction in energy consumption within one year."
                                                name="environment_health_benefit[]"
                                                {{ in_array("Achieve a minimum 20% reduction in energy consumption within one year.", array_column($company_enviromental_benefits->toArray(), 'environmental_benefit_title')) ? "checked" : "" }}>
                                            <label class="form-check-label" for=""> Achieve a minimum 20% reduction
                                                in energy consumption within one year. </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-md-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id=""
                                                onchange="ChangeEnvironmentalBenefit(this, '{{$company->company_id}}', `Achieve a 40% reduction in CO2 emissions within 18 months.`)"
                                                value="Achieve a 40% reduction in CO2 emissions within 18 months."
                                                name="environment_health_benefit[]"
                                                {{ in_array("Achieve a 40% reduction in CO2 emissions within 18 months.", array_column($company_enviromental_benefits->toArray(), 'environmental_benefit_title')) ? "checked" : "" }}>
                                            <label class="form-check-label" for=""> Achieve a 40% reduction in
                                                CO<sub>2</sub> emissions within 18 months.</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-md-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id=""
                                                onchange="ChangeEnvironmentalBenefit(this, '{{$company->company_id}}', `Double your water productivity within one year.`)"
                                                value="Double your water productivity within one year."
                                                name="environment_health_benefit[]"
                                                {{ in_array("Double your water productivity within one year.", array_column($company_enviromental_benefits->toArray(), 'environmental_benefit_title')) ? "checked" : "" }}>
                                            <label class="form-check-label" for=""> Double your water productivity
                                                within one year. </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-md-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id=""
                                                onchange="ChangeEnvironmentalBenefit(this, '{{$company->company_id}}', `Achieve a 50% increase in overall material productivity within one year.`)"
                                                value="Achieve a 50% increase in overall material productivity within one year."
                                                name="environment_health_benefit[]"
                                                {{ in_array("Achieve a 50% increase in overall material productivity within one year.", array_column($company_enviromental_benefits->toArray(), 'environmental_benefit_title')) ? "checked" : "" }}>
                                            <label class="form-check-label" for=""> Achieve a 50% increase in
                                                overall material productivity within one year. </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-md-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id=""
                                                onchange="ChangeEnvironmentalBenefit(this, '{{$company->company_id}}', `Attain ISO 14000 certification to demonstrate your commitment to effective environmental management and sustainability practices.`)"
                                                value="Attain ISO 14000 certification to demonstrate your commitment to effective environmental management and sustainability practices."
                                                name="environment_health_benefit[]"
                                                {{ in_array("Attain ISO 14000 certification to demonstrate your commitment to effective environmental management and sustainability practices.", array_column($company_enviromental_benefits->toArray(), 'environmental_benefit_title')) ? "checked" : "" }}>
                                            <label class="form-check-label" for=""> Attain ISO 14000 certification
                                                to demonstrate your commitment to effective environmental management
                                                and sustainability practices. </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-md-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id=""
                                                onchange="ChangeEnvironmentalBenefit(this, '{{$company->company_id}}', `Achieve an increase in overall annual financial savings.`)"
                                                value="Achieve an increase in overall annual financial savings."
                                                name="environment_health_benefit[]"
                                                {{ in_array("Achieve an increase in overall annual financial savings.", array_column($company_enviromental_benefits->toArray(), 'environmental_benefit_title')) ? "checked" : "" }}>
                                            <label class="form-check-label" for=""> Achieve an increase in overall
                                                annual financial savings. </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-md-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id=""
                                                onchange="ChangeEnvironmentalBenefit(this, '{{$company->company_id}}', `Enhance customer satisfaction through improved products, services, and overall experience.`)"
                                                value="Enhance customer satisfaction through improved products, services, and overall experience."
                                                name="environment_health_benefit[]"
                                                {{ in_array("Enhance customer satisfaction through improved products, services, and overall experience.", array_column($company_enviromental_benefits->toArray(), 'environmental_benefit_title')) ? "checked" : "" }}>
                                            <label class="form-check-label" for=""> Enhance customer satisfaction
                                                through improved products, services, and overall experience.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-md-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id=""
                                                onchange="ChangeEnvironmentalBenefit(this, '{{$company->company_id}}', `Achieve ISO 15000 series certification to demonstrate adherence to international standards for information and communication technology management.`)"
                                                value="Achieve ISO 15000 series certification to demonstrate adherence to international standards for information and communication technology management."
                                                name="environment_health_benefit[]"
                                                {{ in_array("Achieve ISO 15000 series certification to demonstrate adherence to international standards for information and communication technology management.", array_column($company_enviromental_benefits->toArray(), 'environmental_benefit_title')) ? "checked" : "" }}>
                                            <label class="form-check-label" for=""> Achieve ISO 15000 series
                                                certification to demonstrate adherence to international standards
                                                for information and communication technology management. </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- environmental health -->
                                <!-- Innovation -->
                                <div class="row tab-pane fade g-2" id="tab-innovation">
                                    <div class="col-md-12">
                                        <div class="col-md-8">
                                            <label for="">Key areas for improving performance in your
                                                industry.</label>
                                        </div>
                                        <div class="col-md-12 key-areas-container">
                                            @foreach($company_areas_of_improvement as $keyArea)
                                            <div class="row g-2 my-1">
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"
                                                            value="{{ $keyArea->area_title }}"
                                                            placeholder="Key area for improving performance in your industry"
                                                            onblur='update_key_area("{{$company->company_id}}", "{{ $keyArea->improvementAreaID }}", this)'>
                                                    </div>
                                                </div>
                                                <div class="col-md-3"><button class="btn btn-outline-danger"
                                                        onclick='remove_key_area(this, "{{ $keyArea->improvementAreaID }}")'
                                                        type="button"> <i class="iconoir-trash"></i> </button></div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <input type="text" class="form-control"
                                                        placeholder="Key area for performance improvement"
                                                        id="key_area_for_improvent">
                                                </div>
                                            </div>
                                            <div class="col-md-3"><button
                                                    class="btn btn-outline-primary btn-sm add_more_key_areas"
                                                    type="button"> <i class="iconoir-plus fs-4"></i> </button></div>
                                        </div>

                                    </div>

                                    <div class="col-md-12">
                                        <div class="col-md-8">
                                            <label for="">Highlight innovations that enhance your product's
                                                environmental compatibility.</label>
                                        </div>
                                        <div class="col-md-12 product-innovation-container">
                                            @foreach($company_product_innovation as $productInnovation)
                                            <div class="row g-2 my-1">
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"
                                                            value="{{ $productInnovation->innovation_area_title }}"
                                                            placeholder="Key innovation that enhance your product's environmental compatibility"
                                                            onblur='update_product_innovation("{{$company->company_id}}", "{{ $productInnovation->innovationAreaID }}", this)'>
                                                    </div>
                                                </div>
                                                <div class="col-md-3"><button class="btn btn-outline-danger"
                                                        onclick='remove_product_innovation(this, "{{ $productInnovation->innovationAreaID }}")'
                                                        type="button"> <i class="iconoir-trash"></i> </button></div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <input type="text" class="form-control"
                                                        placeholder="Key innovation that enhance your product's environmental compatibility"
                                                        id="key_product_innovation">
                                                </div>
                                            </div>
                                            <div class="col-md-3"><button
                                                    class="btn btn-outline-primary btn-sm add_more_key_innovation"
                                                    type="button"> <i class="iconoir-plus fs-4"></i> </button></div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="col-md-8">
                                            <label for="">Identify hazarduous materials in your process system that
                                                can be reduced, eliminated, or replaced with safer
                                                alternatives.</label>
                                        </div>
                                        <div class="col-md-12 hazarduous-material-container">
                                            @foreach($company_hazarduous_material as $hazarduousMaterial)
                                            <div class="row g-2 my-1">
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"
                                                            value="{{ $hazarduousMaterial->material_title }}"
                                                            placeholder="Key innovation that enhance your product's environmental compatibility"
                                                            onblur='update_hazardous_material("{{$company->company_id}}", "{{ $hazarduousMaterial->hazarduousMaterialID }}", this)'>
                                                    </div>
                                                </div>
                                                <div class="col-md-3"><button class="btn btn-outline-danger"
                                                        onclick='remove_hazardous_material(this, "{{ $hazarduousMaterial->hazarduousMaterialID }}")'
                                                        type="button"> <i class="iconoir-trash"></i> </button></div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <input type="text" class="form-control"
                                                        placeholder="Hazarduous material " id="hazarduous_material">
                                                </div>
                                            </div>
                                            <div class="col-md-3"><button
                                                    class="btn btn-outline-primary btn-sm add_more_hazardous_material"
                                                    type="button"> <i class="iconoir-plus fs-4"></i> </button></div>
                                        </div>
                                    </div>
                                    <!-- Innovation -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="">Resource Efficiency & Cleaner Production Opportunities</h4>
                    <p>Resource Efficiency and Cleaner Production (RECP) focus on optimizing
                        the use of resources while minimizing waste and environmental impacts
                        throughout production processes. By adopting these strategies, industries
                        can enhance productivity, reduce costs, and achieve sustainability goals.</p>
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="true" href="#tab-housekeeping"
                                        data-bs-toggle="tab">Good Housekeeping</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#process-specific-optimization"
                                        data-bs-toggle="tab">Process Specific Optimization</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#process-waste-reduction-measures"
                                        data-bs-toggle="tab">Waste Reduction Measures</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#waste-management-method" data-bs-toggle="tab">Waste
                                        Management & Disposal Methods</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#product-recovery" data-bs-toggle="tab">Product
                                        Recovery Measures</a>
                                </li>
                            </ul>
                        </div><!--end card-header-->
                        <div class="card-body pt-2">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="tab-housekeeping">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeGoodHouseKeeping(this, '{{$company->company_id}}', `Attitudinal change (negligence attitude).`)"
                                                    value="Attitudinal change (negligence attitude)."
                                                    name="house_keeping[]"
                                                    {{ in_array("Attitudinal change (negligence attitude).", array_column($company_house_keeping->toArray(), 'practice_title')) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Attitudinal change (negligence attitude). </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeGoodHouseKeeping(this, '{{$company->company_id}}', `Improved workplace management.`)"
                                                    value="Improved workplace management."
                                                    name="house_keeping[]"
                                                    {{ in_array("Improved workplace management.", array_column($company_house_keeping->toArray(), 'practice_title')) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Improved Workplace management. </label>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeGoodHouseKeeping(this, '{{$company->company_id}}', `Good operating practices(personel practices, waste segregation etc.).`)"
                                                    value="Good operating practices(personel practices, waste segregation etc.)."
                                                    name="house_keeping[]"
                                                    {{ in_array("Good operating practices(personel practices, waste segregation etc.).", array_column($company_house_keeping->toArray(), 'practice_title')) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Good operating practices(personel practices, waste segregation etc.). </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeGoodHouseKeeping(this, '{{$company->company_id}}', `Workers motivation.`)"
                                                    value="Workers motivation."
                                                    name="house_keeping[]"
                                                    {{ in_array("Workers motivation.", array_column($company_house_keeping->toArray(), 'practice_title')) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Workers motivation. </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Process  Specific Specialization -->
                                <div class="row g-2 tab-pane fade " id="process-specific-optimization">
                                    <div class="col-md-12">
                                        <div class="col-md-8">
                                            <label for="">List Unit Processes Requiring Intervention (if
                                                applicable)</label>
                                        </div>
                                        <div class="col-md-12 unit-process-container">
                                            @foreach($company_unit_process as $unitProcess)
                                            <div class="row g-2 my-1">
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"
                                                            value="{{ $unitProcess->unit_process_title }}"
                                                            placeholder="Unit process"
                                                            onblur='update_unit_process("{{$company->company_id}}", "{{ $unitProcess->unitProcessID }}", this)'>
                                                    </div>
                                                </div>
                                                <div class="col-md-3"><button class="btn btn-outline-danger"
                                                        onclick='remove_unit_process(this, "{{ $unitProcess->unitProcessID }}")'
                                                        type="button"> <i class="iconoir-trash"></i> </button></div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Unit process"
                                                        id="unit_process">
                                                </div>
                                            </div>
                                            <div class="col-md-3"><button
                                                    class="btn btn-outline-primary btn-sm add_more_unit_process"
                                                    type="button"> <i class="iconoir-plus fs-4"></i> </button></div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="col-md-8">
                                            <label for=""> Problem Summary and Suggested Solutions. </label>
                                        </div>
                                        <div class="col-md-12 problems-solutions-container">
                                            @foreach($company_problems_and_solutions as $problemSolution)
                                            <div class="row g-2 my-1 align-items-end">
                                                <div class="col-md-5">
                                                    <label for="">Problem Summary</label>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"
                                                            value="{{ $problemSolution->problem_title }}"
                                                            placeholder="Problem Summary"
                                                            onblur='update_problem_summary("{{$company->company_id}}", "{{ $problemSolution->problemSolutionID }}", this)'>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <label for="">Suggested Solution</label>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"
                                                            value="{{ $problemSolution->solution_title }}"
                                                            placeholder="Suggested solution"
                                                            onblur='update_suggested_solution("{{$company->company_id}}", "{{ $problemSolution->problemSolutionID }}", this)'>
                                                    </div>
                                                </div>
                                                <div class="col-md-2"><button class="btn btn-outline-danger"
                                                        onclick='remove_problem_solution(this, "{{ $problemSolution->problemSolutionID }}")'
                                                        type="button"> <i class="iconoir-trash"></i> </button></div>
                                            </div>
                                            @endforeach
                                        </div>

                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-5">
                                                <label for="">Problem summary</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control"
                                                        placeholder="Problem summary" id="problem_summary">
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="">Suggested Solution</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control"
                                                        placeholder="Suggested solution" id="suggested_solution">
                                                </div>
                                            </div>
                                            <div class="col-md-2 "><button
                                                    class="btn btn-outline-primary btn-sm add_more_problem_solution"
                                                    type="button"> <i class="iconoir-plus fs-4"></i> </button></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Process specific specialization -->
                                <!-- waste reduction measures -->
                                <div class="tab-pane fade" id="process-waste-reduction-measures">
                                    <div class="row mb-2">
                                        @php
                                            // Get all checked waste reduction titles as an array for easy lookup
                                            $checkedWasteReductions = array_column($company_waste_reduction_measures->toArray(), 'waste_reduction_title');
                                        @endphp

                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    onchange="ChangeWasteReductionMeasures(this, '{{$company->company_id}}', `Water recycling flow.`)"
                                                    id="" value="Water recycling flow."
                                                    name="RECP_waste_reduction_measures[]"
                                                    {{ in_array("Water recycling flow.", $checkedWasteReductions) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Water recycling flow.
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeWasteReductionMeasures(this, '{{$company->company_id}}', `Waste water treatment.`)"
                                                    value="Waste water treatment."
                                                    name="RECP_waste_reduction_measures[]"
                                                    {{ in_array("Waste water treatment.", $checkedWasteReductions) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Waste water treatment.
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeWasteReductionMeasures(this, '{{$company->company_id}}', `Monitoring of the quality and quantity of waste water.`)"
                                                    value="Monitoring of the quality and quantity of waste water."
                                                    name="RECP_waste_reduction_measures[]"
                                                    {{ in_array("Monitoring of the quality and quantity of waste water.", $checkedWasteReductions) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Monitoring of the quality
                                                    and quantity of wastewater. </label>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeWasteReductionMeasures(this, '{{$company->company_id}}', `Using production equipment or technology that supports energy/resource-efficient production.`)"
                                                    value="Using production equipment or technology that supports energy/resource-efficient production."
                                                    name="RECP_waste_reduction_measures[]"
                                                    {{ in_array("Using production equipment or technology that supports energy/resource-efficient production.", $checkedWasteReductions) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Using production equipment
                                                    or technology that supports energy/resource-efficient
                                                    production. </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeWasteReductionMeasures(this, '{{$company->company_id}}', `Use of waste for internal energy sources.`)"
                                                    value="Use of waste for internal energy sources."
                                                    name="RECP_waste_reduction_measures[]"
                                                    {{ in_array("Use of waste for internal energy sources.", $checkedWasteReductions) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Use of waste for internal
                                                    energy sources. </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeWasteReductionMeasures(this, '{{$company->company_id}}', `Installation of lighting sensor.`)"
                                                    value="Installation of lighting sensor."
                                                    name="RECP_waste_reduction_measures[]"
                                                    {{ in_array("Installation of lighting sensor.", $checkedWasteReductions) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Installation of lighting
                                                    sensor. </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeWasteReductionMeasures(this, '{{$company->company_id}}', `Utilization of sunlight for daytime lighting.`)"
                                                    value="Utilization of sunlight for daytime lighting."
                                                    name="RECP_waste_reduction_measures[]"
                                                    {{ in_array("Utilization of sunlight for daytime lighting.", $checkedWasteReductions) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Utilization of sunlight for
                                                    daytime lighting. </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeWasteReductionMeasures(this, '{{$company->company_id}}', `Use of enviromentally friendly/renewable energy.`)"
                                                    value="Use of enviromentally friendly/renewable energy."
                                                    name="RECP_waste_reduction_measures[]"
                                                    {{ in_array("Use of enviromentally friendly/renewable energy.", $checkedWasteReductions) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Use of enviromentally
                                                    friendly/renewable energy. </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeWasteReductionMeasures(this, '{{$company->company_id}}', `Recording of fuel usage.`)"
                                                    value="Recording of fuel usage."
                                                    name="RECP_waste_reduction_measures[]"
                                                    {{ in_array("Recording of fuel usage.", $checkedWasteReductions) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Recording of fuel usage.
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeWasteReductionMeasures(this, '{{$company->company_id}}', `Minimize the use of generating sets.`)"
                                                    value="Minimize the use of generating sets."
                                                    name="RECP_waste_reduction_measures[]"
                                                    {{ in_array("Minimize the use of generating sets.", $checkedWasteReductions) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Minimize the use of
                                                    generating sets. </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeWasteReductionMeasures(this, '{{$company->company_id}}', `Substitute high yield pollutant raw materials with other less polluting materials.`)"
                                                    value="Substitute high yield pollutant raw materials with other less polluting materials."
                                                    name="RECP_waste_reduction_measures[]"
                                                    {{ in_array("Substitute high yield pollutant raw materials with other less polluting materials.", $checkedWasteReductions) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Substitute high yield
                                                    pollutant raw materials with other less polluting materials.
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeWasteReductionMeasures(this, '{{$company->company_id}}', `Maintain the unit process/equipment to minimize emission of pollutants.`)"
                                                    value="Maintain the unit process/equipment to minimize emission of pollutants."
                                                    name="RECP_waste_reduction_measures[]"
                                                    {{ in_array("Maintain the unit process/equipment to minimize emission of pollutants.", $checkedWasteReductions) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Maintain the unit
                                                    process/equipment to minimize emission of pollutants. </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeWasteReductionMeasures(this, '{{$company->company_id}}', `Diluting the air pollutants.`)"
                                                    value="Diluting the air pollutants."
                                                    name="RECP_waste_reduction_measures[]"
                                                    {{ in_array("Diluting the air pollutants.", $checkedWasteReductions) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Diluting the air pollutants.
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeWasteReductionMeasures(this, '{{$company->company_id}}', `Plant flowers and trees around the premises to reduce large number of pollutants in the air.`)"
                                                    value="Plant flowers and trees around the premises to reduce large number of pollutants in the air."
                                                    name="RECP_waste_reduction_measures[]"
                                                    {{ in_array("Plant flowers and trees around the premises to reduce large number of pollutants in the air.", $checkedWasteReductions) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Plant flowers and trees
                                                    around the premises to reduce large number of pollutants in the
                                                    air. </label>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeWasteReductionMeasures(this, '{{$company->company_id}}', `Fuel substituting(petrol and diesel can be replaced with compressed natural gas, solar and wind energy).`)"
                                                    value="Fuel substituting(petrol and diesel can be replaced with compressed natural gas, solar and wind energy)."
                                                    name="RECP_waste_reduction_measures[]"
                                                    {{ in_array("Fuel substituting(petrol and diesel can be replaced with compressed natural gas, solar and wind energy).", $checkedWasteReductions) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Fuel substituting(petrol and
                                                    diesel can be replaced with compressed natural gas, solar and
                                                    wind energy). </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- waste reduction measures -->
                                <!-- waste management and disposal methods -->
                                <div class="tab-pane fade" id="waste-management-method">
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeWasteDisposalMethod(this, '{{$company->company_id}}', `Landfill`)"
                                                    value="Landfill" name="waste_management_methods[]"
                                                    {{(in_array("Landfill",
                                                    array_column($company_management_measures->toArray(),
                                                'management_method_title')))? "checked": ""}}>
                                                <label class="form-check-label" for=""> Landfill </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeWasteDisposalMethod(this, '{{$company->company_id}}', `Recycling`)"
                                                    value="Recycling" name="waste_management_methods[]"
                                                    {{(in_array("Recycling",
                                                    array_column($company_management_measures->toArray(),
                                                'management_method_title')))? "checked": ""}}>
                                                <label class="form-check-label" for=""> Recycling </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeWasteDisposalMethod(this, '{{$company->company_id}}', `Waste segregation`)"
                                                    value="Waste segregation" name="waste_management_methods[]"
                                                    {{(in_array("Waste segregation",
                                                    array_column($company_management_measures->toArray(),
                                                'management_method_title')))? "checked": ""}}>
                                                <label class="form-check-label" for=""> Waste segregation </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeWasteDisposalMethod(this, '{{$company->company_id}}', `Incineration`)"
                                                    value="Incineration" name="waste_management_methods[]"
                                                    {{(in_array("Incineration",
                                                    array_column($company_management_measures->toArray(),
                                                'management_method_title')))? "checked": ""}}>
                                                <label class="form-check-label" for=""> Incineration </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeWasteDisposalMethod(this, '{{$company->company_id}}', `Composting`)"
                                                    value="Composting" name="waste_management_methods[]"
                                                    {{(in_array("Composting",
                                                    array_column($company_management_measures->toArray(),
                                                'management_method_title')))? "checked": ""}}>
                                                <label class="form-check-label" for=""> Composting </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeWasteDisposalMethod(this, '{{$company->company_id}}', `Waste Symbiosis`)"
                                                    value="Waste Symbiosis" name="waste_management_methods[]"
                                                    {{(in_array("Waste Symbiosis",
                                                    array_column($company_management_measures->toArray(),
                                                'management_method_title')))? "checked": ""}}>
                                                <label class="form-check-label" for=""> Waste Symbiosis </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                                <!--  -->
                                <div class="tab-pane fade" id="product-recovery">
                                    <div class="row mb-2">
                                        @php
                                            // Get all checked product recovery method titles as an array for easy lookup
                                            $checkedProductRecoveryMethods = array_column($company_product_recovery_measures->toArray(), 'recovery_method_title');
                                        @endphp

                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeProductRecoveryMeasure(this, '{{$company->company_id}}', `High temperature recovery method`)"
                                                    value="High temperature recovery method"
                                                    name="product_recovery_measures[]"
                                                    {{ in_array("High temperature recovery method", $checkedProductRecoveryMethods) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> High temperature recovery method </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeProductRecoveryMeasure(this, '{{$company->company_id}}', `Using correct material ratio`)"
                                                    value="Using correct material ratio"
                                                    name="product_recovery_measures[]"
                                                    {{ in_array("Using correct material ratio", $checkedProductRecoveryMethods) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Using correct material ratio </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeProductRecoveryMeasure(this, '{{$company->company_id}}', `Using standard measuring equipment`)"
                                                    value="Using standard measuring equipment"
                                                    name="product_recovery_measures[]"
                                                    {{ in_array("Using standard measuring equipment", $checkedProductRecoveryMethods) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Using standard measuring equipment </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeProductRecoveryMeasure(this, '{{$company->company_id}}', `Adequate chemical/ material storage facility`)"
                                                    value="Adequate chemical/ material storage facility"
                                                    name="product_recovery_measures[]"
                                                    {{ in_array("Adequate chemical/ material storage facility", $checkedProductRecoveryMethods) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Adequate chemical/ material storage facility </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeProductRecoveryMeasure(this, '{{$company->company_id}}', `Adequate container seal to prevent spill`)"
                                                    value="Adequate container seal to prevent spill"
                                                    name="product_recovery_measures[]"
                                                    {{ in_array("Adequate container seal to prevent spill", $checkedProductRecoveryMethods) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Adequate container seal to prevent spill </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeProductRecoveryMeasure(this, '{{$company->company_id}}', `Recycling`)"
                                                    value="Recycling"
                                                    name="product_recovery_measures[]"
                                                    {{ in_array("Recycling", $checkedProductRecoveryMethods) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Recycling </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeProductRecoveryMeasure(this, '{{$company->company_id}}', `Filtration`)"
                                                    value="Filtration"
                                                    name="product_recovery_measures[]"
                                                    {{ in_array("Filtration", $checkedProductRecoveryMethods) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Filtration </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id=""
                                                    onchange="ChangeProductRecoveryMeasure(this, '{{$company->company_id}}', `Extended Producer Responsibility(EPR)`)"
                                                    value="Extended Producer Responsibility(EPR)"
                                                    name="product_recovery_measures[]"
                                                    {{ in_array("Extended Producer Responsibility(EPR)", $checkedProductRecoveryMethods) ? "checked" : "" }}>
                                                <label class="form-check-label" for=""> Extended Producer Responsibility(EPR) </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!--  -->
                            </div>
                        </div>
                    </div>
                    @if(auth()->guard('admin')->check())
                        <!-- RECP Approval and Disapproval Section -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="recpHeading">
                                <button class="accordion-button collapsed p-3 bg-primary text-white" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#recpCollapse" aria-expanded="false" aria-controls="recpCollapse">
                                    <i class="las la-check-circle me-2" style="font-size: 1.5rem;"></i> 
                                    <span class="fw-bold">RECP Compliance</span>
                                </button>
                            </h2>
                            <div id="recpCollapse" class="accordion-collapse collapse" aria-labelledby="recpHeading">
                                <div class="accordion-body">
                                    <div class="card shadow-sm border-0 mb-4">
                                        <div class="card-body">
                                            @if(auth()->guard('admin')->check())
                                                <form id="recp-form">
                                                    <input type="hidden" name="company_id" value="{{ $company->company_id }}">
                                                    <div class="mb-3">
                                                        <label for="recpStatus" class="form-label">Select RECP Status</label>
                                                        <select class="form-select" id="recpStatus" name="recp_status" required>
                                                            <option value="" disabled {{ empty($recp_state->status) ? 'selected' : '' }}>Select status</option>
                                                            @foreach(['approved' => 'Approved', 'disapproved' => 'Disapproved', 'pending' => 'Pending'] as $value => $label)
                                                                <option value="{{ $value }}" {{ ($recp_state->status ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="recpComments" class="form-label">Comments</label>
                                                        <textarea class="form-control" id="recpComments" name="recp_comment" rows="3" placeholder="Enter comments">{{ old('recp_comment', $recp_state->remark ?? '') }}</textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End RECP Approval and Disapproval Section -->
                    @endif
                </div>
            </div>
             <!-- RECP -->
            <div class="tab-pane fade" id="finance" role="tabpanel" aria-labelledby="finance-tab">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-gradient-primary text-white py-3 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold"><i class="las la-wallet me-2"></i>Finance Management</h5>
                    </div>

                    <div class="card-body">
                        <!-- Finance Tabs Navigation -->
                        <ul class="nav nav-pills mb-4 justify-content-center flex-wrap" id="financeTabs" role="tablist">
                            <li class="nav-item m-1" role="presentation">
                                <button class="nav-link active" id="fin-overview-tab" data-bs-toggle="tab"
                                    data-bs-target="#fin-overview" type="button" role="tab" aria-controls="fin-overview"
                                    aria-selected="true"><i class="las la-chart-line me-1"></i> Overview</button>
                            </li>
                            <li class="nav-item m-1" role="presentation">
                                <button class="nav-link" id="fin-income-tab" data-bs-toggle="tab"
                                    data-bs-target="#fin-income" type="button" role="tab" aria-controls="fin-income"
                                    aria-selected="false"><i class="las la-money-bill-wave me-1"></i> Income</button>
                            </li>
                            <li class="nav-item m-1" role="presentation">
                                <button class="nav-link" id="fin-expense-tab" data-bs-toggle="tab"
                                    data-bs-target="#fin-expense" type="button" role="tab" aria-controls="fin-expense"
                                    aria-selected="false"><i class="las la-receipt me-1"></i> Expenses</button>
                            </li>
                            <li class="nav-item m-1" role="presentation">
                                <button class="nav-link" id="fin-budget-tab" data-bs-toggle="tab"
                                    data-bs-target="#fin-budget" type="button" role="tab" aria-controls="fin-budget"
                                    aria-selected="false"><i class="las la-coins me-1"></i> Budgets</button>
                            </li>
                        </ul>

                        <!-- Finance Tab Content -->
                        <div class="tab-content" id="financeTabsContent">
                            <!-- =================== OVERVIEW =================== -->
                            <div class="tab-pane fade show active" id="fin-overview" role="tabpanel" aria-labelledby="fin-overview-tab">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="card text-center shadow-sm border-0">
                                            <div class="card-body">
                                                <h6 class="text-muted mb-1">Total Revenue</h6>
                                                <h4 class="fw-bold text-success">₦{{ number_format($totalRevenue ?? 0, 2) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card text-center shadow-sm border-0">
                                            <div class="card-body">
                                                <h6 class="text-muted mb-1">Total Expenses</h6>
                                                <h4 class="fw-bold text-danger">₦{{ number_format($totalExpense ?? 0, 2) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card text-center shadow-sm border-0">
                                            <div class="card-body">
                                                <h6 class="text-muted mb-1">Profit / Loss</h6>
                                                @php $profit = ($totalRevenue ?? 0) - ($totalExpense ?? 0); @endphp
                                                <h4 class="fw-bold {{ $profit >= 0 ? 'text-success' : 'text-danger' }}">
                                                    ₦{{ number_format($profit, 2) }}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card text-center shadow-sm border-0">
                                            <div class="card-body">
                                                <h6 class="text-muted mb-1">Active Budgets</h6>
                                                <h4 class="fw-bold text-primary">{{ $activeBudgets ?? 0 }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <canvas id="financeChart" height="100"></canvas>
                                </div>
                            </div>

                            <!-- =================== INCOME =================== -->
                            <div class="tab-pane fade" id="fin-income" role="tabpanel" aria-labelledby="fin-income-tab">
                                <form method="POST" action="" class="card shadow-sm border-0 mb-3">
                                    @csrf
                                    <div class="card-header bg-light fw-semibold">
                                        <i class="las la-plus-circle me-1"></i> Record New Income
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Source</label>
                                                <input type="text" name="source" class="form-control" placeholder="Sales, Investment, etc.">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Amount (₦)</label>
                                                <input type="number" name="amount" step="0.01" class="form-control" placeholder="0.00">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Date</label>
                                                <input type="date" name="date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="text-end mt-3">
                                            <button class="btn btn-primary btn-sm px-3"><i class="las la-save me-1"></i> Save Income</button>
                                        </div>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table class="table table-striped align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Source</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($incomes as $income)
                                                <tr>
                                                    <td>{{ $income->source }}</td>
                                                    <td>₦{{ number_format($income->amount, 2) }}</td>
                                                    <td>{{ $income->date->format('d M Y') }}</td>
                                                    <td class="text-end">
                                                        <button class="btn btn-sm btn-outline-primary"><i class="las la-edit"></i></button>
                                                        <button class="btn btn-sm btn-outline-danger"><i class="las la-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- =================== EXPENSES =================== -->
                            <div class="tab-pane fade" id="fin-expense" role="tabpanel" aria-labelledby="fin-expense-tab">
                                <form method="POST" action="" class="card shadow-sm border-0 mb-3">
                                    @csrf
                                    <div class="card-header bg-light fw-semibold">
                                        <i class="las la-plus me-1"></i> Record Expense
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Category</label>
                                                <input type="text" name="category" class="form-control" placeholder="Utilities, Rent, etc.">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Amount (₦)</label>
                                                <input type="number" name="amount" step="0.01" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Date</label>
                                                <input type="date" name="date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="text-end mt-3">
                                            <button class="btn btn-primary btn-sm px-3"><i class="las la-save me-1"></i> Save Expense</button>
                                        </div>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Category</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($expenses as $expense)
                                                <tr>
                                                    <td>{{ $expense->category }}</td>
                                                    <td>₦{{ number_format($expense->amount, 2) }}</td>
                                                    <td>{{ $expense->date->format('d M Y') }}</td>
                                                    <td class="text-end">
                                                        <button class="btn btn-sm btn-outline-primary"><i class="las la-edit"></i></button>
                                                        <button class="btn btn-sm btn-outline-danger"><i class="las la-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- =================== BUDGETS =================== -->
                            <div class="tab-pane fade" id="fin-budget" role="tabpanel" aria-labelledby="fin-budget-tab">
                                <div class="alert alert-info">
                                    Budget planning, allocation and monitoring tools will appear here.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- HRMS -->
            <div class="tab-pane fade" id="hr" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Human Resources</h4>
                    </div>
                    <div class="card-body">
                        <!-- HR Sub Tabs -->
                        <ul class="nav nav-tabs mb-3" id="hrSubTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="hr-overview-tab" data-bs-toggle="tab" data-bs-target="#hr-overview" type="button" role="tab">
                                    <i class="la la-tachometer-alt"></i> Overview
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="department-tab" data-bs-toggle="tab" data-bs-target="#department" type="button" role="tab">
                                    <i class="la la-user-plus"></i> Departments & Recruitment
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="employees-tab" data-bs-toggle="tab" data-bs-target="#employees" type="button" role="tab">
                                    <i class="la la-users"></i> Employees
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="training-tab" data-bs-toggle="tab" data-bs-target="#training" type="button" role="tab">
                                    <i class="la la-graduation-cap"></i> Training & Development
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="performance-tab" data-bs-toggle="tab" data-bs-target="#performance" type="button" role="tab">
                                    <i class="la la-chart-bar"></i> Performance
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="welfare-tab" data-bs-toggle="tab" data-bs-target="#welfare" type="button" role="tab">
                                    <i class="la la-heart"></i> Welfare
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="hrSubTabContent">
                            <div class="tab-pane fade show active" id="hr-overview" role="tabpanel" aria-labelledby="hr-overview-tab">
                                <h5 class="section-heading mb-3">HR Overview</h5>
                                <p>Summary of HR activities, employee statistics, and key performance indicators.</p>
                            </div>
                            <div class="tab-pane fade" id="department" role="tabpanel" aria-labelledby="department-tab">
                                <h5 class="section-heading mb-3">Departments & Recruitment</h5>
                                <p class="text-muted">Manage company departments, job postings, and recruitment processes.</p>

                                <!-- Inner Tabs -->
                                <ul class="nav nav-tabs" id="departmentRecruitmentTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="departments-subtab" data-bs-toggle="tab"
                                            data-bs-target="#departments" type="button" role="tab" aria-controls="departments"
                                            aria-selected="true">
                                            <i class="bi bi-diagram-3"></i> Departments
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="recruitment-subtab" data-bs-toggle="tab"
                                            data-bs-target="#recruitment" type="button" role="tab" aria-controls="recruitment"
                                            aria-selected="false">
                                            <i class="bi bi-person-badge"></i> Recruitment
                                        </button>
                                    </li>
                                </ul>

                                <!-- Inner Tab Content -->
                                <div class="tab-content mt-3" id="departmentRecruitmentTabsContent">
                                    <!-- Departments Tab -->
                                    <div class="tab-pane fade show active" id="departments" role="tabpanel"
                                        aria-labelledby="departments-subtab">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0">Departments</h6>
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                                                <i class="la la-plus-circle"></i> Add Department
                                            </button>
                                        </div>

                                        <table class="table table-hover table-stripped rounded shadow-sm align-middle w-100" id="tbl-departments">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Manager</th>
                                                    <th>Employees</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                        <div class="empty-state d-none" id="emptyItems">
                                            <img src="{{ asset('adminAssets/images/illustrate/addItem.svg') }}" alt="No waste items">
                                            <h5 class="mt-3">No waste items yet</h5>
                                            <p>Create waste items to start tracking materials and quantities.</p>
                                            <button class="btn btn-gradient" id="addItemEmpty">Add Waste Item</button>
                                        </div>
                                    </div>

                                    <!-- Recruitment Tab -->
                                    <div class="tab-pane fade" id="recruitment" role="tabpanel"
                                        aria-labelledby="recruitment-subtab">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0">Job Postings</h6>
                                            <button class="btn btn-sm btn-primary">
                                                <i class="bi bi-plus-circle"></i> Add Job Posting
                                            </button>
                                        </div>

                                        <table class="table table-striped table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Job Title</th>
                                                    <th>Department</th>
                                                    <th>Status</th>
                                                    <th>Applicants</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Software Engineer</td>
                                                    <td>IT Department</td>
                                                    <td><span class="badge bg-info">Open</span></td>
                                                    <td>15</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></button>
                                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>HR Assistant</td>
                                                    <td>Human Resources</td>
                                                    <td><span class="badge bg-secondary">Closed</span></td>
                                                    <td>22</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></button>
                                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Employees Tab -->
                            <div class="tab-pane fade" id="employees" role="tabpanel" aria-labelledby="employees-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="">
                                                <h5 class="section-heading mb-3">Employee Management</h5>
                                                <p>Overview of all employees, departments, and job roles.</p>
                                            </div>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                                                <i class="las la-user-plus me-1"></i> Add Employee
                                            </button>
                                        </div>
                                        <table class="table table-striped mb-0" id="tbl-employees">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Department</th>
                                                    <th class="text-end">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Training Tab -->
                            <div class="tab-pane fade" id="training" role="tabpanel" aria-labelledby="training-tab">
                                <h5 class="section-heading mb-3">Training & Development</h5>
                                <ul class="list-group">
                                    @foreach($trainings as $training)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $training->title }}</strong> <br>
                                                <small>{{ $training->description }}</small>
                                            </div>
                                            <span class="badge bg-success">{{ \Carbon\Carbon::parse($training->date)->format('M Y') }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Performance Tab -->
                            <div class="tab-pane fade" id="performance" role="tabpanel" aria-labelledby="performance-tab">
                                <h5 class="section-heading mb-3">Performance Appraisal</h5>
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Employee</th>
                                            <th>Score</th>
                                            <th>Period</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($performances as $perf)
                                            <tr>
                                                <td>{{ $perf->employee->name }}</td>
                                                <td><span class="badge bg-info">{{ $perf->score }}%</span></td>
                                                <td>{{ $perf->period }}</td>
                                                <td>{{ $perf->remarks }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Welfare Tab -->
                            <div class="tab-pane fade" id="welfare" role="tabpanel" aria-labelledby="welfare-tab">
                                <h5 class="section-heading mb-3">Employee Welfare</h5>
                                <div class="list-group">
                                    @foreach($welfarePrograms as $program)
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">{{ $program->title }}</h6>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($program->created_at)->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-1">{{ $program->description }}</p>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="inventory" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-bottom-0">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h4 class="card-title mb-0">
                                <i class="la la-box text-primary me-2"></i>Inventory Management
                            </h4>

                            <ul class="nav nav-pills mt-2 mt-md-0" id="inventoryTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="inv-general-tab" data-bs-toggle="tab" href="#inv-general"
                                        role="tab" aria-controls="inv-general" aria-selected="true">
                                        <i class="la la-warehouse me-1"></i>General
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="inv-chemical-tab" data-bs-toggle="tab" href="#inv-chemical"
                                        role="tab" aria-controls="inv-chemical" aria-selected="false">
                                        <i class="la la-flask me-1"></i>Chemicals
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="inv-water-tab" data-bs-toggle="tab" href="#inv-water"
                                        role="tab" aria-controls="inv-water" aria-selected="false">
                                        <i class="la la-tint me-1"></i>Water
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="inv-equipment-tab" data-bs-toggle="tab" href="#inv-equipment"
                                        role="tab" aria-controls="inv-equipment" aria-selected="false">
                                        <i class="la la-cogs me-1"></i>Equipment
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="inv-raw-tab" data-bs-toggle="tab" href="#inv-raw"
                                        role="tab" aria-controls="inv-raw" aria-selected="false">
                                        <i class="la la-cube me-1"></i>Raw Materials
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#productsInventory" role="tab">
                                        <i class="la la-box-open"></i> Products
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="inventoryTabsContent">

                            <!-- 🌐 GENERAL INVENTORY -->
                            <div class="tab-pane fade show active" id="inv-general" role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <div class="card bg-light text-center p-3">
                                            <h6 class="text-muted">Total Items</h6>
                                            <h3 class="fw-bold text-primary">0</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-light text-center p-3">
                                            <h6 class="text-muted">Low Stock</h6>
                                            <h3 class="fw-bold text-warning">{{ $lowStockCount ?? 0 }}</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-light text-center p-3">
                                            <h6 class="text-muted">Out of Stock</h6>
                                            <h3 class="fw-bold text-danger">{{ $outOfStockCount ?? 0 }}</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-light text-center p-3">
                                            <h6 class="text-muted">Active Categories</h6>
                                            <h3 class="fw-bold text-success">{{ $activeCategories ?? 0 }}</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0"><i class="la la-list me-1 text-primary"></i>Inventory List</h5>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addGeneralModal"><i class="la la-plus-circle me-1"></i>Add Item</button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle" id="generalTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Item</th>
                                                <th>Category</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Status</th>
                                                <th>Updated</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($generalItems as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->category }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>{{ $item->unit }}</td>
                                                    <td>
                                                        @if($item->quantity == 0)
                                                            <span class="badge bg-danger">Out of Stock</span>
                                                        @elseif($item->quantity < 10)
                                                            <span class="badge bg-warning">Low</span>
                                                        @else
                                                            <span class="badge bg-success">In Stock</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->updated_at->diffForHumans() }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- ⚗️ CHEMICAL INVENTORY -->
                            <div class="tab-pane fade" id="inv-chemical" role="tabpanel">
                                <div class="d-flex justify-content-between mb-3 align-items-center">
                                    <h5><i class="la la-flask text-primary me-1"></i>Chemical Inventory</h5>
                                    <button class="btn btn-sm btn-outline-primary"  data-bs-toggle="modal" data-bs-target="#addChemicalModal">
                                        <i class="la la-plus-circle me-1"></i>Add Chemical</button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped" id="chemicalTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Hazardous</th>
                                                <th>Storage</th>
                                                <th>Updated</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($chemicalItems as $chem)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $chem->name }}</td>
                                                    <td>{{ $chem->type }}</td>
                                                    <td>{{ $chem->quantity }}</td>
                                                    <td>{{ $chem->unit }}</td>
                                                    <td>
                                                        <span class="badge {{ $chem->is_hazardous ? 'bg-danger' : 'bg-success' }}">
                                                            {{ $chem->is_hazardous ? 'Yes' : 'No' }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $chem->storage_location }}</td>
                                                    <td>{{ $chem->updated_at->diffForHumans() }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- 💧 WATER INVENTORY -->
                            <div class="tab-pane fade" id="inv-water" role="tabpanel">
                                <div class="d-flex justify-content-between mb-3 align-items-center">
                                    <h5><i class="la la-tint text-primary me-1"></i>Water Usage</h5>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addWaterModal"><i class="la la-plus-circle me-1"></i>Add Record</button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped" id="waterTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Source</th>
                                                <th>Usage (L)</th>
                                                <th>Recycled (%)</th>
                                                <th>Quality</th>
                                                <th>Updated</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($waterRecords as $w)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ ucfirst($w->source) }}</td>
                                                    <td>{{ $w->usage }}</td>
                                                    <td>{{ $w->recycled_percentage }}%</td>
                                                    <td>{{ ucfirst($w->quality_level) }}</td>
                                                    <td>{{ $w->updated_at->diffForHumans() }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- ⚙️ EQUIPMENT -->
                            <div class="tab-pane fade" id="inv-equipment" role="tabpanel">
                                <div class="d-flex justify-content-between mb-3 align-items-center">
                                    <h5><i class="la la-cogs text-primary me-1"></i>Equipment</h5>
                                    <button class="btn btn-sm btn-outline-primary"><i class="la la-plus-circle me-1"></i>Add Equipment</button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped" id="equipmentTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Condition</th>
                                                <th>Last Maintenance</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($equipmentList as $eq)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $eq->name }}</td>
                                                    <td>{{ $eq->type }}</td>
                                                    <td>{{ $eq->condition }}</td>
                                                    <td>{{ $eq->last_maintenance->diffForHumans() }}</td>
                                                    <td>
                                                        <span class="badge {{ $eq->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                            {{ $eq->is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- 🧱 RAW MATERIALS -->
                            <div class="tab-pane fade" id="inv-raw" role="tabpanel">
                                <div class="d-flex justify-content-between mb-3 align-items-center">
                                    <h5><i class="la la-cube text-primary me-1"></i>Raw Materials</h5>
                                    <button class="btn btn-sm btn-outline-primary"><i class="la la-plus-circle me-1"></i>Add Material</button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped" id="rawTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Supplier</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Reorder Level</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rawMaterials as $raw)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $raw->name }}</td>
                                                    <td>{{ $raw->supplier }}</td>
                                                    <td>{{ $raw->quantity }}</td>
                                                    <td>{{ $raw->unit }}</td>
                                                    <td>{{ $raw->reorder_level }}</td>
                                                    <td>
                                                        <span class="badge {{ $raw->quantity <= $raw->reorder_level ? 'bg-warning' : 'bg-success' }}">
                                                            {{ $raw->quantity <= $raw->reorder_level ? 'Reorder' : 'Available' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Products -->
                            <div class="tab-pane fade" id="productsInventory" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Product Name</th>
                                                <th>SKU</th>
                                                <th>Category</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Reorder Level</th>
                                                <th>Last Batch Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($products as $product)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $product->name }}</td>
                                                    <td>{{ $product->sku }}</td>
                                                    <td>{{ $product->category }}</td>
                                                    <td>{{ $product->quantity }}</td>
                                                    <td>{{ $product->unit }}</td>
                                                    <td>{{ $product->reorder_level }}</td>
                                                    <td>{{ $product->last_batch_date ? \Carbon\Carbon::parse($product->last_batch_date)->format('M d, Y') : '—' }}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary"><i class="la la-edit"></i></button>
                                                        <button class="btn btn-sm btn-outline-danger"><i class="la la-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="9" class="text-center text-muted">No product records found.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="tab-pane fade" id="operations" role="tabpanel" aria-labelledby="operations-tab">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-primary mb-0"><i class="la la-cogs me-2"></i>Operations Management</h5>
                    <button class="btn btn-gradient btn-sm" data-bs-toggle="modal" data-bs-target="#addOperationModal">
                        <i class="la la-plus me-1"></i> New Operation
                    </button>
                </div>

                <div class="card card-wm mb-3">
                    <div class="card-body">
                        <ul class="nav nav-pills nav-wm mb-3" id="operationTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#op-overview" role="tab">
                                    <i class="la la-chart-bar d-block"></i>
                                    Overview</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#annualPlan">
                                    <i class="la la-calendar d-block"></i>
                                    Annual Plan
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#productionOps" role="tab" aria-selected="true">
                                    <i class="la la-industry d-block"></i>Production
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#op-performance" role="tab">
                                    <i class="la la-tachometer-alt d-block"></i>
                                Performance</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#logisticsOps" role="tab" aria-selected="false">
                                    <i class="la la-truck d-block"></i>Logistics
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#qualityOps" role="tab" aria-selected="false">
                                    <i class="la la-check-circle d-block"></i>Quality Control
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#wasteOps" role="tab" aria-selected="false">
                                    <i class="la la-recycle d-block"></i>Waste Tracking
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#reports" role="tab">
                                    <i class="la la-file-alt d-block"></i>    
                                Reports</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Overview -->
                            <div class="tab-pane fade show active" id="op-overview" role="tabpanel">
                                <div class="text-muted mb-3">
                                    <p>Manage and monitor your organization’s annual operations, production batches, waste generation, and performance efficiency.</p>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="card text-center shadow-sm border-0 p-3">
                                            <h6 class="text-muted">Total Plans</h6>
                                            <h3 class="fw-bold text-primary">0</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card text-center shadow-sm border-0 p-3">
                                            <h6 class="text-muted">Total Batches</h6>
                                            <h3 class="fw-bold text-primary"></h3>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card text-center shadow-sm border-0 p-3">
                                            <h6 class="text-muted">Avg. Efficiency</h6>
                                            <h3 class="fw-bold text-success">{{ $avg_efficiency ?? '92%' }}</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card text-center shadow-sm border-0 p-3">
                                            <h6 class="text-muted">Total Waste (tons)</h6>
                                            <h3 class="fw-bold text-danger">{{ $total_waste ?? '18.4' }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Annual Plan -->
                            <div class="tab-pane fade" id="annualPlan" role="tabpanel">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                        <h5 class="fw-bold text-primary mb-0">Annual Operational Plan</h5>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addAnnualPlanModal">
                                            <i class="la la-plus-circle me-1"></i> New Annual Plan
                                        </button>
                                    </div>

                                    <div class="card-body">
                                        <table class="table table-hover align-middle table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Year</th>
                                                    <th>Expected Operations</th>
                                                    <th>Expected Production (Units)</th>
                                                    <th>Expected Water Usage (m³)</th>
                                                    <th>Expected Waste (kg)</th>
                                                    <th>Expected Chemical Usage (L)</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($annualPlans as $plan)
                                                    <tr>
                                                        <td>{{ $plan->year }}</td>
                                                        <td>{{ $plan->expected_operations }}</td>
                                                        <td>{{ number_format($plan->expected_production) }}</td>
                                                        <td>{{ number_format($plan->expected_water_usage) }}</td>
                                                        <td>{{ number_format($plan->expected_waste_generated) }}</td>
                                                        <td>{{ number_format($plan->expected_chemical_usage) }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $plan->status === 'active' ? 'success' : 'secondary' }}">
                                                                {{ ucfirst($plan->status) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                                    data-bs-target="#editPlanModal-{{ $plan->id }}">
                                                                    <i class="la la-edit"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-outline-danger">
                                                                    <i class="la la-trash"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <!-- Edit Modal -->
                                                    <div class="modal fade" id="editPlanModal-{{ $plan->id }}" tabindex="-1" aria-labelledby="editPlanLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <form method="POST" action="{{ route('admin.annual-plan.update', $plan->id) }}">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title fw-bold">Edit Annual Plan ({{ $plan->year }})</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row g-3">
                                                                            <div class="col-md-6">
                                                                                <label class="form-label fw-semibold">Expected Operations</label>
                                                                                <input type="text" name="expected_operations" value="{{ $plan->expected_operations }}" class="form-control">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label fw-semibold">Expected Production (Units)</label>
                                                                                <input type="number" name="expected_production" value="{{ $plan->expected_production }}" class="form-control">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label fw-semibold">Expected Water Usage (m³)</label>
                                                                                <input type="number" name="expected_water_usage" value="{{ $plan->expected_water_usage }}" class="form-control">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label fw-semibold">Expected Waste Generated (kg)</label>
                                                                                <input type="number" name="expected_waste_generated" value="{{ $plan->expected_waste_generated }}" class="form-control">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label fw-semibold">Expected Chemical Usage (L)</label>
                                                                                <input type="number" name="expected_chemical_usage" value="{{ $plan->expected_chemical_usage }}" class="form-control">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label fw-semibold">Status</label>
                                                                                <select name="status" class="form-select">
                                                                                    <option value="active" {{ $plan->status == 'active' ? 'selected' : '' }}>Active</option>
                                                                                    <option value="archived" {{ $plan->status == 'archived' ? 'selected' : '' }}>Archived</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn btn-primary">Update Plan</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center text-muted py-4">No annual plans created yet.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- end Annual Plan -->
                            <!-- Production -->
                            <div class="tab-pane fade" id="productionOps" role="tabpanel">
                                <h5 class="section-heading mb-3">Production Management</h5>

                                <!-- Sub-tabs -->
                                <ul class="nav nav-tabs" id="productionSubTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#prod-overview" role="tab">
                                            <i class="la la-industry me-1"></i>Overview
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#batch-operations" role="tab">
                                            <i class="la la-layer-group me-1"></i>Batch Operations
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="process-tab" data-bs-toggle="tab" href="#production-process" role="tab"
                                            aria-controls="production-process" aria-selected="false">
                                            <i class="la la-cogs me-1"></i>Production Process
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#resource-usage" role="tab">
                                            <i class="la la-flask me-1"></i>Resource Usage
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#production-analytics" role="tab">
                                            <i class="la la-chart-bar me-1"></i>Analytics
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content mt-3">

                                    <!-- OVERVIEW TAB -->
                                    <div class="tab-pane fade show active" id="prod-overview" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="card info-card shadow-sm text-center">
                                                    <div class="card-body">
                                                        <h6 class="text-muted">Annual Output</h6>
                                                        <h3 class="fw-bold text-primary">{{ number_format($productionStats['annual_output'] ?? 0) }}</h3>
                                                        <small class="text-success"><i class="la la-arrow-up"></i> {{ $productionStats['growth_rate'] ?? 0 }}% vs LY</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card info-card shadow-sm text-center">
                                                    <div class="card-body">
                                                        <h6 class="text-muted">Total Batches</h6>
                                                        <h3 class="fw-bold">{{ $productionStats['total_batches'] ?? 0 }}</h3>
                                                        <small class="text-muted">Active: {{ $productionStats['active_batches'] ?? 0 }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card info-card shadow-sm text-center">
                                                    <div class="card-body">
                                                        <h6 class="text-muted">Avg Efficiency</h6>
                                                        <h3 class="fw-bold text-success">{{ $productionStats['efficiency_ratio'] ?? '0.0' }}%</h3>
                                                        <small class="text-muted">Output per Input Unit</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card info-card shadow-sm text-center">
                                                    <div class="card-body">
                                                        <h6 class="text-muted">Waste Generated</h6>
                                                        <h3 class="fw-bold text-danger">{{ number_format($productionStats['waste_generated'] ?? 0) }} kg</h3>
                                                        <small class="text-muted">All batches combined</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- BATCH OPERATIONS -->
                                    <div class="tab-pane fade" id="batch-operations" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="fw-semibold">Batch Operations</h5>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBatchModal">
                                                <i class="la la-plus me-1"></i> Add Batch
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Batch ID</th>
                                                        <th>Product</th>
                                                        <th>Production Date</th>
                                                        <th>Output</th>
                                                        <th>Waste (kg)</th>
                                                        <th>Water Used (L)</th>
                                                        <th>Chemicals</th>
                                                        <th>Efficiency (%)</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($batches as $batch)
                                                        <tr>
                                                            <td class="fw-bold">{{ $batch->batch_code }}</td>
                                                            <td>{{ $batch->product_name }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($batch->production_date)->format('d M Y') }}</td>
                                                            <td>{{ number_format($batch->output_quantity, 2) }} {{ $batch->unit }}</td>
                                                            <td>{{ number_format($batch->waste_generated, 2) }}</td>
                                                            <td>{{ number_format($batch->water_used, 2) }}</td>
                                                            <td>{{ $batch->chemical_summary }}</td>
                                                            <td class="{{ $batch->efficiency >= 90 ? 'text-success' : 'text-danger' }}">
                                                                {{ number_format($batch->efficiency, 1) }}%
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-{{ $batch->status == 'Completed' ? 'success' : 'warning' }}">
                                                                    {{ ucfirst($batch->status) }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="9" class="text-center text-muted py-4">No batch data available.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- PRODUCTION PROCESS -->
                                    <div class="tab-pane fade" id="production-process" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="fw-semibold">Production Process</h5>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProcessModal">
                                                <i class="la la-plus me-1"></i>Add Process Step
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-striped align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Step No.</th>
                                                        <th>Process Name</th>
                                                        <th>Description</th>
                                                        <th>Expected Duration</th>
                                                        <th>Responsible Unit</th>
                                                        <th>Resources Required</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($processes as $step)
                                                        <tr>
                                                            <td>{{ $step->step_number }}</td>
                                                            <td>{{ $step->name }}</td>
                                                            <td>{{ $step->description }}</td>
                                                            <td>{{ $step->expected_duration }} hrs</td>
                                                            <td>{{ $step->responsible_unit }}</td>
                                                            <td>{{ $step->resources_required }}</td>
                                                            <td>
                                                                <span class="badge {{ $step->status == 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                                                    {{ $step->status }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="7" class="text-center text-muted">No production process defined yet.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- RESOURCE USAGE -->
                                    <div class="tab-pane fade" id="resource-usage" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card shadow-sm h-100">
                                                    <div class="card-body">
                                                        <h6 class="fw-semibold mb-3 text-primary"><i class="la la-tint me-1"></i>Water Usage</h6>
                                                        <p>Total Water Used: <strong>{{ number_format($resourceUsage['water_total'] ?? 0) }} L</strong></p>
                                                        <p>Avg Water per Batch: <strong>{{ number_format($resourceUsage['avg_water_per_batch'] ?? 0) }} L</strong></p>
                                                        <p>Efficiency (Output/Litre): <strong>{{ $resourceUsage['efficiency'] ?? 0 }}</strong></p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="card shadow-sm h-100">
                                                    <div class="card-body">
                                                        <h6 class="fw-semibold mb-3 text-danger"><i class="la la-flask me-1"></i>Chemical Usage</h6>
                                                        <ul class="list-group">
                                                            @foreach($chemicalUsage as $chemical)
                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                    {{ $chemical->chemical_name }}
                                                                    <span class="badge bg-secondary">{{ $chemical->quantity_used }} {{ $chemical->unit }}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ANALYTICS -->
                                    <div class="tab-pane fade" id="production-analytics" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card shadow-sm">
                                                    <div class="card-header bg-light">
                                                        <h6 class="fw-bold mb-0"><i class="la la-chart-line me-1"></i>Annual Production Trend</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <canvas id="productionTrendChart" height="150"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card shadow-sm">
                                                    <div class="card-header bg-light">
                                                        <h6 class="fw-bold mb-0"><i class="la la-balance-scale me-1"></i>Efficiency vs Waste Ratio</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <canvas id="efficiencyWasteChart" height="150"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>


                            </div>

                            <!-- Operational Performance -->
                            <div class="tab-pane fade" id="op-performance" role="tabpanel">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom">
                                        <h5 class="fw-bold text-primary mb-0">Operational Performance Tracking</h5>
                                        <select id="performanceYear" class="form-select w-auto">
                                            @foreach($years as $year)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="card-body">
                                        <!-- Summary Metrics -->
                                        <div class="row text-center mb-4">
                                            <div class="col-md-3">
                                                <div class="p-3 rounded bg-light shadow-sm">
                                                    <h6 class="text-muted mb-1">Production Efficiency</h6>
                                                    <h4 class="fw-bold text-success">{{ $performance['production_efficiency'] ?? '0' }}%</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="p-3 rounded bg-light shadow-sm">
                                                    <h6 class="text-muted mb-1">Water Efficiency</h6>
                                                    <h4 class="fw-bold text-primary">{{ $performance['water_efficiency'] ?? '0' }}%</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="p-3 rounded bg-light shadow-sm">
                                                    <h6 class="text-muted mb-1">Waste Reduction</h6>
                                                    <h4 class="fw-bold text-danger">{{ $performance['waste_reduction'] ?? '0' }}%</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="p-3 rounded bg-light shadow-sm">
                                                    <h6 class="text-muted mb-1">Chemical Efficiency</h6>
                                                    <h4 class="fw-bold text-info">{{ $performance['chemical_efficiency'] ?? '0' }}%</h4>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Charts -->
                                        <div class="row">
                                            <div class="col-md-6 mb-4">
                                                <div class="card shadow-sm border-0 h-100">
                                                    <div class="card-header bg-white border-bottom">
                                                        <h6 class="fw-bold text-primary mb-0">Production: Planned vs Actual</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <canvas id="productionChart" height="150"></canvas>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-4">
                                                <div class="card shadow-sm border-0 h-100">
                                                    <div class="card-header bg-white border-bottom">
                                                        <h6 class="fw-bold text-primary mb-0">Water Usage: Planned vs Actual</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <canvas id="waterChart" height="150"></canvas>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-4">
                                                <div class="card shadow-sm border-0 h-100">
                                                    <div class="card-header bg-white border-bottom">
                                                        <h6 class="fw-bold text-primary mb-0">Waste Generation by Category</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <canvas id="wasteChart" height="150"></canvas>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-4">
                                                <div class="card shadow-sm border-0 h-100">
                                                    <div class="card-header bg-white border-bottom">
                                                        <h6 class="fw-bold text-primary mb-0">Chemical Usage per Batch</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <canvas id="chemicalChart" height="150"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Efficiency Ratios -->
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-white border-bottom">
                                                <h6 class="fw-bold text-primary mb-0">Efficiency Ratios</h6>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-striped align-middle">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Metric</th>
                                                            <th>Planned</th>
                                                            <th>Actual</th>
                                                            <th>Efficiency (%)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Output per Water Unit</td>
                                                            <td>{{ number_format($efficiency['planned_output_per_water'] ?? 0, 2) }}</td>
                                                            <td>{{ number_format($efficiency['actual_output_per_water'] ?? 0, 2) }}</td>
                                                            <td>{{ number_format($efficiency['eff_output_per_water'] ?? 0, 2) }}%</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Output per Chemical Unit</td>
                                                            <td>{{ number_format($efficiency['planned_output_per_chemical'] ?? 0, 2) }}</td>
                                                            <td>{{ number_format($efficiency['actual_output_per_chemical'] ?? 0, 2) }}</td>
                                                            <td>{{ number_format($efficiency['eff_output_per_chemical'] ?? 0, 2) }}%</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Waste per Production Unit</td>
                                                            <td>{{ number_format($efficiency['planned_waste_per_unit'] ?? 0, 2) }}</td>
                                                            <td>{{ number_format($efficiency['actual_waste_per_unit'] ?? 0, 2) }}</td>
                                                            <td>{{ number_format($efficiency['eff_waste_per_unit'] ?? 0, 2) }}%</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end operational performance -->
                            <!-- Logistics -->
                            <div class="tab-pane fade" id="logisticsOps" role="tabpanel">
                                <h5 class="section-heading mb-3">Logistics & Supply Chain Management</h5>

                                <!-- Logistics Sub Tabs -->
                                <ul class="nav nav-tabs" id="logisticsSubTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="log-overview-tab" data-bs-toggle="tab" href="#log-overview" role="tab" aria-selected="true">
                                            <i class="la la-truck me-1"></i>Overview
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="transport-tab" data-bs-toggle="tab" href="#transport" role="tab" aria-selected="false">
                                            <i class="la la-road me-1"></i>Transport Records
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="fuel-tab" data-bs-toggle="tab" href="#fuel" role="tab" aria-selected="false">
                                            <i class="la la-gas-pump me-1"></i>Fuel Usage
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="supply-tab" data-bs-toggle="tab" href="#supply" role="tab" aria-selected="false">
                                            <i class="la la-boxes me-1"></i>Supply Chain
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content mt-3" id="logisticsSubTabsContent">
                                    <!-- OVERVIEW -->
                                    <div class="tab-pane fade show active" id="log-overview" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="card info-card shadow-sm">
                                                    <div class="card-body">
                                                        <h6 class="text-muted">Total Deliveries (Annual)</h6>
                                                        <h3 class="fw-bold">{{ $logisticsStats['total_deliveries'] ?? 0 }}</h3>
                                                        <small class="text-success"><i class="la la-arrow-up"></i> +{{ $logisticsStats['delivery_growth'] ?? 0 }}%</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="card info-card shadow-sm">
                                                    <div class="card-body">
                                                        <h6 class="text-muted">Fuel Efficiency</h6>
                                                        <h3 class="fw-bold">{{ $logisticsStats['fuel_efficiency'] ?? 0 }} km/L</h3>
                                                        <small class="text-muted">Average across fleet</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="card info-card shadow-sm">
                                                    <div class="card-body">
                                                        <h6 class="text-muted">Total Distance Covered</h6>
                                                        <h3 class="fw-bold">{{ number_format($logisticsStats['total_distance'] ?? 0) }} km</h3>
                                                        <small class="text-primary">All vehicles combined</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- TRANSPORT RECORDS -->
                                    <div class="tab-pane fade" id="transport" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="fw-semibold">Transport Records</h5>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addTransportModal">
                                                <i class="la la-plus me-1"></i>Add Record
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-striped align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Vehicle</th>
                                                        <th>Driver</th>
                                                        <th>From</th>
                                                        <th>To</th>
                                                        <th>Distance (km)</th>
                                                        <th>Fuel Used (L)</th>
                                                        <th>Goods</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($transportRecords as $record)
                                                        <tr>
                                                            <td>{{ \Carbon\Carbon::parse($record->date)->format('d M, Y') }}</td>
                                                            <td>{{ $record->vehicle_name }}</td>
                                                            <td>{{ $record->driver_name }}</td>
                                                            <td>{{ $record->from_location }}</td>
                                                            <td>{{ $record->to_location }}</td>
                                                            <td>{{ $record->distance }}</td>
                                                            <td>{{ $record->fuel_used }}</td>
                                                            <td>{{ $record->goods }}</td>
                                                            <td>
                                                                <span class="badge {{ $record->status == 'Delivered' ? 'bg-success' : 'bg-warning' }}">
                                                                    {{ $record->status }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="9" class="text-center text-muted">No transport records yet.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- FUEL USAGE -->
                                    <div class="tab-pane fade" id="fuel" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card shadow-sm">
                                                    <div class="card-body">
                                                        <h6 class="fw-semibold mb-3"><i class="la la-gas-pump me-1 text-danger"></i>Fuel Usage Summary</h6>
                                                        <p>Total Fuel Used (Annual): <strong>{{ number_format($fuelStats['total_fuel'] ?? 0) }} L</strong></p>
                                                        <p>Average Fuel/Trip: <strong>{{ $fuelStats['avg_per_trip'] ?? 0 }} L</strong></p>
                                                        <p>Cost Efficiency: <strong>${{ $fuelStats['cost_efficiency'] ?? 0 }} /km</strong></p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="card shadow-sm">
                                                    <div class="card-body">
                                                        <h6 class="fw-semibold mb-3"><i class="la la-truck-loading me-1 text-primary"></i>Top Performing Vehicles</h6>
                                                        <ul class="list-group">
                                                            @foreach($topVehicles as $vehicle)
                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                    {{ $vehicle->vehicle_name }}
                                                                    <span class="badge bg-secondary">{{ $vehicle->efficiency }} km/L</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SUPPLY CHAIN -->
                                    <div class="tab-pane fade" id="supply" role="tabpanel">
                                        <h5 class="fw-semibold mb-3">Supply Chain Activities</h5>
                                        <p class="text-muted">Track inbound and outbound supply chain operations, including suppliers, materials received, and delivery performance.</p>
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Supplier</th>
                                                        <th>Material</th>
                                                        <th>Quantity</th>
                                                        <th>Delivery Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($supplyChain as $supply)
                                                        <tr>
                                                            <td>{{ \Carbon\Carbon::parse($supply->date)->format('d M, Y') }}</td>
                                                            <td>{{ $supply->supplier_name }}</td>
                                                            <td>{{ $supply->material }}</td>
                                                            <td>{{ $supply->quantity }} {{ $supply->unit }}</td>
                                                            <td>
                                                                <span class="badge {{ $supply->status == 'Delivered' ? 'bg-success' : 'bg-warning' }}">
                                                                    {{ $supply->status }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="5" class="text-center text-muted">No supply records found.</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- ADD TRANSPORT MODAL -->
                                <div class="modal fade" id="addTransportModal" tabindex="-1" aria-labelledby="addTransportModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <form method="POST" action="">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addTransportModalLabel">Add Transport Record</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Vehicle</label>
                                                            <input type="text" name="vehicle_name" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Driver Name</label>
                                                            <input type="text" name="driver_name" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">From</label>
                                                            <input type="text" name="from_location" class="form-control">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">To</label>
                                                            <input type="text" name="to_location" class="form-control">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Distance (km)</label>
                                                            <input type="number" name="distance" step="0.1" class="form-control">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Fuel Used (L)</label>
                                                            <input type="number" name="fuel_used" step="0.1" class="form-control">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Goods</label>
                                                            <input type="text" name="goods" class="form-control">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="form-label">Status</label>
                                                            <select name="status" class="form-select">
                                                                <option value="In Transit">In Transit</option>
                                                                <option value="Delivered">Delivered</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Save Record</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Quality Control -->
                            <div class="tab-pane fade" id="qualityOps" role="tabpanel">
                                <h5 class="section-heading mb-3">Quality Control & Assurance</h5>

                                <!-- Sub-tabs for QC -->
                                <ul class="nav nav-tabs" id="qcSubTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="qc-overview-tab" data-bs-toggle="tab" href="#qc-overview" role="tab" aria-selected="true">
                                            <i class="la la-chart-bar me-1"></i>Overview
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="inspection-tab" data-bs-toggle="tab" href="#qc-inspections" role="tab" aria-selected="false">
                                            <i class="la la-search me-1"></i>Inspections
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="defects-tab" data-bs-toggle="tab" href="#qc-defects" role="tab" aria-selected="false">
                                            <i class="la la-times-circle me-1"></i>Defects
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="actions-tab" data-bs-toggle="tab" href="#qc-actions" role="tab" aria-selected="false">
                                            <i class="la la-tools me-1"></i>Corrective Actions
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content mt-3" id="qcSubTabsContent">
                                    <!-- OVERVIEW -->
                                    <div class="tab-pane fade show active" id="qc-overview" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="card info-card shadow-sm">
                                                    <div class="card-body">
                                                        <h6 class="text-muted">Inspections Completed</h6>
                                                        <h3 class="fw-bold">{{ $qcStats['inspections_done'] ?? 0 }}</h3>
                                                        <small class="text-success"><i class="la la-arrow-up"></i> +{{ $qcStats['inspection_growth'] ?? 0 }}% from last year</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="card info-card shadow-sm">
                                                    <div class="card-body">
                                                        <h6 class="text-muted">Defect Rate</h6>
                                                        <h3 class="fw-bold text-danger">{{ $qcStats['defect_rate'] ?? '0.0' }}%</h3>
                                                        <small class="text-muted">Target: <strong>{{ $qcStats['defect_target'] ?? '2.0' }}%</strong></small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="card info-card shadow-sm">
                                                    <div class="card-body">
                                                        <h6 class="text-muted">Customer Complaints Resolved</h6>
                                                        <h3 class="fw-bold">{{ $qcStats['complaints_resolved'] ?? 0 }}</h3>
                                                        <small class="text-primary">{{ $qcStats['complaint_resolution_rate'] ?? 0 }}% resolution rate</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- INSPECTIONS -->
                                    <div class="tab-pane fade" id="qc-inspections" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="fw-semibold">Quality Inspections</h5>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addInspectionModal">
                                                <i class="la la-plus me-1"></i>Add Inspection
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-striped align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Batch</th>
                                                        <th>Inspector</th>
                                                        <th>Product</th>
                                                        <th>Result</th>
                                                        <th>Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($inspections as $inspection)
                                                        <tr>
                                                            <td>{{ \Carbon\Carbon::parse($inspection->date)->format('d M, Y') }}</td>
                                                            <td>{{ $inspection->batch_code }}</td>
                                                            <td>{{ $inspection->inspector_name }}</td>
                                                            <td>{{ $inspection->product_name }}</td>
                                                            <td>
                                                                <span class="badge {{ $inspection->result == 'Pass' ? 'bg-success' : 'bg-danger' }}">
                                                                    {{ $inspection->result }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $inspection->remarks ?? '-' }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="6" class="text-center text-muted">No inspections recorded yet.</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- DEFECTS -->
                                    <div class="tab-pane fade" id="qc-defects" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="fw-semibold">Defect Tracking</h5>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDefectModal">
                                                <i class="la la-plus me-1"></i>Log Defect
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Product</th>
                                                        <th>Batch</th>
                                                        <th>Type</th>
                                                        <th>Severity</th>
                                                        <th>Detected By</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($defects as $defect)
                                                        <tr>
                                                            <td>{{ \Carbon\Carbon::parse($defect->date)->format('d M, Y') }}</td>
                                                            <td>{{ $defect->product_name }}</td>
                                                            <td>{{ $defect->batch_code }}</td>
                                                            <td>{{ ucfirst($defect->type) }}</td>
                                                            <td><span class="badge bg-{{ $defect->severity == 'High' ? 'danger' : ($defect->severity == 'Medium' ? 'warning' : 'secondary') }}">{{ $defect->severity }}</span></td>
                                                            <td>{{ $defect->detected_by }}</td>
                                                            <td>
                                                                <span class="badge {{ $defect->status == 'Resolved' ? 'bg-success' : 'bg-warning' }}">
                                                                    {{ $defect->status }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="7" class="text-center text-muted">No defects logged yet.</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- CORRECTIVE ACTIONS -->
                                    <div class="tab-pane fade" id="qc-actions" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="fw-semibold">Corrective Actions</h5>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addActionModal">
                                                <i class="la la-plus me-1"></i>Add Action
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-striped align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Defect</th>
                                                        <th>Action Taken</th>
                                                        <th>Responsible</th>
                                                        <th>Status</th>
                                                        <th>Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($actions as $action)
                                                        <tr>
                                                            <td>{{ \Carbon\Carbon::parse($action->date)->format('d M, Y') }}</td>
                                                            <td>{{ $action->defect_type }}</td>
                                                            <td>{{ $action->action_taken }}</td>
                                                            <td>{{ $action->responsible_person }}</td>
                                                            <td>
                                                                <span class="badge {{ $action->status == 'Completed' ? 'bg-success' : 'bg-warning' }}">
                                                                    {{ $action->status }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $action->remarks ?? '-' }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="6" class="text-center text-muted">No corrective actions recorded yet.</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Waste Tracking -->
                            <div class="tab-pane fade" id="wasteOps" role="tabpanel">
                                <h5 class="section-heading mb-3">Waste Tracking & Management</h5>

                                <!-- Sub-tabs -->
                                <ul class="nav nav-tabs" id="wasteSubTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="waste-overview-tab" data-bs-toggle="tab" href="#waste-overview" role="tab" aria-selected="true">
                                            <i class="la la-chart-pie me-1"></i>Overview
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="waste-batch-tab" data-bs-toggle="tab" href="#waste-batch" role="tab" aria-selected="false">
                                            <i class="la la-industry me-1"></i>Batch Waste Records
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="waste-disposal-tab" data-bs-toggle="tab" href="#waste-disposal" role="tab" aria-selected="false">
                                            <i class="la la-dumpster me-1"></i>Treatment & Disposal
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="waste-reports-tab" data-bs-toggle="tab" href="#waste-reports" role="tab" aria-selected="false">
                                            <i class="la la-file-alt me-1"></i>Reports
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content mt-3" id="wasteSubTabsContent">
                                    <!-- OVERVIEW -->
                                    <div class="tab-pane fade show active" id="waste-overview" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="card info-card shadow-sm">
                                                    <div class="card-body">
                                                        <h6 class="text-muted">Total Waste Generated</h6>
                                                        <h3 class="fw-bold">{{ number_format($wasteStats['total_generated'] ?? 0, 2) }} tons</h3>
                                                        <small class="text-muted">Year: {{ date('Y') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card info-card shadow-sm">
                                                    <div class="card-body">
                                                        <h6 class="text-muted">Hazardous Waste</h6>
                                                        <h3 class="fw-bold text-danger">{{ number_format($wasteStats['hazardous'] ?? 0, 2) }} tons</h3>
                                                        <small class="text-muted">{{ $wasteStats['hazardous_percent'] ?? 0 }}% of total</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card info-card shadow-sm">
                                                    <div class="card-body">
                                                        <h6 class="text-muted">Non-Hazardous Waste</h6>
                                                        <h3 class="fw-bold text-success">{{ number_format($wasteStats['non_hazardous'] ?? 0, 2) }} tons</h3>
                                                        <small class="text-muted">{{ $wasteStats['non_hazardous_percent'] ?? 0 }}% of total</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card info-card shadow-sm">
                                                    <div class="card-body">
                                                        <h6 class="text-muted">Recycling Efficiency</h6>
                                                        <h3 class="fw-bold text-primary">{{ $wasteStats['recycle_rate'] ?? 0 }}%</h3>
                                                        <small class="text-success">Goal: 80%</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- BATCH WASTE RECORDS -->
                                    <div class="tab-pane fade" id="waste-batch" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="fw-semibold">Batch Waste Records</h5>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addWasteModal">
                                                <i class="la la-plus me-1"></i>Add Record
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-striped align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Batch</th>
                                                        <th>Category</th>
                                                        <th>Waste Type</th>
                                                        <th>Quantity</th>
                                                        <th>Unit</th>
                                                        <th>Disposal Method</th>
                                                        <th>Generated By</th>
                                                        <th>Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($batchWastes as $waste)
                                                        <tr>
                                                            <td>{{ \Carbon\Carbon::parse($waste->date)->format('d M, Y') }}</td>
                                                            <td>{{ $waste->batch_code }}</td>
                                                            <td>
                                                                <span class="badge {{ $waste->is_hazardous ? 'bg-danger' : 'bg-success' }}">
                                                                    {{ $waste->is_hazardous ? 'Hazardous' : 'Non-Hazardous' }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $waste->waste_type }}</td>
                                                            <td>{{ number_format($waste->quantity, 2) }}</td>
                                                            <td>{{ $waste->unit }}</td>
                                                            <td>{{ $waste->disposal_method }}</td>
                                                            <td>{{ $waste->generated_by }}</td>
                                                            <td>{{ $waste->remarks ?? '-' }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="9" class="text-center text-muted">No batch waste recorded yet.</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- TREATMENT & DISPOSAL -->
                                    <div class="tab-pane fade" id="waste-disposal" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="fw-semibold">Waste Treatment & Disposal</h5>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDisposalModal">
                                                <i class="la la-plus me-1"></i>Add Disposal Record
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Waste Type</th>
                                                        <th>Quantity</th>
                                                        <th>Method</th>
                                                        <th>Disposal Site</th>
                                                        <th>Handled By</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($disposals as $disposal)
                                                        <tr>
                                                            <td>{{ \Carbon\Carbon::parse($disposal->date)->format('d M, Y') }}</td>
                                                            <td>{{ $disposal->waste_type }}</td>
                                                            <td>{{ number_format($disposal->quantity, 2) }}</td>
                                                            <td>{{ $disposal->method }}</td>
                                                            <td>{{ $disposal->disposal_site }}</td>
                                                            <td>{{ $disposal->handled_by }}</td>
                                                            <td>
                                                                <span class="badge {{ $disposal->status == 'Completed' ? 'bg-success' : 'bg-warning' }}">
                                                                    {{ $disposal->status }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="7" class="text-center text-muted">No disposal data recorded yet.</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- REPORTS -->
                                    <div class="tab-pane fade" id="waste-reports" role="tabpanel">
                                        <h5 class="fw-semibold mb-3">Annual Waste Reports</h5>
                                        <div class="card">
                                            <div class="card-body">
                                                <p>Generate summary and analytics for waste generation, treatment, and compliance per year.</p>
                                                <form class="row g-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Select Year</label>
                                                        <select class="form-select">
                                                            <option value="">-- Choose Year --</option>
                                                            @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 align-self-end">
                                                        <button type="button" class="btn btn-primary">
                                                            <i class="la la-file-alt me-1"></i>Generate Report
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="reports" role="tabpanel" aria-labelledby="reports-tab">
                                <h5 class="section-heading mb-3">Reports & Analytics</h5>
                                <p>Comprehensive operational performance reports, environmental tracking, and yearly insights.</p>

                                <div class="row g-3 mt-4">
                                    <div class="col-md-4">
                                        <div class="card shadow-sm border-0">
                                            <div class="card-body">
                                                <h6 class="fw-semibold"><i class="la la-chart-area text-primary me-1"></i> Annual Performance</h6>
                                                <p class="text-muted small mb-2">Compare expected vs actual production, waste, and resource use per year.</p>
                                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#annualPerformanceReportModal">View Report</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card shadow-sm border-0">
                                            <div class="card-body">
                                                <h6 class="fw-semibold"><i class="la la-industry text-success me-1"></i> Waste Analytics</h6>
                                                <p class="text-muted small mb-2">Breakdown of waste generation by category, batch, and disposal type.</p>
                                                <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#wasteAnalyticsModal">View Report</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card shadow-sm border-0">
                                            <div class="card-body">
                                                <h6 class="fw-semibold"><i class="la la-flask text-warning me-1"></i> Chemical Usage & Efficiency</h6>
                                                <p class="text-muted small mb-2">Track chemical consumption and calculate operational efficiency ratios.</p>
                                                <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#chemicalEfficiencyModal">View Report</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                <div class="card card-wm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="la la-cog me-2"></i>General Settings</h5>
                    </div>
                    <div class="card-body">
                        <form id="companySettingsForm" method="POST" action="">
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Company Name</label>
                                    <input type="text" name="company_name" class="form-control" value="{{ $company->company_name }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Industry</label>
                                    <input type="text" name="industry" class="form-control" value="{{ $company->industry }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ $company->email }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Website URL</label>
                                    <input type="url" name="website_url" class="form-control" value="{{ $company->website_url }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Primary Phone</label>
                                    <input type="text" name="primary_phone_number" class="form-control" value="{{ $company->primary_phone_number }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Secondary Phone</label>
                                    <input type="text" name="secondary_phone_number" class="form-control" value="{{ $company->secondary_phone_number }}">
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Address</label>
                                    <textarea name="address" class="form-control" rows="2">{{ $company->address }}</textarea>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">City</label>
                                    <input type="text" name="city" class="form-control" value="{{ $company->city }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">State</label>
                                    <input type="text" name="state" class="form-control" value="{{ $company->state }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Country</label>
                                    <input type="text" name="country" class="form-control" value="{{ $company->country }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Time Zone</label>
                                    <select name="time_zone" class="form-select">
                                        @foreach(timezone_identifiers_list() as $tz)
                                            <option value="{{ $tz }}" {{ $company->time_zone == $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Fiscal Year Start</label>
                                    <input type="month" name="fiscal_year_start" class="form-control" value="{{ $company->fiscal_year_start }}">
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-gradient"><i class="la la-save me-1"></i> Save Settings</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Preferences -->
                <div class="card card-wm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="la la-sliders-h me-2"></i>Preferences</h5>
                    </div>
                    <div class="card-body">
                        <form id="preferencesForm">
                            <div class="row align-items-center g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Theme Mode</label>
                                    <select class="form-select" name="theme_mode">
                                        <option value="light">Light</option>
                                        <option value="dark">Dark</option>
                                        <option value="auto">Auto</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Language</label>
                                    <select class="form-select" name="language">
                                        <option value="en">English</option>
                                        <option value="fr">French</option>
                                        <option value="es">Spanish</option>
                                        <option value="de">German</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Data Display Format</label>
                                    <select class="form-select" name="data_format">
                                        <option value="metric">Metric (kg, m³)</option>
                                        <option value="imperial">Imperial (lb, gal)</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="card card-wm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="la la-bell me-2"></i>Notifications</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                                    <label class="form-check-label" for="emailNotifications">Email Alerts</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="smsNotifications">
                                    <label class="form-check-label" for="smsNotifications">SMS Alerts</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="slackNotifications">
                                    <label class="form-check-label" for="slackNotifications">Slack/Teams Alerts</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Integrations -->
                <div class="card card-wm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="la la-plug me-2"></i>Integrations & Automation</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Connect external services or automate periodic tasks.</p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">API Key</label>
                                <input type="text" class="form-control" value="{{ $company->api_key ?? '************' }}" readonly>
                            </div>
                            <div class="col-md-6 text-end">
                                <button class="btn btn-outline-muted btn-sm mt-4"><i class="la la-sync me-1"></i>Regenerate Key</button>
                            </div>
                            <div class="col-md-12">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="autoSync" checked>
                                    <label class="form-check-label" for="autoSync">Enable Automatic Data Sync (daily)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @section('modals')
    <!-- Add your modal content here if needed -->
     @include('components.apps.company.modals._annual_plan')
     @include('components.apps.company.modals._batch')
     @include('components.apps.company.modals.qc_modals')
     @include('components.apps.company.modals.waste_modals')
     @include('components.apps.company.modals.report')
     @include('components.apps.company.modals.process_modals')
     @include('components.apps.company.modals.department_modals')
     @include('components.apps.company.modals.employee_modals')


<!-- --- Edit General Modal (for editing general items) --- -->
<div class="modal fade" id="editGeneralModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title">Edit Item</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="editGeneralForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="type" value="general">
        <input type="hidden" name="id" id="editGeneralId">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Item Name</label>
            <input id="editGeneralName" type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Category</label>
            <input id="editGeneralCategory" type="text" name="category" class="form-control">
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Quantity</label>
              <input id="editGeneralQty" type="number" name="quantity" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Unit</label>
              <input id="editGeneralUnit" type="text" name="unit" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary"><i class="la la-save me-1"></i> Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- --- Finish Add Water Modal (continued) --- -->
<div class="modal fade" id="addWaterModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Add Water Record</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="addWaterForm">
        @csrf
        <input type="hidden" name="type" value="water">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Source</label>
            <input name="source" class="form-control" required>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Usage (L)</label>
              <input name="usage" type="number" step="0.01" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Recycled (%)</label>
              <input name="recycled_percentage" type="number" step="0.01" class="form-control" min="0" max="100">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Quality Level</label>
            <select name="quality_level" class="form-select">
              <option value="" selected>Choose...</option>
              <option value="good">Good</option>
              <option value="fair">Fair</option>
              <option value="poor">Poor</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Last Test Date</label>
            <input name="last_test_date" type="date" class="form-control">
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-primary"><i class="la la-save me-1"></i> Save Record</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- --- Edit Water Modal --- -->
<div class="modal fade" id="editWaterModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title">Edit Water Record</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="editWaterForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="type" value="water">
        <input type="hidden" name="id" id="editWaterId">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Source</label>
            <input id="editWaterSource" name="source" class="form-control" required>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Usage (L)</label>
              <input id="editWaterUsage" name="usage" type="number" step="0.01" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Recycled (%)</label>
              <input id="editWaterRecycled" name="recycled_percentage" type="number" step="0.01" class="form-control" min="0" max="100">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Quality Level</label>
            <select id="editWaterQuality" name="quality_level" class="form-select">
              <option value="">Choose...</option>
              <option value="good">Good</option>
              <option value="fair">Fair</option>
              <option value="poor">Poor</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Last Test Date</label>
            <input id="editWaterTestDate" name="last_test_date" type="date" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary"><i class="la la-save me-1"></i> Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

    <!-- add general inventory item -->
    <div class="modal fade" id="addGeneralModal" tabindex="-1" aria-labelledby="addGeneralModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="la la-plus-circle me-2"></i>Add New Item</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addGeneralForm">
                <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Item Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                    <label class="form-label">Unit</label>
                    <input type="text" name="unit" class="form-control" required>
                    </div>
                </div>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><i class="la la-save me-1"></i> Save</button>
                </div>
            </form>
            </div>
        </div>
    </div>
    <!-- end add general inventory item -->


    <!-- add department -->

     <!-- Add employees -->

    <!-- workflow management -->
    <!-- Workflow Edit Form Modal -->
    <div class="modal fade" id="workflowEditModal" tabindex="-4" aria-labelledby="workflowEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="workflow-edit-form" method="post">
                    @csrf
                    <input type="hidden" name="workflow_id" id="workflow_edit_id">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="workflowEditModalLabel">Edit Workflow</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="workflow_edit_name" class="form-label">Workflow Name</label>
                                <input type="text" class="form-control" id="workflow_edit_name" name="workflow_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="workflow_edit_description" class="form-label">Description</label>
                                <input type="text" class="form-control" id="workflow_edit_description" name="workflow_description" />
                            </div>
                            <div class="col-md-6">
                                <label for="workflow_edit_status" class="form-label">Status</label>
                                <select class="form-select" id="workflow_edit_status" name="workflow_status" required>
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Workflow</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Workflow Edit Form Modal -->
    <!-- Stage Management Modal -->
    <div class="modal fade" id="stageManagementModal" tabindex="-4" aria-labelledby="stageManagementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="stage-management-form">
                    @csrf
                    <input type="hidden" name="company_id" value="{{ $company->company_id }}">
                    <input type="hidden" name="workflow_id" id="stage_workflow_id">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="stageManagementModalLabel">
                            Stage Management for <span id="stage-workflow-title"></span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="stage_name" class="form-label">Stage Name</label>
                                <input type="text" class="form-control" id="stage_name" name="stage_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="stage_description" class="form-label">Description</label>
                                <input type="text" class="form-control" id="stage_description" name="stage_description" placeholder="Enter stage description">
                            </div>
                            <div class="col-md-6">
                                <label for="stage_status" class="form-label">Status</label>
                                <select class="form-select" id="stage_status" name="stage_status" required>
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                    <option value="halted">Halted</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="stage_sequence_order" class="form-label">Sequence Order</label>
                                <input type="number" class="form-control" id="stage_sequence_order" name="stage_sequence_order" min="1" placeholder="Enter sequence order">
                            </div>
                        </div>
                        <div class="col-12 mt-3 text-end">
                            <button type="submit" class="btn btn-info">Save Stage</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive px-3 pb-3">
                    <table class="table table-striped mb-0 w-100" id="tbl-stage-management">
                        <thead class="table-light">
                            <tr>
                                <th>Stage Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Sequence Order</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamic rows will be appended here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Stage Management Modal -->
    <!-- Estimated Time Stage Modal (Improved Design & Pop-Out) -->
    <div class="modal fade" id="setStageDurationModal" tabindex="-1" aria-labelledby="estimatedTimeStageModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg animate__animated animate__zoomIn">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <form id="estimated-time-stage-form" autocomplete="off">
                    @csrf
                    <input type="hidden" name="company_id" value="{{ $company->company_id }}">
                    <input type="hidden" name="stage_id" id="estimated_time_stage_id">
                    <div class="modal-header bg-gradient-primary text-white rounded-top-4">
                        <h5 class="modal-title fw-bold" id="estimatedTimeStageModalLabel">
                            <i class="las la-clock me-2"></i> Set Estimated Time for Stage
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body bg-light">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="estimated_time_stage_name" class="form-label fw-semibold">Stage Name</label>
                                <input type="text" class="form-control" id="estimated_time_stage_name" name="stage_name" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="estimated_time" class="form-label fw-semibold">Estimated Time (hours)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="estimated_time" name="estimated_time" min="0" step="0.01" placeholder="Enter estimated time" required>
                                    <span class="input-group-text"><i class="las la-hourglass-half"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom-4">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="las la-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-info px-4 fw-bold">
                            <i class="las la-save"></i> Save Estimated Time
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Estimated Time Stage Modal -->
    <!-- Edit Stage Modal -->
    <div class="modal fade animate__animated animate__fadeInDown" id="editStageModal" tabindex="-3" aria-labelledby="editStageModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" style="z-index: 1200;">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-3">
                <form id="edit-stage-form" autocomplete="off">
                    @csrf
                    <input type="hidden" name="company_id" value="{{ $company->company_id }}">
                    <input type="hidden" name="stage_id" id="edit_stage_id">
                    <div class="modal-header bg-gradient-primary text-white rounded-top">
                        <h5 class="modal-title fw-bold" id="editStageModalLabel">
                            <i class="las la-edit me-2"></i> Edit Stage
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body bg-light">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_stage_name" class="form-label fw-semibold">Stage Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_stage_name" name="stage_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_stage_description" class="form-label fw-semibold">Description</label>
                                <input type="text" class="form-control" id="edit_stage_description" name="stage_description">
                            </div>
                            <div class="col-md-6">
                                <label for="edit_stage_status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="edit_stage_status" name="stage_status" required>
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                    <option value="halted">Halted</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_stage_sequence_order" class="form-label fw-semibold">Sequence Order</label>
                                <input type="number" class="form-control" id="edit_stage_sequence_order" name="stage_sequence_order" min="1">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="las la-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="las la-save"></i> Update Stage
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Stage Management Modal -->
     <!-- Task Management Modal -->
    <!-- Task Management Modal -->
    <div class="modal fade" id="taskManagementModal" tabindex="-3" aria-labelledby="taskManagementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="task-management-form">
                    @csrf
                    <input type="hidden" name="company_id" value="{{ $company->company_id }}">
                    <input type="hidden" name="stage_id" id="stage_task_id">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title" id="taskManagementModalLabel">Task Management</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="task_title" class="form-label">Task Title</label>
                                <input type="text" class="form-control" id="task_title" name="task_title" required>
                            </div>
                            <div class="col-md-6">
                                <div class="taggable-container " id="manager-tag-input-5">
                                    <label for="manager" class="form-label">Supervisor</label>
                                    <div class="manager-tag-input-5 manager-tag-input border-primary bg-light">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="task_due_date" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="task_due_date" name="task_due_date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="task_priority" class="form-label">Priority</label>
                                <select class="form-select" id="task_priority" name="task_priority" required>
                                    <option value="" selected disabled>Select Priority</option>
                                    <option value="high">High</option>
                                    <option value="medium">Medium</option>
                                    <option value="low">Low</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="task_description" class="form-label">Description</label>
                                <textarea class="form-control" id="task_description" name="task_description" rows="3" placeholder="Enter task description"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="task_status" class="form-label">Status</label>
                                <select class="form-select" id="task_status" name="task_status" required>
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="task_tags" class="form-label">Tags</label>
                                <div id="tagging-system-2"></div>
                            </div>
                        </div>
                        <div class="col-12 mt-3 text-end">
                            <button type="submit" class="btn btn-info">Save Task</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive px-3 pb-3">
                    <table class="table table-striped mb-0 w-100" id="tbl-task-management">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Supervisor</th>
                                <th>Due Date</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Tags</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamic rows will be appended here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Stage Task Modal -->
    <div class="modal fade" id="editStageTaskModal" tabindex="-2" aria-labelledby="editStageTaskModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" style="z-index: 1200;">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-3">

                <form id="edit-stage-task-form" autocomplete="off">
                    @csrf
                    <input type="hidden" name="company_id" value="{{ $company->company_id }}">
                    <input type="hidden" name="stage_task_id" id="edit_task_id">
                <div class="modal-header bg-gradient-primary text-white rounded-top">
                    <h5 class="modal-title" id="editStageTaskModalLabel">Edit Stage Task</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_task_title" class="form-label">Task Title</label>
                                <input type="text" class="form-control" id="edit_task_title" name="task_title" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_task_due_date" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="edit_task_due_date" name="task_due_date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_task_priority" class="form-label">Priority</label>
                                <select class="form-select" id="edit_task_priority" name="task_priority" required>
                                    <option value="" selected disabled>Select Priority</option>
                                    <option value="high">High</option>
                                    <option value="medium">Medium</option>
                                    <option value="low">Low</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_task_status" class="form-label">Status</label>
                                <select class="form-select" id="edit_task_status" name="task_status" required>
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="edit_task_description" class="form-label">Description</label>
                                <textarea class="form-control" id="edit_task_description" name="task_description" rows="3" placeholder="Enter task description"></textarea>
                            </div>
                        </div>
                        <div class="col-12 mt-3 text-end">
                            <button type="submit" class="btn btn-primary">Update Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Edit Stage Task Modal -->
    <!-- End Task Management Modal -->
    <!-- Task Scheduling Modal -->
    <div class="modal fade" id="taskSchedulingModal" tabindex="-2" aria-labelledby="taskSchedulingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="task-scheduling-form" method="post">
                    @csrf
                    <input type="hidden" name="company_id" value="{{ $company->company_id }}">
                    <input type="hidden" name="task_id" id="task_id">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="taskSchedulingModalLabel">
                            <i class="las la-calendar-check me-2"></i> Task Scheduling
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="schedule_task_title" class="form-label">Task Title</label>
                                <input type="text" class="form-control" id="schedule_task_title" name="task_title" required readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="start_date" class="form-label fw-semibold">Scheduled Date</label>
                                <div class="input-group" id="DateRange">
                                    <input type="date" class="form-control" name="start_date" id="schedule_start_date" placeholder="Start" aria-label="StartDate">
                                    <span class="input-group-text">to</span>
                                    <input type="date" class="form-control" name="end_date" id="schedule_end_date" placeholder="End" aria-label="EndDate">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="schedule_status" class="form-label">Status</label>
                                <select class="form-select" id="schedule_status" name="status" required>
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="is_recurrence" class="form-label">Is Recurring?</label>
                                <div class="d-flex align-items-center mt-2">
                                    <div class="form-check me-4">
                                        <input class="form-check-input" type="radio" id="is_recurrence" name="is_recurrence" value="1">
                                        <label class="form-check-label" for="is_recurrence">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="is_not_recurrence" name="is_recurrence" value="0" checked>
                                        <label class="form-check-label" for="is_not_recurrence">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" id="recurrence-rule-container" style="display: none;">
                                <label for="recurrence_rule_id" class="form-label">Recurrence Rule</label>
                                <select class="form-select" id="recurrence_rule_id" name="recurrence_rule_id">
                                    <option value="" selected disabled>Select Recurrence Rule</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 mt-3 text-end">
                            <button type="submit" class="btn btn-primary">Schedule Task</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive px-3 pb-3">
                    <table class="table table-striped mb-0 w-100" id="tbl-task-scheduling">
                        <thead class="table-light">
                            <tr>
                                <th>Recurrence</th>
                                <th>Assignee</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Frequency</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamic rows will be appended here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Task Metrics Modal -->
    <div class="modal fade" id="taskMetricsModal" tabindex="-1" aria-labelledby="taskMetricsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" style="min-height: 80vh;">
            <div class="modal-content shadow-lg border-0 rounded-3" style="min-height: 75vh;">
                <form id="task-metrics-form" method="post">
                    @csrf
                    <input type="hidden" name="company_id" value="{{ $company->company_id }}">
                    <input type="hidden" name="task_schedule_id">

                    <div class="modal-header bg-primary text-white rounded-top">
                        <h5 class="modal-title fw-bold" id="taskMetricsModalLabel">
                            <i class="las la-flask me-2"></i> Task Metrics: Expected Quantities
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body bg-light">
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label for="expected_chemical_quantity" class="form-label fw-semibold">Expected Chemical Quantity</label>
                                <input type="number" class="form-control" id="expected_chemical_quantity" name="expected_chemical_quantity" min="0" step="any" placeholder="Enter expected chemical quantity" required>
                            </div>
                            <div class="col-md-4">
                                <label for="expected_material_quantity" class="form-label fw-semibold">Expected Material Quantity</label>
                                <input type="number" class="form-control" id="expected_material_quantity" name="expected_material_quantity" min="0" step="any" placeholder="Enter expected material quantity" required>
                            </div>
                            <div class="col-md-4">
                                <label for="expected_water_quantity" class="form-label fw-semibold">Expected Water Quantity (Liters)</label>
                                <input type="number" class="form-control" id="expected_water_quantity" name="expected_water_quantity" min="0" step="any" placeholder="Enter expected water quantity" required>
                            </div>
                        </div>
                        <div class="col-12 text-end mb-3">
                            <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm">
                                <i class="las la-save"></i> Save Metrics
                            </button>
                        </div>
                    </div>
                </form>
               
                <div class="table-responsive">
                    <table class="table table-striped mb-0 w-100" id="tbl-task-metrics">
                        <thead class="table-light">
                            <tr>
                                <th>Chemical Quantity</th>
                                <th>Material Quantity</th>
                                <th>Water Quantity (L)</th>
                                <th >Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Metrics rows will be dynamically loaded here -->
                        </tbody>
                    </table>
                </div>
                    
            </div>
        </div>
    </div>
    <!-- End Task Metrics Modal -->
    <!-- End Task Scheduling Modal -->
    <!-- Assign Employee to Task Modal -->
    <div class="modal fade" id="assignEmployeeToTaskModal" tabindex="-1" aria-labelledby="assignEmployeeToTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-3">
                <form id="assign-employee-task-form" autocomplete="off">
                    @csrf
                    <input type="hidden" name="company_id" value="{{ $company->company_id }}">
                    <input type="hidden" name="task_id" id="assign_task_id">
                    <div class="modal-header bg-gradient-primary text-white rounded-top">
                        <h5 class="modal-title fw-bold" id="assignEmployeeToTaskModalLabel">
                            <i class="las la-user-plus me-2"></i> Assign Employee to Task
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body bg-light">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="taggable-container " id="manager-tag-input-6">
                                    <label for="manager" class="form-label">Employee</label>
                                    <div class="manager-tag-input-6 manager-tag-input border-primary bg-light">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="assignment_note" class="form-label">Assignment Note (optional)</label>
                                <textarea class="form-control" id="assignment_note" name="assignment_note" rows="2" placeholder="Add any notes for the assignee(s)"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="las la-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="las la-user-check"></i> Assign
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Assign Employee to Task Modal -->
    <!-- Employee Assignment Management Table Modal -->
    <div class="modal fade" id="employeeAssignmentManagementModal" tabindex="-1" aria-labelledby="employeeAssignmentManagementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-3">
                <div class="modal-header bg-gradient-primary text-white rounded-top">
                    <h5 class="modal-title fw-bold" id="employeeAssignmentManagementModalLabel">
                        <i class="las la-users-cog me-2"></i> Employee Assignment Management
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 w-100" id="tbl-employee-assignment-management">
                            <thead class="table-light">
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Email</th>
                                    <th>Assigned Task</th>
                                    <th>Assignment Note</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dynamic rows will be appended here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Employee Assignment Management Table Modal -->
    <!-- end workflow management -->

     <!-- Annual operations activity -->
    <!-- Annual Operations Activity Modal -->
    <div class="modal fade" id="annualOperationsActivityModal" tabindex="-1" aria-labelledby="annualOperationsActivityModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="annualOperationsActivityModalLabel">Annual Operations Activities for</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card shadow-sm border-0 d-none" id = "annual-operation-activity-form-card" style="background: linear-gradient(90deg, #f8ffae 0%, #43c6ac 100%); color: #333;">
                <div class="card-header d-flex justify-content-between align-items-center rounded-top" style="background: #f8ffae; color: #333;">
                    <h4 class="card-title mb-0 fw-bold">
                        <i class="las la-tasks me-2" style="color: #22c55e;"></i> <span class="fw-bold">Annual Operations Activity Form</span>
                    </h4>
                    <button type="button" class="btn-close" aria-label="Close" onclick="this.closest('.card').classList.add('d-none');"></button>
                </div>
                <div class="card-body">
                    <form action="" method="post" id="annual-operations-activity-form" class="needs-validation" novalidate>
                    <input type="hidden" name="annual_op_metadata_ID" id="annual_op_metadata_ID">
                    <div class="row g-4">
                        <div class="col-md-12">
                        <label for="annual_operation_title" class="form-label fw-semibold">Annual Operation Activity Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="annual_operation_activity_title" name="annual_operation_activity_title" placeholder="Enter Annual Operation Activity Title" readonly>
                        </div>
                        <div class="col-md-6">
                        <label for="operation_name" class="form-label fw-semibold">Operation Activity (Operation type) <span class="text-danger">*</span></label>
                        <select class="form-select operation-select" id="operation_id" name="operation_activity" required>
                            <option value="" selected disabled>Select Operation</option>
                        </select>
                        <div class="invalid-feedback">Please select an operation activity.</div>
                        </div>
                        <div class="col-md-6">
                        <label class="form-label fw-semibold">Activity Date <span class="text-danger">*</span></label>
                        <div class="input-group" id="DateRange">
                            <input type="date" class="form-control" name="activity_start_date" placeholder="Start" aria-label="StartDate" required>
                            <span class="input-group-text">to</span>
                            <input type="date" class="form-control rounded-end" name="activity_end_date" placeholder="End" aria-label="EndDate" required>
                        </div>
                        <div class="invalid-feedback">Please provide both start and end dates.</div>
                        </div>
                        <div class="col-md-6">
                        <label for="objectives" class="form-label fw-semibold">Objectives</label>
                        <textarea name="Objectives" id="objectives" rows="3" class="form-control" placeholder="Enter the objectives of the activity"></textarea>
                        </div>
                        <div class="col-md-6">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea name="Description" id="description" rows="3" class="form-control" placeholder="Enter a detailed description of the activity"></textarea>
                        </div>
                        <div class="col-md-6">
                        <div class="material-quantity-used-container-annual-operation-activity">
                            <div class="row g-2 align-items-end mb-3">
                            <div class="col-md-6">
                                <label for="expected_material" class="form-label fw-semibold">Material Needed</label>
                                <select class="form-select material-select" id="expected_material" name="material_used[0][material_id]">
                                <option value="" selected disabled>Select Material</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="expected_quantity" class="form-label fw-semibold">Quantity</label>
                                <input type="number" class="form-control" id="expected_quantity" name="material_used[0][quantity]" placeholder="Quantity" min="0">
                            </div>
                            <div class="col-md-2">
                                <label for="expected_unit" class="form-label fw-semibold">Unit</label>
                                <input type="text" class="form-control" id="expected_unit" name="material_used[0][unit]" placeholder="Unit">
                            </div>
                            </div>
                        </div>
                        <div class="text-start">
                            <button type="button" class="btn btn-outline-dark btn-sm add-more-material-quantity-annual-operation-activity">
                            <i class="las la-plus"></i> Add More
                            </button>
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="chemical-quantity-container-annual-operation-activity">
                            <div class="row g-2 align-items-end mb-3">
                            <div class="col-md-6">
                                <label for="chemical_used" class="form-label fw-semibold">Chemical Needed</label>
                                <select class="form-select chemical-select" id="chemical_used" name="chemical_used[0][chemical_id]">
                                <option value="" selected disabled>Select Chemical</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="chemical_quantity" class="form-label fw-semibold">Quantity</label>
                                <input type="number" class="form-control" id="chemical_quantity" name="chemical_used[0][quantity]" placeholder="Enter Quantity Used" min="0">
                            </div>
                            <div class="col-md-2">
                                <label for="chemical_unit" class="form-label fw-semibold">Unit</label>
                                <input type="text" class="form-control" id="chemical_unit" name="chemical_used[0][unit]" placeholder="Unit">
                            </div>
                            </div>
                        </div>
                        <div class="text-start">
                            <button type="button" class="btn btn-outline-dark btn-sm add-more-chemical-quantity-annual-operation-activity">
                            <i class="las la-plus"></i> Add More
                            </button>
                        </div>
                        </div>
                        <div class="col-md-3">
                        <label for="water_usage" class="form-label fw-semibold">Expected Water Usage (Liters)</label>
                        <input type="number" class="form-control" id="water_usage" name="water_usage" min="0" placeholder="Enter expected water usage" required>
                        </div>
                        <div class="col-md-3">
                        <label for="energy_usage" class="form-label fw-semibold">Expected Energy Usage (kWh)</label>
                        <input type="number" class="form-control" id="energy_usage" name="energy_usage" min="0" placeholder="Enter expected energy usage">
                        </div>
                        <div class="col-md-6">
                        <div class="annual-operation-activity-waste-quantity-container">
                            <div class="row g-2 align-items-end mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="expected_waste" class="form-label fw-bold">Waste Type</label>
                                <select class="form-select waste-select" name="waste_generated[0][waste_id]" id="expected_waste">
                                    <option value="" selected disabled>Select Waste</option>
                                </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                <label for="expected_quantity" class="form-label fw-bold">Quantity</label>
                                <input type="number" class="form-control" name="waste_generated[0][quantity]" id="expected_quantity" placeholder="Enter Expected Quantity" min="0">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                <label for="expected_unit" class="form-label fw-bold">Unit</label>
                                <input type="text" class="form-control" name="waste_generated[0][unit]" id="expected_unit" placeholder="Enter Expected Unit" >
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-start">
                            <button type="button" class="btn btn-outline-dark btn-sm add-more-annual-activity-waste-quantity-operation">
                            <i class="las la-plus"></i> Add More
                            </button>
                        </div>
                        </div>
                        <div class="col-md-4">
                        <label for="priority" class="form-label fw-semibold">Priority <span class="text-danger">*</span></label>
                        <select class="form-select" id="priority" name="priority" required>
                            <option value="" selected disabled>Select Priority</option>
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                        </div>
                        <div class="col-md-4">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select class="form-select" id="status" name="Status">
                            <option value="" selected disabled>Select Status</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        </div>
                        <div class="col-md-4">
                            <div class="taggable-container " id="manager-tag-input-3">
                                <label for="manager" class="form-label fw-bold">Manager</label>
                                <div class="manager-tag-input-3 manager-tag-input border-primary bg-light">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                        <label for="location" class="form-label fw-semibold">Location</label>
                        <input type="text" class="form-control" id="location" name="Location" placeholder="Enter activity location">
                        </div>
                        <div class="col-md-4">
                        <label for="success_criteria" class="form-label fw-semibold">Success Criteria</label>
                        <textarea class="form-control" id="success_criteria" name="success_criteria" rows="2" placeholder="Enter success criteria"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="tag-input-field" class="form-label fw-semibold">Tags</label>
                            <div id="tagging-system"></div>
                        </div>
                        <div class="col-12 mt-3 text-end">
                        <button type="submit" class="btn btn-dark fw-bold px-4 py-2 shadow-sm">Save Activity</button>
                        </div>
                    </div>
                    </form>
                </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="text-primary mb-0">Annual Operations Activities</h4>
                <button type="button" class="btn btn-primary btn-sm" id="btn-add-annual-activity"
                    onclick="addActivityAnnualOperationLogForm()">
                    <i class="iconoir-plus"></i> Add Activity
                </button>
                </div>
                <div class="table-responsive">
                <table class="table table-striped mb-0 w-100" id="tbl-annual-operations-activity">
                    <thead class="table-light">
                    <tr>
                        <th>Activity Name</th>
                        <th>Operation Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Priority</th>
                        <th>Tags</th>
                        <th class="text-end">Action</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                </div>
            </div>
            </div>
        </div>
    </div>
    <!-- End Annual Operations Activity Modal -->
    <!-- Annual Operation Performance Metrics Modal -->
    <div class="modal fade" id="annualOperationPerformanceMetricsModal" tabindex="-1" aria-labelledby="annualOperationPerformanceMetricsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title" id="annualOperationPerformanceMetricsModalLabel">Annual Operation Performance Metrics</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="annual-operation-performance-metrics-form">
                        @csrf
                        <input type="hidden" name="company_id" value="{{ $company->company_id }}">
                        <div class="row g-3">
                            <!-- Total Operations -->
                            <div class="col-md-4">
                                <label for="total_operations" class="form-label">Total Operations</label>
                                <input type="number" class="form-control" id="total_operations" name="total_operations" min="0" placeholder="Enter total operations" required>
                            </div>
                            <!-- Total Waste Generated -->
                            <div class="col-md-4">
                                <label for="total_waste_generated" class="form-label">Total Waste Generated (Units)</label>
                                <input type="number" class="form-control" id="total_waste_generated" name="total_waste_generated" min="0" placeholder="Enter total waste generated" required>
                            </div>
                            <!-- Total Water Used -->
                            <div class="col-md-4">
                                <label for="total_water_used" class="form-label">Total Water Used (Liters)</label>
                                <input type="number" class="form-control" id="total_water_used" name="total_water_used" min="0" placeholder="Enter total water used" required>
                            </div>
                            <!-- Total Units Produced -->
                            <div class="col-md-4">
                                <label for="total_units_produced" class="form-label">Total Units Produced</label>
                                <input type="number" class="form-control" id="total_units_produced" name="total_units_produced" min="0" placeholder="Enter total units produced" required>
                            </div>
                            <!-- Total Cost -->
                            <div class="col-md-4">
                                <label for="total_cost" class="form-label">Total Cost (₦)</label>
                                <input type="number" class="form-control" id="total_cost" name="total_cost" min="0" placeholder="Enter total cost" required>
                            </div>
                            <!-- Efficiency -->
                            <div class="col-md-4">
                                <label for="efficiency" class="form-label">Efficiency (%)</label>
                                <input type="number" class="form-control" id="efficiency" name="efficiency" min="0" max="100" placeholder="Enter efficiency percentage" required>
                            </div>
                            <!-- Submit Button -->
                            <div class="col-12 text-end mt-3">
                                <button type="submit" class="btn btn-secondary">Save Performance Metrics</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
     <!-- End annual operations Log -->
    @endsection

    @section('scripts')
    <script src="{{ asset('adminAssets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminAssets/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('adminAssets/js/toastify.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('adminAssets/js/location.js') }}"></script>
    <script src="{{ asset('adminAssets/js/industry.js') }}"></script>
    <script src="{{ asset('adminAssets/libs/vanillajs-datepicker/js/datepicker-full.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script src="{{ asset('adminAssets/js/moment.js')}}"></script>
    <script src="{{ asset('adminAssets/libs/imask/imask.min.js')}}"></script>
    <script src="{{ asset('adminAssets/js/pages/forms-advanced.js')}}"></script>
    <script src="{{ asset('adminAssets/js/app.js')}}"></script>
    @include('components.apps.company.scripts.fetch_cycle')
    @include('components.apps.company.scripts.recp')
    @include('components.apps.company.scripts.utils')
    @include('components.apps.company.scripts.departments')
    @include('components.apps.company.scripts.employee')
    @include('components.apps.company.scripts.chemicals')
    <script>
        $(document).ready(function() {
            $('#generalTable, #chemicalTable, #waterTable, #equipmentTable, #rawTable').DataTable();
        });
    </script>
    <script>
        // company policies and objectives
        // company policies
        async function ChangePolicy(ele, company, policy) {
            console.log(ele, company, policy);

            const uri = ele.checked 
                ? "{{ route('admin.add-company-policy') }}" 
                : "{{ route('admin.remove-company-policy') }}";

            if (!ele.checked && !confirm("Do you want to remove this policy?")) return;

            const formData = new FormData();
            formData.append('company', company);
            formData.append('policy', policy);

            try {
                const response = await fetch(uri, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    credentials: 'same-origin',
                    body: formData
                });

                const data = await response.json();
                console.log("--policy", data);

                if (data.status === 'success') {
                    Toastify({
                        text: data.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)",
                        },
                    }).showToast();
                } else if (data.status === 'error') {
                    Object.values(data.errors).forEach(error => {
                        Toastify({
                            text: error,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            stopOnFocus: true,
                            style: {
                                background: "linear-gradient(to right, #ff0000, #ff1745)",
                            },
                        }).showToast();
                    });
                }
            } catch (error) {
                console.error("Error:", error);
            }
        }
        // end company policies
        // Company Objectives
        async function ChangeObjective(element, company, objective) {
            console.log(element, company, objective);

            const uri = element.checked 
                ? "{{ route('admin.add-company-objective') }}" 
                : "{{ route('admin.remove-company-objective') }}";

            if (!element.checked && !confirm("Do you want to remove this objective?")) return;

            const formData = new FormData();
            formData.append('company', company);
            formData.append('objective', objective);

            try {
                const response = await fetch(uri, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    credentials: 'same-origin',
                    body: formData
                });

                const data = await response.json();
                console.log("--objective", data);

                if (data.status === 'success') {
                    Toastify({
                        text: data.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)",
                        },
                    }).showToast();
                } else if (data.status === 'error') {
                    Object.values(data.errors).forEach(error => {
                        Toastify({
                            text: error,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            stopOnFocus: true,
                            style: {
                                background: "linear-gradient(to right, #ff0000, #ff1745)",
                            },
                        }).showToast();
                    });
                }
            } catch (error) {
                console.error("Error:", error);
            }
        }
        // end company objectives
    </script>

    @endsection

</x-layouts.admin-app>