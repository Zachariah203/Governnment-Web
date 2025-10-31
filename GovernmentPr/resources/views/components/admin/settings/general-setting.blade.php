{{-- resources/views/admin/general-setting.blade.php --}}
<x-layouts.admin-app>
<x-validation-errors class="alert" alert />
@include('shared.feedback')
<div class="container-xxl">

    <form action="{{ route('admin.store-settings') }}" method="post" enctype="multipart/form-data">
        @csrf
        <x-form-section submit="">
       
            <x-slot name="title">
                {{ __('Company\'s Profile') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Update your company\'s profile information.') }}
            </x-slot>

            <x-slot name="form">
                <div class="row">
                    <div class="col-md-12">
                        <input type="text" class="form-control @error('company_name') is-invalid @enderror" placeholder="Company name" name="company_name" value="{{ old('company_name', $settings?->company_name ?? '') }}" required>
                        @error('company_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-7 mt-2">
                        <input type="text" class="form-control @error('slogan') is-invalid @enderror" placeholder="Slogan" name="slogan" value="{{ old('slogan', $settings?->slogan ?? '') }}">
                        @error('slogan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-5 mt-2">
                        <input type="text" class="form-control @error('abbreviation') is-invalid @enderror" placeholder="Abbreviation" name="abbreviation" value="{{ old('abbreviation', $settings?->abbreviation ?? '') }}">
                        @error('abbreviation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-2">
                        <label for="">Logo Dark Mode</label>
                        <input type="file" id="input-file-dark" name="logo_dark_mode[]" multiple accept="image/*" />
                        @if($settings?->logo_darkmode)
                            <div class="mt-2">
                                @foreach(json_decode($settings->logo_darkmode, true) ?? [] as $logo)
                                    <img src="{{ Storage::url($logo) }}" alt="Current Logo" style="max-width: 100px; max-height: 100px; margin-right: 10px;">
                                @endforeach
                            </div>
                        @endif
                    </div> <!--end col-->
                    <div class="col-md-6 col-lg-6 mt-2">
                        <label for="">Logo Light Mode</label>
                        <input type="file" id="input-file-light" name="logo_light_mode[]" multiple accept="image/*" />
                        @if($settings?->logo_lightmode)
                            <div class="mt-2">
                                @foreach(json_decode($settings->logo_lightmode, true) ?? [] as $logo)
                                    <img src="{{ Storage::url($logo) }}" alt="Current Logo" style="max-width: 100px; max-height: 100px; margin-right: 10px;">
                                @endforeach
                            </div>
                        @endif
                    </div> <!--end col-->
                    <div class="col-md-6">
                        <label for="">Favicon</label>
                        <input type="file" id="input-file-favicon" name="favicon" accept="image/*" />
                        @if($settings?->favicon)
                            <div class="mt-2">
                                <img src="{{ Storage::url($settings->favicon) }}" alt="Current Favicon" style="max-width: 32px; max-height: 32px;">
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6 mt-3">
                        <label for="">Date of Establishment</label>
                        <input class="form-control @error('date_of_establishment') is-invalid @enderror" type="date" name="date_of_establishment" value="{{ old('date_of_establishment', $settings?->date_of_establishment ?? '') }}" required>
                        @error('date_of_establishment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
            </x-slot>
        </x-form-section>
        <x-form-section submit="">
            <x-slot name="title">
                {{ __('Contact Information') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Update your contact information and email address.') }}
            </x-slot>

            <x-slot name="form">
                <div class="row">
                    <div class="col-md-6">
                        <label>Primary Phone Number <span class="text-danger">*</span></label>
                        <input id="mobile_code_primary" type="tel" class="form-control @error('primary_phone_number') is-invalid @enderror" placeholder="Primary Phone Number" value="{{ old('primary_phone_number', $settings?->phone_number ?? '') }}" required>
                        <input type="hidden" name="primary_phone_number" id="hidden_primary_phone" value="{{ old('primary_phone_number', $settings?->phone_number ?? '') }}">
                        @error('primary_phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label>Secondary Phone Number</label>
                        <input id="mobile_code_secondary" type="tel" class="form-control" placeholder="Secondary Phone Number" value="{{ old('secondary_phone_number', $settings?->secondary_phone_number ?? '') }}">
                        <input type="hidden" name="secondary_phone_number" id="hidden_secondary_phone" value="{{ old('secondary_phone_number', $settings?->secondary_phone_number ?? '') }}">
                    </div>
                    <div class="col-md-12 mt-2">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email Address" name="email" value="{{ old('email', $settings?->email_address ?? '') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="">Country <span class="text-danger">*</span></label>
                            <select name="country" class="form-select countries @error('country') is-invalid @enderror" id="countryId" required>
                                <option value="" selected disabled> Choose... </option>
                            </select>
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="">State <span class="text-danger">*</span></label>
                            <select name="state" class="form-select states @error('state') is-invalid @enderror" onchange="toggleLGA(this);" id="stateId" required>
                                <option value="" selected disabled> Choose... </option>
                            </select>
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="">City <span class="text-danger">*</span></label>
                            <select name="city" id="lga" class="form-select select-lga cities @error('city') is-invalid @enderror" id="cityId" required>
                                <option value="" selected disabled> Choose... </option>
                            </select>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12 mt-2">
                        <div class="form-group">
                            <label for="">Address</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" placeholder="" name="address" value="{{ old('address', $settings?->address ?? '') }}">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <input type="url" class="form-control @error('instagram_links') is-invalid @enderror" placeholder="Instagram Links" name="instagram_links" value="{{ old('instagram_links', $settings?->instagram_links ?? '') }}">
                        @error('instagram_links')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-2">
                        <input type="url" class="form-control @error('facebook_links') is-invalid @enderror" placeholder="Facebook Links" name="facebook_links" value="{{ old('facebook_links', $settings?->facebook_links ?? '') }}">
                        @error('facebook_links')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-2">
                        <input type="url" class="form-control @error('twitter_links') is-invalid @enderror" placeholder="Twitter Links" name="twitter_links" value="{{ old('twitter_links', $settings?->twitter_links ?? '') }}">
                        @error('twitter_links')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12 mt-2">
                        <input type="url" class="form-control @error('websites_URL') is-invalid @enderror" placeholder="Websites URL" name="websites_URL" value="{{ old('websites_URL', $settings?->websites_url ?? '') }}">
                        @error('websites_URL')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </x-slot>
        </x-form-section>
        <x-form-section submit="">
            <x-slot name="title">
                {{ __('Company Overview') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Update your company\'s overview information.') }}
            </x-slot>

            <x-slot name="form">
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <input type="text" class="form-control @error('mission_statement') is-invalid @enderror" placeholder="Mission Statement" name="mission_statement" value="{{ old('mission_statement', $settings?->mission_statement ?? '') }}">
                        @error('mission_statement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12 mt-2">
                        <input type="text" class="form-control @error('vision_statement') is-invalid @enderror" placeholder="Vision Statement" name="vision_statement" value="{{ old('vision_statement', $settings?->vision_statement ?? '') }}">
                        @error('vision_statement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="col-md-8">
                        <label for="">Core Values</label>
                        </div>
                        <div class="row mt-1">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Core Value" name="core_value[]" value="{{ old('core_value.0') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button 
                                class="btn btn-secondary add_more_core_value"
                                type="button">  Add </button>
                            </div>
                        </div>
                        <div class="col-md-12 core-values-container">
                            @if($settings?->core_values)
                                @php $coreValues = is_array($settings->core_values) ? $settings->core_values : json_decode($settings->core_values, true) ?? []; @endphp
                                @foreach($coreValues as $index => $value)
                                    @if($index > 0)
                                    <div class="row mt-2">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Core value" name="core_value[]" value="{{ old('core_value.' . $index, $value) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-danger" onclick="remove_core_value(this)" type="button">  Remove </button>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <label class="">About Company</label>
                        <div class="pt-0">
                            <div id="editor"></div>
                            <textarea name="about_company" id="about_company_hidden" style="display: none">{{ old('about_company', $settings?->about_company ?? '') }}</textarea>
                        </div><!--end card-body--> 
                    </div>
                </div>
            </x-slot>
        </x-form-section>
        <x-form-section submit="">
            <x-slot name="title">
                {{ __('Business Details') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Update your business details.') }}
            </x-slot>
            <x-slot name="form">
                <div class="row">
                    <div class="col-md-6">
                        <div class="">
                            <label for="">Industry <span class="text-danger">*</span></label>
                            <select name="industry" id="industry" class="form-select @error('industry') is-invalid @enderror" required>
                                <option value="" disabled {{ old('industry') ? '' : 'selected' }}> Choose... </option>
                            </select>
                            @error('industry')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="">
                            <label for="">Organization Type <span class="text-danger">*</span></label>
                            <select name="company_type" class="form-select @error('company_type') is-invalid @enderror" required>
                                <option value="" disabled {{ !old('company_type') && !($settings?->organization_type ?? false) ? 'selected' : '' }}> Choose... </option>
                                <option value="private" {{ old('company_type', $settings?->organization_type ?? '') == 'private' ? 'selected' : '' }}>Private</option>
                                <option value="public" {{ old('company_type', $settings?->organization_type ?? '') == 'public' ? 'selected' : '' }}>Public</option>
                                <option value="n.g.o" {{ old('company_type', $settings?->organization_type ?? '') == 'n.g.o' ? 'selected' : '' }}>N.G.O</option>
                            </select>
                            @error('company_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <label>Number of employees <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('size') is-invalid @enderror" placeholder="Number of employees" min="1" name="size" value="{{ old('size', $settings?->size ?? '') }}" required>
                        @error('size')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mt-2">
                        <input type="text" class="form-control @error('registration_number') is-invalid @enderror" placeholder="Registration No. (TIN/VAT)" name="registration_number" value="{{ old('registration_number', $settings?->registration_details ?? '') }}">
                        @error('registration_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="">Certifications</label>
                        <input type="file" id="input-file-cert" name="certifications[]" multiple accept="image/*,application/pdf" />
                        @if($settings?->certifications)
                            <div class="mt-2">
                                @foreach(json_decode($settings->certifications, true) ?? [] as $cert)
                                    <a href="{{ Storage::url($cert) }}" target="_blank">View Cert {{ $loop->index + 1 }}</a><br>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </x-slot>
        </x-form-section>


        <x-form-section submit="">
            <x-slot name="title">
                {{ __('Branding and Media Details') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Update your branding and media details.') }}
            </x-slot>

            <x-slot name="form">
                <div class="row">
                <div class="col-md-3">
                        <label for="">Brand Colour</label>
                        <input type="color" class="form-control @error('brand_colour') is-invalid @enderror"
                            placeholder="Primary and Secondary colour(e.g HEX,RGB)" name="brand_colour" value="{{ old('brand_colour', $settings?->brand_color ?? '#000000') }}">
                        @error('brand_colour')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-6 mt-2">
                        <label for="">Brochures</label>
                        <input type="file" id="input-file-brochure" name="brochures[]" multiple accept="application/pdf" />
                        @if($settings?->brochures)
                            <div class="mt-2">
                                @foreach(json_decode($settings->brochures, true) ?? [] as $brochure)
                                    <a href="{{ Storage::url($brochure) }}" target="_blank">View Brochure {{ $loop->index + 1 }}</a><br>
                                @endforeach
                            </div>
                        @endif
                    </div><!--end col-->
                    <div class="col-md-6 col-lg-6 mt-2">
                    <label for="">Corporate Presentation</label>
                    <input type="file" id="input-file-presentation" name="corporate_presentation[]" multiple accept="application/pdf,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation" />
                    @if($settings?->corperate_presentations)
                        <div class="mt-2">
                            @foreach(json_decode($settings->corperate_presentations, true) ?? [] as $presentation)
                                <a href="{{ Storage::url($presentation) }}" target="_blank">View Presentation {{ $loop->index + 1 }}</a><br>
                            @endforeach
                        </div>
                    @endif
                        </div><!--end col-->
                    <div class="col-md-6 col-lg-6 mt-2">
                    <label for="">Promotional Photos</label>
                    <input type="file" id="input-file-photos" name="promotional_photos[]" multiple accept="image/*" />
                    @if($settings?->promotional_photos)
                        <div class="mt-2">
                            @foreach(json_decode($settings->promotional_photos, true) ?? [] as $photo)
                                <img src="{{ Storage::url($photo) }}" alt="Photo" style="max-width: 100px; max-height: 100px; margin-right: 10px;">
                            @endforeach
                        </div>
                    @endif
                        </div><!--end col-->
                    <div class="col-md-6 col-lg-6 mt-2">
                        <label for="">Promotional Videos</label>
                        <input type="file" id="input-file-videos" name="promotional_videos[]" multiple accept="video/*" />
                        @if($settings?->promotional_videos)
                            <div class="mt-2">
                                @foreach(json_decode($settings->promotional_videos, true) ?? [] as $video)
                                    <video src="{{ Storage::url($video) }}" controls style="max-width: 200px;"></video><br>
                                @endforeach
                            </div>
                        @endif
                     </div><!--end col-->
                </div>
            </x-slot>
        </x-form-section>
        <div class="row justify-content-end">
            <div class="col-md-3 py-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>
    @section('styles')
    <link rel="stylesheet" href="{{asset('adminAssets/libs/quill/quill.snow.css')}}">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"
    />
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    @endsection
    @section('scripts')
    <script src="{{asset('adminAssets/js/pages/file-upload.init.js')}}"></script>
    <script src="{{asset('adminAssets/libs/quill/quill.js')}}"></script>
    <script src="{{asset('adminAssets/js/pages/form-editor.init.js')}}"></script>
    <script src="{{asset('adminAssets/js/pages/forms-advanced.js')}}"></script>
    <script src="{{ asset('adminAssets/js/location.js') }}"></script>
    <script src="{{ asset('adminAssets/js/industry.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"></script>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script>
        // -----Country Code Selection
        let tel_primary = document.querySelector('#mobile_code_primary');
        let tel_secondary = document.querySelector('#mobile_code_secondary');
        let itiPrimary = window.intlTelInput(tel_primary, {
            initialCountry: "ng",
            separateDialCode: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
        });
        let itiSecondary = window.intlTelInput(tel_secondary, {
            initialCountry: "ng",
            separateDialCode: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
        });

        // Set initial values if present
        const initialPrimary = document.getElementById('hidden_primary_phone').value;
        if (initialPrimary) {
            itiPrimary.setNumber(initialPrimary);
        }

        const initialSecondary = document.getElementById('hidden_secondary_phone').value;
        if (initialSecondary) {
            itiSecondary.setNumber(initialSecondary);
        }

        tel_primary.addEventListener("blur", function () {
            const fullPhoneNumber = itiPrimary.getNumber(intlTelInputUtils.numberFormat.E164);
            console.log("Full phone number:", fullPhoneNumber);
            document.getElementById('hidden_primary_phone').value = fullPhoneNumber || '';
        });

        tel_secondary.addEventListener("blur", function () {
            const fullPhoneNumber = itiSecondary.getNumber(intlTelInputUtils.numberFormat.E164);
            console.log("Full phone number:", fullPhoneNumber);
            document.getElementById('hidden_secondary_phone').value = fullPhoneNumber || '';
        });

        // Also update on form submit to catch if not blurred
        document.querySelector('form').addEventListener('submit', function() {
            const primaryNum = itiPrimary.getNumber(intlTelInputUtils.numberFormat.E164);
            if (primaryNum) {
                document.getElementById('hidden_primary_phone').value = primaryNum;
            }
            const secondaryNum = itiSecondary.getNumber(intlTelInputUtils.numberFormat.E164);
            if (secondaryNum) {
                document.getElementById('hidden_secondary_phone').value = secondaryNum;
            }
        });
    </script>
    <script>
        let inputElement = document.querySelectorAll('input[type="file"]');
        console.log(inputElement);
        inputElement.forEach(element => {
            let pond = FilePond.create(element, {
                storeAsFile: true,
            });
        });
    </script>
    <script>
        // Quill Editor Initialization and Event
        document.addEventListener('DOMContentLoaded', function() {
            // Assuming Quill is initialized globally in form-editor.init.js
            // If not, initialize here: var quill = new Quill('#editor', { theme: 'snow' });
            if (typeof quill !== 'undefined') {
                let about_company = document.getElementById('about_company_hidden');
                quill.root.innerHTML = about_company.value;
                quill.on('text-change', () => {
                    about_company.value = quill.root.innerHTML;
                });
            } else {
                console.warn('Quill not found. Check form-editor.init.js');
            }
        });
    </script>
    <script>
        let add_more_core_value = document.querySelector('.add_more_core_value');
        console.log(add_more_core_value);
        if (add_more_core_value) {
            add_more_core_value.addEventListener('click', () => {
                let corevaluecontainer = document.querySelector('.core-values-container');
                corevaluecontainer.innerHTML += `
                    <div class="row mt-2">
                        <div class="col-md-9">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Core value" name="core_value[]">
                            </div>
                        </div>
                        <div class="col-md-3">
                        <button class="btn btn-danger" onclick="remove_core_value(this)" type="button">  Remove </button></div>
                    </div>
                `;
            });
        }

        function remove_core_value(ele) {
            let parent = ele.parentElement.parentElement;
            parent.remove();
        }
    </script>
    @endsection
</x-layouts.admin-app>