@extends('components.layouts.app')
@section('PageTitle', 'Blog')

@section('pageContent')
<main class="tp-blog-advanced pt-120 pb-120">
    <div class="container">
        <div class="row gx-5">

            <!-- BLOG FEED -->
            <div class="col-lg-8">
                <div class="row g-4">

                    @foreach($blogs ?? [] as $blog)
                    <div class="col-md-6">
                        <article class="blog-card">
                            <div class="blog-thumb">
                                <a href="#">
                                    <img src="{{ $blog['featured_image'] ?? asset('MainAssets/img/blog/default.jpg') }}"
                                         alt="{{ $blog['title'] ?? '' }}">
                                </a>

                                @if(!empty($blog['category']))
                                <span class="blog-cat">{{ $blog['category'] }}</span>
                                @endif
                            </div>

                            <div class="blog-content">
                                <h3 class="blog-title">
                                    <a href="#">{{ $blog['title'] ?? 'Blog Title' }}</a>
                                </h3>

                                @if(!empty($blog['excerpt']))
                                <p>{{ Str::limit($blog['excerpt'], 120) }}</p>
                                @endif

                                <div class="blog-meta">
                                    <span><i class="far fa-user"></i> {{ $blog['author'] ?? 'Admin' }}</span>
                                    <span><i class="far fa-clock"></i> {{ $blog['date'] ?? 'Unknown' }}</span>
                                </div>

                                <a href="#" class="btn-readmore">
                                    Read More
                                    <i class="far fa-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                    </div>
                    @endforeach

                </div>
            </div>

            <!-- SIDEBAR -->
            <div class="col-lg-4">
                <aside class="sidebar">

                    <!-- Search -->
                    <div class="sidebar-card">
                        <h4 class="sidebar-title">Search</h4>
                        <form action="#">
                            <input type="text" placeholder="Search articles...">
                            <button type="submit"><i class="far fa-search"></i></button>
                        </form>
                    </div>

                    <!-- Categories -->
                    <div class="sidebar-card">
                        <h4 class="sidebar-title">Categories</h4>
                        <ul class="cat-list">
                            @foreach($categories ?? [] as $cat)
                            <li><a href="#"><span>{{ $cat }}</span><i class="fa fa-chevron-right"></i></a></li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Trending -->
                    <div class="sidebar-card">
                        <h4 class="sidebar-title">Trending News</h4>

                        @foreach($trending ?? [] as $trend)
                        <div class="trend-item d-flex">
                            <div class="trend-img">
                                <img src="{{ $trend['featured_image'] ?? asset('MainAssets/img/blog/default.jpg') }}">
                            </div>
                            <div class="trend-info">
                                <a href="#">{{ Str::limit($trend['title'] ?? '', 55) }}</a>
                                <span><i class="far fa-clock"></i> {{ $trend['date'] ?? '' }}</span>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </aside>
            </div>

        </div>
    </div>
</main>
@endsection
