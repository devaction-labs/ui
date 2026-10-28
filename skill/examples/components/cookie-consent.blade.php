{{-- Cookie consent: cookie notice with accept all, necessary only or choice by category — see references/cookie-consent.md --}}
{{-- Main usage: notice in the page flow with a privacy policy link --}}
<x-ui::cookie-consent policy="#" :fixed="false" force />

{{-- Variation: custom categories, with the choice read on the server --}}
@php
    $categories = [
        'analytics' => ['Analytics', 'Pages visited, without identifying you.'],
        'marketing' => ['Marketing', 'Ads shown on other sites.'],
    ];
    $consent = json_decode(request()->cookie('ui_cookie_consent', '{}'), true) ?? [];
@endphp

<x-ui::cookie-consent
    :categories="$categories"
    policy="#"
    heading="We use cookies to improve the CRM."
    :fixed="false"
    force
    x-on:cookie-consent-updated="console.log($event.detail)"
>
    @if ($consent['analytics'] ?? false)
        Analytics is on for this browser.
    @else
        You choose which cookies to allow.
    @endif
</x-ui::cookie-consent>
