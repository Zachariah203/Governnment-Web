<x-layouts.admin-app>
    @section('styles')
        <!-- Existing styles unchanged -->
    @endsection

    @section('scripts')
        <!-- Existing scripts unchanged -->
        <script>
            var deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var route = button.getAttribute('data-route');
                var form = document.getElementById('deleteForm');
                form.setAttribute('action', route);
            });
        </script>
    @endsection

    <div class="container-xxl"> 
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">                      
                                <h4 class="card-title">Events & News</h4>                      
                            </div>
                            <div class="col-auto">     
                                <a class="nav-link" href="{{route('CMS.add-event')}}">     
                                    <button class="btn btn-primary"><i class="fas fa-plus me-1"></i> Add Event</button>
                                </a>                 
                            </div>
                        </div>  
                    </div>
                    <div class="card-body pt-0">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Event title</th>
                                        <th>Category</th>
                                        <th>Post date</th>
                                        <th>Start date</th>
                                        <th>End date</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($events as $event)
                                        <tr>
                                            <td>{{ $event->EventID ?? 'N/A' }}</td>
                                            <td>
                                                <p class="d-inline-block align-middle mb-0">
                                                    <span class="d-block align-middle mb-0 product-name text-body">{{ Str::limit($event->title, 50) }}</span>
                                                    <span class="text-muted font-13">{{ Str::limit($event->description ?? '', 30) }}</span> 
                                                </p>
                                            </td>
                                            <td>{{ $event->category ?? 'N/A' }}</td>
                                            <td>{{ $event->post_date ? \Carbon\Carbon::parse($event->post_date)->format('d/m/Y') : 'N/A' }}</td>
                                            <td>{{ $event->StartDate ? \Carbon\Carbon::parse($event->StartDate)->format('d/m/Y') : 'N/A' }}</td>
                                            <td>{{ $event->EndDate ? \Carbon\Carbon::parse($event->EndDate)->format('d/m/Y') : 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $status = $event->status ?? 'draft';
                                                    if ($status === 'published') {
                                                        $badgeClass = 'bg-success-subtle text-success';
                                                        $icon = 'check';
                                                    } elseif ($status === 'draft') {
                                                        $badgeClass = 'bg-secondary-subtle text-secondary';
                                                        $icon = 'clock';
                                                    } elseif ($status === 'archived') {
                                                        $badgeClass = 'bg-warning-subtle text-warning';
                                                        $icon = 'archive';
                                                    } else {
                                                        $badgeClass = 'bg-danger-subtle text-danger';
                                                        $icon = 'xmark';
                                                    }
                                                @endphp
                                                <span class="badge {{ $badgeClass }}"><i class="fas fa-{{ $icon }} me-1"></i> {{ ucfirst($status) }}</span>
                                            </td>
                                            <td class="text-end">                                                       
                                                <a href="{{ route('CMS.edit-event', $event) }}"><i class="las la-pen text-secondary fs-18"></i></a>
                                                <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#deleteModal" data-route="{{ route('CMS.delete-event', $event) }}">
                                                    <i class="las la-trash-alt text-danger fs-18"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No events found. <a href="{{ route('CMS.add-event') }}">Create one now!</a></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{ $events->links() }} <!-- Pagination -->
                    </div>
                </div>
            </div>
        </div>                                       
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this event? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" action="" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin-app>