@extends('components.layouts.app')
@section('PageTitle', $page->title ?? 'Frequently Asked Questions')

@section('pageContent')

@php
    // Get FAQ data from page or use defaults
    $faqData = [];
    
    // Try dynamic_tables first (main FAQ data source)
    if (!empty($page->dynamic_tables)) {
        $data = is_string($page->dynamic_tables) ? json_decode($page->dynamic_tables, true) : $page->dynamic_tables;
        if (is_array($data) && !empty($data)) {
            $faqData = $data;
        }
    }
    
    // Fallback to polls_surveys
    if (empty($faqData) && !empty($page->polls_surveys)) {
        $data = is_string($page->polls_surveys) ? json_decode($page->polls_surveys, true) : $page->polls_surveys;
        if (is_array($data) && !empty($data)) {
            $faqData = $data;
        }
    }
    
    // Default FAQ structure if no dynamic content
    if (empty($faqData)) {
        $faqData = [
            [
                'category' => 'General Information',
                'icon' => 'fa-solid fa-info-circle',
                'questions' => [
                    [
                        'question' => 'What services do you offer?',
                        'answer' => 'We offer a comprehensive range of services designed to meet your needs. Our team of experts is dedicated to providing high-quality solutions tailored to your specific requirements.'
                    ],
                    [
                        'question' => 'How can I contact customer support?',
                        'answer' => 'You can reach our customer support team through multiple channels: email, phone, or live chat. Our support team is available 24/7 to assist you.'
                    ],
                    [
                        'question' => 'What are your business hours?',
                        'answer' => 'Our business hours are Monday through Friday, 9:00 AM to 6:00 PM. However, our online services are available 24/7.'
                    ]
                ]
            ],
            [
                'category' => 'Services & Pricing',
                'icon' => 'fa-solid fa-dollar-sign',
                'questions' => [
                    [
                        'question' => 'What is your pricing structure?',
                        'answer' => 'Our pricing is competitive and transparent. We offer flexible packages to suit different budgets and requirements.'
                    ],
                    [
                        'question' => 'Do you offer refunds?',
                        'answer' => 'Yes, we have a comprehensive refund policy. If you are not satisfied with our services, you may be eligible for a refund within 30 days.'
                    ]
                ]
            ]
        ];
    }
    
    // Normalize data structure
    foreach ($faqData as $key => $category) {
        if (!isset($category['questions']) || !is_array($category['questions'])) {
            $faqData[$key]['questions'] = [];
        }
        if (!isset($category['category'])) {
            $faqData[$key]['category'] = 'Category ' . ($key + 1);
        }
        if (!isset($category['icon'])) {
            $faqData[$key]['icon'] = 'fa-solid fa-folder';
        }
    }
    
    // Hero content
    $heroContent = [
        'title' => $page->hero_title ?? $page->title ?? 'Frequently Asked Questions',
        'subtitle' => $page->hero_subtitle ?? 'Help Center',
        'description' => $page->excerpt ?? $page->seo_description ?? 'Find answers to the most commonly asked questions about our services and get the help you need quickly and easily.',
        'button_text' => $page->hero_button_text ?? null,
        'button_url' => $page->hero_button_url ?? null,
        'background' => $page->hero_bg ?? $page->featured_image ?? null
    ];
    
    // Support info
    $supportInfo = [
        'title' => $page->contact_form_subject ?? 'Need More Help?',
        'subtitle' => 'Talk to an expert',
        'phone' => '+1 (555) 123-4567',
        'email' => $page->contact_form_email ?? 'support@example.com'
    ];
    
    // Check if custom CSS/JS is provided
    $hasCustomCSS = !empty($page->custom_css);
    $hasCustomJS = !empty($page->custom_js);
@endphp

<main>
    {{-- HERO SECTION --}}
    <section class="tp-faq-hero-area">
        <div class="tp-faq-hero-bg"
             @if($heroContent['background'])
                 style="background-image: url('{{ $heroContent['background'] }}');"
             @else
                 style="background-image: url('{{ asset('MainAssets/img/breadcrumb/breadcrumb-bg.jpg') }}');"
             @endif>
            <div class="tp-faq-hero-overlay"></div>
            
            {{-- Hero Decorative Elements --}}
            <div class="tp-faq-hero-shape-1">
                <img src="{{ asset('MainAssets/img/hero/hero-shape-1.png') }}" alt="Shape" onerror="this.style.display='none'">
            </div>
            <div class="tp-faq-hero-shape-2">
                <img src="{{ asset('MainAssets/img/hero/hero-shape-2.png') }}" alt="Shape" onerror="this.style.display='none'">
            </div>
            
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-10 col-lg-12">
                        <div class="tp-faq-hero-content text-center">
                            {{-- Hero Subtitle/Badge --}}
                            <div class="tp-faq-hero-subtitle">
                                <span class="tp-faq-hero-subtitle-icon">
                                    <i class="fa-solid fa-question-circle"></i>
                                </span>
                                <span class="tp-faq-hero-subtitle-text">
                                    {{ $heroContent['subtitle'] }}
                                </span>
                            </div>
                            
                            {{-- Hero Title --}}
                            <h1 class="tp-faq-hero-title">
                                {{ $heroContent['title'] }}
                            </h1>
                            
                            {{-- Hero Description --}}
                            <p class="tp-faq-hero-description">
                                {{ $heroContent['description'] }}
                            </p>
                            
                            {{-- Hero Button (if configured) --}}
                            @if($heroContent['button_text'] && $heroContent['button_url'])
                                <div class="tp-faq-hero-btn">
                                    <a href="{{ $heroContent['button_url'] }}" class="tp-btn tp-btn-lg">
                                        {{ $heroContent['button_text'] }}
                                        <i class="fa-solid fa-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            @endif
                            
                            {{-- Breadcrumb Navigation --}}
                            <div class="tp-faq-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb justify-content-center">
                                        <li class="breadcrumb-item">
                                            <a href="{{ url('/') }}">
                                                <i class="fa-solid fa-home me-1"></i>
                                                Home
                                            </a>
                                        </li>
                                        @if(isset($page->parent_id) && $page->parent_id)
                                            <li class="breadcrumb-item">
                                                <a href="#">Parent Page</a>
                                            </li>
                                        @endif
                                        <li class="breadcrumb-item active" aria-current="page">
                                            {{ $page->title ?? 'FAQ' }}
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                            
                            {{-- Quick Stats (if available) --}}
                            @if(is_array($faqData) && count($faqData) > 0)
                                <div class="tp-faq-hero-stats">
                                    <div class="row g-3 justify-content-center">
                                        <div class="col-md-4 col-sm-6">
                                            <div class="tp-faq-stat-item">
                                                <div class="tp-faq-stat-number">
                                                    {{ count($faqData) }}
                                                </div>
                                                <div class="tp-faq-stat-label">Categories</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <div class="tp-faq-stat-item">
                                                <div class="tp-faq-stat-number">
                                                    {{ collect($faqData)->sum(function($category) { return is_array($category['questions'] ?? []) ? count($category['questions']) : 0; }) }}
                                                </div>
                                                <div class="tp-faq-stat-label">Questions</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="tp-faq-stat-item">
                                                <div class="tp-faq-stat-number">24/7</div>
                                                <div class="tp-faq-stat-label">Support</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ SEARCH SECTION --}}
    <section class="tp-faq-search-area pt-80 pb-40">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 offset-xl-2">
                    <div class="tp-faq-search-wrapper">
                        <div class="tp-faq-search-box">
                            <div class="tp-faq-search-input">
                                <input type="text" id="faqSearch" placeholder="Search for answers..." autocomplete="off">
                                <button type="button" class="tp-faq-search-btn">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                            <div class="tp-faq-search-suggestions">
                                <span class="tp-faq-search-label">Popular searches:</span>
                                <button class="tp-faq-search-tag" data-search="pricing">Pricing</button>
                                <button class="tp-faq-search-tag" data-search="support">Support</button>
                                <button class="tp-faq-search-tag" data-search="refund">Refund</button>
                                <button class="tp-faq-search-tag" data-search="account">Account</button>
                            </div>
                        </div>
                        <div class="tp-faq-search-results" id="searchResults" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ MAIN CONTENT AREA --}}
    <section class="tp-faq-content-area pt-40 pb-120">
        <div class="container">
            {{-- FAQ Categories Navigation --}}
            @if(is_array($faqData) && count($faqData) > 1)
                <div class="row">
                    <div class="col-xl-12">
                        <div class="tp-faq-categories mb-60">
                            <div class="tp-faq-categories-nav">
                                <button class="tp-faq-category-btn active" data-category="all">
                                    <i class="fa-solid fa-list"></i>
                                    <span>All Categories</span>
                                    <span class="tp-faq-count">({{ collect($faqData)->sum(function($category) { return is_array($category['questions'] ?? []) ? count($category['questions']) : 0; }) }})</span>
                                </button>
                                @foreach($faqData as $index => $category)
                                    @if(is_array($category))
                                        <button class="tp-faq-category-btn" data-category="category-{{ $index }}">
                                            <i class="{{ $category['icon'] ?? 'fa-solid fa-folder' }}"></i>
                                            <span>{{ $category['category'] ?? 'Category' }}</span>
                                            <span class="tp-faq-count">({{ is_array($category['questions'] ?? []) ? count($category['questions']) : 0 }})</span>
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                {{-- FAQ Content --}}
                <div class="col-xl-8 col-lg-8">
                    <div class="tp-faq-content-wrapper">
                        @if(is_array($faqData))
                            @foreach($faqData as $categoryIndex => $category)
                                @if(is_array($category) && isset($category['questions']) && is_array($category['questions']))
                                    <div class="tp-faq-category-section" data-category="category-{{ $categoryIndex }}">
                                        <div class="tp-faq-category-header">
                                            <div class="tp-faq-category-icon">
                                                <i class="{{ $category['icon'] ?? 'fa-solid fa-folder' }}"></i>
                                            </div>
                                            <div class="tp-faq-category-info">
                                                <h3 class="tp-faq-category-title">{{ $category['category'] ?? 'Category' }}</h3>
                                                <span class="tp-faq-category-count">{{ is_array($category['questions'] ?? []) ? count($category['questions']) : 0 }} questions</span>
                                            </div>
                                        </div>
                                        
                                        <div class="tp-faq-accordion">
                                            <div class="accordion" id="faqAccordion{{ $categoryIndex }}">
                                                @foreach($category['questions'] as $questionIndex => $faq)
                                                    @if(is_array($faq) && isset($faq['question']) && isset($faq['answer']))
                                                        @php
                                                            $accordionId = "collapse{$categoryIndex}_{$questionIndex}";
                                                            $isFirst = $categoryIndex === 0 && $questionIndex === 0;
                                                        @endphp
                                                        <div class="tp-faq-accordion-item">
                                                            <h2 class="tp-faq-accordion-header">
                                                                <button class="tp-faq-accordion-button {{ $isFirst ? '' : 'collapsed' }}" 
                                                                        type="button" 
                                                                        data-bs-toggle="collapse" 
                                                                        data-bs-target="#{{ $accordionId }}" 
                                                                        aria-expanded="{{ $isFirst ? 'true' : 'false' }}" 
                                                                        aria-controls="{{ $accordionId }}">
                                                                    <span class="tp-faq-question-text">{{ $faq['question'] }}</span>
                                                                    <span class="tp-faq-accordion-icon">
                                                                        <i class="fa-solid fa-plus"></i>
                                                                    </span>
                                                                </button>
                                                            </h2>
                                                            <div id="{{ $accordionId }}" 
                                                                 class="accordion-collapse collapse {{ $isFirst ? 'show' : '' }}" 
                                                                 data-bs-parent="#faqAccordion{{ $categoryIndex }}">
                                                                <div class="tp-faq-accordion-body">
                                                                    <div class="tp-faq-answer">
                                                                        {!! nl2br(e($faq['answer'])) !!}
                                                                    </div>
                                                                    <div class="tp-faq-helpful">
                                                                        <span class="tp-faq-helpful-text">Was this helpful?</span>
                                                                        <div class="tp-faq-helpful-buttons">
                                                                            <button class="tp-faq-helpful-btn tp-faq-helpful-yes" data-helpful="yes">
                                                                                <i class="fa-solid fa-thumbs-up"></i>
                                                                                Yes
                                                                            </button>
                                                                            <button class="tp-faq-helpful-btn tp-faq-helpful-no" data-helpful="no">
                                                                                <i class="fa-solid fa-thumbs-down"></i>
                                                                                No
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            {{-- Fallback when no FAQ data is available --}}
                            <div class="tp-faq-no-data">
                                <div class="text-center py-5">
                                    <i class="fa-solid fa-question-circle fa-3x text-muted mb-3"></i>
                                    <h4>No FAQ Available</h4>
                                    <p class="text-muted">FAQ content will be displayed here once it's configured.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- FAQ Sidebar --}}
                <div class="col-xl-4 col-lg-4">
                    <div class="tp-faq-sidebar">
                        {{-- Support Contact Card --}}
                        <div class="tp-faq-sidebar-item tp-faq-support-card">
                            <div class="tp-faq-support-content">
                                <div class="tp-faq-support-icon">
                                    <i class="fa-solid fa-headset"></i>
                                </div>
                                <div class="tp-faq-support-info">
                                    <h4 class="tp-faq-support-title">{{ $supportInfo['title'] }}</h4>
                                    <p class="tp-faq-support-subtitle">{{ $supportInfo['subtitle'] }}</p>
                                    <div class="tp-faq-support-details">
                                        <div class="tp-faq-support-detail">
                                            <i class="fa-solid fa-phone"></i>
                                            <a href="tel:{{ str_replace([' ', '(', ')', '-'], '', $supportInfo['phone']) }}">{{ $supportInfo['phone'] }}</a>
                                        </div>
                                        <div class="tp-faq-support-detail">
                                            <i class="fa-solid fa-envelope"></i>
                                            <a href="mailto:{{ $supportInfo['email'] }}">{{ $supportInfo['email'] }}</a>
                                        </div>
                                    </div>
                                    <div class="tp-faq-support-btn">
                                        <a href="#" class="tp-btn tp-btn-sm">Contact Support</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Quick Links --}}
                        <div class="tp-faq-sidebar-item tp-faq-quick-links">
                            <h4 class="tp-faq-sidebar-title">Quick Links</h4>
                            <ul class="tp-faq-quick-links-list">
                                <li><a href="#"><i class="fa-solid fa-file-text"></i> Documentation</a></li>
                                <li><a href="#"><i class="fa-solid fa-video"></i> Video Tutorials</a></li>
                                <li><a href="#"><i class="fa-solid fa-comments"></i> Community Forum</a></li>
                                <li><a href="#"><i class="fa-solid fa-ticket"></i> Submit a Ticket</a></li>
                            </ul>
                        </div>

                        {{-- Popular Articles --}}
                        <div class="tp-faq-sidebar-item tp-faq-popular-articles">
                            <h4 class="tp-faq-sidebar-title">Popular Articles</h4>
                            <div class="tp-faq-popular-list">
                                <div class="tp-faq-popular-item">
                                    <h5><a href="#">Getting Started Guide</a></h5>
                                    <span class="tp-faq-popular-views">1,234 views</span>
                                </div>
                                <div class="tp-faq-popular-item">
                                    <h5><a href="#">Account Setup Instructions</a></h5>
                                    <span class="tp-faq-popular-views">987 views</span>
                                </div>
                                <div class="tp-faq-popular-item">
                                    <h5><a href="#">Billing and Payment FAQ</a></h5>
                                    <span class="tp-faq-popular-views">756 views</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ADDITIONAL CONTENT SECTION --}}
    @if(!empty($page->body) && !preg_match('/<(?:strong|b)>.*?<\/(?:strong|b)>/', $page->body))
        <section class="tp-faq-additional-content pt-80 pb-40">
            <div class="container">
                <div class="row">
                    <div class="col-xl-10 offset-xl-1">
                        <div class="tp-faq-additional-wrapper">
                            <div class="tp-faq-additional-content">
                                {!! $page->body !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- CONTACT SECTION --}}
    @if($page->contact_form_enabled)
        <section class="tp-faq-contact-area pt-80 pb-120" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 offset-xl-2">
                        <div class="tp-faq-contact-wrapper">
                            <div class="tp-section-title-wrapper text-center mb-50">
                                <div class="tp-section-subtitle">
                                    <span class="tp-section-subtitle-icon">
                                        <i class="fa-solid fa-envelope"></i>
                                    </span>
                                    <span class="tp-section-subtitle-text">Still Need Help?</span>
                                </div>
                                <h3 class="tp-section-title">{{ $supportInfo['title'] }}</h3>
                                <div class="tp-section-title-line">
                                    <span></span>
                                </div>
                                <p class="tp-section-description">If you couldn't find the answer you're looking for, don't hesitate to reach out to our support team.</p>
                            </div>
                            <div class="tp-faq-contact-form">
                                <form action="{{ route('contact.submit', $page->slug ?? 'faq') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="page_id" value="{{ $page->page_id }}">
                                    <input type="hidden" name="form_type" value="faq_contact">
                                    
                                    <div class="row">
                                        @if(is_array($page->contact_form_fields) && !empty($page->contact_form_fields))
                                            @foreach($page->contact_form_fields as $field)
                                                @if($field['type'] === 'text' || $field['type'] === 'email')
                                                    <div class="col-md-6">
                                                        <div class="tp-faq-contact-input mb-20">
                                                            <input type="{{ $field['type'] }}" 
                                                                   name="{{ $field['name'] }}" 
                                                                   placeholder="{{ $field['label'] }}" 
                                                                   {{ $field['required'] ? 'required' : '' }}>
                                                        </div>
                                                    </div>
                                                @elseif($field['type'] === 'textarea')
                                                    <div class="col-12">
                                                        <div class="tp-faq-contact-input mb-30">
                                                            <textarea name="{{ $field['name'] }}" 
                                                                      placeholder="{{ $field['label'] }}" 
                                                                      {{ $field['required'] ? 'required' : '' }}></textarea>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-12">
                                                        <div class="tp-faq-contact-input mb-20">
                                                            <input type="{{ $field['type'] }}" 
                                                                   name="{{ $field['name'] }}" 
                                                                   placeholder="{{ $field['label'] }}" 
                                                                   {{ $field['required'] ? 'required' : '' }}>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            {{-- Default form fields --}}
                                            <div class="col-md-6">
                                                <div class="tp-faq-contact-input mb-20">
                                                    <input type="text" name="name" placeholder="Your Name" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="tp-faq-contact-input mb-20">
                                                    <input type="email" name="email" placeholder="Your Email" required>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="tp-faq-contact-input mb-20">
                                                    <input type="text" name="subject" placeholder="Subject" value="{{ $page->contact_form_subject ?? 'FAQ Inquiry' }}">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="tp-faq-contact-input mb-30">
                                                    <textarea name="message" placeholder="Your Message" required></textarea>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <div class="col-12">
                                            <div class="tp-faq-contact-btn text-center">
                                                <button type="submit" class="tp-btn">Send Message</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
</main>

@endsection

{{-- Debug Section (Remove in production) --}}
@if(config('app.debug'))
    <div class="debug-info" style="background: #f8f9fa; padding: 20px; margin: 20px; border-radius: 8px; font-family: monospace; font-size: 12px;">
        <h4>🔧 FAQ Debug Panel</h4>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin: 15px 0;">
            <div>
                <strong>Data Sources:</strong><br>
                Dynamic Tables: {{ !empty($page->dynamic_tables) && $page->dynamic_tables !== '[]' ? '✅ Has Data' : '❌ Empty' }}<br>
                Polls/Surveys: {{ !empty($page->polls_surveys) && $page->polls_surveys !== '[]' ? '✅ Has Data' : '❌ Empty' }}<br>
                Body Content: {{ !empty($page->body) ? '✅ Has Content' : '❌ Empty' }}
            </div>
            <div>
                <strong>Current FAQ:</strong><br>
                Categories: {{ count($faqData) }}<br>
                Total Questions: {{ collect($faqData)->sum(fn($cat) => count($cat['questions'] ?? [])) }}<br>
                Source: {{ (!empty($page->dynamic_tables) && $page->dynamic_tables !== '[]') ? 'Dynamic' : 'Default' }}
            </div>
        </div>
        
        <details style="margin-top: 15px; background: #e8f5e8; padding: 15px; border-radius: 8px;">
            <summary style="font-weight: bold; color: #155724; cursor: pointer;">📋 Add Your FAQ Content</summary>
            <div style="margin-top: 15px;">
                <p><strong>To add your own FAQ content:</strong></p>
                <ol style="margin: 10px 0; padding-left: 20px;">
                    <li>Copy the JSON below</li>
                    <li>Edit your page</li>
                    <li>Paste in "Dynamic Tables" field</li>
                    <li>Replace with your questions</li>
                    <li>Save the page</li>
                </ol>
                
                <textarea readonly onclick="this.select()" style="width: 100%; height: 180px; font-family: monospace; font-size: 11px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin-top: 10px;">[
  {
    "category": "Your Category Name",
    "icon": "fa-solid fa-info-circle",
    "questions": [
      {
        "question": "Your first question?",
        "answer": "Your detailed answer here."
      },
      {
        "question": "Your second question?",
        "answer": "Another detailed answer."
      }
    ]
  }
]</textarea>
                
                <div style="margin-top: 10px; padding: 8px; background: #fff3cd; border-radius: 4px; font-size: 11px;">
                    <strong>Icons:</strong> fa-solid fa-info-circle, fa-solid fa-dollar-sign, fa-solid fa-cog, fa-solid fa-user, fa-solid fa-phone
                </div>
            </div>
        </details>
    </div>
@endif

@push('styles')
<style>
/* FAQ Template Styles */

/* Hero Section */
.tp-faq-hero-area {
    position: relative;
    min-height: 600px;
    overflow: hidden;
}

.tp-faq-hero-bg {
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    min-height: 600px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
}

.tp-faq-hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(30, 126, 52, 0.85) 0%, rgba(21, 87, 36, 0.9) 100%);
    z-index: 1;
}

/* Fallback background for hero section */
.tp-faq-hero-bg::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
    z-index: 0;
}

/* Hero Decorative Shapes */
.tp-faq-hero-shape-1 {
    position: absolute;
    top: 10%;
    left: 5%;
    z-index: 1;
    opacity: 0.1;
    animation: float 6s ease-in-out infinite;
}

.tp-faq-hero-shape-2 {
    position: absolute;
    bottom: 10%;
    right: 5%;
    z-index: 1;
    opacity: 0.1;
    animation: float 8s ease-in-out infinite reverse;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.tp-faq-hero-content {
    position: relative;
    z-index: 2;
    color: #fff;
    padding: 80px 0;
    width: 100%;
}

.tp-faq-hero-subtitle {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    background: rgba(255, 255, 255, 0.15);
    padding: 12px 30px;
    border-radius: 50px;
    margin-bottom: 30px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(15px);
    transition: all 0.3s ease;
    animation: slideInDown 0.8s ease-out;
    margin-bottom: 30px;
}

.tp-faq-hero-subtitle:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

.tp-faq-hero-subtitle-icon {
    color: #ffc107;
    font-size: 1.3rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.tp-faq-hero-subtitle-text {
    font-weight: 600;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.tp-faq-hero-title {
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 700;
    margin-bottom: 30px;
    line-height: 1.1;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    animation: slideInUp 0.8s ease-out 0.2s both;
    word-wrap: break-word;
}

.tp-faq-hero-description {
    font-size: clamp(1rem, 2.5vw, 1.3rem);
    margin-bottom: 40px;
    opacity: 0.95;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.6;
    animation: slideInUp 0.8s ease-out 0.4s both;
    padding: 0 15px;
}

.tp-faq-hero-btn {
    margin-bottom: 40px;
    animation: slideInUp 0.8s ease-out 0.6s both;
}

.tp-faq-hero-btn .tp-btn {
    background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
    border: none;
    padding: 15px 35px;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 50px;
    color: #333;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(255, 193, 7, 0.3);
}

.tp-faq-hero-btn .tp-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(255, 193, 7, 0.4);
    color: #333;
}

.tp-faq-breadcrumb {
    margin-bottom: 40px;
    animation: slideInUp 0.8s ease-out 0.8s both;
}

.tp-faq-breadcrumb .breadcrumb {
    background: rgba(255, 255, 255, 0.1);
    padding: 12px 25px;
    border-radius: 50px;
    margin: 0 auto;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    display: inline-flex;
    align-items: center;
}

.tp-faq-breadcrumb .breadcrumb-item {
    font-size: 0.95rem;
}

.tp-faq-breadcrumb .breadcrumb-item a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: color 0.3s ease;
    display: flex;
    align-items: center;
    gap: 5px;
}

.tp-faq-breadcrumb .breadcrumb-item a:hover {
    color: #ffc107;
}

.tp-faq-breadcrumb .breadcrumb-item.active {
    color: #ffc107;
    font-weight: 500;
}

.tp-faq-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: rgba(255, 255, 255, 0.5);
    font-weight: bold;
}

/* Hero Stats */
.tp-faq-hero-stats {
    margin-top: 50px;
    animation: slideInUp 0.8s ease-out 1s both;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

.tp-faq-stat-item {
    text-align: center;
    padding: 20px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    margin-bottom: 20px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.tp-faq-stat-item:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateY(-5px);
}

.tp-faq-stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #ffc107;
    margin-bottom: 8px;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
}

.tp-faq-stat-label {
    font-size: 1rem;
    font-weight: 500;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Animations */
@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Search Section */
.tp-faq-search-wrapper {
    max-width: 700px;
    margin: 0 auto;
}

.tp-faq-search-box {
    background: #fff;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
    border-top: 4px solid #1e7e34;
}

.tp-faq-search-input {
    position: relative;
    margin-bottom: 20px;
}

.tp-faq-search-input input {
    width: 100%;
    padding: 18px 60px 18px 20px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.tp-faq-search-input input:focus {
    outline: none;
    border-color: #1e7e34;
    box-shadow: 0 0 0 3px rgba(30, 126, 52, 0.1);
    background: #fff;
}

.tp-faq-search-btn {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    width: 45px;
    height: 45px;
    border: none;
    background: #1e7e34;
    color: #fff;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.tp-faq-search-btn:hover {
    background: #155724;
    transform: translateY(-50%) scale(1.05);
}

.tp-faq-search-suggestions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.tp-faq-search-label {
    font-size: 0.9rem;
    color: #666;
    font-weight: 500;
}

.tp-faq-search-tag {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    padding: 6px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
}

.tp-faq-search-tag:hover {
    background: #1e7e34;
    color: #fff;
    border-color: #1e7e34;
}

.tp-faq-search-results {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    max-height: 400px;
    overflow-y: auto;
}

/* Categories Navigation */
.tp-faq-categories {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
}

.tp-faq-categories-nav {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    justify-content: center;
}

.tp-faq-category-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border: 2px solid #e9ecef;
    background: #fff;
    border-radius: 25px;
    color: #666;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.tp-faq-category-btn:hover,
.tp-faq-category-btn.active {
    background: #1e7e34;
    border-color: #1e7e34;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(30, 126, 52, 0.3);
}

.tp-faq-count {
    background: rgba(255, 255, 255, 0.2);
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
}

.tp-faq-category-btn.active .tp-faq-count {
    background: rgba(255, 255, 255, 0.2);
}

/* FAQ Content */
.tp-faq-content-wrapper {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.tp-faq-category-section {
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.3s ease;
}

.tp-faq-category-section:last-child {
    border-bottom: none;
}

.tp-faq-category-section.hidden {
    display: none;
}

.tp-faq-category-header {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 25px 30px;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-bottom: 1px solid #e9ecef;
}

.tp-faq-category-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.2rem;
}

.tp-faq-category-title {
    font-size: 1.4rem;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.tp-faq-category-count {
    color: #666;
    font-size: 0.9rem;
}

/* Accordion Styles */
.tp-faq-accordion {
    padding: 0;
}

.tp-faq-accordion-item {
    border-bottom: 1px solid #f0f0f0;
}

.tp-faq-accordion-item:last-child {
    border-bottom: none;
}

.tp-faq-accordion-header {
    margin: 0;
}

.tp-faq-accordion-button {
    width: 100%;
    padding: 25px 30px;
    background: #fff;
    border: none;
    text-align: left;
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.tp-faq-accordion-button:hover {
    background: #f8f9fa;
}

.tp-faq-accordion-button:not(.collapsed) {
    background: linear-gradient(135deg, rgba(30, 126, 52, 0.05) 0%, rgba(255, 193, 7, 0.05) 100%);
    border-left: 4px solid #1e7e34;
}

.tp-faq-question-text {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    flex: 1;
    margin-right: 20px;
}

.tp-faq-accordion-icon {
    width: 35px;
    height: 35px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #1e7e34;
    transition: all 0.3s ease;
}

.tp-faq-accordion-button:not(.collapsed) .tp-faq-accordion-icon {
    background: #1e7e34;
    color: #fff;
    transform: rotate(45deg);
}

.tp-faq-accordion-body {
    padding: 0 30px 30px 30px;
    background: #fff;
}

.tp-faq-answer {
    font-size: 1rem;
    line-height: 1.7;
    color: #666;
    margin-bottom: 20px;
}

.tp-faq-helpful {
    display: flex;
    align-items: center;
    gap: 15px;
    padding-top: 20px;
    border-top: 1px solid #f0f0f0;
}

.tp-faq-helpful-text {
    font-size: 0.9rem;
    color: #666;
    font-weight: 500;
}

.tp-faq-helpful-buttons {
    display: flex;
    gap: 10px;
}

.tp-faq-helpful-btn {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 8px 15px;
    border: 1px solid #e9ecef;
    background: #fff;
    border-radius: 20px;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.tp-faq-helpful-yes:hover {
    background: #28a745;
    border-color: #28a745;
    color: #fff;
}

.tp-faq-helpful-no:hover {
    background: #dc3545;
    border-color: #dc3545;
    color: #fff;
}

/* Sidebar Styles */
.tp-faq-sidebar {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.tp-faq-sidebar-item {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.tp-faq-sidebar-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
}

/* Support Card */
.tp-faq-support-card {
    background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
    color: #fff;
}

.tp-faq-support-content {
    padding: 30px;
}

.tp-faq-support-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 20px;
    backdrop-filter: blur(10px);
}

.tp-faq-support-title {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 8px;
}

.tp-faq-support-subtitle {
    opacity: 0.9;
    margin-bottom: 20px;
}

.tp-faq-support-details {
    margin-bottom: 25px;
}

.tp-faq-support-detail {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
    font-size: 0.95rem;
}

.tp-faq-support-detail i {
    width: 18px;
    opacity: 0.8;
}

.tp-faq-support-detail a {
    color: #fff;
    text-decoration: none;
    transition: opacity 0.3s ease;
}

.tp-faq-support-detail a:hover {
    opacity: 0.8;
}

.tp-faq-support-btn .tp-btn {
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: #fff;
    backdrop-filter: blur(10px);
}

.tp-faq-support-btn .tp-btn:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
}

/* Quick Links */
.tp-faq-sidebar-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
    padding: 20px 25px 0 25px;
}

.tp-faq-quick-links-list {
    list-style: none;
    padding: 0 25px 25px 25px;
    margin: 0;
}

.tp-faq-quick-links-list li {
    margin-bottom: 12px;
}

.tp-faq-quick-links-list a {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #666;
    text-decoration: none;
    padding: 10px 0;
    transition: all 0.3s ease;
    border-bottom: 1px solid transparent;
}

.tp-faq-quick-links-list a:hover {
    color: #1e7e34;
    border-bottom-color: #e9ecef;
    padding-left: 10px;
}

.tp-faq-quick-links-list i {
    width: 18px;
    color: #1e7e34;
}

/* Popular Articles */
.tp-faq-popular-list {
    padding: 0 25px 25px 25px;
}

.tp-faq-popular-item {
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
}

.tp-faq-popular-item:last-child {
    border-bottom: none;
}

.tp-faq-popular-item h5 {
    margin: 0 0 5px 0;
    font-size: 1rem;
}

.tp-faq-popular-item a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
}

.tp-faq-popular-item a:hover {
    color: #1e7e34;
}

.tp-faq-popular-views {
    font-size: 0.85rem;
    color: #999;
}

/* Additional Content Section */
.tp-faq-additional-content {
    background: #fff;
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    border-left: 4px solid #1e7e34;
}

.tp-faq-additional-content h1,
.tp-faq-additional-content h2,
.tp-faq-additional-content h3,
.tp-faq-additional-content h4,
.tp-faq-additional-content h5,
.tp-faq-additional-content h6 {
    color: #333;
    margin-bottom: 20px;
    margin-top: 30px;
}

.tp-faq-additional-content h1:first-child,
.tp-faq-additional-content h2:first-child,
.tp-faq-additional-content h3:first-child {
    margin-top: 0;
}

.tp-faq-additional-content p {
    color: #666;
    line-height: 1.7;
    margin-bottom: 20px;
}

.tp-faq-additional-content ul,
.tp-faq-additional-content ol {
    color: #666;
    line-height: 1.7;
    margin-bottom: 20px;
    padding-left: 30px;
}

.tp-faq-additional-content li {
    margin-bottom: 8px;
}

.tp-faq-additional-content a {
    color: #1e7e34;
    text-decoration: none;
    transition: color 0.3s ease;
}

.tp-faq-additional-content a:hover {
    color: #155724;
    text-decoration: underline;
}

.tp-faq-additional-content blockquote {
    background: #f8f9fa;
    border-left: 4px solid #1e7e34;
    padding: 20px;
    margin: 20px 0;
    font-style: italic;
    color: #555;
}

.tp-faq-additional-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 20px 0;
}

.tp-faq-additional-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

.tp-faq-additional-content table th,
.tp-faq-additional-content table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.tp-faq-additional-content table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #333;
}

/* Contact Form */
.tp-faq-contact-wrapper {
    background: #fff;
    padding: 50px;
    border-radius: 20px;
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
    border-top: 4px solid #1e7e34;
}

.tp-faq-contact-input {
    position: relative;
}

.tp-faq-contact-input input,
.tp-faq-contact-input textarea {
    width: 100%;
    padding: 18px 20px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: #f8f9fa;
    font-family: inherit;
}

.tp-faq-contact-input input:focus,
.tp-faq-contact-input textarea:focus {
    outline: none;
    border-color: #1e7e34;
    box-shadow: 0 0 0 3px rgba(30, 126, 52, 0.1);
    background: #fff;
    transform: translateY(-2px);
}

.tp-faq-contact-input textarea {
    min-height: 120px;
    resize: vertical;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .tp-faq-hero-title {
        font-size: 3.5rem;
    }
    
    .tp-faq-hero-description {
        font-size: 1.2rem;
    }
    
    .tp-faq-contact-wrapper {
        padding: 40px 30px;
    }
    
    .tp-faq-hero-bg {
        background-attachment: scroll;
    }
}

@media (max-width: 992px) {
    .tp-faq-hero-area {
        min-height: 550px;
    }
    
    .tp-faq-hero-bg {
        min-height: 550px;
    }
    
    .tp-faq-hero-title {
        font-size: 3rem;
    }
    
    .tp-faq-hero-description {
        font-size: 1.1rem;
    }
    
    .tp-faq-stat-number {
        font-size: 2rem;
    }
    
    .tp-faq-hero-shape-1,
    .tp-faq-hero-shape-2 {
        display: none;
    }
    
    .tp-faq-categories-nav {
        justify-content: flex-start;
        overflow-x: auto;
        padding-bottom: 10px;
    }
    
    .tp-faq-category-btn {
        white-space: nowrap;
        flex-shrink: 0;
    }
    
    .tp-faq-category-header {
        padding: 20px;
    }
    
    .tp-faq-accordion-button {
        padding: 20px;
    }
    
    .tp-faq-accordion-body {
        padding: 0 20px 25px 20px;
    }
    
    .tp-faq-sidebar {
        margin-top: 40px;
    }
}

@media (max-width: 768px) {
    .tp-faq-hero-area {
        min-height: 500px;
    }
    
    .tp-faq-hero-bg {
        min-height: 500px;
        background-attachment: scroll;
    }
    
    .tp-faq-hero-content {
        padding: 50px 0;
    }
    
    .tp-faq-hero-title {
        font-size: 2.5rem;
        margin-bottom: 20px;
        line-height: 1.2;
    }
    
    .tp-faq-hero-description {
        font-size: 1rem;
        margin-bottom: 30px;
        padding: 0 15px;
    }
    
    .tp-faq-hero-subtitle {
        padding: 10px 20px;
        margin-bottom: 20px;
        font-size: 0.9rem;
    }
    
    .tp-faq-hero-btn .tp-btn {
        padding: 12px 25px;
        font-size: 1rem;
    }
    
    .tp-faq-hero-stats {
        margin-top: 30px;
        padding: 0 15px;
    }
    
    .tp-faq-stat-item {
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .tp-faq-stat-number {
        font-size: 1.8rem;
    }
    
    .tp-faq-stat-label {
        font-size: 0.9rem;
    }
    
    .tp-faq-breadcrumb .breadcrumb {
        padding: 8px 15px;
        font-size: 0.85rem;
    }
    
    .tp-faq-search-box {
        padding: 20px;
    }
    
    .tp-faq-search-suggestions {
        justify-content: center;
    }
    
    .tp-faq-categories {
        padding: 20px;
    }
    
    .tp-faq-category-header {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }
    
    .tp-faq-accordion-button {
        padding: 15px;
    }
    
    .tp-faq-question-text {
        font-size: 1rem;
    }
    
    .tp-faq-accordion-body {
        padding: 0 15px 20px 15px;
    }
    
    .tp-faq-contact-wrapper {
        padding: 30px 20px;
    }
    
    .tp-faq-support-content {
        padding: 25px;
    }
    
    .tp-faq-sidebar-title {
        padding: 15px 20px 0 20px;
    }
    
    .tp-faq-quick-links-list {
        padding: 0 20px 20px 20px;
    }
    
    .tp-faq-popular-list {
        padding: 0 20px 20px 20px;
    }
}

@media (max-width: 576px) {
    .tp-faq-hero-area {
        min-height: 450px;
    }
    
    .tp-faq-hero-bg {
        min-height: 450px;
    }
    
    .tp-faq-hero-content {
        padding: 30px 0;
    }
    
    .tp-faq-hero-title {
        font-size: 2rem;
        line-height: 1.2;
    }
    
    .tp-faq-hero-description {
        font-size: 0.95rem;
    }
    
    .tp-faq-hero-subtitle {
        padding: 8px 15px;
        margin-bottom: 15px;
    }
    
    .tp-faq-hero-subtitle-text {
        font-size: 0.85rem;
    }
    
    .tp-faq-breadcrumb .breadcrumb {
        padding: 8px 15px;
        font-size: 0.85rem;
    }
    
    .tp-faq-hero-stats .row {
        margin: 0 -5px;
    }
    
    .tp-faq-hero-stats .col-md-4 {
        padding: 0 5px;
    }
    
    .tp-faq-stat-item {
        padding: 12px;
        margin-bottom: 10px;
    }
    
    .tp-faq-stat-number {
        font-size: 1.5rem;
    }
    
    .tp-faq-stat-label {
        font-size: 0.8rem;
    }
    
    .tp-faq-search-input input {
        padding: 15px 50px 15px 15px;
    }
    
    .tp-faq-search-btn {
        width: 40px;
        height: 40px;
    }
    
    .tp-faq-categories-nav {
        flex-direction: column;
        align-items: stretch;
    }
    
    .tp-faq-category-btn {
        justify-content: center;
    }
    
    .tp-faq-helpful {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}

/* Animation Classes */
.fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.highlight {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.2) 0%, rgba(30, 126, 52, 0.1) 100%);
    border-radius: 8px;
    padding: 2px 6px;
    transition: all 0.3s ease;
}

/* Search Results Styling */
.tp-faq-search-result-item {
    padding: 15px 20px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: all 0.3s ease;
}

.tp-faq-search-result-item:hover {
    background: #f8f9fa;
}

.tp-faq-search-result-item:last-child {
    border-bottom: none;
}

.tp-faq-search-result-question {
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}

.tp-faq-search-result-answer {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.5;
}

.tp-faq-search-result-category {
    font-size: 0.8rem;
    color: #1e7e34;
    font-weight: 500;
    margin-top: 5px;
}

/* Loading States */
.tp-faq-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px;
    color: #666;
}

.tp-faq-loading i {
    animation: spin 1s linear infinite;
    margin-right: 10px;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* No Results State */
.tp-faq-no-results {
    text-align: center;
    padding: 40px 20px;
    color: #666;
}

.tp-faq-no-results i {
    font-size: 3rem;
    color: #ddd;
    margin-bottom: 15px;
}

.tp-faq-no-results h4 {
    margin-bottom: 10px;
    color: #333;
}
</style>
@endpush

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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // FAQ Search Functionality
    const searchInput = document.getElementById('faqSearch');
    const searchResults = document.getElementById('searchResults');
    const searchTags = document.querySelectorAll('.tp-faq-search-tag');
    const categoryButtons = document.querySelectorAll('.tp-faq-category-btn');
    const categorySections = document.querySelectorAll('.tp-faq-category-section');
    const helpfulButtons = document.querySelectorAll('.tp-faq-helpful-btn');
    
    // Collect all FAQ data for search
    const faqData = [];
    categorySections.forEach((section, categoryIndex) => {
        const categoryTitle = section.querySelector('.tp-faq-category-title').textContent;
        const questions = section.querySelectorAll('.tp-faq-accordion-item');
        
        questions.forEach((item, questionIndex) => {
            const question = item.querySelector('.tp-faq-question-text').textContent;
            const answer = item.querySelector('.tp-faq-answer').textContent;
            
            faqData.push({
                categoryIndex,
                questionIndex,
                categoryTitle,
                question,
                answer,
                element: item
            });
        });
    });
    
    // Search functionality
    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim().toLowerCase();
            
            if (query.length === 0) {
                searchResults.style.display = 'none';
                return;
            }
            
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        });
    }
    
    function performSearch(query) {
        const results = faqData.filter(item => 
            item.question.toLowerCase().includes(query) || 
            item.answer.toLowerCase().includes(query) ||
            item.categoryTitle.toLowerCase().includes(query)
        );
        
        displaySearchResults(results, query);
    }
    
    function displaySearchResults(results, query) {
        if (results.length === 0) {
            searchResults.innerHTML = `
                <div class="tp-faq-no-results">
                    <i class="fa-solid fa-search"></i>
                    <h4>No results found</h4>
                    <p>Try searching with different keywords or browse our categories below.</p>
                </div>
            `;
        } else {
            const resultsHTML = results.map(result => {
                const highlightedQuestion = highlightText(result.question, query);
                const highlightedAnswer = highlightText(result.answer.substring(0, 150) + '...', query);
                
                return `
                    <div class="tp-faq-search-result-item" data-category="${result.categoryIndex}" data-question="${result.questionIndex}">
                        <div class="tp-faq-search-result-question">${highlightedQuestion}</div>
                        <div class="tp-faq-search-result-answer">${highlightedAnswer}</div>
                        <div class="tp-faq-search-result-category">${result.categoryTitle}</div>
                    </div>
                `;
            }).join('');
            
            searchResults.innerHTML = resultsHTML;
            
            // Add click handlers to search results
            searchResults.querySelectorAll('.tp-faq-search-result-item').forEach(item => {
                item.addEventListener('click', function() {
                    const categoryIndex = this.dataset.category;
                    const questionIndex = this.dataset.question;
                    
                    // Hide search results
                    searchResults.style.display = 'none';
                    searchInput.value = '';
                    
                    // Show the relevant category
                    showCategory(`category-${categoryIndex}`);
                    
                    // Scroll to and open the specific question
                    setTimeout(() => {
                        const targetAccordion = document.getElementById(`collapse${categoryIndex}_${questionIndex}`);
                        const targetButton = document.querySelector(`[data-bs-target="#collapse${categoryIndex}_${questionIndex}"]`);
                        
                        if (targetButton && targetAccordion) {
                            // Open the accordion if it's not already open
                            if (!targetAccordion.classList.contains('show')) {
                                targetButton.click();
                            }
                            
                            // Scroll to the question
                            targetButton.scrollIntoView({ 
                                behavior: 'smooth', 
                                block: 'center' 
                            });
                            
                            // Add highlight effect
                            const accordionItem = targetButton.closest('.tp-faq-accordion-item');
                            accordionItem.style.background = 'linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(30, 126, 52, 0.05) 100%)';
                            setTimeout(() => {
                                accordionItem.style.background = '';
                            }, 3000);
                        }
                    }, 100);
                });
            });
        }
        
        searchResults.style.display = 'block';
    }
    
    function highlightText(text, query) {
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<span class="highlight">$1</span>');
    }
    
    // Search tags functionality
    searchTags.forEach(tag => {
        tag.addEventListener('click', function() {
            const searchTerm = this.dataset.search;
            if (searchInput) {
                searchInput.value = searchTerm;
                performSearch(searchTerm.toLowerCase());
            }
        });
    });
    
    // Category filtering
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.dataset.category;
            
            // Update active state
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Show/hide categories
            showCategory(category);
            
            // Hide search results
            if (searchResults) {
                searchResults.style.display = 'none';
            }
            if (searchInput) {
                searchInput.value = '';
            }
        });
    });
    
    function showCategory(category) {
        categorySections.forEach(section => {
            if (category === 'all' || section.dataset.category === category) {
                section.classList.remove('hidden');
                section.style.display = 'block';
                // Add fade-in animation
                section.classList.add('fade-in');
            } else {
                section.classList.add('hidden');
                section.style.display = 'none';
            }
        });
    }
    
    // Helpful buttons functionality
    helpfulButtons.forEach(button => {
        button.addEventListener('click', function() {
            const isHelpful = this.dataset.helpful === 'yes';
            const buttonsContainer = this.parentElement;
            
            // Disable all buttons in this container
            buttonsContainer.querySelectorAll('.tp-faq-helpful-btn').forEach(btn => {
                btn.disabled = true;
                btn.style.opacity = '0.5';
            });
            
            // Highlight the clicked button
            this.style.opacity = '1';
            this.style.transform = 'scale(1.1)';
            
            // Show thank you message
            setTimeout(() => {
                const thankYouMsg = document.createElement('span');
                thankYouMsg.textContent = isHelpful ? 'Thank you for your feedback!' : 'Thanks! We\'ll work on improving this answer.';
                thankYouMsg.style.color = '#28a745';
                thankYouMsg.style.fontSize = '0.85rem';
                thankYouMsg.style.fontWeight = '500';
                thankYouMsg.style.marginLeft = '10px';
                
                buttonsContainer.appendChild(thankYouMsg);
            }, 500);
            
            // Here you could send the feedback to your backend
            console.log(`Feedback: ${isHelpful ? 'Helpful' : 'Not helpful'}`);
        });
    });
    
    // Accordion icon animation
    document.addEventListener('shown.bs.collapse', function(e) {
        const button = document.querySelector(`[data-bs-target="#${e.target.id}"]`);
        if (button) {
            const icon = button.querySelector('.tp-faq-accordion-icon i');
            if (icon) {
                icon.style.transform = 'rotate(45deg)';
            }
        }
    });
    
    document.addEventListener('hidden.bs.collapse', function(e) {
        const button = document.querySelector(`[data-bs-target="#${e.target.id}"]`);
        if (button) {
            const icon = button.querySelector('.tp-faq-accordion-icon i');
            if (icon) {
                icon.style.transform = 'rotate(0deg)';
            }
        }
    });
    
    // Auto-hide search results when clicking outside
    document.addEventListener('click', function(e) {
        if (searchInput && searchResults && !searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
    
    // Keyboard navigation for search results
    if (searchInput) {
        searchInput.addEventListener('keydown', function(e) {
            const resultItems = searchResults.querySelectorAll('.tp-faq-search-result-item');
            let currentIndex = -1;
            
            // Find currently highlighted item
            resultItems.forEach((item, index) => {
                if (item.classList.contains('highlighted')) {
                    currentIndex = index;
                }
            });
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                currentIndex = Math.min(currentIndex + 1, resultItems.length - 1);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                currentIndex = Math.max(currentIndex - 1, 0);
            } else if (e.key === 'Enter' && currentIndex >= 0) {
                e.preventDefault();
                resultItems[currentIndex].click();
                return;
            }
            
            // Update highlighting
            resultItems.forEach((item, index) => {
                if (index === currentIndex) {
                    item.classList.add('highlighted');
                    item.style.background = '#f8f9fa';
                } else {
                    item.classList.remove('highlighted');
                    item.style.background = '';
                }
            });
        });
    }
    
    // Initialize: Show all categories by default
    showCategory('all');
    
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