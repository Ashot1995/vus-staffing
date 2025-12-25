@php
    $items = $items ?? [];
    $breadcrumbData = [];
    foreach ($items as $index => $item) {
        $breadcrumbData[] = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $item['name'],
            'item' => $item['url']
        ];
    }
    $jsonLd = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $breadcrumbData
    ];
    $jsonData = json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp
@if(!empty($items))
<script type="application/ld+json">
{!! $jsonData !!}
</script>
@endif
