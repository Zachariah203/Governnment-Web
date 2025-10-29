@extends('components.layouts.app')
@section('PageTitle', $page->seo_title ?? $page->title ?? 'Blog')

@section('pageContent')

<!-- @php
    // Get blog posts from dynamic_tables only
    $blogPosts = [];
    if (!empty($page->dynamic_tables)) {
        $data = is_string($page->dynamic_tables) ? json_decode($page->dynamic_tables, true) : $page->dynamic_tables;
        if (is_array($data)) {
            $blogPosts = $data;
        }
    }
    
    // Get categories and tags from page model
    $pageCategories = is_array($page->categories) ? $page->categories : [];
    $pageTags = is_array($page->tags) ? $page->tags : [];
    
    // Get author info
    $authorInfo = $page->author ? [
        'name' => $page->author->first_name . ' ' . $page->author->last_name,
        'email' => $page->author->email,
        'profile_photo' => $page->author->profile_photo_path
    ] : null;
    
    // Check if custom CSS/JS is provided
    $hasCustomCSS = !empty($page->custom_css);
    $hasCustomJS = !empty($page->custom_js);
@endphp -->

<main>
    {{-- HERO/BREADCRUMB SECTION --}}
    <section class="tp-blog-hero-area">
        <div class="tp-blog-hero-bg breadcrumb__overlay breadcrumb__height p-relative fix" 
             @if($page->hero_bg)
                 style="background-image: url('{{ $page->hero_bg }}');"
             @elseif($page->featured_image)
                 style="background-image: url('{{ $page->featured_image }}');"
             @else
                 style="background-image: url('{{ asset('MainAssets/img/breadcrumb/breadcrumb-bg.jpg') }}');"
             @endif>
            <div class="container">
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="breadcrumb__content z-index text-center">
                            @if($page->hero_subtitle)
                                <div class="tp-blog-hero-subtitle">
                                    <span>{{ $page->hero_subtitle }}</span>
                                </div>
                            @endif
                            <h1 class="breadcrumb__title tp-blog-hero-title">
                                {{ $page->hero_title ?? $page->title }}
                            </h1>
                            @if($page->excerpt)
                                <p class="tp-blog-hero-description">{{ $page->excerpt }}</p>
                            @elseif($page->seo_description)
                                <p class="tp-blog-hero-description">{{ $page->seo_description }}</p>
                            @endif
                            <div class="breadcrumb__list">
                                <span><a href="{{ url('/') }}">Home</a></span>
                                <span class="dvdr"><i class="fa fa-angle-right"></i></span>
                                <span>{{ $page->title }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- BLOG POSTS SECTION --}}
    <section class="postbox__area pt-120 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-xxl-8 col-xl-8 col-lg-8">
                    <div class="postbox__wrapper">
                        {{-- Main Blog Post (from page data) --}}
                        <article class="postbox__item format-image mb-60 transition-3">
                            {{-- Post Featured Image --}}
                            @if($page->featured_image)
                                <div class="postbox__thumb w-img">
                                    <img src="{{ $page->featured_image }}" alt="{{ $page->title }}">
                                    
                                    {{-- Date Badge --}}
                                    <div class="postbox__tag">
                                        @php
                                            $date = $page->publish_at ?? $page->created_at;
                                        @endphp
                                        <span>{{ $date->format('d') }}<br>{{ $date->format('M') }}</span>
                                    </div>
                                </div>
                            @endif

                            {{-- Post Content --}}
                            <div class="postbox__content">
                                {{-- Post Meta --}}
                                <div class="postbox__meta">
                                    @if($authorInfo)
                                        <span>
                                            <a href="#"><i class="far fa-user"></i>By {{ $authorInfo['name'] }}</a>
                                        </span>
                                    @endif
                                    @if(!empty($pageCategories))
                                        <span>
                                            <a href="#"><i class="fas fa-tag tag"></i>{{ implode(', ', array_slice($pageCategories, 0, 2)) }}</a>
                                        </span>
                                    @endif
                                    <span>
                                        <a href="#"><i class="far fa-clock"></i>{{ $page->created_at->format('M d, Y') }}</a>
                                    </span>
                                </div>

                                {{-- Post Title --}}
                                <h1 class="postbox__title">{{ $page->title }}</h1>

                                {{-- Post Excerpt --}}
                                @if($page->excerpt)
                                    <div class="postbox__text">
                                        <p>{{ $page->excerpt }}</p>
                                    </div>
                                @endif

                                {{-- Post Body Content --}}
                                @if($page->body)
                                    <div class="postbox__body-content">
                                        {!! $page->body !!}
                                    </div>
                                @endif
                            </div>
                        </article>

                        {{-- Related Posts from dynamic_tables --}}
                        @if(!empty($blogPosts))
                            <div class="related-posts-section mt-60">
                                <h3 class="related-posts-title mb-40">Related Posts</h3>
                                <div class="row">
                                    @foreach(array_slice($blogPosts, 0, 3) as $post)
                                        <div class="col-lg-4 col-md-6 mb-30">
                                            <article class="related-post-item">
                                                @if(!empty($post['featured_image']))
                                                    <div class="related-post-thumb">
                                                        <img src="{{ $post['featured_image'] }}" alt="{{ $post['title'] ?? 'Related Post' }}">
                                                    </div>
                                                @endif
                                                <div class="related-post-content">
                                                    <h4 class="related-post-title">
                                                        {{ $post['title'] ?? 'Related Post' }}
                                                    </h4>
                                                    @if(!empty($post['excerpt']))
                                                        <p class="related-post-excerpt">
                                                            {{ Str::limit($post['excerpt'], 100) }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </article>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Social Share --}}
                        <div class="social-share-section mt-40">
                            <h4>Share this post:</h4>
                            <div class="social-share-buttons">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="share-btn facebook">
                                    <i class="fab fa-facebook-f"></i> Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($page->title) }}" target="_blank" class="share-btn twitter">
                                    <i class="fab fa-twitter"></i> Twitter
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="share-btn linkedin">
                                    <i class="fab fa-linkedin-in"></i> LinkedIn
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SIDEBAR --}}
                <div class="col-xxl-4 col-xl-4 col-lg-4">
                    <div class="sidebar__wrapper">
                        {{-- Search Widget --}}
                        <div class="sidebar__widget mb-30">
                            <div class="sidebar__widget-content">
                                <h3 class="sidebar__widget-title">Search Posts</h3>
                                <div class="sidebar__search">
                                    <form action="#" method="GET">
                                        <div class="sidebar__search-input-2">
                                            <input type="text" name="search" placeholder="Search blog posts..." value="{{ request('search') }}">
                                            <button type="submit"><i class="far fa-search"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Author Widget --}}
                        @if($authorInfo)
                            <div class="sidebar__widget mb-30">
                                <h3 class="sidebar__widget-title">About Author</h3>
                                <div class="sidebar__widget-content">
                                    <div class="author-widget">
                                        @if($authorInfo['profile_photo'])
                                            <div class="author-avatar mb-20">
                                                <img src="{{ $authorInfo['profile_photo'] }}" alt="{{ $authorInfo['name'] }}" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                                            </div>
                                        @endif
                                        <h4 class="author-name">{{ $authorInfo['name'] }}</h4>
                                        <p class="author-email">{{ $authorInfo['email'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Recent Posts from dynamic_tables --}}
                        @if(!empty($blogPosts))
                            <div class="sidebar__widget mb-30">
                                <h3 class="sidebar__widget-title">Related Articles</h3>
                                <div class="sidebar__widget-content">
                                    <div class="sidebar__post rc__post">
                                        @foreach(array_slice($blogPosts, 0, 3) as $recentPost)
                                            <div class="rc__post mb-30 d-flex align-items-center">
                                                @if(!empty($recentPost['featured_image']))
                                                    <div class="rc__post-thumb mr-20">
                                                        <img src="{{ $recentPost['featured_image'] }}" alt="{{ $recentPost['title'] ?? 'Related Post' }}" style="width: 80px; height: 60px; object-fit: cover; border-radius: 8px;">
                                                    </div>
                                                @endif
                                                <div class="rc__post-content">
                                                    <h3 class="rc__post-title">
                                                        {{ Str::limit($recentPost['title'] ?? 'Related Article', 50) }}
                                                    </h3>
                                                    @if(!empty($recentPost['excerpt']))
                                                        <p class="rc__post-excerpt">{{ Str::limit($recentPost['excerpt'], 80) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Categories Widget --}}
                        @if(!empty($pageCategories))
                            <div class="sidebar__widget mb-30">
                                <h3 class="sidebar__widget-title">Categories</h3>
                                <div class="sidebar__widget-content">
                                    <ul>
                                        @foreach($pageCategories as $category)
                                            <li>
                                                <a href="#">{{ $category }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        {{-- Tags Widget --}}
                        @if(!empty($pageTags))
                            <div class="sidebar__widget mb-30">
                                <h3 class="sidebar__widget-title">Tags</h3>
                                <div class="sidebar__widget-content">
                                    <div class="tagcloud">
                                        @foreach($pageTags as $tag)
                                            <a href="#">{{ $tag }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Newsletter Widget --}}
                        @if($page->newsletter_enabled)
                            <div class="sidebar__widget mb-30">
                                <h3 class="sidebar__widget-title">Newsletter</h3>
                                <div class="sidebar__widget-content">
                                    <div class="tp-newsletter-widget">
                                        <p>Subscribe to our newsletter to get the latest updates and news.</p>
                                        <form action="#" method="POST">
                                            @csrf
                                            <div class="tp-newsletter-input">
                                                <input type="email" name="email" placeholder="Your email address" required>
                                                <button type="submit" class="tp-btn">Subscribe</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- COMMENTS SECTION --}}
    @if($page->contact_form_enabled)
        <section class="tp-blog-comments-section pt-80 pb-80" style="background: #f8f9fa;">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 offset-xl-2">
                        <div class="tp-blog-comments-wrapper">
                            <h3 class="comments-title mb-40">Leave a Comment</h3>
                            <form action="#" method="POST" class="comment-form">
                                @csrf
                                <input type="hidden" name="page_id" value="{{ $page->page_id }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="comment-form-input mb-20">
                                            <input type="text" name="name" placeholder="Your Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="comment-form-input mb-20">
                                            <input type="email" name="email" placeholder="Your Email" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="comment-form-input mb-20">
                                            <input type="text" name="subject" placeholder="Subject" value="Comment on: {{ $page->title }}">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="comment-form-input mb-30">
                                            <textarea name="message" placeholder="Your Comment" rows="6" required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="comment-form-btn">
                                            <button type="submit" class="tp-btn">Post Comment</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
</main>

@endsection

{{-- Custom CSS if provided --}}
@if($hasCustomCSS)
    @push('styles')
    <style>
        {!! $page->custom_css !!}
    </style>
    @endpush
@endif

{{-- Custom JavaScript if provided --}}
@if($hasCustomJS)
    @push('scripts')
    <script>
        {!! $page->custom_js !!}
    </script>
    @endpush
@endif

{{-- Custom Head Content --}}
@if(!empty($page->custom_head))
    @push('head')
        {!! $page->custom_head !!}
    @endpush
@endif

@push('styles')
<style>
/* Blog Template Styles */

/* Hero Section */
.tp-blog-hero-area {
    position: relative;
}

.tp-blog-hero-subtitle {
    display: inline-block;
    background: rgba(255, 255, 255, 0.15);
    padding: 8px 20px;
    border-radius: 25px;
    margin-bottom: 20px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.tp-blog-hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    color: #fff;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.tp-blog-hero-description {
    font-size: 1.2rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 30px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

/* Blog Posts */
.postbox__item {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.postbox__item:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
}

.postbox__thumb img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.postbox__item:hover .postbox__thumb img {
    transform: scale(1.05);
}

.postbox__tag {
    position: absolute;
    top: 20px;
    left: 20px;
    background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
    color: #fff;
    padding: 10px 15px;
    border-radius: 8px;
    text-align: center;
    font-weight: 600;
    font-size: 0.9rem;
    z-index: 2;
}

.postbox__content {
    padding: 30px;
}

.postbox__meta {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.postbox__meta span {
    font-size: 0.9rem;
    color: #666;
}

.postbox__meta a {
    color: #666;
    text-decoration: none;
    transition: color 0.3s ease;
}

.postbox__meta a:hover {
    color: #1e7e34;
}

.postbox__title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 15px;
    line-height: 1.4;
}

.postbox__title a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
}

.postbox__title a:hover {
    color: #1e7e34;
}

.postbox__text {
    color: #666;
    line-height: 1.7;
    margin-bottom: 25px;
}

.postbox__read-more .tp-btn-xl {
    background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
    color: #fff;
    padding: 12px 25px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.postbox__read-more .tp-btn-xl:hover {
    background: linear-gradient(135deg, #155724 0%, #1e7e34 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(30, 126, 52, 0.3);
}

/* Sidebar */
.sidebar__widget {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.sidebar__widget:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
}

.sidebar__widget-title {
    background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
    color: #fff;
    padding: 20px 25px;
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.sidebar__widget-content {
    padding: 25px;
}

.sidebar__search-input-2 {
    position: relative;
}

.sidebar__search-input-2 input {
    width: 100%;
    padding: 15px 50px 15px 20px;
    border: 2px solid #e9ecef;
    border-radius: 25px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.sidebar__search-input-2 input:focus {
    outline: none;
    border-color: #1e7e34;
    box-shadow: 0 0 0 3px rgba(30, 126, 52, 0.1);
}

.sidebar__search-input-2 button {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    border: none;
    background: #1e7e34;
    color: #fff;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
}

.sidebar__search-input-2 button:hover {
    background: #155724;
    transform: translateY(-50%) scale(1.1);
}

/* Recent Posts */
.rc__post-thumb img {
    border-radius: 8px;
}

.rc__post-title a {
    color: #333;
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 500;
    transition: color 0.3s ease;
}

.rc__post-title a:hover {
    color: #1e7e34;
}

.rc__meta {
    margin-bottom: 8px;
}

.rc__meta span {
    font-size: 0.8rem;
    color: #999;
    display: flex;
    align-items: center;
    gap: 5px;
}

/* Categories */
.sidebar__widget-content ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar__widget-content ul li {
    border-bottom: 1px solid #f0f0f0;
    padding: 12px 0;
}

.sidebar__widget-content ul li:last-child {
    border-bottom: none;
}

.sidebar__widget-content ul li a {
    color: #666;
    text-decoration: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: color 0.3s ease;
}

.sidebar__widget-content ul li a:hover {
    color: #1e7e34;
}

.sidebar__widget-content ul li span {
    background: #f8f9fa;
    color: #666;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
}

/* Tags */
.tagcloud {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.tagcloud a {
    background: #f8f9fa;
    color: #666;
    padding: 6px 15px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.tagcloud a:hover {
    background: #1e7e34;
    color: #fff;
    border-color: #1e7e34;
    transform: translateY(-2px);
}

/* Newsletter Widget */
.tp-newsletter-widget p {
    color: #666;
    margin-bottom: 20px;
    line-height: 1.6;
}

.tp-newsletter-input {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.tp-newsletter-input input {
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.tp-newsletter-input input:focus {
    outline: none;
    border-color: #1e7e34;
    box-shadow: 0 0 0 3px rgba(30, 126, 52, 0.1);
}

.tp-newsletter-input .tp-btn {
    background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
    color: #fff;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.tp-newsletter-input .tp-btn:hover {
    background: linear-gradient(135deg, #155724 0%, #1e7e34 100%);
    transform: translateY(-2px);
}

/* Post Body Content */
.postbox__body-content {
    margin-top: 30px;
    color: #666;
    line-height: 1.8;
}

.postbox__body-content h1,
.postbox__body-content h2,
.postbox__body-content h3,
.postbox__body-content h4,
.postbox__body-content h5,
.postbox__body-content h6 {
    color: #333;
    margin: 30px 0 20px 0;
    font-weight: 600;
}

.postbox__body-content h1:first-child,
.postbox__body-content h2:first-child,
.postbox__body-content h3:first-child {
    margin-top: 0;
}

.postbox__body-content p {
    margin-bottom: 20px;
}

.postbox__body-content a {
    color: #1e7e34;
    text-decoration: none;
    transition: color 0.3s ease;
}

.postbox__body-content a:hover {
    color: #155724;
    text-decoration: underline;
}

.postbox__body-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 20px 0;
}

.postbox__body-content blockquote {
    background: #f8f9fa;
    border-left: 4px solid #1e7e34;
    padding: 20px;
    margin: 20px 0;
    font-style: italic;
    color: #555;
}

.postbox__body-content ul,
.postbox__body-content ol {
    margin: 20px 0;
    padding-left: 30px;
}

.postbox__body-content li {
    margin-bottom: 8px;
}

/* Related Posts */
.related-posts-section {
    border-top: 2px solid #f0f0f0;
    padding-top: 40px;
}

.related-posts-title {
    font-size: 1.8rem;
    font-weight: 600;
    color: #333;
    text-align: center;
}

.related-post-item {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    height: 100%;
}

.related-post-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
}

.related-post-thumb img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.related-post-content {
    padding: 20px;
}

.related-post-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
    line-height: 1.4;
}

.related-post-excerpt {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.6;
}

/* Author Widget */
.author-widget {
    text-align: center;
}

.author-name {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.author-email {
    color: #666;
    font-size: 0.9rem;
}

/* Social Share */
.social-share-section {
    border-top: 2px solid #f0f0f0;
    padding-top: 30px;
}

.social-share-section h4 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
}

.social-share-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.share-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.share-btn.facebook {
    background: #1877f2;
    color: #fff;
}

.share-btn.twitter {
    background: #1da1f2;
    color: #fff;
}

.share-btn.linkedin {
    background: #0077b5;
    color: #fff;
}

.share-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Comments Section */
.tp-blog-comments-wrapper {
    background: #fff;
    padding: 50px;
    border-radius: 20px;
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
}

.comments-title {
    font-size: 1.8rem;
    font-weight: 600;
    color: #333;
    text-align: center;
}

.comment-form-input input,
.comment-form-input textarea {
    width: 100%;
    padding: 15px 20px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: #f8f9fa;
    font-family: inherit;
}

.comment-form-input input:focus,
.comment-form-input textarea:focus {
    outline: none;
    border-color: #1e7e34;
    box-shadow: 0 0 0 3px rgba(30, 126, 52, 0.1);
    background: #fff;
    transform: translateY(-2px);
}

.comment-form-input textarea {
    resize: vertical;
}

.comment-form-btn {
    text-align: center;
}

.comment-form-btn .tp-btn {
    background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
    color: #fff;
    padding: 15px 35px;
    border: none;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.comment-form-btn .tp-btn:hover {
    background: linear-gradient(135deg, #155724 0%, #1e7e34 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(30, 126, 52, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .tp-blog-hero-title {
        font-size: 2.5rem;
    }
    
    .tp-blog-hero-description {
        font-size: 1rem;
        padding: 0 15px;
    }
    
    .postbox__content {
        padding: 20px;
    }
    
    .postbox__meta {
        flex-direction: column;
        gap: 10px;
    }
    
    .sidebar__widget-content {
        padding: 20px;
    }
    
    .tp-newsletter-input {
        flex-direction: column;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Blog search functionality
    const searchForm = document.querySelector('.sidebar__search form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const searchTerm = this.querySelector('input[name="search"]').value.toLowerCase();
            
            // Simple client-side search (you can enhance this with server-side search)
            const blogPosts = document.querySelectorAll('.postbox__item');
            blogPosts.forEach(post => {
                const title = post.querySelector('.postbox__title').textContent.toLowerCase();
                const content = post.querySelector('.postbox__text').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || content.includes(searchTerm) || searchTerm === '') {
                    post.style.display = 'block';
                } else {
                    post.style.display = 'none';
                }
            });
        });
    }
    
    // Newsletter subscription
    const newsletterForm = document.querySelector('.tp-newsletter-widget form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[name="email"]').value;
            
            // Here you would typically send the email to your backend
            alert('Thank you for subscribing! We\'ll keep you updated.');
            this.reset();
        });
    }
    
    // Smooth scrolling for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endpush