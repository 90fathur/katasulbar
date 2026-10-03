{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toIso8601String() }}</lastmod>
        <changefreq>always</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('video.index') }}</loc>
        <lastmod>{{ now()->toIso8601String() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    @foreach($categories as $cat)
        <url>
            <loc>{{ route('category.show', $cat->slug) }}</loc>
            <lastmod>{{ $cat->updated_at->toIso8601String() }}</lastmod>
            <changefreq>hourly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
    @foreach($articles as $art)
        <url>
            <loc>{{ route('news.show', $art->slug) }}</loc>
            <lastmod>{{ $art->updated_at->toIso8601String() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach
    @foreach($videos as $vid)
        <url>
            <loc>{{ route('video.show', $vid->slug) }}</loc>
            <lastmod>{{ $vid->updated_at->toIso8601String() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach
    @foreach($pages as $pg)
        <url>
            <loc>{{ route('page.show', $pg->slug) }}</loc>
            <lastmod>{{ $pg->updated_at->toIso8601String() }}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.5</priority>
        </url>
    @endforeach
</urlset>
