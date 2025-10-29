<x-layouts.admin-app>
    @section('styles')
        <!-- App css -->
        <link href="{{asset('adminAssets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('adminAssets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('adminAssets/css/app.min.css')}}" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="{{asset('adminAssets/libs/quill/quill.snow.css')}}">
        <link rel="shortcut icon" href="{{asset('adminAssets/images/favicon.ico')}}">

        <link href="{{asset('adminAssets/libs/mobius1-selectr/selectr.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('adminAssets/libs/huebee/huebee.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('adminAssets/libs/vanillajs-datepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css" />

        <link href="{{asset('adminAssets/libs/uppy/uppy.min.css')}}" rel="stylesheet" type="text/css " />

        <link href="{{asset('adminAssets/libs/simple-datatables/style.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('adminAssets/libs/mobius1-selectr/selectr.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('adminAssets/libs/huebee/huebee.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.1/dist/css/tom-select.css" rel="stylesheet">
    @endsection

    @section('scripts')
        <script src="{{asset('adminAssets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('adminAssets/libs/mobius1-selectr/selectr.min.js')}}"></script>
        <script src="{{asset('adminAssets/libs/huebee/huebee.pkgd.min.js')}}"></script>
        <script src="{{asset('adminAssets/js/pages/forms-advanced.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.1/dist/js/tom-select.complete.min.js"></script>
        <script>
            new TomSelect('#guardSelect2',{maxItems: 5});
            new TomSelect('#guardSelect',{maxItems: 5});
        </script>
        <script src="{{asset('adminAssets/libs/simple-datatables/umd/simple-datatables.js')}}"></script>
        <script src="{{asset('adminAssets/js/pages/datatable.init.js')}}"></script>
        <script src="{{asset('adminAssets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('adminAssets/libs/quill/quill.js')}}"></script>
        <script src="{{asset('adminAssets/js/pages/form-editor.init.js')}}"></script>
        <script src="{{asset('adminAssets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('adminAssets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('adminAssets/libs/uppy/uppy.legacy.min.js')}}"></script>
        <script src="{{asset('adminAssets/js/pages/file-upload.init.js')}}"></script>
        <script src="{{asset('adminAssets/js/app.js')}}"></script>

        <script src="{{asset('adminAssets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('adminAssets/libs/mobius1-selectr/selectr.min.js')}}"></script>
        <script src="{{asset('adminAssets/libs/huebee/huebee.pkgd.min.js')}}"></script>
        <script src="{{asset('adminAssets/libs/vanillajs-datepicker/js/datepicker-full.min.js')}}"></script>
        <script src="{{asset('adminAssets/js/moment.js')}}"></script>
        <script src="{{asset('adminAssets/libs/imask/imask.min.js')}}"></script>
        <script src="{{asset('adminAssets/js/pages/forms-advanced.js')}}"></script>

        <!-- Quill Editor Init -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var quill = new Quill('#editor', {
                    theme: 'snow'
                });

                var hiddenInput = document.getElementById('content-hidden');

                // Function to update hidden input
                function updateHidden() {
                    var html = quill.root.innerHTML;
                    var text = quill.getText().trim(); // Get plain text
                    hiddenInput.value = html;
                    console.log('Content synced:', { html: html, textLength: text.length }); // Debug
                }

                // Initial update (for edit mode)
                updateHidden();

                // Sync on every text change (real-time)
                quill.on('text-change', updateHidden);

                // Final check on submit
                document.querySelector('form').addEventListener('submit', function(e) {
                    var text = quill.getText().trim();
                    if (text.length === 0) {
                        e.preventDefault();
                        alert('Content is required. Please add some text to the editor.');
                        return false;
                    }
                    updateHidden();
                });
            });
        </script>
    @endsection

    {{-- Compute selected values once to avoid nested ternaries --}}
    @php
        $selected_category = old('category') ?: (isset($event) && $event ? ($event->category ?? '') : '');
        $selected_status = old('status') ?: (isset($event) && $event ? ($event->status ?? 'draft') : 'draft');
    @endphp

    <form action="@isset($event) {{ route('CMS.update-event', $event->EventID) }} @else {{ route('CMS.store-event') }} @endisset" method="post">
        @csrf
        @isset($event)
            @method('PUT')
        @endisset

        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">                      
                                <h4 class="card-title">@isset($event) Edit @else Create @endisset Event</h4>                      
                            </div>
                        </div>  
                    </div>
                    <div class="card-body pt-0">
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        <div class="mb-3">
                            <label for="title" class="form-label">Event Title*</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title" value="{{ old('title', isset($event) && $event ? ($event->title ?? '') : '') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3"> 
                            <label for="url" class="form-label">Url</label>
                            <input type="url" class="form-control @error('url') is-invalid @enderror" name="url" id="url" value="{{ old('url', isset($event) && $event ? ($event->url ?? '') : '') }}">
                            @error('url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row justify-content-center">                        
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Event content</h4>                      
                                    </div>
                                    <div class="card-body pt-0">
                                        <div id="editor">
                                            @isset($event)
                                                {!! $event->content !!}
                                            @else
                                                <p>Hello World!</p>
                                                <p>Some initial <strong>bold</strong> text</p>
                                                <p><br /></p>
                                            @endisset
                                        </div> 
                                        <input type="hidden" name="content" id="content-hidden" value="{{ old('content', isset($event) && $event ? $event->content : '') }}">
                                        @error('content')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div> 
                                </div> 
                            </div>                                                       
                        </div>

                        <div class="mb-3">
                            <label class="mb-2">Created At</label>
                            <input class="form-control mb-3 @error('post_date') is-invalid @enderror" type="date" name="post_date" value="{{ old('post_date', isset($event) && $event ? ($event->post_date ?? '') : '') }}" required>
                            @error('post_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="input-group mt-4" id="DateRange">
                            <input type="date" class="form-control @error('StartDate') is-invalid @enderror" name="StartDate" value="{{ old('StartDate', isset($event) && $event ? ($event->StartDate ?? '') : '') }}" required>
                            <span class="input-group-text">to</span>
                            <input type="date" class="form-control rounded-end @error('EndDate') is-invalid @enderror" name="EndDate" value="{{ old('EndDate', isset($event) && $event ? ($event->EndDate ?? '') : '') }}" required>
                        </div> 
                        @error('StartDate')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('EndDate')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div> 
                </div> 
            </div> 

            <div class="col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="12">{{ old('description', isset($event) && $event ? ($event->description ?? '') : '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <div class="col-md-12 mt-3">
                            <label class="form-label" for="category">Add Post Category</label>
                            <select class="form-select @error('category') is-invalid @enderror" name="category" id="category" required>
                                <option value="">Select category</option>
                                <option value="general" {{ $selected_category == 'general' ? 'selected' : '' }}>General</option>
                                <option value="news" {{ $selected_category == 'news' ? 'selected' : '' }}>News</option>
                                {{-- Add more options or fetch dynamically --}}
                            </select>
                            @error('category')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>  

                        <div class="col-md-12 mt-3">
                            <label class="form-label" for="status">Status</label>
                            <select class="form-select" name="status" id="status">
                                <option value="draft" {{ $selected_status == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ $selected_status == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ $selected_status == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-outline-primary w-100">Publish event</button>
                                <a href="{{ route('CMS.event') }}" class="btn btn-outline-secondary w-100">Cancel & View List</a>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>                                                      
        </div>

    </form>

</x-layouts.admin-app>